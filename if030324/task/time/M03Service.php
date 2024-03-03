<?php

namespace App\Services;

use App\Common\CommonConst;
use App\Common\UploadFunc;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Notices;
use App\Models\ProceduresUsers;
use App\Models\TransportUsers;

class M03Service
{
    public function findProByKey($id){
        $query = DB::table('info_users as inf')
            ->select('mst.id', 'mst.name')
            ->selectRaw('CASE WHEN FIND_IN_SET(mst.id, inf.procedure_items) > 0 THEN TRUE ELSE FALSE END AS selected')
            ->leftJoin('procedure_offices as ofc', 'inf.office_cd', '=', 'ofc.office_cd')
            ->leftJoin('mst_procedure_items as mst', 'ofc.procedure_id', '=', 'mst.id')
            ->where('inf.id', $id)
            ->orderBy('mst.id');
        return $query->get();
    }

//m03-1
    public function findStaffAll($data, $offiCdCur = null, $mode = null){
        $query = DB::table('users as u')
        ->leftJoin('info_users as i', 'i.id', '=', 'u.info_users_cd')
        ->leftJoin('mst_offices as o', 'o.office_cd', '=', 'i.office_cd')
        ->leftJoin('mst_belong as b', function ($join) {
            $join->on('b.belong_cd', '=', 'i.belong_cd')
                ->on('b.office_cd', '=', 'i.office_cd');
        })
        ->where('i.date_join', '<=', date('Y-m-d'));
        // ->where(function ($query) {
        //     $query->where(function ($query) {
        //         $query->whereNotNull('u.retire_date')
        //             ->where('u.retire_date', '>=', date('Y-m-d'));
        //     })
        //     ->orWhereNull('u.retire_date');
        // });

        //check office curren
        if(!empty($offiCdCur)){
            $query->where('i.office_cd', '=', $offiCdCur);
        }

        if (!empty($data)) {
            $txtDate = isset($data["txtDate"]) ? $data["txtDate"] : null;
            $cmbBelong = isset($data["cmbBelong"]) ? $data["cmbBelong"] : null;
            $txtIdStaff = isset($data["txtIdStaff"]) ? $data[""] : null;
            $cmbWStatus = isset($data["cmbWStatus"]) ? $data["cmbWStatus"] : null;
            $txtNameStaff = isset($data["txtNameStaff"]) ? $data["txtNameStaff"] : null;

            $query->when($txtDate, function ($query, $src) {
                    $dateSearch=date_create($src);
                    $month = date_format($dateSearch,"m");
                    $year = date_format($dateSearch,"Y");
                    return $query->whereMonth('i.date_join', '=',$month)
                                ->whereYear('i.date_join', '=',$year);
                })
                ->when( $cmbBelong, function ($query, $src) {
                    return $query->where('i.belong_cd', '=', $src);
                })
                ->when( $txtIdStaff, function ($query, $src) {
                    return $query->where('i.users_number','like', '%' . $src . '%');
                })
                ->when( $cmbWStatus, function ($query, $src) {
                    return $query->where('i.work_status', '=', $src);
                })
                ->when( $txtNameStaff, function ($query, $src) {
                    return $query->where(function ($q) use ($src) {
                        return $q->WhereRaw("CONCAT(i.first_name, '　', i.last_name) like ?", ['%'.$src.'%']);
                    });
                });
        }
        $query->select(
            'i.id as idInfo',
            'u.id as idStaff',
            'u.name as nameStaff',
            'u.retire_date',
            'i.date_join',
            'i.office_cd',
            'i.belong_cd',
            'o.office_name as OffiInfo',
            'b.belong_name as BelInfo',
            'i.users_number',
            'i.first_name',
            'i.last_name',
            'i.work_status',
            'i.email as emailInfo',
            'i.tel_number',
            'i.company_mobile_number',
            'i.*'
        )
            ->orderBy('i.date_join','DESC')
            ->orderBy('i.office_cd')
            ->orderBy('i.belong_cd')
            ->orderBy('i.users_number')
            ->distinct('u.id');
        //mode export or none
        return (!$mode) ? $query->paginate(CommonConst::MAX_ROW) : $query->get();
    }

    public function findbyKey($id)
    {
        $belong = DB::table('mst_belong as belong')
            ->Join('mst_offices as ofce', 'ofce.office_cd', '=', 'belong.office_cd')
            ->select(
                'id',
                'ofce.office_cd',
                'ofce.office_name',
                'belong.belong_cd',
                'belong.belong_name',
                'belong_address'
            )
            ->where('id', $id)
            ->orderBy('ofce.office_cd')
            ->first();
        return $belong;
    }

    public function getInfoByIdUser($id)
    {
        $query = DB::table('info_users as info')
            ->leftJoin('users as u', 'u.info_users_cd', '=', 'info.id')
            ->where('u.id', $id)
            ->value('info.id');
        return $query;
    }

    public function registerMessage($data)
    {
        DB::beginTransaction();
        try {
            $idInfoCus =  (!empty(Auth::id())) ? $this->getInfoByIdUser(Auth::id()) : -1;
            if (empty($idInfoCus)) return;
            //get array id checked
            $idChecks = explode(",", $data['InfoCheck']);
            foreach ($idChecks as $idInfoStaff) {
                $this->saveMessage($idInfoStaff,$data['txtMessage'],'M03');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw ($e);
        }
    }

    /**
     * Register Notices 
     *
     * @param  int  $idStaffEnd 
     * @param  string  $content
     * @param  string  $notiType
     * @param  bool  $checkArray
     * @return bool
     */
    public function saveMessage($idStaffEnd, $content, $notiType)
    {
        DB::beginTransaction();
        try {
            $dataToUpdate = [
                'notices_type' => $notiType,
                'notices_date' => now(),
                'notices_create_user' => Auth::id(),
                'notices_content' => $content,
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

    //set file name export xlsx
    public function setFileNameOfficeExport($mode,$officeCd)
    {
        $fileNm = "";
        $officeN = DB::table('mst_offices')->where('office_cd', $officeCd)->value('office_name');
        switch ($mode) {
            case CommonConst::MODE_EXPORT_BASIC:
                $fileNm = '社員データ('.$officeN.')_'.date('Ymd').'.xlsx';
                break;
            case CommonConst::MODE_EXPORT_SALARY:
                $fileNm = '固定金額('.$officeN.')_'.date('Ymd').'.xlsx';
                break;
        }
        return $fileNm;
    }

    //check and get value SalarySystemCod
    public function getSalarySystemCode($idInfo){
        $inFo = DB::table('info_users as i')
        ->leftJoin('contract_info_users as c', 'c.info_users_cd', '=', 'i.id')
        ->where('i.id', $idInfo)->select('i.manager','c.month_salary')->first();
        $result = '';
        if(!empty($inFo))
        {
            if($inFo->manager == 1)
            {
                $result = '03';
            }else if($inFo->month_salary != 0){
                $result = '02';
            }else{
                $result = '01';
            }
        }
        return  $result;
    }

    //loop with same value 
    public function loopSameValue(&$array,$number,$value=""){
        foreach (range(1, $number) as $v) {
            $array[] = $value;
        }
    }

    //check and get Transport
    public function getTransport($user){
        $result = '0';
        $trans = DB::table('info_users as i')
            ->leftJoin('transport_users as t', 't.info_users_cd', '=', 'i.id')
            ->where('t.info_users_cd',$user->id)->get();
        if($trans)
        {
            $result = '0';
        }else{
            if(!$user->transport_type)
            {
                $result = 'Z';
            }else{
                $distance = $user->private_car / 2;
                if ($distance > 55) {
                    $result = '1';
                } elseif ($distance >= 45) {
                    $result = '2';
                } elseif ($distance >= 35) {
                    $result = '3';
                } elseif ($distance >= 25) {
                    $result = '4';
                } elseif ($distance >= 15) {
                    $result = '5';
                } elseif ($distance >= 10) {
                    $result = '6';
                } elseif ($distance >= 2) {
                    $result = '7';
                }else
                    $result = '8';
            }
        }
    return $result;
    }

    public function getNameClass($itemType, $item_cd)
    {
        return DB::table('mst_class')
        ->where('item_type', '=', $itemType)
        ->where('item_cd', '=', $item_cd)
        ->value('item_name');
    }

    public function fomartDateExport($dateInput)
    {
        $date=date_create($dateInput);
        return date_format($date,"Y/m/d");
    }

//end m03-1
//m03-2
    public function findUsersByKey($id)
    {
        $query = DB::table('info_users as u')
        ->leftJoin('mst_belong as be', function ($join) {
            $join->on('u.belong_cd', '=', 'be.belong_cd')
                ->on('u.office_cd', '=', 'be.office_cd');
        })
        ->leftJoin('users', 'users.info_users_cd', '=', 'u.id')
        ->leftJoin('mst_offices as o', 'u.office_cd', '=', 'o.office_cd')
        ->leftJoin('contract_info_users as con', function ($join) {
            $join->on('con.info_users_cd', '=', 'u.id')
                ->on('con.ver_his','=', 'u.ver_his_U05');
        })
        ->leftJoin('upload_info_users as up', function ($join) {
            $join->on('up.info_users_cd', '=', 'u.id')
                ->where('up.upload_type', CommonConst::UPLOAD_TYPE_2)
                ->on('up.ver_his', '=', 'u.ver_his_U05');
        })
            ->select(
                'u.*',
                DB::raw("DATE_FORMAT(u.date_join, '%Y/%m/%d') as date_join"),
                'o.office_name',
                'be.belong_name',
                'o.office_director',
                DB::raw("CONCAT(u.first_name, '　', u.last_name) as name"),
                DB::raw("CONCAT(u.first_furigana, '　', u.last_furigana) as name_furi"),
                DB::raw("DATE_FORMAT(u.sign_date, '%Y/%m/%d %H:%i') as sign_date"),
                'con.work_hours_per_week',
                'con.estimated_amount',
                'up.folder_path',
                'up.folder_name',
                'up.file_name',
                'users.id as idUsers',
                'users.icon_folder_path',
                'users.icon_folder_name',
                'users.icon_file_name',
            )
            ->where('u.id', $id)
            
            ->first();
        return $query;
    }

    public function findProceduresUsersByKey($id)
    {
        $result = db::table('procedures_users')->where('info_users_cd', $id)->get();
        return $result;
    }

    public function findDepentUserbyKey($id, $ver_his = 0){
        $query = db::table('dependent_users as de')
        ->where('de.info_users_cd', $id)
        ->where('de.ver_his', $ver_his)
        ->get();
        return $query;
    }

    public function findTransportUserbyKey($id, $ver_his = 0){
        $query = db::table('transport_users as tr')
        ->leftJoin('info_users as u',function ($join) {
            $join->on('u.id','=','tr.info_users_cd' );
        })
        ->select('tr.*')
        ->where('u.id',$id)
        ->where('tr.ver_his', $ver_his)
        ->get();    

        return $query;
    }

    public function findUpfileById($id, $ver_his = 0){
        $query = db::table('upload_info_users as up')
        ->leftJoin('info_users as u',function ($join) {
            $join->on( 'u.id','=','up.info_users_cd');
        })
        ->select('up.*')
        ->where('u.id',$id)
        ->where('up.upload_type', CommonConst::UPLOAD_TYPE_5)
        ->where('up.ver_his', $ver_his)
        ->first();
        return $query;
    }

    public function registerIconStaff($id,$data)
    {
        DB::beginTransaction();
        try {
            $this->registerIcon($id,$data);

            DB::commit();
            UploadFunc::delTmpFolder();

        } catch (\Exception $e) {
            DB::rollBack();
            UploadFunc::delTmpFolder();
            throw $e;
        }
    }

    public function registerIcon($id,$data){
        $file = User::where('id',$id)->first();
            if ($data['curIcon']){
                if($data['tmpIcon']) 
                {
                    UploadFunc::delFile($file->icon_folder_name . $file->icon_file_name);
                    $this->uploadFile("Icon", $id, $data);
                }
            }
            else{
                $this->deleteFileById($id,$data);
            }
    }
    public function deleteFileById ($id,$data, $isDel=true) {
        $file = User::find($id);
        $fileOld = $data['oldIcon'];
        if ($file) {
            UploadFunc::delFile($fileOld);
            if($isDel) $file->update([
                'icon_folder_path' => null,
                'icon_file_name' => null,
                'icon_folder_name' => null,
            ]);
        }
    }

    public function uploadFile($name, $id, $data){
        $fileTmp = $data['tmp' . $name];
        $fileOld = $data['old' . $name];
        $file = UploadFunc::getFileName($data['tmp' . $name]);
        $ext = UploadFunc::getExtension($file);
        $fileNm = 'プロフィール画像.'. $ext;
        $upFile = User::where('id',$id)->first();
        
        if($name == "Icon"){
            $upFile->update([
                'icon_folder_path' => CommonConst::UPLOAD_FOLDER_PATH,
                'icon_file_name' => $fileNm,
                'icon_folder_name' => CommonConst::UPLOAD_USERS . $id . '/' . $name. '/',
            ]);
            
            if($fileOld){
                UploadFunc::delFile($fileOld);
            }
            $result = UploadFunc::moveFile($fileTmp,  $upFile->icon_folder_name . $fileNm);
        }
        return $result;
    }

    public function backgroundChek($email){
        $query = DB::table('users as u')
        ->leftJoin('info_users as i', 'i.id', '=', 'u.info_users_cd')
        ->leftJoin('mst_belong as be', function ($join) {
            $join->on('i.belong_cd', '=', 'be.belong_cd')
                ->on('i.office_cd', '=', 'be.office_cd');
        })
        ->leftJoin('mst_offices as o', 'i.office_cd', '=', 'o.office_cd')
        ->select(
            'u.email',
            'u.retire_date',
            'i.id',
            'i.date_join',
            'i.office_cd',
            'i.belong_cd',
            'o.office_name',
            'be.belong_name',
            'i.position',
            'i.work_time_flag',
            'i.work_status',
        )
        ->where('u.email',$email)
        ->whereRaw('i.date_join <= NOW()')
        ->get();
        return $query;
    }
    //end-m03-2

}
