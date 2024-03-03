<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Services\U02Service;
use App\Services\U08Service;
use App\Services\MstClassService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Common\MsgConst;
use Illuminate\Support\Facades\Gate;
use App\Common\CommonConst;
use App\Services\CommonService;

class U02Controller extends Controller
{
    private $u02service = null;
    private $classervice = null;
    private $common = null;
     /**
     * U08 service
     * 
     * @var U08Service
     */
    public $u08Service = null;
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->u02service = new U02Service();
        $this->u08Service = new U08Service;
        $this->classervice = new MstClassService();
        $this->common = new CommonService();
    }

    /**
     * Index action
     
     */
    public function index(Request $request)
    {
        $gate_Ser = new Gate;
        $msgErr = null;
        $clas_procedure = $this->classervice->findClassByType('PROCEDURE_TYPE');

        $mode = $this->common->getAuthorityByIdStaff(Auth::id());

        
        switch($mode){
            case '3': 
                $clas_procedure = $clas_procedure->filter(function ($value){
                    $clas_procedure = in_array($value->item_cd, ['U04']);
                    return $clas_procedure;
                });
                break;
            case '4': 
                $clas_procedure = $clas_procedure->filter(function ($value){
                    $clas_procedure = !in_array($value->item_cd, ['U03', 'U05', 'U07']);
                    return $clas_procedure;
                });
                break;
            default:
                $clas_procedure = $clas_procedure->filter(function ($value){
                    $clas_procedure = !in_array($value->item_cd, ['']);
                    return $clas_procedure;
                });
                break;
        }
        
        $clas_HD = $this->classervice->findClassByType('HD_TYPE');
        
        $belong = DB::table('mst_belong')->select('office_cd','belong_cd','belong_name')->get();
        
        $user = $this->u08Service->findUsersById(Auth::id());
        $office_cdSearch = $user->office_cd;
        if($gate_Ser::check('isHD'))
        {
            $office_cdSearch = '';
            $ofce = DB::table('mst_offices')->select('office_cd', 'office_name')->get();
        }
        else{
            $ofce = DB::table('mst_offices')->select('office_cd', 'office_name')->where('office_cd',$user->office_cd)->get();
        }

        $YearMonthSearch =  date('Y-m');
        $ScreenTypeSearch = '';
        $belong_cdSearch = '';
        $name_infoSearch = '';
        $HDSearch = '';
        if($mode == '3') $ScreenTypeSearch =  'U04' ;
        if ($request->input('btnSearch')) {
            $YearMonthSearch =  $request['txtYM'] ;
            $ScreenTypeSearch =  $request['cmbClass'] ;
            $office_cdSearch =  $request['cmbOffice'] ;
            $belong_cdSearch =  $request['cmbBelong'] ;
            $name_infoSearch =  $request['txtName'] ;
            $HDSearch =  $request['cmbHD'] ;
        }
        $arraycontent = explode('-', $YearMonthSearch);
        $YearSearch =  $arraycontent[0];       
        $MonthSearch = $arraycontent[1];   
        $ArrayList = [];   
        if (empty($ScreenTypeSearch )  ) {
            //U03
            $ArrayU03= $this->u02service->findU03($YearSearch,$MonthSearch ,$office_cdSearch,$belong_cdSearch,$name_infoSearch,$HDSearch,1, CommonConst::PROCEDURE_TYPE_U03); 
            $ArrayList = $ArrayU03;
            //U04
            $ArrayU04= $this->u02service->findU04($YearSearch,$MonthSearch ,$office_cdSearch,$belong_cdSearch,$name_infoSearch,$HDSearch,CommonConst::PROCEDURE_TYPE_U04);
            $ArrayList = $ArrayList->merge($ArrayU04); 
            $ArrayU04Finish= $this->u02service->findU03($YearSearch,$MonthSearch ,$office_cdSearch,$belong_cdSearch,$name_infoSearch,$HDSearch,1, CommonConst::PROCEDURE_TYPE_U04); 
            $ArrayList = $ArrayList->merge($ArrayU04Finish); 
            //U05
            $ArrayU05= $this->u02service->findU05($YearSearch,$MonthSearch ,$office_cdSearch,$belong_cdSearch,$name_infoSearch,$HDSearch,CommonConst::PROCEDURE_TYPE_U05);
            $ArrayList = $ArrayList->merge($ArrayU05); 
            $ArrayU05Finish= $this->u02service->findU03($YearSearch,$MonthSearch ,$office_cdSearch,$belong_cdSearch,$name_infoSearch,$HDSearch,1, CommonConst::PROCEDURE_TYPE_U05); 
            $ArrayList = $ArrayList->merge($ArrayU05Finish); 
            //U07
            $ArrayU07= $this->u02service->findU07($YearSearch,$MonthSearch ,$office_cdSearch,$belong_cdSearch,$name_infoSearch,$HDSearch,1,CommonConst::PROCEDURE_TYPE_U07);
            $ArrayList = $ArrayList->merge($ArrayU07);    
            
            //U10
            $ArrayU10= $this->u02service->findU07($YearSearch,$MonthSearch ,$office_cdSearch,$belong_cdSearch,$name_infoSearch,$HDSearch,1,CommonConst::PROCEDURE_TYPE_U10);
            $ArrayList = $ArrayList->merge($ArrayU10);  

            //U11
            $ArrayU11= $this->u02service->findU03($YearSearch,$MonthSearch ,$office_cdSearch,$belong_cdSearch,$name_infoSearch,$HDSearch,1, CommonConst::PROCEDURE_TYPE_U11); 
            $ArrayList = $ArrayList->merge($ArrayU11);   
        }   
        elseif($ScreenTypeSearch =='U03' ) {
            $ArrayU03= $this->u02service->findU03($YearSearch,$MonthSearch ,$office_cdSearch,$belong_cdSearch,$name_infoSearch,$HDSearch,1, CommonConst::PROCEDURE_TYPE_U03); 
            $ArrayList = $ArrayU03;
        } elseif($ScreenTypeSearch =='U04' ) {   
            $ArrayU04Finish= $this->u02service->findU03($YearSearch,$MonthSearch ,$office_cdSearch,$belong_cdSearch,$name_infoSearch,$HDSearch,1, CommonConst::PROCEDURE_TYPE_U04); 
            $ArrayList = $ArrayU04Finish;
            $ArrayU04= $this->u02service->findU04($YearSearch,$MonthSearch ,$office_cdSearch,$belong_cdSearch,$name_infoSearch,$HDSearch,CommonConst::PROCEDURE_TYPE_U04);
            $ArrayList = $ArrayList->merge($ArrayU04); 
        }  
        elseif($ScreenTypeSearch =='U05' ) {  
            $ArrayU05Finish= $this->u02service->findU03($YearSearch,$MonthSearch ,$office_cdSearch,$belong_cdSearch,$name_infoSearch,$HDSearch,1, CommonConst::PROCEDURE_TYPE_U05); 
            $ArrayList = $ArrayU05Finish; 
            $ArrayU05= $this->u02service->findU05($YearSearch,$MonthSearch ,$office_cdSearch,$belong_cdSearch,$name_infoSearch,$HDSearch,CommonConst::PROCEDURE_TYPE_U05);
            $ArrayList = $ArrayList->merge($ArrayU05);          
        }  
        elseif($ScreenTypeSearch =='U07' ) {   
            $ArrayU07= $this->u02service->findU07($YearSearch,$MonthSearch ,$office_cdSearch,$belong_cdSearch,$name_infoSearch,$HDSearch,1,CommonConst::PROCEDURE_TYPE_U07);
            $ArrayList = $ArrayU07; 
        }  
        elseif($ScreenTypeSearch =='U10' ) {   
            $ArrayU10= $this->u02service->findU07($YearSearch,$MonthSearch ,$office_cdSearch,$belong_cdSearch,$name_infoSearch,$HDSearch,1,CommonConst::PROCEDURE_TYPE_U10);
            $ArrayList = $ArrayU10; 
        }  
        elseif($ScreenTypeSearch =='U11' ) {   
            $ArrayU11= $this->u02service->findU03($YearSearch,$MonthSearch ,$office_cdSearch,$belong_cdSearch,$name_infoSearch,$HDSearch,1, CommonConst::PROCEDURE_TYPE_U11); 
            $ArrayList =$ArrayU11;   
        }   
        // $ArrayList = $ArrayList->sortByDesc('created_at')->sortByDesc('id');
        $ArrayList = $ArrayList->sortBy([
            ['created_at', 'desc'],
            ['id', 'desc']
            
        ]);
        if (count($ArrayList) == 0) $msgErr = MsgConst::MSG_NO_DATA;
        return view('users.u02.index', compact('ArrayList','YearMonthSearch','ScreenTypeSearch','office_cdSearch',
                                        'belong_cdSearch','name_infoSearch','HDSearch','clas_procedure','clas_HD','ofce','belong','msgErr','user'));
    }

    public function comment(){
        return response();
    }
}
