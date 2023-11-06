//input yên
<div class="input-group">
  <input type="text" class="form-control">
  <span class="input-group-text">円</span>
</div>


//add
 <div class="input-group">
   <input type="text" class="form-control border-radius-none">
   <button class="btn btn-outline-secondary border-radius-none" type="button">+</button>
</div>

//Hidden or Display
<script>
    var x = document.getElementById("cbb").value;
    if (x) {
        alert(x);
        document.getElementById("txtDateS").disabled = false;
    } else {
        alert('ronf');
        alert(x);
        document.getElementById("txtDateS").disabled = true;
    }
    disableFunc();
    function disableFunc() {
        var e = document.getElementById("cbb").value;
        if (e == 2) {
            document.getElementById("txtDateS").disabled = true;
        } else {
            document.getElementById("txtDateS").disabled = false;
        }
    }

    </script>
