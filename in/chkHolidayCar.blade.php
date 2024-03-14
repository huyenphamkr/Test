@php
    $day = "2024-03-19";
    $arrHoliday = ["2024-03-20", "2024-03-21", "2024-03-22", "2024-03-23", "2024-03-24"];
    // echo getDay($day);
    function getDay($day){
        $result = $value = 0;
        do {
            $day = date('Y-m-d', strtotime($day. ' + 1 days'));
            $result = chkOff($day, $value);
            echo 'val = '.$value.'<br>';
            if ($result == 0 && $value > 0) {
                $result = $value;
                $value--;
            }
        } while ($result > 0);
        return $day;
    }

    function chkOff($day, &$value) {
        $result = 0;
        $arrOff = [3,7];
        $arrHoliday = ["2024-03-20", "2024-03-21", "2024-03-22", "2024-03-23", "2024-03-24"];
        $dayNum = date("N", strtotime($day));
        if (in_array($dayNum, $arrOff) || in_array($day, $arrHoliday)) {
            $result =  1;
        }
        if (in_array($dayNum, $arrOff) && in_array($day, $arrHoliday)) {
            $result =  2;
            $value ++;
        }
        return $result;
    }
@endphp