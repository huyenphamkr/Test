<?php
namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Services\U07Service;
use App\Services\MstClassService;
use App\Common\CommonConst;
use Illuminate\Support\Facades\DB;
use App\Services\CommonService;
use Illuminate\Support\Facades\Auth;
use App\Services\U03Service;
use Illuminate\Http\Request;
use App\Models\User;
use App\Common\UploadFunc;
use App\Models\LetterRetireInfoUsers;
class U07Controller extends Controller
{
     /**
     * U03 service
     *
     * @var U03Service
     */
    public $u03Service = null;
    /**
     * U07 service
     *
     * @var U07Service
     */
    public $u07Service = null;


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
        $this->middleware('auth');
        $this->u07Service = new U07Service;
        $this->u03Service = new U03Service;
        $this->mstClassService = new MstClassService;
        $this->commonService = new CommonService;
    }
    public function retireProc(){
       
        $staffId = request('id') ? request('id') : -1 ;
        $user = User::firstOrNew(['id' => $staffId]);
        $infoUsersId = $user->info_users_cd;
        $item = $this->u03Service->findByIdInfoUser($infoUsersId);
        $itemRetire = $this->u07Service->findByIdInfoUser($infoUsersId);
        $LetterRetireInfoUsers = LetterRetireInfoUsers::where('info_users_cd',$infoUsersId )->first();
        // $office_cd = $this->u03Service->getOffiCdInfoByIdUser(Auth::id());
        if ($item) {
            $office_cd = $item->office_cd;
        }
        $ofces = $this->commonService->findAllOffice();
        $ofce = $this->commonService->findByIdOffice($office_cd);
        $mstWorkStatus = $this->mstClassService->findClassByType(CommonConst::WORK_STATUS);
        $belongs = $this->commonService->findBelongByKey();
        $mstWorkTime = $this->mstClassService->findClassByType(CommonConst::WORK_TIME);
        $prods = $this->u03Service->findProByKey($office_cd);
        UploadFunc::delTmpFolder();
        return view('users.u07.retireProc',compact('user','item','itemRetire','LetterRetireInfoUsers' ,'ofces','ofce','mstWorkStatus','belongs','mstWorkTime','prods'));
    }  
    public function save(Request $request)
    {
        $data = $request->input();
        $mode = $data['hidModeButton'];
        $staffId = $data['hidStaffId'];
        switch ( $mode)
        {    
            case 'save':
                $this->u07Service->registerRetire('Retire',$data);  
                return  redirect()->route('u07.retireProc', ['id' => $staffId]);  
                break;  
            case CommonConst::C_作成者:
                $this->u07Service->registerRetire('Retire',$data);  
                return  redirect()->route('u07.retireProc', ['id' => $staffId]);  
                break;    
            case CommonConst::C_HD承認:
                $this->u07Service->updateStatusRetire($data);  
                return  redirect()->route('u07.retireProc', ['id' => $staffId]);  
                break;      
            default:  
                $this->u07Service->updateStatusRetire($data);  
                return  redirect()->route('u07.retireProc', ['id' => $staffId]);    
                break;                      
        }    
    }
    function deleteRetire(){
        $infoUsersId= request('id',-1);
        $this->u07Service->deleteRetire($infoUsersId);
        return redirect()->route('u02');
    }
}
