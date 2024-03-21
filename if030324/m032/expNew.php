

    /**
     * Retrieve data by email
     *
     * @param string $email
     * @return colection\Illuminate\Database\Eloquent\Collection
     */
    public function backgroundChek($email){
        $query = DB::table('users as u')
            ->leftJoin('info_users as i', 'i.id', '=', 'u.info_users_cd')
            ->leftJoin('info_users_his as ih', 'ih.info_users_cd', '=', 'u.info_users_cd')
            ->leftJoin('mst_belong as be', function ($join) {
                $join->on('i.belong_cd', '=', 'be.belong_cd')
                    ->on('i.office_cd', '=', 'be.office_cd');
            })
            ->leftJoin('mst_offices as o', 'i.office_cd', '=', 'o.office_cd')
            ->leftJoin('mst_belong as beh', function ($join) {
                $join->on('ih.belong_cd', '=', 'beh.belong_cd')
                    ->on('ih.office_cd', '=', 'beh.office_cd');
            })
            ->leftJoin('mst_offices as oh', 'ih.office_cd', '=', 'oh.office_cd')
            ->select(
                'u.email',
                'u.retire_date',
                'i.id',
                'ih.id as idh',
                'i.ver_his_U05',
                'i.date_join',
                'i.office_cd',
                'i.belong_cd',
                'o.office_name',
                'be.belong_name',
                'i.position',
                'i.work_time_flag',
                'i.procedure_type',
                'i.work_status',
                'i.updated_date',
                'oh.office_name as office_name_his',
                'beh.belong_name as belong_name_his',
                'ih.ver_his_U05 as ver_his_U05_his',
                'ih.date_join as date_join_his',
                'ih.position as position_his',
                'ih.work_time_flag as work_time_flag_his',
                'ih.procedure_type as procedure_type_his',
                'ih.work_status as work_status_his',
                'ih.updated_date as updated_date_his',
            )
            ->where('u.email',$email)
            ->whereRaw('i.date_join <= NOW()')
            ->where(function ($query) {
                $query->where('ih.procedure_type','!=',CommonConst::PROCEDURE_TYPE_U04)
                ->orWhereNull('ih.procedure_type');
            })
            ->orderByDesc( 'i.date_join')
            ->orderByDesc( 'ih.id')
            ->distinct()
            ->get();
        return $query;
    }


    /**
     * Retrieve duplicate data by email
     *
     * @param string $email
     * @return colection\Illuminate\Database\Eloquent\Collection
     */
    public function getDupliRecordByEmail($email){
        $query = DB::table('info_users_his as h1')
        ->join('info_users_his as h2', function ($join) {
            $join->on('h1.position', '=', 'h2.position')
                ->on('h1.work_time_flag', '=', 'h2.work_time_flag')
                ->on('h1.work_status', '=', 'h2.work_status')
                ->on('h1.office_cd', '=', 'h2.office_cd')
                ->on('h1.belong_cd', '=', 'h2.belong_cd')
                ->on('h1.procedure_type', '=', 'h2.procedure_type')
                ->on('h1.email', '=', 'h2.email')
                ->on('h1.id', '<>', 'h2.id');
        })
        ->select('h1.id')
        ->where('h1.email',$email)
        ->where('h1.procedure_type',CommonConst::PROCEDURE_TYPE_U05)
        ->orderBy( 'h1.id')
        ->distinct()
        ->get();
        return $query;
    }


    /**
     * Handl and calculate candidate background data
     *
     * @param string $email
     * @return colection\Illuminate\Database\Eloquent\Collection
     */
    public function getStaffBgByEmail($email) {
        $backgr = $this->backgroundChek($email);
        $idHis = $this->getDupliRecordByEmail($email);
        $idHis->pop();
        if($idHis->isNotEmpty()){
            $arrIdHis = collect(json_decode(json_encode($idHis), true))->flatten(1)->toArray();
            $backgr = $backgr->whereNotIn('idh',$arrIdHis);
        }
        $check = ['endHis' => null, 'chkMinus' => 1];
        $arrId = [];
        $result = [];
        foreach( $backgr as  $item){
            if($item->procedure_type == 'U03' || $item->procedure_type == 'U11'){
                $startConm = $item->date_join;
                $endConm = $item->retire_date;
                $officeConm = $item->office_name;
                $belongConm = $item->belong_name;
                $positionConm = $item->position;
                $workTimeConm = $item->work_time_flag;
                $workStatusConm = $item->work_status;
                $proTypeConm = $item->procedure_type;
                $exp = $this->calcEXP($startConm, $endConm);
                $year = $exp['year'];
                $month = $exp['month'];
                $check['chkMinus'] = (empty($result) && empty($endConm)) ? 0 : 1;
                $this->addRow($result, $startConm, $endConm, $officeConm, $belongConm, $positionConm, $workTimeConm, $workStatusConm, $proTypeConm, $year, $month);
            }else{ //co his
                if(isset($arrId) && in_array($item->id, $arrId)){
                    $this->getHis($item, $check['endHis'], $arrId, $result, false);
                }else{
                    if($check['chkMinus'] == 0 || isset($check['endHis'])){
                        $endConm = $item->retire_date ? date(CommonConst::DATE_FORMAT_1, strtotime($item->retire_date)) : '';
                        $check['chkMinus'] = 1;
                    }else
                        $endConm = $item->retire_date ? date(CommonConst::DATE_FORMAT_1, strtotime($item->retire_date.' - 1 days')) : '';
                    $startConm = $item->updated_date ?? '';
                    $officeConm = $item->office_name;
                    $belongConm = $item->belong_name;
                    $positionConm = $item->position;
                    $workTimeConm = $item->work_time_flag;
                    $workStatusConm = $item->work_status;
                    $proTypeConm = $item->procedure_type;
                    $exp = $this->calcEXP($startConm, $endConm);
                    $year = $exp['year'];
                    $month = $exp['month'];
                    //
                    if($this->chkDupEnd($item, $officeConm, $belongConm, $positionConm, $workTimeConm, $workStatusConm, false)){
                        $startConm = $item->updated_date_his;
                        $exp = $this->calcEXP($startConm, $endConm);
                        $year = $exp['year'];
                        $month = $exp['month'];
                    }
                    $this->addRow($result, $startConm, $endConm, $officeConm, $belongConm, $positionConm, $workTimeConm, $workStatusConm, $proTypeConm, $year, $month);
                    $this->getHis($item, $check['endHis'], $arrId, $result);
                }
            }
        }
        return collect($result);
    }

    public function getHis($item, &$endHis, &$arrId, &$result, $isFirst = true){
        if($isFirst){
            $endConm = date(CommonConst::DATE_FORMAT_1, strtotime($item->updated_date.' - 1 days'));
        }else{
            $endConm = date(CommonConst::DATE_FORMAT_1, strtotime($endHis.' - 1 days'));
        }
        $startConm =  $item->updated_date_his ?? $item->date_join;
        $officeConm = $item->office_name_his;
        $belongConm = $item->belong_name_his;
        $positionConm = $item->position_his;
        $workTimeConm = $item->work_time_flag_his;
        $workStatusConm = $item->work_status_his;
        $proTypeConm = $item->procedure_type_his;
        $exp = $this->calcEXP($startConm, $endConm);
        $year = $exp['year'];
        $month = $exp['month'];
        if($item->ver_his_U05_his > 0 || empty($item->updated_date_his)){
            $endHis = $startConm;
            if($item->ver_his_U05_his > 0 && (empty($arrId) || !in_array($item->id, $arrId)))
                array_push($arrId, $item->id);
        }
        //
        if(!$this->chkDupEnd($item, $officeConm, $belongConm, $positionConm, $workTimeConm, $workStatusConm))
            $this->addRow($result, $startConm, $endConm, $officeConm, $belongConm, $positionConm, $workTimeConm, $workStatusConm, $proTypeConm, $year, $month);
    }

    public function chkDupEnd($item, $officeConm, $belongConm, $positionConm, $workTimeConm, $workStatusConm, $isHis = true){
        if( $isHis && $officeConm == $item->office_name &&
            $belongConm == $item->belong_name && 
            $positionConm == $item->position && 
            $workTimeConm == $item->work_time_flag && 
            $workStatusConm == $item->work_status)
            return true;
        if( !$isHis && $officeConm == $item->office_name_his &&
            $belongConm == $item->belong_name_his && 
            $positionConm == $item->position_his && 
            $workTimeConm == $item->work_time_flag_his && 
            $workStatusConm == $item->work_status_his)
            return true;
        return false;
    }
    public function addRow(&$result, $start, $end, $office, $belong, $position, $workTime, $workStatus, $type, $year, $month){
        $row = 
        [
            'startConm' => $start,
            'endConm' => $end,
            'officeConm' => $office ,
            'belongConm' => $belong,
            'positionConm' => $position,
            'workTimeConm' => $workTime,
            'workStatusConm' => ($end && $workStatus == CommonConst::WORK_STATUS_4) ? 1: $workStatus,
            'proTypeConm' => $type,
            'year' => $year,
            'month' => $month,
        ];
        $result[] = $row;
    }



    /**
     * Calculate cumulative experience from start date to end date
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function calcEXP($startDate,  $endDate) {
        $start = date_create($startDate);
        $end = date_create($endDate);
        if( empty($endDate)){
            $end = date_create(date('Y-m-d H:i:s'));
        }
        $date = date_diff($start, $end);
        $date = $date->format('%a');
        $year = floor($date / 365);
        $month = floor(($date - ($year * 365)) / 30);
        if($month == 12) {
            $year += 1;
            $month = 0;
        }
        return ['month' => $month, 'year' => $year];
    }

