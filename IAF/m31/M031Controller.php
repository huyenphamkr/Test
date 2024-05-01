<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\M03Service;
use App\Services\MstClassService;
use App\Services\CommonService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Common\CommonConst;
use Illuminate\Http\Request;

class M03Controller extends Controller
{
    private $m03service = null;
    private $classervice = null;
    public $commonService = null;
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->m03service = new M03Service();
        $this->classervice = new MstClassService();
        $this->commonService = new CommonService();
    }

    /**
     * Index action
     
     */
    public function index()
    {
        $office_cd = DB::table('users')->where('id',1)->value('office_cd');
        // $office_cd = DB::table('users')->where('id',Auth::id())->value('office_cd');
        $ofces = $this->commonService->findAllOffice();
        $ofceCur = $this->commonService->findByIdOffice($office_cd);
        $belongs = $this->commonService->findBelongByKey();
        $mstWorkStatus = $this->classervice->findClassByType(CommonConst::WORK_STATUS);
        $users = $this->m03service->findStaffAll();
$checkHd = false;
        $msgErr = null;
        $clas_procedure = $this->classervice->findClassByType('PROCEDURE_TYPE');
        $clas_HD = $this->classervice->findClassByType('HD_TYPE');
        $belong = DB::table('mst_belong')->select('office_cd','belong_cd','belong_name')->get();

        return view('m03.index', compact('ofces','belongs','mstWorkStatus','checkHd','clas_HD','ofceCur','belong','msgErr','users'));
    }

    public function comment(){
        return response();
    }
    public function saveMessage(Request $request){
        $data = $request->all();
        $this->m03service->saveMessage($data);
        return route('m03');
    }
}