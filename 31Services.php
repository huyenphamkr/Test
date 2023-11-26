<?php

namespace App\Services;


use App\Common\CommonConst;
use App\Common\CommonFunc;
use App\Common\UploadFunc;
use App\Models\InfoUsers;
use App\Models\LetterInfoUsers;
use Illuminate\Support\Facades\DB;
use App\Models\Procedure;
use App\Models\ProceduresUsers;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\MstOffice;
use App\Mail\SendMailInfo;
use App\Models\UploadInfoUsers;
use App\Models\User;

class U03Service
{
    /**
     * Retrieve info_users data by key search.
     *
     * @param  array  $data
     * @return collection
     */

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
            ->select(
                'info.*',
                'au.name as name_author',
                'app.name as name_applicant',
                'ad1.name as name_admin1',
                'ad2.name as name_admin2',
                'hd.name as name_hd',
                'letter.updated_at as let_updated_at',
            )
            ->where('info.id', $id);
        return $query->first();
    }

    public function findProByKey($id)
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
            ->where('pro.delete_flag', '0')->distinct()
            ->orderBy('pro.id');
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
            ->orderBy('u.id');
        return $query->get();
    }

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
            ];

            if (!empty($data['mode'])) {
                $this->updateStatus($data);
            }

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
            }
            $newInfo = InfoUsers::updateOrCreate(['id' => $id], $dataToSave);
            $id = $newInfo->id;
            if ($newInfo && !empty($data['mode'])) {
                $this->SendMailInfo($data, $id);
            }

            //upload file
            $this->registerFile($data, $id);
            DB::commit();
            UploadFunc::delTmpFolder();
            // return $newInfo->id;
        } catch (\Exception $e) {
            DB::rollback();
            UploadFunc::delTmpFolder();
            throw $e;
        }
    }

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

    public function updateStatus($data)
    {
        switch ($data['mode']) {
            case 'author':
                $dataToSave['author_id'] =  Auth::id();
                $dataToSave['author_date'] = now();
                $dataToSave['status_user'] = CommonConst::STATUS_CREATOR;
                break;
            case 'admin1':
                $dataToSave['admin1_id'] =  Auth::id();
                $dataToSave['admin1_date'] = now();
                $dataToSave['status_user'] = CommonConst::STATUS_APPROVAL_2;
                break;
            case 'admin2':
                $dataToSave['admin2_id'] =  Auth::id();
                $dataToSave['admin2_date'] = now();
                $dataToSave['status_user'] = CommonConst::STATUS_APPROVAL_HD;
                break;
            case 'hd':
                $dataToSave['hd_id'] =  Auth::id();
                $dataToSave['hd_date'] = now();
                $dataToSave['status_user'] = CommonConst::STATUS_COMPLETED;
                break;
            case ('admin1Reject' || 'admin2Reject' || 'hdReject'):
                $dataToSave['status_user'] = CommonConst::STATUS_REJECT;
                break;
            case 'btnHD':
                $dataToSave['hd_date'] = now();
                break;
        }
    }

    public function getUserNumMax($data)
    {
        $nameOffi = MstOffice::where('office_cd', $data['cmbOffice_cd'])->first();
        if ($nameOffi->office_name == CommonConst::OFFICE_NAME) {
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

    function getUsersNumberMax($isLifeCare = null)
    {
        $query = DB::table('info_users');
        ($isLifeCare) ? $query->whereNotBetween('users_number', ['0000000', '0100000']) : $query->whereBetween('users_number', ['0000000', '0100000']);
        $query->select('users_number')->orderByDesc('users_number');
        return $query->first();
    }

    public function SendMailInfo($data, $id)
    {
        $user = $this->getInfoUserByKey($id);
        $hdList = $this->getHDApprove();
        $url=route('u03.showInfo');
        switch ($data['mode']) 
        {
            case 'author':
                //send mail
                Mail::to($data['email'])->send(new SendMailInfo($user, 'creator1',$url));
                //Send mail ManageHD
                foreach ($hdList as $hd) {
                    Mail::to($hd->email)->send(new SendMailInfo($user, 'creator2',$url, $hd));
                }
                break;
            case 'admin1':
                $usersApp2 = $this->getSecondApprove($user->office_cd, $user->belong_cd, 2);
                //send list app 2
                foreach ($usersApp2 as $app2) {
                    Mail::to($app2->email_app2)->send(new SendMailInfo($user, 'approval1',$url, $app2->name_app2));
                }
                break;
            case 'admin1Reject':
                //send author
                Mail::to($user->email_author)->send(new SendMailInfo($user, 'reject1',$url));
                break;
            case 'admin2':
                //send hd managerHD
                foreach ($hdList as $hd) {
                    Mail::to($hd->email)->send(new SendMailInfo($user, 'approval2',$url, '', $hd));
                }
                break;
            case 'admin2Reject':
                //send author
                Mail::to($user->email_author)->send(new SendMailInfo($user, 'reject2',$url));
                break;
            case 'hd':
                $url=route('login');
                //random $password has letter and number
                $password = randomPassword();
                //insert users table users get ID
                $idUser = $this->registerUser($data, $password);
                if ($idUser) {
                    $idInfo = User::find($idUser);
                    $user = $this->getInfoUserByKey($idInfo->info_users_cd, true);
                    //send mail user final
                    Mail::to($user->email)->send(new SendMailInfo($user, 'approvalHD',$url, '', '', CommonConst::INITIAL_PASSWORD));
                }
                break;
            case 'hdReject':
                //send author
                Mail::to($user->email_author)->send(new SendMailInfo($user, 'rejectHD',$url));
                break;
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
                    'o.office_name as name_office',
                    'bl.belong_name as name_belong',
                );
        } else {
            $query->select(
                'info.*',
                'au.name as name_author',
                'au.email as email_author',
                'o.office_name as name_office',
                'bl.belong_name as name_belong',
            );
        }
        return $query->first();
    }

    //get all HD
    public function getHDApprove()
    {
        $query = DB::table('info_users as i')
            ->where('i.belong_cd', '=', CommonConst::ID_BELONG_HD)
            ->where('i.manager', '=', '1')
            ->where('i.flg1', '=', '1')
            ->select(
                'i.*',
            );
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
            ->select(
                // 'info.*',
                'u.email as email_app2',
                'u.name as name_app2',
            )
            ->distinct();
        return $query->get();
    }

    function randomPassword()
    {
        $letters = str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $numbers = str_shuffle('0123456789');
        $password = substr($letters, 0, 4) . substr($numbers, 0, 4);
        return str_shuffle(substr($password, 0, 8));
    }

    function registerUser($data, $password)
    {
        $id = DB::table('users')->insertGetId([
            'name' => $data['txtFirstName'] . ' ' . $data['txtLastName'],
            'info_users_cd' => $data['id'],
            'email' => $data['email'],
            'password' => bcrypt(CommonConst::INITIAL_PASSWORD),
            'authority' => '3',
            'created_at' => now(),
            'created_id' => Auth::id(),
            'updated_at' => now(),
            'updated_id' => Auth::id(),
        ]);
        return $id;
    }

    public function findUpFileById($id)
    {
        $upInfUser = DB::table('upload_info_users as up')
            ->where('up.info_users_cd', $id)
            ->get();
        $result = [];
        foreach ($upInfUser as $row) {
            $param = [
                'id' => $row->id,
                'folder_name' => $row->folder_name,
                'file_name' => $row->file_name,
                'path' => $row->folder_path . $row->folder_name . $row->file_name,
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

    public function uploadFile($name, $data, $infoUserCd, $type)
    {
        $fileNm = UploadFunc::getFileName($data['tmp' . $name]);
        $upFile = UploadInfoUsers::updateOrCreate(['upload_type' => $type, 'info_users_cd' => $infoUserCd], [
            'info_users_cd' => $infoUserCd,
            'folder_path' => CommonConst::UPLOAD_FOLDER_PATH,
            'file_name' => $fileNm,
            'created_id' => Auth::id(),
            'updated_id ' => Auth::id(),
        ]);
        $upFile->save();
        $upFile->folder_name = CommonConst::UPLOAD_INFO_USERS . $infoUserCd . '/' . $upFile->id . '/';
        $upFile->save();
        return UploadFunc::moveFile($data['tmp' . $name],  $upFile->folder_name . $fileNm);
    }

    public function deleteFileById($id, $isDel = true)
    {
        $file = UploadInfoUsers::find($id);
        if ($file) {
            UploadFunc::delFile($file->folder_name . $file->file_name);
            if ($isDel) $file->delete();
        }
    }
}
