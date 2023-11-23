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

function OnCheckPro(params){
    let nameProd = $('#lb'+$(params).attr('name')).html();
    // alert(idLabel);
    if(nameProd === "ctycheck")
    {
        alert(nameProd);
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
------------------------------------------------------------------------------------------------------------------------------------------
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
                    {{ $idPro == $prod->id ? 'checked value=1' : 'value' }} @endforeach
                                id="prod_{{ $loop->iteration }}" name="prod_{{ $prod->id }}">
                            <label class="form-check-label" id="lbprod_{{ $prod->id }}"
                                for="prod_{{ $prod->id }}">{{ $prod->name }}</label>
                            <input type="hidden" value="{{ $prod->id }}"
                                id="hidprod_{{ $loop->iteration }}" name="prod[]">
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
        class="form-control border-radius-none allow_numeric {{ $disabledPhone }}" maxlength="20"
        value="{{ $item->company_mobile_number ?? '' }}">
</div>
-------------------------------------------------------------------------------------------------------------------------------------
<?php
    $mtb2_l4 = 'mt-2 mb-2 ms-4';
    $hidden_cb = 'style=display:none';
    $arrPro = $item ? explode(',', $item->procedure_items) : [];
    $arrUpOffi = $item ? explode(',', $item->sign_items) : [];
    $url = route('u03.saveInfo');
    $urlDel = "{{route('u03.deleteInfo')}}";
    $disabled = '';
    $disabledPhone = '';
    $stop = false;
    if (!isset($item->id)) {
        $disabled = 'ctr-readonly';
    }
    if (empty($item->procedure_items)) {
        $disabledPhone = ' ctr-readonly';
    }
    
    foreach ($prods as $prod) {
        foreach ($arrPro as $idPro) {
            if ($prod->name === 'ctycheck' && $idPro == $prod->id) {
                $disabledPhone = '';
                $stop = true;
                break;
            } else {
                $disabledPhone = 'ctr-readonly';
            }
        }
        if ($stop) {
            break;
        }
    }
    ?>
