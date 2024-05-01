@extends('layouts.layout')
<!-- Screen U03_7 -->
@section('title', '入職者申請')

@push('scripts')
<script src="{{ asset('js/users/u037_application.js?v=' . time()) }}"></script>
@endpush

@section('content')

<style>
    .table>:not(caption)>*>* {
        padding: 0 0;
    }
    .width-gr-1{
        min-width: 220px; 
        width: 220px;
    }
    .width-gr-2{
        min-width: 250px;  
        width: 300px;
    }
    .width-gr-3{
        min-width: 200px;  
        width: 200px;
    }
    .table-secondary {
        border-color: rgba(var(--bs-dark-rgb), var(--bs-border-opacity)) !important;
    }
    .border-top-right-none {
        border-right-style: hidden;
        border-top-style: hidden;
    }
    .border-top-bot-right-none {
        border-right-style: hidden;
        border-top-style: hidden;
        border-bottom-style: hidden;
    }
    .border-right-none {
        border-right-style: hidden;
    }

    .border-radius-none {
        border-radius: 0px;
    }
    .bd-t-r {
        border-top-color: rgba(var(--bs-dark-rgb), var(--bs-border-opacity)) !important;
        border-right-color: rgba(var(--bs-dark-rgb), var(--bs-border-opacity)) !important;
    }
    .primary {
        color: #0d6efd;
        font-weight: bold;
    }
    .vertical-mid{
        vertical-align: middle;
    }
    .border-top-bot{
        border-top: 1px solid !important;
        border-bottom: 1px solid !important
    }
    .icon-inp{
        position: static;
        top: unset;
        right: unset;
        transform: translateY(-50%);
        border: 0;
        margin-right: 0; 
    }
    .icon-active{
        margin-right: 0px !important;
    }
</style>

<?php
    $bg_text_center = "table-secondary border-dark";
    $arrObjectPerson = ($infoUsers->object_person) ? explode(',', $infoUsers->object_person) : [];
?>

<div class="edit-content">
    <input type="hidden" id="hidInfoUsersId" name="hidInfoUsersId" value="{{$infoUsers->id ?? ''}}">
    <input type="hidden" id="hidMode" value="{{$mode['edit'] ? '1' : '0'}}" >
    <div class="row">
        <div class="col-md-3 col-xl-3 mb-3">
            <div class="bg-title text-center p-1"><label>氏名</label></div>
            <input id="txtName" name="txtName" class="form-control border-radius-none ctr-readonly" maxlength="62" value="{{ $infoUsers ? ($infoUsers->first_name ? ($infoUsers->first_name .($infoUsers->last_name ? '　' . $infoUsers->last_name : '')) : $infoUsers->last_name) : '' }}">
        </div>
        <div class="col-md-3 col-xl-3 mb-3">
            <div class="bg-title text-center p-1"><label>ﾌﾘｶﾞﾅ</label></div>
            <input id="txtFurigana" name="txtFurigana" class="form-control border-radius-none ctr-readonly" maxlength="62" 
            value="{{ $infoUsers ? ($infoUsers->first_furigana ? ($infoUsers->first_furigana .($infoUsers->last_furigana ? ' ' . $infoUsers->last_furigana : '')) : $infoUsers->last_furigana) : '' }}">
        </div>
        <div class="col-md-3 col-xl-3 mb-3">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>契約区分</label></div>
            <select name="cmbContract_class" id="cmbContract_class" class="form-select border-radius-none {{$mode['edit'] ? '' : 'pointless'}}" onchange="setTblInfoUsers();">
                @if (!empty($cmbContractClass))
                    @foreach ($cmbContractClass as $item)
                        @if (!empty($item))
                            <option value="{{ $item->item_cd }}" 
                            @if ($infoUsers->contract_class == $item->item_cd) selected @endif 
                            >
                                {{ $item->item_name }}
                            </option>
                        @endif
                    @endforeach
                @endif
            </select>
        </div>
    </div>
</div>
<div id="divInfoUsers" class="table-responsive">
    <table class="table table-bordered border-dark">
        <tr>
            <td class="width-gr-1 vertical-mid {{$bg_text_center}}" style="min-width: 220px; width: 220px">
                <div class="p-1 text-center"><span class="note">*</span><label>性別</label></div>
            </td>
            <td class="width-gr-2 {{ $mode['edit'] ? '' : 'pointless' }}">
                <select name="cmbGender" id="cmbGender" class="form-select border-radius-none bd-t-r border-0">
                    @if (!empty($cmbGender))
                        @foreach ($cmbGender as $item)
                            @if (!empty($item))
                                <option value="{{ $item->item_cd }}" 
                                @if ($infoUsers->gender == $item->item_cd) selected @endif 
                                >
                                    {{ $item->item_name }}
                                </option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </td>
            <td colspan="3" class="border-top-right-none"></td>
        </tr>
        <tr>
            <td class="vertical-mid {{$bg_text_center}}">
                <div class="p-1 text-center"><span class="note">*</span><label>電話番号</label></div>
            </td>
            <td class="d-flex align-items-center border-0 p-2 {{ $mode['edit'] ? '' : 'pointless' }}">
                <input id="txtTel_number1" name="txtTel_number1" class="form-control border-radius-none  allow_numeric w-c-100 h-c-35 border-secondary" maxlength="5" autocomplete="off" {{ $mode['edit'] ? 'autofocus' : ''}}
                    value="{{ explode('-', $infoUsers->tel_number)[0] ?? '' }}">
                <span>ー</span>
                <input id="txtTel_number2" name="txtTel_number2" class="form-control border-radius-none allow_numeric w-c-100 h-c-35 border-secondary" maxlength="5" autocomplete="off" {{ $mode['edit'] ? 'autofocus' : ''}}
                    value="{{ explode('-', $infoUsers->tel_number)[1] ?? '' }}">
                <span>ー</span>
                <input id="txtTel_number3" name="txtTel_number3" class="form-control border-radius-none allow_numeric w-c-100 h-c-35 border-secondary" maxlength="5" autocomplete="off" {{ $mode['edit'] ? 'autofocus' : ''}}
                    value="{{ explode('-', $infoUsers->tel_number)[2] ?? '' }}">
            </td>
            <td colspan="3" class="border-top-right-none"></td>
        </tr>
        <tr>
            <td class="vertical-mid {{$bg_text_center}}">
                <div class="p-1 text-center"><span class="note">*</span><label>〒</label></div>
            </td>
            <td>
                <input id="txtPost_code" name="txtPost_code" class="form-control border-radius-none allow_postal {{$mode['edit'] ? '' : 'pointless'}}" maxlength="8" autocomplete="off"
                    value="{{ $infoUsers->post_code }}">
            </td>
            <td colspan="3" class="border-top-right-none vertical-mid">
                <div class="px-2">
                    <button type="button" class="btn btn-info py-1 {{$mode['edit'] ? '' : 'visually-hidden'}}" id="btnPost" onclick="CheckPostCode('#txtPost_code', '#txtAdress1', '#txtAdress_furigana1')">取得</button>
                    <button type="button" class="btn btn-outline-info py-1 {{$mode['edit'] ? '' : 'visually-hidden'}}" onclick="ClearPostCodeInfo('#txtPost_code', '#txtAdress1', '#txtAdress_furigana1')">クリア</button>
                    <label>郵便番号がわからない方は</label><a class="text-decoration-underline" href="{{ CommonConst::POST_CODE_LINK }}" target="_blank" id="btnPost_code">こちら</a>
                </div>
            </td>
        </tr>
        <tr>
            <td class="vertical-mid {{$bg_text_center}}">
                <div class="p-1 text-center"><span class="note">*</span><label>住所1</label></div>
            </td>
            <td colspan="4" class="{{$mode['edit'] ? '' : 'pointless'}}">
                <input id="txtAdress1" name="txtAdress1" class="form-control border-radius-none align-top" maxlength="30" 
                    value="{{ $infoUsers->adress1 }}">
            </td>
        </tr>
        <tr>
            <td class="vertical-mid {{$bg_text_center}}">
                <div class="p-1 text-center"><label>住所2</label></div>
            </td>
            <td colspan="4" class="{{$mode['edit'] ? '' : 'pointless'}}">
                <input id="txtAdress2" name="txtAdress2" class="form-control border-radius-none align-top" maxlength="30" 
                    value="{{ $infoUsers->adress2 }}">
            </td>
        </tr>
        <tr>
            <td class="vertical-mid {{$bg_text_center}}">
                <div class="p-1 text-center"><label>住所3</label></div>
            </td>
            <td colspan="4" class="{{$mode['edit'] ? '' : 'pointless'}}">
                <input id="txtAdress3" name="txtAdress3" class="form-control border-radius-none align-top" maxlength="30" 
                    value="{{ $infoUsers->adress3 }}">
            </td>
        </tr>
        <tr>
            <td class="vertical-mid {{$bg_text_center}}">
                <div class="p-1 text-center"><span class="note">*</span><label>住所1（ﾌﾘｶﾞﾅ）</label></div>
            </td>
            <td colspan="4" class="{{$mode['edit'] ? '' : 'pointless'}}">
                <input id="txtAdress_furigana1" name="txtAdress_furigana1" class="form-control border-radius-none align-top" maxlength="30" 
                    value="{{ $infoUsers->adress_furigana1 }}">
            </td>
        </tr>
        <tr>
            <td class="vertical-mid {{$bg_text_center}}">
                <div class="p-1 text-center"><label>住所2（ﾌﾘｶﾞﾅ）</label></div>
            </td>
            <td colspan="4" class="{{$mode['edit'] ? '' : 'pointless'}}">
                <input id="txtAdress_furigana2" name="txtAdress_furigana2" class="form-control border-radius-none align-top" maxlength="30" 
                    value="{{ $infoUsers->adress_furigana2 }}">
            </td>
        </tr>
        <tr>
            <td class="vertical-mid {{$bg_text_center}}">
                <div class="p-1 text-center"><label>住所3（ﾌﾘｶﾞﾅ）</label></div>
            </td>
            <td colspan="4" class="{{$mode['edit'] ? '' : 'pointless'}}">
                <input id="txtAdress_furigana3" name="txtAdress_furigana3" class="form-control border-radius-none align-top" maxlength="30" 
                    value="{{ $infoUsers->adress_furigana3 }}">
            </td>
        </tr>
        <tr>
            <td class="vertical-mid {{$bg_text_center}}">
                <div class="p-1 text-center"><span class="note">*</span><label>生年月日</label></div>
            </td>
            <td class="{{$mode['edit'] ? '' : 'pointless'}}">
                <input id="txtBirthday" name="txtBirthday" type="date" autocomplete="off"
                    maxlength="10" min="{{ CommonConst::DATE_MIN }}" max="{{ CommonConst::DATE_MAX }}" class="form-control border-radius-none"
                    value="{{ !empty($infoUsers) && ($infoUsers->birthday != '') ? date_format(date_create($infoUsers->birthday), CommonConst::DATE_FORMAT_2 )  : '' }}" >
            </td>
            <td colspan="3" class="border-right-none"></td>
        </tr>
        <tr>
            <td class="vertical-mid {{$bg_text_center}}">
                <div class="p-1 text-center"><label>入社日</label></div>
            </td>
            <td class="{{$mode['edit'] ? '' : 'pointless'}}">
                <input id="txtDate_join" name="txtDate_join" type="date" autocomplete="off"
                    maxlength="10" min="{{ CommonConst::DATE_MIN }}" max="{{ CommonConst::DATE_MAX }}" class="form-control border-radius-none select-disable ctr-readonly"
                    value="{{ !empty($infoUsers) && ($infoUsers->date_join != '') ? date_format(date_create($infoUsers->date_join), CommonConst::DATE_FORMAT_2 )  : date(DATE_FORMAT_2) }}" >
            </td>
            <td colspan="3" class="border-top-right-none"></td>
        </tr>
        <tr>
            <td class="vertical-mid {{$bg_text_center}}">
                <div class="p-1 text-center"><span class="note">*</span><label>前職（会社名）</label></div>
            </td>
            <td colspan="4" class="{{$mode['edit'] ? '' : 'pointless'}}">
                <input id="txtPrev_job" name="txtPrev_job" class="form-control border-radius-none align-top" maxlength="50" 
                    value="{{ $infoUsers->prev_job }}">
            </td>
        </tr>
        <tr>
            <td class="vertical-mid {{$bg_text_center}}">
                <div class="p-1 text-center"><span class="note">*</span><label>マイナンバー</label></div>
            </td>
            <td colspan="4" class="{{$mode['edit'] ? '' : 'pointless'}}">
                <input id="txtMy_number" name="txtMy_number" class="form-control border-radius-none allow_postal" maxlength="12" autocomplete="off" 
                    value="{{ $infoUsers->my_number }}">
            </td>
        </tr>
        <tr>
            <td class="vertical-mid {{$bg_text_center}}">
                <div class="p-1 text-center"><label>該当者は選択</label></div>
            </td>
            <td class="{{$mode['edit'] ? '' : 'pointless'}}">
                <div class="d-flex p-2 {{$infoUsers->contract_class == '2' ? 'select-disable ctr-readonly' : ''}}" id="groupObjPerson">
                    @if (!empty($cmbObjectPerson))
                        @foreach ($cmbObjectPerson as $item)
                            @if (!empty($item))
                            <div class="me-3">
                                <input type="checkbox" id="checkbox_{{$loop->iteration}}" name="checkbox_{{$loop->iteration}}"
                                @foreach ($arrObjectPerson as $idObPer) 
                                    {{$idObPer == $item->item_cd  ? 'checked' : '' }} 
                                @endforeach value="{{$item->item_cd}}"> 
                                <label for="checkbox{{$loop->iteration}}">{{$item->item_name}}</label>
                                <input type="hidden" value="{{$item->item_cd }}" id="hidcheckbox_{{ $loop->iteration }}" name="checkbox[]">
                            </div>
                            @endif
                        @endforeach
                    @endif       
                </div>
            </td>
            <td colspan="3" class="border-right-none vertical-mid"></label></td>
        </tr>
        <tr>
            <td class="vertical-mid {{$bg_text_center}}">
                <div class="p-1 text-center"><label>社会保険加入</label></div>
            </td>
            <td class="{{$mode['edit'] ? '' : 'pointless'}}">
                <select name="cmbJion_insurance" id="cmbJion_insurance" class="form-select border-radius-none border-0 bd-t-r" onchange="setInsuranceDate();">
                    @if (!empty($cmbYES_NO))
                        @foreach ($cmbYES_NO as $item)
                            @if (!empty($item))
                                <option value="{{ $item->item_cd }}" 
                                @if ($infoUsers->jion_insurance == $item->item_cd) selected @endif 
                                >
                                    {{ $item->item_name }}
                                </option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </td>
            <td class="vertical-mid {{$bg_text_center}}" style="min-width: 220px; width: 220px;">
                <div class="p-1 text-center"><label>社会保険加入日</label></div>
            </td>
            <td class="width-gr-2 {{$mode['edit'] ? '' : 'pointless'}}">
                <input id="txtInsurance_date" name="txtInsurance_date" type="date" autocomplete="off"
                    maxlength="10" min="{{ CommonConst::DATE_MIN }}" max="{{ CommonConst::DATE_MAX }}" class="form-control border-radius-none"
                    value="{{ !empty($infoUsers) && ($infoUsers->insurance_date != '') ? date_format(date_create($infoUsers->insurance_date), CommonConst::DATE_FORMAT_2 )  : '' }}" >
            </td>
            <td class="border-top-right-none"></td>
        </tr>
        <tr>
            <td class="vertical-mid {{$bg_text_center}}">
                <div class="p-1 text-center"><label>雇用保険加入</label></div>
            </td>
            <td class="{{$mode['edit'] ? '' : 'pointless'}}">
                <select name="cmbInsurance_social" id="cmbInsurance_social" class="form-select border-radius-none border-0 bd-t-r" onchange="setInsuranceDate();">
                    @if (!empty($cmbYES_NO))
                        @foreach ($cmbYES_NO as $item)
                            @if (!empty($item))
                                <option value="{{ $item->item_cd }}" 
                                @if ($infoUsers->insurance_social == $item->item_cd) selected @endif 
                                >
                                    {{ $item->item_name }}
                                </option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </td>
            <td class="vertical-mid {{$bg_text_center}}">
                <div class="p-1 text-center"><label>雇用保険加入日</label></div>
            </td>
            <td class="{{$mode['edit'] ? '' : 'pointless'}}">
                <input id="txtInsurance_social_date" name="txtInsurance_social_date" type="date" autocomplete="off"
                    maxlength="10" min="{{ CommonConst::DATE_MIN }}" max="{{ CommonConst::DATE_MAX }}" class="form-control border-radius-none"
                    value="{{ !empty($infoUsers) && ($infoUsers->insurance_social_date != '') ? date_format(date_create($infoUsers->insurance_social_date), CommonConst::DATE_FORMAT_2 )  : '' }}" >
            </td>
            <td class="border-top-bot-right-none"></td>
        </tr>
    </table>
    <div id="groupDependents" class="mb-4">
        <fieldset class="legend-box">
            <legend class="legend-title">◆扶養家族</legend>
            <div class="legend-body">
                <div class="w-100 table-responsive">
                    <table class="table table-bordered border-dark">
                        <thead>
                            <tr class="border-radius-none border-0">
                                <td class="width-gr-3 vertical-mid border-top-bot {{$bg_text_center}}">
                                    <div class="p-1 text-center"><label>扶養家族</label></div>
                                </td>
                                <td class="border-top-bot {{$mode['edit'] ? '' : 'pointless'}}" style="min-width: 200px;">
                                    <div class="input-group align-items-center ctr-readonly">

                                        <input id="txtDependent_number" name="txtDependent_number" class="form-control border-radius-none border-0 allow_numeric text-end ctr-readonly" autocomplete="off" 
                                        value="{{ $infoUsers->dependent_number ?? 0 }}">
                                        <span class="me-1">人</span>
                                    </div>
                                </td>
                                <td colspan="2" class="width-gr-3 border-radius-none border-0 vertical-mid" style="width: 400px; min-width: 400px;">※あなたが扶養する義務がある方を記入して下さい</td>
                                <td class="w-c-50 text-center vertical-mid border-radius-none border-0">
                                    <button type="button" class="btn-none text-primary {{$mode['edit'] ? '' : 'visually-hidden'}}" onclick="AddRowDependent()">
                                        <i class="bi bi-plus-circle-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        </thead>
                        <tbody id="tblDependent" class="{{ $mode['edit'] ? '' : 'pointless' }}"></tbody>
                    </table>
                </div>
                <div class="d-none">
                    <select id="cmbGenderDefault" name="cmbGenderDefault" class="txt-driver form-select border-radius-none">
                        @if (!empty($cmbGender))
                            @foreach ($cmbGender as $item)
                                @if (!empty($item))
                                    <option value="{{ $item->item_cd }}" >
                                    {{ $item->item_name }}
                                    </option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>
                <input type="hidden" id="arrDependent" value="{{ count($dependent) > 0 ? json_encode($dependent) : '' }}">
            </div>
        </fieldset>
    </div>
    <div>給与振込口座</div>
    <table class="table table-bordered border-dark">
        <tr>
            <td class="width-gr-1 vertical-mid {{$bg_text_center}}">
                <div class="p-1 text-center"><span class="note">*</span><label>銀行コード</label></div>
            </td>
            <td class="width-gr-2 {{$mode['edit'] ? '' : 'pointless'}}">
                <input id="txtBank_code" name="txtBank_code" class="form-control border-radius-none allow_numeric" maxlength="4" autocomplete="off" 
                    value="{{ $infoUsers->bank_code }}">
            </td>
            <td class="width-gr-1 vertical-mid {{$bg_text_center}}">
                <div class="p-1 text-center"><span class="note">*</span><label>銀行名・振興金庫名・組合名</label></div>
            </td>
            <td class="width-gr-2 {{$mode['edit'] ? '' : 'pointless'}}">
                <input id="txtBank_name" name="txtBank_name" class="form-control border-radius-none" maxlength="20" autocomplete="off" 
                    value="{{ $infoUsers->bank_name }}">
            </td>
            <td class="border-top-bot-right-none"></td>
        </tr>
        <tr>
            <td class="width-gr-1 vertical-mid {{$bg_text_center}}">
                <div class="p-1 text-center"><span class="note">*</span><label>支店コード</label></div>
            </td>
            <td class="width-gr-2 {{$mode['edit'] ? '' : 'pointless'}}">
                <input id="txtBranch_bank_code" name="txtBranch_bank_code" class="form-control border-radius-none allow_numeric" maxlength="3" autocomplete="off" 
                    value="{{ $infoUsers->branch_bank_code }}">
            </td>
            <td class="width-gr-1 vertical-mid {{$bg_text_center}}">
                <div class="p-1 text-center"><span class="note">*</span><label>支店名・出張所/代理店名</label></div>
            </td>
            <td class="width-gr-2 {{$mode['edit'] ? '' : 'pointless'}}">
                <input id="txtBranch_bank_name" name="txtBranch_bank_name" class="form-control border-radius-none" maxlength="20" autocomplete="off" 
                    value="{{ $infoUsers->branch_bank_name }}">
            </td>
            <td class="border-top-bot-right-none"></td>
        </tr>
    </table>
    <div>口座名義</div>
    <table class="table table-bordered border-dark">
        <tr>
            <td class="width-gr-1 vertical-mid {{$bg_text_center}}">
                <div class="p-1 text-center"><span class="note">*</span><label>口座氏名</label></div>
            </td>
            <td class="width-gr-2 {{$mode['edit'] ? '' : 'pointless'}}">
                <input id="txtBank_user_name" name="txtBank_user_name" class="form-control border-radius-none" maxlength="30" autocomplete="off" 
                    {{-- value="{{ $infoUsers->bank_user_name }}"> --}}
            value="{{ $infoUsers ? ($infoUsers->first_name ? ($infoUsers->first_name .($infoUsers->last_name ? '　' . $infoUsers->last_name : '')) : $infoUsers->last_name) : '' }}">

            </td>
            <td class="width-gr-1 vertical-mid {{$bg_text_center}}">
                <div class="p-1 text-center"><span class="note">*</span><label>ﾌﾘｶﾞﾅ</label></div>
            </td>
            <td class="width-gr-2 {{$mode['edit'] ? '' : 'pointless'}}">
                <input id="txtBank_user_furigana" name="txtBank_user_furigana" class="form-control border-radius-none" maxlength="30" autocomplete="off" 
                    {{-- value="{{ $infoUsers->bank_user_furigana }}"> --}}
            value="{{ $infoUsers ? ($infoUsers->first_furigana ? ($infoUsers->first_furigana .($infoUsers->last_furigana ? ' ' . $infoUsers->last_furigana : '')) : $infoUsers->last_furigana) : '' }}">

            </td>
            <td class="border-top-bot-right-none"></td>
        </tr>
        <tr>
            <td class="vertical-mid {{$bg_text_center}}">
                <div class="p-1 text-center"><span class="note">*</span><label>預金種類</label></div>
            </td>
            <td class="{{$mode['edit'] ? '' : 'pointless'}}">
                <select id="cmbDeposit_type" name="cmbDeposit_type" class="txt-driver form-select border-radius-none">
                    @if (!empty($cmbDepositType))
                        @foreach ($cmbDepositType as $item)
                             @if (!empty($item))
                                <option value="{{ $item->item_cd }}" 
                                @if ($infoUsers->deposit_type == $item->item_cd) selected @endif >
                                {{ $item->item_name }}
                                </option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </td>
            <td class="vertical-mid {{$bg_text_center}}">
                <div class="p-1 text-center"><span class="note">*</span><label>口座番号</label></div>
            </td>
            <td class="{{$mode['edit'] ? '' : 'pointless'}}">
                <input id="txtBank_number" name="txtBank_number" class="form-control border-radius-none allow_numeric" maxlength="7" autocomplete="off" 
                    value="{{ $infoUsers->bank_number }}">
            </td>
            <td class="border-top-bot-right-none"></td>
        </tr>
    </table>
    <div class="mb-4">※注意事項:上記はすべて給与振込に必要な事項となっていますので、記入漏れないようにして下さい</div>
    <div class="mb-4">
        <fieldset class="legend-box">
            <legend class="legend-title">◆交通費(1日/往復)</legend>
            <div class="legend-body">
                <div class="w-100 table-responsive">
                    <table class="table table-bordered border-dark mb-0">
                        <tbody>
                            <tr class="border-radius-none border-0">
                                <td class="width-gr-3 vertical-mid border-top-bot {{$bg_text_center}}">
                                    <div class="p-1 text-center"><label>自家用車</label></div>
                                </td>
                                <td class="border-top-bot {{$mode['edit'] ? '' : 'pointless'}}" style="width: 400px; min-width: 200px;">
                                    <div class="input-group align-items-center">
                                        <input id="txtPrivate_car" name="txtPrivate_car" class="form-control border-radius-none border-0 allow_money text-end" maxlength="11" autocomplete="off" 
                                        value="{{ $infoUsers->private_car }}">
                                        <span class="me-1">km</span>
                                    </div>
                                    
                                </td>
                                <td colspan="3" class="border-radius-none border-0 vertical-mid" style="min-width: 600px;">※自宅〜勤務先までの往復実走距離</td>
                                <td class="w-c-50 text-center vertical-mid border-radius-none border-0"></td>
                            </tr>
                        </tbody>
                    </table> 
                    <table class="table table-bordered border-dark mb-4">
                        <thead>
                            <tr class="border-radius-none border-0">
                                <td colspan="5" class="border-radius-none border-0 vertical-mid" style="width:1000px; min-width: 1000px;">　</td>
                                <td class="w-c-50 text-center vertical-mid border-radius-none border-0 {{$mode['edit'] ? '' : 'd-none'}}">
                                    <button id="btnAddTrans" type="button" class="btn-none text-primary" onclick="AddRowTransport()">
                                        <i class="bi bi-plus-circle-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        </thead>
                        <tbody id="tblTransport" class="{{$mode['edit'] ? '' : 'pointless'}}"></tbody>
                    </table>
                    <table class="table table-bordered border-dark">
                        <tbody>
                            <tr class="border-radius-none border-0">
                                <td class="width-gr-3 vertical-mid border-top-bot {{$bg_text_center}}">
                                    <div class="p-1 text-center"><label>その他</label></div>
                                </td>
                                <td class="border-top-bot {{$mode['edit'] ? '' : 'pointless'}}" style="width: 400px; min-width: 200px;">
                                    <select name="cmbTransport_type" id="cmbTransport_type" class="form-select border-radius-none border-0">
                                        <option value=""></option>
                                        @if (!empty($cmbTransportType))
                                            @foreach ($cmbTransportType as $item)
                                                @if (!empty($item))
                                                    <option value="{{ $item->item_cd }}" 
                                                    @if ($infoUsers->transport_type == $item->item_cd) selected @endif 
                                                    >
                                                        {{ $item->item_name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td colspan="3" class="border-radius-none border-0 vertical-mid" style="min-width: 600px;">※自転車・徒歩の選択</td>
                                <td class="w-c-50 text-center vertical-mid border-radius-none border-0"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <input type="hidden" id="arrTransport" value="{{ count($transport) > 0 ? json_encode($transport) : '' }}">
            </div>
        </fieldset>
    </div>
    <div class="mb-4">
        <fieldset class="legend-box" style="height: 160px" >
            <legend class="legend-title"><span class="note">*</span>◆通勤経路マップ</legend>
            <div class="legend-body">
                <div class="d-flex h-100">
                    <div class="w-75">
                        <a target="_blank" id="linkRouteMap" href="{{ !empty($upInfUser['type5']) ? asset($upInfUser['type5'][0]['path']) : '' }}">
                            <img id="imgRouteMap" name="imgRouteMap" class="img-fluid" style="height: 97%!important;" src="{{ !empty($upInfUser['type5']) ? asset($upInfUser['type5'][0]['path']) : '' }}">
                        </a>         
                        <input type="hidden" id="curRouteMap" name="curRouteMap" value="{{ !empty($upInfUser['type5']) ? 1 : 0 }}">
                        <input type="hidden" id="hidRouteMapId" name="hidRouteMapId" value="{{ !empty($upInfUser['type5']) ? $upInfUser['type5'][0]['id'] : '' }}">
                        <input id="fileRouteMap" name="fileRouteMap" type="file" class="visually-hidden" onchange="ChangeFile('RouteMap', 1, 0)" />
                        <input id="tmpRouteMap" name="tmpRouteMap" type="hidden" />
                    </div>
                    <div id="iconRouteMap" class="w-25 text-end {{$mode['edit'] ? '' : 'visually-hidden'}}">
                        <button class="btn-none h2 mt-auto ms-auto " type="button" onclick="return ShowFileDialog('RouteMap')">
                            <i class="bi bi-paperclip"></i>
                        </button>
                        <button type="button" class="icon-inp ctr-readonly text-danger {{ !empty($upInfUser['type5']) ? (asset($upInfUser['type5'][0]['path']) ? '' : 'visually-hidden') : 'visually-hidden' }}" onclick="ClearFile('RouteMap')"><i class="bi bi-x-circle-fill"></i></button>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
    <div class="mb-4 text-center text-sm-center float-md-end">
        <input id="hidUrlU037Save" type="hidden" value="{{ route('u037.saveAppli', ['id' => $mode['id'], 'token' => $mode['token']]) }}">
        <button type="button" class="btn btn-primary px-5 {{$mode['edit'] ? '' : 'd-none'}}" id="btnNext" href="#" onclick="ChkInput()">次へ</a>
    </div>
</div>
@endsection