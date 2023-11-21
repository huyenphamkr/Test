-----------------------------------------đệm 0 trước---------------------------------------------
function padZeroToSeven($input) {
    return str_pad($input, 7, '0', STR_PAD_LEFT);
}

// Sử dụng function: đệm 0 trước
$inputNumber = "12345";
$result = padZeroToSeven($inputNumber);
echo "Kết quả: " . $result;
-----------------------------------------xóa đệm 0 trước---------------------------------------------
function padZeroToSeven($input) {
    $paddedInput = str_pad($input, 7, '0', STR_PAD_LEFT);
    return ltrim($paddedInput, '0');
}

// Sử dụng function:
$inputNumber = "0012345";
$result = padZeroToSeven($inputNumber);
echo "Kết quả: " . $result;
-----------------------------------------xác đinh check bõ đc chọn---------------------------------------------
<!DOCTYPE html>
<html>
<head>
    <title>Checkbox Example</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>Chọn các mục:</h2>
    <input type="checkbox" class="myCheckbox" value="item1"> Mục 1<br>
    <input type="checkbox" class="myCheckbox" value="item2"> Mục 2<br>
    <input type="checkbox" class="myCheckbox" value="item3"> Mục 3<br>
    <input type="checkbox" class="myCheckbox" value="item4"> Mục 4<br>
    <input type="checkbox" class="myCheckbox" value="item5"> Mục 5<br>
    <button id="checkButton">Kiểm tra</button>

    <script>
        $(document).ready(function(){
            $('#checkButton').on('click', function(){
                var atLeastOneChecked = false;
                $('.myCheckbox').each(function(){
                    if ($(this).is(':checked')) {
                        atLeastOneChecked = true;
                        return false; // Thoát khỏi vòng lặp khi tìm thấy checkbox được chọn
                    }
                });

                if (atLeastOneChecked) {
                    alert('Ít nhất một checkbox đã được chọn.');
                } else {
                    alert('Không có checkbox nào được chọn.');
                }
            });
        });
    </script>
</body>
</html>
