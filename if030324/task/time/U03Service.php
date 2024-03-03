<?php
namespace App\Services;

use App\Common\CommonConst;
use App\Common\UploadFunc;
use App\Common\CommonFunc;
use App\Models\InfoUsers;
use App\Models\LetterInfoUsers;
use Illuminate\Support\Facades\DB;
use App\Models\ProceduresUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\GuranteeUsers;
use App\Models\UploadInfoUsers;
use App\Mail\SendMailInfo;
use App\Mail\SendMailGuar;
use App\Models\DependentUsers;
use App\Models\TransportUsers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\PrivateCar;
use App\Models\ContractInfoUsers;
use App\Models\BenefitInfoUsers;
use App\Models\Notices;
use App\Services\U07Service;
use App\Services\U04Service;
use App\Models\W1BenefitInfoUser;
use App\Models\W1ContractInfoUsers;
use App\Models\W1InfoUser;
use App\Models\W1PrivateCarUser;
use App\Models\W1ProceduresUser;

class U03Service
{
    // application U03_7
    public function findAppliByIdInfoUser($id, $isTemplate=false)
    {
        $tbl = 'info_users as info';
        if($isTemplate) $tbl = 'w_info_users as info';

        $query = DB::table($tbl)
            ->where('info.id', $id);
        return $query->first();
    }
    // application U03_7
    public function findDepByKey($id, $ver_his = 0, $isTemplate=false)
    {
        $tbl = 'dependent_users as dep';
        if($isTemplate) $tbl = 'w_dependent_users as dep';

        $query = DB::table($tbl)
            ->where('dep.info_users_cd', $id)
            ->where('dep.ver_his', $ver_his);
        return $query->get();
    }
    // application U03_7
    public function findTransByKey($id, $ver_his = 0, $isTemplate=false)
    {
        $tbl = 'transport_users as trans';
        if($isTemplate) $tbl = 'w_transport_users as trans';

        $query = DB::table($tbl)
            ->where('trans.info_users_cd', $id)
            ->where('trans.ver_his', $ver_his);
        return $query->get();
    }
    // Invitation U03_2, Invitation Confirm U03_6
    public function findInviByIdInfoUser($id)
    {
        $query = DB::table('info_users as info')
        ->leftJoin('mst_offices as offices', 'offices.office_cd', '=', 'info.office_cd')
        ->leftJoin('letter_info_users as letter', 'letter.info_users_cd', '=', 'info.id')
        ->select(
            'info.*',
            'offices.office_name',
            'offices.office_director',
            'info.token',
            DB::raw("(CASE WHEN letter.title_invitation IS NULL OR letter.title_invitation = '' THEN offices.title_invitation ELSE letter.title_invitation END) as title_invitation"),
            DB::raw("(CASE WHEN letter.intro_invitation IS NULL OR letter.intro_invitation = '' THEN offices.intro_invitation ELSE letter.intro_invitation END) as intro_invitation"),
            DB::raw("(CASE WHEN letter.body_invitation IS NULL OR letter.body_invitation = '' THEN offices.body_invitation ELSE letter.body_invitation END) as body_invitation")
        )
            ->where('info.id', $id);
        return $query->first();
    }
    // Invitation U03_2

    //u0311 confirm document sign file
    public function findUploadOficeByInfoId($upId)
    {
        $query = DB::table('upload_offices as u')
        ->select(
            'u.*',
        )
        ->where('u.id', $upId)
        ->first();
        return $query;
    }
    
    public function saveInvitation($data)
    {
        DB::beginTransaction();
        try{
            if($this->setRegDataInvitation($data)){
                DB::commit();
                return true;
            }
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }
    // Invitation Confirm U03_6
    public function saveInvitationConfirm($data)
    {
        DB::beginTransaction();
        try{
            if($this->setRegDataInvitationConfirm($data)){
                DB::commit();
                return true;
            }
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }
    //Register and Update U03_7
    public function infUserUpOrReg($data){
        DB::beginTransaction();
        try{
            if($this->setRegDataInfUser($data)){
                DB::commit();
                UploadFunc::delTmpFolder();
                return true;
            }
        }catch(\Exception $e){
            UploadFunc::delTmpFolder();
            DB::rollBack();
            throw $e;
        }
    }
    public function setRegDataInvitation($data){
        $dataToSave = [
            'title_invitation' => $data['txtTitle_invitation'],
            'intro_invitation' => $data['txtIntro_invitation'],
            'body_invitation' => $data['txtBody_invitation'],
        ];
        LetterInfoUsers::updateOrCreate(['info_users_cd' => $data['hidInfo_users_cd']], $dataToSave);

        return true;
    }
    public function setRegDataInvitationConfirm($data,$ver_his = 0){
        $login_id = Auth::id();
        $currentDate = date('Y/m/d H:i:s');
        $dataToSave = [
            'U03-6_date' => $currentDate,
            'U03-6_id' => $login_id,
        ];
        ProceduresUsers::updateOrCreate(['info_users_cd' => $data['hidInfo_users_cd'], 'ver_his' => $ver_his], $dataToSave);

        return true;
    }
    public function setRegDataInfUser($data){
        $objectPerson = $this->getListCheckbox($data, 'checkbox');
        $arrContractClass = $data['cmbContract_class'];
        $arrGender = $data['cmbGender'];
        $arrTelNumber = $data['txtTel_number1'] . '-' . $data['txtTel_number2'] . '-' . $data['txtTel_number3'];
        $arrPost_code = $data['txtPost_code'];
        $arrAdress1 = $data['txtAdress1'];
        $arrAdress2 = $data['txtAdress2'];
        $arrAdress3 = $data['txtAdress3'];
        $arrAdressFurigana1 = $data['txtAdress_furigana1'];
        $arrAdressFurigana2 = $data['txtAdress_furigana2'];
        $arrAdressFurigana3 = $data['txtAdress_furigana3'];
        $arrBirthday = $data['txtBirthday'];
        $arrDateJoin = $data['txtDate_join'];
        $arrPrevJob = $data['txtPrev_job'];
        $arrMyNumber = $data['txtMy_number'];
        $arrObjectPerson = $objectPerson;
        $arrJionInsurance = $data['cmbJion_insurance'];
        $arrInsuranceDate = $data['txtInsurance_date'];
        $arrInsuranceSocial = $data['cmbInsurance_social'];
        $arrInsuranceSocialDate = $data['txtInsurance_social_date'];
        $arrDependentNumber = $data['txtDependent_number'];
        $arrBankCode = $data['txtBank_code'];
        $arrBankName = $data['txtBank_name'];
        $arrBranchBankCode = $data['txtBranch_bank_code'];
        $arrBranchBankName = $data['txtBranch_bank_name'];
        $arrBankUserName = $data['txtBank_user_name'];
        $arrBankUserFurigana = $data['txtBank_user_furigana'];
        $arrDepositType = $data['cmbDeposit_type'];
        $arrBankNumber = $data['txtBank_number'];
        $arrPrivateCar = empty($data['txtPrivate_car']) ? NULL : (int) str_replace(',','',$data['txtPrivate_car']);
        $arrTransportType = isset($data['cmbTransport_type']) ? $data['cmbTransport_type'] : NULL;
        $dataToUpdate = [
            'contract_class' => $arrContractClass,
            'gender' => $arrGender,
            'tel_number' => $arrTelNumber,
            'post_code' => $arrPost_code,
            'adress1' => $arrAdress1,
            'adress2' => $arrAdress2,
            'adress3' => $arrAdress3,
            'adress_furigana1' => $arrAdressFurigana1,
            'adress_furigana2' => $arrAdressFurigana2,
            'adress_furigana3' => $arrAdressFurigana3,
            'birthday' => $arrBirthday,
            'date_join' => $arrDateJoin,
            'prev_job' => $arrPrevJob,
            'my_number' => $arrMyNumber,
            'object_person' => $arrObjectPerson,
            'jion_insurance' => $arrJionInsurance,
            'insurance_date' => $arrInsuranceDate,
            'insurance_social' => $arrInsuranceSocial,
            'insurance_social_date' => $arrInsuranceSocialDate,
            'dependent_number' => $arrDependentNumber,
            'bank_code' => $arrBankCode,
            'bank_name' => $arrBankName,
            'branch_bank_code' => $arrBranchBankCode,
            'branch_bank_name' => $arrBranchBankName,
            'bank_user_name' => $arrBankUserName,
            'bank_user_furigana' => $arrBankUserFurigana,
            'deposit_type' => $arrDepositType,
            'bank_number' => $arrBankNumber,
            'private_car' => $arrPrivateCar,
            'transport_type' => empty($arrTransportType) ? NULL : $arrTransportType
        ];
        $infoUsers = InfoUsers::updateOrCreate(['id' => $data['hidInfoUsersId'] ?? '' ], $dataToUpdate);
        $id = $infoUsers->id;
        if($id){
            //扶養家族
            $this->registerDep($data, $id);
            //公共交通機関
            $this->registerTrans($data, $id);
            //file
            $this->checkUploadFile('RouteMap', $data, $id, CommonConst::UPLOAD_TYPE_5);
            //入職手続き
            $this->registerProcUsers($id);
        }
        return true;
    }
    //扶養家族
    public function registerDep($data, $info_users_cd,$ver_his = 0){
        if(isset($data['hidIdDependentDel'])){
            DependentUsers::whereIn('id', $data['hidIdDependentDel'])->where('ver_his', $ver_his)->delete();
        }

        if(isset($data['txtDepNameIns'])){
            $arrInsert = [];
            for($i=0; $i < count($data['txtDepNameIns']); $i++){
                $name = $data['txtDepNameIns'][$i];
                $furigana = $data['txtDepFuriganaIns'][$i];
                $gender = $data['cmbDepGenderIns'][$i];
                $relationship = $data['txtDepRelationshipIns'][$i];
                $birthday = $data['txtDepBirthdayIns'][$i];
                $career = $data['txtDepCareerIns'][$i];
                $my_number = $data['txtDepMyNumberIns'][$i];
                if(empty($info_users_cd) && empty($name) && empty($furigana) 
                && empty($gender) && empty($relationship) && empty($birthday)
                && empty($career) && empty($my_number)) continue;

                $arrInsert[] = [
                    'info_users_cd' => $info_users_cd,
                    'ver_his' => $ver_his,
                    'name' => $name,
                    'furigana' => $furigana,
                    'gender' => $gender,
                    'relationship' => $relationship,
                    'birthday' => $birthday,
                    'career' => $career,
                    'my_number' => $my_number,
                    'created_id' => Auth::id(),
                    'updated_id' => Auth::id()
                ];
            }
            DependentUsers::insert($arrInsert);
        }

        if(isset($data['hidIdDependentUp'])){
            $arrUpdate = [];
            for($i=0; $i < count($data['hidIdDependentUp']); $i++){
                $id = $data['hidIdDependentUp'][$i];
                $name = $data['txtDepNameUp'][$i];
                $furigana = $data['txtDepFuriganaUp'][$i];
                $gender = $data['cmbDepGenderUp'][$i];
                $relationship = $data['txtDepRelationshipUp'][$i];
                $birthday = $data['txtDepBirthdayUp'][$i];
                $career = $data['txtDepCareerUp'][$i];
                $my_number = $data['txtDepMyNumberUp'][$i];

                $arrUpdate[$id] = [
                    'id' => $id,
                    'info_users_cd' => $info_users_cd,
                    'ver_his' => $ver_his,
                    'name' => $name,
                    'furigana' => $furigana,
                    'gender' => $gender,
                    'relationship' => $relationship,
                    'birthday' => $birthday,
                    'career' => $career,
                    'my_number' => $my_number,
                    'updated_id' => Auth::id(),
                ];
            }
            DB::table('dependent_users')->upsert($arrUpdate, 'id');
        }
    }
    //公共交通機関
    public function registerTrans($data, $info_users_cd,$ver_his = 0){
        if(isset($data['hidIdTransportDel'])){
            TransportUsers::whereIn('id', $data['hidIdTransportDel'])->where('ver_his', $ver_his )->delete();
        }

        if(isset($data['txtStartPointIns'])){
            $arrInsert = [];
            for($i=0; $i < count($data['txtStartPointIns']); $i++){
                $startpoint = $data['txtStartPointIns'][$i];
                $endpoint = $data['txtEndPointIns'][$i];
                $amount = $data['txtAmountIns'][$i];
                if(empty($info_users_cd)) continue;
                if(!empty($startpoint) && !empty($endpoint) && !empty($amount)){
                    $arrInsert[] = [
                            'info_users_cd' => $info_users_cd,
                            'ver_his' => $ver_his ,
                            'start_point' => $startpoint,
                            'end_point' => $endpoint,
                            'amount' => str_replace(',', '', $amount),
                            'created_id' => Auth::id(),
                            'updated_id' => Auth::id()
                    ];
                }
            }
            TransportUsers::insert($arrInsert);
        }

        if(isset($data['hidIdTransportUp'])){
            $arrUpdate = [];
            for($i=0; $i < count($data['hidIdTransportUp']); $i++){
                $id = $data['hidIdTransportUp'][$i];
                $startpoint = $data['txtStartPointUp'][$i];
                $endpoint = $data['txtEndPointUp'][$i];
                $amount = $data['txtAmountUp'][$i];

                $arrUpdate[$id] = [
                    'id' => $id,
                    'info_users_cd' => $info_users_cd,
                    'ver_his' => $ver_his ,
                    'start_point' => $startpoint,
                    'end_point' => $endpoint,
                    'amount' => str_replace(',', '', $amount),
                    'updated_id' => Auth::id()
                ];
            }
            DB::table('transport_users')->upsert($arrUpdate, 'id');
        }
    }
    //入職手続き
    public function registerProcUsers($id,$ver_his = 0){
        $login_id = Auth::id();
        $currentDate = date('Y/m/d H:i:s');
        $arrProceUsers = [
            'U03-7_date' => $currentDate,
            'U03-7_id' => $login_id
        ];
        ProceduresUsers::updateOrCreate(['info_users_cd' => $id, 'ver_his' => $ver_his ], $arrProceUsers);
    }
//infomation u03-1
    public function findByIdInfoUser($id)
    {
        $query = DB::table('info_users as info')
        ->leftJoin('users as au', 'au.id', '=', 'info.author_id')
        ->leftJoin('users as app', 'app.id', '=', 'info.applicant_id')
        ->leftJoin('users as ad1', 'ad1.id', '=', 'info.admin1_id')
        ->leftJoin('users as ad2', 'ad2.id', '=', 'info.admin2_id')
        ->leftJoin('users as hd', 'hd.id', '=', 'info.hd_id')
        ->leftJoin('letter_info_users as letter', 'letter.info_users_cd', '=', 'info.id')
        ->leftJoin('contract_info_users as con', function($join){
            $join->on('con.info_users_cd', '=', 'info.id')
            ->on('con.ver_his', '=', 'info.ver_his_U05');
        })
        ->select(
            'info.*',
            'au.name as name_author',
            'app.name as name_applicant',
            'ad1.name as name_admin1',
            'ad2.name as name_admin2',
            'hd.name as name_hd',
            'letter.updated_at as let_updated_at',
            'con.estimated_amount as estimated_amount',
            'con.work_hours_per_week as work_hours_per_week',
            'con.updated_at as con_updated_at',
        )
        ->where('info.id', $id);
        return $query->first();
    }

    public function getOffiCdInfoByIdUser($id)
    {
        $query = DB::table('info_users as info')
            ->leftJoin('users as u', 'u.info_users_cd', '=', 'info.id')
            ->where('u.id', $id)
            ->value('info.office_cd');
        return $query;
    }

    public function findProByKey($id,$delete_flag = 0)
    {
        $query = DB::table('mst_procedure_items as pro')
            ->leftJoin('procedure_offices as po', 'po.procedure_id', '=', 'pro.id')
            ->select('pro.id', 'pro.name', 'po.office_cd')
            ->selectRaw('CASE WHEN EXISTS (
                SELECT 1
                FROM procedure_offices po
                WHERE po.office_cd = ?)
                THEN TRUE ELSE FALSE
                END AS selected', [$id])
            ->where('pro.delete_flag', $delete_flag)->distinct()
            ->orderBy('pro.id', 'ASC');
        return $query->get();
    }

    public function findUpOffiByKey($id)
    {
        $query = DB::table('upload_offices as u')
            ->select('u.id', 'u.file_name', 'u.office_cd')
            ->selectRaw('CASE WHEN EXISTS (
                SELECT 1
                WHERE u.office_cd = ?) THEN TRUE ELSE FALSE END AS selected', [$id])
            ->where('u.upload_type', '2')
            ->orderBy('u.id','ASC');
        return $query->get();
    }

    //save info user
    function saveInformation($data)
    {
        DB::beginTransaction();
        try {
            $id = $data['id'];
            $proItem = $this->getListCheckbox($data, 'prod');
            $upOffi = $this->getListCheckbox($data, 'up_offi');
            $dataToSave = [
                'date_join' => $data['txtDate_join'],
                'first_name' => $data['txtFirstName'],
                'last_name' => $data['txtLastName'],
                'first_furigana' => $data['txtFirstFurigana'],
                'last_furigana' => $data['txtLastFurigana'],
                'work_status' => $data['cmbWorkStatus'],
                'office_cd' => $data['cmbOffice_cd'],
                'belong_cd' => $data['cmbBelong_cd'],
                'position' => $data['txtPosition'],
                'work_time_flag' => $data['cmbWork_time_flag'],
                'procedure_items' => $proItem,
                'company_mobile_number' => $data['txtCompany_mobile_number'],
                'manager' => $data['manager'] ?? '',
                'manage_position' => $data['manage_position'] ?? '',
                'flg1' => $data['flg1'] ?? '',
                'manage_business' => $data['manage_business'] ?? '',
                'accountant' => $data['accountant'] ?? '',
                'company_car' => $data['company_car'] ?? '',
                'flg3' => $data['flg3'] ?? '',
                'flg4' => $data['flg4'] ?? '',
                'flg5' => $data['flg5'] ?? '',
                'flg6' => $data['flg6'] ?? '',
                'flg7' => $data['flg7'] ?? '',
                'flg8' => $data['flg8'] ?? '',
                'flg9' => $data['flg9'] ?? '',
                'sign_items' => $upOffi,
                'email' => $data['email'],
                'confirm_email' => $data['confirm_email'],
                'note' => $data['txtNote'],
                'authority' => $this->checkAuthorityHD($data),
                'token' => Str::random(20),
                'procedure_type' => CommonConst::PROCEDURE_TYPE_U03
            ];
            if (empty($id)) $id = -1;
            if ($id > 0) {
                // update
                $info = InfoUsers::find($id);
                $dataToSave['users_number'] = $data['users_number'];
                $dataToSave['revision'] = (int)$info->revision + 1;
                $dataToSave['updated_id'] = Auth::id();
            } else {
                //insert
                $dataToSave['created_id'] = Auth::id();
                $dataToSave['users_number'] = $this->getUserNumMax($data);
                $dataToSave['author_id'] =  Auth::id();
                $dataToSave['author_date'] = now();
                $dataToSave['status_user'] = CommonConst::U03_STATUS_SUPERIOR;
            }
            $newInfo = InfoUsers::updateOrCreate(['id' => $id], $dataToSave);
            $id = $newInfo->id;

            //send mail click btn creator
            if ($newInfo && $data['mode'] == 'author') {
                $this->SendMailInfo($data, $id);
            }

            //upload file
            $this->registerFile($data, $id);
            DB::commit();
            UploadFunc::delTmpFolder();
            return $newInfo->id;
        } catch (\Exception $e) {
            DB::rollback();
            UploadFunc::delTmpFolder();
            throw $e;
        }
    }

    //get list checkbox
    function getListCheckbox($data, $name)
    {
        if (empty($data[$name])) return;
        $result = '';
        foreach ($data[$name] as $item) {
            if (!empty($data[$name . '_' . $item]) && $data[$name . '_' . $item] != 0)
                $result .= $item . ',';
        }
        return rtrim($result, ",");
    }

    //check AuthorityHD
    function checkAuthorityHD($data)
    {
        $manager = $data['manager'] ?? 0;
        $manage_position = $data['manage_position'] ?? 0;
        $flg1 = $data['flg1'] ?? 0;
        $cmbOffice_cd = $data['cmbOffice_cd'] ?? '';
        $auth = CommonConst::AUTHORITY_1STUSER;
        if($manage_position == 1 &&  $flg1 == 1 && $cmbOffice_cd == CommonConst::OFFICE_CD_HD ) {
            $auth = CommonConst::AUTHORITY_HD;
        }elseif($manage_position == 0 &&  $flg1 == 1  && $cmbOffice_cd == CommonConst::OFFICE_CD_HD ){
            $auth = CommonConst::AUTHORITY_HD_LABOUR;
        } elseif($manage_position == 1 ){ 
            $auth = CommonConst::AUTHORITY_ADMIN ;
        } else{ 
            $auth = CommonConst::AUTHORITY_1STUSER;
        }
        return $auth;
    }

    //set usernumber max
    public function getUserNumMax($data)
    {
        if ($data['cmbOffice_cd'] == CommonConst::OFFICE_LIFECARE) {
            //create users_number with condition office_name 0100000+
            $userNumMax = $this->getUsersNumberMax(true);
            if (!$userNumMax) {
                $userNumMax = CommonConst::USERNUM_LIMIT_END;
                $userNumMax = str_pad((int)ltrim($userNumMax, '0') + 1, 7, '0', STR_PAD_LEFT);
            } else {
                $userNumMax = str_pad((int)ltrim($userNumMax->users_number, '0') + 1, 7, '0', STR_PAD_LEFT);
            }
        } else {
            //create users_number auto max + 1 [000000,0100000]
            $userNumMax = $this->getUsersNumberMax();
            if (!$userNumMax) {
                $userNumMax = CommonConst::USERNUM_LIMIT_START;
                $userNumMax = str_pad((int)ltrim($userNumMax, '0') + 1, 7, '0', STR_PAD_LEFT);
            } else $userNumMax = str_pad((int)ltrim($userNumMax->users_number, '0') + 1, 7, '0', STR_PAD_LEFT);
        }
        return (string)$userNumMax;
    }

    //get usernumber max with condition
    function getUsersNumberMax($isLifeCare = null)
    {
        $query = DB::table('info_users');
        ($isLifeCare) ? $query->whereNotBetween('users_number', ['0000000', '0100000']) : $query->whereBetween('users_number', ['0000000', '0100000']);
        $query->select('users_number')->orderByDesc('users_number');
        return $query->first();
    }

    //send mail with mode
    public function SendMailInfo($data, $id)
    { 
        try {
            $user = $this->getInfoUserByKey($id);
            $hdList = $this->getHDApprove();
            $urlCreate=route('u034');
            $url=route('u03.showInfo',$id);
            $typeNoti = 'U03';
            $content = '';
            if ($data['mode'] == 'author') 
            {
                //clear all possible cases where the status is 上長入力中
                if($user->status_user == CommonConst::U03_STATUS_SUPERIOR)
                {
                    //clear mess author
                    $this->deleteMessage($user, $user->id_author, $id, $typeNoti, 'admin1Reject_send');
                    $this->deleteMessage($user, $user->id_author, $id, $typeNoti, 'admin2Reject_send');
                    $this->deleteMessage($user, $user->id_author, $id, $typeNoti);
                    $this->deleteMessage($user, $user->id_author, $id, $typeNoti, 'hdReject_send');
                }
            }
            $this->setStatusUser($data,$id);
            $user = $this->getInfoUserByKey($id);
            switch ($data['mode']) 
            {
                case 'author':
                    //send mail
                    Mail::to($data['email'])->send(new SendMailInfo($user, 'creator1',$urlCreate));
                    //Send mail ManageHD
                    foreach ($hdList as $hd) {
                        Mail::to($hd->email)->send(new SendMailInfo($user, 'creator2',$url,'', $hd));
                    }
                    break;
                case 'admin1':
                    $usersApp2 = $this->getSecondApprove($user->office_cd, $user->belong_cd, 2);
                    //send list app 2
                    foreach ($usersApp2 as $app2) {
                        //send mail 2
                        Mail::to($app2->email_app2)->send(new SendMailInfo($user, 'approval1',$url, $app2->name_app2));

                        //send mess 2
                        $content = $this->getContentMes($user,'admin1_send');
                        $this->sendNotice($app2->id, $id, $content, $typeNoti);
                    }
                    //delete mess 1
                    $usersApp1 = $this->getSecondApprove($user->office_cd, $user->belong_cd, 1);
                    foreach ($usersApp1 as $app1) {
                        $this->deleteMessage($user, $app1->id, $id, $typeNoti, 'admin1_del');
                    }
                    break;
                case 'admin1Reject':
                    //send mail author 
                    Mail::to($user->email_author)->send(new SendMailInfo($user, 'reject1',$url));

                    //send mess author 
                    $content = $this->getContentMes($user,'admin1Reject_send');
                    $this->sendNotice($user->id_author, $id, $content, $typeNoti);
                    
                    //delete mess 1
                    $usersApp1 = $this->getSecondApprove($user->office_cd, $user->belong_cd, 1);
                    foreach ($usersApp1 as $app1) {
                        $this->deleteMessage($user, $app1->id, $id, $typeNoti, 'admin1_del');
                    }
                    break;
                case 'admin2':
                    //send hd managerHD
                    foreach ($hdList as $hd) {
                        //send mail HD
                        Mail::to($hd->email)->send(new SendMailInfo($user, 'approval2',$url, '', $hd));

                        //send mess HD
                        $content = $this->getContentMes($user,'admin2_send');
                        $this->sendNotice($hd->id, $id, $content, $typeNoti);
                    }

                    //delete mess 2
                    $usersApp2 = $this->getSecondApprove($user->office_cd, $user->belong_cd, 2);
                    foreach ($usersApp2 as $app2) {
                        $this->deleteMessage($user, $app2->id, $id, $typeNoti, 'admin2_del');
                    }
                    break;
                case 'admin2Reject':
                    //send author 2
                    Mail::to($user->email_author)->send(new SendMailInfo($user, 'reject2',$url));

                    //send mess author 
                    $content = $this->getContentMes($user,'admin2Reject_send');
                    $this->sendNotice($user->id_author, $id, $content, $typeNoti);

                    //delete mess 2
                    $usersApp2 = $this->getSecondApprove($user->office_cd, $user->belong_cd, 2);
                    foreach ($usersApp2 as $app2) {
                        $this->deleteMessage($user, $app2->id, $id, $typeNoti, 'admin2_del');
                    }
                    break;
                case 'hd':
                    $url=route('login');
                    //random $password has letter and number
                    $password = $this->randomPassword();
                    //get auth infouser $id
                    $auth = DB::table('info_users')->where('id',$id)->value('authority');
                    //insert users table users get ID
                    $idUser = $this->registerUser($data, $password,$auth);
                    if ($idUser) {
                        $idInfo = User::find($idUser);
                        $user = $this->getInfoUserByKey($idInfo->info_users_cd, true);
                        //send mail user final
                        Mail::to($user->email)->send(new SendMailInfo($user, 'approvalHD',$url, '', '', $password));

                        //send mess author 
                        $content = $this->getContentMes($user);
                        $this->sendNotice($user->id_author, $id, $content, $typeNoti);

                        //delete mess hd
                        foreach ($hdList as $hd) {
                            $this->deleteMessage($user, $hd->id, $id, $typeNoti, 'hdReject_del');
                        }
                    }
                    break;
                case 'hdReject':
                    //send mail author
                    Mail::to($user->email_author)->send(new SendMailInfo($user, 'rejectHD',$url));

                    //send mess author
                    $content = $this->getContentMes($user,'hdReject_send');
                    $this->sendNotice($user->id_author, $id, $content, $typeNoti);
                    
                    //delete mess hd
                    foreach ($hdList as $hd) {
                        $this->deleteMessage($user, $hd->id, $id, $typeNoti, 'hdReject_del');
                    }
                    break;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    //send message
    public function sendNotice($idStaffEnd, $idConfim, $content, $notiType)
    {
        DB::beginTransaction();
        try {
            $InfoConfim = $this->getInfoUserByKey($idConfim);
            $dataToUpdate = [
                'notices_type' => $notiType,
                'notices_date' => now(),
                'notices_create_user' => Auth::id(),
                'office_short_name' => $InfoConfim->name_short_office,
                'belong_name' => $InfoConfim->name_belong,
                'notices_content' => $content,
                'info_users_cd' => $idConfim,
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
    //delete mess 
    public function deleteMessage($userInfo, $idUserEnd, $idInfoConfim, $typeNoti, $mode = null)
    {
        switch($typeNoti)
        {
            case 'U03':
                $content = $this->getContentMes($userInfo, $mode);
                break;
            case 'U07':
                $u07service = new U07Service();
                $content = $u07service->getContentMesU07($userInfo, $mode);
                break;
            case 'U04':
                $u04service = new U04Service();
                $content = $u04service->getContentMesU04($userInfo, $mode);
                break;    
            case 'U05':
                $u05service = new U05Service();
                $content = $u05service->getContentMesU05($userInfo, $mode);
                break;
            case 'U11':
                $u11service = new U11Service();
                $content = $u11service->getContentMesU11($userInfo, $mode);
                break;
            default:
                $content = "";
        }
        $idNotice = $this->getIdNotice($idUserEnd, $idInfoConfim, $content, $typeNoti);
        if(!empty($idNotice))
            $this->deleteNotice($idNotice);
    }

    public function getContentMes($userInfo, $mode = null )
    {
        if(empty($mode))
        {
            $content = '入職手続きが完了しましたのでご連絡いたします。';
        }else
        {
            $content = $userInfo->first_name.' '.$userInfo->last_name;
            switch ($mode) 
            {
                case 'admin1_send':
                    $content .= 'さんの入職手続きの2次承認の依頼があります。';
                    break;
                case 'admin1_del':
                    $content .= 'さんの入職手続きの1次承認の依頼があります。';
                    break;
                case 'admin2_send':
                    $content .= 'さんの入職手続きのHD承認の依頼があります。';
                break;
                case 'admin2_del':
                    $content .= 'さんの入職手続きの2次承認の依頼があります。';
                    break;
                case 'admin1Reject_send':
                    $content .= 'さんの入職手続きが1次承認者に否認されました。';
                    break;
                case 'admin2Reject_send':
                    $content .= 'さんの入職手続きが2次承認者に否認されました。';
                    break;
                case 'hdReject_send':
                    $content .= 'さんの入職手続きのHD承認者に否認されました。';
                    break;
                case 'hdReject_del':
                    $content .= 'さんの入職手続きのHD承認の依頼があります。';
                break;
            }
        }
        return $content;
    }

    //get id Notice 
    public function getIdNotice($idUserEnd, $idInfoConfim, $content, $typeNoti){
        return DB::table('notices')
        ->where('notices_type', $typeNoti)
        ->where('notices_dest_user', $idUserEnd)
        ->where('info_users_cd', $idInfoConfim)
        ->where('notices_content', $content)
        ->value('id');
    }

    //delete notice
    public function deleteNotice($id)
    {
        return DB::transaction(function () use ($id) {
            return Notices::where("id", $id)->delete();
        });
    }

    //update status user
    public function setStatusUser($data,$id){
        $dataToSave = [
            'note' => $data['txtNote'],
        ];
        try{
            switch ($data['mode']) {
                case 'author':
                    $dataToSave['author_id'] = Auth::id();
                    $dataToSave['author_date'] = now();
                    $dataToSave['status_user'] = CommonConst::U03_STATUS_CREATOR;
                    $dataToSave['email'] = $data['email'];
                    $dataToSave['confirm_email'] = $data['confirm_email'];
                    break;
                case 'admin1':
                    $dataToSave['admin1_id'] = Auth::id();
                    $dataToSave['admin1_date'] = now();
                    $dataToSave['status_user'] = CommonConst::U03_STATUS_APPROVAL_2;
                    break;
                case 'admin2':
                    $dataToSave['admin2_id'] = Auth::id();
                    $dataToSave['admin2_date'] = now();
                    $dataToSave['status_user'] = CommonConst::U03_STATUS_APPROVAL_HD;
                    break;
                case 'hd':
                    $dataToSave['hd_id'] = Auth::id();
                    $dataToSave['hd_date'] = now();
                    $dataToSave['status_user'] = CommonConst::STATUS_COMPLETED;
                    break;
                case 'btnHD':
                    $dataToSave['hd_confirm_date'] = now();
                    break;
                case ('admin1Reject' || 'admin2Reject' || 'hdReject'):
                    $dataToSave['status_user'] = CommonConst::U03_STATUS_SUPERIOR;
                    $dataToSave['applicant_id'] = null;
                    $dataToSave['applicant_date'] = null;
                    $dataToSave['admin1_id'] = null;
                    $dataToSave['admin1_date'] = null;
                    $dataToSave['admin2_id'] = null;
                    $dataToSave['admin2_date'] = null;
                    $dataToSave['hd_id'] = null;
                    $dataToSave['hd_date'] = null;
                    //$this->deleteGuarantee($id);
                    break;
                }
            return  InfoUsers::updateOrCreate(['id' => $id], $dataToSave);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function getInfoUserByKey($id, $isHd = false)
    {
        $query = DB::table('info_users as info')
            ->leftJoin('users as au', 'au.id', '=', 'info.author_id')
            ->leftJoin('mst_offices as o', 'o.office_cd', '=', 'info.office_cd')
            ->leftJoin('mst_belong as bl', 'bl.belong_cd', '=', 'info.belong_cd')
            ->where('info.id', $id);
        if ($isHd) {
            $query->leftJoin('users as u', 'u.info_users_cd', '=', 'info.id')
                ->select(
                    'u.id as userId',
                    'info.*',
                    'u.id as id_user',
                    'au.name as name_author',
                    'au.email as email_author',
                    'info.author_id as id_author',
                    'o.office_name as name_office',
                    'o.office_short_name as name_short_office',
                    'bl.belong_name as name_belong',
                );
        } else {
            $query->select(
                'info.*',
                'au.name as name_author',
                'au.email as email_author',
                'info.author_id as id_author',
                'o.office_name as name_office',
                'o.office_short_name as name_short_office',
                'bl.belong_name as name_belong',
            );
        }
        return $query->first();
    }

    //get all HD
    public function getHDApprove()
    {
        $query = DB::table('info_users as i')
            ->leftJoin('users as user', 'i.id', '=', 'user.info_users_cd')
            ->where('i.office_cd', '=', CommonConst::OFFICE_CD_HD)
            ->where('i.manager', '=', '1')
            ->where('i.flg1', '=', '1')
            ->where('i.date_join', '<=', date('Y-m-d'))
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereNotNull('user.retire_date')
                        ->where('user.retire_date', '>=', date('Y-m-d'));
                })
                ->orWhereNull('user.retire_date');
            })
            ->whereNotNull('user.id');
        return $query->get();
    }

    //get all email and name App 2
    public function getSecondApprove($office_cd, $belong_cd, $type = null)
    {
        $query = DB::table('info_users as info')
            ->leftJoin('mst_structure as s', function ($join) {
                $join->on('s.belong_cd', '=', 'info.belong_cd')
                        ->on('s.office_cd', '=', 'info.office_cd');
            })
            ->leftJoin('mst_employment_structure as emply', 's.id', '=', 'emply.structure_id')
            ->leftJoin('users as u', 'u.id', '=', 'emply.staff_id')
            ->where('emply.type', '=', $type)
            ->where('info.office_cd', '=', $office_cd)->where('info.belong_cd', '=', $belong_cd)
            ->whereDate('s.start_date','<=',date('Y-m-d'))
            ->when('s.end_date', function ($query, $endDate) {
                return $query->where(function ($q) use ($endDate) {
                    return $q->whereDate($endDate, '>=', date('Y-m-d'))
                            ->orwhereNull($endDate);
                });
            })
            ->when('u.retire_date', function ($query, $retireDate) {
                return $query->where(function ($q) use ($retireDate) {
                    return $q->whereDate($retireDate, '>', date('Y-m-d'))
                            ->orwhereNull($retireDate);
                });
            })      
            ->select(
                'u.id',
                'u.email as email_app2',
                'u.name as name_app2',
            )
            ->distinct('u.id');
        return $query->get();
    }

    //random password with 4 char and 4 number
    function randomPassword()
    {
        $letters = str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $numbers = str_shuffle('0123456789');
        $password = substr($letters, 0, 4) . substr($numbers, 0, 4);
        return str_shuffle(substr($password, 0, 8));
    }

    //insert user
    function registerUser($data, $password, $auth)
    {
        $id = DB::table('users')->insertGetId([
            'name' => $data['txtFirstName'] . ' ' . $data['txtLastName'],
            'info_users_cd' => $data['id'],
            'email' => $data['email'],
            'password' => bcrypt($password),
            'authority' => $auth,
            'created_at' => now(),
            'created_id' => Auth::id(),
            'updated_at' => now(),
            'updated_id' => Auth::id(),
        ]);
        return $id;
    }

    public function findUpFileById($id, $ver_his = 0, $isTemplate=false)
    {
        $tbl = 'upload_info_users as up';
        if($isTemplate) $tbl = 'w_upload_info_users as up';

        $upInfUser = DB::table($tbl)
            ->where('up.info_users_cd', $id)
            ->where('up.ver_his', $ver_his)
            ->get();
        $result = [];
        foreach ($upInfUser as $row) {
            $param = [
                'id' => $row->id,
                'folder_name' => $row->folder_name,
                'file_name' => $row->file_name,
                'path' => $row->folder_path . $row->folder_name . $row->file_name,
                'ver_his' => $row->ver_his,
            ];
            $result['type' . $row->upload_type][] = $param;
        }
        return $result;
    }

    function registerFile($data, $id)
    {
        $this->checkUploadFile('Resume', $data, $id, CommonConst::UPLOAD_TYPE_1);
        $this->checkUploadFile('Conditions', $data, $id, CommonConst::UPLOAD_TYPE_2);
        $this->checkUploadFile('CollectionFees', $data, $id, CommonConst::UPLOAD_TYPE_3);
        $this->checkUploadFile('Contract', $data, $id, CommonConst::UPLOAD_TYPE_4);
    }

    function checkUploadFile($name, $data, $id, $type)
    {
        if ($data['cur' . $name]) {
            if ($data['tmp' . $name]) {
                if ($data['hid' . $name . 'Id']) $this->deleteFileById($data['hid' . $name . 'Id']);
                $this->uploadFile($name, $data, $id, $type);
            }
        } else {
            if ($data['hid' . $name . 'Id']) $this->deleteFileById($data['hid' . $name . 'Id']);
        }
    }

    public function uploadFile($name, $data, $infoUserCd, $type,$ver_his = 0)
    {
        $fileNm = UploadFunc::getFileName($data['tmp' . $name]);
        $upFile = UploadInfoUsers::updateOrCreate(['upload_type' => $type, 'info_users_cd' => $infoUserCd], [
            'info_users_cd' => $infoUserCd,
            'ver_his' => $ver_his,
            'folder_path' => CommonConst::UPLOAD_FOLDER_PATH,
            'file_name' => $fileNm,
            'created_id' => Auth::id(),
            'updated_id ' => Auth::id(),
        ]);
        $upFile->save();
        $upFile->folder_name = CommonConst::UPLOAD_INFO_USERS . $infoUserCd . '/' . $type .'/' . $ver_his .'/' . $upFile->id . '/' ;
        $upFile->save();
        return UploadFunc::moveFile($data['tmp' . $name],  $upFile->folder_name . $fileNm);
    }

    public function deleteFileById($id, $isDel = true,$ver_his = 0)
    {
        $file = UploadInfoUsers::where("id", $id)->where("ver_his", $ver_his)->first();
        if ($file) {
            UploadFunc::delFile($file->folder_name . $file->file_name);
            if ($isDel) $file->delete();
        }
    }

    //delete 
    public function deleteInfoUser($id,$ver_his = 0)
    {
        InfoUsers::where("id", $id)->where("ver_his", CommonConst::VER_HIS)->delete();
        CommonFunc::deleteFolder(CommonConst::UPLOAD_FOLDER_PATH . CommonConst::UPLOAD_INFO_USERS . $id,true );
        UploadInfoUsers::where("info_users_cd", $id)->where("ver_his", $ver_his )->delete();
        return true;
    }

    // end infomation u03-1

    //u039
    public function findW1Info($idInfo){
        $query = db::table('w1_info_users as w1')
        ->leftJoin('info_users as i', 'i.id','=','w1.info_users_cd')
        ->select(
            'w1.*',
            'i.birthday',
        )
        ->where('w1.info_users_cd', $idInfo)
        ->first();
        return $query;
    }
    public function regisApliHouses($data,$ver_his = 0){
        DB::beginTransaction();
        try {
            $id = $data['hidId'];
            $arrUpdateInfo = [];
            $login_id = Auth::id();
            $currentDate = date('Y/m/d H:i:s');
            $arrUpdateInfo = [
                'payment_date' => $data['hidPaymentDate'],
                'payment_amount' => $data['hidPaymentmount'] ? str_replace(',', '', $data['hidPaymentmount']) : 0 ,
            ];

            $arrUpdateProdUser = [
                'U03-9_date' => $currentDate,
                'U03-9_id' => $login_id
            ];

            if($data['hidScr'] == 'U03'){
                InfoUsers::updateOrCreate(['id' => $id, 'ver_his' => $ver_his] , $arrUpdateInfo);
                ProceduresUsers::updateOrCreate(['info_users_cd' => $id, 'ver_his' => $ver_his], $arrUpdateProdUser);
            }
            else{
                W1InfoUser::updateOrCreate(['id' => $id, 'ver_his_U05' => $ver_his] , $arrUpdateInfo);
                $w1Info = W1InfoUser::where('id', $id)->first();
                W1ProceduresUser::updateOrCreate(['info_users_cd' => $w1Info->info_users_cd, 'ver_his' => $ver_his], $arrUpdateProdUser);
            }
            
            $isConvert = false;
            if($data['hidScr'] == 'U03' && $data['hidOldId']) {
                $this->copyHouse($data);
                $isConvert = true;
            }

            $this->registerHouseFile($data, $ver_his, $isConvert);

            DB::commit();
            UploadFunc::delTmpFolder();
        
        } catch (\Exception $e) {
            DB::rollBack();
            UploadFunc::delTmpFolder();
            throw $e;
        }
    }

    public function copyHouse($data){
        $infoId =  $data['hidId'];
        $baseDir = CommonConst::UPLOAD_INFO_USERS . $infoId . '/';
        $verHis = 0;

        $oldInfoId = $data['hidOldId'];
        $oldBaseDir = CommonConst::UPLOAD_INFO_USERS . $oldInfoId . '/';
        $oldVerHis = $data['hidOldVerHis'];
        $oldInfo = InfoUsers::where('id', $oldInfoId)->where('ver_his_U05', $oldVerHis)->first();

        if($oldInfo){
            $info = [
                'rental_folder_path' => CommonConst::UPLOAD_FOLDER_PATH,
                'rental_folder_name' => $baseDir . CommonConst::UPLOAD_RENTAL . $verHis . '/',
                'rental_file_name' => $oldInfo->rental_file_name,
                'evidence_folder_path' => CommonConst::UPLOAD_FOLDER_PATH,
                'evidence_folder_name' => $baseDir . CommonConst::UPLOAD_EVIDENCE . $verHis . '/',
                'evidence_file_name' => $oldInfo->evidence_file_name,
            ];
            InfoUsers::where('id', $infoId)->where('ver_his_U05', $verHis)->update($info);

            if($info['rental_file_name']) UploadFunc::copyDirectory($oldBaseDir . CommonConst::UPLOAD_RENTAL . $oldVerHis, $baseDir . CommonConst::UPLOAD_RENTAL . $verHis);
            if($info['evidence_file_name']) UploadFunc::copyDirectory($oldBaseDir . CommonConst::UPLOAD_EVIDENCE . $oldVerHis, $baseDir . CommonConst::UPLOAD_EVIDENCE . $verHis);
        }
    }

    public function registerHouseFile($data, $ver_his=0, $isConvert = false)
    {
        $id = $data['hidId'];
        $mode = null;
        if($data['hidScr'] == 'U03'){
            $file = InfoUsers::where('id',$id)->first();
            $mode = true;
        }
        else{
            $file = W1InfoUser::where('id',$id)->first();
            $mode = false;
        }
        //rental
        if ($data['curRental']){
            if($data['tmpRental']) 
            {
                $this->uploadHouseFile("Rental",$file, $data, $ver_his, $mode, $isConvert);
            }
        }

        //evidence
        if ($data['curEvidence']){
            if($data['tmpEvidence']) 
            {
                $this->uploadHouseFile("Evidence",$file, $data, $ver_his, $mode, $isConvert);
            }
        }
    }
	
    public function uploadHouseFile($name,$file, $data, $ver_his, $mode, $isConvert = false){
        $id = $data['hidId'];
        //$fileCur = $data['cur' . $name];
        $fileTmp = $data['tmp' . $name];
        $fileOld = $data['old' . $name];
        if($isConvert) {
            $fileOld = str_replace('/' . $data['hidOldId'] . '/', '/' . $data['hidId'] . '/', $fileOld);
            $fileOld = str_replace('/' . $data['hidOldVerHis'] . '/', '/' . 0 . '/', $fileOld);
        }
        $baseDir = null;
        $userCd = null;
        if($mode){
            $baseDir = CommonConst::UPLOAD_INFO_USERS;
            $userCd = $id;
        }else{
            $baseDir = CommonConst::UPLOAD_W1_INFO_USERS;
            $info = W1InfoUser::where('id', $id)->first();
            $userCd = $info->info_users_cd;
        }

        $fileNm = UploadFunc::getFileName($data['tmp' . $name]);
        if($name == "Rental"){
            $upFile = $file->updateOrCreate(['id' => $id], [
                'rental_folder_path' => CommonConst::UPLOAD_FOLDER_PATH,
                'rental_file_name' => $fileNm,
            ]);
            $upFile->save();
            $upFile->rental_folder_name = $baseDir . $userCd . '/' . 'rental/' . $ver_his . '/';
            $upFile->save();
            if($fileOld){
                UploadFunc::delFile($fileOld);
            }
            $result = UploadFunc::moveFile($fileTmp,  $upFile->rental_folder_name . $fileNm);
        }
        if($name == 'Evidence'){
            $upFile = $file->updateOrCreate(['id' => $id], [
                'evidence_folder_path' => CommonConst::UPLOAD_FOLDER_PATH,
                'evidence_file_name' => $fileNm,
            ]);
            $upFile->save();
            $upFile->evidence_folder_name = $baseDir .  $userCd . '/' . 'evidence/' . $ver_his . '/';
            $upFile->save();
            if($fileOld){
                UploadFunc::delFile($fileOld);
            }
            $result = UploadFunc::moveFile($fileTmp,  $upFile->evidence_folder_name . $fileNm);
        }
        return $result ;
    }
    //end U039
    
    //Aplication Guarantee U035
    public function findGuranteeById($idInfo, $ver_his = 0){
        $query = DB::table('guarantee_users as gur')
        ->leftJoin('info_users as inf','gur.info_users_cd','=','inf.id')
        ->select(
            'gur.info_users_cd',
            'gur.address1',
            'gur.address2',
            'gur.address3',
            'gur.name',
            'gur.relationship',
            'gur.career',
            'gur.my_number',
            'gur.sign_folder_path',
            'gur.sign_folder_name',
            'gur.sign_file_name',
            'gur.created_id',
            'gur.updated_id',
            'gur.updated_at',
        )
        ->where('gur.info_users_cd',$idInfo)
        ->where('gur.ver_his',$ver_his)
        ->first();
        return $query;
    }
    public function findOfiiceByKey($id){
        $query = DB::table('info_users as u')
        ->leftJoin('mst_belong as be', function ($join) {
            $join->on('u.belong_cd', '=', 'be.belong_cd')
                ->on('u.office_cd', '=', 'be.office_cd');
        })
        ->leftJoin('mst_offices as o', 'u.office_cd', '=', 'o.office_cd')
        ->select(
            'u.*',
            'o.office_short_name',
            'be.belong_name',
            'o.office_name',
            'o.office_director',
        )
        ->where('u.id',$id)
        ->first();
        return $query;
    }

    public function regisGuarantee($data){
        DB::beginTransaction();
        try {
            
            $idUsers = $data['hidIdUses'];
            $status = null;
            //insert table Info_users
            $info_Us = GuranteeUsers::where('info_users_cd',$idUsers)->first();
            $arrUpUser = [];
            if(empty($info_Us)) 
            {
                $status = CommonConst::U03_STATUS_APPROVAL_1;
                $token = null;
                $arrUpUser = [
                'status_user' => $status,
                'token'       => $token,
                'token_guarantee' => null,
                ];
                InfoUsers::updateOrCreate(['id' => $idUsers], $arrUpUser);
            }
            //insert table guarantee 
            $arrGuarantee = [];
            $imgNm = "身元保証_" . date('YmdHis');																

            $arrGuarantee = [
                'info_users_cd' => $idUsers,
                'address1' => $data['guranteeAdres1'],
                'address2' => $data['guranteeAdres2'],
                'address3' => $data['guranteeAdres3'],
                'name' => $data['guranteeName'],
                'relationship' => $data['guranteeRelaship'],
                'career'    => $data['guranteeCaree'],
                'my_number'     => $data['guranteeNumber'],
                'sign_folder_path' => CommonConst::UPLOAD_FOLDER_PATH,
                'sign_file_name' => $imgNm . '.png',
                'created_id' => Auth::id(),
                'updated_id	' => Auth::id(),
                'sign_folder_name' => CommonConst::UPLOAD_INFO_USERS . $idUsers . '/guarantee'. '/',
            ];
            $object = GuranteeUsers::updateOrCreate(['info_users_cd' => $idUsers], $arrGuarantee);

            if(empty($info_Us)){
                $this->SendMailGuar($idUsers,$data);
            }

            $image = str_replace('data:image/png;base64,', '', $data['signContent']);
            $image = str_replace(' ', '+', $image);
            $imageName = $object->sign_folder_name.$imgNm.'.png';

            Storage::disk('public')->put($imageName, base64_decode($image));

            DB::commit();
            return $object->info_users_cd;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function SendMailGuar($idUsers, $data)
    {
        $arrNotice = [];
        $currentDate = date('Y/m/d H:i:s');
        $content_mail = null;
        $infoUser= $this->findAppliByIdInfoUser($idUsers);
        $content_mail = $this->getContentMes($infoUser, 'admin1_del');
        $url=route('u03.showInfo', $idUsers);
        $LisUs1 = $this->getSecondApprove($infoUser->office_cd, $infoUser->belong_cd,1);
        foreach($LisUs1 as $UserOne)
        {
            $arrNotice = [
                'notices_type' => 'U03',
                'notices_date' => $currentDate,
                'notices_create_user' => Auth::id(),
                'office_short_name' =>  $data['hidOficeShort'],
                'belong_name'   => $data['hidBeName'],
                'notices_content' => $content_mail,
                'notices_dest_user' => $UserOne->id,
                'info_users_cd' => $infoUser->id,
                'notices_visible' => CommonConst::NOTICE_INDICATION,
            ];
            Notices::updateOrCreate(['notices_dest_user' => $UserOne->id, 'notices_type' => 'U03'], $arrNotice);  
            Mail::to($UserOne->email_app2)->send(new SendMailGuar($infoUser,$url,$UserOne));
        }
    }

    //u038 Private Car			
    public function regisPrivateCar($id,$data,$ver_his = 0, $mode){
        DB::beginTransaction();
        try {
            
            $arrPrivateCar = [];
            $arrProceUsers = [];
            $login_id = Auth::id();
            $currentDate = date('Y/m/d H:i:s');

            $arrPrivateCar = [
                'info_users_cd' => $id,
                'ver_his'   => $ver_his,
                'car_expiry_date' => $data['txtCar_expiry_date'],
                'insure_start_date' => $data['insure_start_date'],
                'insure_end_date' => $data['insure_end_date'],
                'com_insure_start_date' =>$data['com_insure_start'],
                'com_insure_end_date' => $data['com_insure_end'],
            ];

            $arrProceUsers = [
                'U03-8_date' => $currentDate,
                'U03-8_id' => $login_id
            ];

            //U03
            if($mode == true)
            {
                PrivateCar::updateOrCreate(['info_users_cd' => $id, 'ver_his' => $ver_his], $arrPrivateCar);  
                ProceduresUsers::updateOrCreate(['info_users_cd' => $id, 'ver_his' => $ver_his], $arrProceUsers);              
            }
            
            //U05
            if($mode == false){
                W1PrivateCarUser::updateOrCreate(['info_users_cd' => $id, 'ver_his' => $ver_his], $arrPrivateCar);
                W1ProceduresUser::updateOrCreate(['info_users_cd' => $id, 'ver_his' => $ver_his], $arrProceUsers);
            }

            $isConvert = false;
            if($mode && $data['hidOldId']) {
                $this->copyCar($data);
                $isConvert = true;
            }
            
            $this->registerCarFile($id,$data,$ver_his,$mode, $isConvert);
    
            DB::commit();
            UploadFunc::delTmpFolder();

        } catch (\Exception $e) {
            DB::rollBack();
            UploadFunc::delTmpFolder();
            throw $e;
        }
    }

    public function copyCar($data){
        $infoId =  $data['hidIdInfo'];
        $baseDir = CommonConst::UPLOAD_INFO_USERS . $infoId . '/' . CommonConst::UPLOAD_PRIVATE_CAR;
        $verHis = 0;

        $oldInfoId = $data['hidOldId'];
        $oldBaseDir = CommonConst::UPLOAD_INFO_USERS . $oldInfoId . '/' . CommonConst::UPLOAD_PRIVATE_CAR;
        $oldVerHis = $data['hidOldVerHis'];
        $oldCar = PrivateCar::where('info_users_cd', $oldInfoId)->where('ver_his', $oldVerHis)->first();

        if($oldCar){
            $car = [
                'car_folder_path' => CommonConst::UPLOAD_FOLDER_PATH,
                'car_folder_name' => $baseDir . 'car/' . $verHis . '/',
                'car_file_name' => $oldCar->car_file_name,
                'insure_folder_path' => CommonConst::UPLOAD_FOLDER_PATH,
                'insure_folder_name' => $baseDir . 'insure/' . $verHis . '/',
                'insure_file_name' => $oldCar->insure_file_name,
                'com_insure_folder_path' => CommonConst::UPLOAD_FOLDER_PATH,
                'com_insure_folder_name' => $baseDir . 'com_insure/' . $verHis . '/',
                'com_insure_file_name' => $oldCar->com_insure_file_name,
            ];
            PrivateCar::where('info_users_cd', $infoId)->where('ver_his', $verHis)->update($car);
            if($car['car_file_name']) UploadFunc::copyDirectory($oldBaseDir . 'car/' . $oldVerHis, $baseDir . 'car/' . $verHis);
            if($car['insure_file_name']) UploadFunc::copyDirectory($oldBaseDir . 'insure/' . $oldVerHis, $baseDir . 'insure/' . $verHis);
            if($car['com_insure_file_name']) UploadFunc::copyDirectory($oldBaseDir . 'com_insure/' . $oldVerHis, $baseDir . 'com_insure/' . $verHis);
        }
    }

    public function registerCarFile($id,$data, $ver_his, $mode, $isConvert){
        if($mode == true){
            $file = PrivateCar::where('info_users_cd',$id)->where('ver_his', $ver_his)->first();
        }
        else{
            $file = W1PrivateCarUser::where('info_users_cd',$id)->where('ver_his', $ver_his)->first();
        }
        if ($data['curCar']){
            if($data['tmpCar']) 
            {
                $this->uploadCarFile("Car", $file, $data, $ver_his, $mode, $isConvert);
            }
        }
        //Insure
        if ($data['curInsure']){
                if($data['tmpInsure']) 
                {
                    $this->uploadCarFile("Insure", $file, $data, $ver_his, $mode, $isConvert);
                }
        }
        else{
            $this->deleteFileCarById('Insure', $id, $data,$ver_his, true, $isConvert);
        }
        //Com_insure
        if ($data['curCom_insure']){
            if($data['tmpCom_insure']) 
            {
                $this->uploadCarFile("Com_insure", $file, $data, $ver_his, $mode, $isConvert);
            }
        }
        else{
            $this->deleteFileCarById('Com_insure',$id,$data,$ver_his, true, $isConvert);
        }
    }

    public function deleteFileCarById($name, $id, $data, $ver_his = 0, $isDel= true, $isConvert=false)
    {
        if($data['hidScr'] == 'U03')
        {
            $file = PrivateCar::where('info_users_cd',$id)->where('ver_his', $ver_his)->first();
        }else{
            $file = W1PrivateCarUser::where('info_users_cd',$id)->where('ver_his', $ver_his)->first();
        }
        $oldNm = 'old'.$name;
        $fileOld = $data[$oldNm];
        if($isConvert) {
            $fileOld = str_replace('/' . $data['hidOldId'] . '/', '/' . $data['hidIdInfo'] . '/', $fileOld);
            $fileOld = str_replace('/' . $data['hidOldVerHis'] . '/', '/' . 0 . '/', $fileOld);
        }

        $name = strtolower($name);

        if ($file) {
            UploadFunc::delFile($fileOld);
            if($isDel) $file->update([
                $name.'_folder_path' => null,
                $name.'_file_name' => null,
                $name.'_folder_name' => null, 
            ]);
        }
    }

    public function uploadCarFile($name, $file, $data, $ver_his, $mode, $isConvert=false){
        $fileTmp = $data['tmp' . $name];
        $fileOld = $data['old' . $name];
        if($isConvert) {
            $fileOld = str_replace('/' . $data['hidOldId'] . '/', '/' . $data['hidIdInfo'] . '/', $fileOld);
            $fileOld = str_replace('/' . $data['hidOldVerHis'] . '/', '/' . 0 . '/', $fileOld);
        }
        $folder = 'private_car/';
        $fileNm = UploadFunc::getFileName($data['tmp' . $name]);
        $upFile = $file;
        $baseDir = $mode ? CommonConst::UPLOAD_INFO_USERS : CommonConst::UPLOAD_W1_INFO_USERS;
        //car
        if($name == "Car"){
            $upFile->update([
                'car_folder_path' => CommonConst::UPLOAD_FOLDER_PATH,
                'car_file_name' => $fileNm,
                'car_folder_name' => $baseDir . $upFile->info_users_cd . '/'. $folder . 'car/'. $ver_his . '/',
            ]);
            
            if($fileOld){
                UploadFunc::delFile($fileOld);
            }
            $result = UploadFunc::moveFile($fileTmp,  $upFile->car_folder_name . $fileNm);
        }

        //insure
        if($name == 'Insure'){
            $upFile->update([ 
                'insure_folder_path' => CommonConst::UPLOAD_FOLDER_PATH,
                'insure_file_name' => $fileNm,
                'insure_folder_name' => $baseDir . $upFile->info_users_cd . '/'. $folder  . 'insure/'. $ver_his. '/',
            ]);
            if($fileOld){
                UploadFunc::delFile($fileOld);
            }
            $result = UploadFunc::moveFile($fileTmp,  $upFile->insure_folder_name . $fileNm);
        }
        //com_insure
        if($name == 'Com_insure'){
            $upFile->update([
                'com_insure_folder_path' => CommonConst::UPLOAD_FOLDER_PATH,
                'com_insure_file_name' => $fileNm,
                'com_insure_folder_name' => $baseDir . $upFile->info_users_cd. '/'. $folder  . 'com_insure/'. $ver_his . '/',
            ]);
            if($fileOld){
                UploadFunc::delFile($fileOld);
            }
            $result = UploadFunc::moveFile($fileTmp,  $upFile->com_insure_folder_name . $fileNm);
        }
        return $result ;
    }
    //end U038 Private Car Users	

//u033 contract
    public function finContractInfoByInfoId($id, $ver_his=0, $isTemplate=false)
    {
        $tbl = 'contract_info_users';
        if($isTemplate) $tbl = 'w1_contract_info_users';
        $query = DB::table($tbl)->where('info_users_cd', '=', $id)->where('ver_his', $ver_his);
        return $query->first();
    }

    public function finByContInfoIdBenefit($contInfoId, $isTemplate=false)
    {
        $tbl = 'benefit_info_users';
        if($isTemplate) $tbl = 'w1_benefit_info_users';
        $query = DB::table($tbl)
            ->where('contract_info_users_id', $contInfoId)->orderBy('line_no');
        return $query->get();
    }

    /**
     * Register Contract.
     *
     * @param  array  $data
     * @return bool
     */
    public function saveContract($data, $ver_his=0)
    {
        return DB::transaction(function () use ($data, $ver_his) {
            return $this->registerContractInfo($data, $ver_his);
        });
    }

    /**
     * Insert or update Employment Contract.
     *
     * @param  array  $data
     * @return bool
     */
    public function registerContractInfo($data, $ver_his =0)
    {
        $idInfo = $data['idInfo'];
        $idCont = $data['id'];
        $checkAdd = false;
        $dataToSave = [
            'ver_his' => $ver_his,
            'limit_flag' => $data['cmbContract_limit_flag'],
            'limit_date_start' => $data['txtLimit_date_start'],
            'limit_date_end' => $data['txtLimit_date_end'],
            'trial_flag' => $data['cmbTrial_flag'],
            'trial_date_start' => $data['txtTrial_date_start'],
            'trial_date_end' => $data['txtTrial_date_end'],
            'renewed_flag' => $data['cmbRenewed_flag'],
            'renewed_decision' => $data['txtRenewed_decision'],
            'renewed_up' => $data['txtRenewed_up'],
            'special_law' => $data['txtSpecial_law'],
            'work_place' => $data['txtWork_place'],
            'work_content1' => $data['txtWork_content1'],
            'work_content2' => $data['txtWork_content2'],
            'work_hours' => $data['txtWork_hours'],
            'schedule_work_hours' => $data['txtSchedule_work_hours'],
            'holiday_time' => $data['txtHoliday_time'],
            'work_overtime_flag' => $data['cmbWork_overtime_flag'],
            'allowance_text' => $data['txtAllowance_text'],
            'work_holiday_flag' => $data['cmbWork_holiday_flag'],
            'holidays' => $data['txtHolidays'],
            'vacation' => $data['txtVacation'],
            'month_salary' => (int) str_replace(',', '', $data['txtMonth_salary']),
            'daily_salary' => (int) str_replace(',', '', $data['txtDaily_salary']),
            'hourly_salary' => (int) str_replace(',', '', $data['txtHourly_salary']),
            'below_60_hour' => (int) $data['txtBelow_60_hour'],
            'over_60_hour' => (int) $data['txtOver_60_hour'],
            'standard_amount' => (int) $data['txtStandard_amount'],
            'holiday_legal' => (int) $data['txtHoliday_legal'],
            'holiday_non_legal' => (int) $data['txtHoliday_non_legal'],
            'work_night_shift' => (int) $data['txtWork_night_shift'],
            'off_date_number' => (int) $data['txtOff_date_number'],
            'payment_date_number' => (int) $data['txtPayment_date_number'],
            'payment_method' => $data['cmbPayment_method'],
            'agreement_labor_flag' => $data['cmbAgreement_labor_flag'],
            'agreement_labor' => $data['txtAgreement_labor'],
            'salary_modify_flag' => $data['cmbSalary_modify_flag'],
            'salary_modify' => $data['txtsalary_modify'],
            'bonus_flag' => $data['cmbBonus_flag'],
            'bonus' => $data['txtBonus'],
            'retired_salary_flag' => $data['cmbRetired_salary_flag'],
            'retired_salary' => $data['txtRetired_salary'],
            'retire_limit_age_flag' => $data['cmbRetire_limit_age_flag'],
            'retire_limit_age' => $data['txtRetire_limit_age_flag'],
            'reemployment_age_flag' => $data['cmbReemployment_age_flag'],
            'reemployment_age' => $data['txtReemployment_age_flag'],
            'retired_reason' => $data['txtRetired_reason'],
            'dismiss_reason' => $data['txtDismiss_reason'],
            'other_reason' => $data['txtOther_reason'],
            // 'social_insurance' => $data['cmbSocial_insurance'],
            'estimated_amount' => (int) str_replace(',', '', $data['txtEstimated_amount']),
            // 'employment_insurance' => $data['cmbEmployment_insurance'],
            'work_hours_per_week' => $data['txtWork_hours_per_week'],
            'public_date' => $data['txtPublicDate'],
        ];

        $tbl = ($data['hidScr'] == 'u03' || $data['hidScr'] == 'u111') ? 'contract_info_users' : 'w1_contract_info_users';

        // if id null then create
        $contract = null;
        if (empty($idCont)) $idCont = -1;
        if ($idCont > 0) {
            // update
            $contract = DB::table($tbl)->where('id', $idCont)->where('ver_his', $ver_his)->first();
            $dataToSave['revision'] = (int)$contract->revision + 1;
            $dataToSave['updated_id'] = Auth::id();
        } else {
            //insert
            $dataToSave['info_users_cd'] =  $idInfo;
            $dataToSave['created_id'] = Auth::id();
            $dataToSave['revision'] = 0;
            $checkAdd = true;
        }

        if($tbl == 'contract_info_users'){
            $contract = ContractInfoUsers::updateOrCreate(['id' => $idCont, 'ver_his' => $ver_his], $dataToSave);
        }else{
            $contract = W1ContractInfoUsers::updateOrCreate(['id' => $idCont, 'ver_his' => $ver_his], $dataToSave);
        }
    
        if ($contract) {
            if($checkAdd)
            {
                $result = $this->registerBenefitInfo($data, $contract->id,$checkAdd);
            }else{
                $result = $this->registerBenefitInfo($data, $contract->id);
            }
        }
        return $contract->info_users_cd;
    }

    /**
     * Register Benefit Contract
     *
     * @param  array  $data
     * @param  string  $infoId
     * @return bool
     */
    public function registerBenefitInfo($data, $infoId,$checkAdd=false)
    {
        $result = true;
        $value = [];
        if($checkAdd){
            //array add line when add contract info
            $arrAddLine = isset($data['hidIdBenefitAdd']) ? $data['hidIdBenefitAdd'] : null;
            if (!empty($arrAddLine)) {
                $this->getBenefitInfoData($data, $infoId, $value,'Add');
            }
        }

        //array insert
        $arrInLine = isset($data['hidIdBenefitIn']) ? $data['hidIdBenefitIn'] : null;
        if (!empty($arrInLine)) {
            $this->getBenefitInfoData($data, $infoId, $value,'In');
        }

        //array update
        $arrUpLine = isset($data['hidIdBenefitUp']) ? $data['hidIdBenefitUp'] : null;
        if (!empty($arrUpLine)) {
            $this->getBenefitInfoData($data, $infoId, $value, 'Up');
        }

        if (!empty($value)) {
            $updateCol = [
                "benefit_amount",
                "benefit_content",
                "updated_id",
                "revision" => DB::raw("revision + 1"),
            ];

            if($data['hidScr'] == 'u03' || $data['hidScr'] == 'u111'){
                $result = BenefitInfoUsers::upsert($value, "id", $updateCol) > 0;
            }else{
                $result = W1BenefitInfoUser::upsert($value, "id", $updateCol) > 0;
            }
        }
        if ($result) {
            //array delete
            $arrDelLine = isset($data['hidKeyDel']) ? $data['hidKeyDel'] : null;
            if (!empty($arrDelLine)) {
                if($data['hidScr'] == 'u03' || $data['hidScr'] == 'u111'){
                    $result = BenefitInfoUsers::whereIn('id', $arrDelLine)->delete() > 0;
                }else{
                    $result = W1BenefitInfoUser::whereIn('id', $arrDelLine)->delete() > 0;
                }
            }
        }
        return $result;
    }

    /**
     * Get Benefit Contract data to register.
     *
     * @param  array  $data
     * @param  string  $infoId
     * @param  array  $value
     * @param  string  $eleNm
     * @return void
     */
    public function getBenefitInfoData($data, $infoId, &$value, $eleNm)
    {
        $maxLine = BenefitInfoUsers::where('contract_info_users_id', $infoId)->max('line_no');
        $currentId = Auth::id();
        $arrLine = $data['hidIdBenefit' . $eleNm];
        $arrId = $data['hidKey' . $eleNm];

        for ($i = 0; $i < count($arrLine); $i++) {
            $lineNo = $arrLine[$i];
            if ($eleNm == 'In') {
                $maxLine++;
                $lineNo = $lineNo;
            }
            if (empty($data['colBenefit_amount_' . $lineNo]) && empty($data['colBenefit_content_' . $lineNo])) {
                continue;
            }
            $value[] = [
                'id' => $arrId[$i],
                'contract_info_users_id' => $infoId,
                'line_no' => $lineNo,
                'benefit_amount' => empty($data['colBenefit_amount_' . $lineNo]) ? null : (int) str_replace(',', '', $data['colBenefit_amount_' . $lineNo]),
                'benefit_content' => empty($data['colBenefit_content_' . $lineNo]) ? "" : str_replace(',', '', $data['colBenefit_content_' . $lineNo]),
                'created_id' => $currentId,
                'updated_id' => $currentId,
                'revision' => 0,
            ];
        }
    }
    //end u033 contract
    //u0311 regis Document Sign 
    public function registDocSign($id, $data, $ver_his=0 ){
            
        DB::beginTransaction();
        try {
            $imgNm = "sign_" . $id . '_' . date('Ymd');																
            $info = null;
            $proUsers = null;
            $baseDir = null;

            if($data['hidScr'] == 'u05'){
                $proUsers = W1ProceduresUser::where('info_users_cd', $id)->where('ver_his', $ver_his)->first();
                $info = W1InfoUser::where('info_users_cd', $id)->first();
                $baseDir = CommonConst::UPLOAD_W1_INFO_USERS;
            }else{
                $proUsers = ProceduresUsers::where('info_users_cd', $id)->where('ver_his', $ver_his)->first();
                $info = InfoUsers::where('id', $id)->first();
                $baseDir = CommonConst::UPLOAD_INFO_USERS;
            }
            $info->update([
                'sign_folder_path' => CommonConst::UPLOAD_FOLDER_PATH,
                'sign_folder_name' => $baseDir .  $id . '/' . CommonConst::UPLOAD_SIGN . $ver_his . '/',
                'sign_file_name'   => $imgNm. '.png',
                'sign_date' => date('Y/m/d H:i:s'),
            ]);

            $proUsers->update([
                'U03-11_date' => date('Y/m/d H:i:s'),
                'U03-11_id'   => Auth::id(),
            ]);

            $image = str_replace('data:image/png;base64,', '', $data['signContent']);
            $image = str_replace(' ', '+', $image);
            $imageName = $info->sign_folder_name . $imgNm . '.png';

            Storage::disk('public')->put($imageName, base64_decode($image));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    //endU0311

    //u0312 register ProcedureUsers
    public function RegistProceUsers($id,$ver_his= 0){
        $dataToSave = [];
        $login_id = Auth::id();
        $currentDate = date('Y/m/d H:i:s');
        $dataToSave = [
            'U03-12_date' => $currentDate,
            'U03-12_id'   =>$login_id,
        ];
        ProceduresUsers::updateOrCreate(['info_users_cd' => $id, 'ver_his' => $ver_his], $dataToSave);
    }
//u0310 contract info 
    public function saveEmplContract($data, $ver_his=0){
        $id = $data['idInfo'];
        $pro = null;
        $dataToSave = [
            'info_users_cd' => $id,
            'U03-10_date' =>now(),
        ];

        $tbl = ($data['hidScr'] == 'u05') ? 'w1_procedures_users' : 'procedures_users';
        $pro = DB::table($tbl)->where('info_users_cd', $id)->where('ver_his', $ver_his)->first();
        if($pro){
            //update
            $dataToSave['U03-10_id'] = Auth::id();
            $dataToSave['revision'] = (int)$pro->revision + 1;
            $dataToSave['updated_id'] = Auth::id();
            $dataToSave['created_id'] = Auth::id();
        }
        DB::table($tbl)->where('info_users_cd', $id)->where('ver_his', $ver_his)->update($dataToSave);
        return $id;
    }

    public function findInfoUserByInfoId($id, $isTemplate=false)
    {
        $tbl = 'info_users as i';
        if($isTemplate) $tbl = 'w1_info_users as i';
        $query = DB::table($tbl)
            ->leftJoin('mst_offices as o', 'o.office_cd', '=' , 'i.office_cd')
            ->select(
                'i.*',
                'i.office_cd as office_cd',
                'i.first_name as firstN',
                'i.last_name as lastN',
                'o.office_name as officeN',
                'o.office_address as officeAdd',
                'o.office_director as officeDir',
            );
        if (!$isTemplate) {
            $query->where('i.id', $id)
                ->addSelect('i.token as token');
        }else{
            $query->where('i.info_users_cd', $id);
        }
        return $query->first();
    }
//u0310 contract info 
//u03-1
    public function getBelongCdInfoByIdUser($id)
    {
        $query = DB::table('info_users as info')
            ->leftJoin('users as u', 'u.info_users_cd', '=', 'info.id')
            ->where('u.id', $id)
            ->value('info.belong_cd');
        return $query;
    }

    public function deleteGuarantee($idInfo)
    {
        GuranteeUsers::where("info_users_cd", $idInfo)->delete();
        $dataToSave = [
            'mail_guarantor' => null,
            'mail_confirm_guarantor' => null,
        ];
        DB::table('application_users')->where('info_users_cd', $idInfo)->update($dataToSave);
        return true;
    }

}