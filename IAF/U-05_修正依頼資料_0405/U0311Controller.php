<?php

namespace App\Http\Controllers;

use App\Common\MsgConst;
use App\Http\Controllers\Controller;
use App\Services\U03Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Common\CommonConst;
use App\Models\InfoUsers;
use App\Models\UploadOffice;
use App\Models\User;

class U0311Controller extends Controller
{
    /**
     * U03 service
     * 
     * @var U03Service
     */
    public $u03Service = null;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->u03Service = new U03Service();
    }

    //Application Houses
    public function docSign()
    {
        $uploadOfce = null;
        $msgErr = null;
        $idUpOfice = null;
        $SignItem = null;
        $pathDocSign = null;
        $indexFile = 0;
        $maxFile = 0;
        $idInfo = request('id');
        $token = request('token');
        $mode = ['edit' => false, 'id' => $idInfo, 'token' => $token, 'scr' => 'u03'];
        $infoUsers = db::table('w1_info_users')->where('info_users_cd', $idInfo)->first();
        if ($infoUsers) {
            //u05
            $mode['scr'] = 'u05';
            if (!Auth::check()) return abort(403);
            if (
                $infoUsers->status_user == CommonConst::U05_STATUS_申請者
                && $infoUsers->info_users_cd == Auth::user()->info_users_cd
            ) $mode['edit'] = true;

            $procedures = DB::table('w1_procedures_users as pro')
                ->leftJoin('users as u', 'pro.U03-11_id', '=', 'u.id')
                ->leftJoin('info_users as i', 'u.info_users_cd', '=', 'i.id')
                ->where('pro.info_users_cd', $idInfo)->first();
        } else {
            //u03
            $infoUsers = db::table('info_users')->where('id', $idInfo)->first();
            if (empty($infoUsers) || (!Auth::check() && $token != $infoUsers->token)) {
                return abort(404);
            }
            if (!empty($token) && $token == $infoUsers->token  && $infoUsers->status_user == CommonConst::U03_STATUS_CREATOR) {
                $mode['edit'] = true;
            }
            $procedures = DB::table('procedures_users as pro')
                ->leftJoin('users as u', 'pro.U03-11_id', '=', 'u.id')
                ->leftJoin('info_users as i', 'u.info_users_cd', '=', 'i.id')
                ->where('pro.info_users_cd', $idInfo)->orderBy('pro.ver_his', 'DESC')->first();
        }
        $U03_11Name = $procedures ? $procedures->first_name .  '　' . $procedures->last_name : '';
        $U03_11Date = $procedures ? $procedures->{'U03-11_date'} : '';
        $this->checkFlg1AndAccountant($infoUsers, $mode['scr']);
        if (!empty($infoUsers->sign_items)) {
            $SignItem = explode(',', $infoUsers->sign_items);
            $idUpOfice =  $SignItem[$indexFile];
            $uploadOfce = $this->u03Service->findUploadOficeByInfoId($idUpOfice);
            $pathDocSign = $uploadOfce->folder_path . $uploadOfce->folder_name . $uploadOfce->file_name;
            $maxFile = count($SignItem);
        }

        $off_cd = $infoUsers->office_cd;
        $path = $pathDocSign;

        if (!is_file($path)) {
            $msgErr = MsgConst::MSG_NO_FILE;
        }

        if (!empty($path)) {
            $indexFile++;
        }
        return view('users.u0311.docSign', compact(
            'uploadOfce',
            'msgErr',
            'infoUsers',
            'maxFile',
            'indexFile',
            'path',
            'mode',
            'U03_11Date',
            'U03_11Name'
        ));
    }

    public function nextPageDocSign(Request $request)
    {
        $data = $request->input();
        $idInfo = request('id');
        $token = request('token');
        $maxFile = $data['hidNum'];
        $mode = ['edit' => false, 'id' => $idInfo, 'token' => $token, 'scr' => 'u03'];
        $infoUsers = db::table('w1_info_users')->where('info_users_cd', $idInfo)->first();
        if ($infoUsers) {
            //u05
            $mode['scr'] = 'u05';
            if (!Auth::check()) return abort(403);
            if (
                $infoUsers->status_user == CommonConst::U05_STATUS_申請者
                && $infoUsers->info_users_cd == Auth::user()->info_users_cd
            ) $mode['edit'] = true;

            $procedures = DB::table('w1_procedures_users as pro')
                ->leftJoin('users as u', 'pro.U03-11_id', '=', 'u.id')
                ->leftJoin('info_users as i', 'u.info_users_cd', '=', 'i.id')
                ->where('pro.info_users_cd', $idInfo)->first();
        } else {
            //u03
            $infoUsers = db::table('info_users')->where('id', $idInfo)->first();
            if (empty($infoUsers) || (!Auth::check() && $token != $infoUsers->token)) {
                return abort(404);
            }
            if (!empty($token) && $token == $infoUsers->token  && $infoUsers->status_user == CommonConst::U03_STATUS_CREATOR) {
                $mode['edit'] = true;
            }
            $procedures = DB::table('procedures_users as pro')
                ->leftJoin('users as u', 'pro.U03-11_id', '=', 'u.id')
                ->leftJoin('info_users as i', 'u.info_users_cd', '=', 'i.id')
                ->where('pro.info_users_cd', $idInfo)->orderBy('pro.ver_his', 'DESC')->first();
        }
        $U03_11Name = $procedures ? $procedures->first_name .  '　' . $procedures->last_name : '';
        $U03_11Date = $procedures ? $procedures->{'U03-11_date'} : '';
        $this->checkFlg1AndAccountant($infoUsers, $mode['scr']);
        $SignItem = explode(',', $infoUsers->sign_items);
        $indexFile = $data["hidNextIndexFile"];
        $msgErr = null;
        if ($indexFile == $maxFile) {
            $this->u03Service->registDocSign($idInfo, $data, $infoUsers->ver_his_U05);
            if ($mode['scr'] ==  'u05') {
                $user = User::where('info_users_cd', $idInfo)->first();
                return route('u05', ['id' => $user->id]);
            }

            return route('u0312.compleProcedure', ['id' => $idInfo, 'token' => $token]);
        }
        //return next screen if displayed all sign file
        if ($indexFile > $data['hidNum']) {
            return route('u0312.compleProcedure', ['id' => $idInfo, 'token' => $token]);
        }
        $uploadOfce = $this->u03Service->findUploadOficeByInfoId($SignItem[$indexFile]);
        $path = $uploadOfce->folder_path . $uploadOfce->folder_name . $uploadOfce->file_name;

        if (!is_file($path)) {
            $msgErr = MsgConst::MSG_NO_FILE;
        }
        if (!empty($path)) {
            $indexFile++;
        }

        return view('users.u0311.docSign', compact(
            'uploadOfce',
            'msgErr',
            'maxFile',
            'infoUsers',
            'indexFile',
            'path',
            'mode',
            'U03_11Date',
            'U03_11Name'
        ));
    }

    public function checkFlg1AndAccountant(&$info, $mode)
    {
        $isAgree = false;
        switch (strtoupper($mode)) {
            case CommonConst::PROCEDURE_TYPE_U05:
                $infoUsers = InfoUsers::Id($info->info_users_cd)->VerHisU05($info->ver_his_U05)->first();
                if ((empty($infoUsers->flg1) && $info->flg1) || (empty($infoUsers->accountant) && $info->accountant)) {
                    $isAgree = true;
                }
                break;
            default:
                if ($info->flg1 || $info->accountant) {
                    $isAgree = true;
                }
                break;
        }
        if ($isAgree && $idUpload = UploadOffice::OfficeCd($info->office_cd)->UploadType(3)->value('id')) {
            empty($info->sign_items) ? $info->sign_items = $idUpload : $info->sign_items .= ',' . $idUpload;
        }
    }
}
