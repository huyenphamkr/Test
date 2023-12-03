@extends('layouts.layout')


<!-- U03-10 -->
@section('title', '雇用契約書編集')


@push('scripts')
    <script src="{{ asset('js/users/u0310.js?v=' . time()) }}"></script>
@endpush


@section('content')
    <style>
        .table>:not(caption)>*>* {
            padding: 0 0;
        }


        .table>tbody>tr:first-child>td:not(:first-child) {
            border-top-style: hidden !important;
            border: none;
        }


        .table-secondary {
            border-color: rgba(var(--bs-dark-rgb), var(--bs-border-opacity)) !important;
        }


        .border-top-right-none {
            border-right-style: hidden;
            border-top-style: hidden;
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

        #tableContent>:not(caption)>*>* {
            padding: 0 0;
            background-color: var(--bs-table-bg);
        }

        #tableContent {
            border-style: hidden;
        }

        .primary {
            color: #0d6efd;
            font-weight: bold;
        }

        .h-110 {
            height: 110px;
        }

        .h-200 {
            height: 200px;
        }

        .h-400 {
            height: 400px;
        }


        textarea {
            width: 100%;
            height: auto;
            min-height: 50px;
            resize: none;
            overflow: hidden;
        }

        #tblMon {
            pointer-events: none;
            user-select: none;
            opacity: 0.8;
        }


    @media only screen and (min-width: 1200px) {
        .ms-me-10 {
            margin-left: 10rem !important;
            margin-right: 10rem !important;
        }
    }
  
    </style>
    <?php
    $bg_text_center = 'table-secondary border-dark';
    ?>
<input type="hidden" name="id" id="hidIdCon" value="{{ old('id', $contract ? $contract->id : '') }}">
<input type="hidden" name="idInfo" id="idInfo" value="{{ old('id', $idInfo ?? '') }}">

    {{-- <div style="pointer-events: none;"  class="ms-md-5 me-md-5"> --}}
<div class="row mb-2 mt-3">
<label class="fw-bold text-center">労働条件通知書(雇用契約書)</label>

</div>
<div class="row mb-2">
    <label class="">1 {{$contract->id}}</label>
    <label class="">2 合和{{date("Y", strtotime($contract->public_date))}}年
        {{date("m", strtotime($contract->public_date))}}月
        {{date("d", strtotime($contract->public_date))}}日</label>
    <label class="">3事業所名 {{$contract->office_name}}</label>
    <label class="">4 住所 {{$contract->office_address}}</label>
    <label class="">5 代表者名 {{$contract->office_director}}</label>
</div>
    <div class="table-responsive mx-md-5 mx-sm-2 ms-me-10">
        <table id="tblMon" class="table table-bordered border-dark">
      <thead>
            {{-- <tr>
                <td colspan="12">
                         <div class="row justify-content-end my-1 pe-2">
                         <div class="col-2"> 
                      <div class="d-flex">
                                <div class="p-1 text-md-end col-form-label w-c-50">
                                    <label>
                                        合和{{date("Y",strtotime($contract->public_date))}}年
                                            {{date("m",strtotime($contract->public_date))}}月
                                            {{date("d",strtotime($contract->public_date))}}日
                                    </label>
                                </div>
                             </div> 
                        </div>
                    </div> 

                   <div class="row justify-content-start mb-5 ps-2">
                        <div class="col-5 ">
                            <div class="d-flex">
                                <div class="p-1 text-md-end col-form-label">
                                    <label class="text-decoration-underline">1 {{$contract->id}}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-1 col-form-label">
                                <label class="">3事業所名 {{$contract->office_name}}</label>
                            </div>

                            <div class="p-1 col-form-label">
                                <label class="">4 住所 {{$contract->office_address}}</label>
                            </div>

                            <div class="p-1 col-form-label">
                                <label class="">5 代表者名 {{$contract->office_director}}</label>
                            </div>
                        </div>
                    </div> 
                </td>
            </tr> --}}
        </thead>
            <tbody>
                <tr>
                    <td class="{{ $bg_text_center }}" style="max-width: 200px">
                        <div class="p-1 text-center">
                            <label>契約期間の定め</label>
                        </div>
                    </td>
                    <td class="w-c-250">
                        <select name="cmbContract_limit_flag" id="cmbContract_limit_flag"
                            class="form-select border-radius-none bd-t-r " onchange="ChangeCombobox(this)"
                            autofocus><!-- onchange="ChangeCombobox(this)" -->
                            <option></option>
                            @foreach ($mstClass as $cmb)
                                <option value="{{ $cmb->item_cd }}"
                                    {{ $contract && $contract->limit_flag == $cmb->item_cd ? 'selected' : '' }}>
                                    {{ $cmb->item_name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td colspan="10" style="min-width: 600px;">
                        <div class="d-flex flex-wrap align-items-center justify-content-center">
                            <div class="w-c-250">
                                <input id="txtLimit_date_start" name="txtLimit_date_start" type="date" autocomplete="off"
                                    maxlength="10" min="{{ CommonConst::DATE_MIN }}" max="{{ CommonConst::DATE_MAX }}"
                                    class="form-control border-radius-none "
                                    value="{{ !empty($contract) && $contract->limit_date_start != '' ? date_format(date_create($contract->limit_date_start), CommonConst::DATE_FORMAT_2) : '' }}">
                            </div>
                            <label class="me-2 ms-2">~</label>
                            <div class="w-c-250">
                                <input id="txtLimit_date_end" name="txtLimit_date_end" type="date" autocomplete="off"
                                    maxlength="10" min="{{ CommonConst::DATE_MIN }}" max="{{ CommonConst::DATE_MAX }}"
                                    class="form-control border-radius-none"
                                    value="{{ !empty($contract) && $contract->limit_date_end != '' ? date_format(date_create($contract->limit_date_end), CommonConst::DATE_FORMAT_2) : '' }}">
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="{{ $bg_text_center }}">
                        <div class="p-1 text-center">
                            <label>試用期間の定め</label>
                        </div>
                    </td>
                    <td colspan="2" class="border-top-right-none">
                        <select name="cmbTrial_flag" id="cmbTrial_flag" class="form-select border-radius-none bd-t-r"
                            onchange="ChangeCombobox(this)">
                            <option></option>
                            @foreach ($mstClass as $cmb)
                                <option value="{{ $cmb->item_cd }}"
                                    {{ $contract && $contract->trial_flag == $cmb->item_cd ? 'selected' : '' }}>
                                    {{ $cmb->item_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td colspan="10" class="border-top-right-none" style="min-width: 600px;">
                        <div class="d-flex flex-wrap align-items-center justify-content-center">
                            <!--  style="visibility: hidden;display: none;"-->
                            <div class="w-c-250">
                                <input id="txtTrial_date_start" name="txtTrial_date_start" type="date" autocomplete="off"
                                    maxlength="10" min="{{ CommonConst::DATE_MIN }}"
                                    max="{{ CommonConst::DATE_MAX }}"class="form-control border-radius-none"
                                    value="{{ !empty($contract) && $contract->trial_date_start != '' ? date_format(date_create($contract->trial_date_start), CommonConst::DATE_FORMAT_2) : '' }}">
                            </div>
                            <label class="me-2 ms-2">~</label>
                            <div class="w-c-250">
                                <input id="txtTrial_date_end" name="txtTrial_date_end" type="date" autocomplete="off"
                                    maxlength="10" min="{{ CommonConst::DATE_MIN }}"
                                    max="{{ CommonConst::DATE_MAX }}"class="form-control border-radius-none"
                                    value="{{ !empty($contract) && $contract->trial_date_end != '' ? date_format(date_create($contract->trial_date_end), CommonConst::DATE_FORMAT_2) : '' }}">
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="{{ $bg_text_center }}">
                        <div class="p-1 text-center"><label>1.契約更新の有無</label></div>
                    </td>
                    <td colspan="2" class="border-top-right-none">
                        <select name="cmbRenewed_flag" id="cmbRenewed_flag" class="form-select border-radius-none bd-t-r">
                            <option></option>
                            @foreach ($mstClass as $cmb)
                                <option value="{{ $cmb->item_cd }}"
                                    {{ $contract && $contract->renewed_flag == $cmb->item_cd ? 'selected' : '' }}>
                                    {{ $cmb->item_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td colspan="10" class="border-top-right-none"></td>
                </tr>
                <tr>
                    <td class="{{ $bg_text_center }}">
                        <div class="p-1 text-center"><label>2.契約の更新判断</label></div>
                    </td>
                    <td colspan="12">
                        <textarea contenteditable="true" id="txtRenewed_decision" name="txtRenewed_decision"
                            class="form-control border-radius-none align-top h-110" maxlength="500">{{ old('txtRenewed_decision', $contract->renewed_decision ?? '') }}</textarea>
                    </td>
                </tr>
                <tr>
                    <td class="{{ $bg_text_center }}">
                        <div class="p-1 text-center"><label>3.雇用契約の上限</label></div>
                    </td>
                    <td colspan="12">
                        <textarea contenteditable="true" id="txtRenewed_up" name="txtRenewed_up"
                            class="form-control border-radius-none align-top h-110" maxlength="500">{{ old('txtRenewed_up', $contract->renewed_up ?? '') }}</textarea>
                    </td>
                </tr>


                <tr>
                    <td class="{{ $bg_text_center }}">
                        <div class="p-1 text-center"><label>有期雇用特別措置法</label></div>
                    </td>
                    <td colspan="12">
                        <textarea id="txtSpecial_law" name="txtSpecial_law" class="form-control border-radius-none align-top h-110"
                            maxlength="500">{{ old('txtSpecial_law', $contract->special_law ?? '') }}</textarea>
                    </td>
                </tr>


                <tr>
                    <td class="{{ $bg_text_center }}">
                        <div class="p-1 text-center"><label>就業場所</label></div>
                    </td>
                    <td colspan="12">
                        <input id="txtWork_place" name="txtWork_place" class="form-control border-radius-none align-top"
                            maxlength="100" autocomplete="off"
                            value="{{ old('txtWork_place', $contract->work_place ?? '') }}">
                    </td>
                </tr>


                <tr>
                    <td class="{{ $bg_text_center }}" rowspan="2">
                        <div class="p-1 text-center"><label>業務の内容</label></div>
                    </td>
                    <td colspan="12">
                        <input id="txtWork_content1" name="txtWork_content1"
                            class="form-control border-radius-none align-top" maxlength="100" autocomplete="off"
                            value="{{ old('txtWork_content1', $contract->work_content1 ?? '') }}">
                    </td>
                </tr>


                <tr>
                    <td colspan="12">
                        <textarea id="txtWork_content2" name="txtWork_content2" class="form-control border-radius-none align-top"
                            maxlength="300">{{ old('txtWork_content2', $contract->work_content2 ?? '') }}</textarea>
                    </td>
                </tr>


                <tr>
                    <td class="{{ $bg_text_center }}">
                        <div class="p-1 text-center"><label>就業時間</label></div>
                    </td>
                    <td colspan="12">
                        <textarea id="txtWork_hours" name="txtWork_hours" class="form-control border-radius-none align-top h-400"
                            maxlength="1000">{{ old('txtWork_hours', $contract->work_hours ?? '') }}</textarea>
                    </td>
                </tr>


                <tr>
                    <td class="{{ $bg_text_center }}">
                        <div class="p-1 text-center"><label>所定労働時間</label></div>
                    </td>
                    <td colspan="12">
                        <input id="txtSchedule_work_hours" name="txtSchedule_work_hours"
                            class="form-control border-radius-none align-top" maxlength="100" autocomplete="off"
                            value="{{ old('txtSchedule_work_hours', $contract->schedule_work_hours ?? '') }}">
                    </td>
                </tr>


                <tr>
                    <td class="{{ $bg_text_center }}">
                        <div class="p-1 text-center"><label>休日時間</label></div>
                    <td colspan="12">
                        <input id="txtHoliday_time" name="txtHoliday_time"
                            class="form-control border-radius-none align-top" maxlength="100" autocomplete="off"
                            value="{{ old('txtHoliday_time', $contract->holiday_time ?? '') }}">
                    </td>
                </tr>
                <tr>
                    <td class="{{ $bg_text_center }}">
                        <div class="p-1 text-center"><label>所定労働時間を<br>
                                超える労働の有無
                            </label>
                        </div>
                    </td>
                    <td colspan="2">
                        <select name="cmbWork_overtime_flag" id="cmbWork_overtime_flag"
                            class="form-select border-radius-none"
                            style="min-height: 75px; border-bottom-style: hidden !important">
                            <!-- onchange="ChangeCombobox(this)" -->

                            <option></option>
                            @foreach ($mstClass as $cmb)
                                <option value="{{ $cmb->item_cd }}"
                                    {{ $contract && $contract->work_overtime_flag == $cmb->item_cd ? 'selected' : '' }}>
                                    {{ $cmb->item_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td colspan="10" class="table-secondary">
                        <textarea id="txtAllowance_text" name="txtAllowance_text" class="form-control border-radius-none align-top"
                            maxlength="100" style="min-height: 75px;">{{ old('txtAllowance_text', $contract->allowance_text ?? '') }}</textarea>
                    </td>
                </tr>


                <tr>
                    <td class="{{ $bg_text_center }}">
                        <div class="p-1 text-center"><label>休日労働</label></div>
                    </td>
                    <td colspan="2">
                        <select name="cmbWork_holiday_flag" id="cmbWork_holiday_flag"
                            class="form-select border-radius-none">
                            <option></option>
                            @foreach ($mstClass as $cmb)
                                <option value="{{ $cmb->item_cd }}"
                                    {{ $contract && $contract->work_holiday_flag == $cmb->item_cd ? 'selected' : '' }}>
                                    {{ $cmb->item_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td colspan="10" class="border-right-none"></td>
                </tr>


                <tr>
                    <td class="{{ $bg_text_center }}">
                        <div class="p-1 text-center"><label>休日</label></div>
                    </td>

                    <td colspan="12">
                        <textarea id="txtHolidays" name="txtHolidays" class="form-control border-radius-none align-top" maxlength="100">{{ old('txtHolidays', $contract->holidays ?? '') }}</textarea>
                    </td>
                </tr>


                <tr>
                    <td class="{{ $bg_text_center }}">
                        <div class="p-1 text-center"><label>休暇</label></div>
                    </td>
                    <td colspan="12">
                        <textarea id="txtVacation" name="txtVacation" class="form-control border-radius-none align-top" maxlength="100">{{ old('txtVacation', $contract->vacation ?? '') }}</textarea>
                    </td>
                </tr>
                <tr>
                    <td class="{{ $bg_text_center }}" rowspan="17">
                        <div class="p-1 text-center"><label>賃金</label></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="2" class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>1.基本賃金</label></div>
                    </td>
                    <td colspan="2" class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>ｲ　月給</label></div>
                    </td>
                    <td colspan="6">
                        <div class="input-group">
                            <input id="txtMonth_salary" name="txtMonth_salary" type="text" autocomplete="off"
                                maxlength="11" class="form-control border-radius-none allow_money text-end"
                                value="{{ old('txtMonth_salary', !empty($contract) ? number_format($contract->month_salary) : '') }}">
                            <span class="input-group-text border-radius-none">円</span>
                        </div>
                    </td>
                </tr>


                <tr style="display: none;">
                    <td colspan="2" class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>ﾛ　日給</label></div>
                    </td>
                    <td colspan="6">
                        <div class="input-group">
                            <input id="txtDaily_salary" name="txtDaily_salary" type="type" autocomplete="off"
                                maxlength="11" class="form-control border-radius-none allow_money text-end"
                                value="{{ old('txtDaily_salary', !empty($contract) ? number_format($contract->daily_salary) : '') }}">
                            <span class="input-group-text border-radius-none">円</span>
                        </div>
                    </td>
                </tr>


                <tr>
                    <td colspan="2" class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>ﾊ　時間給</label></div>
                    </td>
                    <td colspan="6">
                        <div class="input-group">
                            <input id="txtHourly_salary" name="txtHourly_salary" type="type" autocomplete="off"
                                maxlength="11" class="form-control border-radius-none allow_money text-end"
                                value="{{ old('txtHourly_salary', !empty($contract) ? number_format($contract->hourly_salary) : '') }}">
                            <span class="input-group-text border-radius-none">円</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="12">
                        <table id="tableContent" class="table-bordered border-dark">
                            <tbody id="tblList">
                                <tr id="trIndex_0">
                                    <td rowspan="1" class="{{ $bg_text_center }} w-c-250">
                                        <div class="p-1 text-start input-group">
                                            <label>2.諸手当及び計算方法</label>
                                            <button onclick="AddRowAllow()" type="button" class="btn-none ms-2"
                                                tabindex="-1">
                                                <span class="bi bi-plus-circle-fill primary"></span>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="border-0 border-bottom border-white w-c-250"></td>
                                    <td class="border-0 border-bottom border-white"></td>
                                </tr>

                            </tbody>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" rowspan="6" class="{{ $bg_text_center }} w-c-200">
                        <div class="p-1 text-start">
                            <label>3.時間外・休日勤務等の<label></label>
                        </div>
                        <div class="p-1 text-start">
                            <label>　割増賃金について</label>
                        </div>
                    </td>
                    <td rowspan="3" colspan="2" class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>ｲ　所定時間外勤務</label></div>
                    </td>
                    <td rowspan="2" class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>法定超</label></div>
                    </td>
                    <td colspan="2" class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>月60時間以内</label></div>
                    </td>
                    <td colspan="3">
                        <div class="input-group">
                            <input id="txtBelow_60_hour" name="txtBelow_60_hour"
                                class="form-control border-radius-none allow_numeric text-end" maxlength="2"
                                autocomplete="off" value="{{ old('txtBelow_60_hour', $contract->below_60_hour ?? '') }}">
                            <span class="input-group-text border-radius-none">%</span>
                        </div>

                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>月60時間超</label></div>
                    </td>
                    <td colspan="3">
                        <div class="input-group">
                            <input id="txtOver_60_hour" name="txtOver_60_hour"
                                class="form-control border-radius-none allow_numeric text-end" maxlength="2"
                                autocomplete="off" value="{{ old('txtOver_60_hour', $contract->over_60_hour ?? '') }}">
                            <span class="input-group-text border-radius-none">%</span>
                        </div>

                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>所定超</label></div>
                    </td>
                    <td colspan="3">
                        <div class="input-group">
                            <input id="txtStandard_amount" name="txtStandard_amount"
                                class="form-control border-radius-none allow_numeric text-end" maxlength="2"
                                autocomplete="off"
                                value="{{ old('txtStandard_amount', $contract->standard_amount ?? '') }}">
                            <span class="input-group-text border-radius-none">%</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" colspan="2" class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>ﾛ　休日勤務</label></div>
                    </td>
                    <td colspan="3" class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>法定休日</label></div>
                    </td>
                    <td colspan="3">
                        <div class="input-group">
                            <input id="txtHoliday_legal" name="txtHoliday_legal"
                                class="form-control border-radius-none allow_numeric text-end" maxlength="2"
                                autocomplete="off" value="{{ old('txtHoliday_legal', $contract->holiday_legal ?? '') }}">
                            <span class="input-group-text border-radius-none">%</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>法定外休日</label></div>
                    </td>
                    <td colspan="3">
                        <div class="input-group">
                            <input id="txtHoliday_non_legal" name="txtHoliday_non_legal"
                                class="form-control border-radius-none allow_numeric text-end" maxlength="2"
                                autocomplete="off"
                                value="{{ old('txtHoliday_non_legal', $contract->holiday_non_legal ?? '') }}">
                            <span class="input-group-text border-radius-none">%</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>ﾊ　深夜勤務</label></div>
                    </td>
                    <td colspan="6">
                        <div class="input-group">
                            <input id="txtWork_night_shift" name="txtWork_night_shift"
                                class="form-control border-radius-none allow_numeric text-end" maxlength="2"
                                autocomplete="off"
                                value="{{ old('txtWork_night_shift', $contract->work_night_shift ?? '') }}">
                            <span class="input-group-text border-radius-none">%</span>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td rowspan="3" colspan="2" class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>4.賃金支払</label></div>
                    </td>
                    <td colspan="2" class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>締日</label></div>
                    </td>
                    <td colspan="6">
                        <div class="input-group">
                            <select name="txtOff_date_number" id="txtOff_date_number"
                                class="form-select border-radius-none text-end" onchange="ChangeCombobox(this)">
                                <option></option>
                                @foreach (range(1, 31) as $i)
                                    <option value="{{ $i }}"
                                        {{ $contract && $contract->off_date_number == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="input-group-text border-radius-none">日</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>支払日</label></div>
                    </td>
                    <td colspan="6">
                        <div class="input-group">
                            <select name="txtPayment_date_number" id="txtPayment_date_number"
                                class="form-select border-radius-none text-end" onchange="ChangeCombobox(this)">
                                <option></option>
                                @foreach (range(1, 31) as $i)
                                    <option value="{{ $i }}"
                                        {{ $contract && $contract->payment_date_number == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="input-group-text border-radius-none">日</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>支払方法</label></div>
                    </td>
                    <td colspan="6">
                        <select name="cmbPayment_method" id="cmbPayment_method" class="form-select border-radius-none">
                            <option></option>
                            @foreach ($mstClsPay as $cmbPay)
                                <option value="{{ $cmbPay->item_cd }}"
                                    {{ $contract && $contract->payment_method == $cmbPay->item_cd ? 'selected' : '' }}>
                                    {{ $cmbPay->item_name }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>


                <tr>
                    <td colspan="3"class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>5.労使協定に基づく賃金支払い時の控除</label></div>
                    </td>
                    <td colspan="1">
                        <select name="cmbAgreement_labor_flag" id="cmbAgreement_labor_flag"
                            class="form-select border-radius-none">
                            <option></option>
                            @foreach ($mstClass as $cmb)
                                <option value="{{ $cmb->item_cd }}"
                                    {{ $contract && $contract->agreement_labor_flag == $cmb->item_cd ? 'selected' : '' }}>
                                    {{ $cmb->item_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td colspan="8">
                        <input id="txtAgreement_labor" name="txtAgreement_labor" class="form-control border-radius-none"
                            maxlength="50" autocomplete="off"
                            value="{{ old('txtAgreement_labor', $contract->agreement_labor ?? '') }}">
                    </td>
                </tr>


                <tr>
                    <td rowspan="3" colspan="2" class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>6.その他</label></div>
                    </td>
                    <td style="width:150px" class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>賃金改訂</label></div>
                    </td>
                    <td style="width:100px">
                        <select name="cmbSalary_modify_flag" id="cmbSalary_modify_flag"
                            class="form-select border-radius-none">
                            <option></option>
                            @foreach ($mstClass as $cmb)
                                <option value="{{ $cmb->item_cd }}"
                                    {{ $contract && $contract->salary_modify_flag == $cmb->item_cd ? 'selected' : '' }}>
                                    {{ $cmb->item_name }}</option>
                            @endforeach
                    </td>
                    <td colspan="8">
                        <input id="txtsalary_modify" name="txtsalary_modify" class="form-control border-radius-none"
                            maxlength="50" autocomplete="off"
                            value="{{ old('txtsalary_modify', $contract->salary_modify ?? '') }}">
                    </td>
                </tr>


                <tr>
                    <td colspan="1"class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>賞与</label></div>
                    </td>
                    <td colspan="1">
                        <select name="cmbBonus_flag" id="cmbBonus_flag" class="form-select border-radius-none">
                            <option></option>
                            @foreach ($mstClass as $cmb)
                                <option value="{{ $cmb->item_cd }}"
                                    {{ $contract && $contract->bonus_flag == $cmb->item_cd ? 'selected' : '' }}>
                                    {{ $cmb->item_name }}</option>
                            @endforeach
                    </td>
                    <td colspan="8">
                        <input id="txtBonus" name="txtBonus" class="form-control border-radius-none" maxlength="50"
                            autocomplete="off" value="{{ old('txtBonus', $contract->bonus ?? '') }}">
                    </td>
                </tr>


                <tr>
                    <td colspan="1"class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>退職金</label></div>
                    </td>
                    <td colspan="1">
                        <select name="cmbRetired_salary_flag" id="cmbRetired_salary_flag"
                            class="form-select border-radius-none">
                            <option></option>
                            @foreach ($mstClass as $cmb)
                                <option value="{{ $cmb->item_cd }}"
                                    {{ $contract && $contract->retired_salary_flag == $cmb->item_cd ? 'selected' : '' }}>
                                    {{ $cmb->item_name }}</option>
                            @endforeach
                    </td>
                    <td colspan="8">
                        <input id="txtRetired_salary" name="txtRetired_salary" class="form-control border-radius-none"
                            maxlength="50" autocomplete="off"
                            value="{{ old('txtRetired_salary', $contract->retired_salary ?? '') }}">
                    </td>
                </tr>


                <tr>
                    <td class="{{ $bg_text_center }}" rowspan="5">
                        <div class="p-1 text-center"><label>退職に関する事項</label></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>1.定年制</label></div>
                    </td>
                    <td colspan="1">
                        <select name="cmbRetire_limit_age_flag" id="cmbRetire_limit_age_flag"
                            class="form-select border-radius-none">
                            <option></option>
                            @foreach ($mstClass as $cmb)
                                <option value="{{ $cmb->item_cd }}"
                                    {{ $contract && $contract->retire_limit_age_flag == $cmb->item_cd ? 'selected' : '' }}>
                                    {{ $cmb->item_name }}</option>
                            @endforeach
                    </td>
                    <td colspan="8">
                        <input id="txtRetire_limit_age_flag" name="txtRetire_limit_age_flag"
                            class="form-control border-radius-none" maxlength="50" autocomplete="off"
                            value="{{ old('txtRetire_limit_age_flag', $contract->retire_limit_age ?? '') }}">
                    </td>
                </tr>

                <tr>
                    <td colspan="3"class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>2.継続雇用制度</label></div>
                    </td>
                    <td colspan="1">
                        <select name="cmbReemployment_age_flag" id="cmbReemployment_age_flag"
                            class="form-select border-radius-none">
                            <option></option>
                            @foreach ($mstClass as $cmb)
                                <option value="{{ $cmb->item_cd }}"
                                    {{ $contract && $contract->reemployment_age_flag == $cmb->item_cd ? 'selected' : '' }}>
                                    {{ $cmb->item_name }}</option>
                            @endforeach
                    </td>
                    <td colspan="8">
                        <input id="txtReemployment_age_flag" name="txtReemployment_age_flag"
                            class="form-control border-radius-none" maxlength="50" autocomplete="off"
                            value="{{ old('txtReemployment_age_flag', $contract->reemployment_age ?? '') }}">
                    </td>
                </tr>

                <tr>
                    <td colspan="3"class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>3.自己都合退職の手続</label></div>
                    </td>
                    <td colspan="9">
                        <input id="txtRetired_reason" name="txtRetired_reason" class="form-control border-radius-none"
                            maxlength="100" autocomplete="off"
                            value="{{ old('txtRetired_reason', $contract->retired_reason ?? '') }}">
                    </td>
                </tr>

                <tr>
                    <td colspan="3"class="{{ $bg_text_center }}">
                        <div class="p-1 text-start"><label>4.解雇事由及び手続</label></div>
                    </td>
                    <td colspan="9">
                        <textarea id="txtDismiss_reason" name="txtDismiss_reason" class="form-control border-radius-none h-110"
                            maxlength="200" value="{{ old('txtDismiss_reason', $contract->dismiss_reason ?? '') }}">{{ old('txtDismiss_reason', $contract->dismiss_reason ?? '') }}</textarea>
                    </td>
                </tr>


                <tr>
                    <td class="{{ $bg_text_center }}">
                        <div class="p-1 text-center">
                            <span class="note">*</span>
                            <label>社会保険加入</label>
                        </div>
                    </td>

                    <td colspan="2">
                        <select name="cmbSocial_insurance" id="cmbSocial_insurance"
                            class="form-select border-radius-none">
                            <option></option>
                            @foreach ($mstClass as $cmb)
                                <option value="{{ $cmb->item_cd }}"
                                    {{ $contract && $contract->social_insurance == $cmb->item_cd ? 'selected' : '' }}>
                                    {{ $cmb->item_name }}</option>
                            @endforeach
                    </td>


                    <td colspan="2" class="{{ $bg_text_center }}">
                        <div class="p-1 text-center">
                            <span class="note">*</span>
                            <label>見込額</label>
                        </div>
                    </td>


                    <td colspan="8">
                        <input id="txtEstimated_amount" name="txtEstimated_amount"
                            class="form-control border-radius-none allow_money text-end" maxlength="11"
                            autocomplete="off"
                            value="{{ old('txtEstimated_amount', $contract->estimated_amount ?? '') }}">
                    </td>
                </tr>



                <tr>
                    <td class="{{ $bg_text_center }}">
                        <div class="p-1 text-center">
                            <span class="note">*</span>
                            <label>雇用保険加入</label>
                        </div>
                    </td>

                    <td colspan="2">
                        <select name="cmbEmployment_insurance" id="cmbEmployment_insurance"
                            class="form-select border-radius-none">
                            <option></option>
                            @foreach ($mstClass as $cmb)
                                <option value="{{ $cmb->item_cd }}"
                                    {{ $contract && $contract->employment_insurance == $cmb->item_cd ? 'selected' : '' }}>
                                    {{ $cmb->item_name }}</option>
                            @endforeach
                    </td>


                    <td colspan="2" class="{{ $bg_text_center }}">
                        <div class="p-1 text-center">
                            <span class="note">*</span>
                            <label>１週間の所定労働時間</label>
                        </div>
                    </td>


                    <td colspan="8">
                        <input id="txtWork_hours_per_week" name="txtWork_hours_per_week"
                            class="form-control border-radius-none" maxlength="30" autocomplete="off"
                            value="{{ old('txtWork_hours_per_week', $contract->work_hours_per_week ?? '') }}">
                    </td>
                </tr>


                <tr>
                    <td class="{{ $bg_text_center }}">
                        <div class="p-1 text-center"><label>その他</label></div>
                    </td>
                    <td colspan="12">
                        <textarea id="txtOther_reason" name="txtOther_reason" class="form-control border-radius-none h-200" maxlength="500"
                            value="{{ old('txtOther_reason', $contract->other_reason ?? '') }}">{{ old('txtOther_reason', $contract->other_reason ?? '') }}
                    </textarea>
                    </td>
                </tr>


                <tr>
                    <td class="border-0">
                        <div class="p-1"><label></label></div>
                    </td>
                    </td>
                    <td colspan="12" class="border-0 border-bottom-0"></td>
                </tr>

                <tr>
                    <td class="">
                        <div class="bg-title p-1 text-center">
                            <span class="note">*</span>
                            <label>発行日</label>
                        </div>
                    </td>
                    <td colspan="12" class="border-0 border-left"
                        style="border-top-style: hidden !important; border-bottom-style: hidden !important;"> </td>
                </tr>
                <tr>
                    <td>
                        <input id="txtPublicDate" name="txtPublicDate" type="date" autocomplete="off" 
                            maxlength="10" min="{{ CommonConst::DATE_MIN }}" max="{{ CommonConst::DATE_MAX }}"
                            class="form-control border-radius-none "
                            value="{{ !empty($contract) && $contract->public_date != '' ? date_format(date_create($contract->public_date), CommonConst::DATE_FORMAT_2) : date_format(date_create($info->date_join), CommonConst::DATE_FORMAT_2) }}">
                    </td>
                    <td colspan="12" class="border-0" style="border-bottom-style: hidden !important;"> </td>
                </tr>
            </tbody>
        </table>
    </div>

<div class="row justify-content-center mt-2 mb-2 me-sm-4">
    <div class="col-3">
        <div class="d-flex align-items-center">
            <div class="form-check">
                <input name="cbAgree" id="cbAgree" class="form-check-input" type="checkbox" onchange="Oncheck(this)">
                <label class="form-check-label w-c-150" for="cbAgree">本内容に同意します</label>
            </div>
        </div>
    </div>
</div>
<div class="row justify-content-end mb-2 ms-1">
    <div class="col-xl-4 col-md-5 me-xl-5 me-3">
        <div class="d-flex">
            <div class="p-1 text-md-end col-form-label w-c-50"><label>日時 :</label></div>
                <input name="txtDate" id="txtDate" class="form-control border-radius-none bg-title " type="text" value="{{$contract->datePro}}">
        </div>
    </div>
</div>

<div class="row justify-content-end mb-2 ms-1">
    <div class="col-xl-4 col-md-5 me-xl-5 me-3">
        <div class="d-flex">
            <div class="p-1 text-md-end col-form-label w-c-50"><label>氏名 :</label></div>
                <input name="txtName" id="txtName" class="form-control border-radius-none bg-title " type="text" value="{{$contract->firstNameU310}} {{$contract->lastNameU310}}" >
        </div>
    </div>
</div>
<div class="row justify-content-center mt-2 mb-4">
    <button type="button" class="btn btn-blue btn-primary mb-2" id="btnNext" style="display: none"
    onclick="Save(`{{route('u03.saveContInfo')}}`)">次へ</button>
</div>


@endsection