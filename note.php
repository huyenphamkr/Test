//input yên
<div class="input-group">
  <input type="text" class="form-control">
  <span class="input-group-text">円</span>
</div>


//add
 <div class="input-group">
   <input type="text" class="form-control border-radius-none">
   <button class="btn btn-outline-secondary border-radius-none" type="button">+</button>
</div>

//Hidden or Display
<script>
    var x = document.getElementById("cbb").value;
    if (x) {
        alert(x);
        document.getElementById("txtDateS").disabled = false;
    } else {
        alert('ronf');
        alert(x);
        document.getElementById("txtDateS").disabled = true;
    }
    disableFunc();
    function disableFunc() {
        var e = document.getElementById("cbb").value;
        if (e == 2) {
            document.getElementById("txtDateS").disabled = true;
        } else {
            document.getElementById("txtDateS").disabled = false;
        }
    }

    </script>

/----------------------------------------------------------------------------------
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Định dạng số</title>
</head>
<body>
    <input type="text" id="inputNumber" placeholder="Nhập số">
    <button onclick="formatNumber()">Định dạng</button>
    <p id="formattedNumber"></p>

    <script>
        function formatNumber() {
            const inputElement = document.getElementById("inputNumber");
            const inputValue = inputElement.value;

            // Xóa tất cả các dấu chấm trong chuỗi
            const numberString = inputValue.replace(/\./g, '');

            // Chuyển đổi chuỗi thành số
            const number = parseInt(numberString, 10);

            // Định dạng số theo định dạng "9.999.999"
            const formattedNumber = new Intl.NumberFormat().format(number);

            // Hiển thị số đã định dạng
            document.getElementById("formattedNumber").textContent = formattedNumber;
        }
    </script>
</body>
</html>
---------------------------------------------------------------------------
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Định dạng và maxlength</title>
</head>
<body>
    <input type="text" id="inputNumber" placeholder="Nhập số %" maxlength="2" oninput="formatAndLimitInput(this)">
    
    <script>
        function formatAndLimitInput(inputElement) {
            // Loại bỏ tất cả ký tự không phải số và ký tự %
            inputElement.value = inputElement.value.replace(/[^\d%]/g, '');

            // Giới hạn độ dài của văn bản
            if (inputElement.value.length > 2) {
                inputElement.value = inputElement.value.slice(0, 2);
            }
        }
    </script>
</body>
</html>

---------------------------------------------------------------------------
