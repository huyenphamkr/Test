@extends('layouts.layout')

@section('title', '退職手続き')

@push('scripts')
    <script src="{{ asset('js/users/u07.js?v=' . time()) }}"></script>
@endpush

@section('content')
<style>
    .legend-body {
        padding-right: 0rem !important;
        padding-bottom: 0rem !important;
    }

    .tablegray>tbody>tr>td:first-child {
        background-color: var(--grey-medium);
    }
</style>
@php
use App\Common\CommonConst;
$readonly = "readonly tabindex=-1";
$disabledSelect = 'select-disable ctr-readonly';
$arrPro = ($item) ? explode(',', $item->procedure_items) : [];
$arrProRetire = [];
$mode = "Insert";
if ($itemRetire) {
    $arrProRetire = ($itemRetire) ? explode(',', $itemRetire->retire_procedure_items) : [];
    $mode = "Upd";
}
$visibleSave = 0;
$statusRetire =  ($itemRetire) ?  $itemRetire->status_user  : '';
$disabled= "disabled readonly";
if($statusRetire === CommonConst::U07_STATUS_保存 ||  $statusRetire === '' ){
    $visibleSave = 1;
    $disabled = "";
}
$ButtonVissible = $user && $user->id  !=null ? '' : 'visually-hidden';
@endphp
<div class="edit-content">
    <input type="hidden"  id="hidModeButton" name="hidModeButton" value="" >
    <input type="hidden"  id="hidInfoUserId" name="hidInfoUserId" value="{{$item->id ?? ''}}" >
    <input type="hidden"  id="hidStaffId" name="hidStaffId" value="{{$user ? $user->id : '' }}" >
    <input type="hidden" id="linkReload" name="linkReload" value="{{route('u07.retireProc')}}" >
    <input type="hidden" id="hidRouteSaveU07" name="hidRouteSaveU07" value="{{route('u07.save')}}">
    <div>
        @if($visibleSave  ===  1)
            <button type="button" class="btn btn-blue mb-2" onclick="Save('{{route('u07.save')}}', 'save')">保存</button>
        @endif      
        <button type="button" class="btn btn-outline-primary mb-2" onclick="BackPrev('{{route('u02')}}')" >キャンセル</button>
        <a type="button" class="btn btn-success float-sm-end float-none mb-2 {{$ButtonVissible}} " href="{{route('u073.retireProcTop',['id'=>$user ? $user->id : '' ])}}"  target="_blank">退職者申請状況</a>
    </div>
   
    <div class="row mb-2 mb-xl-0 mb-md-4">
        <div class="col-xl-6">
            <div class="table-responsive">
                <table class="table tablegray table-bordered table-border-color">
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
                            <td class="text-center">作成者</td>
                            <td>
                                <label id="lbAuthorId">{{ $itemRetire->name_author ?? '' }}</label>
                            </td>
                            <td>
                                <label id="lbAuthorDate">
                                    {{ $itemRetire && $itemRetire->author_date ? date_format(new DateTime($itemRetire->author_date), CommonConst::DATETIME_FORMAT) : "" }}
                                </label>
                            <td class="text-center w-c-50 border-0 border-bottom border-top border-white">       
                                @if($visibleSave  ===  1)        
                                    <button type="button" class="btn btn-blue" onclick="Save('{{route('u07.save')}}', '{{ CommonConst::C_作成者 }}')" >送信</button>
                                @endif 
                            </td>
                            <td class="text-center w-c-50 border-0 border-bottom border-top border-white">                         
                                @if($mode == "Upd" && $statusRetire === CommonConst::U07_STATUS_保存)
                                <button type="button" class="btn btn-danger" onclick="ConfirmDel(`{{ $item->id ?? '' }}`)" >削除</button>
                                @endif                     
                            </td>
                        </tr>
                        <tr class="align-middle text-center">
                            <td class="text-center">申請者</td>
                            <td>
                                <label id="lbApplicantId">{{ $itemRetire->name_applicant ?? '' }}</label>
                            </td>
                            <td>
                                <label id="lbApplicantDate">
                                    {{ empty($itemRetire->applicant_date) ? "" : date_format(new DateTime($itemRetire->applicant_date), CommonConst::DATETIME_FORMAT) }}
                                </label>
                            </td>
                            <td class="text-center w-c-50 border-0 border-bottom border-top border-white"></td>
                            <td class="text-center w-c-50 border-0 border-bottom border-top border-white"></td>
                        </tr>
                        <tr class="align-middle text-center">
                            <td class="text-center">1次承認</td>
                            <td>
                                <label id="lbAdmin1Id">{{ $itemRetire->name_admin1 ?? ''}}</label>
                            </td>
                            <td>
                                <label id="lbAdmin1Date">
                                    {{ empty($itemRetire->admin1_date) ? "" : date_format(new DateTime($itemRetire->admin1_date), CommonConst::DATETIME_FORMAT) }}
                                </label>
                            </td>
                            <td class="text-center w-c-50 border-0 border-bottom border-top border-white">
                                @can('isCensor', $OneList)
                                    @if($mode == "Upd" && $statusRetire === CommonConst::U07_STATUS_1次 )
                                        <button type="button" class="btn btn-blue" onclick="Save('{{route('u07.save')}}', '{{ CommonConst::C_1次承認 }}')" >承認</button>
                                    @endif  
                                @endcan
                            </td>
                            <td class="text-center w-c-50 border-0 border-bottom border-top border-white">
                                @can('isCensor', $OneList)
                                    @if($mode == "Upd" && $statusRetire === CommonConst::U07_STATUS_1次)
                                        <button type="button" class="btn btn-danger" onclick="Save('{{route('u07.save')}}', '{{ CommonConst::C_1次否認 }}')" >否認</button>
                                    @endif 
                                @endcan 
                            </td>
                        </tr>
                        <tr class="align-middle text-center">
                            <td class="text-center">2次承認</td>
                            <td>
                                <label id="lbAdmin2Id">{{ $itemRetire->name_admin2 ?? '' }}</label>
                            </td>
                            <td>
                                <label id="lbAdmin2Date">
                                    {{empty($itemRetire->admin2_date) ? "" : date_format(new DateTime($itemRetire->admin2_date), CommonConst::DATETIME_FORMAT)}}
                                </label>
                            </td>
                            <td class="text-center w-c-50 border-0 border-bottom border-top border-white">
                                @can('isCensor', $SecondList)
                                    @if($mode == "Upd" && $statusRetire=== CommonConst::U07_STATUS_2次 )
                                        <button type="button" class="btn btn-blue" onclick="Save('{{route('u07.save')}}', '{{ CommonConst::C_2次承認 }}')" >承認</button>
                                    @endif  
                                @endcan
                            </td>
                            <td class="text-center w-c-50 border-0 border-bottom border-top border-white">
                                @can('isCensor', $SecondList)
                                    @if($mode == "Upd" && $statusRetire === CommonConst::U07_STATUS_2次  )
                                    <button type="button" class="btn btn-danger" onclick="Save('{{route('u07.save')}}', '{{ CommonConst::C_2次否認 }}')"  >否認</button>
                                    @endif  
                                @endcan
                            </td>
                        </tr>
                        <tr class="align-middle text-center">
                            <td class="text-center">HD承認</td>
                            <td>
                                <label id="lbHdId">{{ $itemRetire->name_hd  ?? ''}}</label>
                            </td>
                            <td>
                                <label id="lbHdDate">
                                    {{ empty($itemRetire->hd_date) ? "" : date_format(new DateTime($itemRetire->hd_date), CommonConst::DATETIME_FORMAT) }}
                                </label>
                            </td>

                            <td class="text-center w-c-50 border-0 border-bottom border-top border-white">
                                @if($mode == "Upd" && $statusRetire === CommonConst::U07_STATUS_HD && Gate::check('isHD'))
                                <button type="button" class="btn btn-blue" onclick="Save('{{route('u07.save')}}', '{{ CommonConst::C_HD承認 }}')"  >承認</button>
                                @endif  
                            </td>
                            <td class="text-center w-c-50 border-0 border-bottom border-top border-white">
                                @if($mode == "Upd" && $statusRetire === CommonConst::U07_STATUS_HD && Gate::check('isHD'))
                                <button type="button" class="btn btn-danger" onclick="Save('{{route('u07.save')}}', '{{ CommonConst::C_HD否認 }}')" >否認</button>
                                @endif  
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
                <textarea name="txtNote" id="txtNote" class="form-control border-radius-none align-top "  maxlength="1000" style="min-height: 270px;">{{$itemRetire ? $itemRetire->note : '' }}</textarea>
            </div>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-xl-2 col-md-4">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>退職予定日</label></div>
            <input {{ $disabled }} type="date" name="txtRetire_date" id="txtRetire_date" autocomplete="off" class="form-control border-radius-none " maxlength="10" min="{{ CommonConst::DATE_MIN }}" max="{{ CommonConst::DATE_MAX }}" 
            value="{{ !empty($itemRetire) && $itemRetire->retire_date != '' ? date(CommonConst::DATE_FORMAT_2, strtotime($itemRetire->retire_date)) : '' }}">
        </div>
    </div>

    <div class="row">
        <div class="col-xl-2 col-md-4 mb-2">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>氏名</label></div>
            <input disabled readonly name="txtName" id="txtName" type="text" autocomplete="off" class="form-control border-radius-none "  maxlength="30" value="{{$item ? $item->first_name . '　' . $item->last_name : '' }}">
        </div>
      
        <div class="col-xl-2 col-md-4 mb-2">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>フリガナ</label></div>
            <input disabled readonly name="txtFirstFurigana" id="txtFirstFurigana" type="text" autocomplete="off" class="form-control border-radius-none " value="{{$item ? $item->first_furigana . '　' . $item->last_furigana : '' }}" maxlength="30" >
        </div>
       
        <div class="col-xl-2 col-md-4 mb-2">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>ステータス</label></div>
            <select name="cmbWorkStatus" id="cmbWorkStatus" class="form-select border-radius-none  select-disable ctr-readonly" >
                @foreach ($mstWorkStatus as $cmb)
                <option value="{{ $cmb->item_cd }}" {{ $item && $item->work_status == $cmb->item_cd ? 'selected' : '' }}>{{ $cmb->item_name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-xl-2 col-md-4 mb-5">
            <button type="button" class="btn btn-blue" onclick="ShowSearch('',searchUser,'{{$ofce->office_cd}}')" >検索</button>
        </div>
    </div>

    <div class="row mb-4">
        <div class="mt-2">
            <div class="p-1 fs-25"><label>所属</label></div>
        </div>
        <div class="col-xl-3 col-md-6 mb-2">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>事業所</label></div>
            <select name="cmbOffice_cd" id="cmbOffice" class="form-select border-radius-none select-disable ctr-readonly" tabindex="-1">
                @foreach ($ofces as $o)
                <option value="{{ $o->office_cd }}" {{ $ofce->office_cd == $o->office_cd ? 'selected' : '' }}>{{ $o->office_name }}
                </option>
                @endforeach     
            </select>
        </div>
        <div class="col-xl-3 col-md-6 mb-2">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>所属</label></div>
            <select name="cmbBelong_cd" id="cmbBelong" class="form-select border-radius-none select-disable ctr-readonly" tabindex="-1" >
                @foreach ($belongs as $belong)
                    <option value="{{ $belong->belong_cd }}" {{ $item && $item->belong_cd == $belong->belong_cd ? 'selected' : '' }}>
                        {{ $belong->belong_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-xl-2 col-md-4 mb-2">
            <div class="bg-title text-center p-1"><label>役職</label></div>
            <input disabled readonly value="{{ $item ? $item->position : ''}}"  type="text" name="txtPosition" id="txtPosition" autocomplete="off" class="form-control border-radius-none " maxlength="30" >
        </div>
        <div class="col-xl-2 col-md-4 mb-2">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>正社員・パート</label></div>
            <select name="cmbWork_time_flag" id="cmbWork_time_flag" class="form-select border-radius-none select-disable ctr-readonly" tabindex="-1" >
                @foreach ($mstWorkTime as $cmb)
                <option value="{{ $cmb->item_cd }}" {{ $item && $item->work_time_flag == $cmb->item_cd ? 'selected' : '' }}>{{ $cmb->item_name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-xl-2 col-md-4 mb-2">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>社員番号</label></div>
            <input  disabled readonly value="{{ $item ? $item->users_number : ''}}"  type="text" name="users_number" id="users_number" autocomplete="off" class="form-control border-radius-none allow_numeric pad-0 " maxlength="7" >
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-8 mb-xl-0 mb-md-2" >
            <fieldset class="legend-box" disabled>
                <legend class="legend-title">入社前手続き</legend>
                <div class="legend-body" >
                    <div class="row ms-1" id="ListProItem">
                        @foreach ($prods as $prod)
                        @if ($ofce->office_cd == $prod->office_cd && $prod->selected == '1')
                        <div class="form-check col-4 col-md-3 col-xl-2 mb-2">
                            <input class="form-check-input" type="checkbox"
                            @foreach ($arrPro as $idPro) 
                            {{$idPro == $prod->id  ? 'checked' : '' }} 
                            @endforeach  value={{ $prod->id }} id="ChkProd_{{ $loop->iteration }}" name="ChkProd[]">
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
            <input type="text" id="txtCompany_mobile_number" name="txtCompany_mobile_number" autocomplete="off" class="form-control border-radius-none allow_numeric " 
            disabled readonly maxlength="20"  value="{{ $item ? $item->company_mobile_number : ''}}">
        </div>
        <input type="hidden" id="hidListProItem" name="hidListProItem" value="{{$item && $item->procedure_items ? $item->procedure_items : ''}} ">
    </div>
    <div class="row mb-4">
        <div class="col-xl-8 mb-xl-0 mb-md-2">
            <fieldset class="legend-box" {{ $disabled }}>
                <legend class="legend-title">返却確認</legend>
                <div class="legend-body">
                    <div class="row ms-1" id="ListProcRetire">
                        @foreach ($prods as $prod)
                        @if ($ofce->office_cd == $prod->office_cd && $prod->selected == '1')
                        <div class="form-check col-4 col-md-3 col-xl-2 mb-2">
                            <input class="form-check-input" type="checkbox"
                            @foreach ($arrProRetire as $idPro) 
                            {{$idPro == $prod->id  ? 'checked' : '' }} 
                            @endforeach  value="{{ $prod->id }}" id="ChkRetireProd_{{ $loop->iteration }}" name="ChkRetireProd[]">
                            <label class="form-check-label" id="lbRetireProd_{{ $prod->id }}" for="Retireprod__{{ $loop->iteration }}">{{ $prod->name }}</label>
                            <input type="hidden" value="{{$prod->id}}" id="hidRetireProd_{{$loop->iteration}}" name="RetireProd[]">
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-xl-3 col-md-4 mb-2">
            <div class="bg-title text-center p-1"><span class="note">*</span><label>退職届</label></div>
            <div class="position-relative ">
                <a target="_blank" id="linkRetire" href="{{$itemRetire && $itemRetire->retire_file_name ? asset($itemRetire->retire_folder_path . $itemRetire->retire_folder_name . $itemRetire->retire_file_name) : '' }}" class="{{$itemRetire && $itemRetire->retire_file_name ? '' : 'disabled'}}">
                    <input id="txtRetire" name="txtRetire" class="form-control border-radius-none ctr-readonly  {{$itemRetire && $itemRetire->retire_file_name ? 'cursor-pointer' : ''}}" 
                    readonly value="{{$itemRetire && $itemRetire->retire_file_name ? $itemRetire->retire_file_name : ''}}" />
                </a>
                @if ($visibleSave == '1')
                    <div id="iconRetire" {{ $disabled }}>
                        <button type="button" class="icon-inp ctr-readonly  {{$itemRetire && $itemRetire->retire_file_name ? 'icon-active' : ''}}" onclick="ShowFileDialog('Retire')">
                            <i class="bi bi-paperclip"></i>
                        </button>
                        <button type="button" class="icon-inp ctr-readonly text-danger  {{$itemRetire && $itemRetire->retire_file_name ? '' : 'visually-hidden'}} " 
                        onclick="ClearFile('Retire')">
                            <i class="bi bi-x-circle-fill"></i>
                        </button>
                    </div>
                @endif
            </div>  
            <input id="curRetire" name="curRetire" type="hidden" value=" {{ $itemRetire && $itemRetire->retire_file_name ? 1 : 0 }}">
            <input id="fileRetire" name="fileRetire" type="file" class="visually-hidden" onchange="ChangeFile('Retire',-1,'info_users/retire')" />
            <input id="tmpRetire" name="tmpRetire" type="hidden" />
            <input id="oldRetire" name="oldRetire" class="stt-row" type="hidden"  value="{{$itemRetire && $itemRetire->retire_file_name ? $itemRetire->retire_folder_name . $itemRetire->retire_file_name : '' }}" />
            <input type="hidden" id="hidRetireId" name="hidRetireId" value="">
        </div>
    </div>
    <div class="row mb-2">
        <div class="mt-2">
            <div class="p-1 fs-25"><label>念書</label></div>
        </div>
        <div class="col-xl-3 col-md-4 m-3">        
            <a type="button" class="btn btn-blue {{$ButtonVissible}}" onclick="checkChange()">詳細</a>
            <input type="hidden" id="hidUrlU072" name="hidUrlU072" value="{{route('u072.showMemo',['id'=>$user ? $user->id : '' ])}}">
            <span>更新日時：</span>   {{ empty($LetterRetireInfoUsers->updated_at) ? "" : date_format(new DateTime($LetterRetireInfoUsers->updated_at), CommonConst::DATETIME_FORMAT) }}    
            <input type="hidden" id="hidU07_2Date" name="hidU07_2Date" value="{{ empty($LetterRetireInfoUsers->updated_at) ? "" : date_format(new DateTime($LetterRetireInfoUsers->updated_at), CommonConst::DATETIME_FORMAT) }} ">
        </div>   
    </div>
</div>
@include('layouts.search')
@endsection