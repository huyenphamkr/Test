<?php
$disabled = '';
if (empty($item->procedure_items)) {
    $disabled = 'select-disable ctr-readonly';
}
?>
<div class="row mb-4">
  <div class="col-xl-8 mb-xl-0 mb-md-2">
      <fieldset class="legend-box">
          <legend class="legend-title">入社前手続き</legend>
          <div class="legend-body">
              <div class="row ms-1" id="ListProItem">
                  @foreach ($prods as $prod)
                  @if ($item && $item->office_cd == $prod->office_cd && $prod->selected == '1')
                  <div class="form-check col-4 col-md-3 col-xl-2 mb-2">
                      <input class="form-check-input" type="checkbox" onchange="OnCheckPro(this)"
                          @foreach ($arrPro as $idPro)
                              {{$idPro == $prod->id  ? 'checked' : '' }}
                          @endforeach
                          value="1" id="prod_{{ $loop->iteration }}" name="prod_{{$prod->id}}">
                      <label class="form-check-label" for="prod_{{$prod->id}}">{{ $prod->name }}</label>
                      <input type="hidden" value="{{$prod->id}}" id="hidprod_{{$loop->iteration}}" name="prod[]">
                  </div>
                  @endif
                  @endforeach
              </div>
          </div>
      </fieldset>
  </div>


 <div class="col-xl-2 col-md-4 mb-0 mt-auto">
    <div class="bg-title text-center p-1"><label>会社携帯番号</label></div>
    <input type="text" id="txtCompany_mobile_number" name="txtCompany_mobile_number" autocomplete="off" 
    class="form-control border-radius-none allow_numeric {{$disabled}}" 
    maxlength="20" value="{{ $item->company_mobile_number  ?? ''}}">
</div>


=========-------------------------------------------------JS---------------------------------------------
function setChangeCmbOffice(){

    //------------------------------------------------------------------------
    $('#txtCompany_mobile_number').prop('readonly', true); 
    //------------------------------------------------------------------------

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

function AddProItem(arr,id,condition) {
    arr.filter((item,index) => {
        if (item.office_cd == condition) {
            $(id).append(
                `<div class="form-check col-4 col-md-3 col-xl-2 mb-2">
                    <input class="form-check-input" type="checkbox" onchange="OnCheckPro(this)" value="" id="prod_${index}" name="prod_${item.id}">
                    <input type="hidden" value="${item.id}" id="hidprod_${index}" name="prod[]">
                    <label class="form-check-label" for="prod_${index}">${item.name }</label>
                </div>`
            );
        }
    });
}
function OnCheckPro(params){
    if($(params).is(':checked')){
        $(params).val('1');
       $('#txtCompany_mobile_number').prop('readonly', false);
    }
    else
    {
        $(params).val("");
        CheckListProd();

    }
   CheckListProd();
}

function CheckListProd()
{
    let idProd = document.getElementsByName('prod[]');
    for (let i = 0; i <idProd.length; i++) {
        if($(`#prod_${idProd[i].value}`).val() && $(`#prod_${idProd[i].value}`).val() != '0' && $(`#prod_${idProd[i].value}`).val() != ''){ 
            $('#txtCompany_mobile_number').prop('readonly', false); 
            break;
        }
        else
        {
            $('#txtCompany_mobile_number').prop('readonly', true); 
        }
    }
};
