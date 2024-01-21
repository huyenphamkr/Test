<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CommonService;
use App\Services\U09Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class U092Controller extends Controller
{
    private $u09Service = null;
    private $commonService = null;
    private $gate = null;

    public function __construct()
    {
        $this->middleware('auth');
        $this->u09Service = new U09Service();
        $this->commonService = new CommonService();
        $this->gate = new Gate;
    }

    public function index()
    {
        //check Session and authority
        if (Session::has("listIDInfoU09") && ($this->gate::check('isHD') || $this->gate::check('isHDLabour'))) {
            $dataId = Session::get("listIDInfoU09");
            $users  = $this->u09Service->getStaffTranfer($dataId);
            $ofces = $this->commonService->findAllOffice();
            $belongs = $this->commonService->findBelongByKey();
            return view('users.u092.index', compact('users','ofces','belongs'));
        }
        return redirect()->route('u091.transProcedure');
    }

    public function saveTransfer(Request $request)
    {
        $data = $request->all();
        $this->u09Service->saveTransfer($data);
        Session::forget("listIDInfoU09");
        return redirect()->route('u02');
    }

    public function checkExist(Request $request)
    {
        $arrId = $request->input('arrIdTrans');
        $check = false;
        foreach ($arrId as $idStaff) {
            $idTranfer =  DB::table("transfer_office_users")->where('staff_id',$idStaff)->get();
            if($idTranfer->isNotEmpty())
            {
                $check = true;
                return response()->json(['check' => $check]);
            }
            continue;
        }
        return response()->json(['check' => $check]);
    }
}