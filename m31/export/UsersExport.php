<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class UsersExport implements FromCollection, WithHeadings
{
    private $ids;

    public function __construct($ids) 
    {
        $this->ids = $ids;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $result = array();
        foreach($this->ids as $id){
            $records =  DB::table('info_users as i')
            ->leftJoin('contract_info_users as c', 'c.info_users_cd', '=', 'i.id')
            ->where('i.id',$id)
            ->select(
                'i.id as id',
                'c.id as idContract',
                'c.month_salary as month_salary',
                'c.hourly_salary as hourly_salary',
            )->get();
            foreach($records as $record){
                $result[] = array(
                    'id'=>$record->id,
                    'month_salary' => $record->month_salary,
                    'hourly_salary' => $record->hourly_salary,
                );
                $bens = DB::table('benefit_info_users as b')->where('b.contract_info_users_id',$record->idContract)->get();
                foreach($bens as $index => $ben){
                    $result[count($result) - 1]["手当$index"] = $ben->benefit_content; // Sử dụng
                    // $result = ["手当$index" => $ben->benefit_content]; // Thêm mộ
                    // $result["手当$index"] = $ben->benefit_content;
                    // array_push($result,$ben->benefit_content);
                }
            }
        }
        return collect($result);


    }

    public function headings(): array
    {
        $title = [
            '社員cd',
            '月給',
            '時間給',
        ];
        foreach (range(1, 30) as $i) {
            $title[] = "手当$i";
        }
        // array_push($title,array_map(fn($i) => "手当$i", range(1, 30)));
       return $title;
    }
    															

}
