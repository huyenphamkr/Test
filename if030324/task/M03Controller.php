<?php

namespace App\Http\Controllers;

use App\Common\CommonConst;
use App\Common\MsgConst;
use App\Common\UploadFunc;
use App\Services\M03Service;
use App\Services\U034Service;
use App\Services\U03Service;
use App\Services\MstClassService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\CommonService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Exports\ExportStaffBasic;
use App\Exports\ExportStaffSalary;
use App\Models\InfoUsers;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class M03Controller extends Controller
{
    private $m03service = null;
    private $mstClassService = null;
    private $u03service = null;
    public $commonService = null;
    private $u034service = null;
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->m03service = new M03Service();
        $this->mstClassService = new MstClassService();
        $this->u03service = new U03Service();
        $this->commonService = new CommonService();
        $this->u034service = new U034Service();
        $this->middleware(function ($request, $next) {
            return Gate::allows('isHD') || Gate::allows('isHDLabour') || Gate::allows('isAdmin') ? $next($request) : redirect('/login');
        });
    }

    /**
     * Index action
     */
    public function index(Request $request)
    {
        $gate_Ser = new Gate;
        $offiCdCur = $this->u03service->getOffiCdInfoByIdUser(Auth::id());
        $checkHd = ($gate_Ser::check('isHDLabour') || $gate_Ser::check('isHD')) ? true : false;
        $belongs = $this->commonService->findBelongByKey();
        $mstWorkStatus = $this->mstClassService->findClassByType(CommonConst::WORK_STATUS);
        $msgErr = $data = null;
        $data = $request->all();

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

    public function saveMessage(Request $request)
    {
        $data = $request->all();
        $this->m03service->registerMessage($data);
        return route('m03');
    }

    //view staff list
    public function showStaffList($id,$token = null)
    {
        $gate_Ser = new Gate;
        $infoUsers= null;
        $cmbGender = null;
        $cmbContractClass = null;
        $cmbObjectPerson = null;
        $cmbYES_NO = null;
        $cmbDeposit = null;
        $cmbTransp = null;
        $depenUs = null;
        $guarantee = null;
        $transport = null; 
        $uploadUs = null; 
        $app = null;
        $disDepend = null;
        $res = null;
        $certif = null;
        $proUser = null; 
        $proItem = null;
        UploadFunc::delTmpFolder();

        $info = DB::table('users')->where('id',$id)->first();
        if(!$info) abort(404);
        $infoId = $info->info_users_cd;
        $mode = ['id' => $infoId, 'auth' => Auth::id(), 'edit' => false, 'idStaff' => $id];
        if(Auth::user()->info_users_cd == $infoId){
            $mode['edit'] = true;
        }

        $backgr = $this->m03service->backgroundChek($info->email);
        $infoUsers = $this->m03service->findUsersByKey($infoId);

        if($gate_Ser::check('isAdmin')){
            $chk = $this->commonService->chkAuthForEdit($infoUsers->office_cd);
            if(!$chk){
                return redirect()->route('u01');
            }
        }        
        $verHis = InfoUsers::where('id', $infoId)->max('ver_his');

        if(!$infoUsers) abort(404);
        $depenUs = $this->m03service->findDepentUserbyKey($infoId, $verHis);
        $transport = $this->m03service->findTransportUserbyKey($infoId, $verHis);
        $uploadUs = $this->m03service->findUpfileById($infoId, $verHis);
        $proUser = $this->m03service->findProceduresUsersByKey($infoId, $verHis);

        $proItem = $this->m03service->findProByKey($infoUsers->id);

        $cmbDeposit = $this->mstClassService->findClassByType(CommonConst::DEPOSIT_TYPE);
        $cmbTransp = $this->mstClassService->findClassByType(CommonConst::TRANSPORT_TYPE);
        $cmbGender = $this->mstClassService->findClassByType(CommonConst::GENDER);
        $cmbContractClass = $this->mstClassService->findClassByType(CommonConst::CONTRACT_CLASS);
        $cmbObjectPerson = $this->mstClassService->findClassByType(CommonConst::OBJECT_PERSON);
        $mstWorkStatus = $this->mstClassService->findClassByType(CommonConst::WORK_STATUS);
        $mstWorkTime = $this->mstClassService->findClassByType(CommonConst::WORK_TIME);
        $cmbYES_NO = $this->mstClassService->findClassByType(CommonConst::YES_NO);

        $productUser = $this->u034service->findProceduresUsersByKey($infoId, $verHis);
        $res = $this->u034service->findResidentDependUploadByKey($infoId, $verHis);
        $certif = $this->u034service->findCertificateUploadByKey($infoId, $verHis);
        $disDepend = $this->u034service->findDisabledDependUploadByKey($infoId, $verHis);
        $app = $this->u034service->findEmployApplyByKey($infoId, $verHis);
        
        $guarantee = $this->u03service->findGuranteeById($infoId, $verHis);

        $ofce = $this->commonService->findByIdOffice($infoUsers->office_cd);
        
        return view('users.m03.staffList',compact(
            'mode',
            'infoUsers',
            'cmbGender',
            'cmbDeposit',
            'cmbTransp',
            'cmbContractClass',
            'cmbObjectPerson',
            'cmbYES_NO',
            'depenUs',
            'guarantee',
            'transport',
            'uploadUs',
            'app',
            'disDepend',
            'res',
            'certif',
            'proUser',
            'productUser',
            'proItem',
            'mstWorkStatus',
            'mstWorkTime',
            'backgr',
            'ofce',
        ));
    }

    public function saveStaffList(Request $request){
        $data = $request->input();
        $id = $data['hidIdUsers'];
        $this->m03service->registerIconStaff($id,$data);
        return route('u04', ['id' => $id]);
    }
}
