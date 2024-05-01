<?php
namespace App\Services;
use App\Models\InfoRetireUsers;
use App\Models\InfoUsers;
use App\Models\User;
use App\Models\Notices;
use Illuminate\Support\Facades\DB;
use App\Common\CommonConst;
use App\Models\LetterRetireInfoUsers;
use App\Common\UploadFunc;
use Illuminate\Support\Facades\Auth;
use App\Common\CommonFunc;
use App\Mail\SendMailRetire;
use Illuminate\Support\Facades\Mail;
use App\Services\U03Service;
use Illuminate\Support\Facades\Storage;


class U07Service{
    public function findByIdInfoUser($id)
    {
        $query = DB::table('info_retire_users as infoR')
        ->leftJoin('users as au', 'au.id', '=', 'infoR.author_id')
        ->leftJoin('users as app', 'app.id', '=', 'infoR.applicant_id')
        ->leftJoin('users as ad1', 'ad1.id', '=', 'infoR.admin1_id')
        ->leftJoin('users as ad2', 'ad2.id', '=', 'infoR.admin2_id')
        ->leftJoin('users as hd', 'hd.id', '=', 'infoR.hd_id')
        ->select(
            'infoR.*',
            'au.name as name_author',
            'app.name as name_applicant',
            'ad1.name as name_admin1',
            'ad2.name as name_admin2',
            'hd.name as name_hd',
        )
        ->where('infoR.info_users_cd', $id);
        return $query->first();
    }
    public function registerRetire($name,$data){
        DB::beginTransaction();
        try {
            $infoUsersCd  = $data['hidInfoUserId'];
            $hidListRetireProd= isset($data['ChkRetireProd']) ? implode(",",$data['ChkRetireProd']) : null;
            $InfoRetireUsers = InfoRetireUsers::where('info_users_cd',$infoUsersCd )->first();
            if($InfoRetireUsers ){
                UploadFunc::delFile($InfoRetireUsers->retire_folder_name . $InfoRetireUsers->retire_file_name);
            }
            $revisionInfo = $InfoRetireUsers ? (int)$InfoRetireUsers->revision + 1 : 1 ;
            $folder_name = CommonConst::UPLOAD_INFO_USERS . $infoUsersCd . '/retire'. '/';
            $dataToSave = [
                'info_users_cd' => $infoUsersCd ,
                'retire_date' => $data['txtRetire_date'],
                'retire_procedure_items' => $hidListRetireProd ,
                'note' => $data['txtNote'],
                'status_user' => CommonConst::U07_STATUS_保存,
                'retire_folder_path' =>  CommonConst::UPLOAD_FOLDER_PATH,
                'retire_folder_name' => $folder_name,
                'author_date' => now(),
                'author_id' => Auth::id(),
                'created_at' => now(),
                'created_id' => Auth::id(),
                'updated_at' => now(),
                'updated_id' => Auth::id(),
                'revision' => $revisionInfo,
            ];      
         
            $fileCur = $data['cur' . $name];
            $fileTmp = $data['tmp' . $name];
            $fileOld = $data['old' . $name];
            if($fileCur){
                if($fileTmp){
                    $fileNm = UploadFunc::getFileName($data['tmp' . $name]);
                    $dataToSave['retire_file_name'] = $fileNm ;
                    UploadFunc::moveFile($fileTmp ,$folder_name. $fileNm,$fileOld);
                }
            }
            InfoRetireUsers::updateOrCreate(['info_users_cd' => $infoUsersCd], $dataToSave);        
            if($data['hidModeButton'] =='作成者' ){
                //sendmail
                $this->SendMailRetire($data);
            }  
            DB::commit();
            UploadFunc::delTmpFolder();
        } catch (\Exception $e) {
            DB::rollback();
            UploadFunc::delTmpFolder();
            throw $e;
        }
    }
    //
    public function updateStatusRetire($data){
        DB::beginTransaction();
        try {
            $this->SendMailRetire($data);            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    //
    public function getInfoUserByStaff($staffId)
    {
        $query = DB::table('info_users as info')
            ->leftJoin('users as u', 'u.info_users_cd', '=', 'info.id')
            ->leftJoin('mst_offices as o', 'o.office_cd', '=', 'info.office_cd')
            ->leftJoin('mst_belong as bl', 'bl.belong_cd', '=', 'info.belong_cd')
            ->where('u.id', $staffId);
        $query->select(
            'info.*',
            'u.name as staff_name',
            'u.id as staff_id',
            'u.email as staff_email',
            'o.office_name as name_office',
            'bl.belong_name as name_belong',
        );    
        return $query->first();
    }
    //send mail with mode
    public function SendMailRetire($data)
    {
        try{
            $u03service = new U03Service();
            $tanto = $this->getInfoUserByStaff(Auth::id());
            $staff = $this->getInfoUserByStaff($data['hidStaffId']);
            $typeNoti = 'U07';
           
            $mode = $data['hidModeButton'];
            $this->setStatusInfoRetireUsers($mode,$data['hidInfoUserId'],$data['txtNote']);
       
            switch ( $mode)
            {
                case  CommonConst::C_作成者:
                    $urlCreate=route('u073.retireProcTop', ['id' => $data['hidStaffId']]);
                    $hdList = $u03service->getHDApprove();
                    //Send mail 申請者
                        Mail::to($staff->staff_email)->send(new SendMailRetire($tanto,$staff, '作成者To申請者',$urlCreate,$data['txtNote']));
                    //Send mail HD
                    foreach ($hdList as $hd) {
                        Mail::to($hd->email)->send(new SendMailRetire($tanto,$staff,  '作成者ToHD',$urlCreate,$data['txtNote'], $hd));
                    }

                    //Send mess 申請者
                    $content = $this->getContentMesU07($staff);
                    $this->sendMessageU07($staff,$staff->staff_id,$content, $typeNoti);

                    if($staff->status_user == CommonConst::U07_STATUS_保存)
                    {
                        //clear mess 作成者
                        $this->deleteMessageU07($tanto, $tanto->id_author, $data['hidStaffId'], $typeNoti,CommonConst::C_2次否認);
                        $this->deleteMessageU07($tanto, $tanto->id_author, $data['hidStaffId'], $typeNoti,CommonConst::C_HD否認);
                        $this->deleteMessageU07($tanto, $tanto->id_author, $data['hidStaffId'], $typeNoti,CommonConst::C_HD承認.'作成者');
                        $this->deleteMessageU07($staff, $staff->id_author, $data['hidStaffId'], $typeNoti);
                    }
                    break;  
                case CommonConst::C_申請者:
                    $urlCreate=route('u07.retireProc', ['id' => $data['hidStaffId']]);
                    //Get 1次承認者
                    $OneList = $u03service->getSecondApprove($tanto->office_cd,$tanto->belong_cd,1);  
                    //Send mail 1次承認者
                    foreach ($OneList as $one) {
                        Mail::to($one->email_app2)->send(new SendMailRetire($tanto,$staff,  '申請者To1次',$urlCreate,$data['txtNote'], $one ));
                    }
                    //remove mess 申請者
                    $this->deleteMessageU07($staff, $staff->id, $data['hidStaffId'], $typeNoti);

                    break;      
                case CommonConst::C_1次承認:
                    $urlCreate=route('u07.retireProc', ['id' => $data['hidStaffId']]);
                    //Get 2次承認者
                    $SecondList = $u03service->getSecondApprove($tanto->office_cd,$tanto->belong_cd,2);  
                    //Send mail 2次承認者
                    foreach ($SecondList as $sc) {
                        Mail::to($sc->email_app2)->send(new SendMailRetire($tanto,$staff,  '1次To2次',$urlCreate,$data['txtNote'],$sc ));

                        //Send mess 2次承認者
                        $content = $this->getContentMesU07($staff,CommonConst::C_2次承認);
                        $this->sendMessageU07($sc,$sc->staff_id,$content, $typeNoti,CommonConst::C_2次承認);
                    }

                    //Get 1次承認者
                    $OneList = $u03service->getSecondApprove($tanto->office_cd,$tanto->belong_cd,1);  

                    //Remove mail 1次承認者
                    foreach ($OneList as $one) {
                        $this->deleteMessageU07($one, $one->id, $data['hidStaffId'], $typeNoti,CommonConst::C_1次承認);
                    }

                    break;
                case CommonConst::C_1次否認:
                    $urlCreate=route('u07.retireProc', ['id' => $data['hidStaffId']]);
                    $itemRetire = $this->findByIdInfoUser($data['hidInfoUserId']);
                    $tanto = $this->getInfoUserByStaff($itemRetire->author_id);
                    //Send mail 作成者
                    Mail::to($tanto->staff_email)->send(new SendMailRetire($tanto,$staff, '1次To作成者',$urlCreate,$data['txtNote']));

                    //Get 1次承認者
                    $OneList = $u03service->getSecondApprove($tanto->office_cd,$tanto->belong_cd,1);  
                    //Remove mess 1次承認者
                    foreach ($OneList as $one) {
                        $this->deleteMessageU07($one, $one->id, $data['hidStaffId'], $typeNoti,CommonConst::C_1次承認);
                    }

                    //Send mess 申請者
                    $content = $this->getContentMesU07($staff,CommonConst::C_1次否認);
                    $this->sendMessageU07($staff,$staff->staff_id,$content, $typeNoti,CommonConst::C_1次否認);
                    break;  
                case  CommonConst::C_2次承認:
                    $urlCreate=route('u07.retireProc', ['id' => $data['hidStaffId']]);
                    $hdList = $u03service->getHDApprove();
                    //Send mail HD
                    foreach ($hdList as $hd) {
                        Mail::to($hd->email)->send(new SendMailRetire($tanto,$staff,  '2次ToHD',$urlCreate,$data['txtNote'], $hd));

                        //Send mess HD
                        $content = $this->getContentMesU07($staff,CommonConst::C_HD承認);
                        $this->sendMessageU07($hd,$hd->staff_id,$content, $typeNoti,CommonConst::C_HD承認);
                    }

                    //Get 2次承認者
                    $SecondList = $u03service->getSecondApprove($tanto->office_cd,$tanto->belong_cd,2);  
                    //Remove mess 2次承認者
                    foreach ($SecondList as $sc) {
                        //Send mess 2次承認者
                        $this->deleteMessageU07($sc, $sc->id, $data['hidStaffId'], $typeNoti,CommonConst::C_2次承認);
                    }

                    break;  
                case CommonConst::C_2次否認:
                    $urlCreate=route('u07.retireProc', ['id' => $data['hidStaffId']]);
                    $itemRetire = $this->findByIdInfoUser($data['hidInfoUserId']);
                    $tanto = $this->getInfoUserByStaff($itemRetire->author_id);
                    //Send mail 作成者
                    Mail::to($tanto->staff_email)->send(new SendMailRetire($tanto,$staff, '2次To作成者',$urlCreate,$data['txtNote']));

                    //Get 2次承認者
                    $SecondList = $u03service->getSecondApprove($tanto->office_cd,$tanto->belong_cd,2);  
                    //Remove mess 2次承認者
                    foreach ($SecondList as $sc) {
                        //Send mess 2次承認者
                        $this->deleteMessageU07($sc, $sc->id, $data['hidStaffId'], $typeNoti,CommonConst::C_2次承認);
                    }

                    //Send mess 作成者
                    $content = $this->getContentMesU07($tanto,CommonConst::C_2次否認);
                    $this->sendMessageU07($tanto,$tanto->id_author,$content, $typeNoti,CommonConst::C_2次否認);
                    break;
                case  CommonConst::C_HD承認:
                    $urlCreate=route('u07.retireProc', ['id' => $data['hidStaffId']]);
                    $itemRetire = $this->findByIdInfoUser($data['hidInfoUserId']);
                    $tanto = $this->getInfoUserByStaff($itemRetire->author_id);
                    $this->setWorkStatus($mode,$itemRetire,$data['hidInfoUserId'],$staff);
                    //Send mail 作成者
                    Mail::to($tanto->staff_email)->send(new SendMailRetire($tanto,$staff, 'HDAccept',$urlCreate,$data['txtNote']));

                    $hdList = $u03service->getHDApprove();
                    foreach ($hdList as $hd) {
                        //Remove mess HD
                        $this->deleteMessageU07($hd, $hd->id, $data['hidStaffId'], $typeNoti,CommonConst::C_HD承認);
                    }

                    //Send mess 作成者
                    $content = $this->getContentMesU07($tanto,CommonConst::C_2次否認);
                    $this->sendMessageU07($tanto,$tanto->id_author,$content, $typeNoti,CommonConst::C_HD承認.'作成者');
                    break;      
                case  CommonConst::C_HD否認:
                    $urlCreate=route('u07.retireProc', ['id' => $data['hidStaffId']]);
                    $itemRetire = $this->findByIdInfoUser($data['hidInfoUserId']);
                    $tanto = $this->getInfoUserByStaff($itemRetire->author_id);
                    //Send mail 作成者
                    Mail::to($tanto->staff_email)->send(new SendMailRetire($tanto,$staff, 'HDTo作成者',$urlCreate,$data['txtNote']));

                    $hdList = $u03service->getHDApprove();
                    foreach ($hdList as $hd) {
                        //Remove mess HD
                        $this->deleteMessageU07($hd, $hd->id, $data['hidStaffId'], $typeNoti,CommonConst::C_HD承認);
                    }

                    //Send mess 作成者
                    $content = $this->getContentMesU07($tanto,CommonConst::C_2次否認);
                    $this->sendMessageU07($tanto,$tanto->id_author,$content, $typeNoti,CommonConst::C_HD否認);
                    break;                  
            }        
        } catch (\Exception $e) {
            throw $e;
        }
    }
     //update status Work Status
     public function setWorkStatus($mode,$itemRetire,$infoUserCd,$staff){
        $dataToInfoUser = [];
        $dataToStaff = [];
        try{
            switch ($mode) {    
                case CommonConst::C_HD承認:
                    $InfoUsers = InfoUsers::where('id',$infoUserCd )->first();
                    $revisionInfo = $InfoUsers ? (int)$InfoUsers->revision + 1 : 1 ;
                    $dataToInfoUser['work_status'] =  '4';
                    $dataToInfoUser['updated_at'] = now();
                    $dataToInfoUser['updated_id'] = Auth::id();
                    $dataToInfoUser['revision'] = $revisionInfo;  
                 
                    $User = User::where('id',$staff->staff_id)->first();
                    $revisionUser = $User ? (int)$User->revision + 1 : 1 ;
                    $dataToStaff['retire_date'] =  $itemRetire->retire_date;
                    $dataToStaff['updated_at'] = now();
                    $dataToStaff['updated_id'] = Auth::id();
                    $dataToStaff['revision'] = $revisionUser;  
                    break;          
            }
            InfoUsers::updateOrCreate(['id' => $infoUserCd], $dataToInfoUser);
            User::updateOrCreate(['id' => $staff->staff_id], $dataToStaff);
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    //update status user
    public function setStatusInfoRetireUsers($mode,$info_users_cd,$comment){
        $dataToSave = [];
        try{
            switch ($mode) {
                case CommonConst::C_作成者:
                    $dataToSave['author_id'] = Auth::id();
                    $dataToSave['author_date'] = now();
                    $dataToSave['status_user'] = CommonConst::U07_STATUS_作成者;
                    $dataToSave['note'] = $comment;
                    break;
                case CommonConst::C_申請者:
                    $dataToSave['applicant_id'] = Auth::id();
                    $dataToSave['applicant_date'] = now();
                    $dataToSave['status_user'] = CommonConst::U07_STATUS_1次;
                    $dataToSave['note'] = $comment;
                    break;
                case CommonConst::C_1次承認:
                    $dataToSave['admin1_id'] = Auth::id();
                    $dataToSave['admin1_date'] = now();
                    $dataToSave['status_user'] = CommonConst::U07_STATUS_2次;
                    $dataToSave['note'] = $comment;                  
                    break;    
                case CommonConst::C_1次否認:
                    $dataToSave['status_user'] = CommonConst::U07_STATUS_保存;
                    $dataToSave['note'] = $comment;                  
                    break;
                case CommonConst::C_2次承認:
                    $dataToSave['admin2_id'] = Auth::id();
                    $dataToSave['admin2_date'] = now();
                    $dataToSave['status_user'] = CommonConst::U07_STATUS_HD;
                    $dataToSave['note'] = $comment;                  
                    break;  
                case CommonConst::C_2次否認:
                    $dataToSave['status_user'] = CommonConst::U07_STATUS_保存;
                    $dataToSave['note'] = $comment;                  
                    break;  
                case CommonConst::C_HD承認:
                    $dataToSave['hd_id'] = Auth::id();
                    $dataToSave['hd_date'] = now();
                    $dataToSave['status_user'] = CommonConst::STATUS_COMPLETED;
                    $dataToSave['note'] = $comment;                  
                    break;
                case CommonConst::C_HD否認:
                    $dataToSave['status_user'] = CommonConst::U07_STATUS_保存;
                    $dataToSave['note'] = $comment;                  
                    break;              
            }
            return  InfoRetireUsers::updateOrCreate(['info_users_cd' => $info_users_cd], $dataToSave);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    //delete mess
    public function deleteMessageU07($userInfo, $idUserEnd, $idInfoConfim, $typeNoti, $mode = null)
    {
        $content = $this->getContentMesU07($userInfo, $mode);
        $idNotice = $this->getIdNoticeU07($idUserEnd, $idInfoConfim, $content, $typeNoti);
        if(!empty($idNotice))
            $this->deleteNoticeU07($idNotice);
    }
    public function getContentMesU07($staffInfo, $mode = null )
    {
        if(empty($mode))
        {
            $content = '退職手続きが作成されました。';
        }else
        {
            $content = $staffInfo->staff_name;
            switch ($mode)
            {
                case CommonConst::C_2次承認:
                    $content .= 'さんの退職手続きの2次承認の依頼があります。';
                    break;
                case CommonConst::C_1次承認:
                    $content .= 'さんの退職手続きの1次承認の依頼があります。';
                    break;
                case CommonConst::C_1次否認:
                    $content .= 'さんの退職手続きが1次承認者に否認されました。';
                break;
                case CommonConst::C_HD承認:
                    $content .= 'さんの退職手続きのHD承認の依頼があります。';
                    break;
                case CommonConst::C_2次否認:
                    $content .= 'さんの退職手続きが2次承認者に否認されました。';
                    break;
                case CommonConst::C_HD承認.'作成者':
                    $content .= 'さんの退職手続きのHD承認者に承認されました。';
                    break;
                case CommonConst::C_HD否認:
                    $content .= 'さんの退職手続きのHD承認者に否認されました。';
                    break;
            }
        }
        return $content;
    }


    //get id Notice
    public function getIdNoticeU07($idUserEnd, $idInfoConfim, $content, $typeNoti){
        return DB::table('notices')
        ->where('notices_type', $typeNoti)
        ->where('notices_dest_user', $idUserEnd)
        ->where('info_users_cd', $idInfoConfim)
        ->where('notices_content', $content)
        ->value('id');
    }


    //delete notice
    public function deleteNoticeU07($id)
    {
        return DB::transaction(function () use ($id) {
            return Notices::where("id", $id)->delete();
        });
    }
    //u07_2
    public function getDataMemo($id){
        $query = DB::table('info_users as info')
        ->leftJoin('mst_offices as offices', 'offices.office_cd', '=', 'info.office_cd')
        ->leftJoin('letter_retire_info_users as lr', 'lr.info_users_cd', '=', 'info.id')
        ->select(
            'info.id',
            'offices.office_name',
            'offices.office_director',
            'info.token',
            'lr.title_retire',
            'lr.intro_retire',
            'lr.body_retire'
        )
            ->where('info.id', $id);
        return $query->first();
    }
    // Memo U07_2
    public function saveMemo($data)
    {  
        DB::beginTransaction();
        try{
            if($this->setDataMemo($data)){
                DB::commit();
                return true;
            }
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }
    // Memo Confirm U07_5
    public function saveMemoConfirm($data)
    {
        DB::beginTransaction();
        try{
            if($this->setDataMemoConfirm($data)){
                DB::commit();
                return true;
            }
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }


    public function setDataMemo($data){
        $dataToSave = [
            'title_retire' => $data['txtTitle_retire'],
            'intro_retire' => $data['txtIntro_retire'],
            'body_retire' => $data['txtBody_retire'],
        ];
        LetterRetireInfoUsers::updateOrCreate(['info_users_cd' => $data['hidInfo_users_cd']], $dataToSave);


        return true;
    }
   
    public function setDataMemoConfirm($data){
        $currentDate = date('Y/m/d H:i:s');
        $imgNm = "サイン退職_" . $data['hidInfo_users_cd'] . '_' . date('Ymd');  


        $dataToSave = [
            'U07-5_date' => $currentDate,
            'retire_sign_folder_path' => CommonConst::UPLOAD_FOLDER_PATH,
            'retire_sign_folder_name' => CommonConst::UPLOAD_INFO_USERS . $data['hidInfo_users_cd'] . '/retire'. '/sign'. '/0' . '/',
            'retire_sign_file_name'   => $imgNm . '.png',
            'retire_sign_date'  => $currentDate,
        ];
        $infoRetire = InfoRetireUsers::updateOrCreate(['info_users_cd' => $data['hidInfo_users_cd']], $dataToSave);
        $image = str_replace('data:image/png;base64,', '', $data['signContent']);
        $image = str_replace(' ', '+', $image);
        $imageName = $infoRetire->retire_sign_folder_name.$imgNm.'.png';


        Storage::disk('public')->put($imageName, base64_decode($image));
        return true;
    }
      //delete
      public function deleteRetire($infoUserCd)
      {
          return DB::transaction(function () use ($infoUserCd) {
 
            InfoRetireUsers::where("info_users_cd", $infoUserCd)->delete();
            CommonFunc::deleteFolder(CommonConst::UPLOAD_FOLDER_PATH . CommonConst::UPLOAD_INFO_USERS . $infoUserCd.'/retire',true );
            LetterRetireInfoUsers::where("info_users_cd", $infoUserCd)->delete();
            return true;
          });
      }


    //U07_4
    public function getInfoReUsersByKey($id){
        $query = DB::table('info_retire_users as infoR')
        ->select(
            'infoR.*'
        )
        ->where('infoR.info_users_cd', $id);
        return $query->first();
    }
    public function infReUserUpOrReg($data){
        DB::beginTransaction();
        try{
            if($this->setDataInfReUser($data)){
                DB::commit();
                return true;
            }
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }
    public function setDataInfReUser($data){
        $currentDate = date('Y/m/d H:i:s');
        $arrRetire_date = $data['txtRetire_date'];
        $arrTire_Post_code = $data['txtPost_code'];
        $arrTire_Address = $data['txtAddress'];
        $arrRetire_contact_info = $data['txtRetire_contact_info'];
        $arrNote1 = $data['txtNote1'];
        $arrSeparation_form = $data['cmbSeparation_form'];
        $arrSpecial_note1 = $data['txtSpecial_note1'];
        $arrSpecial_note2 = $data['txtSpecial_note2'];
        $arrSpecial_note3 = $data['txtSpecial_note3'];
        $arrRetire_date = $data['txtRetire_date'];
        $arrRetire_date = $data['txtRetire_date'];
        $dataToSave = [
            'retire_date' => $arrRetire_date,
            'retire_post_code' => $arrTire_Post_code,
            'retire_address' => $arrTire_Address,
            'retire_contact_info' => $arrRetire_contact_info,
            'note1' => $arrNote1,
            'separation_form' => $arrSeparation_form,
            'special_note1' => $arrSpecial_note1,
            'special_note2' => $arrSpecial_note2,
            'special_note3' => $arrSpecial_note3,
            'U07-4_date' => $currentDate
        ];
        InfoRetireUsers::updateOrCreate(['info_users_cd' => $data['hidInfo_users_cd']], $dataToSave);


        return true;
    }
}
