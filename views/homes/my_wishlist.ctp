<?php
/*
	 File Name : my_wishlist.ctp 
	 File Description : View page for wishlist information
	 Author : m68interactive
 */
?>
<?php

if ($this->Session->read('Config.language') == 'en') {
    $setLang = 'en';
} else {
    $setLang = 'es';
}
function ieversion()
{
	  ereg('MSIE ([0-9]\.[0-9])',$_SERVER['HTTP_USER_AGENT'],$reg);
	  if(!isset($reg[1])) {
		return -1;
	  } else {
		return floatval($reg[1]);
	  }
}
$ieVersion =  ieversion();

?>
<style type="text/css">

</style>
<script lenguage="javascript">
   var languageSet = '<?php echo $setLang; ?>';  
   
   
   $(document).ready(function() {
	$('.songdelete').click(function(e) {
		e.preventDefault();
		var parent = $(this).parent();		
               // alert(parent.attr('id'));
		$.ajax({
			type: 'post',
			url: webroot+'homes/removeWishlistSong/',
			data: 'ajax=1&delete=' + parent.attr('id').replace('wishlistsong-',''),
			beforeSend: function() {                            
				parent.animate({'backgroundColor':'#fb6c6c'},600);
			},
			success: function(data) { 
                              // alert(data);
				parent.slideUp(600,function() {
					parent.remove();
				});
			}
		});
	});
        
        $('.videodelete').click(function(e) {
		e.preventDefault();
		var parent = $(this).parent();		
                //alert(parent.attr('id').replace('wishlistvideo-',''));
		$.ajax({
			type: 'post',
			url: webroot+'homes/removeWishlistVideo/',
			data: 'ajax=1&delete=' + parent.attr('id').replace('wishlistvideo-',''),
			beforeSend: function() {                            
				parent.animate({'backgroundColor':'#fb6c6c'},600);
			},
			success: function() { 
                            //alert(1);
				parent.slideUp(600,function() {
					parent.remove();
				});
			}
		});
	});
});
   
   
 $(document).ready(function(){
	
	$('.my-wishlist-page .date-filter-button').addClass('active');
	$('.my-wishlist-page .music-filter-button').addClass('active');
	
	
	$('.my-wishlist-page .my-wishlist-filter-container div.filter').on('click',function(e){
            
        if($(this).hasClass('date-filter-button')){
            $('#sortForm #sort').val('date');
        } else if($(this).hasClass('song-filter-button')){
            $('#sortForm #sort').val('song');
        } else if($(this).hasClass('artist-filter-button')){
            $('#sortForm #sort').val('artist');
        } else if($(this).hasClass('album-filter-button')){
            $('#sortForm #sort').val('album');
        }
		if($(this).hasClass('active')) {
			
			if($(this).hasClass('toggled')) {
				
				$(this).removeClass('toggled');
                                $('#sortForm #sortOrder').val('asc');
				
			} else {
				
				$(this).addClass('toggled');
                                $('#sortForm #sortOrder').val('desc');
			}
			
			
		} else {
			$('.my-wishlist-page .my-wishlist-filter-container div.filter').removeClass('active');
			$(this).addClass('active');
                        $('#sortForm #sortOrder').val('asc');
			
			
		}
		
		$('#sortForm').submit();
	});
	
	
	$('.my-wishlist-page .my-wishlist-filter-container div.tab').on('click',function(e){
		if($(this).hasClass('active')) {
			
			if($(this).hasClass('toggled')) {
				
				$(this).removeClass('toggled');
				
			} else {
				
				$(this).addClass('toggled');
			}
			
			
		} else {
			$('.my-wishlist-page .my-wishlist-filter-container div.tab').removeClass('active');
			$(this).addClass('active');
			
			
		}
		
		
	});
	
	$('.my-wishlist-page .my-wishlist-scrollable').bind('mousewheel',function(e){
		

		$(this).scrollTop($(this).scrollTop()-e.originalEvent.wheelDeltaY);
		
		
		
		

	    //prevent page fom scrolling
	    return false;

		
	});
	
	$('.my-wishlist-page .my-video-wishlist-scrollable').bind('mousewheel',function(e){
		

		$(this).scrollTop($(this).scrollTop()-e.originalEvent.wheelDeltaY);
		
		
		
		

	    //prevent page fom scrolling
	    return false;

		
	});
	
	
	
	
	
	$('.my-wishlist-page .add-to-wishlist-button').on('click',function(e){
		e.preventDefault();
	
		$(this).siblings('.wishlist-popover').addClass('active');
	});
	
	$('.my-wishlist-page .wishlist-popover').on('mouseleave',function(e){
	
		$(this).removeClass('active');
	});
	
	
	
	
	
	$('.my-wishlist-page .my-wishlist-scrollable .wishlist-popover').slice(0,3).addClass('top');
	

	$('.my-wishlist-page .my-wishlist-scrollable').on('scroll',function(e){

		$('.my-wishlist-page .my-wishlist-scrollable .wishlist-popover').removeClass('top');
		

		$('.my-wishlist-page .my-wishlist-scrollable .row').each(function(e){
			
			if($(this).position().top >= -22 && $(this).position().top <= 110) {
				
				
				

				$(this).find('.wishlist-popover').addClass('top');
				
				
				
			}
		
		});
		
	});
	
	
	$('.my-wishlist-page .my-wishlist-scrollable .row').on('mouseenter',function(){
		$(this).find('.date').addClass('hovered');
		$(this).find('.album-title').addClass('hovered');
		$(this).find('.artist-name').addClass('hovered');
		$(this).find('.time').addClass('hovered');
		$(this).find('.song-title').addClass('hovered');
		$(this).find('.preview').addClass('hovered');
		$(this).find('.add-to-wishlist-button').addClass('hovered');
		
	});
	
	$('.my-wishlist-page .my-video-wishlist-scrollable .row').on('mouseleave',function(){
		$(this).find('.date').removeClass('hovered');
		$(this).find('.album-title').removeClass('hovered');
		$(this).find('.artist-name').removeClass('hovered');
		$(this).find('.time').removeClass('hovered');
		$(this).find('.song-title').removeClass('hovered');
		$(this).find('.preview').removeClass('hovered');
		$(this).find('.add-to-wishlist-button').removeClass('hovered');
		
	});

	$('.my-wishlist-page .my-video-wishlist-scrollable .row').on('mouseenter',function(){
		$(this).find('.date').addClass('hovered');
		$(this).find('.album-title').addClass('hovered');
		$(this).find('.artist-name').addClass('hovered');
		$(this).find('.time').addClass('hovered');
		$(this).find('.song-title').addClass('hovered');
		$(this).find('.preview').addClass('hovered');
		$(this).find('.add-to-wishlist-button').addClass('hovered');
		
	});
	
	$('.my-wishlist-page .my-video-wishlist-scrollable .row').on('mouseleave',function(){
		$(this).find('.date').removeClass('hovered');
		$(this).find('.album-title').removeClass('hovered');
		$(this).find('.artist-name').removeClass('hovered');
		$(this).find('.time').removeClass('hovered');
		$(this).find('.song-title').removeClass('hovered');
		$(this).find('.preview').removeClass('hovered');
		$(this).find('.add-to-wishlist-button').removeClass('hovered');
		
	});





	$('.my-wishlist-page .my-wishlist-scrollable .row .preview').on('mouseenter',function(e){
		$(this).removeClass('hovered').addClass('blue-bkg');
	
	});
	
	$('.my-wishlist-page .my-wishlist-scrollable .row .preview').on('mouseleave',function(e){
		$(this).removeClass('blue-bkg').addClass('hovered');
	
	});
	
	$('.my-wishlist-page .my-wishlist-scrollable .row .preview').on('click',function(e){
		
		if($(this).hasClass('playing')) {
			
			$(this).removeClass('playing');
			
			$(this).parents('.row').removeClass('playing');
			$(this).parent().removeClass('playing');
			$(this).siblings('.date').removeClass('playing');
			$(this).siblings('.album-title').removeClass('playing');
			$(this).siblings('.artist-name').removeClass('playing');
			$(this).siblings('.time').removeClass('playing');
			$(this).siblings('.song-title').removeClass('playing');
			$(this).siblings('.add-to-wishlist-button').removeClass('playing');
			$(this).siblings('.download').removeClass('playing');
			
			
		} else {
		
			$('.my-wishlist-page .my-wishlist-scrollable .row').removeClass('playing');
			$('.my-wishlist-page .my-wishlist-scrollable .row .date').removeClass('playing');
			$('.my-wishlist-page .my-wishlist-scrollable .row .preview').removeClass('playing');
			$('.my-wishlist-page .my-wishlist-scrollable .row .album-title').removeClass('playing');
			$('.my-wishlist-page .my-wishlist-scrollable .row .artist-name').removeClass('playing');
			$('.my-wishlist-page .my-wishlist-scrollable .row .time').removeClass('playing');
			$('.my-wishlist-page .my-wishlist-scrollable .row .song-title').removeClass('playing');
			$('.my-wishlist-page .my-wishlist-scrollable .row .add-to-wishlist-button').removeClass('playing');
			$('.my-wishlist-page .my-wishlist-scrollable .row .download').removeClass('playing');
		
			$(this).addClass('playing');
			$(this).parents('.row').addClass('playing');
			$(this).parent().addClass('playing');			$(this).siblings('.date').addClass('playing');
			$(this).siblings('.album-title').addClass('playing');
			$(this).siblings('.artist-name').addClass('playing');
			$(this).siblings('.time').addClass('playing');
			$(this).siblings('.song-title').addClass('playing');
			$(this).siblings('.add-to-wishlist-button').addClass('playing');
			$(this).siblings('.download').addClass('playing');			
			
			
		}
		
	});
        
    $('.video-filter-button').click(function(){
       $(this).addClass('active');
       $('.music-filter-button').removeClass('active');
       $('.my-wishlist-shadow-container').hide();
       $('.my-video-wishlist-shadow-container').show();
       
    });
    
    $('.music-filter-button').click(function(){
       $(this).addClass('active');
       $('.video-filter-button').removeClass('active');
       $('.my-video-wishlist-shadow-container').hide();
       $('.my-wishlist-shadow-container').show();
    });
	
});  
   
</script>
<form id="sortForm" name="sortForm" method='post'>
    <input id='sort' type='hidden' name="sort" value="<?php echo $sort; ?>" />
    <input id='sortOrder' type='hidden' name="sortOrder" value="<?php echo $sortOrder; ?>" />
</form>
<section class="my-wishlist-page">
		
		<div class="breadcrumbs"><?php
	$html->addCrumb( __('My Wishlist', true), '/homes/my_wishlist');
	echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
?></div>
		<header class="clearfix">
			<h2><?php echo __('My Wishlist', true); ?></h2>
			<div class="faq-link"><?php echo __('Need help? Visit our', true); ?> <?php echo $html->link(__('FAQ section.', true), array('controller' => 'questions', 'action' =>'index')); ?></div>
		</header>
		<div class="instructions">
			<p>
				In the event that your library exceeds its download budget for the week, you will see "add to wishlist" in place of the "download now" command. Adding your music to the wishlist will place you in a "first come, first serve" line to get more music when it becomes available, which is at midnight Sunday Eastern Time (U.S.). At that point your music is on hold for you for 24 hours (so no need to set your alarm clock) for you to proactively download. You should visit the Wishlist area on the top part of the home page to see the music that you requested, and if it is available.
			</p>
			<p>
				If you do not see the "download now" command in the Wish List area, it means so many people were waiting in line that you need to check back on a subsequent Monday.
			</p>
		</div>
		<nav class="my-wishlist-filter-container clearfix">
					<?php 
            if($sort == 'date'){
                if($sortOrder == 'asc'){
                ?>    
                    <div class="date-filter-button filter active" style="cursor:pointer;">Date</div>
                <?php } else { ?>
                    <div class="date-filter-button filter active toggled" style="cursor:pointer;">Date</div>
                <?php } 
            } else {
                ?>
                <div class="date-filter-button filter " style="cursor:pointer;">Date</div>
            <?php
            }
            if($sort == 'song'){
                if($sortOrder == 'asc'){
                ?>    
                    <div class="song-filter-button filter active" style="cursor:pointer;">Song</div>
                <?php } else { ?>
                    <div class="song-filter-button filter active toggled" style="cursor:pointer;">Song</div>
                <?php } 
            } else {
                ?>
			<div class="song-filter-button filter" style="cursor:pointer;">Song</div>
            <?php
            }
            ?>
			<div class="music-filter-button tab" style="cursor:pointer;">Music</div>
			<div class="video-filter-button tab" style="cursor:pointer;">Video</div>
		<?php
            if($sort == 'artist'){
                if($sortOrder == 'asc'){
                ?>    
                    <div class="artist-filter-button filter active" style="width:106px;cursor:pointer;">Artist</div>
                <?php } else { ?>
                    <div class="artist-filter-button filter active toggled" style="width:106px;cursor:pointer;">Artist</div>
                <?php } 
            } else {
                ?>
			<div class="artist-filter-button filter" style="width:106px;cursor:pointer;">Artist</div>
            <?php
            }
            if($sort == 'album'){
                if($sortOrder == 'asc'){
                ?>    
                    <div class="album-filter-button filter active" style="cursor:pointer;">Album</div>
                <?php } else { ?>
                    <div class="album-filter-button filter active toggled" style="cursor:pointer;">Album</div>
                <?php } 
            } else {
                ?>
			<div class="album-filter-button filter" style="cursor:pointer;">Album</div>
            <?php
            }
            ?>  
			<div class="download-button filter" >Download</div>
			
		</nav>
		<div class="my-wishlist-shadow-container">
			<div class="my-wishlist-scrollable">
				<div class="row-container">
				<?php

         if(is_array($wishlistResults) && count($wishlistResults) > 0){ 
             
	
            for($i = 0; $i < count($wishlistResults); $i++) {
		
			
	?>
				
				<div class="row clearfix wishlistsong"  id="wishlistsong-<?php echo $wishlistResults[$i]['wishlists']['id']?>">
					<div class="date"><?php echo date('Y-m-d',strtotime($wishlistResults[$i]['wishlists']['created'])); ?></div>
					<div class="small-album-container">                                     
                                
						 <?php
                        echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview",  "style" => "cursor:pointer;display:block;", "id" => "play_audio".$i, "onClick" => 'playSample(this, "'.$i.'", '.$wishlistResults[$i]['Song']['ProdID'].', "'.base64_encode($wishlistResults[$i]['Song']['provider_type']).'", "'.$this->webroot.'");')); 
                        echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$i)); 
                        echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$i, "onClick" => 'stopThis(this, "'.$i.'");')); 
                        ?>
					</div>
					<div class="song-title">
                                        <?php 
						if (strlen($wishlistResults[$i]['wishlists']['track_title']) >= 15) {
							echo '<span title="'.$this->getTextEncode(htmlentities($wishlistResults[$i]['wishlists']['track_title'])).'">' .$this->getTextEncode(substr($wishlistResults[$i]['wishlists']['track_title'], 0, 15)) . '...</span>';
						} else {
							echo $this->getTextEncode($wishlistResults[$i]['wishlists']['track_title']);
					 	}
					?></div>
					<a class="add-to-wishlist-button" href="#"></a>
					<div class="album-title"><a href="/artists/view/<?=base64_encode($wishlistResults[$i]['Song']['ArtistText']);?>/<?= $wishlistResults[$i]['Song']['ReferenceID']; ?>/<?= base64_encode($wishlistResults[$i]['Song']['provider_type']);?>">
                                         <?php
						if (strlen($wishlistResults[$i]['wishlists']['album']) >= 15) {
							echo '<span title="'.$this->getTextEncode(htmlentities($wishlistResults[$i]['wishlists']['album'])).'">' .$this->getTextEncode(substr($wishlistResults[$i]['wishlists']['album'], 0, 15)) . '...</span>';
						} else {
							echo $this->getTextEncode($wishlistResults[$i]['wishlists']['album']);
						}
						
                                          ?>
                                            </a></div>
					<div class="artist-name"><a href="/artists/album/<?= base64_encode($wishlistResults[$i]['Song']['ArtistText']); ?>">
                                         <?php
						if (strlen($wishlistResults[$i]['wishlists']['artist']) >= 15) {
							echo '<span title="'.$this->getTextEncode(htmlentities($wishlistResults[$i]['wishlists']['artist'])).'">' .$this->getTextEncode(substr($wishlistResults[$i]['wishlists']['artist'], 0, 15)) . '...</span>';
						} else {
							$ArtistName = $wishlistResults[$i]['wishlists']['artist'];
							echo $this->getTextEncode($ArtistName);
						}
						
                                         ?>
                                            </a></div>
					
                                        <div class="wishlist-popover">
                                            <?php if( $this->Session->read('library_type') == 2 ){
                                                        echo $this->Queue->getQueuesList($this->Session->read('patron'),$wishlistResults[$i]["Song"]["ProdID"],$wishlistResults[$i]["Song"]["provider_type"],$wishlistResults[$i]["Albums"]["ProdID"],$wishlistResults[$i]["Albums"]["provider_type"]); ?>
                                                        <a class="add-to-playlist" href="#">Add To Queue</a>
                                            <?php } ?>
                                            <?php echo $this->Queue->getSocialNetworkinglinksMarkup(); ?>            
					</div>
						<div class="download">
                                            
                                    <?php										
                                            $productInfo = $song->getDownloadData($wishlistResults[$i]['wishlists']['ProdID'],$wishlistResults[$i]['wishlists']['provider_type']);
                                            if($libraryDownload == '1' && $patronDownload == '1'){
                                                    $songUrl = shell_exec('perl files/tokengen ' . $productInfo[0]['Full_Files']['CdnPath']."/".$productInfo[0]['Full_Files']['SaveAsName']);                                                
                                                    $finalSongUrl = Configure::read('App.Music_Path').$songUrl;
                                                    $finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
                                    ?>
							<p>
								<span class="beforeClick" id="wishlist_song_<?php echo $wishlistResults[$i]['wishlists']['ProdID']; ?>">
									<![if !IE]>
										<a href='#' title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick='return wishlistDownloadOthers("<?php echo $wishlistResults[$i]['wishlists']['ProdID']; ?>", "<?php echo $wishlistResults[$i]['wishlists']['id']; ?>", "<?php echo urlencode($finalSongUrlArr[0]);?>", "<?php echo urlencode($finalSongUrlArr[1]);?>", "<?php echo urlencode($finalSongUrlArr[2]);?>" , "<?php echo $wishlistResults[$i]['wishlists']["provider_type"]; ?>");'><?php __('Download');?></a>
									<![endif]>
									<!--[if IE]>
									<a title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick='return wishlistDownloadIE("<?php echo $wishlistResults[$i]['Wishlist']['ProdID']; ?>", "<?php echo $wishlistResults[$i]['Wishlist']['id']; ?>" , "<?php echo $wishlistResults[$i]['Wishlist']["provider_type"]; ?>");' href='<?php echo $finalSongUrl; ?>'><?php __('Download Now');?></a>
									<![endif]-->							
								</span>
								<span class="afterClick" id="downloading_<?php echo $wishlistResults[$i]['wishlists']['ProdID']; ?>" style="display:none;float:left;"><?php __('Please Wait..');?></span>
								<span id="wishlist_loader_<?php echo $wishlistResults[$i]['wishlists']['ProdID']; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
							</p>
                                    <?php	}else{ ?>
                                                    <p><?php __("Limit Met");?></p>
                                            <?php
                                            }
                                    ?>
                                            
                                        </div>						
					<div class="delete-btn songdelete"></div>
				</div>
				<?php 

           }

        }else{            
            echo 	__("You have no songs in your wishlist.");            
        }


        ?>
				</div>
			</div>
		</div>
			<!--(this is the html for the videos) -->
		<div class="my-video-wishlist-shadow-container" style="display:none;">
			<div class="my-video-wishlist-scrollable">
				<div class="row-container">
				<?php
                if(count($wishlistResultsVideos) != 0)
                {
                   //$i = 1;
                    foreach($wishlistResultsVideos as $key => $wishlistResultsVideo):
                    /*$class = null;
                    if ($i++ % 2 == 0) {
                        $class = ' class="altrow"';
                    }*/
                ?>
				
				<div class="row clearfix" id="wishlistvideo-<?php echo $wishlistResultsVideo['WishlistVideo']['id']?>">
					<div class="date"><?php echo date("Y-m-d",strtotime($wishlistResultsVideo['WishlistVideo']['created'])); ?></div>
					<div class="small-album-container">
						<?php
                        $videoImage = shell_exec('perl files/tokengen_artwork ' .$wishlistResultsVideo['File']['CdnPath']."/".$wishlistResultsVideo['File']['SourceURL']);
                        $videoImageUrl = Configure::read('App.Music_Path').$videoImage;
                        ?>
                        <img src="<?php echo $videoImageUrl; ?>" alt="video-cover" width="67" height="40" />
						<!-- <a class="preview" href="#"></a> -->
					</div>
					<div class="song-title">
                    <?php 
						if (strlen($wishlistResultsVideo['WishlistVideo']['track_title']) >= 15) {
							echo '<span title="'.htmlentities($wishlistResultsVideo['WishlistVideo']['track_title']).'">' .substr($wishlistResultsVideo['Download']['track_title'], 0, 15) . '...</span>';							
						} else {
							echo $wishlistResultsVideo['WishlistVideo']['track_title']; 
					 	}
					?>
                    </div>
					<a class="add-to-wishlist-button" href="#"></a>
					<div class="album-title"><a href="#"><?php echo substr($wishlistResultsVideo['Video']['Title'],0,15);  ?>...</a></div>
					<div class="artist-name"><a href="#">
                    <?php
						if (strlen($wishlistResultsVideo['WishlistVideo']['artist']) >= 15) {
							echo '<span title="'.htmlentities($wishlistResultsVideo['WishlistVideo']['artist']).'">' .substr($wishlistResultsVideo['WishlistVideo']['artist'], 0, 15) . '...</span>';							
						} else {
							$ArtistName = $wishlistResultsVideo['WishlistVideo']['artist'];
							echo $ArtistName;
						}
						
					?></a></div>
					
					<div class="wishlist-popover">
						
						<div class="share clearfix">
							<p>Share via</p>
							<a class="facebook" href="#"></a>
							<a class="twitter" href="#"></a>
						</div>
						
					</div>
					<div class="download">
                        <a href="#">
                            
                        <p>
                        <?php
                            $productInfo = $mvideo->getDownloadData($wishlistResultsVideo['WishlistVideo']['ProdID'],$wishlistResultsVideo['WishlistVideo']['provider_type']);
                            $videoUrl = shell_exec('perl files/tokengen ' . 'sony_test/' . $productInfo[0]['Full_Files']['CdnPath']."/".$productInfo[0]['Full_Files']['SaveAsName']);                                                
							$finalVideoUrl = Configure::read('App.Music_Path').$videoUrl;
							$finalVideoUrlArr = str_split($finalVideoUrl, ceil(strlen($finalVideoUrl)/3));
                            ?>
                            <span class="beforeClick" id="download_song_<?php echo $wishlistResultsVideo['WishlistVideo']['ProdID']; ?>">
								<?php if($ieVersion > 8 || $ieVersion < 0){ ?>
									<a href='#' onclick='return historyDownloadOthers("<?php echo $wishlistResultsVideo['WishlistVideo']['ProdID']; ?>","<?php echo $wishlistResultsVideo['WishlistVideo']['library_id']; ?>","<?php echo $wishlistResultsVideo['WishlistVideo']['patron_id']; ?>", "<?php echo urlencode($finalVideoUrlArr[0]);?>", "<?php echo urlencode($finalVideoUrlArr[1]);?>", "<?php echo urlencode($finalVideoUrlArr[2]);?>");'><?php __('Download');?></a>
								<?php } else {?>
								<!--[if IE]>
									<a onclick='return historyDownload("<?php echo $downloadResult['Download']['ProdID']; ?>","<?php echo $wishlistResultsVideo['WishlistVideo']['library_id']; ?>","<?php echo $wishlistResultsVideo['WishlistVideo']['patron_id']; ?>");' href='<?php echo $finalSongUrl; ?>'><?php __('Download');?></a> 										
								<![endif]-->
								<?php } ?>
							</span>
							<span class="afterClick" style="display:none;float:left"><?php __("Please Wait...");?></span>
							<span id="download_loader_<?php echo $wishlistResultsVideo['WishlistVideo']['ProdID']; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
                       </p></a>
                    </div>
		    <div class="delete-btn videodelete"></div>
				</div>
				<?php
                    endforeach;
                    }else{
                echo 	'<tr><td valign="top"><p>';?><?php echo __("You have no videos in your wishlist."); ?><?php echo '</p></td></tr>';
                }
				?>
				</div>
			</div>
		</div>

	</section>
