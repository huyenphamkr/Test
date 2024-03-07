{{-- <script>
$(document).ready(function () {
    let video = $('#InstrucVid')[0]; // video raw
    let vid = $('#InstrucVid');
    let btnOpen = $('#btnOpenVid');
    let divOpen = $('.txt-open');
    let btnClose = $('#btnCloseVid');
    let divAgree = $('#divAgree');
    let chkAgree = $('#chkAgree');
    let divConfirm =$('#divConfirm');
    let hid = 'd-none';

    $(video).on('loadedmetadata', function() {
        // Lấy thời lượng video và hiển thị nó
        var duration = video.duration;
        var minutes = Math.floor(duration / 60);
        var seconds = Math.floor(duration % 60);
        console.log('Thời lượng: ' + minutes + ' phút ' + seconds + ' giây');
    });

    //show and play video
    btnOpen.click(function() {
        setClass(btnClose,hid);
        video.play();
    });

    //click video
    vid.click(function() {
        video.paused ? video.play() : video.pause();
    });

    //video ended
    vid.on('ended', function() {
        //show btn close video
        btnClose.removeClass(hid);
        btnClose.focus();
    });

    btnClose.click(function(){
        //hidden btnOpenVid
        setClass(divOpen,hid);
        //show divAgree
        divAgree.removeClass(hid);
    })

    chkAgree.change(function() {
        this.checked ? divConfirm.removeClass(hid) : setClass(divConfirm,hid);
    });
});

function setClass(ele, classNm){
    if (!ele.hasClass(classNm)) ele.addClass(classNm);
}
</script>

<!-- GUi -->
<div class="row justify-content-center mb-2 rent-edit txt-open">
    <div class="col-xl-10 text-center">
        <button type="button" id="btnOpenVid" name="btnOpenVid" class="btn btn-secondary" onclick="ShowModal('mdVideo')">動画を再生する</button>
    </div>
</div>
<div class="row justify-content-center mb-4 txt-open">
    <div class="col-xl-9 text-center">
        <label>※クリックで動画が再生されます。音量等に注意ください。</label>
    </div>
</div>

<div id="divAgree" class="row mb-2 justify-content-center rent-edit d-none">
    <div class="col-auto">
        <div class="d-flex">
            <input type="checkbox" id="chkAgree" name="chkAgree" class="form-check-input p-2 me-2 ms-2">
            <label for="chkAgree" class="form-check-label">キャンセルポリシー及び、動画を視聴し、利用規約に同意します。</label>
        </div>
    </div>
</div>
<div id="divConfirm" class="row justify-content-center mb-4 rent-edit d-none">
    <div class="col-xl-10 text-center">
        <button type="button" id="btnConfirm" name="btnConfirm" class="btn btn-secondary" onclick="ChkAndConfirm()">入力内容確認画面へ　　＞＞</button>
    </div>
</div>
<!-- GUi --> --}}

<!-- Modal video -->
<div class="modal fade" id="mdVideo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content p-2">
            <div class="modal-header">
                <h5 class="modal-title">Video</h5>
                <button type="button" class="btn-close d-none" id="btnCloseVid" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <video id="InstrucVid" width="100%" height="100%">
                        <source src="{{asset("public/images/bunny.mp4")}}" type="video/mp4">
                    </video>                    
                </div>
                {{-- <div class="text-center">
                    <button type="button" class="btn btn-blue" data-bs-dismiss="modal" aria-label="Close">閉じる</button>
                </div> --}}
            </div>
        </div>
    </div>
    </div>
  <!-- Modal video -->