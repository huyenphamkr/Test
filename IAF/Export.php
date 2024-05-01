<?php

extension=gd trong php.ini
run composer update
run composer require maatwebsite/excel --with-all-dependencies





https://makitweb.com/export-data-to-csv-excel-format-in-laravel-8/

jp
https://github.com/SpartnerNL/Laravel-Excel/issues/1171

laravel excel
https://docs.laravel-excel.com/nova/1.x/exports/customizations.html


//loop add
foreach (range(1, 5) as $i) {
    $tt = [
        '社員cd',
        '月給',
        '時間給',
    ];
    foreach (range(1, 3) as $i) {

        $tt[] = "手当$i";
    }
    //cach 1
    array_push($tt, 'end', 'e2');
    //cách 2
    $tt[] = 'end';
}

//
public function getTransportUsers($user)
{
    $result = '0';
    $trans = DB::table('info_users as i')->leftJoin('transport_users as t', 't.info_users_cd', '=', 'i.id')
        ->where('t.info_users_cd',$user->id)->get();

    if($trans)
    {
        $result = '0';
    }else{
        if(!$user->transport_type)
        {
            $result = 'Z';
        }else{
            $distance = $user->private_car / 2;
            if ($distance > 55) {
                $result = '1';
            } elseif ($distance >= 45) {
                $result = '2';
            } elseif ($distance >= 35) {
                $result = '3';
            } elseif ($distance >= 25) {
                $result = '4';
            } elseif ($distance >= 15) {
                $result = '5';
            } elseif ($distance >= 10) {
                $result = '6';
            } elseif ($distance >= 2) {
                $result = '7';
            }else
                $result = '8';
        }
    }
    return $result;
}
?>