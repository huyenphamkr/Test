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
    $('textarea').change(function () {
        isChange = false;
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
        $("#flg6").prop('checked', false);
        Disable("#flg6");
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
            HiddenFlg("#rowFlg3");
            checkHid = true;
        }
        $("#flg3").prop('checked', false);
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

function OncheckFlg3(params) {
    if($(params).is(':checked') && $("#company_car").is(':checked'))
    {
        $(params).prop('checked', false);
    }else{
        if($(params).is(':checked'))
        {
            Disable("#company_car");
        }else
            Disable("#company_car",false);
    }
    Oncheck(params);
    Oncheck("#company_car");
}

function OnCheckManagerPosition(params)
{
    if($(params).is(':checked') && $("#flg6").is(':checked'))
    {
        $(params).prop('checked', true);
        $("#flg6").prop('checked', false);
        Disable("#flg6");
    }else{
        if($(params).is(':checked'))
        {
            Disable("#flg6");
        }else
            Disable("#flg6",false);
    }
    Oncheck(params);
    Oncheck("#flg6");
}

function checkFlg6(params) {
    if($(params).is(':checked') && $("#manage_position").is(':checked'))
    {
        $(params).prop('checked', false);
    }
    Oncheck(params);
    Oncheck("#manage_position");
}

function Oncheck(params) {
    ($(params).is(':checked')) ? $(params).val('1') : $(params).val('')
}

function Save(url, mode = 'save') {
    $('#hidMode').val(mode);
    ChkForm('U03Request', function() {
        Disable("#manage_position",false);
        Disable("#flg3",false);
        Disable("#flg6",false);
        Disable("#company_car",false);
        submitFormAjax(url);
    });
}

function Approval(url,mode,urlSave=null,yesFunc,noFunc) {
    let mes= '';
    switch (mode) {
        case 'author':
            mes = confirmSend;
            break;
        case 'admin1': case 'admin2': case 'hd': case 'appli':
            mes = confirmApprove;
            if(mode == 'hd' && !$('#btnHDConfirm').val()){
                ShowAlert(msgChkHd,$('#btnHDConfirm'));
                return false;
            }
            break;
        case 'admin1Reject': case 'admin2Reject': case 'hdReject': case 'appliReject': 
            mes = confirmReject;
            break;
        case 'btnHD':
            mes = confirmHD;
            break;
    }

    $('#hidMode').val(mode);
    if (isChange) {
        if(mode == 'admin1' || mode == 'admin2' || mode == 'hd' || mode == 'appli'|| mode == 'btnHD') {
            if( mode == 'btnHD' && !$('#email').hasClass("ctr-readonly")){
                ShowDialog(
                    mes,
                    yesFunc || function() {
                        submitFormAjax(url);
                    },
                    noFunc || function() {
                        HideModal("mdConfirm");
                    }
                );
            }else{
                ChkFormMesCus('U03Request',mes, function() {
                    submitFormAjax(url);
                });
            }
        }else{
            if(mode == 'admin1Reject' || mode == 'admin2Reject' || mode == 'hdReject' || mode == 'appliReject') {
                ShowDialog(
                    mes,
                    yesFunc || function() {
                        submitFormAjax(url);
                    },
                    noFunc || function() {
                        HideModal("mdConfirm");
                    }
                );
            } else Save(urlSave,mode);
        }
    }else{
        if(mode == 'author') Save(urlSave,mode);
        else{
            if(mode == 'admin1' || mode == 'admin2' || mode == 'hd' || mode == 'appli'|| mode == 'btnHD') {
                ChkFormMesCus('U03Request',mes, function() {
                    submitFormAjax(url);
                });
            }
            if(mode == 'admin1Reject' || mode == 'admin2Reject' || mode == 'hdReject' || mode == 'appliReject') {
                ShowDialog(
                    mes,
                    yesFunc || function() {
                        submitFormAjax(url);
                    },
                    noFunc || function() {
                        HideModal("mdConfirm");
                    }
                );
            }
        }
    }
}

function OnCheckPro(params){
    let nameProd = $('#lb'+$(params).attr('name')).html();
    if(nameProd === PROD_NAME_PHONE)
    {
        if($(params).is(':checked')){
            $(params).val('1');
            $('#txtCompany_mobile_number').removeClass("select-disable ctr-readonly");
        }
        else
        {
            $(params).val('');
            $('#txtCompany_mobile_number').val('');
            $('#txtCompany_mobile_number').addClass("select-disable ctr-readonly");
        }
    }
    else Oncheck(params);
}

function UpdateLeter(url,mode,urlNext,yesFunc,noFunc)
{
    if($('#hidId').val()){
        if (isChange && $('#btnReSend').parent().hasClass("d-none")) {
            ChkForm('U03Request', 
            yesFunc || function() {
                Disable("#manage_position",false);
                Disable("#flg3",false);
                Disable("#flg6",false);
                Disable("#company_car",false);
                submitFormAjax(url,window.location.href = urlNext);
            },
            noFunc || function() {
                window.location.href  = urlNext;
            });
        }else{
            window.location.href = urlNext;
        }
    }else{
        $('#hidMode').val(mode);
        ChkForm('U03Request', 
        yesFunc || function() {
            Disable("#manage_position",false);
            Disable("#flg3",false);
            Disable("#flg6",false);
            Disable("#company_car",false);
            submitFormAjax(url);
        },
        noFunc || function() {
            HideModal("mdConfirm");
        });
    }
}

function OnCheckForeign(params){
    if($(params).is(':checked')){
        $("#req3").toggle();
        $("#req2").toggle();
        $("#req1").toggle();
        $('#hidCheck').val('1');
    }
    else
    {
        $("#req3").hide();
        $("#req2").hide();
        $("#req1").hide();
        $('#hidCheck').val('');
    }
    Oncheck(params);
}
function ChkFormMesCus(requestName, msg, funcYes = null, funcNo = null, funcError = null){
    let formData = $('#form-main').serialize();
    formData += '&requestName=' + encodeURIComponent(requestName);
    let urlValidate = $('#hidValidate').val();
    CallAjax(urlValidate, formData, function(){
        ShowDialog(msg, funcYes, funcNo);
    }, function(results){
        console.clear();
        if(funcError){
            funcError();
        }else{
            if(results.responseJSON.errors){ 
                let error = results.responseJSON.errors;
                let inputId = Object.keys(error)[0].split('.');
                let name = inputId[0];
                let index = inputId[1];
                let input = index ? $(`[name^="${name}"]`)[index] : $(`[name^="${name}"]`);
                let msg = Object.values(error)[0];
                ShowAlert(msg, input);
            }else{
                console.log(results.responseJSON);
                ShowAlert(results.responseJSON.message);
            }
        }
    });
}

function ResendEmail(urlSend, mode) { 
    $('#hidMode').val(mode);
    ChkForm('U03Request', function() {
        submitFormAjax(urlSend);
    });
}

const msgChkHd = 'HD確認をしてください。';