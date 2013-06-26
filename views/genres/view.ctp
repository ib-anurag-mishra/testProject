<?php
/*
	 File Name : index.ctp
	 File Description : View page for genre index
	 Author : m68interactive
 */
?>

          
<link rel="stylesheet/less" type="text/css" href="<? echo $this->webroot; ?>app/webroot/css/genres.less" />        

<style>

.genre_list_item{
	cursor: pointer;
	display:block;
}
.genre_list_item_all{
  cursor: pointer;
	display:block;
}
    
    
    
</style>
<script language="javascript">
function load_genres(link , id_serial , genre_name)
{
	jQuery("#ajax_genrelist_content").empty().html(jQuery("#ajx_loader").html());
	jQuery('#ajax_genrelist_content').load(link);
	jQuery('.genre_list_item_all,.genre_list_item').css('font-weight' , 'normal');
	jQuery('#genre_list_item_'+id_serial).css('font-weight' , 'bold');
        
//	jQuery(".breadCrumb").find("a:eq(3)").html(genre_name);
//	jQuery(".breadCrumb").find("a:eq(3)").attr('href' , link );
//	jQuery(".breadCrumb").find("a:eq(3)").attr('href' , jQuery(".breadCrumb").find("a:eq(2)").attr('href').replace('ajax_view' , 'view'));
//
//	 jQuery("#genre_artist_search a").each(function () {
//		jQuery(this).attr('href' , jQuery(this).attr('href').replace('ajax_view' , 'view'));
//	});


	//setInterval('VSA_initScrollbars()' , 500);
}


function showAllAlbumsList(albumListURL){
    alert(webroot+albumListURL);
    var data = "";
    jQuery.ajax({
            type: "post",  // Request method: post, get
            url: webroot+"admin/artists/getAutoArtist", // URL to request
            data: data,  // post data
            success: function(response) {
                $('.album-list').html(response);
            },
            error:function (XMLHttpRequest, textStatus, errorThrown) {}
        });    
    
}


</script>            
<?php



$genre_text_conversion = array(
		"Children's Music" =>  "Children's" ,
		"Classic"  =>  "Soundtracks",
		"Comedy/Humor"  =>  "Comedy",
		"Country/Folk"  =>  "Country",
		"Dance/House"  =>  "Dance",
		"Easy Listening Vocal" => "Easy Listening",
		"Easy Listening Vocals"  =>  "Easy Listening",
		"Folk/Blues" => "Folk",
		"Folk/Country" => "Folk",
		"Folk/Country/Blues" => "Folk",
		"Hip Hop Rap" => "Hip-Hop Rap",
		"Rap/Hip-Hop" => "Hip-Hop Rap",
		"Rap / Hip-Hop" => "Hip-Hop Rap",
		"Jazz/Blues"  =>  "Jazz",
		"Kindermusik"  =>  "Children's",
		"Miscellaneous/Other" => "Miscellaneous",
		"Other" => "Miscellaneous",
		"Age/Instumental" => "New Age",
		"Pop / Rock" =>  "Pop/Rock",
		"R&B/Soul" => "R&B",
		"Soundtracks" => "Soundtrack",
		"Soundtracks/Musicals" => "Soundtrack",
		"World Music (Other)" => "World Music"
	);
	
	$genre_crumb_name = isset($genre_text_conversion[trim($genre)])?$genre_text_conversion[trim($genre)]:trim($genre);
	
	$html->addCrumb(__('All Genre', true), '/genres/view/');
	$html->addCrumb( $this->getTextEncode($genre_crumb_name)  , '/genres/view/'.base64_encode($genre_crumb_name));	
	$totalRows = count($genresAll);
?>

	<div height="400px" style="color:blue;">
    
	</div>
        	<section class="genres-page">
		<div class="breadcrumbs"><span><?php echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');?></span></div>
		<header class="clearfix">
			<h2>Search for your favorite music.</h2>
			<div class="faq-link">Need help? Visit our <a href="#">FAQ section.</a></div>
		</header>
		<section class="genre-filter-container clearfix">
			<div class="genre-shadow-container">
				<h3>Genre</h3>
				<div class="genre-list">
					
					<ul>
						
						<li><a class="genre_list_item_all" href="#" data-genre="All Artists" id="genre_list_item_0" onclick="load_genres('/genres/ajax_view/<?php echo base64_encode('All'); ?>' ,'0' , '<?php echo addslashes('All');  ?>')"><?php echo __('All Artists'); ?></a></li>
					  
                                                
            <?php
                $genre_count = 1;
                foreach ($genresAll as $genre_all):
                    if($genre_all['Genre']['Genre'] != ''){
                            $genre_name = isset($genre_text_conversion[trim($genre_all['Genre']['Genre'])])?$genre_text_conversion[trim($genre_all['Genre']['Genre'])]:$genre_all['Genre']['Genre'];	
                            if($genre_name == $genre){
                                    ?>
                                <li> <a  class="genre_list_item_all" href="#" data-genre="<?php echo addslashes($this->getTextEncode($genre_name));  ?>" id="genre_list_item_<?php echo $genre_count; ?>" onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>' ,'<?php echo $genre_count; ?>' , '<?php echo addslashes($this->getTextEncode($genre_name));  ?>')"><?php echo $this->getTextEncode($genre_name); ?></a></li>
                                    <?php
                            }
                            else{
                                    ?>
                                <li> <a  class="genre_list_item_all" href="#" data-genre="<?php echo addslashes($this->getTextEncode($genre_name));  ?>" id="genre_list_item_<?php echo $genre_count; ?>"  onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_name); ?>' , '<?php echo $genre_count; ?>' , '<?php echo addslashes($this->getTextEncode($genre_name));  ?>' )" ><?php echo $this->getTextEncode($genre_name); ?></a></li>
                                    <?php
                            }
                }
                $genre_count++;
                endforeach;
            ?>            
                    	</ul>
				</div>
			</div>
			<div class="border"></div>
                        
                 <div style="display:none;" id="ajx_loader"><img style="margin-top:200px;margin-left:270px" src="/img/ajax-loader-big.gif" ></div>

                <div id="ajax_genrelist_content">
                    
			<div class="alphabetical-shadow-container">
				<h3><?php __('Artist'); ?></h3>
				<div class="alphabetical-filter">
                                    <ul>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>" data-letter="All">ALL</a></li>                                            
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/spl" data-letter="#">#</a></li> 
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/A" data-letter="A">A</a></li>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/B" data-letter="B">B</a></li>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/C" data-letter="C">C</a></li>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/D" data-letter="D">D</a></li>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/E" data-letter="E">E</a></li>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/F" data-letter="F">F</a></li>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/G" data-letter="G">G</a></li>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/H" data-letter="H">H</a></li>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/I" data-letter="I">I</a></li>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/J" data-letter="J">J</a></li>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/K" data-letter="K">K</a></li>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/L" data-letter="L">L</a></li>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/M" data-letter="M">M</a></li>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/N" data-letter="N">N</a></li>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/O" data-letter="O">O</a></li>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/P" data-letter="P">P</a></li>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/Q" data-letter="Q">Q</a></li>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/R" data-letter="R">R</a></li>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/S" data-letter="S">S</a></li>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/T" data-letter="T">T</a></li>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/U" data-letter="U">U</a></li>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/V" data-letter="V">V</a></li>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/W" data-letter="W">W</a></li>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/X" data-letter="X">X</a></li>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/Y" data-letter="Y">Y</a></li>
                                    <li><a href="/genres/view/<?=base64_encode($genre)?>/Z" data-letter="Z">Z</a></li>
                                    </ul>
				</div>
			</div> 
                    
                    
                    
                    
                    
                    
			<div class="artist-list-shadow-container">
				<h3>&nbsp;</h3>
				<div class="artist-list">					
					<ul>
						
                                            
                                         <?php
                                                           
                                            if(count($genres) > 0){                                                    
                                                for ($i = 0; $i < count($genres); $i++) {
                                                        echo " <li>";
                                                        $ArtistName = $this->getTextEncode($genres[$i]['Song']['ArtistText']);
                                                        echo "<a onclick='showAllAlbumsList('/artists/album/" . str_replace('/','@',base64_encode($genres[$i]['Song']['ArtistText'])) . '/' . base64_encode($genre). "')' data-artist='".$ArtistName."'>";
                                                        echo $ArtistName;
                                                        echo '</a>';
                                                        echo '</li>';                                                                    
                                                }
                                            }else{
                                                    echo "<li><a href='javascript:void(0)' data-artist='No Results Found'>No Results Found</a></li>";
                                            }
                                         ?> 
                                            
                                          <!--  <li><a href="#" data-artist="A.J. Croce">A.J. Croce</a></li> -->
				
					</ul>
				</div>
			</div>
                   </div>                 
                        
                        
                        
			<div class="border"></div>
			<div class="album-list-shadow-container">
				<h3>Album</h3>
				<div class="album-list">
					<div class="album-overview-container">
						<div class="album-image selected">
							<a href="#"><img src="/app/webroot/img/genres/album-cover-small.jpg" alt="album-cover-small" width="59" height="59" /></a>
						</div>
						<div class="album-title">
							<a href="#">13 Shades Of Blue, Best Of Mapleshade Vol. 2</a>
						</div>
						<div class="album-year">
							<a href="#">(2013)</a>
						</div>
					</div>
					<div class="album-overview-container">
						<div class="album-image">
							<a href="#"><img src="/app/webroot/img/genres/album-cover-small.jpg" alt="album-cover-small" width="59" height="59" /></a>
						</div>
						<div class="album-title">
							<a href="#">13 Shades Of Blue, Best Of Mapleshade Vol. 2</a>
						</div>
						<div class="album-year">
							<a href="#">(2013)</a>
						</div>
					</div>
					<div class="album-overview-container">
						<div class="album-image">
							<a href="#"><img src="/app/webroot/img/genres/album-cover-small.jpg" alt="album-cover-small" width="59" height="59" /></a>
						</div>
						<div class="album-title">
							<a href="#">13 Shades Of Blue, Best Of Mapleshade Vol. 2</a>
						</div>
						<div class="album-year">
							<a href="#">(2013)</a>
						</div>
					</div>
					<div class="album-overview-container">
						<div class="album-image">
							<a href="#"><img src="/app/webroot/img/genres/album-cover-small.jpg" alt="album-cover-small" width="59" height="59" /></a>
						</div>
						<div class="album-title">
							<a href="#">13 Shades Of Blue, Best Of Mapleshade Vol. 2</a>
						</div>
						<div class="album-year">
							<a href="#">(2013)</a>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section class="album-detail-container clearfix">
			<section class="album-detail">
				<div class="album-cover-image">
					<img src="/app/webroot/img/genres/album-detail-cover.jpg" alt="album-detail-cover" width="250" height="250" />
				</div>
				<!-- <a href="#" class="more-by">More by Al Lee, Ben Andrews</a> -->
				<div class="album-title">13 Shades Of Blue, Best Of Mapleshade Vol. 2</div>
				<div class="album-genre">Genre: <span><a href="#">Blues</a></span></div>
				<div class="album-label">Label: <span>Mapleshade Records</span></div>
				
			</section>
			<section class="tracklist-container">
				
				<div class="tracklist-header"><span class="song">Song</span><span class="artist">Artist</span><span class="time">Time</span></div>
				<?php
					$tracklist_array = array('Grow Up','Swagger Jagger','End Up Here','Want U Back','With Ur Love','Behind The Music','Oath','Beautiful People','End Up Here','Want U Back','With Ur Love','Behind The Music','Oath','Beautiful People');
					
					for($a=0;$a<count($tracklist_array);$a++) {
				?>	
					
					<div class="tracklist">
						<a href="#" class="preview"></a>
						<div class="song"><?php echo $tracklist_array[$a]; ?></div>
						<div class="artist"><a href="#">Al Lee, Ben Andrews</a></div>
						<div class="time">3:27</div>
						<a class="add-to-playlist-button" href="#"></a>
						<div class="wishlist-popover">
							<div class="playlist-options">
								<ul>
									<li><a href="#">Create New Playlist</a></li>
									<li><a href="#">Playlist 1</a></li>
									<li><a href="#">Playlist 2</a></li>
									<li><a href="#">Playlist 3</a></li>
									<li><a href="#">Playlist 4</a></li>
									<li><a href="#">Playlist 5</a></li>
									<li><a href="#">Playlist 6</a></li>
									<li><a href="#">Playlist 7</a></li>
									<li><a href="#">Playlist 8</a></li>
									<li><a href="#">Playlist 9</a></li>
									<li><a href="#">Playlist 10</a></li>
								</ul>
							</div>
							<a class="add-to-queue" href="#">Add To Queue</a>
							<a class="add-to-playlist" href="#">Add To Playlist</a>
							<a class="add-to-wishlist" href="#">Add To Wishlist</a>
							
							<div class="share clearfix">
								<p>Share via</p>
								<a class="facebook" href="#"></a>
								<a class="twitter" href="#"></a>
							</div>
							
						</div>
					</div>
						
				<?php		
					}
				?>
				
						
					
			</section>
			
		</section>
		
	</section>
