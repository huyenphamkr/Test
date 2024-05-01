@extends('layouts.layout')


@section('title', 'ホーム')


@push('scripts')
<script src="{{ asset('js/users/u01_homepage.js?v=' . time()) }}"></script>
@endpush


@section('content')
<style>
    table>tbody>tr {
        vertical-align: middle;
    }

    .tbl-none-border-y>tbody>tr>td {
        border-bottom-style: hidden;
        border-top-style: hidden;
    }

    .table-custum-boder>tbody>tr:not(:last-child)>td {
        border-bottom-style: hidden !important;
    }

    .table-custum-boder>tbody>tr>td:first-child {
        border-right-style: hidden !important;
    }

    .tbl-scrollbar {
        position: relative;
        height: 287px;
        overflow: auto;
    }

    .tbl-wrapper-scroll-y {
        display: block;
    }

    .h-cus {
        height: 287px;
    }
    /* .bg-bold{
        opacity:1 !important;
    } */
</style>
@php
    $totalU03 = $totalU05 = $totalU07 = $totalM03 = 0;
    $fullName = "";
    foreach ($user as $key => $item){
        switch ($item->notices_type) {
            case 'U05':
                $totalU05 = $item->totalType;
                break;
            case 'U03':
                $totalU03 = $item->totalType;
                break;
            case 'U07':
                $totalU07 = $item->totalType;
                break;
            case 'M03':
                $totalM03 = $item->totalType;
                break;
        }
        if($key ==0) $fullName = $item->first_name.$item->last_name;
    }
@endphp
<div class="row justify-content-between">
    <div class="col-4 mb-2">
        <button type="button" class="btn btn-blue">社員証表示</button>
    </div>
    <div class="col-4 text-end mb-2">
        <label class="fw-bold">{{ $fullName }} 様 </label>
    </div>
</div>
<div class="row">
    <div class="col-xl-7 col-md-7 col-sm-12 col-12 mb-md-0 mb-sm-2 mb-2">
        <div id="tblU01" class="table-responsive tbl-scrollbar tbl-wrapper-scroll-y">
            <table class="table table-bordered table-border-color">
                <thead class="sticky-top top--1">
                    <tr class="tr-head bg-bold">
                        <th scope="col" colspan="2" class="mw-c-300 fw-normal ps-4">通知</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notices as $item)
                    @if($item->notices_type == 'U03')
                    <tr>
                        <td class="mw-c-150 w-c-150">
                            <div class="text-center py-1">
                                {{ date(CommonConst::DATE_FORMAT_3,strtotime($item->notices_date)) }}
                            </div>
                        </td>
                        <td class="mw-c-300">
                            {{ $item->office_short_name }}
                            {{ $item->belong_name }}
                            {{ $item->firstNCreate}}
                            {{ $item->lastNCreate}}「入社手続き」の申請があります。
                            <a href="" class="link-primary"><u>通知</u></a>
                            <a href="https://www.w3schools.com"><u>Visit W3Schools.com!</u></a>
                        </td>
                    </tr>
                    @endif
                    @if($item->notices_type == 'U05')
                    <tr>
                        <td class="mw-c-150 w-c-150">
                            <div class="text-center py-1">
                                {{ date(CommonConst::DATE_FORMAT_3,strtotime($item->notices_date)) }}
                            </div>
                        </td>
                        <td class="mw-c-300">
                            {{ $item->office_short_name }}
                            {{ $item->belong_name }}
                            {{ $item->firstNCreate}}
                            {{ $item->lastNCreate}}「追加・変更手続き」の申請があります。  
                            <a href="" class="link-primary">通知</a>
                        </td>
                    </tr>
                    @endif
                    @if($item->notices_type == 'U07')
                    <tr>
                        <td class="mw-c-150 w-c-150">
                            <div class="text-center py-1">
                                {{ date(CommonConst::DATE_FORMAT_3,strtotime($item->notices_date)) }}
                            </div>
                        </td>
                        <td class="mw-c-300">
                            {{ $item->office_short_name }}
                            {{ $item->belong_name }}
                            {{ $item->firstNCreate}}
                            {{ $item->lastNCreate}}「退職手続き」の申請があります。
                            <a href="" class="link-primary">通知</a>
                        </td>
                    </tr>
                    @endif
                    @if($item->notices_type == 'M03')
                    <tr>
                        <td class="mw-c-150 w-c-150">
                            <div class="text-center py-1">
                                {{ date(CommonConst::DATE_FORMAT_3,strtotime($item->notices_date)) }}
                            </div>
                        </td>
                        <td class="mw-c-300">
                            {{ $item->firstNCreate}}
                            {{ $item->lastNCreate}}
                            {{ $item->notices_content }}
                            「追加・変更手続き」の申請があります。
                            <a href="" class="link-primary">通知</a>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                    <tr>
                        <td class="mw-c-150 w-c-150">
                            <div class="text-center py-1">2023/12/07</div>
                        </td>
                        <td class="mw-c-300">XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX</td>
                    </tr>
                    <tr>
                        <td class="mw-c-150 w-c-150">
                            <div class="text-center py-1">2023/12/07</div>
                        </td>
                        <td class="mw-c-300">XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX</td>
                    </tr>


                    <tr>
                        <td class="mw-c-150 w-c-150">
                            <div class="text-center py-1">2023/12/07</div>
                        </td>
                        <td class="mw-c-300">XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX</td>
                    </tr>
                    <tr>
                        <td class="mw-c-150 w-c-150">
                            <div class="text-center py-1">2023/12/07</div>
                        </td>
                        <td class="mw-c-300">5555555555555555555555</td>
                    </tr>
                    <tr>
                        <td class="mw-c-150 w-c-150">
                            <div class="text-center py-1">2023/12/07</div>
                        </td>
                        <td class="mw-c-300">cioi</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-xl-5 col-md-5 col-sm-12 col-12">
        <div class="table-responsive h-cus" style="overflow: hidden;">
            <table class="table table-bordered table-border-color table-custum-boder">
                <thead>
                    <tr class="tr-head">
                        <th scope="col" colspan="2" class="mw-c-300 fw-normal ps-4">現在進行中の手続</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="w-c-150">
                            <div class="ps-3 py-1">
                                <label>追加・変更</label>
                            </div>
                        </td>
                        <td class="mw-c-300">
                            <label>{{ $totalU05 }}件</label>
                        </td>
                    </tr>
                    <tr>
                        <td class="w-c-150">
                            <div class="ps-3 py-1">
                                入社手続き
                            </div>
                        </td>
                        <td class="mw-c-300">
                            <label>{{ $totalU03 }}件</label>
                        </td>
                    </tr>
                    <tr>
                        <td class="w-c-150">
                            <div class="ps-3 py-1">
                                退職手続き
                            </div>
                        </td>
                        <td class="mw-c-300">
                            <label>{{ $totalU07 }}件</label>
                        </td>
                    </tr>
                    <tr>
                        <td class="w-c-150">
                            <div class="ps-3 py-1">
                                &nbsp;
                            </div>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="w-c-150">
                            <div class="ps-3 py-1 mt-1">
                                &nbsp;
                            </div>
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row mt-4">
    <div class="mt-2 mb-2">
        <label class="pe-4 fw-bold">手続きの開始</label>
        <button type="button" class="btn btn-blue">手続き一覧</button>
    </div>
</div>
<div class="row">
    <div class="col-xl-4 col-md-4 col-sm-12 col-12">
        <table class="table table-bordered table-border-color">
            <thead>
                <tr class="tr-head">
                    <th scope="col" class="fw-normal ps-4">追加・変更</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">
                        <div>
                            {{-- u05 --}}
                            <a href="{{route('u03')}}" class="btn btn-none">
                                <i class="bi bi-people-fill" style="font-size:7rem;"></i>
                            </a>
                        </div>
                        <div>各種項目の追加・変更はこちら</div>
                        <div> 氏名・扶養家族情報など</div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-xl-4 col-md-4 col-sm-12 col-12">
        <table class="table table-bordered table-border-color">
            <thead>
                <tr class="tr-head">
                    <th scope="col" class="fw-normal ps-4">入社手続き</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">
                        <div>
                            <a href="{{route('u03')}}" class="btn btn-none">
                                <i class="bi bi-building" style="font-size:7rem;"></i>
                            </a>
                        </div>
                        <div>入職者手続きはこちら</div>
                        <div>&nbsp;</div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-xl-4 col-md-4 col-sm-12 col-12">
        <table class="table table-bordered table-border-color">
            <thead>
                <tr class="tr-head">
                    <th scope="col" class="fw-normal ps-4">退職手続き</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">
                        <div>
                            <a href="{{route('u07.retireProc')}}" class="btn btn-none">
                                <i class="bi bi-vector-pen" style="font-size:7rem;"></i>
                            </a>
                        </div>
                        <div>退職手続きはこちら</div>
                        <div>&nbsp;</div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
