@extends('layouts.layout')
<!-- U03-1 -->
@section('title', '雇用契約プリセット')

@push('scripts')
    <script>
        function Save(url, yesFunc, noFunc) {
            submitFormAjax(url);
        }
    </script>
@endpush

@section('content')

    <div class="row align-items-center mb-2">
        <div class="d-sm-flex flex-wrap align-items-center">
            <div class="d-sm-flex align-items-center flex-wrap mb-lg-0 mb-2 me-sm-2">
                <label class="me-2">入社月</label>
                <div>
                    <input id="txtDate" name="txtDate" type="month" autocomplete="off" maxlength="10" min=""
                        max="" value="" class="form-control border-radius-none">
                </div>
            </div>

            <div class="d-sm-flex align-items-center flex-wrap mb-sm-0 mb-2 me-sm-2">
                <label class="me-2">事業所</label>
                <div>
                    <select class="form-select border-radius-none " id="cmbOffice" name="cmbOffice">
                        <option></option>
                        </option>
                    </select>
                </div>
            </div>

            <div class="d-sm-flex align-items-center flex-wrap mb-sm-0 mb-2 me-sm-2">
                <label class="me-2">所属</label>
                <div>
                    <select class="form-select border-radius-none " id="cmbBelong" name="cmbBelong">
                        <option></option>
                        <option value="belong_cd ">
                        </option>
                    </select>
                </div>
            </div>

            <div class="d-sm-flex align-items-center flex-wrap mb-lg-0 mb-2 me-sm-2">
                <label class="me-2">社員番号</label>
                <div>
                    <input id="txtIdStaff" name="txtIdStaff" type="text" autocomplete="off" maxlength="7" value=""
                        class="form-control border-radius-none">
                </div>
            </div>

            <div class="d-sm-flex align-items-center flex-wrap mb-lg-0 mb-2 me-sm-2">
                <label class="me-2">氏名</label>
                <div>
                    <input id="txtNameStaff" name="txtNameStaff" type="text" autocomplete="off" maxlength="30"
                        value="" class="form-control border-radius-none">
                </div>
            </div>

            <div class="d-sm-flex align-items-center flex-wrap mb-sm-0 mb-2 me-sm-2">
                <label class="me-2">ステータス</label>
                <div>
                    <select class="form-select border-radius-none " id="cmbWStatus" name="cmbWStatus">
                        <option></option>
                        </option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-secondary" id="btnSrc" name="btnSrc" value="search"
                tabindex="-1">検索</button>
            <div class="align-items-center">
                <a class="btn btn-blue">通知</a>
            </div>
            <div class="align-items-center">
                <a class="btn btn-blue" href="">新規</a>
            </div>

            <div class="text-end" tabindex="-1">
                <button class="btn-none ms-3 h2 h-100" type="button" value="exportCSV" onclick="">
                    <i class="bi bi-box-arrow-right d-flex"></i>
                </button>
            </div>
        </div>
    </div>
    
    <div class="row align-items-center mb-2">
        <div class="d-sm-flex flex-wrap align-items-center">
            <div class="d-sm-flex align-items-center flex-wrap mb-lg-0 mb-2 me-sm-2">
                <label class="me-2">入社月</label>
                <div>
                    <input id="txtDate" name="txtDate" type="month" autocomplete="off" maxlength="10" min=""
                        max="" value="" class="form-control border-radius-none">
                </div>
            </div>
    
            <div class="d-sm-flex align-items-center flex-wrap mb-sm-0 mb-2 me-sm-2">
                <label class="me-2">事業所</label>
                <div>
                    <select class="form-select border-radius-none " id="cmbOffice" name="cmbOffice">
                        <option></option>
                    </select>
                </div>
            </div>
    
            <div class="d-sm-flex align-items-center flex-wrap mb-sm-0 mb-2 me-sm-2">
                <label class="me-2">所属</label>
                <div>
                    <select class="form-select border-radius-none " id="cmbBelong" name="cmbBelong">
                        <option></option>
                        <option value="belong_cd "></option>
                    </select>
                </div>
            </div>
    
            <div class="d-sm-flex align-items-center flex-wrap mb-lg-0 mb-2 me-sm-2">
                <label class="me-2">社員番号</label>
                <div>
                    <input id="txtIdStaff" name="txtIdStaff" type="text" autocomplete="off" maxlength="7" value=""
                        class="form-control border-radius-none">
                </div>
            </div>
    
            <div class="d-sm-flex align-items-center flex-wrap mb-lg-0 mb-2 me-sm-2">
                <label class="me-2">氏名</label>
                <div>
                    <input id="txtNameStaff" name="txtNameStaff" type="text" autocomplete="off" maxlength="30"
                        value="" class="form-control border-radius-none">
                </div>
            </div>
    
            <div class="d-sm-flex align-items-center flex-wrap mb-sm-0 mb-2 me-sm-2">
                <label class="me-2">ステータス</label>
                <div>
                    <select class="form-select border-radius-none " id="cmbWStatus" name="cmbWStatus">
                        <option></option>
                    </select>
                </div>
            </div>
    
            <div class="ms-3 d-flex align-items-center order-1 order-sm-2">
                <button class="btn-none h2" type="button" value="exportCSV" onclick="">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </div>
    
            <div class="d-flex flex-wrap align-items-center order-2 order-sm-3">
                <div class="d-flex flex-wrap align-items-center mb-2 mb-sm-0 me-sm-2">
                    <button type="submit" class="btn btn-secondary">検索</button>
                </div>
    
                <div class="align-items-center mb-2 mb-sm-0 me-sm-2">
                    <a class="btn btn-blue">通知</a>
                </div>
    
                <div class="align-items-center">
                    <a class="btn btn-blue" href="">新規</a>
                </div>
            </div>
        </div>
    </div>
    
    


@endsection


{{-- @foreach ($prods as $p)
    id:     {{$p->id}}<br>
    name:       {{$p->name}}<br>
    ofcd:       {{$p->office_cd}}<br>
    select:     {{$p->selected}}
    <br>
    {{ $loop->iteration }}
    <br><br>
@endforeach
<br>
<br>

@foreach ($upOfices as $p)
    {{$p->id}} - {{$p->file_name}} - {{$p->office_cd}} - {{$p->selected}}
    <br>
@endforeach


email_app2 --}}
{{-- @foreach ($item as $p)
    {{$p->email_app2}} ---------------------- {{$p->name_app2}}
    <br> 
    <div>http://localhost:88/TechnetGobal/public/u03/information/2</div>
    <button type="button" onclick="Save(`{{route('l')}}`)">Login</button>
    <button type="button" class="btn btn-blue mb-2" onclick="Save(`{{route('u03.saveInfo')}}`)">保存</button>
    
    <br> --}}
{{-- @endforeach --}}

<?php
//     function randomPassword($length = 8) {
//         $letters = str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
//         $numbers = str_shuffle('0123456789');
//         $password = substr($letters, 0, 4) . substr($numbers, 0, 4);
//         return str_shuffle(substr($password, 0, $length));
//     }
// $password = generateRandomPassword();
// echo $password;
?>
<style>
    #myTextarea {
        width: 400px;
        /* Đặt chiều rộng */
        height: 200px;
        /* Đặt chiều cao */
        resize: none;
        /* Ngăn chặn việc thay đổi kích thước bằng cách kéo góc */
        overflow: hidden;
        /* Ngăn chặn hiển thị thanh cuộn */
    }
</style>
{{-- <textarea id="myTextarea"><?php echo htmlspecialchars($content); ?></textarea> --}}
