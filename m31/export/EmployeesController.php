<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\EmployeesExport;
use App\Exports\EmployeesByAgeExport;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class EmployeesController extends Controller
{
    public function index(){
       return view('index');
    }

    // CSV Export
    public function exportCSV(){
       $file_name = 'employees_'.date('Y_m_d_H_i_s').'.csv';
       return Excel::download(new EmployeesExport, $file_name);
    }

    // Excel Export
    public function exportExcel(){
       $file_name = 'employees_'.date('Y_m_d_H_i_s').'.xlsx';
       return Excel::download(new EmployeesExport, $file_name);
    }

    // Conditional Export (csv)
    public function exportByAgeCSV(Request $request){
      $off = 'NameOff';
       $age = [1,2];

       $file_name = '固定金額('.$off.')_'.date('Y_m_d').'.xlsx';
       return Excel::download(new UsersExport($age), $file_name);
    }
}
