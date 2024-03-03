@extends('layouts.layout')

<!-- U03-1 -->
@section('title', '入職者情報入力')

@push('scripts')
<script src="{{ asset('js/users/u03_information.js?v=' . time()) }}"></script>
@endpush

@section('content')
<style>
    .legend-body {
        padding-right: 0rem !important;
        padding-bottom: 0rem !important;
    }

    .table>tbody>tr>td:first-child {
        background-color: var(--grey-medium);
    }
</style>

@php

    use App\Common\CommonConst;
    use App\Common\UploadFunc;

    $mtb2_l4 = "mt-2 mb-2 ms-4";
    $hidden_cb = 'style=display:none';
    $arrPro = ($item) ? explode(',', $item->procedure_items) : [];
    $arrUpOffi = ($item) ? explode(',', $item->sign_items) : [];
    $url = route('u03.saveInfo');
    $urlSendMail = route('u03.sendMail');
    $checkFile = $hidden_cb;
    $disabledSelect = 'select-disable ctr-readonly';
    $tabIndex = "tabindex=-1";
    $readonly = "readonly tabindex=-1";

    $disabledPhone = '';
    $tabIndexdPhone = '';
    $disabledUsersNum = '';
    $tabIndexdUsersNum = '';
    $stop = $chagMail = $reject = false;

    if (empty($item->procedure_items)) {
        $disabledPhone = $disabledSelect;
        $tabIndexdPhone = $tabIndex;
    }

    if(!isset($item->id))
    {
        $disabledUsersNum = $disabledSelect;
        $tabIndexdUsersNum = $readonly;
    }

    foreach ($prods as $prod) {
        foreach ($arrPro as $idPro) {
            if ($prod->name === CommonConst::PROD_NAME_PHONE && $idPro == $prod->id) {
                $disabledPhone = '';
                $tabIndexdPhone = '';
                $stop = true;
                break;
            } else {
                $disabledPhone = $disabledSelect;
                $tabIndexdPhone = $tabIndex;    
            }
        }
        if ($stop) {
            break;
        }
    }
    
    if(isset($item) && $item->status_user != CommonConst::U03_STATUS_SUPERIOR)
    {
        $tabInp = $readonly;
        $read = $disabledSelect;
        $tab = $tabIndex;
        $readCb = "disabled";
        $disabledPhone = $disabledSelect;
        $tabIndexdPhone = $tabIndex;
        $disabledUsersNum = $disabledSelect;
        $tabIndexdUsersNum = $readonly;
        $enableBtn = 'disabled'; 
        $hiddenBtn ='';
        if(isset($item) && $item->status_user == CommonConst::U03_STATUS_CREATOR && $item->author_id == Auth::id()) $chagMail = true;
    }else
    {
        $read = '';
        $tab = '';
        $tabInp = '';
        $readCb = '';
        $enableBtn = '';
        $hiddenBtn = $hidden_cb;
    }
    if(isset($item))
    {
        if($item->flg4 != 1){
            $checkFile = $hidden_cb;
        } else $checkFile = '';
        if($item->status_user == CommonConst::U03_STATUS_SUPERIOR) $reject = true;
    }
    
@endphp

<div class="edit-content">
    <div class="btn-scroll">
        <button type="button" class="btn btn-blue mb-2" {{$enableBtn}} onclick="Save(`{{route('u03.saveInfo')}}`)">保存</button>
        <button type="button" class="btn btn-outline-primary mb-2" onclick="BackPrev(`{{route('u02')}}`)">キャンセル</button>
        @can('isHD')
            <button type="button" id="btnHDConfirm" value="{{isset($item) && isset($item->hd_confirm_date) ? 1 : ''}}" class="btn btn-warning mb-2" onclick="Approval(`{{$urlSendMail}}`,'btnHD')" {{$hiddenBtn}}>HD確認</button>
        @endcan
        <button type="button" class="btn btn-success float-sm-end float-none mb-2"  target="_blank" 
            {{empty($item->id) ? 'disabled' : ''}} onclick="window.open('{{route('u034',$item ? $item->id : '')}}','_blank')">入職者申請状況</button>
    </div>
    <input type="hidden" name="id" id="hidId" value="{{ old('id', $item ? $item->id : '') }}">
    <input type="hidden" id="hidMode" name="mode" value="">
    <input type="hidden" id="hidStatus" name="hidStatus" value="{{ old('hidStatus', $item ? $item->status_user : '') }}">
    <input type="hidden" id="hidCheck" name="hidCheck" value="{{ (isset($item) && $item->flg4 == 1) ? 1 : '' }}">

    <div class="row mb-2 mb-xl-0 mb-md-4">
        <div class="col-xl-6">
            <div class="table-responsive">
                <table class="table table-bordered table-border-color">
                    <thead class="sticky-top top--1">
                        <tr id="" class="tr-head bg-title text-center p-1">
                            <th scope="col" class="mw-c-100">フロー</th>
                            <th scope="col" class="mw-c-150">氏名</th>
                            <th scope="col" class="mw-c-150">更新日時</th>
                            <th class="text-center w-c-50 bg-white border-0 border-bottom border-top border-white"></th>
                            <th class="text-center w-c-50 bg-white border-0 border-bottom border-top border-white"></th>
                        </tr>
                    </thead>
                    <tbody id="">
                        <tr class="align-middle text-center">
                            <td>作成者</td>
                            <td>
                                <label id="lbAuthorId">{{ $item->name_author ?? '' }}</label>
                            </td>
                            <td>
                                <label id="lbAuthorDate">
                                    {{ empty($item->author_date) ? "" : date_format(new DateTime($item->author_date), CommonConst::DATETIME_FORMAT) }}
                                </label>
                            </td>
                            <td class="w-c-50 border-0 border-bottom border-top border-white">
                                @if(Gate::any(['isHD', 'isAdmin']))
                                @if(!empty($item) && $item->status_user === CommonConst::U03_STATUS_SUPERIOR)
                                <button type="button" {{ (empty($item->let_updated_at) || (empty($item->con_updated_at))) ? "disabled" : "" }}  class="btn btn-blue" onclick="Approval(`{{$urlSendMail}}`,'author',`{{$url}}`)">送信</button>
                                @endif
                                @endif
                            </td>
                            <td class="w-c-50 border-0 border-bottom border-top border-white">
                                @if(!empty($item) &&  $item->status_user != CommonConst::STATUS_COMPLETED)
                                <button type="button" class="btn btn-danger" onclick="ConfirmDel(`{{ $item->id ?? '' }}`)">削除</button>
                                @endif
                            </td>
                        </tr>
                        <tr class="align-middle text-center">
                            <td>申請者</td>
                            <td>
                                <label id="lbApplicantId">{{ empty($item->applicant_date) ? "" : $item->first_name.'　'.$item->last_name }}</label>
                            </td>
                            <td>
                                <label id="lbApplicantDate">
                                    {{ empty($item->applicant_date) ? "" : date_format(new DateTime($item->applicant_date), CommonConst::DATETIME_FORMAT) }}
                                </label>
                            </td>
                            <td class="text-center w-c-50 border-0 border-bottom border-top border-white"></td>
                            <td class="text-center w-c-50 border-0 border-bottom border-top border-white"></td>
                        </tr>
                        <tr class="align-middle text-center">
                            <td>1次承認</td>
                            <td>
                                <label id="lbAdmin1Id">{{ $item->name_admin1 ?? ''}}</label>
                            </td>
                            <td>
                                <label id="lbAdmin1Date">
                                    {{ empty($item->admin1_date) ? "" : date_format(new DateTime($item->admin1_date), CommonConst::DATETIME_FORMAT) }}
                                </label>
                            </td>
                            <td class="w-c-50 border-0 border-bottom border-top border-white">
                                @can('isCensor', $staff1s)
                                @if($item && $item->status_user === CommonConst::U03_STATUS_APPROVAL_1)
                                <button type="button" class="btn btn-blue" onclick="Approval(`{{$urlSendMail}}`,'admin1')">承認</button>
                                @endif
                                @endcan
                            </td>
                            <td class="w-c-50 border-0 border-bottom border-top border-white">
                                @can('isCensor', $staff1s)
                                @if($item && $item->status_user === CommonConst::U03_STATUS_APPROVAL_1)
                                <button type="button" class="btn btn-danger" onclick="Approval(`{{$urlSendMail}}`,'admin1Reject')">否認</button>
                                @endif
                                @endcan
                            </td>
                        </tr>
                        <tr class="align-middle text-center">
                            <td>2次承認</td>
                            <td>
                                <label id="lbAdmin2Id">{{ $item->name_admin2 ?? '' }}</label>
                            </td>
                            <td>
                                <label id="lbAdmin2Date">
                                    {{empty($item->admin2_date) ? "" : date_format(new DateTime($item->admin2_date), CommonConst::DATETIME_FORMAT)}}
                                </label>
                            </td>
                            <td class="w-c-50 border-0 border-bottom border-top border-white">
                                @can('isCensor', $staff2s)
                                @if($item && $item->status_user == CommonConst::U03_STATUS_APPROVAL_2)
                                <button type="button" class="btn btn-blue" onclick="Approval(`{{$urlSendMail}}`,'admin2')">承認</button>
                                @endif
                                @endcan
                            </td>
                            <td class="w-c-50 border-0 border-bottom border-top border-white">
                                @can('isCensor', $staff2s)
                                @if($item && $item->status_user == CommonConst::U03_STATUS_APPROVAL_2)
                                <button type="button" class="btn btn-danger" onclick="Approval(`{{$urlSendMail}}`,'admin2Reject')">否認</button>
                                @endif
                                @endcan
                            </td>
                        </tr>
                        <tr class="align-middle text-center">
                            <td>HD承認</td>
                            <td>
                                <label id="lbHdId">{{ $item->name_hd  ?? ''}}</label>
                            </td>
                            <td>
                                <label id="lbHdDate">
                                    {{ empty($item->hd_date) ? "" : date_format(new DateTime($item->hd_date), CommonConst::DATETIME_FORMAT) }}
                                </label>
                            </td>

                            <td class="w-c-50 border-0 border-bottom border-top border-white">
                                @can('isHD')
                                @if($item && $item->status_user == CommonConst::U03_STATUS_APPROVAL_HD)
                                <button type="button" class="btn btn-blue" onclick="Approval(`{{$urlSendMail}}`,'hd')">承認</button>
                                @endif
                                @endcan
                            </td>
                            <td class="w-c-50 border-0 border-bottom border-top border-white">
                                @can('isHD')
                                @if($item && $item->status_user == CommonConst::U03_STATUS_APPROVAL_HD)
                                <button type="button" class="btn btn-danger" onclick="Approval(`{{$urlSendMail}}`,'hdReject')">否認</button>
                                @endif
                                @endcan
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-xl-1"></div>

        <div class="col-xl-4">
            <div>
                <div class="p-1 fs-25"><label>コメント</label></div>
            </div>
            <div class="">
                <textarea name="txtNote" id="txtNote" class="form-control border-radius-none align-top" maxlength="500" style="min-height: 270px;" 
                autofocus value="{{ old('txtNote', $item->note ?? '') }}">{{ old('txtNote', $item->note ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-xl-2 col-md-4">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>入社予定日</label></div>
            <input type="date" name="txtDate_join" id="txtDate_join" autocomplete="off" class="form-control border-radius-none {{$read}}" {{$tabInp}} maxlength="10" min="{{ CommonConst::DATE_MIN }}" max="{{ CommonConst::DATE_MAX }}" value="{{ !empty($item) && $item->date_join != '' ? date(CommonConst::DATE_FORMAT_2, strtotime($item->date_join)) : '' }}">
        </div>
    </div>

    <div class="row align-items-end">
        <div class="col-xl-2 col-md-4 mb-2">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>氏名（姓）</label></div>
            <input name="txtFirstName" id="txtFirstName" type="text" autocomplete="off" class="form-control border-radius-none {{$read}}" {{$tabInp}} maxlength="30" value="{{old('txtFirstName', $item->first_name  ?? '')}}">
        </div>
        <div class="col-xl-2 col-md-4 mb-2">
            <div class="bg-title text-center p-1"><span class="note">*</span><label for="txtLastName">氏名（名）</label></div>
            <input name="txtLastName" id="txtLastName" type="text" autocomplete="off" class="form-control border-radius-none {{$read}}" {{$tabInp}} maxlength="30" value="{{old('txtLastName', $item->last_name  ?? '')}}">
        </div>
        <div class="col-xl-4 col-md-8 mb-2">
            <div class="row mb-1">
                <div class="col text-center">
                    <span class="note fw-normal"><label>※ﾌﾘｶﾞﾅ入力は半角カタカナで入力してください</label></span>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-md-6 mb-md-0 mb-2">
                    <div class="bg-title text-center p-1"><span class="note">*</span><label>ﾌﾘｶﾞﾅ（姓）</label></div>
                    <input name="txtFirstFurigana" id="txtFirstFurigana" type="text" autocomplete="off" class="form-control border-radius-none {{$read}}" {{$tabInp}} maxlength="30" value="{{old('txtFirstFurigana', $item->first_furigana  ?? '')}}">
                </div>
                <div class="col-xl-6 col-md-6">
                    <div class="bg-title text-center p-1"><span class="note">*</span><label>ﾌﾘｶﾞﾅ（名）</label></div>
                    <input name="txtLastFurigana" id="txtLastFurigana" type="text" autocomplete="off" class="form-control border-radius-none {{$read}}" {{$tabInp}} maxlength="30" value="{{old('txtLastFurigana', $item->last_furigana  ?? '')}}">
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 mb-2">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>ステータス</label></div>
            <select name="cmbWorkStatus" id="cmbWorkStatus" class="form-select border-radius-none {{$read}}" {{$tab}}>
                <option></option>
                @foreach ($mstWorkStatus as $cmb)
                <option value="{{ $cmb->item_cd }}" {{ $item && $item->work_status == $cmb->item_cd ? 'selected' : '' }}>{{ $cmb->item_name }}
                </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row mb-4">
        <div class="mt-2">
            <div class="p-1 fs-25"><label>所属</label></div>
        </div>
        <div class="col-xl-3 col-md-6 mb-2">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>事業所</label></div>
            <select name="cmbOffice_cd" id="cmbOffice" class="form-select border-radius-none select-disable ctr-readonly" tabindex="-1">
                <option></option>
                @foreach ($ofces as $o)
                <option value="{{ $o->office_cd }}" {{ $ofce->office_cd == $o->office_cd ? 'selected' : '' }}>{{ $o->office_name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-xl-3 col-md-6 mb-2">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>所属</label></div>
            <select name="cmbBelong_cd" id="cmbBelong" class="form-select border-radius-none {{$read}}" {{$tab}}>
                <option></option>
                @foreach ($belongs as $belong)
                @if($belong->office_cd == $ofce->office_cd)
                <option value="{{ $belong->belong_cd }}" {{ $item && $item->belong_cd == $belong->belong_cd ? 'selected' : '' }}>
                    {{ $belong->belong_name }}
                </option>
                @endif
                @endforeach
            </select>
        </div>
        <div class="col-xl-2 col-md-4 mb-2">
            <div class="bg-title text-center p-1"><label>役職</label></div>
            <input type="text" name="txtPosition" id="txtPosition" autocomplete="off" class="form-control border-radius-none {{$read}}" {{$tabInp}} maxlength="30" 
            value="{{old('txtPosition', $item->position  ?? '')}}">
        </div>
        <div class="col-xl-2 col-md-4 mb-2">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>正社員・パート</label></div>
            <select name="cmbWork_time_flag" id="cmbWork_time_flag" class="form-select border-radius-none {{$read}}" {{$tab}}>
                <option></option>
                @foreach ($mstWorkTime as $cmb)
                <option value="{{ $cmb->item_cd }}" {{ $item && $item->work_time_flag == $cmb->item_cd ? 'selected' : '' }}>{{ $cmb->item_name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-xl-2 col-md-4 mb-2">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>社員番号</label></div>
            <input type="text" name="users_number" id="users_number" autocomplete="off" class="form-control border-radius-none allow_numeric pad-0 {{$disabledUsersNum}}" {{$tabIndexdUsersNum}} maxlength="7" value="{{ $item->users_number  ?? ''}}">
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-8 mb-xl-0 mb-md-2">
            <fieldset class="legend-box">
                <legend class="legend-title">入社前手続き</legend>
                <div class="legend-body">
                    <div class="row ms-1" id="ListProItem">
                        @foreach ($prods as $prod)
                        @if ($ofce->office_cd == $prod->office_cd && $prod->selected == '1')
                        <div class="form-check col-4 col-md-3 col-xl-2 mb-2">
                            <input {{$readCb}} class="form-check-input" type="checkbox" onchange="OnCheckPro(this)" 
                            @foreach ($arrPro as $idPro) 
                            {{$idPro == $prod->id  ? 'checked' : '' }} 
                            @endforeach  value="1" id="prod_{{ $loop->iteration }}" name="prod_{{$prod->id}}">
                            <label class="form-check-label" id="lbprod_{{ $prod->id }}" for="prod_{{ $loop->iteration }}">{{ $prod->name }}</label>
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
            <input type="text" id="txtCompany_mobile_number" name="txtCompany_mobile_number" autocomplete="off" class="form-control border-radius-none allow_numeric {{$disabledPhone}}" 
                {{$tabIndexdPhone}} maxlength="20" value="{{old('txtCompany_mobile_number', $item->company_mobile_number  ?? '')}}">
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-4 mb-2">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>履歴書</label></div>
            <div class="position-relative ">
                <a target="_blank" id="linkResume" href="{{ !empty($upInfUser['type1']) ? asset($upInfUser['type1'][0]['path']) : '' }}" class="{{ !empty($upInfUser['type1']) ? '' : 'disabled' }}">
                    <input id="txtResume" name="txtResume" class="form-control border-radius-none ctr-readonly {{!empty($upInfUser['type1']) ? 'cursor-pointer' : ''}}" 
                    readonly value="{{ !empty($upInfUser['type1']) ? $upInfUser['type1'][0]['file_name'] : '' }}" {{$tabInp}} />
                </a>
                <div id="iconResume" class="{{$read}}">
                    <button type="button" class="icon-inp ctr-readonly {{!empty($upInfUser['type1'][0]['file_name']) ? 'icon-active' : ''}}" {{$tabInp}} onclick="ShowFileDialog('Resume')">
                        <i class="bi bi-paperclip"></i>
                    </button>
                    <button type="button" class="icon-inp ctr-readonly text-danger {{!empty($upInfUser['type1'][0]['file_name']) ? '' : 'visually-hidden'}}" {{$tabInp}} 
                    onclick="ClearFile('Resume')">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </div>
            </div>
            <input id="curResume" name="curResume" type="hidden" value="{{ !empty($upInfUser['type1']) ? 1 : 0 }}">
            <input id="fileResume" name="fileResume" type="file" class="visually-hidden" onchange="ChangeFile('Resume', 2, 0, 1)" />
            <input id="tmpResume" name="tmpResume" type="hidden" />
            <input id="oldResume" name="oldResume" class="stt-row" type="hidden" value="" />
            <input type="hidden" id="hidResumeId" name="hidResumeId" value="{{ !empty($upInfUser['type1']) ? $upInfUser['type1'][0]['id'] : '' }}">
        </div>
    </div>

    <div class="row col-xl-6 col-md-8 mb-4">
        <div><label>入職者情報　取扱項目全てにチェックしてください。</label></div>
        <div class="ms-4 mt-2" id="">
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input {{$readCb}} name="manager" id="manager" type="checkbox" class="form-check-input" onchange="OnCheckManager(this)" value="{{old('manager', $item->manager  ?? '')}}" {{ $item ? ($item->manager == '1' ? 'checked' : '') : '' }}>
                    <label class="form-check-label" for="manager">役員</label>
                </div>
            </div>
        </div>

        <div class="{{ $mtb2_l4 }}">
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input {{$readCb}} name="manage_position" id="manage_position" class="form-check-input" type="checkbox" onchange="OnCheckManagerPosition(this)" 
                    value="{{old('manage_position', $item->manage_position  ?? '')}}" {{ $item && $item->manager == '1' ? "disabled" : '' }} {{ $item ? ($item->manage_position == '1' ? 'checked' : '') : '' }}>
                    <label class="form-check-label" for="manage_position">管理職</label>
                </div>
            </div>
        </div>

        <div id="rowFlg1" class="{{ $mtb2_l4 }}" {{$ofce->flg1 != '1' ? $hidden_cb : '' }}>
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input {{$readCb}} name="flg1" id="flg1" class="form-check-input" type="checkbox" onchange="Oncheck(this)" value="{{old('flg1', $item->flg1  ?? '')}}" {{ $item ? ($item->flg1 == '1' ? 'checked' : '') : '' }}>
                    <label class="form-check-label" for="flg1">労務担当</label>
                </div>
            </div>
        </div>

        <div class="{{ $mtb2_l4 }}">
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input {{$readCb}} name="manage_business" id="manage_business" class="form-check-input" type="checkbox" onchange="Oncheck(this)" value="{{old('manage_business', $item->manage_business  ?? '')}}" {{ $item ? ($item->manage_business == '1' ? 'checked' : '') : '' }}>
                    <label class="form-check-label" for="manage_business">業務担当</label>
                </div>
            </div>
        </div>

        <div id="rowAccountant" class="{{ $mtb2_l4 }}" {{$ofce->flg1 != '1' ? $hidden_cb : '' }}>
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input {{$readCb}} name="accountant" id="accountant" class="form-check-input" type="checkbox" onchange="Oncheck(this)" value="{{old('accountant', $item->accountant  ?? '')}}" {{ $item ? ($item->accountant == '1' ? 'checked' : '') : '' }}>
                    <label class="form-check-label" for="accountant">経理担当</label>
                </div>
            </div>
        </div>

        <div class="{{ $mtb2_l4 }}">
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input {{$readCb}} name="company_car" id="company_car" class="form-check-input" type="checkbox" onchange="OncheckCompanyCar(this)" value="{{old('company_car', $item->company_car  ?? '')}}" {{ $item && $item->flg3 == '1' ? "disabled" : '' }} {{ $item ? ($item->company_car == '1' ? 'checked' : '') : '' }}>
                    <label class="form-check-label" for="company_car">社用車通勤</label>
                </div>
            </div>
        </div>

        <div id="rowFlg3" class="{{ $mtb2_l4 }}" {{$ofce->flg3 != '1' ? $hidden_cb : '' }}>
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input {{$readCb}} name="flg3" id="flg3" class="form-check-input" type="checkbox" onchange="OncheckFlg3(this)" value="{{old('flg3', $item->flg3  ?? '')}}" 
                    {{-- {{ $item && $item->company_car == '1' ? "disabled" : '' }} --}} 
                    {{ $item ? ($item->flg3 == '1' ? 'checked' : '') : '' }}>
                    <label class="form-check-label" for="flg3">自家用車通勤</label>
                </div>
            </div>
        </div>

        <div id="rowFlg4" class="{{ $mtb2_l4 }}" {{$ofce->flg4 != '1' ? $hidden_cb : '' }}>
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input {{$readCb}} name="flg4" id="flg4" class="form-check-input" type="checkbox" onchange="OnCheckForeign(this)" value="{{old('flg4', $item->flg4  ?? '')}}" {{ $item ? ($item->flg4 == '1' ? 'checked' : '') : '' }}>
                    <label class="form-check-label" for="flg4">外国人雇用</label>
                </div>
            </div>
        </div>

        <div id="rowFlg5" class="{{ $mtb2_l4 }}" {{$ofce->flg5 != '1' ? $hidden_cb : '' }}>
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input {{$readCb}} name="flg5" id="flg5" class="form-check-input" type="checkbox" onchange="Oncheck(this)" value="{{old('flg5', $item->flg5  ?? '')}}" {{ $item ? ($item->flg5 == '1' ? 'checked' : '') : '' }}>
                    <label class="form-check-label" for="flg5">家族（扶養）手当</label>
                </div>
            </div>
        </div>

        <div id="rowFlg6" class="{{ $mtb2_l4 }}" {{$ofce->flg6 != '1' ? $hidden_cb : ''}}>
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input {{$readCb}} name="flg6" id="flg6" class="form-check-input" type="checkbox" onchange="checkFlg6(this)" value="{{old('flg6', $item->flg6  ?? '')}}" 
                    {{ $item && $item->manager == '1' ? "disabled" : '' }} {{ $item ? ($item->flg6 == '1' && $item->manager != '1' ? 'checked' : '') : '' }}>
                    <label class="form-check-label" for="flg6">住宅手当</label>
                </div>
            </div>
        </div>

        <div id="rowFlg7" class="{{ $mtb2_l4 }}" {{$ofce->flg7 != '1' ? $hidden_cb : '' }}>
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input {{$readCb}} name="flg7" id="flg7" class="form-check-input" type="checkbox" onchange="Oncheck(this)" value="{{old('flg7', $item->flg7  ?? '')}}" {{ $item ? ($item->flg7 == '1' ? 'checked' : '') : '' }}>
                    <label class="form-check-label" for="flg7">保育料助成</label>
                </div>
            </div>
        </div>

        <div id="rowFlg8" class="{{ $mtb2_l4 }}" {{$ofce->flg8 != '1' ? $hidden_cb : '' }}>
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input {{$readCb}} name="flg8" id="flg8" class="form-check-input" type="checkbox" onchange="Oncheck(this)" value="{{old('flg8', $item->flg8  ?? '')}}" {{ $item ? ($item->flg8 == '1' ? 'checked' : '') : '' }}>
                    <label class="form-check-label" for="flg8">障害者（本人）</label>
                </div>
            </div>
        </div>

        <div id="rowFlg9" class="{{ $mtb2_l4 }}" {{$ofce->flg9 != '1' ? $hidden_cb : '' }}>
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input {{$readCb}} name="flg9" id="flg9" class="form-check-input" type="checkbox" onchange="Oncheck(this)" value="{{old('flg9', $item->flg9  ?? '')}}" {{ $item ? ($item->flg9 == '1' ? 'checked' : '') : '' }}>
                    <label class="form-check-label" for="flg9">障害者（扶養家族）</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-8 mb-4">
    <fieldset class="legend-box">
        <legend class="legend-title">サイン書類</legend>
        <div class="legend-body" id="ListUpOffice">
            @foreach ($upOfices as $upOfice)
            @if ($upOfice->selected == '1')
            <div class="form-check mb-2">
                <input {{$readCb}} class="form-check-input" type="checkbox" onchange="Oncheck(this)" 
                @foreach ($arrUpOffi as $idUpOffi) 
                {{$idUpOffi == $upOfice->id  ? 'checked' : '' }} 
                @endforeach value="1" id="up_offi_{{$loop->iteration}}" name="up_offi_{{$upOfice->id}}">
                <input type="hidden" value="{{$upOfice->id }}" id="hidup_offi_{{ $loop->iteration }}" name="up_offi[]">
                <label class="form-check-label">{{ UploadFunc::getFileNameNoExt($upOfice->file_name)}}</label>
            </div>
            @endif
            @endforeach
        </div>
    </fieldset>
</div>

<div class="row mt-4">
    <div class="col-xl-3 col-md-4 mb-2">
        <div class="bg-title text-center p-1"><span class="note">*</span><label>メールアドレス</label></div>
        <input type="email" name="email" id="email" autocomplete="off" class="form-control border-radius-none {{($reject|| empty($item) || $chagMail) ? '' : 'select-disable ctr-readonly'}}" 
        {{($reject|| empty($item) || $chagMail) ? '' : "readonly tabindex=-1"}} maxlength="100" value="{{old('email', $item->email  ?? '')}}">
    </div>
    <div class="col-xl-3 col-md-4 mb-2">
        <div class="bg-title text-center p-1"><span class="note">*</span><label>メールアドレス　<span style="font-size:.75rem; font-weight: bold">※</span>再確認</label></div>
        <input type="email" name="confirm_email" id="confirm_email" autocomplete="off" class="form-control border-radius-none {{($reject|| empty($item) || $chagMail) ? '' : 'select-disable ctr-readonly'}}" 
        {{($reject || empty($item) || $chagMail) ? '' : "readonly tabindex=-1"}} maxlength="100" value="{{old('confirm_email', $item->confirm_email  ?? '')}}">
    </div>
    <div class="col-xl-2 col-md-4 mb-2 {{$chagMail ? '' : 'd-none'}}">
        <button type="button" id="btnReSend" class="btn btn-blue" onclick="ResendEmail(`{{$urlSendMail}}`,'author')" >再送</button>
    </div>
</div>

<div class="row mb-2">
    <div class="mt-2">
        <div class="p-1 fs-25"><label>内定通知書</label></div>
    </div>
    <div class="col mb-2 mt-2">
        <!-- u032 -->
        <button type="button"class="btn btn-blue ms-4" onclick="UpdateLeter(`{{route('u03.saveInfo')}}`,'leter1',`{{route('u032.showInvi', $item ? $item->id : '')}}`)">詳細</button>
        <label class="ms-2" id="letterUpdate1">
            更新日時: {{ empty($item->let_updated_at) ? "" : date_format(new DateTime($item->let_updated_at), CommonConst::DATETIME_FORMAT) }}
        </label>
    </div>
</div>

<div class="row mb-2">
    <div class="mt-2">
        <div class="p-1 fs-25"><label>雇用契約書</label></div>
    </div>
    <div class="col mb-2 mt-2">
         <!-- u033 -->
        <button type="button" class="btn btn-blue ms-4" onclick="UpdateLeter(`{{route('u03.saveInfo')}}`,'leter2',`{{route('u033.contract', $item ? strtr(base64_encode($item->id), '/', '-') : '')}}`)">詳細</button>
        <label class="ms-2" id="letterUpdate2">
            更新日時: {{ empty($item->con_updated_at) ? "" : date_format(new DateTime($item->con_updated_at), CommonConst::DATETIME_FORMAT) }}
        </label>
    </div>
</div>

<div class="row mb-2">
    <div class="mt-2">
            <div class="p-1 fs-25"><label>必要書類の添付</label></div>
        </div>
    <div class="col-xl-3 col-md-6 mb-2">
        <div class="bg-title text-center p-1">
            <span id="req1" {{$checkFile}} class="note">*</span>
            <label>（外国人雇用）雇用条件書</label>
        </div>
        <div class="position-relative">
            <a target="_blank" id="linkConditions" href="{{ !empty($upInfUser['type2']) ? asset($upInfUser['type2'][0]['path']) : '' }}" class="{{ !empty($upInfUser['type2']) ? '' : 'disabled' }}">
                <input id="txtConditions" name="txtConditions" {{$tabInp}} class="form-control border-radius-none ctr-readonly {{!empty($upInfUser['type2']) ? 'cursor-pointer' : ''}}" readonly value="{{ !empty($upInfUser['type2']) ? $upInfUser['type2'][0]['file_name'] : '' }}" />
            </a>
            <div id="iconConditions" class="{{$read}}">
                <button type="button" {{$tabInp}} class="icon-inp ctr-readonly {{!empty($upInfUser['type2'][0]['file_name']) ? 'icon-active' : ''}}" onclick="ShowFileDialog('Conditions')">
                    <i class="bi bi-paperclip"></i>
                </button>
                <button type="button" {{$tabInp}} class="icon-inp ctr-readonly text-danger {{!empty($upInfUser['type2'][0]['file_name']) ? '' : 'visually-hidden'}}" 
                onclick="ClearFile('Conditions')">
                    <i class="bi bi-x-circle-fill"></i>
                </button>
            </div>
        </div>
        <input id="curConditions" name="curConditions" type="hidden" value="{{ !empty($upInfUser['type2']) ? 1 : 0 }}">
        <input id="fileConditions" name="fileConditions" type="file" class="visually-hidden" onchange="ChangeFile('Conditions', 2, 0, 2)" />
        <input id="tmpConditions" name="tmpConditions" type="hidden" />
        <input id="oldConditions" name="oldConditions" class="stt-row" type="hidden" value="" />
        <input type="hidden" id="hidConditionsId" name="hidConditionsId" value="{{ !empty($upInfUser['type2']) ? $upInfUser['type2'][0]['id'] : '' }}">
    </div>
</div>

<div class="row mb-2">
    <div class="col-xl-3 col-md-6 mb-2">
        <div class="bg-title text-center p-1">
            <span id="req2" {{$checkFile}} class="note">*</span>
            <label>（外国人雇用）徴収費用の説明書</label>
        </div>
        <div class="position-relative">
            <a target="_blank" id="linkCollectionFees" href="{{ !empty($upInfUser['type3']) ? asset($upInfUser['type3'][0]['path']) : '' }}" class="{{ !empty($upInfUser['type3']) ? '' : 'disabled' }}">
                <input id="txtCollectionFees" name="txtCollectionFees" {{$tabInp}} class="form-control border-radius-none ctr-readonly {{!empty($upInfUser['type3']) ? 'cursor-pointer' : ''}}" readonly value="{{ !empty($upInfUser['type3']) ? $upInfUser['type3'][0]['file_name'] : '' }}" />
            </a>
            <div id="iconCollectionFees" class="{{$read}}">
                <button type="button" {{$tabInp}} class="icon-inp ctr-readonly {{!empty($upInfUser['type3'][0]['file_name']) ? 'icon-active' : ''}}" onclick="ShowFileDialog('CollectionFees')">
                    <i class="bi bi-paperclip"></i>
                </button>
                <button type="button" {{$tabInp}} class="icon-inp ctr-readonly text-danger {{!empty($upInfUser['type3'][0]['file_name']) ? '' : 'visually-hidden'}}" onclick="ClearFile('CollectionFees')">
                    <i class="bi bi-x-circle-fill"></i>
                </button>
            </div>
        </div>
        <input id="curCollectionFees" name="curCollectionFees" type="hidden" value="{{ !empty($upInfUser['type3']) ? 1 : 0 }}">
        <input id="fileCollectionFees" name="fileCollectionFees" type="file" class="visually-hidden" onchange="ChangeFile('CollectionFees', 2, 0, 3)" />
        <input id="tmpCollectionFees" name="tmpCollectionFees" type="hidden" />
        <input id="oldCollectionFees" name="oldCollectionFees" class="stt-row" type="hidden" value="" />
        <input type="hidden" id="hidCollectionFeesId" name="hidCollectionFeesId" value="{{ !empty($upInfUser['type3']) ? $upInfUser['type3'][0]['id'] : '' }}">
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-2">
        <div class="bg-title text-center p-1">
            <span id="req3" {{$checkFile}} class="note">*</span>
            <label>（外国人雇用）雇用契約書</label>
        </div>
        <div class="position-relative">
            <a target="_blank" id="linkContract" href="{{!empty($upInfUser['type4']) ? asset($upInfUser['type4'][0]['path']) : '' }}" class="{{ !empty($upInfUser['type4']) ? '' : 'disabled' }}">
                <input id="txtContract" name="txtContract" {{$tabInp}} class="form-control border-radius-none ctr-readonly {{!empty($upInfUser['type4']) ? 'cursor-pointer' : ''}}" readonly value="{{ !empty($upInfUser['type4']) ? $upInfUser['type4'][0]['file_name'] : '' }}" />
            </a>
            <div id="iconContract" class="{{$read}}">
                <button type="button" {{$tabInp}} class="icon-inp ctr-readonly {{!empty($upInfUser['type4'][0]['file_name']) ? 'icon-active' : ''}}" onclick="ShowFileDialog('Contract')">
                    <i class="bi bi-paperclip"></i>
                </button>
                <button type="button" {{$tabInp}} class="icon-inp ctr-readonly text-danger {{!empty($upInfUser['type4'][0]['file_name']) ? '' : 'visually-hidden'}}" 
                onclick="ClearFile('Contract')">
                    <i class="bi bi-x-circle-fill"></i>
                </button>
            </div>
        </div>
        <input id="curContract" name="curContract" type="hidden" value="{{ !empty($upInfUser['type4']) ? 1 : 0 }}">
        <input id="fileContract" name="fileContract" type="file" class="visually-hidden" onchange="ChangeFile('Contract', 2, 0, 4)" />
        <input id="tmpContract" name="tmpContract" type="hidden" />
        <input id="oldContract" name="oldContract" class="stt-row" type="hidden" value="" />
        <input type="hidden" id="hidContractId" name="hidContractId" value="{{ !empty($upInfUser['type4']) ? $upInfUser['type4'][0]['id'] : '' }}">
    </div>
</div>
@endsection