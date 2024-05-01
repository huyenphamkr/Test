window.onload = function() {
    let tags = document.getElementsByTagName("textarea");
    for (let i = 0; i < tags.length; i++) {
        let textarea = tags[i];
        textarea.style.height = "auto"; // Set the height to auto to recalculate the size
        let computedHeight = textarea.scrollHeight;
        textarea.style.height = computedHeight + "px"; // Set the new height based on the calculated height
    }
};

function HiddenFlg(params,isHide=true){
    isHide ? $(params).hide() : $(params).show();
}

function Oncheck(params) {
    if($(params).is(':checked')){
        if($('#btnNext').css('display') == 'none'){
            HiddenFlg("#btnNext",false);
            checkHid = true;
        }
    }
    else{
        HiddenFlg("#btnNext");
    }
}

function Save(url) {
    ChkForm('U0310Request', function(){
        submitFormAjax(url)
    });
}
