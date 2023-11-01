<select name="g1" id="select_g1">
        <option value="one">one</option>
        <option value="two">two</option>
        <option value="three">three</option>
    </select>
    <input type="button" id="btn1" value="Ẩn" />
<script>

$('#select_g1').on('change', function (e) {
document.getElementById("btn1").style.display = 'none';
    var optionSelected = $("option:selected", this);
    var valueSelected = this.value;
    alert(valueSelected);
});

</script>
