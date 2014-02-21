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
$(document).ready(function(){

    $('.ac_results').bindMouseWheel();

    $('.music-note-icon').on('mouseenter',function(){
        
        $('.plays-tooltip').addClass('active');

    });

    $('.music-note-icon').on('mouseleave',function(){
        
        $('.plays-tooltip').removeClass('active');

    });   

    $('.my-account-menu').on('click',function(){
        
        $('.account-menu-dropdown').toggleClass('active');
        
    });

    $('.my-account-menu').on('mouseleave',function(e){

         if (e.offsetX < 0 || e.offsetX > $(this).width() || e.offsetY < 0) {

            $('.account-menu-dropdown').removeClass('active');
        }       
    });

    $('.album-cover-container ul').bindMouseWheel();
    $('.album-cover-container ul').on('mouseleave',function(){
        $(this).removeClass('active');
        
    });
    
    
    $('.album-cover-container').on('mouseenter',function(){
        
        $(this).find('.toggleable').addClass('active');
        
    });
    
    $('.album-cover-container').on('mouseleave',function(){
        
        $(this).find('.toggleable').removeClass('active');
        
    });


    $('.playlist-menu-icon').on('click',function(){
        
        $(this).siblings('ul').toggleClass('active');
    });

    $('.left-scroll-button,.wishlist-icon').on('mouseenter',function(){
        

        $('.album-cover-container ul').removeClass('active');
        

    });

    $('.top-albums-carousel>ul>li').on('mouseleave',function(){

        $('.album-cover-container ul').removeClass('active');
    });

    $('.top-songs-filter-icon').on('mouseleave',function(e){

        if (e.offsetX < 0 || e.offsetX > $(this).width() || e.offsetY < 0) {

            $('.top-songs-filter-menu').removeClass('active');
        }

    });


    var ulPosition;

    
    $('.left-scroll-button').on('click',function(){
        


        var currentScrollLeft = $('.top-albums-carousel').scrollLeft();

        currentScrollLeft = currentScrollLeft - 660;


        $('.top-albums-carousel').animate({scrollLeft:currentScrollLeft});
        
    });
    
    
    $('.right-scroll-button').on('click',function(){
        



        var currentScrollLeft = $('.top-albums-carousel').scrollLeft();

        currentScrollLeft = currentScrollLeft + 660;


        $('.top-albums-carousel').animate({scrollLeft:currentScrollLeft});


        


        
    });

    $('.account-menu-dropdown').on('mouseleave',function(){
        
        $(this).removeClass('active');
    });
	$('.genre-column,.alpha-artist-list-column,.artist-column').bindMouseWheel();



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


    $('.sr-albums-prev').on('click',function(){
        var currentScrollLeft = $('.search-results-albums').scrollLeft();
        currentScrollLeft = currentScrollLeft - 660;
        $('.search-results-albums').animate({

            scrollLeft:currentScrollLeft
        })


    });

    $('.sr-albums-next').on('click',function(){



        var currentScrollLeft = $('.search-results-albums').scrollLeft();
        currentScrollLeft = currentScrollLeft + 660;
        $('.search-results-albums').animate({

            scrollLeft:currentScrollLeft
        })

    });

    var multipleRowsChecked = false;
    $('.row-checkbox').on('click',function(){
        
        $(this).parent('.row').toggleClass('highlighted');

        var c = 0;

        $('.row-checkbox').each(function(){
            

            if($(this).is(':checked')) {

                c++;
                
            }

            if (c>=2) {

                $('.multi-select-icon').addClass('highlighted');
                multipleRowsChecked = true;
                
            } else {
                $('.multi-select-icon').removeClass('highlighted');
                multipleRowsChecked = false;
            }

        });


    });

    $('.add-to-playlist').on('mouseenter',function(){
        $(this).parents('ul').next('.playlist-menu').addClass('active');

    });

    $('.options-menu').on('mouseleave',function(){
        $(this).children('.playlist-menu').removeClass('active');
        $(this).removeClass('active');
        if(!multipleRowsChecked) {
            $('.multi-select-icon').removeClass('highlighted');
        }
    });

    $('.multi-select-icon').on('click',function(){

        $(this).siblings('.options-menu').addClass('active');
        $('.multi-select-icon').addClass('highlighted');

    });

    $('.multi-select-icon').on('mouseleave',function(e){

       if (e.offsetX > $(this).width() || e.offsetY < 0) {

            $('.options-menu').removeClass('active');
            $(this).removeClass('highlighted');
        }
    });

    $('.select-all').on('click',function(e){
        e.preventDefault();
        $('.row-checkbox').each(function(){
            $(this).prop('checked', true);
        });

    });

    $('.clear-all').on('click',function(e){
        e.preventDefault();
        $('.row-checkbox').each(function(){
            $(this).prop('checked', false);
        });

    });

    $('.playlist-menu').bindMouseWheel();

    $('.menu-btn').on('click',function(){

        $(this).siblings('.options-menu').addClass('active');
    });

    $('.menu-btn').on('mouseleave',function(e){

        if (e.offsetX > $(this).width() || e.offsetY < 0) {

            $('.options-menu').removeClass('active');
        }
    });



    $('.top-songs-filter-icon').on('click',function(){
        $(this).siblings('.top-songs-filter-menu').toggleClass('active');

    });

    $('.top-songs-filter-menu').on('mouseleave',function(){
        $(this).removeClass('active');

    });

    $('.featured-grid-item').on('mouseenter',function(){
        $(this).find('.featured-grid-menu').addClass('active');
        
    });
    
    $('.featured-grid-item').on('mouseleave',function(){
        $(this).find('.featured-grid-menu').removeClass('active');
        
    });






});