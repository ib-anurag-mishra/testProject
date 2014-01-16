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

var flashvars = {};
var params = {};
var attributes = {};
attributes.id = "fmp_player";
swfobject.switchOffAutoHideShow();
swfobject.embedSWF("swf/fmp.swf?"+(Math.random()*1000000), "alt", "960", "100", "9.0.0", false, flashvars, params, attributes);

$(document).ready(function(){
	$('.genre-column,.alpha-artist-list-column,.artist-column').bindMouseWheel();

    // $(document).on('scroll',function(){

    //     if($(document).scrollTop() >= 200) {
    //         $('.genres-page header').addClass('sticky');
            

    //     } else {

    //         $('.genres-page header').removeClass('sticky');
    //     }
        

    // });

    $('.genre-column a').on('click',function(e){
        e.preventDefault();
        $('.genre-column a').removeClass('active');
        $(this).addClass('active');

    });


    $('.alpha-artist-list-column a').on('click',function(e){
        e.preventDefault();
        $('.alpha-artist-list-column a').removeClass('active');
        $(this).addClass('active');

    });





    var genreScrollAmount;
    $('.genre-scroll-up').on('click',function(){

        var currentScrollTop = $('.genre-column').scrollTop();
        var genreListHeight = $('.genre-column ul').height();
        var genreColumnHeight = $('.genre-column').height();

        genreScrollAmount = currentScrollTop + genreColumnHeight;



        $('.genre-column').animate({
            scrollTop: genreScrollAmount
        });
        

    });

    $('.genre-scroll-down').on('click',function(){
        var currentScrollTop = $('.genre-column').scrollTop();
        var genreListHeight = $('.genre-column ul').height();
        var genreColumnHeight = $('.genre-column').height();

        genreScrollAmount = currentScrollTop - genreColumnHeight;



        $('.genre-column').animate({
            scrollTop: genreScrollAmount
        });


        

    });


    var artistScollAmount;
    $('.artist-scroll-up').on('click',function(){



        var currentScrollTop = $('.artist-column').scrollTop();
        var artistListHeight = $('.artist-column ul').height();
        var artistColumnHeight = $('.artist-column').height();

        artistScrollAmount = currentScrollTop + artistColumnHeight;



        $('.artist-column').animate({
            scrollTop: artistScrollAmount
        });
           


    });

    $('.artist-scroll-down').on('click',function(){

        var currentScrollTop = $('.artist-column').scrollTop();
        var artistListHeight = $('.artist-column ul').height();
        var artistColumnHeight = $('.artist-column').height();

        artistScrollAmount = currentScrollTop - artistColumnHeight;



        $('.artist-column').animate({
            scrollTop: artistScrollAmount
        });        

    });




});