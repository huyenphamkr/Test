<form action="#" method="post">
    <input type="text" name="fullname[]" id="fn1" class="form-control" value="11">
    <input type="text" name="fullname[]" id="fn2" class="form-control" value="aa">
    <input type="text" name="fullname[]" id="fn3" class="form-control" value="2222">
    <select name="Color[]" id="cb1"> // Khởi tạo tên với một mảng
        <option value="Red">Red</option>
        <option value="Green">Green</option>
        <option value="Blue">Blue</option>
        <option value="Pink">Pink</option>
        <option value="Yellow">Yellow</option>
    </select>
    <select name="Color[]" id="cb2"> // Khởi tạo tên với một mảng
        <option value="Red">Red</option>
        <option value="Green">Green</option>
        <option value="Blue">Blue</option>
        <option value="Pink">Pink</option>
        <option value="Yellow">Yellow</option>
    </select>
    <input type="submit" name="submit" value="Lấy giá trị đã chọn" />
</form>
<script>
// alert(document.getElementById("cb2").value);

getvalues();
function getvalues() {
    var inps = document.getElementsByName('fullname[]');
    for (var i = 0; i < inps.length; i++) {
        var inp = inps[i];
        console.log("fullname[" + i + "].value=" + inp.value);
    }
}
</script>
<?php

if (isset($_POST['submit'])) {

    // Trả về $_POST['Color'] là một mảng, chúng ta phải sử dụng vòng lặp foreach để hiển thị mỗi giá trị

    foreach ($_POST['Color'] as $select) {
        $res = 0;
        echo "Bạn đã chọn :" . $select,'<br>'; //Hiển thị giá trị được chọn

    }
    echo '<br>';
    foreach ($_POST['fullname'] as $a) {

        echo "gia tri :" . $a.'<br>'; //Hiển thị giá trị được chọn

    }
}
?>
