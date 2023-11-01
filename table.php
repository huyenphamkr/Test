<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

    <title>Document</title>
</head>

<body>
    <form action="" method="post">
        <h2>hello</h2>
        <div class="col-md-12"><br>
            <h4 style="text-align: center">Chi tiết đơn đặt hàng</h4>
            <table id="tableContent" class="table table-bordered table-hover dataTable dtr-inline"
                aria-describedby="example2_info">
            </table>
        </div>
    </form>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

    <!-- javacript ckeditor -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>

</html>

<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
    }
});
var $i = 0;

loadTable();

function loadTable() {
    alert("vao");
    document.getElementById("tableContent").innerHTML += '<thead>' +
        '<tr style="text-align: center">' +
        '<th>#</th>' +
        '<th>ID</th>' +
        '<th>Tên sản phẩm</th>' +
        '<th>Số lượng</th>' +
        '<th>Giá</th>' +
        '<th>Thành tiền</th>' +
        '  <td style="text-align: center">' +
        '    <button onclick="add();" type="button" class="btn btn-primary btn-sm" href="#">' +
        '       <i class="fas fa-edit"></i>' +
        '    </button>' +
        '</tr>' +
        '</thead>' +
        '<tbody>';
    // for ($i = 0; $i < 3; $i++) {
    // SumPrice += detailOrder[$i].price * detailOrder[$i].amount;
    // SumAmount += Number(detailOrder[$i].amount);
    document.getElementById("tableContent").innerHTML += '<tr data-index='+ ($i + 1) +'>'+
        '  <td style="text-align: center">' + ($i + 1) + '</td>' +
        '  <td style="text-align: center">' + ($i + 1) + '</td>' +
        '  <td>' + ($i + 1) + '</td>' +
        '  <td style="text-align: center">' + ($i + 1) + '</td>' +
        '  <td style="text-align: center">' + +'</td>' +
        '  <td style="text-align: center">' + ($i + 1) + '</td>' +
        '  <td style="text-align: center">' +
        '    <button onclick="editItem(\'' + ($i + 1) +
        '\');" type="button" class="btn btn-primary btn-sm" href="#">' +
        '       <i class="fas fa-edit"></i>' +
        '    </button>' +
        '    <button onclick="removeRow(\'' + ($i + 1) +
        '\')" type="button" class="btn btn-danger btn-sm" href="#">' +
        '      <i class="fas fa-trash"></i>' +
        '    </button>' +
        '  </td>' +
        '</tr>';

        document.getElementById("tableContent").innerHTML += '</tbody>';
    // $('#tableContent').html(_xhtml);

}

function add()
{
     $i++ ;
    document.getElementById("tableContent").innerHTML += '<tr data-index='+ ($i + 1) +'>'+
    '  <td style="text-align: center">' + ($i + 1) + '</td>' +
    '<td><input type="text" name="fullname[]" class="form-control"></td>'+
            '<td><select name="designation[]" class="form-control">'+
                    '<option value="" selected>Select Designation</option>'+
                  ' <option value="Engineer">Engineer</option>'+
                    '<option value="Accountant">Accountant</option>'+
                '</select></td>'+
        '  <td>' + ($i + 1) + '</td>' +
        '  <td style="text-align: center">' + +'</td>' +
        '  <td style="text-align: center">' + ($i + 1) + '</td>' +
        '  <td style="text-align: center">' +
        '    <button onclick="editItem(\'' + ($i + 1) +
        '\');" type="button" class="btn btn-primary btn-sm" href="#">' +
        '       <i class="fas fa-edit"></i>' +
        '    </button>' +
        '    <button onclick="removeRow(\'' + ($i + 1) +
        '\')" type="button" class="btn btn-danger btn-sm" href="#">' +
        '      <i class="fas fa-trash"></i>' +
        '    </button>' +
        '  </td>' +
        '</tr>';
}
function removeRow(i) {
        $("tbody").find(`tr[data-index='${i}']`).remove();
    }
</script>


-----------------------------------------------------------------------------------------------------------------


<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<form action="" method="post">

    <table class="table table-hover small-text" id="tb">
        <tr class="tr-header">
            <th>Stt</th>
            <th>Full Name</th>
            <th>Designation</th>
            <th>Mobile No.</th>
            <th>Email Id</th>
            <th><a href="javascript:void(0);" style="font-size:18px;" id="addMore" title="Add More Person"><span class="glyphicon glyphicon-plus"></span></a></th>
        <tr>
            <td><script>i</script></td>
            <td><input type="text" name="fullname[]" class="form-control"></td>
            <td><select name="designation[]" class="form-control">
                    <option value="" selected>Select Designation</option>
                    <option value="Engineer">Engineer</option>
                    <option value="Accountant">Accountant</option>
                </select></td>
            <td><input type="text" name="mobileno[]" class="form-control"></td>
            <td><input type="text" name="emailid[]" class="form-control"></td>
            <td><a href='javascript:void(0);' class='remove'><span class='glyphicon glyphicon-remove'></span></a></td>
        </tr>
    </table>
    <button type="Submit" class="btn btn-primary ml-auto">Submit</button>
</form>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script>
    let i = 0;
    $(function() {
        $('#addMore').on('click', function() {
            i++;
            var data = $("#tb tr:eq(1)").clone(true).appendTo("#tb");
            data.find("input").val('');
        });
        $(document).on('click', '.remove', function() {
            var trIndex = $(this).closest("tr").index();
            if (trIndex > 1) {
                $(this).closest("tr").remove();
            } else {
                alert("Sorry!! Can't remove first row!");
            }
        });
    });
</script>

<?php

if (isset($_POST['submit'])) {
    echo 'bam rui';

    // Trả về $_POST['Color'] là một mảng, chúng ta phải sử dụng vòng lặp foreach để hiển thị mỗi giá trị

    foreach ($_POST['fullname'] as $select) {

        echo "Bạn đã chọn :" . $select; //Hiển thị giá trị được chọn

    }
}
?>

//----------------------------------------70%------------------------------------------------//
<head>
    <title></title>

    <!-- media query support -->
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <!-- font awsome css link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>

<div class="container">
    <h2>Welcome to dynamic input table with row adding option</h2>
    <table class="table table-hover my-5" id="tb">
        <thead class="">
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Pnone Number</th>
                <th>Email</th>
                <th>combobox</th>
                <th><i class="fa fa-plus-circle" style="font-size: 22px; color: blue;" onclick="addRow()"></i></th>
            </tr>
        </thead>

        <tbody>
        </tbody>

    </table>
    <div class="row m-0">
        <button class="btn btn-warning" onclick="addRow()">Add row</button>
        <button class="btn btn-danger ml-3">Delete last row</button>
        <button type="Submit" class="btn btn-primary ml-auto">Submit</button>
    </div>
</div>

<script>
    let i = 0;

    function rowTemplate(i) {
        return `<tr data-index=${i}>
      <td>${i}</td>
      <td><input type="text" name="name-[]"></td>
      <td><input type="text" name="phone-[]"></td>
      <td><input type="text" name="Email-[]"></td>
      <td>
        <select name="designation[]" class="form-control">
            <option value="" selected>Select Designation</option>
            <option value="Engineer">Engineer</option>
            <option value="Accountant">Accountant</option>
        </select>
      </td>
      <td><i class="fa fa-times-circle" style="font-size: 22px; color: red;" onclick="removeRow(${i})"></i></td>
    </tr>`
    }

    for (i = 1; i < 4; i++) {
        $('tbody').append(rowTemplate(i));
    }

    function removeRow(i) {
        $("tbody").find(`tr[data-index='${i}']`).remove();
    }

    function addRow() {
        $('tbody').append(rowTemplate(i));
        i++;
    }
</script>

//-------------------------------------------Request---------------------------------------------//
<form action="#" method="post">
<input type="text" name="fullname[]" class="form-control" value="11">
<input type="text" name="fullname[]" class="form-control" value="2222">
    <select name="Color[]" multiple> // Khởi tạo tên với một mảng

        <option value="Red">Red</option>

        <option value="Green">Green</option>

        <option value="Blue">Blue</option>

        <option value="Pink">Pink</option>

        <option value="Yellow">Yellow</option>

    </select>
    <input type="submit" name="submit" value="Lấy giá trị đã chọn" />

</form>

<?php

if (isset($_POST['submit'])) {

    // Trả về $_POST['Color'] là một mảng, chúng ta phải sử dụng vòng lặp foreach để hiển thị mỗi giá trị

    foreach ($_POST['Color'] as $select) {

        echo "Bạn đã chọn :" . $select; //Hiển thị giá trị được chọn

    }
    echo '<br>';
    foreach ($_POST['fullname'] as $a) {

        echo "gia tri :" . $a.'<br>'; //Hiển thị giá trị được chọn

    }
}
?>
