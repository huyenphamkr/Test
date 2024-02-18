@extends('layouts.layout')

@section('title', '出品一覧')

@push('scripts')
    <script src="{{ asset('js/users/u04.js?v=' . time()) }}"></script>
@endpush

@section('content')
    <style type="text/css">
        @media (min-width: 1401px) {
            .sticky-xxl-left {
                left: -1px;
                z-index: 629;
                position: sticky;
                position: -webkit-sticky;
                white-space: normal!important;
            }

            .left-position {
                left: 0 !important;
            }

            .left-position:nth-child(2) {
                left: 150px !important;
            }

            .left-position:nth-child(3) {
                left: 300px !important;
            }

            .left-position:nth-child(4) {
                left: 400px !important;
            }

            .border-head {
                border-collapse: separate;
                border-spacing: 0;
            }

            .border-head td {
                border-bottom: var(--bs-border-width) var(--bs-border-style) var(--bs-border-color) !important;
            }
        }
    </style>
    <div class="edit-content py-2">
        <div class="row align-items-center">
            <div class="d-sm-flex flex-wrap align-items-center">
                <div class="d-sm-flex align-items-center flex-wrap mb-2 me-sm-4">
                    <label id="lblDate">開催日</label><span class="me-2">：</span>
                    <div class="w-c-150">
                        <input class="form-control" type="date" autocomplete="off" name="txtDate" id="txtDate" maxlength="10"
                            value="{{ !empty($data) ? $data['txtDate'] : date(CommonConst::Date_Format_Calendar) }}" min="{{ CommonConst::Date_Min }}"
                            max="{{ date(CommonConst::Date_Format_Calendar, strtotime('last day of December this year +1 years')) }}" autofocus>
                    </div>
                </div>
                <div class="d-sm-flex align-items-center mb-2 me-sm-4">
                    <label class="me-2">会場名：</label>
                    <div class="d-inline">
                        <select id="cmbSrc" name="cmbSrc" class="form-select border-radius-none mw-c-100">
                            <option value="0">全て</option>
                            @foreach ($venues as $item)
                                <option value="{{ $item->line }}" @if (!empty($data) && $data['cmbSrc'] == $item->line) selected @endif>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary mb-2" onclick="ChkInputSearch()" id="btnSrc" name="btnSrc" value="search" tabindex="-1">検索</button>
            </div>
        </div>
        <div class="mb-2">
            <button type="button" class="btn btn-green" onclick="ChkInput()" tabindex="-1">保存</button>
            <button type="button" class="btn btn-outline-green" onclick="BackPrev('{{ route('u04', ['page' => $page]) }}')" tabindex="-1">キャンセル</button>
            <input type="hidden" id="hidCarPayments" value='@json($carPayments->items())'>
            <input type="hidden" id="hidMode" name="hidMode">
        </div> 

        {!! $carPayments->appends(request()->all())->links('layouts.pagination') !!}
        
        {{-- 支払一覧 --}}
        <div class="table-responsive">
            <table class="table table-bordered table-border-color border-head">
                <thead>
                    <tr class="tr-head text-center">
                        {{-- <th scope="col" class="mw-c-100"><label id="lblSPaymentDate">日付</label></th> --}}
                        {{-- <th scope="col" class="mw-c-100"><label id="lblSAuctionResult">成約結果</label></th> --}}
                        <th scope="col" class="mw-c-150 sticky-xxl-left left-position bg-cyan-light"><label id="lblSCarNumber">号車</label></th>
                        <th scope="col" class="mw-c-150 sticky-xxl-left left-position bg-cyan-light"><label id="lblSCarName">車名</label></th>
                        <th scope="col" class="mw-c-100 sticky-xxl-left left-position bg-cyan-light"><label id="lblSCarModel">型式</label></th>
                        <th scope="col" class="mw-c-250 sticky-xxl-left left-position bg-cyan-light"><label id="lblSGrade">グレード</label></th>
                        <th scope="col" class="mw-c-100"><label id="lblSChassisNumber">車体番号</label></th>
                        <th scope="col" class="mw-c-150"><label id="lblSUser">担当</label></th>
                        <th scope="col" class="mw-c-150"><label id="lblCarAmount">請求車両金額</label></th>
                        <th scope="col" class="mw-c-150"><label id="lblBillingSubtotal">自動車代金小計</label></th>
                        <th scope="col" class="mw-c-100"><label id="lblSCarNo">車両No</label></th>

                        {{-- <th scope="col" class="mw-c-150"><label id="lblSVenue">会場</label></th> --}}
                        {{-- <th scope="col" class="mw-c-100"><label id="lblSHeldNumber">開催数</label></th> --}}
                        {{-- <th scope="col" class="mw-c-100"><label id="lblSExhibitNumber">出品番号</label></th> --}}
                        <th scope="col" class="mw-c-150"><label id="lblSCarAmount">車両金額</label></th>
                        <th scope="col" class="mw-c-150"><label id="lblSConTax">消費税</label></th>
                        <th scope="col" class="mw-c-150"><label id="lblSRDeposit">R預託金</label></th>
                        <th scope="col" class="mw-c-150"><label id="lblSOthers">その他</label></th>
                        {{-- <th scope="col" class="mw-c-150"><label id="lblSCarTax">自動車税</label></th> --}}
                        <th scope="col" class="mw-c-150"><label id="lblSExhibitFee">出品料</label></th>
                        <th scope="col" class="mw-c-150"><label id="lblSClosingFee">成約料</label></th>
                        <th scope="col" class="mw-c-150"><label id="lblSReAuctionFee">再セリ料</label></th>
                        <th scope="col" class="mw-c-200"><label>自動車代金小計</label></th>
                        <th scope="col" class="mw-c-200"><label>利益 </label></th>
                        {{-- <th scope="col" class="w-c-50 bg-white border-0 border-bottom border-top border-white p-0"></th> --}}
                    </tr>
                </thead>
                <tbody id="tblPayments">
                </tbody>
            </table>
        </div>
        @if (Session::has('error'))
            <div class="text-center"><label class="err-msg">{{ Session::get('error') }}</label></div>
            @php
                Session::forget('error');
            @endphp
        @endif
        <div class="d-none">
            <select id="cmbUser" name="cmbUser">
                <option></option>
                @foreach ($users as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endsection
