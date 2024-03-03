@extends('layouts.layout')
<!-- Screen U07_4 -->
@section('title', '退職申請')

@push('scripts')
<script src="{{ asset('js/users/u074_retireAppli.js?v=' . time()) }}"></script>
@endpush

@section('content')

<style>
    .table>:not(caption)>*>* {
        padding: 0 0;
    }
    .table-secondary {
        border-color: rgba(var(--bs-dark-rgb), var(--bs-border-opacity)) !important;
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
</style>

<?php
    $read_only = "readonly tabindex=-1";
    $disabled_Select = 'select-disable ctr-readonly';    
    $bg_text_center = "table-secondary border-dark";
    $ReadOnly = $mode['edit'] ? 0 : 1;
    $retire_date = '';
?>

<div class="edit-content">
    <div class="row mb-4">
        <div class="col-md-3 col-xl-3 mb-3">
            <div class="bg-title text-center p-1"><label>氏名</label></div>
            <input id="txtName" name="txtName" class="form-control border-radius-none ctr-readonly" maxlength="20" 
                value="{{ $infoUsers ? ($infoUsers->first_name ? ($infoUsers->first_name .($infoUsers->last_name ? '　' . $infoUsers->last_name : '')) : $infoUsers->last_name) : '' }}">
        </div>
        <div class="col-md-3 col-xl-3 mb-3">
            <div class="bg-title text-center p-1"><label>フリガナ</label></div>
            <input id="txtFurigana" name="txtFurigana" class="form-control border-radius-none ctr-readonly" maxlength="20" 
                value="{{ $infoUsers ? ($infoUsers->first_furigana ? ($infoUsers->first_furigana .($infoUsers->last_furigana ? '　' . $infoUsers->last_furigana : '')) : $infoUsers->last_furigana) : '' }}">
        </div>
        <div class="col-md-3 col-xl-3 mb-3">
            <div class="bg-title text-center p-1"><label>契約区分</label></div>
            <select name="cmbContract_class" id="cmbContract_class" class="form-select border-radius-none select-disable ctr-readonly">
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
    <div>下記の理由により退職いたしたくお願い申し上げます。</div>
    <div id="divInfoReUsers" class="table-responsive">
        <table class="table table-bordered border-dark">
            <tr>
                <td class="vertical-mid {{$bg_text_center}}" style="min-width: 220px; width: 220px">
                    <div class="p-1 text-center"><span class="note">*</span><label>退職年月日</label></div>
                </td>
                <td class="">
                    <input id="txtRetire_date" name="txtRetire_date" type="date" autocomplete="off"
                        {{  $ReadOnly==1 ?  $read_only  : ''}}
                        maxlength="10" min="{{ CommonConst::DATE_MIN }}" max="{{ CommonConst::DATE_MAX }}" class="form-control border-radius-none"
                        value="{{ !empty($infoUsers) && !empty($infoReUsers->retire_date) ? date_format(date_create($infoReUsers->retire_date), CommonConst::DATE_FORMAT_2 )  : '' }}" >
                </td>
                @if($ReadOnly  ===  0)
                    <td class="border-top-right-none"></td>
                @endif 
            </tr>
            <tr>
                <td class="vertical-mid {{$bg_text_center}}">
                    <div class="p-1 text-center"><span class="note">*</span><label>退職後〒</label></div>
                </td>
                <td>
                    <input id="txtPost_code" name="txtPost_code" class="form-control border-radius-none allow_postal" maxlength="8" autocomplete="off"
                        {{  $ReadOnly==1 ?  $read_only  : ''}}  
                        value="{{ $infoReUsers ? $infoReUsers->retire_post_code : '' }}">
                </td>
                @if($ReadOnly  ===  0)
                    <td class="border-top-right-none vertical-mid">                
                        <div class="px-2">                      
                                <button type="button" class="btn btn-info py-1" id="btnPost" onclick="CheckPostCode('#txtPost_code', '#txtAddress')">取得</button>
                                <button type="button" class="btn btn-outline-info py-1" onclick="ClearPostCodeInfo('#txtPost_code', '#txtAddress')">クリア</button>
                                <label>郵便番号がわからない方は</label><a class="text-decoration-underline" href="{{ CommonConst::POST_CODE_LINK }}" target="_blank" id="btnPost_code">こちら</a>                    
                        </div>                  
                    </td>
                @endif 
            </tr>
            <tr>
                <td class="vertical-mid {{$bg_text_center}}">
                    <div class="p-1 text-center"><span class="note">*</span><label>退職後住所</label></div>
                </td>
                <td colspan="2">
                    <input id="txtAddress" name="txtAddress" class="form-control border-radius-none align-top" maxlength="100" 
                        {{  $ReadOnly==1 ?  $read_only  : ''}}  
                        value="{{ $infoReUsers ? $infoReUsers->retire_address : '' }}">
                </td>
            </tr>
            <tr>
                <td class="vertical-mid {{$bg_text_center}}">
                    <div class="p-1 text-center"><span class="note">*</span><label>退職後連絡先</label></div>
                </td>
                <td colspan="2">
                    <input id="txtRetire_contact_info" name="txtRetire_contact_info" class="form-control border-radius-none allow_postal" maxlength="20" autocomplete="off" 
                        {{  $ReadOnly==1 ?  $read_only  : ''}} 
                        value="{{ $infoReUsers ? $infoReUsers->retire_contact_info : '' }}">
                </td>
            </tr>
            <tr>
                <td class="vertical-mid {{$bg_text_center}}">
                    <div class="p-1 text-center"><label>備考</label></div>
                </td>
                <td colspan="2">
                    <textarea id="txtNote1" name="txtNote1" class="form-control border-radius-none" maxlength="200"
                        {{  $ReadOnly==1 ?  $read_only  : ''}} 
                        rows="3">{{ $infoReUsers ? $infoReUsers->note1 : '' }}</textarea>
                </td>
            </tr>
            <tr>
                <td class="vertical-mid {{$bg_text_center}}">
                    <div class="p-1 text-center"><span class="note">*</span><label>離職票</label></div>
                </td>
                <td colspan="2">
                    <select name="cmbSeparation_form" id="cmbSeparation_form" class="form-select border-radius-none border-0 bd-t-r {{  $ReadOnly==1 ?  $disabled_Select  : ''}} "  >
                        @if (!empty($cmbSeparateForm))
                            @foreach ($cmbSeparateForm as $item)
                                @if (!empty($item))
                                    <option value="{{ $item->item_cd }}" {{ $infoReUsers && $infoReUsers->separation_form == $item->item_cd ? 'selected'  : '' }}>
                                        {{ $item->item_name }}
                                    </option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </td>
            </tr>
        </table>
        <div class="mb-2">
            <div>◎今後の採用選考、退職後のトラブル防止、等の一助にするため、在籍中の特記事項、退職に至った 経緯、</div>
            <div>退職後の勤務先・予定等、管理者の把握している内容を下記に記載してください。</div>        
            <div>在籍中の特記事項とは、次のような事項について記載してください。</div>    
            <div>・病気による退職の場合病名、病状、休暇状況等</div>
            <div>・職場環境、人間関係等</div>
            <div>・欠勤状況等</div>
        </div>
        <table class="table table-bordered border-dark">
            <tr>
                <td class="vertical-mid {{$bg_text_center}}" style="min-width: 220px; width: 220px">
                    <div class="p-1 text-center"><label>在籍中の特記事項</label></div>
                </td>
                <td>
                    <textarea id="txtSpecial_note1" name="txtSpecial_note1" class="form-control border-radius-none" maxlength="1000"
                    {{  $ReadOnly==1 ?  $read_only  : ''}} 
                        rows="5">{{ $infoReUsers ? $infoReUsers->special_note1 : '' }}</textarea>
                </td>
            </tr>
            <tr>
                <td class="vertical-mid {{$bg_text_center}}">
                    <div class="p-1 text-center"><label>退職に至った経緯</label></div>
                </td>
                <td>
                    <textarea id="txtSpecial_note2" name="txtSpecial_note2" class="form-control border-radius-none" maxlength="1000"
                    {{  $ReadOnly==1 ?  $read_only  : ''}} 
                        rows="5">{{ $infoReUsers ? $infoReUsers->special_note2 : '' }}</textarea>
                </td>
            </tr>
            <tr>
                <td class="vertical-mid {{$bg_text_center}}">
                    <div class="p-1 text-center"><label>退職後の</br>勤務先・予定等</label></div>
                </td>
                <td>
                    <textarea id="txtSpecial_note3" name="txtSpecial_note3" class="form-control border-radius-none" maxlength="1000"
                    {{  $ReadOnly==1 ?  $read_only  : ''}} 
                        rows="5">{{ $infoReUsers ? $infoReUsers->special_note3 : '' }}</textarea>
                </td>
            </tr>
        </table>
        <div class="mb-4 text-center text-sm-center float-md-end">
            <input id="hidInfo_users_cd" name="hidInfo_users_cd" type="hidden" value="{{ $infoReUsers ? $infoReUsers->info_users_cd : '' }}">
            <input id="hidUrlU074Save" type="hidden" value="{{ route('u074.saveRetireAppli', ['id' => $staffId]) }}">
            @if($ReadOnly  ===  0)
                <button type="button" class="btn btn-primary px-5" id="btnNext" href="#" onclick="ChkInput()">次へ</a>
            @endif 
        </div>
    </div>
</div>
@endsection