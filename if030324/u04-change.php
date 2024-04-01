
    public function getInfoUserMode($idInfo, $isU05 = true)
    {
        $mode = $isU05 ? 'w_' : '';
        $query = DB::table($mode . 'info_users as i');
        if ($isU05) {
            $query->where('i.info_users_cd', $idInfo);
        } else {
            $query->where('i.id', $idInfo);
        }
        return $query->first();
    }

    public function getUploadInfoUserMode($idInfo, $ver_his, $type, $isU05 = true)
    {
        $mode = $isU05 ? 'w_' : '';
        $query = DB::table($mode . 'upload_info_users')
            ->where('info_users_cd', $idInfo)
            ->where('ver_his', $ver_his)
            ->where('upload_type', $type);
        return $query->first();
    }

    public function getInfoChangeU04($idInfo)
    {
        $u034Service = new U034Service();
        $u05Service = new U05Service();
        $u03Service = new U03Service();
        $w = $this->getInfoUserMode($idInfo);
        $info = $this->getInfoUserMode($idInfo, false);
        $hidColumn = [
            'id',
            'info_users_cd',
            'ver_his',
            'ver_his_U05',
            'note',
            'author_id',
            'author_date',
            'applicant_id',
            'applicant_date',
            'admin1_id',
            'admin1_date',
            'admin2_id',
            'admin2_date',
            'hd_id',
            'hd_date',
            'hd_confirm_date',
            'status_user',
            'updated_date',
            'created_at',
            'created_id',
            'updated_at',
            'updated_id',
            'revision',
        ];
        $changAll =  $this->handlCompareColection($w, $info, $hidColumn);
        $arrChg = array_keys($changAll);
        if (in_array("object_person", $arrChg)) {
            $changAll["object_person"] = $u05Service->handlCompareArray($w->object_person, $info->object_person, "object_person_");
            $arrChg = array_merge($arrChg, $changAll["object_person"]);
        }

        //w_application_users
        $wApp = $this->findEmplAppTemplateByKey($idInfo);
        $app = $u034Service->findEmployApplyByKey($idInfo, $info->ver_his);
        $hidColumn = [
            'id',
            'info_users_cd',
            'ver_his',
            'created_at',
            'created_id',
            'updated_at',
            'updated_id',
            'revision',
        ];
        $arrChg = array_merge($arrChg, array_keys($u05Service->handlCompareColection($wApp, $app, $hidColumn)));
        
        //upload_file type 5
        $arrChg = array_merge($arrChg, $this->handlCompareFile($idInfo, $w->ver_his, 5, 5));

        //dephen
        // $dependentW = $u03Service->findDepByKey($idInfo, $info->ver_his, true)->toArray();
        // $dependent = $u03Service->findDepByKey($idInfo, $info->ver_his)->toArray();
        // $de = $this->handl($dependentW, $dependent, 'dependent');


        dd($de);
        return $arrChg;
    }

    public function handlCompareArray($arrOne, $arrTwo, $name)
    {
        $arrOne = explode(',', $arrOne) ?? [];
        $arrTwo = explode(',', $arrTwo) ?? [];
        $result =  array_merge($arrOne, $arrTwo);
        $arrDiff = array_filter(array_diff(array_merge($arrOne, $arrTwo), array_intersect($arrOne, $arrTwo)));
        $result = array_map(function ($value) use ($name) {
            return $name . $value;
        }, $arrDiff);
        return $result;
    }
    public function handlCompareColection($infoOne, $infoTwo, $hidColumn, $isHid = true)
    {
        $diff = collect($infoOne)->diffAssoc(collect($infoTwo));
        $result = $isHid ? collect($diff->all())->except($hidColumn) : collect($diff->all())->only($hidColumn);
        return $result->all();
    }
    public function handlCompareFile($idInfo, $ver_his_U05, $end, $start = 2)
    {
        $result = [];
        for ($i = $start; $i <= $end; $i++) {
            $fileW1 = $this->getUploadInfoUserMode($idInfo, $ver_his_U05, $i);
            $file = $this->getUploadInfoUserMode($idInfo, $ver_his_U05, $i, false);
            $getColumn = ['folder_name', 'file_name'];
            $diff =  $this->handlCompareColection($fileW1, $file, $getColumn, false);
            if (!empty($diff)) {
                array_push($result, 'type' . $i);
            }
        }
        return $result;
    }

    // public function handl($u04, $u03, $name){

    //     $result = [];
    //     $hidColumn = [
    //         'id',
    //         'info_users_cd',
    //         'ver_his',
    //         'created_at',
    //         'created_id',
    //         'updated_at',
    //         'updated_id',
    //         'revision',
    //     ];
    //     array_map(function ($value) use ($name, $hidColumn, $u03) {
    //         $this->Map($value, $u03, $name, $hidColumn);
    //         $valueU04 = $value;
    //         // array_map(function ($valueU03) use ($valueU04, $name, $hidColumn) {
    //         //     $diff = $this->handlCompareColection($valueU04, $valueU03, $hidColumn);

    //         //     if (!empty($diff)) {
    //         //         array_push($result, $name . $valueU04['id']);
    //         //     }
    //         // }, $u03);
    //     }, $u04);
    //     return $result;
    // }

    // public function Map($value, $arrMap, $name, $hidColumn){
    //    $result =  array_map(function ($valueMap) use ($value, $name, $hidColumn) {
    //                 $diff = $this->handlCompareColection($value, $valueMap, $hidColumn);
    //                 if (empty($diff)) {
    //                     array_push($result, $name . $value['id']);
    //                 }
    //             }, $arrMap);
    //     return $result;
    // }

  --------------------------------------------------

  AddRowDependent
AddRowTransport
AddDisableDepend
AddResidentDepend
AddCertificate


app->
upInfUser['type5']

------------------------------------------------------
  .bg-change{
    background-color: rgba(255, 178, 178, 0.3) !important;
  }
  .bg-change-chkbox{
    background-color: rgba(255, 178, 178, 0.3)  !important;
    border-color: rgba(255, 178, 178, 0.3)  !important;
  }