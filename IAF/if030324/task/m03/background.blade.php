<style>
    #tbCareer>:not(caption)>*>* {
        padding: 0.5rem;
    }

    table>tbody>tr {
        vertical-align: middle;
    }
    .tbl-scrollbar {
        position: relative;
        height: 287px;
        overflow: auto;
        border: 1px solid #eee;
    }

    .tbl-wrapper-scroll-y {
        display: block;
    }
 
</style>

<!-- Modal background -->
<div class="modal fade" id="mdBackGround" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content p-4">
            <div class="modal-body text-center">
                <div class="text-center row">
                    <input class="border-0 fs-4 py-2" readonly value="経歴一覧" >
                </div>
                <div class="container-fluid">
                    <div class="table-responsive tbl-scrollbar tbl-wrapper-scroll-y">
                        <table id="tbCareer" class="table table-bordered">
                            <thead class="sticky-top top--1">
                                <tr id="headTbl" class="tr-head bg-title text-center p-1">
                                    <th scope="col" class="mw-c-100 text-center">始期</th>
                                    <th scope="col" class="mw-c-100 text-center">終期</th>
                                    <th scope="col" class="mw-c-200 text-center">事業所</th>
                                    <th scope="col" class="mw-c-200 text-center">所属</th>
                                    <th scope="col" class="mw-c-200 text-center">役職</th>
                                    <th scope="col" class="mw-c-100 text-center">正社員・パート</th>
                                    <th scope="col" class="mw-c-100 text-center">ステータス</th>
                                    <th scope="col" class="mw-c-100 text-center">年数</th>
                                </tr>
                            </thead>
                            <tbody id="bodyTbl">
                                @php
                                    $totalYear = 0 ;
                                    $num = 0;
                                    $totalMonth = 0;
                                @endphp
                                @foreach( $backgr as $item)
                                @php
                                    $start = date_create($item->date_join);
                                    $end = date_create($item->retire_date);
                                    if( empty($item->retire_date)){
                                        $end = date_create(date('Y-m-d H:i:s'));
                                    }
                                    $date = date_diff($start, $end);
                                    $date = $date->format('%a');
                                    $year = floor($date / 365);
                                    $month = floor(($date - ($year * 365)) / 30);
                                    $totalYear += $year;
                                    $totalMonth += $month;
                                    
                                @endphp
                                <tr>
                                    <td> {{ $item->date_join ? date_format(date_create($item->date_join), CommonConst::DATE_FORMAT_1) : ''}}</td>
                                    <td> {{ $item->retire_date ? date_format(date_create($item->retire_date), CommonConst::DATE_FORMAT_1) : '' }}</td>
                                    <td> {{$item->office_name}} </td>
                                    <td> {{$item->belong_name}} </td>
                                    <td> {{$item->position}} </td>
                                    <td>
                                        @foreach ($mstWorkTime as $cmb)
                                            @if($item->work_time_flag == $cmb->item_cd) {{$cmb->item_name}} @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($mstWorkStatus as $cmb)
                                            @if($item->work_status == $cmb->item_cd) {{$cmb->item_name}} @endif
                                        @endforeach
                                    </td>
                                    <td>{{$year}}年 {{$month}}ヶ月 </td>
                                </tr>
                                {{--   --}}
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @php
                        if($totalMonth > 12)
                        {
                            $num = floor($totalMonth / 12);
                            $totalYear += $num;
                            $totalMonth = $totalMonth - ($num * 12);
                        }
                    @endphp
                    <div class="text-end mt-2">合計: {{$totalYear}}年{{$totalMonth}}ヶ月</div>
                </div>
                <div class="text-center">
                    <button type="button" class="btn btn-blue" data-bs-dismiss="modal" aria-label="Close">閉じる</button>
                </div>
            </div>
        </div>
    </div>
    </div>
  <!-- Modal background -->