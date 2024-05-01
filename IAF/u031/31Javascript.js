$(document).ready(function() {
    Init();
   
});


/**桁数分0をパディング */
function LeftPad(value, length, char = '0') {
    if (value == "") return value;
    return (char.repeat(length) + value) . slice(-length);
}


//check hidden
let checkHid = false;


function Init(){
    $('input').change(function () {
        isChange = true;
        const class_nm = $(this).attr("class");
        const ioPad = class_nm.indexOf("pad-0");
        // 桁数分0をパディング
        if (ioPad > -1) $(this).val(LeftPad($(this).val(), $(this).attr("maxlength")));
        if (typeof inputChangeEvent === "function") inputChangeEvent();
    });
   
}




function changeCmbOffice(yesFunc,noFunc) {
    ShowDialog(
        msgConfirm,
        yesFunc || function() {
            ShowProgress();
            setChangeCmbOffice();
            HideProgress();
        },
        noFunc || function() { HideModal("mdConfirm"); }
    );
    HideProgress();
}
function setChangeCmbOffice(){
    $('#txtCompany_mobile_number').addClass("ctr-readonly");


    const belong = $.parseJSON($("#hidBelong").val());
    const proItem = $.parseJSON($("#hidProItem").val());
    const Offices = $.parseJSON($("#hidOffice").val());
    const upOffice = $.parseJSON($("#hidUpOffice").val());
    const office = $("#cmbOffice").val();


    //clear
    $("#cmbBelong option:not(:first)").remove();
    $("#ListProItem").empty();
    $("#ListUpOffice").empty();


    //set value onchang
    AddCmbBelong(belong,"#cmbBelong",office);
    AddProItem(proItem,"#ListProItem",office);
    AddUpOffice(upOffice,"#ListUpOffice",office);
    SetListInfoCB(Offices,office);
}




function AddCmbBelong(arr,id,condition) {
    arr.filter((item) => {
        if (item.office_cd == condition) {
            $(id).append(
                $("<option></option>").attr("value", item.belong_cd).text(item.belong_name)
            );
        }
    });
}


function AddProItem(arr,id,condition) {
    arr.filter((item,index) => {
        if (item.office_cd == condition) {
            $(id).append(
                `<div class="form-check col-4 col-md-3 col-xl-2 mb-2">
                    <input class="form-check-input" type="checkbox" onchange="OnCheckPro(this)" value="" id="prod_${index}" name="prod_${item.id}">
                    <input type="hidden" value="${item.id}" id="hidprod_${index}" name="prod[]">
                    <label class="form-check-label" for="prod_${index}" id="lbprod_${item.id}">${item.name }</label>
                   
                </div>`
            );
        }
    });
}
   
function AddUpOffice(arr,id,condition) {
    arr.filter((item,index) => {
        if (item.office_cd == condition) {
            $(id).append(
                `<div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" onchange="Oncheck(this)" value="1" id="up_offi_${index}" name="up_offi_${item.id }">
                    <input type="hidden" value="${item.id }" id="hidup_offi_${index}" name="up_offi[]">
                    <label class="form-check-label" for="up_offi_${item.id }">${item.file_name }</label>
                </div>`
            );
        }
    });
}


function SetListInfoCB(Offices,office) {
    Offices.filter((item,index) => {
        if (item.office_cd == office) {
            for (let index = 3; index < 10; index++) {
                if(index == 6) continue;
                let flg = "flg"+index;
                (item[flg] != 1) ? HiddenFlg("#rowFlg"+index) : HiddenFlg("#rowFlg"+index,false);
                $('#'+flg).attr('checked', false);
            }
            if (item.flg1 != 1)
            {
                HiddenFlg("#rowFlg1");
                HiddenFlg("#rowAccountant");
            }
            else
            {
                HiddenFlg("#rowFlg1",false);
                HiddenFlg("#rowAccountant",false);
            }
        }
    });
}

function HiddenFlg(params,isHide=true){
    isHide ? $(params).hide() : $(params).show();
}

function Disable(params,isDis=true) {
    isDis ? $(params).prop( "disabled", true ) : $(params).prop( "disabled", false );
}

function OnCheckManager(params) {
    if($(params).is(':checked')){
       
        $("#manage_position").prop('checked', true);
        Disable("#manage_position");
    }
    else{
        Disable("#manage_position",false);
    }


    $("#flg6").prop('checked', false);


    Oncheck(params);
    Oncheck("#manage_position");
    Oncheck("#flg6");
}


function OncheckCompanyCar(params) {
    if($(params).is(':checked')){
        if($('#rowFlg3').css('display') == 'none'){
            HiddenFlg("#rowFlg3",false);
            checkHid = true;
        }
        $("#flg3").prop('checked', true);
        Disable("#flg3");
    }
    else{
        Disable("#flg3",false);
        if(checkHid)
        {
            HiddenFlg("#rowFlg3");
        }
    }
    Oncheck(params);
    Oncheck("#flg3");
}


function checkFlg6(params) {
    if($(params).is(':checked') && $("#manager").is(':checked'))
    {
        $(params).prop('checked', false);
    }
}


function Oncheck(params) {
    ($(params).is(':checked')) ? $(params).val('1') : $(params).val('')
}


function Save(url,yesFunc,noFunc) {
    Disable("#manage_position",false);
    Disable("#flg3",false);
    ChkForm('U03Request', function() {
        submitFormAjax(url)
    });
}


function Approval(url, mode) {
    $('#hidMode').val(mode);
    Save(url);
}


function OnCheckPro(params){
    let nameProd = $('#lb'+$(params).attr('name')).html();
    if(nameProd === PROD_NAME_PHONE)
    {
        if($(params).is(':checked')){
            $(params).val('1');
            $('#txtCompany_mobile_number').removeClass("ctr-readonly");
        }
        else
        {
            $(params).val('');
            $('#txtCompany_mobile_number').addClass("ctr-readonly");
        }
    }
    else Oncheck(params);
}
function UpdateLeter(url,urlNext,yesFunc,noFunc)
{
    if (isChange) {
        ShowDialog(
            msgConfirm,
            yesFunc || function() {
            submitFormAjax(url,window.location.href = urlNext)
            },
            noFunc || function() {
                window.location.href  = urlNext;
            }
        ); 
    }else{
        window.location.href = urlNext;
    }
}

function BackPrev(urlY, urlN, yesFunc, noFunc){
    if (isChange) {
        ShowDialog(
            msgConfimBack,
            yesFunc || function() { window.location.href = urlY; },
            noFunc || function() { window.location.href = urlN; } //u03/4
        );  
    }else{
        window.location.href = urlY;
    }
}
