<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Common\CommonConst;
use App\Common\UploadFunc;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailU09;
use App\Models\DependentUsers;
use App\Models\TransferOfficeUsers;
use App\Models\MstBelong;
use App\Models\MstOffice;
use App\Models\Notices;
use App\Models\TransportUsers;
use App\Models\UploadInfoUsers;

class U09Service
{
    public function findbyUserID($id){
        $query = DB::table('users as u')
        ->leftJoin('info_users as i', 'i.id', '=', 'u.info_users_cd')
        ->select(
            'u.id as Staff_id',
            'i.id',
            'i.office_cd',
            'i.belong_cd'
        )
        ->where('u.id',$id)
        ->first();
        return $query;
    }

    public function findTransferUsers($office_cdSearch = null, $belong_cdSearch = null, $name_infoSearch = null)
    {
        $arr = DB::table("transfer_office_users")->select('staff_id')->get();
        $arrIdUser = json_decode(json_encode($arr), true);

        $query = DB::table('users as u')
        ->leftJoin('info_users as i', 'i.id', '=', 'u.info_users_cd')
        ->leftJoin('mst_offices as o', 'o.office_cd', '=', 'i.office_cd')
        ->leftJoin('mst_belong as b', function ($join) {
            $join->on('b.belong_cd', '=', 'i.belong_cd')
                ->on('b.office_cd', '=', 'i.office_cd');
        })
        ->leftJoin('transfer_office_users as trans', 'trans.staff_id','=', 'u.id')
        ->select(
            'u.id as staff_id',
            'i.id as idInfo',
            DB::raw("CONCAT(i.first_name, '　', i.last_name) as name"),
            'i.email',
            'i.office_cd as officeInfo',
            'o.office_name',
            'i.belong_cd as belongInfo',
            'b.belong_name'
        )
        ->where('i.date_join', '<=', date('Y-m-d'))
        ->where(function ($query) {
            $query->where(function ($query) {
                $query->whereNotNull('u.retire_date')
                    ->where('u.retire_date', '>=', date('Y-m-d'));
            })
            ->orWhereNull('u.retire_date');
        })
        ->where('i.work_status',1)
        ->whereNotIn('u.id', $arrIdUser);
        if(!empty($office_cdSearch)){
            $query->where('i.office_cd', $office_cdSearch);
        }
        if(!empty($belong_cdSearch)){
            $query->where('i.belong_cd', $belong_cdSearch);
        }
        if(!empty($name_infoSearch)){
            $query->WhereRaw("CONCAT(i.first_name, '　', i.last_name) like ?", ['%'.$name_infoSearch.'%']);
        }
        $query->orderByRaw("CHAR_LENGTH(i.office_cd)")
        ->orderBy('i.office_cd')
        ->orderByRaw("CHAR_LENGTH(i.belong_cd)")
        ->orderBy('i.belong_cd')
        ->orderByRaw("CONCAT(i.first_name, '　', i.last_name)");
        return $query->get();
    }

    public function saveSession(Request $request){
        $data = $request->all();
        Session::forget("listIDInfoU09");
        $listIDInfo = explode(',', $data['InfoCheck'][0]);
        Session::put("listIDInfoU09", $listIDInfo);
    }

    //u092
    public function getStaffTranfer($arrIdStaff, $isArray = true){
        $query = DB::table('users as u')
        ->leftJoin('info_users as info', 'u.info_users_cd', '=', 'info.id')
        ->leftJoin('mst_offices as o', 'o.office_cd', '=', 'info.office_cd')
        ->leftJoin('mst_belong as bl', function ($join) {
            $join->on('bl.belong_cd', '=', 'info.belong_cd')
                ->on('bl.office_cd', '=', 'info.office_cd');
        });
        if($isArray)
        {
            return $query->whereIn('u.id', $arrIdStaff )
                    ->select(
                        'info.*',
                        'u.id as idStaff',
                        'u.name as fullName',
                        'o.office_name',
                        'bl.belong_name',
                    )->get();
        }
        return $query->where('u.id', $arrIdStaff)
        ->select(
            'info.*',
            'u.id as idStaff',
            'u.name as fullName',
            'u.email',
            'o.office_name',
            'bl.belong_name',
        )->first();
    }

    function saveTransfer($data){
        DB::beginTransaction();
        try {
            foreach ($data['arrId'] as $idStaff) {
                $user = $this->getStaffTranfer($idStaff, false);
                $idInfoTrans = $this->registerUserTrans($user, $data);
                $idRetire = $this->registerUserRetire($user, $data);
                $this->registerTrans($user, $data, $idInfoTrans, $idRetire);
                $this->sendMailTrans($user, $data);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw ($e);
        }
    }

    public function registerTrans($user, $data, $idInfoTrans, $idRetire)
    {
        DB::beginTransaction();
        try {
            $dataToUpdate = [
                'staff_id' => $user->idStaff,
                'office_cd' => $user->office_cd,
                'belong_cd' =>$user->belong_cd,
                'transfer_date' => $data['txtDateJoin_'.$user->idStaff],
                'transfer_office_cd' => $data['cmbOfficeCd_'.$user->idStaff],
                'transfer_belong_cd' => $data['cmbBelongCd_'.$user->idStaff],
                'transfer_info_users_cd' => $idInfoTrans,
                'transfer_info_retire_users' => $idRetire,
                'flag_U11' => 0,
                'flag_U10' => 0,
                'updated_id' => Auth::id(),
                'created_id' => Auth::id(),
                'revision' => 0,
            ];
            TransferOfficeUsers::insert($dataToUpdate);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw ($e);
        }
    }

    public function sendMailTrans($user, $data)
    {
        $u03service = new U03Service();
        $author = DB::table('users')->where('id', Auth::id())->value('name');

        //company old
        $censors = $u03service->getSecondApprove($user->office_cd, $user->belong_cd, 1);
        
        foreach ($censors as $censor) {
            $subject = '転籍前手続きのお願い（'.$user->office_name.'　'.$author.')';
            // send mail censor 1
            Mail::to($censor->email_app2)->send(new SendMailU09($user, $subject, $author, $censor, 'old'));
            // send mes censor 1
            $u03service->sendNotice($censor->id, $user->id, $subject, 'U09');
        }

        //company new
        $censorsNew = $u03service->getSecondApprove($data['cmbOfficeCd_'.$user->idStaff], $data['cmbBelongCd_'.$user->idStaff], 1);
        $officeName = MstOffice::where('office_cd',$data['cmbOfficeCd_'.$user->idStaff])->value('office_name');
        
        foreach ($censorsNew as $censor) {
            $subject = '転籍手続きのお願い（'.$officeName.'　'.$author.')';
            // send mail censor 1 
            Mail::to($censor->email_app2)->send(new SendMailU09($user, $subject, $author, $censor, 'new',$officeName));
            // send mes censor 1
            $this->sendNoticeU09($censor->id, $user, $subject, $data);
        }
    }

    //send message
    public function sendNoticeU09($idStaffEnd, $user, $content, $data)
    {
        DB::beginTransaction();
        try {
            $dataToUpdate = [
                'notices_type' => 'U09',
                'notices_date' => now(),
                'notices_create_user' => Auth::id(),
                'office_short_name' => MstOffice::where('office_cd',$data['cmbOfficeCd_'.$user->idStaff])->value('office_short_name'),
                'belong_name' => MstBelong::where('belong_cd',$data['cmbBelongCd_'.$user->idStaff])->value('belong_name'),
                'notices_content' => $content,
                'info_users_cd' => $user->id,
                'notices_dest_user' => $idStaffEnd,
                'notices_visible' => 1,
                'updated_id' => Auth::id(),
                'created_id' => Auth::id(),
                'revision' => 0,
            ];
            Notices::insert($dataToUpdate);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw ($e);
        }
    }

    public function registerUserTrans($user, $data)
    {
        DB::beginTransaction();
        try {
            $id = DB::table('info_users')->insertGetId([
                'procedure_type' => CommonConst::PROCEDURE_TYPE_U11,
                'date_join' => $data['txtDateJoin_'.$user->idStaff],
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'first_furigana' => $user->first_furigana,
                'last_furigana' => $user->last_furigana,
                'email' => $user->email,
                'confirm_email' => $user->confirm_email,
                'office_cd' => $data['cmbOfficeCd_'.$user->idStaff],
                'belong_cd' => $data['cmbBelongCd_'.$user->idStaff],
                'status_user' => CommonConst::U03_STATUS_SUPERIOR,
                'office_cd_old' => $user->office_cd,
                'belong_cd_old' => $user->belong_cd,
                'work_status' => 1,
                //u117
                'gender' => $user->gender,
                'tel_number' => $user->tel_number,
                'post_code' => $user->post_code,
                'adress1' => $user->adress1,
                'adress2' => $user->adress2,
                'adress3' => $user->adress3,
                'adress_furigana1' => $user->adress_furigana1,
                'adress_furigana2' => $user->adress_furigana2,
                'adress_furigana3' => $user->adress_furigana3,
                'birthday' => $user->birthday,
                'prev_job' => $user->prev_job,
                'my_number' => $user->my_number,
                'contract_class' => $user->contract_class,
                'object_person' => $user->object_person,
                'jion_insurance' => $user->jion_insurance,
                'insurance_date' => $user->insurance_date,
                'insurance_social' => $user->insurance_social,
                'insurance_social_date' => $user->insurance_social_date,
                'bank_code' => $user->bank_code,
                'bank_name' => $user->bank_name,
                'branch_bank_code' => $user->branch_bank_code,
                'branch_bank_name' => $user->branch_bank_name,
                'bank_user_name' => $user->bank_user_name,
                'bank_user_furigana' => $user->bank_user_furigana,
                'deposit_type' => $user->deposit_type,
                'bank_number' => $user->bank_number,
                'transport_type' => $user->transport_type,
            ]);

            $des = DependentUsers::where('info_users_cd', $user->id)->where('ver_his', $user->ver_his)->get();
            foreach($des as $de){
                unset($de->id);
                $newDe = new DependentUsers($de->toArray());
                $newDe->info_users_cd = $id;
                $newDe->ver_his = 0;
                $newDe->save();
            }

            $trans = TransportUsers::where('info_users_cd', $user->id)->where('ver_his', $user->ver_his)->get();
            foreach($trans as $tran){
                unset($tran->id);
                $newTrans = new TransportUsers($tran->toArray());
                $newTrans->info_users_cd = $id;
                $newTrans->ver_his = 0;
                $newTrans->save();
            }

            $ups = UploadInfoUsers::where('info_users_cd', $user->id)
                ->where('upload_type', CommonConst::UPLOAD_TYPE_5)->where('ver_his', $user->ver_his)->get();
            foreach($ups as $up){
                unset($up->id);
                $newUp = new UploadInfoUsers($up->toArray());
                $newUp->info_users_cd = $id;
                $newUp->ver_his = 0;
                $newUp->save();
                $newUp->folder_name = CommonConst::UPLOAD_INFO_USERS . $id . '/' . CommonConst::UPLOAD_TYPE_5 . '/0/' . $newUp->id . '/';
                $newUp->save();
                UploadFunc::copyDirectory($up->folder_name, $newUp->folder_name);
            }
            DB::commit();
            return $id;
        } catch (\Exception $e) {
            DB::rollBack();
            throw ($e);
        }
    }

    public function registerUserRetire($user, $data)
    {
        DB::beginTransaction();
        try {
            $id = DB::table('info_retire_users')->insertGetId([
                'info_users_cd' => $user->id,
                'procedure_type' => CommonConst::PROCEDURE_TYPE_U10,
                'retire_date' => date(CommonConst::DATE_FORMAT_2, strtotime($data['txtDateJoin_'.$user->idStaff]. ' - 1 days')),
                'author_id' => Auth::id(),
                'author_date' => now(),
                'applicant_id' => $user->idStaff,
                'applicant_date' => now(),
                'status_user' => CommonConst::U10_STATUS_1次,
            ]);
            DB::commit();
            return $id;
        } catch (\Exception $e) {
            DB::rollBack();
            throw ($e);
        }
    }
}