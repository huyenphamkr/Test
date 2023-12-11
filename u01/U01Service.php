<?php


namespace App\Services;


use App\Common\CommonConst;
use App\Common\UploadFunc;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Notices;
class U01Service
{
    public function findStaffInfo($id, $notVis = 1){
        $query = DB::table('users as u')
        ->leftJoin('info_users as i', 'i.id', '=', 'u.info_users_cd')
        ->leftJoin('notices as n', 'u.id', '=', 'n.notices_dest_user')
        ->where('u.id',$id)
        ->where('n.notices_visible',$notVis)
        ->select(
            'i.id as idInfo',
            'u.id as idStaff',
            'n.notices_type',
            'i.first_name',
            'i.last_name',
            DB::raw('COUNT(notices_dest_user) as totalType')
        )
        ->groupBy('i.id','u.id','n.notices_type','i.first_name','i.last_name')->get();
        return $query;
    }


    public function findNoticesByDestUser($id, $notVis = 1){
        $query = DB::table('notices as n')
        ->leftJoin('users as u', 'u.id', '=', 'n.notices_dest_user')
        ->leftJoin('users as us', 'us.id', '=', 'n.notices_create_user')
        ->leftJoin('info_users as i', 'i.id', '=', 'us.info_users_cd')
        // ->leftJoin('mst_belong as be', function ($join) {
        //     $join->on('u.belong_cd', '=', 'be.belong_cd')
        //         ->on('u.office_cd', '=', 'be.office_cd');
        // })
        ->where('u.id',$id)
        ->where('notices_visible',$notVis)
        ->select(
            'u.id as idStaff',
            'i.first_name as firstNCreate',
            'i.last_name as lastNCreate',
            'n.*',
            'i.*',
        )
        ->orderBy('notices_type')
        ->get();
        return $query;
    }
}
