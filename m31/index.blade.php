@extends('layouts.layout')
<!-- m03-1 -->
@section('title', 'スタッフ一覧')
@push('scripts')
<script src="{{ asset('js/users/m03.js?v=' . time()) }}"></script>
@endpush
@section('content')
<div class="row align-items-center mb-2">
  <div class="d-sm-flex flex-wrap align-items-center">
    <div class="d-sm-flex align-items-center flex-wrap mb-lg-0 mb-2 me-sm-2">
      <label class="me-2">入社月</label>
      <div  class="w-c-150">
        <input id="txtDate" name="txtDate" type="month" autocomplete="off" maxlength="10" min="" max="" value="" class="form-control border-radius-none">
      </div>
    </div>


    <div class="d-sm-flex align-items-center flex-wrap mb-sm-0 mb-2 me-sm-2">
      <label class="me-2">事業所</label>
      <div>
        <select class="form-select border-radius-none {{$checkHd ? 'select-disable ctr-readonly' : ''}}"
          {{$checkHd ? 'tabindex=-1' : ''}} id="cmbOffice" name="cmbOffice">
          <option></option>
          @foreach ($ofces as $item)
          <option value="{{ $item->office_cd }}" {{ old('cmbOffice', $item->office_name) }}
            {{ $ofceCur->office_cd == $item->office_cd ? 'selected' : '' }}>
            {{ $item->office_name }}
          </option>
          @endforeach
        </select>
      </div>
    </div>


    <div class="d-sm-flex align-items-center flex-wrap mb-sm-0 mb-2 me-sm-2">
      <label class="me-2">所属</label>
      <div>
        <select class="form-select border-radius-none " id="cmbBelong" name="cmbBelong">
          <option></option>
          @foreach ($belongs as $item)
          @if($ofceCur->office_cd == $item->office_cd )
          <option value="{{ $item->belong_cd }}" {{ old('office_name', $item->belong_cd) }}>
            {{ $item->belong_name }}
          </option>
          @endif
          @endforeach
        </select>
      </div>
    </div>


    <div class="d-sm-flex align-items-center flex-wrap mb-lg-0 mb-2 me-sm-2">
      <label class="me-2">社員番号</label>
      <div>
        <input id="txtIdStaff" name="txtIdStaff" type="text" autocomplete="off" maxlength="7" value="" class="form-control border-radius-none">
      </div>
    </div>


    <div class="d-sm-flex align-items-center flex-wrap mb-lg-0 mb-2 me-sm-2">
      <label class="me-2">氏名</label>
      <div>
        <input id="txtNameStaff" name="txtNameStaff" type="text" autocomplete="off" maxlength="30" value="" class="form-control border-radius-none">
      </div>
    </div>


    <div class="d-sm-flex align-items-center flex-wrap mb-sm-0 mb-2 me-sm-2">
      <label class="me-2">ステータス</label>
      <div>
        <select class="form-select border-radius-none " id="cmbWStatus" name="cmbWStatus">
          <option></option>
          @foreach ($mstWorkStatus as $item)
          <option value="{{ $item->item_cd }}">
            {{ $item->item_name }}
          </option>
          @endforeach
        </select>
      </div>
    </div>


    <button type="button" class="btn btn-secondary" id="" name="" value="" onclick="ShowSearch(1)" tabindex="-1">ssssssss</button>
    <button type="submit" class="btn btn-secondary" id="btnSrc" name="btnSrc" value="search" tabindex="-1">検索</button>
    <div class="align-items-center">
      <a class="btn btn-blue" onclick="ShowPopMessage()">通知</a>
    </div>
    <div class="align-items-center">
      <a class="btn btn-blue" href="{{ route('m04.edit', ['id' => $item->id ?? '']) }}">新規</a>
    </div>


    <div class="text-end" tabindex="-1">
      <button class="btn-none ms-3 h2 h-100" type="button" value="exportCSV" onclick="ShowConfirmExport('btnSrc')">
        <i class="bi bi-box-arrow-right d-flex"></i>
      </button>
    </div>
  </div>
</div>


<!-- users->links('layouts.pagination')S -->


<div>
  <div class="table-responsive">
    <table class="table table-bordered table-border-color">
      <thead>
        <tr class="tr-head text-center">
          <th scope="col" class="w-c-50 text-center align-middle">
            <input class="form-check-input" type="checkbox" value="1" id="checkAll">
          </th>
          <th scope="col" class="w-c-100">入社月</th>
          <th scope="col" class="w-c-100">事業所名</th>
          <th scope="col" class="">所属名</th>
          <th scope="col" class="w-c-250">社員番号</th>
          <th scope="col" class="w-c-100">氏名</th>
          <th scope="col" class="w-c-100">ステータス</th>
          <th scope="col" class="w-c-100">メールアドレス</th>
          <th scope="col" class="w-c-150">電話番号</th>
          <th scope="col" class="w-c-100"> 会社携帯番号</th>
          <th scope="col" class="w-c-50"></th>
        </tr>
      </thead>
      <tbody class="tr-bg">
        @foreach ($users as $item)
        @if(isset($item->idInfo))
        <tr class="align-middle">
          <input type="hidden" name='idInfo' id="idInfo" value="{{$item->idInfo}}">
          <td scope="row" class="text-center align-middle">
            <input class="form-check-input checkboxItem" id="{{$item->idInfo}}" type="checkbox" value="1">
            {{$item->idInfo}}
          </td>
          <td scope="row">{{date_format(new DateTime($item->date_join), CommonConst::DATE_FORMAT_3) }} </td>
          <td scope="row">{{$item->OffiInfo}}</td>
          <td scope="row">{{$item->BelInfo}}</td>
          <td scope="row">{{$item->users_number}}</td>
          <td scope="row">{{$item->first_name}} {{$item->last_name}} </td>
         
          <td scope="row">
          @foreach ($mstWorkStatus as $cmb)
            {{$item->work_status == $cmb->item_cd ? $cmb->item_name : '' }}
          @endforeach
          </td>


          <td scope="row">{{$item->emailInfo}}</td>
          <td scope="row">{{$item->tel_number}}</td>
          <td scope="row">{{$item->company_mobile_number}}</td>
          </td>
          <td scope="row" class="text-center">
            <button type="button" class="btn btn-blue" onclick="">承認</button>
          </td>
        </tr>
        @endif
        @endforeach
      </tbody>
    </table>
  </div>
</div>








@if ($msgErr)
<div class="text-center"><label class="err-msg">{{ $msgErr }}</label></div>
@endif
<input type="hidden" value="" id="InfoCheck" name="InfoCheck">
<input type="hidden" value="" id="txtMessage" name="txtMessage">
@endsection


<!-- Modal Message -->
<div class="modal fade" id="mdMessage" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-body text-center">
        <div class="container-fluid py-3 px-3">
          <div class="py-2">
            <div class="float-start">
              <input class="border-0 py-2 float-left" id="popMessage" name="popMessage" value="">
            </div>
            <textarea class="form-control" id="txtMes" name="txtMes" style="height: 150px"></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-center border-0">
        <button type="button" id="btnSend" class="btn btn-blue btn-box-shadow border-0 px-3" onclick="SaveMessage(`{{route('m03.saveMessage')}}`)">送信</button>
        <button type="button" id="btnCancel" class="btn bg-white bg-opacity-75 btn-box-shadow border-0 px-2" onclick=" HideModal('mdMessage');">キャンセル</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal Message -->
