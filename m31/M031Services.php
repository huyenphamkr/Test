<?php


namespace App\Services;


use App\Models\MstBelong;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Common\CommonConst;
use App\Models\Notices;


class M03Service
{
    public function findStaffAll(){
        $query = DB::table('users as u')
        ->leftJoin('info_users as i', 'i.id', '=', 'u.info_users_cd')
        ->leftJoin('mst_offices as o', 'o.office_cd', '=', 'i.office_cd')
        ->leftJoin('mst_belong as b', function ($join) {
            $join->on('b.belong_cd', '=', 'i.belong_cd')
                ->on('b.office_cd', '=', 'i.office_cd');
        })
        ->select(
            'i.id as idInfo',
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
        )
        // ->orderBy('topics.updated_at','DESC')
        ->orderBy('i.date_join','DESC')
        ->orderBy('i.office_cd')
        ->orderBy('i.belong_cd')
        ->orderBy('i.users_number')
        ->distinct('u.id')
        ;
       


        // ->leftJoin('users as u', 'u.id', '=', 'profce.office_cd')
        // ->leftJoin('mst_belong as pro_item', 'profce.procedure_id', '=', 'pro_item.id')
        // ->select(
        //     'ofce.office_cd',
        //     'ofce.office_name',
        //     'ofce.office_short_name',
        //     DB::raw("CONCAT_WS('、',
        //         CASE WHEN ofce.flg1 = 1 THEN '労務経理担当' END,
        //         CASE WHEN ofce.flg2 = 1 THEN '社章借用書' END,
        //         CASE WHEN ofce.flg3 = 1 THEN '自家用車通勤' END,
        //         CASE WHEN ofce.flg4 = 1 THEN '外国人雇用' END,
        //         CASE WHEN ofce.flg5 = 1 THEN '家族（扶養）手当申請書' END,
        //         CASE WHEN ofce.flg6 = 1 THEN '住宅手当' END,
        //         CASE WHEN ofce.flg7 = 1 THEN '保育料助成' END,
        //         CASE WHEN ofce.flg8 = 1 THEN '障害者（本人）' END,
        //         CASE WHEN ofce.flg9 = 1 THEN '障害者（扶養家族）' END
        //     ) AS flg_name"),
        //     DB::raw("GROUP_CONCAT(pro_item.name ORDER BY pro_item.id SEPARATOR '、') AS pro_name")
        // )
        // ->orderByRaw('LENGTH(ofce.office_cd)')
        // ->orderBy('ofce.office_cd')      


        return $query->get();
        // return $query->paginate(2);
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


    public function saveMessage($data)
    {
        DB::beginTransaction();
        try {
            $idInfo =  (!empty(1)) ? $this->getInfoByIdUser(1) : -1;
            // $idInfo =  (!empty(Auth::id())) ? $this->getInfoByIdUser(Auth::id()) : -1;
            $idNoti = Notices::where('info_users_cd', $idInfo)->value('id');
            if (empty($idInfo)) return;
            if (empty($idNoti)) $idNoti = -1;
            $dataToUpdate = [
                'notices_type' => 'M03',
                'notices_date' => now(),
                'notices_create_user' => Auth::id(),
                'notices_content' => $data['txtMessage'],
                'notices_visible' => 1,
                // 'updated_id' => Auth::id(),
            ];
            if ($idNoti > 0) {
                //update
                $notice = Notices::find($idNoti);
                $dataToUpdate['revision'] = (int)$notice->revision + 1;
            } else {
                //insert
                $dataToUpdate['info_users_cd'] = $idInfo;
               
                $dataToUpdate['created_id'] = 1;
                // $dataToUpdate['created_id'] = Auth::id();
                $dataToUpdate['revision'] = 0;

            }
            $idChecks = explode(",", $data['InfoCheck']);
            foreach ($idChecks as $value) {
                $dataToUpdate['notices_dest_user'] = $value;
                Notices::updateOrCreate(['id' => $idNoti], $dataToUpdate);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw ($e);
        }
    }
}
