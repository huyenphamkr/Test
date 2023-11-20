@extends('layouts.layout')


<!-- U03-1 -->
@section('title', '雇用契約プリセット')


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


<?php
$mtb2_l4 = "mt-2 mb-2 ms-4";
$hidden_cb = 'style=display:none';
$arrPro = ($item) ? explode(',', $item->procedure_items) : [];
$arrUpOffi = ($item) ? explode(',', $item->sign_items) : [];
?>


<div class="edit-content">
    <div class="mb-2">
        <button type="button" class="btn btn-blue" tabindex="-1" onclick="Save(`{{route('u03.saveInfo')}}`)">保存</button>
        <button type="button" class="btn btn-outline-primary" tabindex="-1" onclick="BackPrev(`{{route('u03')}}`)">キャンセル</button>
        <button type="button" class="btn btn-warning" tabindex="-1" onclick="BackPrev(`{{route('u02')}}`)">HD確認</button>
        <button type="button" class="btn btn-success float-end" tabindex="-1" onclick="">入職者申請状況</button>
    </div>
    <input type="hidden" name="id" id="hidId" value="{{ $item->id ?? '' }}">
    <input type="hidden" id="hidBelong" value="{{ $belongs }}">
    <input type="hidden" id="hidProItem" value="{{ $prods }}">
    <input type="hidden" id="hidOffice" value="{{ $ofces }}">
    <input type="hidden" id="hidUpOffice" value="{{ $upOfices }}">


    <div class="row mb-2 mb-xl-0 mb-md-4">
        <div class="col-xl-6">
            <div class="table-responsive">
                <table class="table table-bordered table-border-color">
                    <thead class="sticky-top top--1">
                        <tr id="" class="tr-head bg-title text-center p-1">
                            <th scope="col">フロー</th>
                            <th scope="col">氏名</th>
                            <th scope="col">更新日時</th>
                            <th class="text-center w-c-50 bg-white border-0 border-bottom border-top border-white"></th>
                            <th class="text-center w-c-50 bg-white border-0 border-bottom border-top border-white"></th>
                        </tr>
                    </thead>
                    <tbody id="">
                        <tr>
                            <td class="text-center">作成者</td>
                            <td>
                                <label id="lbAuthorId">{{ $item->name_author ?? '' }}</label>
                                <input type="hidden" name="author_id" value="{{old('author_id', $item->author_id ?? '')}}">
                            </td>
                            <td>
                                <label id="lbAuthorDate">
                                    {{ empty($item->author_date) ? '' : date_format(new DateTime($item->author_date), CommonConst::DATE_FORMAT_1) }}
                                </label>
                                <input type="hidden" name="author_date" value="{{old('author_date', $item->author_date ?? '')}}">
                            <td class="text-center w-c-50 border-0 border-bottom border-top border-white">
                                <button type="button" class="btn btn-blue">送信</button>
                            </td>
                            <td class="text-center w-c-50 border-0 border-bottom border-top border-white">
                                <button type="button" class="btn btn-danger">削除</button>
                            </td>
                        </tr>


                        <tr>
                            <td class="text-center">申請者</td>
                            <td>
                                <label id="lbApplicantId">{{ $item->name_applicant ?? '' }}</label>
                                <input type="hidden" name="applicant_id" value="{{old('applicant_id', $item->applicant_id ?? '')}}">
                            </td>
                            <td>
                                <label id="lbApplicantDate">
                                    {{ empty($item->applicant_date) ? '' : date_format(new DateTime($item->applicant_date), CommonConst::DATE_FORMAT_1) }}
                                </label>
                                <input type="hidden" name="applicant_date" value="{{old('applicant_date', $item->applicant_date ?? '')}}">
                            </td>
                            <td class="text-center w-c-50 border-0 border-bottom border-top border-white"></td>
                            <td class="text-center w-c-50 border-0 border-bottom border-top border-white"></td>
                        </tr>


                        <tr>
                            <td class="text-center">1次承認</td>
                            <td>
                                <label id="lbAdmin2Id">{{ $item->name_admin1 ?? ''}}</label>
                                <input type="hidden" name="admin1_id" value="{{old('admin1_id', $item->admin1_id ?? '')}}">
                            </td>
                            <td>
                                <label id="lbAdmin1Date">
                                    {{ empty($item->admin1_date) ? '' : date_format(new DateTime($item->admin1_date), CommonConst::DATE_FORMAT_1) }}
                                </label>
                                <input type="hidden" name="admin1_date" value="{{old('admin1_date', $item->admin1_date ?? '')}}">
                            </td>
                            <td class="text-center w-c-50 border-0 border-bottom border-top border-white">
                                <button type="button" class="btn btn-blue">承認</button>
                            </td>
                            <td class="text-center w-c-50 border-0 border-bottom border-top border-white">
                                <button type="button" class="btn btn-danger">否認</button>
                            </td>
                        </tr>


                        <tr>
                            <td class="text-center">2次承認</td>
                            <td>
                                <label id="lbAdmin2Id">{{ $item->name_admin2 ?? '' }}</label>
                                <input type="hidden" name="admin2_id" value="{{old('admin2_id', $item->admin2_id ?? '')}}">
                            </td>
                            <td>
                                <label id="lbAdmin2Date">
                                    {{ empty($item->admin2_date) ? '' : date_format(new DateTime($item->admin2_date), CommonConst::DATE_FORMAT_1) }}
                                </label>
                                <input type="hidden" name="admin2_date" value="{{old('admin2_date', $item->admin2_date ?? '')}}">
                            </td>
                            <td class="text-center w-c-50 border-0 border-bottom border-top border-white">
                                <button type="button" class="btn btn-blue">承認</button>
                            </td>
                            <td class="text-center w-c-50 border-0 border-bottom border-top border-white">
                                <button type="button" class="btn btn-danger">否認</button>
                            </td>
                        </tr>


                        <tr>
                            <td class="text-center">HD承認</td>
                            <td>
                                <label id="lbHdId">{{ $item->name_hd  ?? ''}}</label>
                                <input type="hidden" name="hd_id" value="{{old('hd_id', $item->hd_id ?? '')}}">
                            </td>
                            <td>
                                <label id="lbHdDate">
                                    {{ empty($item->hd_date) ? '' : date_format(new DateTime($item->hd_date), CommonConst::DATE_FORMAT_1) }}
                                </label>
                                <input type="hidden" name="hd_date" value="{{old('hd_date', $item->hd_date ?? '')}}">
                            </td>
                            <td class="text-center w-c-50 border-0 border-bottom border-top border-white">
                                <button type="button" class="btn btn-blue">承認</button>
                            </td>
                            <td class="text-center w-c-50 border-0 border-bottom border-top border-white">
                                <button type="button" class="btn btn-danger">否認</button>
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
                <textarea name="txtNote" class="form-control border-radius-none align-top" maxlength="500" style="min-height: 270px;" autofocus value="{{ old('txtNote', $item->note ?? '') }}">{{ old('txtNote', $item->note ?? '') }}</textarea>
            </div>
        </div>
    </div>


    <div class="row mb-2">
        <div class="col-xl-2 col-md-4">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>入社予定日</label></div>
            <input type="date" name="txtDate_join" autocomplete="off" class="form-control border-radius-none" maxlength="10"
            value="{{ !empty($item) && $item->date_join != '' ? date(CommonConst::DATE_FORMAT_2, strtotime($item->date_join)) : '' }}">
        </div>
    </div>


    <div class="row">
        <div class="col-xl-2 col-md-4 mb-2">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>氏名</label></div>
            <input name="txtName" type="text" autocomplete="off" class="form-control border-radius-none" maxlength="30" value="{{old('txtName', $item->name  ?? '')}}">
        </div>
        <div class="col-xl-2 col-md-4 mb-2">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>事業所名</label></div>
            <input name="txtFurigana" type="text" autocomplete="off" class="form-control border-radius-none" maxlength="30" value="{{old('txtFurigana', $item->furigana  ?? '')}}">
        </div>
    </div>


    <div class="row mb-4">
        <div class="mt-2">
            <div class="p-1 fs-25"><label>所属</label></div>
        </div>
        <div class="col-xl-3 col-md-6 mb-2">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>事業所</label></div>
            <select name="cmbOffice_cd" id="cmbOffice" class="form-select border-radius-none" onchange="changeCmbOffice()">
                <option></option>
                @foreach ($ofces as $ofce)
                <option value="{{ $ofce->office_cd }}" {{ $item && $item->office_cd == $ofce->office_cd ? 'selected' : '' }}>{{ $ofce->office_name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-xl-3 col-md-6 mb-2">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>所属</label></div>
            <select name="cmbBelong_cd" id="cmbBelong" class="form-select border-radius-none"> {{-- select-disable --}}
                <option></option>
                @isset($item->belong_cd)
                @foreach ($belongs as $belong)
                <option value="{{ $belong->belong_cd }}" {{ $item && $item->belong_cd == $belong->belong_cd ? 'selected' : '' }}>
                    {{ $belong->belong_name }}
                </option>
                @endforeach
                @endisset
            </select>
        </div>
        <div class="col-xl-2 col-md-4 mb-2">
            <div class="bg-title text-center p-1"><label>役職</label></div>
            <input type="text" name="txtPosition" autocomplete="off" class="form-control border-radius-none" maxlength="30" value="{{old('txtPosition', $item->position  ?? '')}}">
        </div>
        <div class="col-xl-2 col-md-4 mb-2">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>正社員・パート</label></div>
            <select name="cmbWork_time_flag" class="form-select border-radius-none">
                <option></option>
                @foreach ($mstWorkTime as $cmb)
                <option value="{{ $cmb->item_cd }}" {{ $item && $item->work_time_flag == $cmb->item_cd ? 'selected' : '' }}>{{ $cmb->item_name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-xl-2 col-md-4 mb-2">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>社員番号</label></div>
            <input type="text" name="users_number" autocomplete="off" class="form-control border-radius-none allow_numeric pad-0" maxlength="7" value="{{ $item->users_number  ?? ''}}">
        </div>
    </div>


    <div class="row mb-4">
        <div class="col-xl-8 mb-xl-0 mb-md-2">
            <fieldset class="legend-box">
                <legend class="legend-title">入社前手続き</legend>
                <div class="legend-body">
                    <div class="row ms-1" id="ListProItem">
                        @foreach ($prods as $prod)
                        @if ($item && $item->office_cd == $prod->office_cd && $prod->selected == '1')
                        <div class="form-check col-4 col-md-3 col-xl-2 mb-2">
                            <input class="form-check-input" type="checkbox" onchange="Oncheck(this)"
                                @foreach ($arrPro as $idPro)
                                    {{$idPro == $prod->id  ? 'checked' : '' }} 
                                @endforeach
                                    value="1" id="prod_{{ $loop->iteration }}" name="prod_{{$prod->id}}">
                            <input type="hidden" value="{{ $prod->id }}" id="hidprod_{{ $loop->iteration }}" name="prod[]">
                            <label class="form-check-label" for="prod_{{ $loop->iteration }}">{{ $prod->name }}</label>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="col-xl-2 col-md-4 mb-0 mt-auto">
            <div class="bg-title text-center p-1"><label>会社携帯番号</label></div>
            <input type="text" name="txtCompany_mobile_number" autocomplete="off" class="form-control border-radius-none allow_numeric" maxlength="20" value="{{ $item->company_mobile_number  ?? ''}}">
        </div>
    </div>


    <div class="row mb-4">
        <div class="col-xl-3 col-md-4 mb-2">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>履歴書</label></div>
            <fieldset class="legend-box" style="padding-right:0; padding: bottom 0;">
                <div class="legend-body">
                    <div class="d-flex">
                        <a target="_blank" id="linkBanner" href="">
                            <img id="imgBanner" name="imgBanner" class="img-fluid" style="height: 97%!important;" src="">
                        </a>
                        <input type="hidden" id="curBanner" name="curBanner" value="">
                        <input type="hidden" id="hidBannerId" name="hidBannerId" value="">
                        <input id="fileBanner" name="fileBanner" type="file" class="visually-hidden" />
                        <button class="btn-none h7 mt-auto ms-auto" type="button" onclick="">
                            <i class="bi bi-paperclip"></i>
                        </button>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>


    <div class="row ol-xl-4 col-md-4 mb-4">
        <div><label>入職者情報　取扱項目全てにチェックしてください。</label></div>
        <div class="ms-4 mt-2" id="">
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input name="manager" id="manager" type="checkbox" class="form-check-input" onchange="OnCheckManager(this)"
                        value="{{old('manager', $item->manager  ?? '')}}" {{ $item ? "disabled" : '' }}
                        {{ $item ? ($item->manager == '1' ? 'checked' : '') : '' }}>
                    <label class="form-check-label" for="manager">役員</label>
                </div>
            </div>
        </div>


        <div class="{{ $mtb2_l4 }}">
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input name="manage_position" id="manage_position" class="form-check-input" type="checkbox" 
                    value="{{old('manage_position', $item->manage_position  ?? '')}}" {{ $item ? ($item->manage_position == '1' ? 'checked' : '') : '' }}>
                    <label class="form-check-label" for="manage_position">管理職</label>
                </div>
            </div>
        </div>


        <div id="rowFlg1" class="{{ $mtb2_l4 }}" {{ $item && $ofce->flg1 != '1' ? $hidden_cb : '' }}>
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input name="flg1" id="flg1" class="form-check-input" type="checkbox" value="{{old('flg1', $item->flg1  ?? '')}}" {{ $item ? ($item->flg1 == '1' ? 'checked' : '') : '' }}>
                    <label class="form-check-label" for="flg1">労務担当</label>
                </div>
            </div>
        </div>


        <div class="{{ $mtb2_l4 }}">
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input name="manage_business" id="manage_business" class="form-check-input" type="checkbox"
                    value="{{old('manage_business', $item->manage_business  ?? '')}}" {{ $item ? ($item->manage_business == '1' ? 'checked' : '') : '' }}>
                    <label class="form-check-label" for="manage_business">業務</label>
                </div>
            </div>
        </div>


        <div id="rowAccountant" class="{{ $mtb2_l4 }}" {{ $item && $ofce->flg1 != '1' ? $hidden_cb : '' }}>
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input name="accountant" id="accountant" class="form-check-input" type="checkbox" value="{{old('accountant', $item->accountant  ?? '')}}" {{ $item ? ($item->accountant == '1' ? 'checked' : '') : '' }}>
                    <label class="form-check-label" for="accountant">経理担当</label>
                </div>
            </div>
        </div>


        <div class="{{ $mtb2_l4 }}">
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input name="company_car" id="company_car" class="form-check-input" type="checkbox"
                        value="{{old('company_car', $item->company_car  ?? '')}}"
                    {{ $item ? "disabled" : '' }} {{ $item ? ($item->company_car == '1' ? 'checked' : '') : '' }}>
                    <label class="form-check-label" for="company_car">社用車通勤</label>
                </div>
            </div>
        </div>


        <div id="rowFlg3" class="{{ $mtb2_l4 }}" {{ $item && $ofce->flg3 != '1' ? $hidden_cb : '' }}>
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input name="flg3" id="flg3" class="form-check-input" type="checkbox" value="{{old('flg3', $item->flg3  ?? '')}}" {{ $item ? ($item->flg3 == '1' ? 'checked' : '') : '' }}>
                    <label class="form-check-label" for="flg3">自家用車通勤</label>
                </div>
            </div>
        </div>


        <div id="rowFlg4" class="{{ $mtb2_l4 }}" {{ $item && $ofce->flg4 != '1' ? $hidden_cb : '' }}>
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input name="flg4" id="flg4" class="form-check-input" type="checkbox" value="{{old('flg4', $item->flg4  ?? '')}}" {{ $item ? ($item->flg4 == '1' ? 'checked' : '') : '' }}>
                    <label class="form-check-label" for="flg4">外国人雇用</label>
                </div>
            </div>
        </div>


        <div id="rowFlg5" class="{{ $mtb2_l4 }}" {{ $item && $ofce->flg5 != '1' ? $hidden_cb : '' }}>
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input name="flg5" id="flg5" class="form-check-input" type="checkbox" value="{{old('flg5', $item->flg5  ?? '')}}" {{ $item ? ($item->flg5 == '1' ? 'checked' : '') : '' }}>
                    <label class="form-check-label" for="flg5">家族（扶養）手当</label>
                </div>
            </div>
        </div>


        <div id="rowFlg6" class="{{ $mtb2_l4 }}" {{ $item ? ($item->flg6 != '1' ? $hidden_cb : '') : '' }}>
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input name="flg6" id="flg6" class="form-check-input" type="checkbox" onchange="checkFlg6(this)" value="{{old('flg6', $item->flg6  ?? '')}}" {{ $item ? ($item->flg6 == '1' && $item->manager != '1' ? 'checked' : '') : '' }} value="{{$item->flg6 ?? '0'}}">
                    <label class="form-check-label" for="flg6">住宅手当</label>
                </div>
            </div>
        </div>


        <div id="rowFlg7" class="{{ $mtb2_l4 }}" {{ $item && $ofce->flg7 != '1' ? $hidden_cb : '' }}>
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input name="flg7" id="flg7" class="form-check-input" type="checkbox" value="{{old('flg7', $item->flg7  ?? '')}}" {{ $item ? ($item->flg7 == '1' ? 'checked' : '') : '' }}>
                    <label class="form-check-label" for="flg7">保育料助成</label>
                </div>
            </div>
        </div>


        <div id="rowFlg8" class="{{ $mtb2_l4 }}" {{ $item && $ofce->flg8 != '1' ? $hidden_cb : '' }}>
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input name="flg8" id="flg8" class="form-check-input" type="checkbox" value="{{old('flg8', $item->flg8  ?? '')}}" {{ $item ? ($item->flg8 == '1' ? 'checked' : '') : '' }}>
                    <label class="form-check-label" for="flg8">障害者（本人）</label>
                </div>
            </div>
        </div>


        <div id="rowFlg9" class="{{ $mtb2_l4 }}" {{ $item && $ofce->flg9 != '1' ? $hidden_cb : '' }}>
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input name="flg9" id="flg9" class="form-check-input" type="checkbox" value="{{old('flg9', $item->flg9  ?? '')}}" {{ $item ? ($item->flg9 == '1' ? 'checked' : '') : '' }}>
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
                <input class="form-check-input" type="checkbox" onchange="Oncheck(this)"
                    @foreach ($arrUpOffi as $idUpOffi)
                        {{$idUpOffi == $upOfice->id  ? 'checked' : '' }} 
                    @endforeach
                    value="1" id="up_offi_{{$loop->iteration}}" name="up_offi_{{$upOfice->id}}">
                    <input type="hidden" value="{{ $upOfice->id }}" id="hidup_offi_{{ $loop->iteration }}" name="up_offi[]">
                <label class="form-check-label" for="up_offi_{{ $upOfice->id }}">{{ $upOfice->file_name }}</label>
            </div>
            @endif
            @endforeach
        </div>
    </fieldset>
</div>

<div class="row mb-2">
    <div class="mt-2">
        <div class="p-1 fs-25"><label>内定通知書</label></div>
    </div>
    <div class="col mb-2 mt-2">
        <button type="button" class="btn btn-blue ms-4" tabindex="-1" onclick="">詳細</button>
        <label class="ms-2" id="letterUpdate1">
            更新日時 {{ empty($item->let_updated_at) ? "" : date_format(new DateTime($item->let_updated_at), CommonConst::DATETIME_FORMAT) }}
        </label>
    </div>
</div>


<div class="row mb-2">
    <div class="mt-2">
        <div class="p-1 fs-25"><label>雇用契約書</label></div>
    </div>
    <div class="co mb-2 mt-2">
        <button type="button" class="btn btn-blue ms-4" tabindex="-1" onclick="">詳細</button>
        <label class="ms-2" id="letterUpdate2">
            更新日時 {{ empty($item->let_updated_at) ? "" : date_format(new DateTime($item->let_updated_at), CommonConst::DATETIME_FORMAT) }}
        </label>
    </div>
</div>


<div class="row mt-4">
    <div class="col-xl-3 col-md-4 mb-2">
        <div class="bg-title text-center p-1"><span class="note">*</span><label>メールアドレス</label></div>
        <input type="email" name="email" id="email" autocomplete="off" class="form-control border-radius-none" maxlength="25" value="{{$item->email ?? '' }}">
    </div>
    <div class="col-xl-3 col-md-4 mb-2">
        <div class="bg-title text-center p-1"><span class="note">*</span><label>メールアドレス　<span style="font-size:.75rem; font-weight: bold">※</span>再確認</label></div>
        <input type="email" name="confirm_email" id="confirm_email" autocomplete="off" class="form-control border-radius-none" maxlength="25" value="{{$item->confirm_email ?? '' }}">
    </div>
</div>


<div class="row mb-2">
    <div class="mt-2">
        <div class="p-1 fs-25"><label>必要書類の添付</label></div>
    </div>
    <div class="col-xl-3 col-md-4 mb-2">
        <div class="bg-title text-center p-1"><span class="note">*</span><label>（外国人雇用）雇用条件書</label></div>
        <fieldset class="legend-box" style="padding-right:0; padding: bottom 0;">
            <div class="legend-body">
                <div class="d-flex">
                    <a target="_blank" id="linkBanner" href="">
                        <img id="imgBanner" name="imgBanner" class="img-fluid" style="height: 97%!important;" src="">
                    </a>
                    <input type="hidden" id="curBanner" name="curBanner" value="">
                    <input type="hidden" id="hidBannerId" name="hidBannerId" value="">
                    <input id="fileBanner" name="fileBanner" type="file" class="visually-hidden" />
                    <button class="btn-none h7 mt-auto ms-auto" type="button" onclick="">
                        <i class="bi bi-paperclip"></i>
                    </button>
                </div>
            </div>
        </fieldset>
    </div>
</div>


<div class="row mb-2">
    <div class="col-xl-3 col-md-4 mb-2">
        <div class="bg-title text-center p-1"><span class="note">*</span><label>（外国人雇用）徴収費用の説明書</label></div>
        <fieldset class="legend-box" style="padding-right:0; padding: bottom 0;">
            <div class="legend-body">
                <div class="d-flex">
                    <a target="_blank" id="linkBanner" href="">
                        <img id="imgBanner" name="imgBanner" class="img-fluid" style="height: 97%!important;" src="">
                    </a>
                    <input type="hidden" id="curBanner" name="curBanner" value="">
                    <input type="hidden" id="hidBannerId" name="hidBannerId" value="">
                    <input id="fileBanner" name="fileBanner" type="file" class="visually-hidden" />
                    <button class="btn-none h7 mt-auto ms-auto" type="button" onclick="">
                        <i class="bi bi-paperclip"></i>
                    </button>
                </div>
            </div>
        </fieldset>
    </div>
</div>


<div class="row">
    <div class="col-xl-3 col-md-4 mb-2">
        <div class="bg-title text-center p-1"><span class="note">*</span><label>（外国人雇用）雇用契約書</label></div>
        <fieldset class="legend-box" style="padding-right:0; padding: bottom 0;">
            <div class="legend-body">
                <div class="d-flex">
                    <a target="_blank" id="linkBanner" href="">
                        <img id="imgBanner" name="imgBanner" class="img-fluid" style="height: 97%!important;" src="">
                    </a>
                    <input type="hidden" id="curBanner" name="curBanner" value="">
                    <input type="hidden" id="hidBannerId" name="hidBannerId" value="">
                    <input id="fileBanner" name="fileBanner" type="file" class="visually-hidden" />
                    <button class="btn-none h7 mt-auto ms-auto" type="button" onclick="">
                        <i class="bi bi-paperclip"></i>
                    </button>
                </div>
            </div>
        </fieldset>
    </div>
</div>




















</div>


@endsection


//--------------------------------------------------------JS-----------------------------------------------------------------
$(document).ready(function() {
    Init();


    $('input').change(function () {
        isChange = true;
        const class_nm = $(this).attr("class");
        const ioPad = class_nm.indexOf("pad-0");
        // 桁数分0をパディング
        if (ioPad > -1) $(this).val(LeftPad($(this).val(), $(this).attr("maxlength")));
        if (typeof inputChangeEvent === "function") inputChangeEvent();
    });
});
/**桁数分0をパディング */
function LeftPad(value, length, char = '0') {
    if (value == "") return value;
    return (char.repeat(length) + value) . slice(-length);
}


function Init(){
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
                    <input class="form-check-input" type="checkbox" onchange="Oncheck(this)" value="1" id="prod_${index}" name="prod_${item.id}">
                    <input type="hidden" value="${item.id}" id="hidprod_${index}" name="prod[]">
                    <label class="form-check-label" for="prod_${index}">${item.name }</label>
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

function OnCheckManager() {
    $("#flg6").prop('checked', false);
}


function checkFlg6(params) {
    if($(params).is(':checked') && $("#manager").is(':checked'))
    {
        $(params).prop('checked', false);
    }
}

function Oncheck(params) {
    ($(params).is(':checked')) ? $(params).val('1') : $(params).val('0')
}

function Save(url) {
    Disable("#manager",false);
    Disable("#company_car",false);
    ChkForm('U03Request', function(){
        submitFormAjax(url)
    });
}

//------------------------------------------------------Controller-------------------------------------------------------------------
public function information(){
        $infoUsersId= request('id',-1);
        $item = $this->u03Service->findByIdInfoUser($infoUsersId);
        $office_cd = null;
        if ($item) {
            $office_cd = $item->office_cd;
        }
        $mstWorkTime = $this->mstClassService->findClassByType(CommonConst::WORK_TIME);
        $ofces = $this->commonService->findAllOffice();
        $ofce = $this->commonService->findByIdOffice($office_cd);
        $belongs = $this->commonService->findBelongByKey();
        $prods = $this->u03Service->findProByKey($office_cd);
        $upOfices = $this->u03Service->findUpOffiByKey($office_cd);
       
            return view('u03.information', compact('item','mstWorkTime','ofces','belongs','prods','upOfices','ofce'));
            // return view('welcome', compact('item','mstWorkTime','ofces','belongs','prods','upOfices','ofce'));
    }
   
    function saveInformation(Request $request) {
        $data = $request->all();
        $this->u03Service->saveInformation($data);
        return route('u03.showInfo', '2');
    }

//------------------------------------------------Service-------------------------------------------------------------------------
function saveInformation($data)
    {
        DB::beginTransaction();
        try {
            $id = $data['id'];
            $officeCd = $data['cmbOffice_cd'];
            $proItem = $this->getListCheckbox($data,'prod');
            $upOffi = $this->getListCheckbox($data,'up_offi');
            $dataToSave = [
                'date_join' => $data['txtDate_join'],
                'name' => $data['txtName'],
                'furigana' => $data['txtFurigana'],
                'office_cd' => $data['cmbOffice_cd'],
                'belong_cd' => $data['cmbBelong_cd'],
                'position' => $data['txtPosition'],
                'work_time_flag' => $data['cmbWork_time_flag'],
                'users_number' => $data['users_number'],
                'procedure_items' => $proItem,


                // 'company_mobile_number' => $data['txtCompany_mobile_number'],
                // 'manager' => $data['manager'],
                // 'manage_position' => $data['manage_position'],
                // 'flg1' => $data['flg1'],
                // 'manage_business' => $data['manage_business'],
                // 'accountant' => $data['accountant'],
                // 'company_car' => $data['company_car'],
                // 'flg3' => $data['flg3'],
                // 'flg4' => $data['flg4'],
                // 'flg5' => $data['flg5'],
                // 'flg6' => $data['flg6'],
                // 'flg7' => $data['flg7'],
                // 'flg8' => $data['flg8'],
                // 'flg9' => $data['flg9'],


                //tblList2  
                'sign_items' => $upOffi,   

                'email' => $data['email'],
                'confirm_email' => $data['confirm_email'],
                'note' => $data['txtNote'],
                'author_id' => $data['author_id'],
                'author_date' => $data['author_date'],
                'applicant_id' => $data['applicant_id'],
                'applicant_date' => $data['applicant_date'],
                'admin1_id  ' => $data['admin1_id'],
                'admin1_date' => $data['admin1_date'],
                'admin2_id' => $data['admin2_id'],
                'admin2_date' => $data['admin2_date'],
                'hd_id' => $data['hd_id'],
                'hd_date' => $data['hd_date'],
            ];
            if (empty($id)) $id = -1;
            if ($id > 0) {
                // update
                $info = InfoUsers::find($id);
                $dataToSave['revision'] = (int)$info->revision + 1;
                $dataToSave['updated_id'] = Auth::id();
            } else {
                //insert
                $dataToSave['author_id'] =  Auth::id();
                $dataToSave['author_date'] = now();
                $dataToSave['status_user'] = '入職者申請待ち'; //$data['status_user'],
                $dataToSave['created_id'] = Auth::id();
            }


            // for ($i = 1; $i <= 7; $i++) {
            //     $flgKey = "flg{$i}";
            //     $officeData[$flgKey] = isset($data[$flgKey]) ? $data[$flgKey] : null;
            // }
            $newInfo = InfoUsers::updateOrCreate(['id' => $id], $dataToSave);




            // $this->registerFile($data);
            // $this->registerHouse($data);
            DB::commit();
            // return $newInfo->id;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    function getListCheckbox($data, $name) {
        if(empty($data[$name])) return;
        $result = '';
        foreach($data[$name] as $item){
            if(!empty($data[$name.'_'.$item]) && $data[$name.'_'.$item] != 0)
                $result .= $item.',';
        }
        return rtrim($result, ",");
    }
//------------------------------------------------Test-------------------------------------------------------------------------
@foreach ($prods as $p)
    {{$p->id}} - {{$p->name}} - {{$p->office_cd}} - {{$p->selected}}
    <br>
@endforeach
<br>
<br>

@foreach ($upOfices as $p)
    {{$p->id}} - {{$p->file_name}} - {{$p->office_cd}} - {{$p->selected}}
    <br>
@endforeach
