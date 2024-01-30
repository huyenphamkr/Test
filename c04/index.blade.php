
<style>

/* ảnh */
.card {
height: 100px;
text-shadow: 0 1px 3px rgba(0,0,0,0.6);
background-size: cover !important;
color: white;
position: relative;
border-radius: 5px;
margin-bottom: 20px;
}
.card-user {
position: absolute;
right: 10px;
top: 10px;
}
.card-category {
position: absolute;
top: 10px;
left: 10px;
font-size: 15px;
}
.card-description {
position: absolute;
bottom: 10px;
left: 10px;
}
.card-description h2 {
font-size: 22px;
}
.card-description p {
font-size: 15px;
}
.card-link {
position: absolute;
top: 0;
bottom: 0;
width: 100%;
z-index:2;
background: black;
opacity: 0;
}
.card-link:hover{
opacity: 0.1;
}
.features img {
width: 100px;
}
.features h2 {
font-size: 20px;
margin-bottom: 10px;
}
.features p {
font-size: 15px;
font-weight: lighter;
}
</style>


<?php
$bg_text_center = "table-secondary border-dark";
?>
<div class="row">
<head>
    <meta charset="utf-8">
    <title> home</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  </head>

  <body>


    <div class="wrapper-grey padded">
      <div class="container">
        <h2 class="text-center">Your next trip</h2>
        <div class="col-xs-12 col-sm-2">
            <div class="card" style="background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.2)), url('https://i1.wp.com/handluggageonly.co.uk/wp-content/uploads/2015/05/IMG_2813-s.jpg?w=1600&ssl=1');">
              <div class="card-category">1</div>
              <div class="card-user avatar avatar-large">X</div>
              <a class="card-link" href="#" ></a>
            </div>
          </div>
          <div class="col-xs-12 col-sm-2">
            <div class="card" style="background-color: gray;">
              <div class="card-category">2</div>
              <div class="card-user avatar avatar-large">X</div>
              <a class="card-link" href="#" ></a>
            </div>
          </div>
          <div class="col-xs-12 col-sm-2">
            <div class="card" style="background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.2)), url('https://i0.wp.com/handluggageonly.co.uk/wp-content/uploads/2016/04/IMG_5589.jpg?w=1600&ssl=1');">
              <div class="card-category">2</div>
              <img class="card-user avatar avatar-large" src="https://github.com/lewagon/bootstrap-challenges/blob/master/11-Airbnb-search-form/images/anne.jpg?raw=true">
              <a class="card-link" href="#" ></a>
            </div>
          </div>
          <div class="col-xs-12 col-sm-2">
            <div class="card" style="background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.2)), url('https://i0.wp.com/handluggageonly.co.uk/wp-content/uploads/2016/04/IMG_5589.jpg?w=1600&ssl=1');">
              <div class="card-category">2</div>
              <img class="card-user avatar avatar-large" src="https://github.com/lewagon/bootstrap-challenges/blob/master/11-Airbnb-search-form/images/anne.jpg?raw=true">
              <a class="card-link" href="#" ></a>
            </div>
          </div>
          <div class="col-xs-12 col-sm-2">
            <div class="card" style="background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.2)), url('https://i0.wp.com/handluggageonly.co.uk/wp-content/uploads/2016/03/Positano-Weather.jpg?w=1600&ssl=1');">
              <div class="card-category">3</div>
              <div class="card-description">
                <h2>Home</h2>
                <p>Lovely house</p>
              </div>
              <img class="card-user avatar avatar-large" src="https://github.com/lewagon/bootstrap-challenges/blob/master/11-Airbnb-search-form/images/anne.jpg?raw=true">
              <a class="card-link" href="#" ></a>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>


<div class="row justify-content-md-center">
    <i class="fas fa-times"></i>
    <div class="col col-md-8"  style="background-color: #6499e4">
        .col-md-6 .offset-md-3<br>
        雇用契約プリセット雇用契約プリセット雇用契約プリセット雇用契約プリセット<br>
        雇用契約プリセット雇用契約プリセット雇用契約プリセット雇用契約プリセット雇用契約プリセット雇用契約プリセット雇用契約プリセット<br>
    
    </div>
</div>
<div class="row">
    <div class="col-md-6 offset-md-3" style="background-color: #e0e7f1">
        .col-md-6 .offset-md-3<br>
        雇用契約プリセッット雇用契約プリセット雇用契約プリセット雇用契約プリセット<br>
        雇用契約プリセット雇用契約プリセット雇用契約プリセット雇用契約プリセット雇用契約プリセット雇用契約プリセット雇用契約プリセット雇用契約プリセット雇用契約プリセット雇用契約プリセット<br>
    </div>
</div>

<div class="edit-content">
    <div class="mb-2">
        <button type="button" class="btn btn-blue" tabindex="-1" onclick="submitFormAjax(`{{route('m01.saveCont')}}`)">保存</button>
        <button type="button" class="btn btn-outline-primary" tabindex="-1" onclick="BackPrev(`{{route('m01.edit', $office_cd)}}`)">キャンセル</button>
    </div>
    <input type="hidden" name="office_cd" id="hidOfficeCd" value="{{ $office_cd ?? '' }}">
    <input type="hidden" name="id" id="hidId" value="{{ $contract->id ?? '' }}">
    <input type="hidden" id="hidArrBenCon" value="{{!empty($arrBenCon) && count($arrBenCon) > 0 ? json_encode($arrBenCon) : '' }}">
    <input type="hidden" id="hidCheck" value="">


</div>




{{-- link --}}
https://stackoverflow.com/questions/4459379/preview-an-image-before-it-is-uploaded



//check upload ảnh lien tục


<script>
  function preview(ele) {
      let input = document.getElementById(ele + '_input');
      let tmp = document.getElementById('tmp_'+ele);
      let img = document.getElementById(ele);

      if (input.files && input.files[0]) {
          img.src = URL.createObjectURL(input.files[0]);
          tmp.value = input.files[0].name;
      }
  }

  function check() {
      let lastUploaded = 0;
      let emptyPosition = 0; // Vị trí ảnh trống cần phải upload
      let allEmpty = true; // Biến kiểm tra xem tất cả input có trống không hay không
      for (let i = 1; i < 6; i++) {
          let img = document.getElementById('frame' + i + '_input');
          if (img.files && img.files[0]) {
              allEmpty = false;
              // Kiểm tra xem số ảnh đã được chọn có liên tục từ 1 trở đi hay không
              if (parseInt(img.id.match(/\d+/)[0]) !== lastUploaded + 1) {
                  alert('Số ảnh không liên tục từ 1 trở đi. Vui lòng upload ảnh tại vị trí: ' + emptyPosition);
                  return;
              }
              lastUploaded = parseInt(img.id.match(/\d+/)[0]);
          } else if (emptyPosition === 0) {
              emptyPosition = i;
          }
      }
      // if (allEmpty) {
      //     alert('Vui lòng chọn ít nhất một ảnh.');
      // } else {
      //     alert('Số ảnh liên tục từ 1 trở đi.');
      // }
  }
  function dup(){
      for (let line = 1; line < 5; line++) {
          let tmp = document.getElementById('tmp_frame'+line);
          if(tmp.value)
          {
              for (let i = line+1; i < 6; i++) {
                  let tmpCheck = document.getElementById('tmp_frame'+i);
                  if(tmp.value == tmpCheck.value) {
                      alert('trung file vi tri '+line+' va '+i);
                      return false;
                  }
              }
          }
      }
      return true;
  }
</script>

<form>
  @for ($i = 1; $i < 6; $i++)
      <input type="file" id="frame{{$i}}_input" onchange="preview('frame{{$i}}')">
      <input type="hidden" id="tmp_frame{{$i}}" value="">
      <img id="frame{{$i}}" src="" width="100px" height="100px"/><br>
  @endfor
  <button type="button" onclick="dup()">Kiểm tra</button>

</form>

{{-- mail --}}
<pre>
  adsjjsbvbdfhbfa-flip-verticalsjcksdnvjcdfjv
  adkkdk
  sjjc:vj jasnjfa-spin

  s: <pre style="border: 0!important; margin-left: 30px;margin-top: -28px">aaa
sss</pre>

</pre>



{{-- 30/01/24--------------------------------------------------------------------------------------------------------- --}}

{{-- check lien tuc theo hidden exFile  --}}
function check() {
  let lastUploaded = 0;
  let emptyPosition = 0; // Vị trí ảnh trống cần phải upload
  let allEmpty = true; // Biến kiểm tra xem tất cả input có trống không hay không
  for (let i = 1; i < 6; i++) {
      let exFile = document.getElementById('ex_frame' + i);
      if (exFile.value == 1) {
          // Kiểm tra xem số ảnh đã được chọn có liên tục từ 1 trở đi hay không
          if (parseInt(exFile.id.match(/\d+/)[0]) !== lastUploaded + 1) {
              alert('Số ảnh không liên tục từ 1 trở đi. Vui lòng upload ảnh tại vị trí: ' + emptyPosition);
              return;
          }
          lastUploaded = parseInt(exFile.id.match(/\d+/)[0]);
      } else if (emptyPosition === 0) {
          emptyPosition = i;
      }
  }
}



{{-- zip file php --}}
https://nhanweb.com/nen-va-tao-file-zip-bang-php.html
https://kungfuphp.com/lap-trinh/tong-hop-php/tao-file-zip-trong-php.html
https://tinhoc88.com/tao-zip-va-nen-file-bang-php.html
https://laptrinhvienphp.com/bai-23-huong-dan-download-file-bang-php/
<script>
function HandlDisplayShowFile(name){
  const eleFile = $("#file" + name)[0] ? $("#file" + name)[0] : null;
  let checkLast = '';
  if (eleFile.files && eleFile.files[0]) {
      let line = parseInt(eleFile.id.match(/\d+/)[0]);
      if(line != totalFileUpload)
      {
          for (let index = line + 1; index <= totalFileUpload; index++) {
              const eleFileLater = $("#tmpFile" + index) ? $("#tmpFile" + index) : null;
           //   const eleOldLater = $("#oldFile" + index) ? $("#oldFile" + index) : null;
             // if (eleFileLater.val() != '' || eleOldLater.val() != ''){
                  checkLast = index;
                  DisplayShowFile(index);
              }
          }
          if(checkLast != 10 && checkLast){
              for (let index = checkLast -1 ; index >= 1; index--) {DisplayShowFile(index);}                
              DisplayShowFile(checkLast+1);
          }
          DisplayShowFile(line + 1);
      }        
  }
}
</cript>