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

    .modal-xl {
        --bs-modal-width: 95%;
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
                                    $totalYear += $item['year'];
                                    $totalMonth += $item['month'];
                                @endphp
                                <tr>
                                    <td> {{ $item['startConm'] ? date_format(date_create($item['startConm']), CommonConst::DATE_FORMAT_1) : ''}}</td>
                                    <td> {{ $item['endConm'] ? date_format(date_create($item['endConm']), CommonConst::DATE_FORMAT_1) : '' }}</td>
                                    <td> {{$item['officeConm']}} </td>
                                    <td> {{$item['belongConm']}} </td>
                                    <td> {{$item['positionConm']}} </td>
                                    <td>
                                        @foreach ($mstWorkTime as $cmb)
                                            @if($item['workTimeConm'] == $cmb->item_cd) {{$cmb->item_name}} @endif
                                        @endforeach
                                    </td>
                                    <td>{{$item['proTypeConm']}}
                                        @foreach ($mstWorkStatus as $cmb)
                                            @if($item['workStatusConm'] == $cmb->item_cd) {{$cmb->item_name}} @endif
                                        @endforeach
                                    </td>
                                    <td>{{$item['year']}}年 {{$item['month']}}ヶ月 </td>
                                </tr>
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