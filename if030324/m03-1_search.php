public function index(Request $request)
    {
        $gate_Ser = new Gate;
        $offiCdCur = $this->u03service->getOffiCdInfoByIdUser(Auth::id());
        $checkHd = ($gate_Ser::check('isHDLabour') || $gate_Ser::check('isHD')) ? true : false;
        $belongs = $this->commonService->findBelongByKey();
        $mstWorkStatus = $this->mstClassService->findClassByType(CommonConst::WORK_STATUS);
        $msgErr = $data = null;
        $data = $request->all();
        if(!empty($data)){
            $offiCdCur = $data['cmbOffice'] ?? null;
        }
        $request->query->set('cmbOffice', $offiCdCur);
        if($request->isMethod('post')){
            $offiCdCur = $data['cmbOffice'];
            $mode = isset($data["btnSrc"]) ? $data["btnSrc"] : "";
            switch($mode)
            {
                //basic
                case CommonConst::MODE_EXPORT_BASIC:
                    $users = $this->m03service->findStaffAll($data,$offiCdCur,1);
                    $ids = [];
                    foreach($users as $user) {
                        $ids[] = $user->idInfo;
                    }
                    $file_name = $this->m03service->setFileNameOfficeExport($mode, $offiCdCur);
                    return Excel::download(new ExportStaffBasic($ids), $file_name);
                    break;
                //salary
                case  CommonConst::MODE_EXPORT_SALARY:
                    $users = $this->m03service->findStaffAll($data,$offiCdCur,1);
                    $file_name = $this->m03service->setFileNameOfficeExport($mode, $offiCdCur);
                    return Excel::download(new ExportStaffSalary($users), $file_name);
                    break;
                //search
                default:
                    $request->query->set('page', null);
                    $request->query->set('cmbOffice', $data['cmbOffice']);
                    $request->query->set('cmbBelong', $data['cmbBelong']);
                    $request->query->set('txtDate', $data['txtDate']);
                    $request->query->set('txtIdStaff', $data['txtIdStaff']);
                    $request->query->set('txtNameStaff', $data['txtNameStaff']);
                    $request->query->set('cmbWStatus', $data['cmbWStatus']);
                    break;     
            }
        }
            Session::put($data);
        
        if($checkHd){
            $ofces = $this->commonService->findAllOffice();
        }
        else{
            $ofces = $this->commonService->findAllOffice()->where('office_cd', $offiCdCur);
        }

        $users = $this->m03service->findStaffAll($data,$offiCdCur);
        $ofceCur = $this->commonService->findByIdOffice($offiCdCur); 
        if ($users->isEmpty()) $msgErr = MsgConst::MSG_NO_DATA;
        return view('users.m03.index', compact('users','ofces','belongs','mstWorkStatus','ofceCur','checkHd','data','msgErr'));   
    }



public function findStaffAll($data, $offiCdCur = null, $mode = null){
        $query = DB::table('users as u')
        ->leftJoin('info_users as i', 'i.id', '=', 'u.info_users_cd')
        ->leftJoin('mst_offices as o', 'o.office_cd', '=', 'i.office_cd')
        ->leftJoin('mst_belong as b', function ($join) {
            $join->on('b.belong_cd', '=', 'i.belong_cd')
                ->on('b.office_cd', '=', 'i.office_cd');
        })
        ->where('i.date_join', '<=', date('Y-m-d'))
        ->where(function ($query) {
            $query->where(function ($query) {
                $query->whereNotNull('u.retire_date')
                    ->whereIn('u.id', $this->getIdStaffSameEmail());
            })
            ->orWhereNull('u.retire_date');
        });


        //check office curren
        if(!empty($offiCdCur)){
            $query->where('i.office_cd', '=', $offiCdCur);
        }


        if (!empty($data)) {
            $txtDate = isset($data["txtDate"]) ? $data["txtDate"] : null;
            $cmbBelong = isset($data["cmbBelong"]) ? $data["cmbBelong"] : null;
            $txtIdStaff = isset($data["txtIdStaff"]) ? $data["txtIdStaff"] : null;
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






    ------------------------SS--------------------------------------public function index(Request $request)
    {

        $gate_Ser = new Gate;
        $offiCdCur = $this->u03service->getOffiCdInfoByIdUser(Auth::id());
        $checkHd = ($gate_Ser::check('isHDLabour') || $gate_Ser::check('isHD')) ? true : false;
        $belongs = $this->commonService->findBelongByKey();
        $mstWorkStatus = $this->mstClassService->findClassByType(CommonConst::WORK_STATUS);
        $msgErr = $data = null;
        $sessionNm = "ss_iandf_m03";

        if (Session::has($sessionNm) && $checkHd) {
            $data = Session::get($sessionNm);
            $offiCdCur =  $data['cmbOffice'];
        }

        // if(empty($data['cmbOffice'])) $data['cmbOffice'] = $offiCdCur;
        // $data += $request->all();
        // $a = request()->cmbOffice ?? null;
        // if(empty($data['cmbOffice'])) $data['cmbOffice'] = $offiCdCur;
        // if($data && $checkHd && $data['cmbOffice'] != $offiCdCur){
        //     $a = request()->cmbOffice;
        //     $offiCdCur = $data['cmbOffice'] ?? null;
        // }
        // $request->query->set('cmbOffice', $offiCdCur);
        if($request->isMethod('post')){
            $data = $request->all();
            Session::put($sessionNm, $data);
            $offiCdCur = $data['cmbOffice'];
            $mode = isset($data["btnSrc"]) ? $data["btnSrc"] : "";
            switch($mode)
            {
                //basic
                case CommonConst::MODE_EXPORT_BASIC:
                    $users = $this->m03service->findStaffAll($data,$offiCdCur,1);
                    $ids = [];
                    foreach($users as $user) {
                        $ids[] = $user->idInfo;
                    }
                    $file_name = $this->m03service->setFileNameOfficeExport($mode, $offiCdCur);
                    return Excel::download(new ExportStaffBasic($ids), $file_name);
                    break;
                //salary
                case  CommonConst::MODE_EXPORT_SALARY:
                    $users = $this->m03service->findStaffAll($data,$offiCdCur,1);
                    $file_name = $this->m03service->setFileNameOfficeExport($mode, $offiCdCur);
                    return Excel::download(new ExportStaffSalary($users), $file_name);
                    break;
                //search
                default:
                    $request->query->set('page', null);
                    $request->query->set('cmbOffice', $data['cmbOffice']);
                    $request->query->set('cmbBelong', $data['cmbBelong']);
                    $request->query->set('txtDate', $data['txtDate']);
                    $request->query->set('txtIdStaff', $data['txtIdStaff']);
                    $request->query->set('txtNameStaff', $data['txtNameStaff']);
                    $request->query->set('cmbWStatus', $data['cmbWStatus']);
                    break;     
            }
        }
            // Session::put($data);
        
        if($checkHd){
            $ofces = $this->commonService->findAllOffice();
        }
        else{
            $ofces = $this->commonService->findAllOffice()->where('office_cd', $offiCdCur);
        }

        $users = $this->m03service->findStaffAll($data,$offiCdCur);
        $ofceCur = $this->commonService->findByIdOffice($offiCdCur); 
        if ($users->isEmpty()) $msgErr = MsgConst::MSG_NO_DATA;
        return view('users.m03.index', compact('users','ofces','belongs','mstWorkStatus','ofceCur','checkHd','data','msgErr'));   
    }