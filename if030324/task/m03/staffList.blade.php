@extends('layouts.layout')

@section('title', 'スタッフ一覧')
@push('scripts')
    <script src="{{ asset('js/users/m03-2.js?'. time()) }}"></script>
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
?>

<div class="mb-2">
@if($mode['edit'] == true)
    <button type="button" class="btn btn-blue" tabindex="0" onclick="save()">変更申請</button>
@endif
  <a type="button" class="btn btn-outline-primary" href="{{route('m03')}}" tabindex="0" >戻る</a>
  <input type="hidden" id="hidIdInfo" name="hidIdInfo" value="{{$infoUsers->id}}">
  <input type="hidden" id="hidIdUsers" name="hidIdUsers" value="{{$infoUsers->idUsers}}">
  <input type="hidden" id="hidUrlSave" name="hidUrlSave" value="{{route('m03.saveStaffList')}}">
  <input type="hidden" id="hidUrlU04" name="hidUrlU04" value="{{route('u04',['id' => $infoUsers->idUsers])}}">
  
</div>

<div class="mt-5">所属</div>
<div class="row col-md mb-3">
    <div class="col-md-3 col-lg-2 mb-2">
      <div class="bg-title text-center p-1"><label>事業所</label></div>
      <input id="office_name" readonly class="form-control border-radius-none ctr-readonly" value="{{$infoUsers->office_name ?? ''}}">
    </div>
    <div class="col-md-3 col-lg-2 mb-2">
      <div class="bg-title text-center p-1"><label>所属</label></div>
      <input id="belong_name" readonly class="form-control border-radius-none ctr-readonly" value="{{$infoUsers->belong_name ?? ''}}">
    </div>
    <div class="col-md-3 col-lg-2 col-xl-2 mb-2">
      <div class="bg-title text-center p-1"><label>役職</label></div>
      <input id="position" readonly class="form-control border-radius-none ctr-readonly" value="{{$infoUsers->position ?? ''}}">
    </div>
    <div class="col-md-3 col-lg-2 col-xl-2 mb-2">
      <div class="bg-title text-center p-1"><label>正社員・パート</label></div>
     
        <select name="cmbWork_time_flag" id="cmbWork_time_flag" class="form-select border-radius-none select-disable ctr-readonly">
            <option></option>
            @foreach ($mstWorkTime as $cmb)
            <option value="{{ $cmb->item_cd }}" {{ $infoUsers && $infoUsers->work_time_flag == $cmb->item_cd ? 'selected' : '' }}>{{ $cmb->item_name }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 col-lg-2 col-xl-2 col-xxl-1 mb-2">
      <div class="bg-title text-center p-1"><label>社員番号</label></div>
      <input id="users_number" readonly class="form-control border-radius-none ctr-readonly" value="{{$infoUsers->users_number ?? ''}}">
    </div>
    <div class="col-md-3 col-lg-2 col-xl-2 col-xxl-1 mb-2">
        <div class="bg-title text-center p-1"><label>ステータス</label></div>
        <select name="cmbWorkStatus" id="cmbWorkStatus" class="form-select border-radius-none select-disable ctr-readonly">
            @foreach ($mstWorkStatus as $cmb)
            <option value="{{ $cmb->item_cd }}" {{ $infoUsers && $infoUsers->work_status == $cmb->item_cd ? 'selected' : '' }}>{{ $cmb->item_name }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="col-xxl-1">
        <a id="linkChekProfile" href="javascript:void(0)" onclick="showBackGround()" >経歴確認</a>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-7 col-lg-8 col-xl-8 mb-2 d-flex align-items-center me-lg-0 me-4">
        <label>基本情報</label>
    </div>
    <div class="col-md-4 col-lg-4 col-xxl-3 ms-lg-0 ms-md-4 ms-0 mb-2 {{$infoUsers->work_status != 4 ? 'd-none' : ''}}">
        <div class="bg-title text-center p-1"><label>退職時メールアドレス</label></div>
        <input readonly class="form-control border-radius-none ctr-readonly" value="{{$infoUsers->email ?? ''}}">
    </div>
</div>
<div class="row">
    <div class="col-md-8 mx-3">
        {{-- <label>基本情報</label><br><br> --}}
        <div class="row">
            <div class="border bg-title col-2 col-sm-2 col-md-3 col-lg-3 col-xl-3 mb-md-0">
                <div class="text-center p-1" >
                    <label>氏名</label>
                </div>
            </div>
            <div class="border col-5 col-sm-5 col-md-3 col-xl-3 mb-md-0 ctr-readonly">
                <div>
                    <input id="txtName" name="txtName" class="border-0 form-control ctr-readonly border-radius-none " autocomplete="off" 
                    value="{{$infoUsers->name ?? ''}}">
                </div>
            </div>
            <div class="border-0  col-2 col-sm-2 col-md-3 mb-md-0"></div>
            <div class="border bg-title col-2 col-sm-2 col-md-3 col-lg-3 col-xl-3 mb-md-0">
                <div class="text-center p-1" >
                    <label>契約区分</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="border bg-title col-2 col-sm-2 col-md-3 col-lg-3 col-xl-3 mb-md-0">
                <div class="text-center p-1" >
                    <label>フリガナ</label>
                </div>
            </div>
            <div class="border col-5 col-sm-5 col-md-3 col-xl-3 mb-md-0 ctr-readonly">
                <div>
                    <input id="txtNameFuji" name="txtNameFuji" class="form-control ctr-readonly border-0 border-radius-none "autocomplete="off" 
                        value="{{$infoUsers ? $infoUsers->name_furi : ''}}">
                </div>
            </div>
            <div class="border-0  col-2 col-sm-2 col-md-3 mb-md-0"></div>
            <div class="border ctr-readonly col-2 col-sm-2 col-md-3 col-lg-3 col-xl-3 mb-md-0">
                @foreach($cmbContractClass as $item)
                        @if(!empty($infoUsers) && $item->item_cd == $infoUsers->contract_class)   
                            <input id="txtContracClass" name="txtContracClass" class="border-0 form-control ctr-readonly border-radius-none " autocomplete="off" 
                            value="{{$item->item_name ?? ''}}">
                            @endif
                    @endforeach
            </div>
        </div>
        <div class="row">
            <div class="border bg-title col-2 col-sm-2 col-md-3 col-lg-3 col-xl-3 mb-md-0">
                <div class="text-center p-1" >
                    <label>性別</label>
                </div>
            </div>
            <div class="border col-5 col-sm-5col-md-3 col-xl-3 mb-md-0 ctr-readonly">
                <div>
                    @foreach($cmbGender as $item)
                        @if(!empty($infoUsers) && $item->item_cd == $infoUsers->gender)   
                            <input id="txtGender" name="txtGender" class="border-0 form-control ctr-readonly border-radius-none " autocomplete="off" 
                            value="{{$item->item_name ?? ''}}">
                            @endif
                    @endforeach
                </div>
            </div>
            <div class="border-0 col-3 col-sm-3 col-md-4 col-lg-6 col-xl-5 mb-md-0"></div>
        </div>
        <div class="row">
            <div class="border bg-title col-2 col-sm-2 col-md-3 col-lg-3 col-xl-3 col-xl-3 mb-md-0">
                <div class="text-center p-1" >
                    <label>電話番号</label>
                </div>
            </div>
            <div class="border col-5 col-sm-5col-md-3 col-xl-3 mb-md-0 ctr-readonly">
                <div>
                    <input id="txtTel_number" name="txtTel_number" class="border-0 form-control ctr-readonly border-radius-none " maxlength="20" autocomplete="off" 
                    value="{{$infoUsers ? $infoUsers->tel_number :''}}">
                </div>
            </div>
            <div class="border-0 col-3 col-sm-3 col-md-4 col-lg-6 col-xl-5 mb-md-0"></div>
        </div>
        <div class="row">
            <div class="border bg-title col-2 col-sm-2 col-md-3 col-lg-3 col-xl-3 mb-md-0">
                <div class="text-center p-1" >
                    <label>〒</label>
                </div>
            </div>
            <div class="border col-5 col-sm-5col-md-3 col-xl-3 mb-md-0 ctr-readonly">
                <div>
                    <input id="txtPost_code" name="txtPost_code" class="border-0 form-control ctr-readonly border-radius-none " autocomplete="off" 
                        value="{{$infoUsers ? $infoUsers->post_code : ''}}">
                </div>
            </div>
            <div class="border-0 col-3 col-sm-3 col-md-4 col-lg-6 col-xl-5 mb-md-0"></div>
        </div>
        <div class="row">
            <div class="border bg-title col-2 col-sm-2 col-md-3 col-lg-3 col-xl-3 mb-md-0">
                <div class="text-center p-1" >
                    <label>住所1</label>
                </div>
            </div>
            <div class="border col-10 col-sm-10 col-md-9 col-lg-9 col-xl-9 mb-md-0 ctr-readonly">
                <div>
                    <input id="txtAdress1" name="txtAdress1" class="border-0 form-control ctr-readonly border-radius-none align-top" value="{{ $infoUsers ? $infoUsers->adress1 :''}}" readonly="readonly">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="border bg-title col-2 col-sm-2 col-md-3 col-lg-3 col-xl-3 mb-md-0">
                <div class="text-center p-1" >
                    <label>住所2</label>
                </div>
            </div>
            <div class="border col-10 col-sm-10 col-md-9 col-lg-9 col-xl-9 mb-md-0 ctr-readonly">
                <div>
                    <input id="txtAdress2" name="txtAdress2" class="border-0 form-control ctr-readonly border-radius-none align-top" value="{{ $infoUsers ? $infoUsers->adress2 :''}}" readonly="readonly">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="border bg-title col-sm-2 col-2 col-md-3 col-lg-3 col-xl-3 mb-md-0">
                <div class="text-center p-1" >
                    <label>住所3</label>
                </div>
            </div>
            <div class="border col-10 col-sm-10 col-md-9 col-lg-9 col-xl-9 mb-md-0 ctr-readonly">
                <div>
                    <input id="txtAdress3" name="txtAdress3" class="border-0 form-control ctr-readonly border-radius-none align-top" value="{{ $infoUsers ? $infoUsers->adress3 :''}}" readonly="readonly">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="border bg-title col-2 col-sm-2 col-md-3 col-lg-3 col-xl-3 mb-md-0">
                <div class="text-center p-1" >
                    <label>住所1（フリガナ）</label>
                </div>
            </div>
            <div class="border col-10 col-sm-10 col-md-9 col-lg-9 col-xl-9 mb-md-0 ctr-readonly">
                <div>
                    <input id="txtAdress_furigana1" name="txtAdress_furigana1" class="border-0 form-control ctr-readonly border-radius-none align-top"  
                        value="{{ $infoUsers ? $infoUsers->adress_furigana1 : ''}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="border bg-title col-2 col-sm-2 col-md-3 col-lg-3 col-xl-3 col-xl-3 mb-md-0">
                <div class="text-center p-1" >
                    <label>住所2（フリガナ）</label>
                </div>
            </div>
            <div class="border col-10 col-sm-10 col-md-9 col-lg-9 col-xl-9 mb-md-0 ctr-readonly">
                <div>
                    <input id="txtAdress_furigana2" name="txtAdress_furigana2" class="border-0 form-control ctr-readonly border-radius-none align-top"  
                        value="{{$infoUsers ? $infoUsers->adress_furigana2 : ''}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="border bg-title col-2 col-sm-2 col-md-3 col-lg-3 col-xl-3 mb-md-0">
                <div class="text-center p-1" >
                    <label>住所3（フリガナ）</label>
                </div>
            </div>
            <div class="border col-10 col-sm-10 col-md-9 col-lg-9 col-xl-9 mb-md-0 ctr-readonly">
                <div>
                    <input id="txtAdress_furigana3" name="txtAdress_furigana3" class="border-0 form-control ctr-readonly border-radius-none align-top"  
                        value="{{$infoUsers ? $infoUsers->adress_furigana3 : ''}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="border bg-title col-2 col-sm-2 col-md-3 col-lg-3 col-xl-3 mb-md-0">
                <div class="text-center p-1" >
                    <label>生年月日</label>
                </div>
            </div>
            <div class="border col-5 col-sm-5 col-md-5 col-xl-3 mb-md-0 ctr-readonly">
                <div>
                    <input id="txtBirthday" name="txtBirthday" type="date" autocomplete="off"
                        maxlength="10" min="{{ CommonConst::DATE_MIN }}" max="{{ CommonConst::DATE_MAX }}" class="border-0 form-control ctr-readonly border-radius-none"
                        value="{{ !empty($infoUsers) && ($infoUsers->birthday != '') ? date_format(date_create($infoUsers->birthday), CommonConst::DATE_FORMAT_2 )  : '' }}" >
                </div>
            </div>
            <div class="border-0 col-3 col-sm-3 col-md-5 mb-md-0"></div>
        </div>
        <div class="row">
            <div class="border bg-title col-2 col-sm-2 col-md-3 col-lg-3 col-xl-3 mb-md-0">
                <div class="text-center p-1" >
                    <label>入社日</label>
                </div>
            </div>
            <div class="border col-5 col-sm-5col-md-3 col-lg-3 col-xl-3 mb-md-0 ctr-readonly">
                <div>
                    <input id="txtDate_join" name="txtDate_join" type="date" autocomplete="off"
                        maxlength="10" min="{{ CommonConst::DATE_MIN }}" max="{{ CommonConst::DATE_MAX }}" class="border-0 form-control border-radius-none select-disable ctr-readonly"
                        value="{{ !empty($infoUsers) && ($infoUsers->date_join != '') ? date_format(date_create($infoUsers->date_join), CommonConst::DATE_FORMAT_2 )  : ''}}" >
                </div>
            </div>
            <div class="border-0 col-3 ol-sm-3 col-md-4 col-xl-5 mb-md-0"></div>
        </div>
        <div class="row">
            <div class="border bg-title col-2 col-sm-2 col-md-3 col-lg-3 col-xl-3 mb-md-0">
                <div class="text-center p-1" >
                    <label>前職（会社名）</label>
                </div>
            </div>
            <div class="border col-10 col-sm-10 col-md-9 col-lg-9 col-xl-9 mb-md-0 ctr-readonly">
                <div>
                    <input id="txtPrev_job" name="txtPrev_job" class="border-0 form-control ctr-readonly border-radius-none align-top" maxlength="50" 
                        value="{{ $infoUsers ? $infoUsers->prev_job :''}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="border bg-title col-2 col-sm-2 col-md-3 col-lg-3 col-xl-3 mb-md-0">
                <div class="text-center p-1" >
                    <label>マイナンバー (12桁)</label>
                </div>
            </div>
            <div class="border col-10 col-sm-10 col-md-9 col-lg-9 col-xl-9 mb-md-0 ctr-readonly">
                <div>
                    <input id="txtMy_number" name="txtMy_number" class="border-0 form-control ctr-readonly border-radius-none" maxlength="20" autocomplete="off" 
                        value="{{ $infoUsers ? ($infoUsers->idUsers == $mode['auth'] ? $infoUsers->my_number : str_repeat('*', strlen($infoUsers->my_number))) : '' }}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="border bg-title col-2 col-sm-2 col-md-3 col-lg-3 col-xl-3 mb-md-0">
                <div class="text-center p-1" >
                    <label>該当者は選択</label>
                </div>
            </div>
            <div class="border col-10 col-sm-10 col-md-9 col-lg-9 col-xl-9 mb-md-0 ctr-readonly">
                <div class="d-flex p-2 select-disable ctr-readonly">
                    @foreach($cmbObjectPerson as $item)
                        <div class="me-3">
                            <input type="checkbox" id="checkbox_{{$loop->iteration}}" name="checkbox_{{$loop->iteration}}" {{ in_array($item->item_cd, explode(',', $infoUsers->object_person)) ? 'checked' : '' }}>
                            <label for="checkbox_{{$loop->iteration}}">{{$item->item_name}}</label>
                            <input type="hidden" value="{{$item->item_cd }}" id="hidcheckbox_{{ $loop->iteration }}" name="checkbox[]">
                            </div> 
                            
                    @endforeach
                </div>
            </div>
        </div>
        <div class="row">
            <div class="border bg-title col-2 col-sm-2 col-md-3 col-lg-3 col-xl-3 mb-md-0">
                <div class="text-center p-1" >
                    <label>社会保険加入</label>
                </div>
            </div>
            <div class="border col-10 col-sm-10 col-md-3 col-xl-3 mb-md-0 ctr-readonly">
                <div>
                    @foreach($cmbYES_NO as $item)
                        @if(!empty($infoUsers) && $item->item_cd == $infoUsers->jion_insurance)   
                            <input id="txtJion_insurance" name="txtJion_insurance" class="border-0 form-control ctr-readonly border-radius-none " autocomplete="off" 
                            value="{{$item->item_name ?? ''}}">
                            @endif
                    @endforeach
                </div>
            </div>
            <div class="border bg-title col-2 col-sm-2 col-md-3 col-lg-3 col-xl-3 mb-md-0">
                <div class="text-center p-1" >
                    <label>社会保険加入日</label>
                </div>
            </div>
            <div class="border col-10 col-sm-10 col-md-3 col-xl-3 mb-md-0 ctr-readonly">
                <div>
                    <input id="txtInsurance_date" name="txtInsurance_date" type="date" autocomplete="off"
                        maxlength="10" min="{{ CommonConst::DATE_MIN }}" max="{{ CommonConst::DATE_MAX }}" class="border-0 form-control ctr-readonly border-radius-none"
                        value="{{ !empty($infoUsers) && ($infoUsers->insurance_date != '') ? date(CommonConst::DATE_FORMAT_2, strtotime($infoUsers->insurance_date))  : '' }}" >
                </div>
            </div>
        </div>
        <div class="row">
            <div class="border bg-title col-2 col-sm-2 col-md-3 col-lg-3 col-xl-3 mb-md-0">
                <div class="text-center p-1" >
                    <label>雇用保険加入</label>
                </div>
            </div>
            <div class="border col-4 col-sm-4 col-md-3 col-xl-3 mb-md-0 ctr-readonly">
                <div>
                    @foreach($cmbYES_NO as $item)
                        @if(!empty($infoUsers) && $item->item_cd == $infoUsers->insurance_social)   
                            <input id="txtInsurance_social" name="txtInsurance_social" class="border-0 form-control ctr-readonly border-radius-none " autocomplete="off" 
                            value="{{$item->item_name ?? ''}}">
                            @endif
                    @endforeach
                </div>
            </div>
            <div class="border bg-title col-2 col-sm-2 col-md-3 col-lg-3 col-xl-3 mb-md-0">
                <div class="text-center p-1" >
                    <label>雇用保険加入日</label>
                </div>
            </div>
            <div class="border col-4 col-sm-4 col-md-3 col-xl-3 mb-md-0 ctr-readonly">
                <div>
                    <input id="txtInsurance_social_date" name="txtInsurance_social_date" type="date" autocomplete="off"
                        maxlength="10" min="{{ CommonConst::DATE_MIN }}" max="{{ CommonConst::DATE_MAX }}" class="border-0 form-control ctr-readonly border-radius-none"
                        value="{{ !empty($infoUsers) && ($infoUsers->insurance_social_date != '') ? date(CommonConst::DATE_FORMAT_2, strtotime($infoUsers->insurance_social_date)) : '' }}" >
                </div>
            </div>
        </div>
    </div>
  {{-- icon image --}}
    <div class="row col-md-3 col-lg-3 col-xl-3 text-center mb-2 ms-3" style="height: 25rem; max-width:25rem;" >
        <div class="col-md-8 col-sm-8 col-lg-9 col-xl-9 border my-3 flex-fill" style="max-height: 20rem; min-height: 15rem;">
            <div class="flex-fill" style="height:18rem;">
                <a target="_blank" id="linkIcon" href="{{ !empty($infoUsers) && !empty($infoUsers->icon_file_name) ? asset($infoUsers->icon_folder_path.$infoUsers->icon_folder_name.$infoUsers->icon_file_name) : '' }}">
                    <img id="imgIcon" name="imgIcon" class="img-fluid py-2" style="height: 97%!important;" src="{{ !empty($infoUsers) && !empty($infoUsers->icon_file_name) ? asset($infoUsers->icon_folder_path.$infoUsers->icon_folder_name.$infoUsers->icon_file_name) : '' }}">
                </a>
            </div>
            @if($mode['edit'] == true)
            <div id="iconIcon" class="text-end">
                <button class="btn-none h5 mt-auto ms-auto" type="button" onclick="return ShowFileDialog('Icon')"><i class="bi bi-paperclip"></i></button>
                <button type="button" class="btn-none text-danger {{$infoUsers && $infoUsers->icon_file_name ? '' : 'visually-hidden'}}" onclick="ClearFile('Icon')"><i class="bi bi-x-circle-fill"></i></button>
            </div>
            @endif
        </div>
        
            <input id="oldIcon" name="oldIcon" class="stt-row" type="hidden" value="{{ $infoUsers ? $infoUsers->icon_folder_name.$infoUsers->icon_file_name : ''}}"/>   
            <input type="hidden" id="curIcon" name="curIcon" value="{{ !empty($infoUsers) ? 1 : 0 }}">
            <input id="fileIcon" name="fileIcon" type="file" class="visually-hidden" onchange="ChangeFile('Icon', 1,'icon')" />
            <input id="tmpIcon" name="tmpIcon" type="hidden" />
    </div>
    {{-- end icon image --}}
</div>
<div class="mb-4 col-md-10">
        <fieldset class="legend-box" style="min-height: 10rem;">
            <legend class="legend-title">◆扶養家族</legend>
            <div class="legend-body">
                <div class="table-responsive w-100 me-4">
                    <table id="tbDependent" class="table table-bordered border-dark mb-4">
                        <tr>
                            <td class="width-gr-1 border-right-none vertical-mid {{$bg_text_center}}" >
                                <div class="p-1 text-center"><label>扶養家族</label></div>
                            </td>
                            <td class="{{ $depenUs->isEmpty() ? '' : 'width-gr-2'}} ctr-readonly align-items-center input-group border-right-none">
                                <input class="form-control border-radius-none ctr-readonly border-0 allow_numeric" autocomplete="off" value="{{ empty($infoUsers) ? 0 : $infoUsers->dependent_number }}" ><span class="ctr-readonly me-3">　人</span>
                            </td>
                            <td colspan="3" style="{{ $depenUs->isEmpty() ? 'border-bottom-style: hidden;' : ''}} " class="border-top-right-none vertical-mid">
                                <label >※あなたが扶養する義務がある方を記入して下さい</label>
                            </td>
                        </tr>
                        @foreach ($depenUs as $item)
                            <tr>
                                <td class="vertical-mid {{$bg_text_center}}" >
                                    <div class="p-1 text-center"><label>氏名</label></div>
                                </td>
                                <td class="width-gr-2">
                                    <input class="form-control border-radius-none  ctr-readonly" autocomplete="off" value="{{$item->name}}">
                                </td>
                                <td class="width-gr-1 vertical-mid {{$bg_text_center}}"   >
                                    <div class="p-1 text-center"><label>フリガナ</label></div>
                                </td>
                                <td>
                                    <input class="form-control border-radius-none ctr-readonly " autocomplete="off" value="{{$item->furigana}}">
                                </td>
                            </tr>
                            <tr>
                                <td  class="vertical-mid {{$bg_text_center}}"   >
                                    <div class="p-1 text-center"><label>性別</label></div>
                                </td>
                                <td>
                                    <input class="form-control ctr-readonly border-radius-none " autocomplete="off" value="{{$item->gender}}">
                                </td>
                                <td  class="vertical-mid {{$bg_text_center}}"   >
                                    <div class="p-1 text-center"><label>統柄</label></div>
                                </td>
                                <td>
                                    <input class="form-control ctr-readonly border-radius-none " autocomplete="off" value="{{$item->relationship}}">
                                </td>
                            </tr>
                            <tr>
                                <td  class="vertical-mid {{$bg_text_center}}"   >
                                    <div class="p-1 text-center"><label>生年月日</label></div>
                                </td>
                                <td>
                                    <input type="date" autocomplete="off"
                                    maxlength="10" min="{{ CommonConst::DATE_MIN }}" max="{{ CommonConst::DATE_MAX }}" class="border-0 form-control ctr-readonly border-radius-none"
                                    value="{{ $item->birthday != '' ? date(CommonConst::DATE_FORMAT_2, strtotime($item->birthday)) : '' }}" >
                                    
                                </td>
                                <td  class="vertical-mid {{$bg_text_center}}"   >
                                    <div class="p-1 text-center"><label>職業</label></div>
                                </td>
                                <td>
                                    <input class="form-control ctr-readonly border-radius-none " autocomplete="off" value="{{$item->career}}">
                                </td>
                            </tr>

                            <tr>
                                <td  class="vertical-mid {{$bg_text_center}}"   >
                                    <div class="p-1 text-center"><label>マイナンバー (12桁)</label></div>
                                </td>
                                <td colspan="4" class="ctr-readonly">
                                    <input class="form-control ctr-readonly border-radius-none {{ $infoUsers->idUsers == $mode['auth'] ? '': 'visually-hidden'}}" maxlength="20" autocomplete="off" value="{{$item->my_number}}">
                                </td>
                            </tr>
                        @endforeach
                    </table>
            </div>
        </fieldset>
</div>
<div class="table-responsive w-100 me-4">
    <label>緊急連絡先(本人以外)</label>
    <table id="tbGuarantee" class="table table-bordered border-dark mb-4">
        <tr>
            <td class="width-gr-1 vertical-mid {{$bg_text_center}}"   >
                <div class="p-1 text-center"><label>氏名</label></div>
            </td>
            <td class="width-gr-2">
                <input id="txtGuar_name" name="txtGuar_name" class="form-control border-radius-none ctr-readonly border-0 allow_numeric" autocomplete="off" 
                value="{{ $guarantee ? $guarantee->name : '' }}">
            </td>
            <td colspan="3" class="border-top-right-none vertical-mid"></td>
        </tr>
        <tr>
            <td class="width-gr-1 vertical-mid {{$bg_text_center}}"   >
                <div class="p-1 text-center"><label>続柄</label></div>
            </td>
            <td class="width-gr-2">
                <input id="txtGuar_relaship" name="txtGuar_relaship" class="form-control border-radius-none  ctr-readonly" autocomplete="off" 
                    value="{{$guarantee ? $guarantee->relationship: ''}}">
            </td>
            <td  class="vertical-mid width-gr-1 {{$bg_text_center}}"   >
                <div class="p-1 text-center"><label>緊急連絡先</label></div>
            </td>
            <td class="width-gr-2">
                <input id="txtGuar_Fuji" name="txtGuar_Fuji" class="form-control border-radius-none ctr-readonly " autocomplete="off" 
                    value="{{$guarantee ? $guarantee->my_number : ''}}">
            </td>
            <td class="border-top-bot-right-none"></td>
        </tr>

    </table>
</div>
<div class="table-responsive w-100 me-4">
    <label>給与振込口座</label>
    <table  class="table table-bordered border-dark mb-4">
        <tr>
            <td class="width-gr-1 vertical-mid {{$bg_text_center}}"   >
                <div class="p-1 text-center"><label>銀行コード</label></div>
            </td>
            <td class="width-gr-2">
                <input id="txtBank_code" name="txtBank_code" class="form-control border-radius-none ctr-readonly" autocomplete="off" 
                value = "{{$infoUsers ? $infoUsers->bank_code : ''}}" >
            </td>
            <td class="vertical-mid width-gr-1 {{$bg_text_center}}"   >
                <div class="p-1 text-center"><label>銀行名・振興金庫名・組合名</label></div>
            </td>
            <td class="width-gr-2">
                <input id="txtBank_name" name="txtBank_name" class="form-control border-radius-none ctr-readonly" autocomplete="off" 
                value="{{$infoUsers ? $infoUsers->bank_name : ''}}">
            </td>
            <td class="border-top-bot-right-none"></td>
        </tr>
        <tr>
            <td class="vertical-mid {{$bg_text_center}}"   >
                <div class="p-1 text-center"><label>支店コード</label></div>
            </td>
            <td>
                <input id="txtBrandBank_code" name="txtBrandBank_code" class="form-control border-radius-none ctr-readonly" autocomplete="off" 
                value = "{{$infoUsers ? $infoUsers->branch_bank_code : ''}}" >
            </td>
            <td  class="vertical-mid {{$bg_text_center}}"   >
                <div class="p-1 text-center"><label>支店名・出張所/代理店名</label></div>
            </td>
            <td>
                <input id="txtBrandBankNm" name="txtBrandBankNm" class="form-control border-radius-none ctr-readonly" autocomplete="off" 
                    value="{{$infoUsers ? $infoUsers->branch_bank_name : ''}}">
            </td>
            <td class="border-top-bot-right-none"></td>
        </tr>
    </table>
</div>
<div class="table-responsive w-100 me-4">
    <label>口座名義</label>
    <table  class="table table-bordered border-dark">
        <tr>
            <td class="vertical-mid width-gr-1 {{$bg_text_center}}"   >
                <div class="p-1 text-center"><label>氏名</label></div>
            </td>
            <td class="width-gr-2">
                <input id="txtBankUserNm" name="txtBankUserNm" class="form-control border-radius-none ctr-readonly" autocomplete="off" 
                    value="{{$infoUsers ? $infoUsers->bank_user_name :''}}">
            </td>
            <td class="vertical-mid width-gr-1 {{$bg_text_center}}"   >
                <div class="p-1 text-center"><label>フリガナ</label></div>
            </td>
            <td class="width-gr-2">
                <input id="txtBankUserFuji" name="txtBankUserFuji" class="form-control border-radius-none ctr-readonly" autocomplete="off" 
                    value="{{$infoUsers ? $infoUsers->bank_user_furigana :''}}">
            </td>
            <td class="border-top-bot-right-none"></td>
        </tr>
        <tr>
            <td  class="vertical-mid {{$bg_text_center}}"   >
                <div class="p-1 text-center"><label>預金種類</label></div>
            </td>
            <td class="ctr-readonly">
            @foreach($cmbDeposit as $item)
                @if(!empty($infoUsers) && $item->item_cd == $infoUsers->deposit_type)   
                    <input id="txtDeposit_type" name="txtDeposit_type" class="form-control border-radius-none ctr-readonly " autocomplete="off" 
                    value="{{ $item->item_name}}">
                    @endif
                @endforeach
            </td>
            <td  class="vertical-mid {{$bg_text_center}}"   >
                <div class="p-1 text-center"><label>口座番号</label></div>
            </td>
            <td>
                <input id="txtBankNum" name="txtBankNum" class="form-control border-radius-none ctr-readonly" autocomplete="off" 
                    value="{{$infoUsers ? $infoUsers->bank_number : ''}}">
            </td>
            <td class="border-top-bot-right-none"></td>
        </tr>
    </table>
    <label class="mb-4">※注意事項:上記はすべて給与振込に必要な事項となっていますので、記入漏れないようにして下さい</label><br>
</div>
<div class="mb-4 col-md-10">
    <fieldset class="legend-box" style="min-height: 10rem;">
        <legend class="legend-title">◆交通費(1日/往復)</legend>
        <div class="legend-body">
            <div class="table-responsive w-100">
        <table id="tbTransport" class="table table-bordered border-dark mb-4">
            <tr>
                <td class="width-gr-1 vertical-mid {{$bg_text_center}}" >
                    <div class="p-1 text-center"><label>自家用車</label></div>
                </td>
                <td class="width-gr-2 ctr-readonly align-items-center">
                    <input id="txtPrivate_car" name="txtPrivate_car" class="form-control border-0 border-radius-none ctr-readonly text-end" autocomplete="off" 
                        value="{{$infoUsers && $infoUsers->private_car ? number_format($infoUsers->private_car) .'　Km'   : ''}}">                     
                </td>
                <td colspan="3" class="border-top-right-none">
                    <div class="px-2">
                        <label>※自宅~勤務先までの往復実装距離</label>
                    </div>
                </td>
            </tr>
            @if(count($transport)> 0 )
                @foreach ($transport as $tran)
                    <tr>
                        <td class="vertical-mid {{$bg_text_center}}" >
                            <div class="p-1 text-center"><label>公共交通機関</label></div>
                        </td>
                        <td class="ctr-readonly align-items-center">
                            <input id="txtTran_startPoint_{{$loop->iteration}}" maxlength="10" name="txtTran_startPoint_{{$loop->iteration}}" class="text-end ctr-readonly form-control border-0 border-radius-none " autocomplete="off" 
                            value="{{$tran && $tran->start_point ? $tran->start_point . '　駅から' : ''}}">  
                        </td>
                        <td class="width-gr-2 ctr-readonly align-items-center">
                            <input id="txtTran_endPoint_{{$loop->iteration}}" maxlength="10" name="txtTran_endPoint_" class="text-end  form-control border-0 border-radius-none  ctr-readonly" autocomplete="off" 
                            value="{{$tran ? $tran->end_point. '　駅まで' : ''}}"> 
                        </td>
                        <td class="vertical-mid width-gr-1 {{$bg_text_center}}" >
                            <div class="p-1 text-center"><label>金額</label></div>
                        </td>
                        <td class="width-gr-1 ctr-readonly align-items-center ">
                            <input id="txtTran_Amount_{{$loop->iteration}}" maxlength="9" name="txtTran_Amount_{{$loop->iteration}}" class="text-end  form-control border-0 border-radius-none  ctr-readonly" autocomplete="off" 
                                value="{{$tran ? number_format($tran->amount) .'　円' : '　円'}} ">  
                        </td>
                    </tr>
                @endforeach
            @else 
            <tr>
                <td class="vertical-mid {{$bg_text_center}}" >
                    <div class="p-1 text-center"><label>公共交通機関</label></div>
                </td>
                <td >
                    <input id="txtTran_startPoint" maxlength="10" name="txtTran_startPoint" class="ctr-readonly form-control border-0 border-radius-none " autocomplete="off" 
                    value="">                        
                </td>
                <td class="width-gr-2">
                    <input id="txtTran_endPoint" maxlength="10" name="txtTran_endPoint_" class="form-control border-0 border-radius-none  ctr-readonly" autocomplete="off" 
                    value="">                        
                </td>
                <td class="vertical-mid width-gr-1 {{$bg_text_center}}" >
                    <div class="p-1 text-center"><label>金額</label></div>
                </td>
                <td class="width-gr-1">
                    <input id="txtTran_Amount" maxlength="9" name="txtTran_Amount" class="form-control border-0 border-radius-none  ctr-readonly" autocomplete="off" 
                        value="">                        
                </td>
            </tr>
            @endif

            <tr class="border-0">
                <td class=" vertical-mid {{$bg_text_center}} border" >
                    <div class="p-1 text-center"><label>その他</label></div>
                </td>
                <td class="ctr-readonly border border-dark ">
                    @foreach($cmbTransp as $item)
                        @if(!empty($infoUsers) && $item->item_cd == $infoUsers->transport_type)   
                            <input id="txtTransp_type" name="txtTransp_type" class="form-control border-radius-none ctr-readonly" autocomplete="off"
                            value="{{ $item->item_name}}">
                        @endif
                    @endforeach
                </td>
                <td colspan="3" class="border-0 vertical-mid">
                    <div class="px-2" >
                        <label>※自転車・徒歩の選択</label>
                    </div>
                </td>
            </tr>
        </table>
        </div>
    </fieldset>
</div>
<div class="row col-md-10 mb-2 px-3">
    <div class="row col-md-4 me-3 mb-2">    
        <div class="bg-title text-center p-1"><label>通勤経路マップ</label></div>
        <div class=" border ctr-readonly">
            <a class="flex-grow-1" id="link5" style="{{!empty($uploadUs) && !empty($uploadUs->file_name) ? '' : 'pointer-events: none;' }}" target="_blank"  href="{{ !empty($uploadUs) ? asset($uploadUs->folder_path.$uploadUs->folder_name.$uploadUs->file_name) :'' }}" >
                <input id="txt5" name="txt5" readonly class="ctr-readonly form-control border-radius-none border-0" value="{{  $uploadUs ? $uploadUs->file_name:'' }}">
            </a>
        </div>
    </div>
</div>


<label>各種書類</label>
<div class="row col-md-10 mb-2 px-3">
    <div class="row col-md-4 me-3 mb-2">
        <div class="bg-title text-center p-1"><label>マイナンバーカードもしくは通知カードの写し(表)</label></div>
        <div class=" border ctr-readonly">
            <a class="flex-grow-1" style="{{!empty($app) && !empty($app->file_name_notice_before) ? '' : 'pointer-events: none;' }}" id="linkNoticeCardBefore" target="_blank"  href="{{ !empty($app) ? asset($app->folder_path.$app->folder_name_notice.$app->file_name_notice_before) :'' }}" >
                <input id="txtNoticeCardBefore" name="txtNoticeCardBefore" readonly class="form-control border-radius-none border-0 ctr-readonly" value="{{  $app ? $app->file_name_notice_before:'' }}">
            </a>
        </div>
    </div>
    <div class="row col-md-4 mb-2">
        <div class="bg-title text-center p-1"><label>マイナンバーカードもしくは通知カードの写し(裏)</label></div>
        <div class=" border ctr-readonly">
            <a class="flex-grow-1" style="{{!empty($app) && !empty($app->file_name_notice_after) ? '' : 'pointer-events: none;' }}" id="linkNoticeCardAfter" target="_blank"  href="{{ !empty($app) ? asset($app->folder_path.$app->folder_name_notice.$app->file_name_notice_after) :'' }}" >
                <input id="txtNoticeCardAfter" name="txtNoticeCardAfter" readonly class="form-control border-radius-none border-0 ctr-readonly" value="{{  $app ? $app->file_name_notice_after:'' }}">
            </a>
        </div>
    </div>
</div>
<div class="row col-md-10 px-3">
    <div class="row col-md-4 me-3 mb-2">
        <div class="bg-title text-center p-1">@if ($infoUsers->flg3)<span class="note">*</span>@endif<label>運転免許証の写（表）</label></div>
        <div class="border ctr-readonly">
            <a class="flex-grow-1" style="{{!empty($app) && !empty($app->file_name_driver_before) ? '' : 'pointer-events: none;' }}" id="linkDriverLicenseBefore" target="_blank"  href="{{ !empty($app) ? asset($app->folder_path . $app->folder_name_driver . $app->file_name_driver_before) :'' }}" >
                <input id="txtDriverLicenseBefore" name="txtDriverLicenseBefore" readonly class="form-control border-radius-none border-0 ctr-readonly" value="{{ old('txtDriverLicenseBefore', $app ? $app->file_name_driver_before:'') }}">
            </a>
        </div>
    </div>
    <div class="row col-md-4 me-3 mb-2">
        <div class="bg-title text-center p-1">@if ($infoUsers->flg3)<span class="note">*</span>@endif<label>運転免許証の写し(裏)</label></div>
        <div class="ctr-readonly border">
            <a class="flex-grow-1" style="{{!empty($app) && !empty($app->file_name_driver_after) ? '' : 'pointer-events: none;' }}" id="linkDriverLicenseAfter" target="_blank"  href="{{ !empty($app) ? asset($app->folder_path . $app->folder_name_driver . $app->file_name_driver_after) :'' }}" >
                <input id="txtDriverLicenseAfter" name="txtDriverLicenseAfter" readonly class="form-control border-radius-none ctr-readonly border-0" value="{{ $app ? $app->file_name_driver_after:'' }}">
            </a>
        </div>
    </div>
    <div class="row col-md-3 mb-2">
        <div class="bg-title text-center p-1">@if ($infoUsers->flg3)<span class="note">*</span>@endif<label>有効期限</label></div>
        <div class="ctr-readonly border">
            <input class="form-control border-radius-none ctr-readonly border-0" type="date" autocomplete="off" name="driver_license_date" id="driver_license_date" 
            value="{{$app->driver_license_date ? date(CommonConst::DATE_FORMAT_2, strtotime($app->driver_license_date)) : ''}}"
            maxlength="10" min="{{CommonConst::DATE_MIN}}" 
            max="{{date(CommonConst::DATE_FORMAT_2, strtotime('+4 years')) }}" >
        </div>
    </div>
</div>

<div class="row col-md-10 px-3">
    <div class="row col-md-4 me-3 mb-2">
        <div class="bg-title text-center p-1"><label>扶養控除申告書</label></div>
        <div class="ctr-readonly border">
            <a class="flex-grow-1" style="{{!empty($app) && !empty($app->file_name_dependent) ? '' : 'pointer-events: none;' }}" id="linkDependentPerson" target="_blank"  href="{{ !empty($app) ? asset($app->folder_path . $app->folder_name_dependent . $app->file_name_dependent) :'' }}" >
                <input id="txtDependentPerson" name="txtDependentPerson" readonly class="form-control ctr-readonly border-radius-none border-0" value="{{ $app ? $app->file_name_dependent:'' }}">
            </a>
        </div>
    </div>
</div>

<div class="row col-md-10 px-3">
    <div class="row col-md-4 me-3 mb-2">
        <div class="bg-title text-center p-1"><label>雇用保険被保険者番号通知書</label></div>
        <div class="ctr-readonly border">
            <a class="flex-grow-1" style="{{!empty($app) && !empty($app->file_name_insurance_no) ? '' : 'pointer-events: none;' }}" 
                id="linkInsuranceNo" target="_blank"  href="{{ !empty($app) ? asset($app->folder_path . $app->folder_name_insurance_no . $app->file_name_insurance_no) :'' }}" >
                <input id="txtInsuranceNo" name="txtInsuranceNo" readonly class="form-control ctr-readonly border-radius-none border-0" value="{{ $app ? $app->file_name_insurance_no:'' }}">
            </a>
        </div>
    </div>
</div>

<div class="row col-md-10 px-3">
    <div class="row col-md-4 me-3 mb-2">
        <div class="bg-title text-center p-1"><label>住民税納税通知書</label></div>
        <div class="ctr-readonly border">
            <a class="flex-grow-1" style="{{!empty($app) && !empty($app->file_name_resident_tax) ? '' : 'pointer-events: none;' }}" 
                id="linkResidentTax" target="_blank"  href="{{ !empty($app) ? asset($app->folder_path . $app->folder_name_resident_tax . $app->file_name_resident_tax) :'' }}" >
                <input id="txtResidentTax" name="txtResidentTax" readonly class="form-control ctr-readonly border-radius-none border-0" value="{{ $app ? $app->file_name_resident_tax:'' }}">
            </a>
        </div>
    </div>
</div>

<div class="row col-md-10 px-3">
    <div class="row col-md-4 me-3 mb-2">
        <div class="bg-title text-center p-1"><label>保育料助成金申請書</label></div>
        <div class="ctr-readonly border">
            <a class="flex-grow-1" style="{{!empty($app) && !empty($app->file_name_childcare_fee) ? '' : 'pointer-events: none;' }}" 
                id="linkChildcareFee" target="_blank"  href="{{ !empty($app) ? asset($app->folder_path . $app->folder_name_childcare_fee . $app->file_name_childcare_fee) :'' }}" >
                <input id="txtChildcareFee" name="txtChildcareFee" readonly class="form-control ctr-readonly border-radius-none border-0" value="{{  $app ? $app->file_name_childcare_fee:'' }}">
            </a>
        </div>
    </div>
</div>

<div class="row col-md-10 px-3 {{$infoUsers->flg8 ? '' : 'visually-hidden'}}">
    <div class="row col-md-4 me-3 mb-2">
        <div class="bg-title text-center p-1"><label>障害者手帳の写し　(本人分)</label></div>
        <div class="ctr-readonly border">
            <a class="flex-grow-1" style="{{!empty($app) && !empty($app->file_name_disable_person) ? '' : 'pointer-events: none;' }}" 
                id="linkDisablePerson" target="_blank"  href="{{ !empty($app) ? asset($app->folder_path . $app->folder_name_disable_person . $app->file_name_disable_person) :'' }}" >
                <input id="txtDisablePerson" name="txtDisablePerson" readonly class="form-control ctr-readonly border-radius-none border-0" value="{{ $app ? $app->file_name_disable_person:'' }}">
            </a>
        </div>
        </div>
    <div class="row col-md-3 me-3 mb-2">
        <div class="bg-title text-center p-1"><label>有効期限</label></div>
        <div class="ctr-readonly border">
            <input class="form-control ctr-readonly border-radius-none border-0" type="date" autocomplete="off" name="disable_person_date" id="disable_person_date" 
            value="{{$app->disable_person_date ? date(CommonConst::DATE_FORMAT_2, strtotime($app->disable_person_date)) : ''}}" maxlength="10" min="{{CommonConst::DATE_MIN}}" 
            max="{{date(CommonConst::DATE_FORMAT_2, strtotime('+4 years')) }}" onchange="">
        </div>
    </div>
</div>

<div class="mb-4 col-md-10 ">
    <fieldset class="legend-box" style="min-height: 10rem; ">
        <legend class="legend-title">◆障害者手帳の写し　(扶養家族分)</legend>
        <div class="legend-body">
            @foreach($disDepend as $disDepend)
                <div class="row px-3">
                    <div class="row col-md-4 me-3 mb-2">
                        <div class="bg-title text-center p-1"><label>障害者手帳の写し　(扶養家族分)</label></div>
                        <div class="ctr-readonly border">
                            <a class="flex-grow-1" style="{{!empty($disDepend) && !empty($disDepend->file_name) ? '' : 'pointer-events: none;' }}" 
                                id="linkDisableDepend_{{$loop->iteration}}" target="_blank"  href="{{ !empty($disDepend) ? asset($disDepend->folder_path . $disDepend->folder_name . $disDepend->file_name) :'' }}" >
                                <input id="txtDisableDepend_{{$loop->iteration}}" name="txtDisableDepend_{{$loop->iteration}}" readonly class="form-control ctr-readonly border-radius-none border-0" value="{{ $disDepend ? $disDepend->file_name:'' }}">
                            </a>
                        </div>
                        </div>
                    <div class="row col-md-4 me-3 mb-2">
                        <div class="bg-title text-center p-1"><label>有効期限</label></div>
                        <div class="ctr-readonly border">
                            <input class="form-control ctr-readonly border-radius-none border-0" type="date" autocomplete="off" name="disable_depend_date_{{$loop->iteration}}" id="disable_depend_date_{{$loop->iteration}}" 
                            value="{{$disDepend ? date(CommonConst::DATE_FORMAT_2, strtotime($disDepend->disable_depend_date)) : ''}}" maxlength="10" min="{{CommonConst::DATE_MIN}}" 
                            max="{{date(CommonConst::DATE_FORMAT_2, strtotime('+4 years')) }}" >
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </fieldset>
</div>

<div class="row col-md-10 px-3">
    <div class="row col-md-4 me-3 mb-2">
        <div class="bg-title text-center p-1"><label>在留カードの写し(本人分　表)</label></div>
        <div class="ctr-readonly border">
            <a class="flex-grow-1" style="{{!empty($app) && !empty($app->file_name_resident_person_card_before) ? '' : 'pointer-events: none;' }}" 
                id="linkResidentPersonCardBefore" target="_blank"  href="{{ !empty($app) ? asset($app->folder_path . $app->folder_name_resident_person_card . $app->file_name_resident_person_card_before) :'' }}" >
                <input id="txtResidentPersonCardBefore" name="txtResidentPersonCardBefore" readonly class="form-control border-radius-none border-0 ctr-readonly" value="{{ old('txtResidentPersonCardBefore', $app ? $app->file_name_resident_person_card_before:'') }}">
            </a>
        </div>
        </div>
    <div class="row col-md-4 me-3 mb-2">
        <div class="bg-title text-center p-1"><label>在留カードの写し（本人分　裏）</label></div>
        <div class="ctr-readonly border">
            <a class="flex-grow-1" style="{{!empty($app) && !empty($app->file_name_resident_person_card_after) ? '' : 'pointer-events: none;' }}" 
                id="linkResidenPersonCardAfter" target="_blank"  href="{{ !empty($app) ? asset($app->folder_path . $app->folder_name_resident_person_card . $app->file_name_resident_person_card_after) :'' }}" >
                <input id="txtResidenPersonCardAfter" name="txtResidenPersonCardAfter" readonly class="form-control border-radius-none border-0 ctr-readonly" value="{{ old('txtResidenPersonCardAfter', $app ? $app->file_name_resident_person_card_after:'') }}">
            </a>
        </div>
        </div>
    <div class="row col-md-3 mb-2">
        <div class="bg-title text-center p-1"><label>有効期限</label></div>
        <div class="ctr-readonly border">
            <input class="form-control border-radius-none border-0 ctr-readonly" autocomplete="off" type="date" name="resident_person_card_date" id="resident_person_card_date" 
            value="{{ $app && $app->resident_person_card_date ? date(CommonConst::DATE_FORMAT_2, strtotime($app->resident_person_card_date)) : ''}}" maxlength="10" >
        </div>
    </div>
</div>

<div class="mb-4 col-md-10 ">
    <fieldset class="legend-box" style="min-height: 10rem; ">
        <legend class="legend-title">◆在留カードの写し　(扶養家族分)</legend>
        <div class="legend-body">
            @foreach($res as $res)
            <div class="row px-3">
                <div class="row col-md-4 me-3 mb-2">
                    <div class="bg-title text-center p-1"><label>在留カードの写し　(扶養家族分　表)　</label></div>
                    <div class="ctr-readonly border ctr-readonly">
                        <a class="flex-grow-1" style="{{!empty($res) && !empty($res->file_name_before) ? '' : 'pointer-events: none;' }}" 
                            id="linkResDependBefore_{{$loop->iteration}}" target="_blank"  href="{{ !empty($res) ? asset($res->folder_path . $res->folder_name . $res->file_name_before) :'' }}" >
                            <input id="txtResDependBefore_{{$loop->iteration}}" name="txtResDependBefore_{{$loop->iteration}}" readonly class="form-control border-radius-none ctr-readonly border-0" value="{{ $res ? $res->file_name_before:'' }}">
                        </a>
                    </div>
                </div>
                <div class="row col-md-4 me-3 mb-2">
                    <div class="bg-title text-center p-1"><label>在留カードの写し　(扶養家族分　裏)　</label></div>
                    <div class="ctr-readonly ctr-readonly border">
                        <a class="flex-grow-1" style="{{!empty($res) && !empty($res->file_name_after) ? '' : 'pointer-events: none;' }}" 
                            id="linkResDependAfter_{{$loop->iteration}}" target="_blank"  href="{{ !empty($res) ? asset($res->folder_path . $res->folder_name . $res->file_name_after) :'' }}" >
                            <input id="txtResDependAfter_{{$loop->iteration}}" name="txtResDependAfter_{{$loop->iteration}}" readonly class="form-control border-radius-none ctr-readonly border-0" value="{{ $res ? $res->file_name_after:'' }}">
                        </a>
                    </div>
                </div>
                <div class="row col-md-3 mb-2">
                    <div class="bg-title text-center p-1"><label>有効期限</label></div>
                    <div class="ctr-readonly ctr-readonly border">
                        <input class="form-control ctr-readonly border-radius-none border-0" type="date" autocomplete="off" name="resident_depend_date_{{$loop->iteration}}" id="resident_depend_date_{{$loop->iteration}}" 
                        value="{{$res ? date(CommonConst::DATE_FORMAT_2, strtotime($res->resident_depend_date)) : ''}}" maxlength="10" min="{{CommonConst::DATE_MIN}}" 
                        max="{{date(CommonConst::DATE_FORMAT_2, strtotime('+4 years')) }}" >
                    </div>
                </div>
            </div>
            @endforeach
            
        </div>
    </fieldset>
</div>
<div class="row col-md-10 px-3">
    <div class="row col-md-4 me-3 mb-2">
        <div class="bg-title text-center p-1"><label>健康診断書の写し</label></div>
        <div class="d-flex ctr-readonly flex-row border">
            <a class="flex-grow-1" style="{{!empty($app) && !empty($app->file_name_health_certificate) ? '' : 'pointer-events: none;' }}" 
                id="linkHealthCert" target="_blank"  href="{{ !empty($app) ? asset($app->folder_path . $app->folder_name_health_certificate . $app->file_name_health_certificate) :'' }}" >
                <input id="txtHealthCert" name="txtHealthCert" readonly class="form-control border-radius-none ctr-readonly  border-0" value="{{ $app ? $app->file_name_health_certificate:'' }}">
            </a>
        </div>
    </div>
</div>

<div class="row col-md-10 px-3">
    <div class="row col-md-4 me-3 mb-2">
        <div class="bg-title text-center p-1"><label>その他</label></div>
        <div class="ctr-readonly ctr-readonly border">
            <a class="flex-grow-1" style="{{!empty($app) && !empty($app->file_name_others) ? '' : 'pointer-events: none;' }}" 
                id="linkNameOrther" target="_blank"  href="{{ !empty($app) ? asset($app->folder_path . $app->folder_name_others . $app->file_name_others) :'' }}" >
                <input id="txtApp_NameOrther" name="txtApp_NameOrther" readonly class="form-control border-radius-none ctr-readonly border-0" value="{{ $app ? $app->file_name_others:'' }}">
            </a>
        </div>
    </div>
</div>

<div class="row col-md-10 px-3">
    <div class="row col-md-4 me-3 mb-2">
        <div class="bg-title text-center p-1"><label>(外国人雇用)通帳コピー</label></div>
        <div class="ctr-readonly ctr-readonly border">
            <a class="flex-grow-1" style="{{!empty($app) && !empty($app->file_name_passport_foreign) ? '' : 'pointer-events: none;' }}" 
                id="linkPassportForeign" target="_blank"  href="{{ !empty($app) ? asset($app->folder_path . $app->folder_name_passport_foreign . $app->file_name_passport_foreign) :'' }}" >
                <input id="txtPassportForeign" name="txtPassportForeign" readonly class="form-control border-radius-none ctr-readonly border-0" value="{{ $app ? $app->file_name_passport_foreign:'' }}">
            </a>
        </div>
    </div>
</div>
<div class="mb-4 col-md-10">
    <fieldset class="legend-box" style="min-height: 10rem;">
        <legend class="legend-title">◆資格者証一覧</legend>
        <div class="legend-body">
            @foreach($certif as $cer)
            <div class="row px-3">
                <div class="row col-md-4 me-3 mb-2">
                    <div class="bg-title text-center p-1"><label>資格者証</label></div>
                    <div class="ctr-readonly ctr-readonly border">
                        <a class="flex-grow-1" style="{{!empty($cer) && !empty($cer->file_name) ? '' : 'pointer-events: none;' }}" 
                            id="linkCertificateUp_{{$loop->iteration}}" target="_blank"  href="{{ !empty($cer) ? asset($cer->folder_path . $cer->folder_name . $cer->file_name) :'' }}" >
                            <input id="txtCertificateUp_{{$loop->iteration}}" name="txtCertificateUp_{{$loop->iteration}}" readonly class="form-control border-radius-none ctr-readonly border-0" value="{{ $cer ? $cer->file_name:'' }}">
                        </a>
                    </div>
                </div>
                <div class="row col-md-3 me-3 mb-2">
                    <div class="bg-title text-center p-1"><label>有効期限</label></div>
                    <div class="ctr-readonly ctr-readonly border">
                        <input class="form-control border-radius-none ctr-readonly border-0" autocomplete="off" type="date" name="certificate_date_{{$loop->iteration}}" id="certificate_date_{{$loop->iteration}}" 
                        value="{{$res ? date(CommonConst::DATE_FORMAT_2, strtotime($cer->certificate_date)) : ''}}" maxlength="10" >
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </fieldset>
</div>

<div class="d-flex col-md-5 mb-1 mt-3">
    <div class="me-5 flex-fill align-self-end"><label class="form-label mb-0">雇用契約書</label></div>
    @if(Gate::check('isAdmin') || (Gate::check('isHD')))
    <div class="ms-5">
        <a class="btn btn-info" type="button" href="{{ route('u05',['id' => $mode['idStaff']]) }}">雇用契約更新</a>
    </div>
    @endif
</div>

<div class="col-md-5 table-responsive mb-4">
    <table class="mb-2 table table-bordered table-border-color">
        <thead>
          <tr class="tr-head text-center">
            <th scope="col" class="w-c-150 p-2">作成日</th>
            <th scope="col" class="mw-c-250 text-center p-2">事業所名</th>
          </tr>
        </thead>
        <tbody i class="text-center">
            @foreach( $proUser as $proUser)
            <tr class="{{$proUser->{'U03-10_date'} ? '' : 'visually-hidden'}}">
                <td class="ctr-readonly p-2">{{$proUser->{'U03-10_date'} ? date(CommonConst::DATETIME_FORMAT, strtotime($proUser->{'U03-10_date'})) : ''}}</td>
                <td class="ctr-readonly p-2 text-start">
                    <a target="_blank" href="{{route('u0310.emplContract', ['id' => $mode['id'], 'verhis' => $proUser->ver_his])}}" class="text-decoration-underline">雇用契約書</a>
                </td>
            </tr>
            @endforeach
        </tbody>
      </table>
</div>

<label>サイン書類</label>
<div class="col-md-5 table-responsive mb-4">
    <table class="mb-2 table table-bordered table-border-color">
        <thead>
            <tr class="tr-head text-center">
                <th scope="col" class="w-c-150 p-2">作成日</th>
                <th scope="col" class="mw-c-250 text-center p-2">事業所名</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <tr>
                <td class="ctr-readonly p-2">{{$productUser->{'U03-6_date'} ? date(CommonConst::DATETIME_FORMAT, strtotime($productUser->{'U03-6_date'})) : ''}}</td>
                <td class="ctr-readonly p-2 text-start">
                    <a target="_blank" href="{{route('u036.showInviConfirm', ['id' => $mode['id']])}}" class="{{$productUser->{'U03-6_date'} ? 'text-decoration-underline' : 'disabled'}}">内定通知書</a>
                </td>
            </tr>
            <tr>
                <td class="ctr-readonly p-2">{{$productUser->{'U03-7_date'} ? date(CommonConst::DATETIME_FORMAT, strtotime($productUser->{'U03-7_date'})) : ''}}</td>
                <td class="ctr-readonly p-2 text-start">
                    <a target="_blank" href="{{route('u037.showAppli', ['id' => $mode['id']])}}" class="{{$productUser->{'U03-7_date'} ? 'text-decoration-underline' : 'disabled'}}">入職者申請</a>
                </td>
            </tr>
            @if($infoUsers->flg3)
                <tr>
                    <td class="ctr-readonly p-2">{{$productUser->{'U03-8_date'} ? date(CommonConst::DATETIME_FORMAT, strtotime($productUser->{'U03-8_date'})) : ''}}</td>
                    <td class="ctr-readonly p-2 text-start">
                        <a target="_blank" href="{{route('u038.privateCar', ['id' => $mode['id']])}}" class="{{$productUser->{'U03-8_date'} ? 'text-decoration-underline' : 'disabled'}}">自家用車通勤願</a>
                    </td>
                </tr>
            @endif
            @if($infoUsers->flg6 )
                <tr>
                    <td class="ctr-readonly p-2">{{$productUser->{'U03-9_date'} ? date(CommonConst::DATETIME_FORMAT, strtotime($productUser->{'U03-9_date'})) : ''}}</td>
                    <td class="ctr-readonly p-2 text-start">
                        <a target="_blank" href="{{route('u039.showAppliHouses', ['id' => $mode['id']])}}" class="{{$productUser->{'U03-9_date'} ? 'text-decoration-underline' : 'disabled'}}">住宅手当申請</a>
                    </td>
                </tr>
            @endif
            <tr>
                <td class="ctr-readonly p-2">{{$productUser->{'U03-11_date'} ? date(CommonConst::DATETIME_FORMAT, strtotime($productUser->{'U03-11_date'})) : ''}}</td>
                <td class="ctr-readonly p-2 text-start">
                    <a target="_blank" href="{{route('u0311.showDocSign', ['id' => $mode['id']])}}" class="{{$productUser->{'U03-11_date'} ? 'text-decoration-underline' : 'disabled'}}">誓約書</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="row col-md-10">
    <div class="col-md-8 mb-4">
        <fieldset class="legend-box" style="min-height: 10rem;">
        <legend class="legend-title">◆入社前手続き</legend>
        <div class="legend-body">
            <div class="row ms-1">
                @foreach ($proItem as $item)
                    <div class="form-check col-4 col-md-4 col-xl-2 mb-2">
                    <input class="form-check-input border border-dark" disabled type="checkbox" {{$item->selected == '1' ? 'checked ' : ''}} value="{{$item->id}}" id="proItem_{{$loop->iteration}}" name="proItem_[]">
                    <label class="form-check-label" for="proItem_{{$loop->iteration}}">{{$item->name}}</label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
        </fieldset>
        <div class="col-md-4 mt-3 py-1 mb-4">
            <div class="bg-title text-center p-1"><label>会社携帯番号</label></div>
            <div class="ctr-readonly border">
                <input id="txtComMobieNum" name="txtComMobieNum" readonly class="form-control ctr-readonly border-radius-none border-0" value="{{$infoUsers->company_mobile_number}}">
            </div>
        </div>
</div>


<div class="row col-md-10 px-3">
    <div class="row col-md-4 me-3 mb-2">
        <div class="bg-title text-center p-1"><label>1週間の所定労働時間</label></div>
        <div class="border ctr-readonly">
            <input style="text-overflow: ellipsis;" id="txtWork_hours_per_week" name="txtWork_hours_per_week" readonly class="form-control ctr-readonly border-radius-none border-0" value="{{$infoUsers->work_hours_per_week}}">
        </div>
    </div>
    <div class="row col-md-3 px-3 me-3 mb-2">
        <div class="bg-title text-center p-1"><label>見込額</label></div>
        <div class=" border ctr-readonly ">
            <input id="txtEstimated_amount" name="txtEstimated_amount" readonly class="form-control text-end ctr-readonly border-radius-none border-0" value="{{ $infoUsers->estimated_amount ? number_format($infoUsers->estimated_amount) : ''}}">
        </div>
    </div>
</div>

<div class="row col-md-10 px-3 mb-4">
    <div class="row col-md-4 me-3 mb-2">
        <div class="bg-title text-center p-1"><label>履歴書</label></div>
        <div class="border ctr-readonly">
            <a class="flex-grow-1" style="{{!empty($infoUsers) && !empty($infoUsers->file_name) ? '' : 'pointer-events: none;' }}" 
                id="linkNameOrther" target="_blank"  href="{{ !empty($infoUsers) ? asset($infoUsers->folder_path . $infoUsers->folder_name . $infoUsers->file_name) :'' }}" >
                <input id="txtNameOrther" name="txtNameOrther" readonly class="form-control border-radius-none ctr-readonly border-0" value="{{ $infoUsers ? $infoUsers->file_name:'' }}">
            </a>
        </div>
    </div>
</div>

<label>入職者情報 取扱項目</label>
<div>
    {{-- manager --}}
    <div class="form-check col-4 col-md-5 col-xl-2 my-3">
        <input class="form-check-input border border-dark" disabled type="checkbox" value="{{$infoUsers->manager}}" {{ $infoUsers ? ($infoUsers->manager == '1' ? 'checked' : '') : '' }} id="txtManager" name="txtManager">
        <label class="form-check-label" for="txtManager">役員</label>
    </div>
    {{-- manager_position --}}
    <div class="form-check col-4 col-md-5 col-xl-2 my-3">
        <input class="form-check-input border border-dark" disabled type="checkbox" value="{{$infoUsers->manage_position}}" {{ $infoUsers ? ($infoUsers->manage_position == '1' ? 'checked' : '') : '' }} id="txtManagerPosit" name="txtManagerPosit">
        <label class="form-check-label" for="txtManagerPosit">管理職</label>
    </div>
    {{-- flag_1 --}}
    <div class="form-check col-4 col-md-5 col-xl-2 mb-2 {{$ofce->flg1 == '1' ? '' : 'visually-hidden'}}">
        <input class="form-check-input border border-dark" disabled type="checkbox" value="1" name="flg1" id="flg1" {{$infoUsers ? ($infoUsers->flg1 == '1' ? 'checked' : '') : ''}}>
        <label class="form-check-label" for="flg1">労務担当</label>
    </div>
    {{-- Manager Buiness --}}
    <div class="form-check col-4 col-md-5 col-xl-2 mb-2">
        <input class="form-check-input border border-dark" disabled type="checkbox" name="txtMagBusines" id="txtMagBusines" {{$infoUsers ? ($infoUsers->manage_business == '1' ? 'checked' : '') : ''}} >
        <label class="form-check-label" for="txtMagBusines">業務担当</label>
    </div>
    {{-- Acountan --}}
    <div class="form-check col-4 col-md-5 col-xl-2 mb-2" {{$ofce->flg1 == '1' ? '' : 'visually-hidden'}}>
        <input class="form-check-input border border-dark" disabled type="checkbox"  name="txtAcountant" id="txtAcountant" {{$infoUsers ? ($infoUsers->accountant == '1' ? 'checked' : '') : ''}}>
        <label class="form-check-label" for="txtAcountant">経理担当</label>
    </div>
    {{-- Company Car --}}
    <div class="form-check col-4 col-md-5 col-xl-2 mb-2">
        <input class="form-check-input border border-dark" disabled type="checkbox" name="txtComCar" id="txtComCar" {{$infoUsers ? ($infoUsers->company_car == '1' ? 'checked' : '') : ''}}>
        <label class="form-check-label" for="txtComCar">社用車通勤</label>
    </div>
    {{-- flag_3 --}}
    <div class="form-check col-4 col-md-5 col-xl-2 mb-2 {{$ofce->flg3 == '1' ? '' : 'visually-hidden'}}">
        <input class="form-check-input border border-dark" disabled type="checkbox" value="{{$infoUsers->flg3}}" name="flg3" id="flg3" {{$infoUsers ? ($infoUsers->flg3 == '1' ? 'checked' : '') : ''}}>
        <label class="form-check-label" for="flg3">自家用車通勤</label>
    </div>
    {{-- flag_4 --}}
    <div class="form-check col-4 col-md-5 col-xl-2 mb-2 {{$ofce->flg4 == '1' ? '' : 'visually-hidden'}}">
        <input class="form-check-input border border-dark" disabled type="checkbox" value="{{$infoUsers->flg4}}" name="flg4" id="flg4" {{$infoUsers ? ($infoUsers->flg4 == '1' ? 'checked' : '') : ''}}>
        <label class="form-check-label" for="flg4">外国人雇用</label>
    </div>
    {{-- flag_5 --}}
    <div class="form-check col-4 col-md-5 col-xl-2 mb-2 {{$ofce->flg5 == '1' ? '' : 'visually-hidden'}}">
        <input class="form-check-input border border-dark" disabled type="checkbox" value="{{$infoUsers->flg5}}" name="flg5" id="flg5" {{$infoUsers ? ($infoUsers->flg5 == '1' ? 'checked' : '') : ''}}>
        <label class="form-check-label" for="flg5">家族（扶養）手当</label>
    </div>
    {{-- flag_6 --}}
    <div class="form-check col-4 col-md-5 col-xl-2 mb-2 {{$ofce->flg6 == '1' ? '' : 'visually-hidden'}}">
        <input class="form-check-input border border-dark" disabled type="checkbox" value="{{$infoUsers->flg6}}" name="flg6" id="flg6" {{$infoUsers ? ($infoUsers->flg6 == '1' ? 'checked' : '') : ''}} >
        <label class="form-check-label" for="flg6">住宅手当</label>
    </div>
    {{-- flag_7 --}}
    <div class="form-check col-4 col-md-5 col-xl-2 mb-2 {{$ofce->flg7 == '1' ? '' : 'visually-hidden'}}">
        <input class="form-check-input border border-dark" disabled type="checkbox" value="{{$infoUsers->flg7}}" name="flg7" id="flg7" {{$infoUsers ? ($infoUsers->flg7 == '1' ? 'checked' : '') : ''}}>
        <label class="form-check-label" for="flg7">保育料助成</label>
    </div>
    {{-- flag_8 --}}
    <div class="form-check col-4 col-md-5 col-xl-2 mb-2" {{$ofce->flg8 == '1' ? '' : 'visually-hidden'}}">
        <input class="form-check-input border border-dark" disabled type="checkbox" value="{{$infoUsers->flg8}}" name="flg8" id="flg8" {{$infoUsers ? ($infoUsers->flg8 == '1' ? 'checked' : '') : ''}}>
        <label class="form-check-label" for="flg8">障害者（本人）</label>
    </div>
    {{-- flag_9 --}}
    <div class="form-check col-4 col-md-5 col-xl-2 mb-2 {{$ofce->flg9 == '1' ? '' : 'visually-hidden'}}">
        <input class="form-check-input border border-dark" disabled type="checkbox" value="{{$infoUsers->flg9}}" name="flg9" id="flg9" {{$infoUsers ? ($infoUsers->flg9 == '1' ? 'checked' : '') : ''}}>
        <label class="form-check-label" for="flg9">障害者（扶養家族）</label>
    </div>
    
</div>
@include('users.m03.background')

@endsection

