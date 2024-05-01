.fix {
    /* height: 46px; */
    /* background-color: #000; */
    position: fixed;
    top: 10px;
    /* left: 0; */
    /* right: 0; */
    z-index: 1;
}

$(document).ready(function () {
 // Change this to the value of your own.
    var scroll_offset = 150;

    $(window).scroll(function(){
        var $this = $(window);
        if( $('.btn-fixed').length ) {
            if( $this.scrollTop() > scroll_offset ) { 
                $('.btn-fixed').addClass('fix');
            } else {
                $('.btn-fixed').removeClass('fix');
            }	
        }
    });

});
