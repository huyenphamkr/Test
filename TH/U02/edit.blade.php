@extends('layouts.layout')

@section('title', '車両登録')

@push('scripts')
    <script src="{{ asset('js/users/u02.js?v=' . time()) }}"></script>
@endpush

@section('content')
    <style type="text/css">
        @media only screen and (max-width: 1799px) and (min-width: 1400px) {
            .basic-info * {
                font-size: 14px;
            }
        }

        .w-file {
            width: calc(100% - 20px);
        }

        @media only screen and (min-width: 768px) {
            .border-first-row div:not(:first-child) .border-radius-none,
            .border-second-row div:not(:first-child) .border-radius-none {
                border-left: 0;
            }

            .border-second-row .border-radius-none {
                border-top: 0;
            }
        }

        @media only screen and (max-width: 767px) and (min-width: 576px) {
            .border-first-row div:nth-child(3) .border-radius-none,
            .border-first-row div:nth-child(4) .border-radius-none,
            .border-second-row .border-radius-none {
                border-top: 0;
            }

            .border-first-row div:nth-of-type(even) .border-radius-none,
            .border-second-row div:nth-of-type(even) .border-radius-none {
                border-left: 0;
            }
        }
        .check-input-red:checked {
            background-color: #dc3545;
            border-color: #dc3545;
          }
        .check-input-red:focus {
            border-color: #dc3545;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
        }
        .check-input-green:checked {
            background-color: #1BB556;
            border-color: #1BB556;
          }
        .check-input-green:focus {
            border-color: #1BB556;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
        }
    </style>
    <div class="edit-content py-2">
        @if (Session::has('error'))
            <div id="msgErr" class="mb-2 text-danger fw-bold alert alert-warning" role="alert">{{ Session::get('error') }}
            </div>
            @php
                Session::forget('error');
            @endphp
        @endif
        <div class="mb-4">
            <div class="form-check">
                <input class="form-check-input custom-checkbox" id="chkDocDate1" name="chkDocDate1" type="checkbox" tabindex="-1" 
                    onchange="EnableEdit(this, 'txtDocDate')">
                <label class="form-check-label align-middle" id="lblDocDate1" for='chkDocDate1'>日付</label>
            </div>

            <div class="form-check">
                <input class="form-check-input custom-checkbox" id="chkDocDate12" name="chkDocDate12" type="checkbox" tabindex="-1" 
                    onchange="EnableEdit(this, 'txtDocDate')">
                <label class="form-check-label align-middle" id="lblDocDate12" for='chkDocDate1'>日付</label>
            </div>
              
            <button type="button" class="btn btn-green" onclick="ChkInput()" tabindex="-1">保存</button>
            <button type="button" class="btn btn-outline-green" onclick="BackPrev('{{ route('u02', ['page' => $page]) }}')" tabindex="-1">キャンセル</button>
            @if ($car->id)
                <button type="button" class="btn btn-danger" onclick="ConfirmDelCar()" tabindex="-1">削除</button>
            @endif
            <input type="hidden" id="hidChkDup" value="{{ route('u02.checkDup') }}">
            <input type="hidden" id="hidId" name="hidId" value="{{ $car->id ?? '' }}">
            <input type="hidden" id="hidCarPayments" value="{{ $carPayments }}">
            <input type="hidden" id="hidUrlUpload" value="{{ route('u02.upload') }}">
            <input type="hidden" id="hidUrlDelFile" value="{{ route('u02.delFile') }}">
            <input type="hidden" id="hidUrlFilePath" value="{{ asset(CommonConst::Upload_Folder_Path_Url) }}">
        </div> 
        @php
            const CLASS_COL_LEFT = 'col-xxl-4 col-xl-8 col-lg-10';
            const CLASS_COL_RIGHT = 'col-xxl-8';
            const CLASS_COL_OTHERS = 'col-xl-6';
            const CLASS_COL_OTHERS_TIT = 'col-lg-3 pe-lg-0';
            const CLASS_COL_OTHERS_TEXT = 'col-lg-4 col-md-6 pe-lg-0';
            const CLASS_COL_OTHERS_FILE = 'col pe-lg-0';
            const CLASS_COL_MONEY = 'col-xxl-7 col-xl-8 col-lg-10';
            const CLASS_COL_TITLE_1 = 'col-md-3 col-sm-4 pe-sm-0';
            const CLASS_COL_TEXT_1 = 'col-md-3 col-sm-8 ps-sm-0';
            const CLASS_COL_TITLE_2 = 'col-md-2 col-sm-4 pe-sm-0';
            const CLASS_COL_TEXT_2 = 'col-md-4 col-sm-8 ps-sm-0';
        @endphp
        <div class="row basic-info">
            <div class="<?= CLASS_COL_LEFT ?>">
                {{-- 登録No --}}
                <div class="row mb-4 border-first-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label>登録No</label></div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_1 ?> px-md-0 mb-sm-0 mb-2">
                        <input id="txtRegisterNo" name="txtRegisterNo" readonly type="text" autocomplete="off" value="{{ $car->register_no ?? '' }}" tabindex="-1"
                            class="form-control border-radius-none shadow-none">
                    </div>
                    <div class="<?= CLASS_COL_TITLE_2 ?> px-md-0">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label>ステータス</label></div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_2 ?>">
                        <select id="cmbStatus" name="cmbStatus" class="form-select border-radius-none" autofocus>
                            <option></option>
                            @foreach ($combobox as $item)
                                <option value="{{ $item->cmb_cd }}" @if ($car->status == $item->cmb_cd) selected @endif>
                                    {{ $item->cmb_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- 基本情報 --}}
                <div class="row mb-2">
                    <div class="fs-5 fw-semibold">基本情報</div>
                </div>
                {{-- 日付 --}}
                <div class="row mb-sm-0 mb-2 border-first-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblRegisterDate">日付</label></div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_1 ?> px-md-0 mb-sm-0 mb-2">
                        <input id="txtRegisterDate" name="txtRegisterDate" type="date" autocomplete="off" maxlength="10" class="form-control border-radius-none"
                            value="{{ $car->register_date ?? '' }}" min="{{ CommonConst::Date_Min_1 }}"
                            max="{{ date(CommonConst::Date_Format_Calendar, strtotime('last day of December this year +1 years')) }}">
                    </div>
                    <div class="<?= CLASS_COL_TITLE_2 ?> px-md-0">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblCarNumber">出品番号</label></div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_2 ?>">
                        <input id="txtCarNumber" name="txtCarNumber" type="text" autocomplete="off" maxlength="10"
                            value="{{ $car->car_number ?? '' }}" class="form-control border-radius-none">
                    </div>
                </div>
                {{-- 会場 --}}
                <div class="row mb-sm-0 mb-2 border-second-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblVenue">会場</label></div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_1 ?> px-md-0 mb-sm-0 mb-2">
                        <select id="cmbVenue" name="cmbVenue" class="form-select border-radius-none">
                            <option></option>
                            @foreach ($others as $item)
                                @if ($item->id == CommonConst::Other_Type_Venue_Id)
                                    <option value="{{ $item->line }}" @if ($car->venue == $item->line) selected @endif>
                                        {{ $item->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="<?= CLASS_COL_TITLE_2 ?> px-md-0">
                        <div class="bg-title text-center form-control border-radius-none px-0">
                            <span class="note">*</span><label id="lblUser">担当者</label>
                        </div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_2 ?>">
                        <select id="cmbUser" name="cmbUser" class="form-select border-radius-none">
                            <option></option>
                            @foreach ($user as $item)
                                <option value="{{ $item->id }}" @if ($car->user_id == $item->id) selected @endif>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- 開催数 --}}
                <div class="row mb-4 border-second-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblHeldNumber">開催数</label></div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_1 ?> px-md-0 mb-sm-0 mb-2">
                        <input id="txtHeldNumber" name="txtHeldNumber" type="text" autocomplete="off" maxlength="10"
                            value="{{ $car->held_number ?? '' }}" class="form-control border-radius-none allow_numeric">
                    </div>
                    <div class="<?= CLASS_COL_TITLE_2 ?> px-md-0">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblEvaluation">評価</label></div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_2 ?>">
                        <input id="txtEvaluation" name="txtEvaluation" type="text" autocomplete="off" maxlength="10"
                            value="{{ $car->evaluation ?? '' }}" class="form-control border-radius-none">
                    </div>
                </div>
                {{-- 車名 --}}
                <div class="row mb-sm-0 mb-2 border-first-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblCarName">車名</label></div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_1 ?> px-md-0 mb-sm-0 mb-2">
                        <input id="txtCarName" name="txtCarName" type="text" autocomplete="off" maxlength="30"
                            value="{{ $car->car_name ?? '' }}" class="form-control border-radius-none">
                    </div>
                    <div class="<?= CLASS_COL_TITLE_2 ?> px-md-0">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblCarInspection">車検</label></div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_2 ?>">
                        <input id="txtCarInspection" name="txtCarInspection" type="text" autocomplete="off"
                            maxlength="10" value="{{ $car->car_inspection ?? '' }}" class="form-control border-radius-none">
                    </div>
                </div>
                {{-- 車体番号 --}}
                <div class="row mb-sm-0 mb-2 border-second-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0">
                            <span class="note">*</span><label id="lblChassisNumber">車体番号</label>
                        </div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_1 ?> px-md-0 mb-sm-0 mb-2">
                        <input id="txtChassisNumber" name="txtChassisNumber" type="text" autocomplete="off"
                            maxlength="50" value="{{ $car->chassis_number ?? '' }}" class="form-control border-radius-none">
                    </div>
                    <div class="<?= CLASS_COL_TITLE_2 ?> px-md-0">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblMileage">走行距離</label></div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_2 ?>">
                        <input id="txtMileage" name="txtMileage" type="text" autocomplete="off" maxlength="13"
                            value="{{ $car->mileage ?? '' }}" class="form-control border-radius-none allow_money text-end">
                    </div>
                </div>
                {{-- 車歴 --}}
                <div class="row mb-sm-0 mb-2 border-second-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblCarHistory">車歴</label></div>
                    </div>
                    <div class="col ps-sm-0">
                        <input id="txtCarHistory" name="txtCarHistory" type="text" autocomplete="off" maxlength="50"
                            value="{{ $car->car_history ?? '' }}" class="form-control border-radius-none">
                    </div>
                </div>
                {{-- 型式 --}}
                <div class="row mb-sm-0 mb-2 border-second-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><span class="note">*</span><label id="lblCarModel">型式</label></div>
                    </div>
                    <div class="col ps-sm-0">
                        <input id="txtCarModel" name="txtCarModel" type="text" autocomplete="off" maxlength="50"
                            value="{{ $car->car_model ?? '' }}" class="form-control border-radius-none">
                    </div>
                </div>
                {{-- 初度登録年月 --}}
                <div class="row mb-sm-0 mb-2 border-second-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblFirstDate">初度登録年月</label></div>
                    </div>
                    <div class="col ps-sm-0">
                        <input id="txtFirstDate" name="txtFirstDate" type="text" autocomplete="off" maxlength="50"
                            value="{{ $car->first_date ?? '' }}" class="form-control border-radius-none">
                    </div>
                </div>
                {{-- グレード --}}
                <div class="row mb-sm-0 mb-2 border-second-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblGrade">グレード</label></div>
                    </div>
                    <div class="col ps-sm-0">
                        <input id="txtGrade" name="txtGrade" type="text" autocomplete="off" maxlength="50"
                            value="{{ $car->grade ?? '' }}" class="form-control border-radius-none">
                    </div>
                </div>
                {{-- その他 --}}
                <div class="row mb-4 border-second-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblOthers">その他</label></div>
                    </div>
                    <div class="col ps-sm-0">
                        <input id="txtOthers" name="txtOthers" type="text" autocomplete="off" maxlength="50"
                            value="{{ $car->others ?? '' }}" class="form-control border-radius-none">
                    </div>
                </div>
                {{-- 車両No --}}
                <div class="row mb-4 border-first-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblCarNo">車両No</label></div>
                    </div>
                    <div class="col ps-sm-0">
                        <input id="txtCarNo" name="txtCarNo" type="text" autocomplete="off" maxlength="15"
                            value="{{ $car->car_no ?? '' }}" class="form-control border-radius-none">
                    </div>
                </div>
                {{-- 新ナンバー --}}
                <div class="row mb-2">
                    <div class="fs-5 fw-semibold">新ナンバー</div>
                </div>
                <div class="row mb-sm-0 mb-2 border-first-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblNewNumber">新ナンバー</label></div>
                    </div>
                    <div class="col ps-sm-0">
                        <input id="txtNewNumber" name="txtNewNumber" type="text" autocomplete="off" maxlength="15"
                            value="{{ $car->new_number ?? '' }}" class="form-control border-radius-none">
                    </div>
                </div>
                {{-- 名変コピー１ --}}
                <div class="row mb-sm-0 mb-2 border-second-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblNameCopy1">名変コピー１</label></div>
                    </div>
                    <div class="col ps-sm-0">
                        <input id="txtNameCopy1" name="txtNameCopy1" type="text" autocomplete="off" maxlength="50"
                            value="{{ $car->name_copy_1 ?? '' }}" class="form-control border-radius-none">
                    </div>
                </div>
                {{-- 名変コピー２ --}}
                <div class="row mb-sm-0 mb-2 border-second-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblNameCopy2">名変コピー２</label></div>
                    </div>
                    <div class="col ps-sm-0">
                        <input id="txtNameCopy2" name="txtNameCopy2" type="text" autocomplete="off" maxlength="50"
                            value="{{ $car->name_copy_2 ?? '' }}" class="form-control border-radius-none">
                    </div>
                </div>
                {{-- 備考 --}}
                <div class="row mb-4 border-second-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblNote">備考</label></div>
                    </div>
                    <div class="col ps-sm-0">
                        <input id="txtNote" name="txtNote" type="text" autocomplete="off" maxlength="50"
                            value="{{ $car->note ?? '' }}" class="form-control border-radius-none">
                    </div>
                </div>
            </div>{{-- 左 --}}
            {{-- その他マスタ：資料 --}}
            <div class="<?= CLASS_COL_RIGHT ?>">
                <div class="row">
                    <div class="<?= CLASS_COL_OTHERS ?>">
                         {{-- 売却出品番号 --}}
                         <div class="row mb-2 border-first-row">
                            <div class="<?= CLASS_COL_TITLE_1 ?>">
                                <div class="bg-title text-center form-control border-radius-none px-0"><label>売却出品番号</label></div>
                            </div>
                            <div class="col ps-sm-0">
                                <input id="lblExhibitNum" name="lblExhibitNum" readonly type="text" autocomplete="off" value=""
                                tabindex="-1" class="form-control border-radius-none shadow-none">
                            </div>
                        </div>
                        {{-- 資料 --}}
                        <div class="row mb-2">
                            <div class="fs-5 fw-semibold">資料</div>
                        </div>
                        {{-- 日付 --}}
                        <div class="row mb-2 align-items-center">
                            <div class="<?= CLASS_COL_OTHERS_TIT ?>">
                                <div class="form-check">
                                    <input class="form-check-input" id="chkDocDate" name="chkDocDate" type="checkbox" tabindex="-1" onchange="EnableEdit(this, 'txtDocDate')"
                                        @if ($car->doc_date_flag == CommonConst::CheckBox_Checked_Cd) checked @endif>
                                    <label class="form-check-label align-middle" id="lblDocDate" for='chkDocDate'>日付</label>
                                </div>
                            </div>
                            <div class="<?= CLASS_COL_OTHERS_TEXT ?>">
                                <input id="txtDocDate" name="txtDocDate" type="date" autocomplete="off" maxlength="10" value="{{ $car->doc_date ?? '' }}"
                                    class="form-control border-radius-none" min="{{ CommonConst::Date_Min_1 }}"
                                    max="{{ date(CommonConst::Date_Format_Calendar, strtotime('last day of December this year +1 years')) }}"
                                    @if ($car->doc_date_flag != CommonConst::CheckBox_Checked_Cd) readonly @endif>
                            </div>
                        </div>
                        {{-- その他マスタ：資料 --}}
                        @foreach ($others as $item)
                            @if ($item->id == CommonConst::Other_Type_Doc_Id)
                                <div class="row mb-2 align-items-center">
                                    @php
                                        $line = $item->id . '_' . $item->line;
                                        $margin = '';
                                        $path = CommonConst::Upload_Folder_Car . ($car->id ?? 0) . '/' . $item->id . '/' . $item->line;
                                        $textVisible = $item->type_text == CommonConst::CheckBox_Checked_Cd ? '' : 'visually-hidden';
                                        $fileVisible = $item->type_file == CommonConst::CheckBox_Checked_Cd ? '' : 'visually-hidden';
                                        if ($item->type_text == CommonConst::CheckBox_Checked_Cd && $item->type_file == CommonConst::CheckBox_Checked_Cd) {
                                            $margin = 'mb-md-0 mb-2';
                                        }

                                        $fileNm = '';
                                        $lbClass = 'pointer-none shadow-none';
                                        $attrLink = '';
                                        $visually = 'visually-hidden';
                                        $exFile = '0';
                                        if (!empty($item->file_content)) {
                                            $filePath = asset(CommonConst::Upload_Folder_Path_Url . $item->file_content);
                                            $fileNm = CommonFunc::getFileName($filePath);
                                            $attrLink = 'target="_blank" href="' . $filePath . '"';
                                            $visually = '';
                                            $lbClass = 'cursor-pointer';
                                            $exFile = '1';
                                        }
                                        $readOnly = ($item->select_flag == CommonConst::CheckBox_Checked_Cd) ? '' : 'readonly';
                                    @endphp
                                    <div class="<?= CLASS_COL_OTHERS_TIT ?>">
                                        <div class="form-check">
                                            <input class="form-check-input chkSelect{{ $item->id }}" id="chkSelect{{ $line }}" name="chkSelect{{ $line }}"
                                                type="checkbox" tabindex="-1" onchange="EnableEdit(this, 'txtTextCon{{ $line }}', '{{ $line }}')"
                                                @if ($item->select_flag == CommonConst::CheckBox_Checked_Cd) checked @endif>
                                            <label class="form-check-label align-middle" id="lblTextCon{{ $line }}" for='chkSelect{{ $line }}'>{{ $item->name }}</label>
                                        </div>
                                        <input type="hidden" name="hidOtherLine{{ $item->id }}[]" value="{{ $item->line }}">
                                    </div>
                                    <div class="<?= CLASS_COL_OTHERS_TEXT ?> {{ $margin }}">
                                        <input id="txtTextCon{{ $line }}" name="txtTextCon{{ $item->id }}[]" type="text" autocomplete="off" maxlength="30"
                                            value="{{ $item->text_content ?? '' }}" class="form-control border-radius-none {{ $textVisible }}" {{ $readOnly }}>
                                    </div>
                                    <div class="<?= CLASS_COL_OTHERS_FILE ?>">
                                        <div class="{{ $fileVisible }}">
                                            <div class="d-flex align-items-center">
                                                <a id="linkFile{{ $line }}" {!! $attrLink !!} tabindex="-1" class="w-file">
                                                    <input readonly type="text" id="lblFile{{ $line }}" value="{{ $fileNm }}"
                                                        class="form-control border-radius-none text-truncate {{ $lbClass }}" tabindex="-1" />
                                                </a>
                                                <button id="btnSelFile{{ $line }}" type="button" tabindex="-1" class="btn mx-1 p-0 border-white"
                                                    onclick="ShowFileDialog('File{{ $line }}')"><i class="bi bi-upload text-success fs-4"></i></button>
                                                <button id="btnDelFile{{ $line }}" type="button" tabindex="-1" class="btn p-0 border-white {{ $visually }}"
                                                    onclick="DelFile('File{{ $line }}')"><i class="bi bi-x-circle text-danger fs-4"></i></button>
                                                <input id="exFile{{ $line }}" name="exFile{{ $item->id }}[]" type="hidden" value="{{ $exFile }}">
                                                <input id="fileFile{{ $line }}" type="file" class="visually-hidden" tabindex="-1"
                                                    onchange="ChangeFile('File{{ $line }}', '{{ $path }}')" />
                                                <input id="tmpFile{{ $line }}" name="tmpFile{{ $item->id }}[]" type="hidden" />
                                                <input id="oldFile{{ $line }}" name="oldFile{{ $item->id }}[]" type="hidden" value="{{ $item->file_content }}"
                                                    data-show="{{ $exFile }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="<?= CLASS_COL_OTHERS ?>">
                        {{-- 付属品 --}}
                        <div class="row mb-2">
                            <div class="fs-5 fw-semibold">付属品</div>
                        </div>
                        {{-- 日付 --}}
                        <div class="row mb-2 align-items-center">
                            <div class="<?= CLASS_COL_OTHERS_TIT ?>">
                                <div class="form-check">
                                    <input class="form-check-input" id="chkAccessoryDate" name="chkAccessoryDate"
                                        type="checkbox" tabindex="-1" onchange="EnableEdit(this, 'txtAccessoryDate')"
                                        @if ($car->accessory_date_flag == CommonConst::CheckBox_Checked_Cd) checked @endif>
                                    <label class="form-check-label" id="lblAccessoryDate" for='chkAccessoryDate'>日付</label>
                                </div>
                            </div>
                            <div class="<?= CLASS_COL_OTHERS_TEXT ?>">
                                <input id="txtAccessoryDate" name="txtAccessoryDate" type="date" autocomplete="off" maxlength="10" value="{{ $car->accessory_date ?? '' }}"
                                    class="form-control border-radius-none" min="{{ CommonConst::Date_Min_1 }}"
                                    max="{{ date(CommonConst::Date_Format_Calendar, strtotime('last day of December this year +1 years')) }}"
                                    @if ($car->accessory_date_flag != CommonConst::CheckBox_Checked_Cd) readonly @endif>
                            </div>
                        </div>
                        {{-- その他マスタ：付属品 --}}
                        @foreach ($others as $item)
                            @if ($item->id == CommonConst::Other_Type_Accessory_Id)
                                <div class="row mb-2 align-items-center">
                                    @php
                                        $line = $item->id . '_' . $item->line;
                                        $margin = '';
                                        $path = CommonConst::Upload_Folder_Car . ($car->id ?? 0) . '/' . $item->id . '/' . $item->line;
                                        $textVisible = $item->type_text == CommonConst::CheckBox_Checked_Cd ? '' : 'visually-hidden';
                                        $fileVisible = $item->type_file == CommonConst::CheckBox_Checked_Cd ? '' : 'visually-hidden';
                                        if ($item->type_text == CommonConst::CheckBox_Checked_Cd && $item->type_file == CommonConst::CheckBox_Checked_Cd) {
                                            $margin = 'mb-lg-0 mb-2';
                                        }

                                        $fileNm = '';
                                        $lbClass = 'pointer-none shadow-none';
                                        $attrLink = '';
                                        $visually = 'visually-hidden';
                                        $exFile = '0';
                                        if (!empty($item->file_content)) {
                                            $filePath = asset(CommonConst::Upload_Folder_Path_Url . $item->file_content);
                                            $fileNm = CommonFunc::getFileName($filePath);
                                            $attrLink = 'target="_blank" href="' . $filePath . '"';
                                            $visually = '';
                                            $lbClass = 'cursor-pointer';
                                            $exFile = '1';
                                        }
                                        $readOnly = ($item->select_flag == CommonConst::CheckBox_Checked_Cd) ? '' : 'readonly';
                                    @endphp
                                    <div class="<?= CLASS_COL_OTHERS_TIT ?>">
                                        <div class="form-check">
                                            <input class="form-check-input chkSelect{{ $item->id }}" id="chkSelect{{ $line }}" name="chkSelect{{ $line }}"
                                                type="checkbox" tabindex="-1" onchange="EnableEdit(this, 'txtTextCon{{ $line }}', '{{ $line }}')"
                                                @if ($item->select_flag == CommonConst::CheckBox_Checked_Cd) checked @endif>
                                            <label class="form-check-label align-middle" id="lblTextCon{{ $line }}" for='chkSelect{{ $line }}'>{{ $item->name }}</label>
                                        </div>
                                        <input type="hidden" name="hidOtherLine{{ $item->id }}[]" value="{{ $item->line }}">
                                    </div>
                                    <div class="<?= CLASS_COL_OTHERS_TEXT ?> {{ $margin }}">
                                        <input id="txtTextCon{{ $line }}" name="txtTextCon{{ $item->id }}[]" type="text" autocomplete="off" maxlength="30"
                                            value="{{ $item->text_content ?? '' }}" class="form-control border-radius-none {{ $textVisible }}" {{ $readOnly }}>
                                    </div>
                                    <div class="<?= CLASS_COL_OTHERS_FILE ?>">
                                        <div class="{{ $fileVisible }}">
                                            <div class="d-flex align-items-center">
                                                <a id="linkFile{{ $line }}" {!! $attrLink !!} tabindex="-1" class="w-file">
                                                    <input readonly type="text" id="lblFile{{ $line }}" value="{{ $fileNm }}"
                                                        class="form-control border-radius-none text-truncate {{ $lbClass }}" tabindex="-1" />
                                                </a>
                                                <button id="btnSelFile{{ $line }}" type="button" tabindex="-1" class="btn mx-1 p-0 border-white"
                                                    onclick="ShowFileDialog('File{{ $line }}')"><i class="bi bi-upload text-success fs-4"></i></button>
                                                <button id="btnDelFile{{ $line }}" type="button" tabindex="-1" class="btn p-0 border-white {{ $visually }}"
                                                    onclick="DelFile('File{{ $line }}')"><i class="bi bi-x-circle text-danger fs-4"></i></button>
                                                <input id="exFile{{ $line }}" name="exFile{{ $item->id }}[]" type="hidden" value="{{ $exFile }}">
                                                <input id="fileFile{{ $line }}" type="file" class="visually-hidden" tabindex="-1"
                                                    onchange="ChangeFile('File{{ $line }}', '{{ $path }}')" />
                                                <input id="tmpFile{{ $line }}" name="tmpFile{{ $item->id }}[]" type="hidden" />
                                                <input id="oldFile{{ $line }}" name="oldFile{{ $item->id }}[]" type="hidden" value="{{ $item->file_content }}"
                                                    data-show="{{ $exFile }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        {{-- その他 --}}
                        <div class="row mb-2">
                            <div class="<?= CLASS_COL_OTHERS_TIT ?>">
                                <div class="form-check">
                                    <input class="form-check-input" id="chkAccessoryOthers" name="chkAccessoryOthers" type="checkbox"
                                        onchange="EnableEdit(this, 'txtAccessoryOthers')" tabindex="-1"
                                        @if ($car->accessory_others_flag == CommonConst::CheckBox_Checked_Cd) checked @endif>
                                    <label class="form-check-label" id="lblAccessoryOthers" for='chkAccessoryOthers'>その他</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col">
                                <textarea id="txtAccessoryOthers" name="txtAccessoryOthers" maxlength="300" rows="5"
                                    @if ($car->accessory_others_flag != CommonConst::CheckBox_Checked_Cd) readonly @endif
                                    class="form-control border-radius-none">{{ $car->accessory_others ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>{{-- 右 --}}
        </div>{{-- 基本情報・その他マスタ --}}
        {{-- 請求金額情報 --}}
        <div class="row">
            <div class="<?= CLASS_COL_MONEY ?>">
                <div class="row mb-2">
                    <div class="fs-5 fw-semibold">請求金額情報</div>
                </div>
                {{-- 車両金額 --}}
                <div class="row mb-sm-0 mb-2 border-first-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblCarAmount">車両金額</label></div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_1 ?> px-md-0 mb-sm-0 mb-2">
                        <input id="txtCarAmount" name="txtCarAmount" type="text" autocomplete="off" maxlength="14"
                            value="{{ $car->car_amount ?? '' }}" class="form-control border-radius-none allow_money_minus text-end txt-billing txt-amount">
                    </div>
                    <div class="<?= CLASS_COL_TITLE_1 ?> px-md-0">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblConTax">消費税</label>
                        </div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_1 ?>">
                        <input id="txtConTax" name="txtConTax" type="text" autocomplete="off" maxlength="13"
                            value="{{ $car->consumption_tax ?? '' }}" class="form-control border-radius-none allow_money text-end txt-billing">
                    </div>
                </div>
                {{-- 自動車税 --}}
                <div class="row mb-sm-0 mb-2 border-second-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblCarTax">自動車税</label>
                        </div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_1 ?> px-md-0 mb-sm-0 mb-2">
                        <input id="txtCarTax" name="txtCarTax" type="text" autocomplete="off" maxlength="13" value="{{ $car->car_tax ?? '' }}"
                            class="form-control border-radius-none allow_money text-end txt-billing">
                    </div>
                    <div class="<?= CLASS_COL_TITLE_1 ?> px-md-0">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblRDeposit">R預託金</label></div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_1 ?>">
                        <input id="txtRDeposit" name="txtRDeposit" type="text" autocomplete="off" maxlength="13" value="{{ $car->r_deposit ?? '' }}"
                            class="form-control border-radius-none allow_money text-end txt-billing">
                    </div>
                </div>
                {{-- 自税相当額 --}}
                <div class="row mb-sm-0 mb-2 border-second-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblSelfTax">自税相当額</label></div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_1 ?> px-md-0 mb-sm-0 mb-2">
                        <input id="txtSelfTax" name="txtSelfTax" type="text" autocomplete="off" maxlength="13" value="{{ $car->self_tax ?? '' }}"
                            class="form-control border-radius-none allow_money text-end txt-billing">
                    </div>
                    <div class="<?= CLASS_COL_TITLE_1 ?> px-md-0">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblWinningBid">落札料</label></div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_1 ?>">
                        <input id="txtWinningBid" name="txtWinningBid" type="text" autocomplete="off" maxlength="13" value="{{ $car->winning_bid ?? '' }}"
                            class="form-control border-radius-none allow_money text-end txt-billing">
                    </div>
                </div>
                {{-- 陸送料 --}}
                <div class="row mb-sm-0 mb-2 border-second-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblShippingFee">陸送料</label></div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_1 ?> px-md-0 mb-sm-0 mb-2">
                        <input id="txtShippingFee" name="txtShippingFee" type="text" autocomplete="off" maxlength="13" value="{{ $car->shipping_fee ?? '' }}"
                            class="form-control border-radius-none allow_money text-end txt-billing">
                    </div>
                    <div class="<?= CLASS_COL_TITLE_1 ?> px-md-0">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblOtherFee">その他</label></div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_1 ?>">
                        <input id="txtOtherFee" name="txtOtherFee" type="text" autocomplete="off" maxlength="14" value="{{ $car->other_fee ?? '' }}"
                            class="form-control border-radius-none allow_money_minus text-end txt-billing">
                    </div>
                </div>
                {{-- 加修費 --}}
                <div class="row mb-sm-0 mb-2 border-second-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblRepairFee">加修費</label></div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_1 ?> px-md-0">
                        <input id="txtRepairFee" name="txtRepairFee" type="text" autocomplete="off" maxlength="14" value="{{ $car->repair_fee ?? '' }}"
                            class="form-control border-radius-none allow_money_minus text-end txt-billing txt-amount">
                    </div>
                </div>
                {{-- 自動車代金小計 --}}
                <div class="row mb-sm-0 mb-2 border-second-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label>自動車代金小計</label></div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_1 ?> px-md-0">
                        <input id="lblBillingSubtotal" name="lblBillingSubtotal" readonly type="text" autocomplete="off" value="{{ $car->billing_subtotal ?? '' }}"
                            tabindex="-1" class="form-control border-radius-none allow_money_minus text-end shadow-none">
                    </div>
                </div>
                {{-- 請求合計 --}}
                <div class="row mb-4 border-second-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label>請求合計</label></div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_1 ?> px-md-0">
                        <input id="lblBillingTotal" name="lblBillingTotal" readonly type="text" autocomplete="off" value="{{ $car->billing_total ?? '' }}" tabindex="-1"
                            class="form-control border-radius-none allow_money_minus text-end shadow-none">
                    </div>
                </div>
            </div>{{-- 左 --}}
        </div>{{-- 請求金額情報 --}}
        {{-- 支払情報 --}}
        <div class="row">
            <div class="<?= CLASS_COL_MONEY ?>">
                <div class="row mb-2">
                    <div class="fs-5 fw-semibold">支払情報</div>
                </div>
                {{-- 支払日付 --}}
                <div class="row mb-sm-0 mb-2 border-first-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblPaymentDate">支払日付</label></div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_1 ?> px-md-0 mb-sm-0 mb-2">
                        <input id="txtPaymentDate" name="txtPaymentDate" type="date" autocomplete="off" maxlength="10" value="{{ $car->payment_date ?? '' }}"
                            class="form-control border-radius-none" min="{{ CommonConst::Date_Min_1 }}"
                            max="{{ date(CommonConst::Date_Format_Calendar, strtotime('last day of December this year +1 years')) }}">
                    </div>
                    <div class="<?= CLASS_COL_TITLE_1 ?> px-md-0">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label id="lblExhibitTimes">出品回数</label></div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_1 ?>">
                        <input id="txtExhibitTimes" name="txtExhibitTimes" type="text" autocomplete="off" maxlength="13" value="{{ $car->exhibit_times ?? '' }}"
                            class="form-control border-radius-none allow_money text-end">
                    </div>
                </div>
                {{-- 自動車代金小計 --}}
                <div class="row mb-sm-0 mb-2 border-second-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label>自動車代金小計</label></div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_1 ?> px-md-0">
                        <input id="lblPaymentSubtotal" name="lblPaymentSubtotal" readonly type="text" autocomplete="off" value="{{ $car->payment_subtotal ?? '' }}"
                            tabindex="-1" class="form-control border-radius-none allow_money text-end shadow-none">
                    </div>
                </div>
                {{-- 自動車税小計 --}}
                <div class="row mb-sm-0 mb-2 border-second-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label>自動車税小計</label></div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_1 ?> px-md-0">
                        <input id="lblPaymentTax" name="lblPaymentTax" readonly type="text" autocomplete="off" value="{{ $car->payment_tax ?? '' }}" tabindex="-1"
                            class="form-control border-radius-none allow_money text-end shadow-none">
                    </div>
                </div>
                {{-- 支払合計 --}}
                <div class="row mb-sm-0 mb-2 border-second-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label>支払合計</label></div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_1 ?> px-md-0">
                        <input id="lblPaymentTotal" name="lblPaymentTotal" readonly type="text" autocomplete="off" value="{{ $car->payment_total ?? '' }}" tabindex="-1"
                            class="form-control border-radius-none allow_money text-end shadow-none">
                    </div>
                </div>
                {{-- 利益 --}}
                <div class="row mb-2 border-second-row">
                    <div class="<?= CLASS_COL_TITLE_1 ?>">
                        <div class="bg-title text-center form-control border-radius-none px-0"><label>利益</label></div>
                    </div>
                    <div class="<?= CLASS_COL_TEXT_1 ?> px-md-0">
                        <input id="lblBenefit" name="lblBenefit" readonly type="text" autocomplete="off" value="{{ $car->benefit ?? '' }}" tabindex="-1"
                            class="form-control border-radius-none allow_money text-end shadow-none">
                    </div>
                </div>
            </div>{{-- 左 --}}
        </div>{{-- 支払情報 --}}
        {{-- 支払一覧 --}}
        <div class="row mb-4">
            <div class="text-end">
                <button type="button" class="btn btn-none border-0" style="padding-right: 18px;" tabindex="-1" onclick="AddRow()">
                    <i class="bi bi-plus-circle-fill icon-green"></i>
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="tr-head text-center">
                            <th scope="col" class="mw-c-100"><label id="lblSPaymentDate">日付</label></th>
                            <th scope="col" class="mw-c-100"><label id="lblSAuctionResult">成約結果</label></th>
                            <th scope="col" class="mw-c-150"><label id="lblSCarNumber">号車</label></th>
                            <th scope="col" class="mw-c-150"><label id="lblSVenue">会場</label></th>
                            <th scope="col" class="mw-c-100"><label id="lblSHeldNumber">開催数</label></th>
                            <th scope="col" class="mw-c-100"><label id="lblSExhibitNumber">出品番号</label></th>
                            <th scope="col" class="mw-c-150"><label id="lblSCarAmount">車両金額</label></th>
                            <th scope="col" class="mw-c-100"><label id="lblSConTax">消費税</label></th>
                            <th scope="col" class="mw-c-100"><label id="lblSRDeposit">R預託金</label></th>
                            <th scope="col" class="mw-c-150"><label id="lblSOthers">その他</label></th>
                            <th scope="col" class="mw-c-100"><label id="lblSCarTax">自動車税</label></th>
                            <th scope="col" class="mw-c-100"><label id="lblSExhibitFee">出品料</label></th>
                            <th scope="col" class="mw-c-100"><label id="lblSClosingFee">成約料</label></th>
                            <th scope="col" class="mw-c-100"><label id="lblSReAuctionFee">再セリ料</label></th>
                            <th scope="col" class="mw-c-150"><label>自動車代金小計</label></th>
                            <th scope="col" class="w-c-50 bg-white border-0 border-bottom border-top border-white p-0">
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tblPayments"></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
