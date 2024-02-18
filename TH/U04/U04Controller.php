<?php

namespace App\Http\Controllers;

use App\Common\MsgConst;
use App\Common\CommonFunc;
use App\Common\CommonConst;
use App\Services\M01Service;
use App\Services\M02Service;
use App\Services\U04Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class U04Controller extends Controller
{
    /**
     * u04 service
     * 
     * @var U04Service
     */
    protected $u04Service;

    /**
     * M02 service
     * 
     * @var M02Service
     */
    protected $m02Service;

    /**
     * M01 service
     * 
     * @var M01Service
     */
    protected $m01Service;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(U04Service $u04Service, M02Service $m02Service, M01Service $m01Service)
    {
        $this->u04Service = $u04Service;
        $this->m02Service = $m02Service;
        $this->m01Service = $m01Service;
    }

    /**
     * Register action
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index(Request $request)
    {
        $data = [];
        $page = request('page');
        $sessionNm = "u04" . CommonConst::Search_Session_Nm;

        if (Session::has($sessionNm)) {
            $data = Session::get($sessionNm);
        }
        if ($request->isMethod('post')) {
            $data = $request->all();
            $mode = isset($data["hidMode"]) ? $data["hidMode"] : "";
            if ($mode == CommonConst::Mode_Insert) {
                $result = $this->u04Service->registerCar($data);
                if ($result) return redirect()->route('u04', ['page' => $page]);
                else CommonFunc::createErrorMsg(MsgConst::Msg_Reg_Fail, $data, "保存");
            } else {
                Session::put($sessionNm, $data);
            }
        }

        $carPayments = $this->u04Service->findByKeySrc($data);
        $venues = $this->m02Service->getOthersById(CommonConst::Other_Type_Venue_Id);
        $users = $this->m01Service->getAll();
        if ($carPayments->isEmpty()) {
            if (!empty($page) && ($page != 1)) return redirect()->route('u04');
            CommonFunc::createErrorMsg(MsgConst::Msg_No_Data, $data);
        }
        return view('u04.index', compact('data', 'venues', 'page', 'users', 'carPayments'));
    }
}