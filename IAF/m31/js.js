

$(document).ready(function() {
    Init();
    HideProgress();
});




function ShowPopMessage() {
    if(checkChecked())
    {
        let arr = getCheckboxChecked();
        var listCb = arr.join(',');
        document.getElementById('InfoCheck').value = listCb;
        $("#popMessage").val('通知');
        ShowModal('mdMessage');
    }
    else{
        alert('chua chon check box');
    }
}


function Init(){
    let checkAll = document.getElementById('checkAll');
    let checkboxes = document.querySelectorAll('.checkboxItem');
    // Select All changes state
    checkAll.addEventListener('change', function () {
        checkboxes.forEach(function (checkbox) {
            checkbox.checked = checkAll.checked;
        });
    });
   
    //the remaining checkboxes change state
    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            let allChecked = true;
            checkboxes.forEach(function (cb) {
                if (!cb.checked) {
                    allChecked = false;
                }
            });
            checkAll.checked = allChecked;
        });
    });
}


function checkChecked() {
    let checkboxes = document.querySelectorAll('.checkboxItem');
    let checkedCount = 0;
    checkboxes.forEach(function(checkbox) {
        if (checkbox.checked) {
            checkedCount++;
        }
    });
    return (checkedCount >= 1) ? true : false;
}

function getCheckboxChecked() {
    var selectedIDs = [];
    let checkboxes = document.querySelectorAll('.checkboxItem');
    checkboxes.forEach(function(checkbox) {
        if (checkbox.checked) {
            selectedIDs.push(checkbox.id);
            
        }
    });
    console.log('Selected IDs:', selectedIDs);
    return selectedIDs;
}

function SaveMessage(url){
    document.getElementById('txtMessage').value = document.getElementById('txtMes').value;
    ChkForm('', function() {
       submitFormAjax(url);
    });
    // ChkForm('M03Request', function() {
    //     // submitFormAjax(url);
    //  });
}
//set value input after serch
function SetUserFromSearch(obj, row = "") {
   
    HideModal("mdSearch");
}



Route::get('/m03', [M03Controller::class, 'index'])->name('m03');
Route::post('/m03', [M03Controller::class, 'saveMessage'])->name('m03.saveMessage');