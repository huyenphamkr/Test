<?php


namespace App\Http\Controllers;


use App\Common\MsgConst;
use App\Http\Controllers\Controller;
use App\Http\Requests\U03Request;
use App\Services\U03Service;
use App\Services\MstClassService;
use App\Common\CommonConst;
use App\Services\M01Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Models\InfoUsers;


class U03Controller extends Controller
{
    /**
     * U03 service
     *
     * @var U03Service
     */
    public $u03Service = null;
    private $m01Service = null;


    /**
     * MstClass service
     *
     * @var MstClassService
     */
    public $mstClassService = null;


    /**
     * Common service
     *
     * @var CommonService
     */
    public $commonService = null;


    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        $this->u03Service = new U03Service;
        $this->m01Service = new M01Service();
        $this->mstClassService = new MstClassService;
        $this->commonService = new CommonService;
    }

//u03-1
    public function information(){
        $infoUsersId= request('id',-1);
        $item = $this->u03Service->findByIdInfoUser($infoUsersId);
        $office_cd = DB::table('users')->where('id',1)->value('office_cd');
        if ($item) {
            $office_cd = $item->office_cd;
        }
        $mstWorkTime = $this->mstClassService->findClassByType(CommonConst::WORK_TIME);
        $mstWorkStatus = $this->mstClassService->findClassByType(CommonConst::WORK_TIME);
        $ofces = $this->commonService->findAllOffice();
        $ofce = $this->commonService->findByIdOffice($office_cd);
        $belongs = $this->commonService->findBelongByKey();
        $prods = $this->u03Service->findProByKey($office_cd);
        $upOfices = $this->u03Service->findUpOffiByKey($office_cd);
        $upInfUser = $this->u03Service->findUpFileById($infoUsersId);
        return view('u03.information', compact('item','mstWorkTime','mstWorkStatus','ofces','belongs','prods','upOfices','ofce','upInfUser'));
    }

    function saveInformation(U03Request $request) {
        $data = $request->all();
        $this->u03Service->saveInformation($data);
        return route('u03.showInfo', '2');
        // return route('u03');
    }
   
    function deleteInformation(){
        $infoUsersId= request('id',-1);
        InfoUsers::destroy($infoUsersId);
        return redirect()->route('m02');
    }
    //end u03-1
    public function test(){
        // $item = $this->u03Service->getSecondApprove(,,,);

        // return view('welcome', compact('item'));
        return view('welcome');
    }
}
