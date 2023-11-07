//addtable child
<script>
                        function AddRow() {
                            var table = document.getElementById("tableContent");
                            var rowCount = table.rows.length;

                            // Increase rowspan of the first header cell
                            var firstHeaderCell = table.rows[0].cells[0];
                            firstHeaderCell.rowSpan += 1;
                            if (rowCount > 29) {
                                alert('> 30');
                                return false;
                            }
                            // Create a new row
                            var newRow = table.insertRow(rowCount);
                            var cell1 = newRow.insertCell(0);
                            cell1.innerHTML = '<td style="width:250px"><div class="p-1 text-center"><label>' +
                                rowCount + '</label></div></td>';
                            var cell2 = newRow.insertCell(1);
                            cell2.innerHTML = '<td><div class="input-group">' +
                                '<input type="text" name="cell' + rowCount +
                                '"class="form-control border-radius-none">' +
                                '<span class="input-group-text">円</span>' +
                                '</div></td>';
                            var cell3 = newRow.insertCell(2);
                            cell3.innerHTML = '<td><button onclick="DelRow(' + rowCount +
                                ')" type="button" class="btn-none fs-25">' +
                                '<span class="bi bi-plus-circle-fill primary">-</span></button></td>';
                        }

                        function DelRow(rowCount) {
                            // var table = document.getElementById("tableContent");

                            // // Check if there is only one row and return if true
                            // if (table.rows.length <= 1 || rowCount < 1) {
                            //     return;
                            // }
                            // else{
                            //     // Decrease rowspan of the first header cell
                            // var firstHeaderCell = table.rows[0].cells[0];
                            // table.deleteRow(rowCount);

                            // firstHeaderCell.rowSpan -= 1;


                            // }
                            var table = document.getElementById("tableContent");
                            var row = table.rows[rowCount];
                            row.remove();

                            // // Decrease rowspan of the first header cell
                            // var firstHeaderCell = table.rows[0].cells[0];
                            // firstHeaderCell.rowSpan -= 1;

                            // // Remove the row based on rowCount
                            // if (rowCount) {
                            //     table.deleteRow(rowCount);
                            // }
                        }
                        </script>
                        <tr>
                            <td colspan="10">
                                <table id="tableContent" class="table-bordered border-dark">
                                    <tbody id="tblList">
                                        <tr>
                                            <td style="width: 200px;" rowspan="1" class="{{$bg_text_center}}">
                                                <div class="p-1 text-center">
                                                    <label>2.諸手当一覧諸手当追</label>
                                                    <button onclick="AddRow()" type="button" class="btn-none fs-25">
                                                        <span class="bi bi-plus-circle-fill primary">+</span>
                                                    </button>
                                                </div>
                                            </td>
                                            <td style="width:250px">
                                                <div class="p-1 text-center"><label>2.3</label></div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="text" class="form-control border-radius-none">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                            </td>
                                            <td>
                                                <button onclick="DelRow(0)" type="button" class="btn-none fs-25">
                                                    <span class="bi bi-plus-circle-fill primary">-</span>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
--------------------------------------------------------------------------------------------------------------------

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
