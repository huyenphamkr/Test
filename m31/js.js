<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm cột vào bảng</title>
    <style>
  
</style>
</head>

<body>
    <!-- <input type="checkbox" id="checkAll"> Chọn tất cả <br>
    <input type="checkbox" class="checkboxItem"> Checkbox 1 <br>
    <input type="checkbox" class="checkboxItem"> Checkbox 2 <br>
    <input type="checkbox" class="checkboxItem"> Checkbox 3 <br> -->
<!-- Thêm các checkbox cần thiết -->

<input type="checkbox" class="checkboxItem" value="Giá trị 1"> Checkbox 1 <br>
<input type="checkbox" class="checkboxItem" value="Giá trị 2"> Checkbox 2 <br>
<input type="checkbox" class="checkboxItem" value="Giá trị 3"> Checkbox 3 <br>
<!-- Thêm các checkbox cần thiết -->
<button onclick="checkChecked()">Kiểm tra checkbox đã chọn</button>



<!-- Một số ví dụ về các combobox và input -->
<select class="form-select" id="cmbOffice" name="cmbOffice">
    <option value="office1">Office 1</option>
    <option value="office2">Office 2</option>
    <option value="office3">Office 3</option>
</select>

<input id="txtIdStaff" type="text" value="">
<input id="txtNameStaff" type="text" value="">



    <script>
//    // Lấy tất cả các checkbox và checkbox "Chọn tất cả"
// const checkAll = document.getElementById('checkAll');
// const checkboxes = document.querySelectorAll('.checkboxItem');

// // Thêm sự kiện khi checkbox "Chọn tất cả" thay đổi trạng thái
// checkAll.addEventListener('change', function () {
//     checkboxes.forEach(function (checkbox) {
//         checkbox.checked = checkAll.checked;
//     });
// });

// // Thêm sự kiện khi các checkbox còn lại thay đổi trạng thái
// checkboxes.forEach(function (checkbox) {
//     checkbox.addEventListener('change', function () {
//         // Kiểm tra nếu tất cả các checkbox khác đều được chọn, thì checkbox "Chọn tất cả" cũng được chọn
//         let allChecked = true;
//         checkboxes.forEach(function (cb) {
//             if (!cb.checked) {
//                 allChecked = false;
//             }
//         });
//         checkAll.checked = allChecked;
//     });
// });

function checkChecked() {
    const checkboxes = document.querySelectorAll('.checkboxItem');
    let checkedCount = 0;

    checkboxes.forEach(function(checkbox) {
        if (checkbox.checked) {
            checkedCount++;
        }
    });

    if (checkedCount >= 1) {
        alert("Chỉ có một checkbox được chọn!");
    } else {
        alert("Số lượng checkbox được chọn không phù hợp.");
    }
}


// <!-- Một số ví dụ về các combobox và input -->
document.addEventListener('DOMContentLoaded', function() {
    const cmbOffice = document.getElementById('cmbOffice');
    const txtIdStaff = document.getElementById('txtIdStaff');
    const txtNameStaff = document.getElementById('txtNameStaff');
    
    cmbOffice.addEventListener('change', function() {
        const selectedValue = cmbOffice.value;
        // Thực hiện cập nhật giá trị của các input dựa trên giá trị mới của combobox
        if (selectedValue === 'office1') {
            txtIdStaff.value = 'Value for Office 1 ID';
            txtNameStaff.value = 'Value for Office 1 Name';
        } else if (selectedValue === 'office2') {
            txtIdStaff.value = 'Value for Office 2 ID';
            txtNameStaff.value = 'Value for Office 2 Name';
        } else if (selectedValue === 'office3') {
            txtIdStaff.value = 'Value for Office 3 ID';
            txtNameStaff.value = 'Value for Office 3 Name';
        }
    });
});

</script>
</body>

</html>

