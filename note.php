<?php
$phone_number = "12312341234";

$formatted_number = substr($phone_number, 0, 3) . '-' . substr($phone_number, 3, 4) . '-' . substr($phone_number, 7);

echo $formatted_number; // Hiển thị số điện thoại đã định dạng
echo '<br>';
$phone_number = "12312341234";

$formatted_number = substr_replace($phone_number, '-', 3, 0); // Thêm dấu gạch ngang sau 3 ký tự đầu tiên
$formatted_number = substr_replace($formatted_number, '-', 8, 0); // Thêm dấu gạch ngang sau 8 ký tự

echo $formatted_number; // Hiển thị số điện thoại đã định dạng
