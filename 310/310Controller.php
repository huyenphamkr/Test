<?php


namespace App\Http\Controllers;


use App\Common\MsgConst;
use App\Http\Controllers\Controller;
use App\Http\Requests\U03Request;
use App\Services\U03Service;
use App\Services\MstClassService;
use App\Services\CommonService;
use App\Services\M01Service;
use Illuminate\Http\Request;
use App\Common\CommonConst;
use Illuminate\Support\Facades\DB;
use App\Models\InfoUsers;

class U0310Controller extends Controller
{
    /**
     * U033 service
     *
     * @var U03Service
     */
    public $u03Service = null;


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
     * M01 service
     *
     * @var UM01Service
     */
    public $m01Service = null;


    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->u03Service = new U03Service;
        $this->m01Service = new M01Service;
        $this->mstClassService = new MstClassService;
        $this->commonService = new CommonService;
    }


    public function contract()
    {
        $idInfo = request('id',-1);
        $info = InfoUsers::find($idInfo);
        $arrBenCon = null;
        $contract = null;
        if($info)
        {      
            $office_cd = $info->office_cd;
            $contract = $this->u03Service->findContractInfo($idInfo);
            if(empty($contract))
            {
                $idHidConInfo = '';
                $contract = $this->m01Service->finByOfficeIdContract($office_cd);
                $arrBenCon = $this->m01Service->finByContractIdBenefit($contract->id);
            }
            else{
                $idHidConInfo = $contract->id;
                $arrBenCon = $this->u03Service->finByContInfoIdBenefit($contract->id);
                $inFoCon = $this->u03Service->findContractInfo($idInfo);
            }
            $mstClass = $this->mstClassService->findClassByType(CommonConst::YES_NO);
            $mstClsPay = $this->mstClassService->findClassByType(CommonConst::PAYMENT_METHOD);
            return view('u03.u0310', compact('contract', 'mstClass', 'mstClsPay','idInfo','arrBenCon','idHidConInfo','info','inFoCon'));
        }
        return view('u03.u0310', compact('contract', 'mstClass', 'mstClsPay','idInfo','arrBenCon','idHidConInfo','info'));

    }
   
    public function saveContractInfo(Request $request)
    {
        $data = $request->all();
        $id = $this->u03Service->saveContractInfo($data);
        // return route('u03.u03.11', $data['hidId']);
        return route('u0310', $id);
    }
}
