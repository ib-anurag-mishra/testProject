(function($) {

    $.fn.bindMouseWheel = function() {

        return this.each(function() {

            $(this).bind('mousewheel', function(e) {

                $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);
                //prevent page fom scrolling
                return false;

            });


        });

    };


}(jQuery));

$(document).ready(function(){
	$('.genre-column,.alpha-artist-list-column,.artist-column').bindMouseWheel();

    // $(document).on('scroll',function(){

    //     if($(document).scrollTop() >= 200) {
    //         $('.genres-page header').addClass('sticky');
            

    //     } else {

    //         $('.genres-page header').removeClass('sticky');
    //     }
        

    // });

    $('.genre-column a').on('click',function(){
        $('.genre-column a').removeClass('active');
        $(this).addClass('active');

    });


    $('.alpha-artist-list-column a').on('click',function(){
        $('.alpha-artist-list-column a').removeClass('active');
        $(this).addClass('active');

    });




});