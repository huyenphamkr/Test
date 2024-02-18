<?php

namespace App\Services;

use App\Common\CommonFunc;
use App\Models\Car;
use App\Models\CarPayment;
use App\Common\CommonConst;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class U04Service
{
    /**
     * Retrieve data by key search.
     *
     * @param  array  $data
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function findByKeySrc($data)
    {
        $query = CarPayment::select
            (
                'car_payments.*', 
                'cars.car_number as ccar_number', 
                'cars.car_name', 
                'cars.car_model', 
                'cars.grade', 
                'cars.chassis_number', 
                'cars.user_id',
                'cars.car_amount as ccar_amount',
                'cars.billing_subtotal as cbilling_subtotal',
                'cars.car_no',
                'cars.payment_subtotal as cpayment_subtotal',
                'cars.payment_tax',
                'cars.payment_total',
                'cars.benefit',
            )
            ->leftJoin('cars', function ($join) {
                $join->on('car_payments.id', '=', 'cars.id')
                    ->whereNull('cars.deleted_at');
            })
            // ->orderByRaw('LENGTH(car_payments.exhibit_number)');
            ->orderByRaw('CONVERT(car_payments.exhibit_number, SIGNED) asc');
        if (!empty($data)) {
            $query->when($data['txtDate'], function ($query, $src) {
                    $query->where("car_payments.payment_date", $src);
                })
                ->when($data['cmbSrc'] != 0, function ($query) use ($data) {
                    $query->where("car_payments.venue", $data['cmbSrc']);
                });
        }else{
            $query->where("car_payments.payment_date", date(CommonConst::Date_Format_Calendar));
        }
        return $query->paginate(CommonConst::Pagination);
    }

    /**
     * Register car Payment
     *
     * @param  array  $data
     * @return bool
     */
    public function registerCar($data)
    {
        DB::beginTransaction();
        try {
            $result = $this->setRegisCar($data);
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Set register cars.
     *
     * @param  array  $data
     * @return bool
     */
    public function setRegisCar($data)
    {
        $result = false;
        $valueCar = [];
        $arrUpLine = isset($data['hidPaymentLineUp']) ? $data['hidPaymentLineUp'] : null;
        if (!empty($arrUpLine)) {
            $arrId = array_unique($data['hidIdUp']);
            sort($arrId);
            for ($i = 0; $i < count($arrId); $i++) {
                $this->getCarData($data, $arrId[$i], $valueCar);
            }
        }

        if (!empty($valueCar)) {
            $updateCol = [
                "user_id",
                "payment_subtotal",
                "payment_total",
                "benefit",
                "updated_at",
                "updated_id",
                "revision" => DB::raw("revision + 1"),
            ];
            $result = Car::upsert($valueCar, 'id', $updateCol) > 0;
        }
        if ($result) $result = $this->regisCarPayments($data);
        return $result;
    }

    
    /**
     * Get cars data to register.
     *
     * @param  array  $data
     * @param  string  $carId
     * @param  array  $value car
     * @return void
     */
    public function getCarData($data, $carId, &$valueCar)
    {
        $eleNm = "Up";
        $arrUser = $data['cmbSUser' . $eleNm];
        $arrPaymentSubtotal = $data['lblPaymentSubtotal' . $eleNm];
        $arrPaymentTotal = $data['lblPaymentTotal' . $eleNm];
        $arrBenefit = $data['lblBenefit' . $eleNm];
        $arrPaymentLine = $data['hidPaymentLine' . $eleNm];
        $currentId = Auth::id();
        for ($i = 0; $i < count($arrPaymentLine); $i++) {
            $lineTemp = explode('-', $arrPaymentLine[$i]);
            if($carId == $lineTemp[0])
            {
                if (!is_null($arrUser[$i]) || !is_null($arrBenefit[$i])) {
                    $valueCar[] = [
                        'id' => $carId,
                        'register_no' => "",
                        'chassis_number' => "",
                        'car_model' => "",
                        'user_id' => $arrUser[$i],
                        'payment_subtotal' => CommonFunc::chkAndGetMoneyValue($arrPaymentSubtotal[$i]),
                        'payment_total' => CommonFunc::chkAndGetMoneyValue($arrPaymentTotal[$i]),
                        'benefit' => CommonFunc::chkAndGetMoneyValue($arrBenefit[$i]),
                        'updated_at' => Carbon::now(),
                        'updated_id' => $currentId,
                        'revision' => 0,
                    ];
                    break;
                }
            }
        }
    }


    /**
     * Register car_payments
     *
     * @param  array  $data
     * @param  string  $carId
     * @return bool
     */
    public function regisCarPayments($data)
    {
        $result = true;
        $value = [];
        $arrUpLine = isset($data['hidPaymentLineUp']) ? $data['hidPaymentLineUp'] : null;
        if (!empty($arrUpLine)) {
            $arrId = array_unique($data['hidIdUp']);
            sort($arrId);
            for ($i = 0; $i < count($arrId); $i++) {
                $this->getCarPaymentData($data, $arrId[$i], $value, false);
            }
        }

        if (!empty($value)) {
            $updateCol = [
                // "payment_date",
                // "auction_result",
                // "car_number",
                // "venue",
                // "held_number",
                // "exhibit_number",
                // "others",
                "car_amount",
                "consumption_tax",
                "r_deposit",
                // "car_tax",
                "exhibit_fee",
                "closing_fee",
                "re_auction_fee",
                "payment_subtotal",
                "updated_at",
                "updated_id",
                "revision" => DB::raw("revision + 1"),
            ];
            $result = CarPayment::upsert($value, ["id", "line"], $updateCol) > 0;
        }
        return $result;
    }

    /**
     * Get car_payment data to register.
     *
     * @param  array  $data
     * @param  string  $carId
     * @param  array  $value
     * @param  bool  $isInsert
     * @return void
     */
    public function getCarPaymentData($data, $carId, &$value, $isInsert = true)
    {
        $eleNm = ($isInsert) ? "In" : "Up";
        // $arrPaymentDate = $data['txtSPaymentDate' . $eleNm];
        // $arrAuctionResult = $data['txtSAuctionResult' . $eleNm];
        // $arrCarNumber = $data['txtSCarNumber' . $eleNm];
        // $arrVenue = $data['cmbSVenue' . $eleNm];
        // $arrHeldNumber = $data['txtSHeldNumber' . $eleNm];
        // $arrExhibitNumber = $data['txtSExhibitNumber' . $eleNm];
        // $arrOthers = $data['txtSOthers' . $eleNm];
        $arrCarAmount = $data['txtSCarAmount' . $eleNm];
        $arrConTax = $data['txtSConTax' . $eleNm];
        $arrRDeposit = $data['txtSRDeposit' . $eleNm];
        // $arrCarTax = $data['txtSCarTax' . $eleNm];
        $arrExhibitFee = $data['txtSExhibitFee' . $eleNm];
        $arrClosingFee = $data['txtSClosingFee' . $eleNm];
        $arrReAuctionFee = $data['txtSReAuctionFee' . $eleNm];
        $arrPaymentSubtotal = $data['lblSPaymentSubtotal' . $eleNm];
        $arrPaymentLine = $data['hidPaymentLine' . $eleNm];
        $currentId = Auth::id();
        $maxLine = CarPayment::withTrashed()->where('id', $carId)->max('line');
        for ($i = 0; $i < count($arrPaymentLine); $i++) {
            $lineTemp = explode('-', $arrPaymentLine[$i]);
            if($carId == $lineTemp[0])
            {
                if (
                    !is_null($arrCarAmount[$i]) || !is_null($arrConTax[$i]) || !is_null($arrRDeposit[$i])
                    || !is_null($arrExhibitFee[$i]) || !is_null($arrClosingFee[$i]) || !is_null($arrReAuctionFee[$i])
                    || !is_null($arrPaymentSubtotal[$i]) || !$isInsert
                ) {
                    $line = $lineTemp[count($lineTemp) - 1];
                    if ($isInsert) {
                        $maxLine++;
                        $line = $maxLine;
                    }
                    $value[] = [
                        'id' => $carId,
                        'line' => $line,
                        // 'payment_date' => $arrPaymentDate[$i],
                        // 'auction_result' => $arrAuctionResult[$i],
                        // 'car_number' => $arrCarNumber[$i],
                        // 'venue' => $arrVenue[$i],
                        // 'held_number' => CommonFunc::chkAndGetMoneyValue($arrHeldNumber[$i]),
                        // 'exhibit_number' => $arrExhibitNumber[$i],
                        // 'others' => CommonFunc::chkAndGetMoneyValue($arrOthers[$i]),
                        'car_amount' => CommonFunc::chkAndGetMoneyValue($arrCarAmount[$i]),
                        'consumption_tax' => CommonFunc::chkAndGetMoneyValue($arrConTax[$i]),
                        'r_deposit' => CommonFunc::chkAndGetMoneyValue($arrRDeposit[$i]),
                        // 'car_tax' => CommonFunc::chkAndGetMoneyValue($arrCarTax[$i]),
                        'exhibit_fee' => CommonFunc::chkAndGetMoneyValue($arrExhibitFee[$i]),
                        'closing_fee' => CommonFunc::chkAndGetMoneyValue($arrClosingFee[$i]),
                        're_auction_fee' => CommonFunc::chkAndGetMoneyValue($arrReAuctionFee[$i]),
                        'payment_subtotal' => CommonFunc::chkAndGetMoneyValue($arrPaymentSubtotal[$i]),
                        'created_at' => Carbon::now(),
                        'created_id' => $currentId,
                        'updated_at' => Carbon::now(),
                        'updated_id' => $currentId,
                        'revision' => 0,
                    ];
                }
            }
        }
    }
}
