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
function load_genres(link , id_serial , genre_name){
	
	jQuery('#ajax_artistlist_content').load(link);
}


function showAllAlbumsList(albumListURL){
    //alert(webroot+albumListURL);
    jQuery(".album-list-span").empty().html(jQuery("#loadingmessage").html());
    $('#loadingmessage').show();
    var data = "";
    jQuery.ajax({
            type: "post",  // Request method: post, get
            url: webroot+albumListURL, // URL to request
            data: data,  // post data
            success: function(response) {
                jQuery(".album-list-span").empty();
                $('.album-list-span').html(response);
            },
            error:function (XMLHttpRequest, textStatus, errorThrown) { alert('error')}
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
<div id='loadingmessage' style='display:none' >
<img src='<? echo $this->webroot; ?>app/webroot/img/ajax-loader_black.gif'/>
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
                <div id="ajax_artistlist_content">
                    	<div class="alphabetical-shadow-container">
				<h3><?php __('Artist'); ?></h3>
				<div class="alphabetical-filter">
                                    
                                    
                                    
                                   
                                    
                                    
                                    
                                    
                                    
                                    <ul>
                                    <li><a href="javascript:void(0);" data-letter="All" onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>' ,'' , '')">ALL</a></li>                                            
                                    <li><a href="javascript:void(0);" data-letter="#"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/spl' ,'' , '')" >#</a></li> 
                                    <li><a href="javascript:void(0);" data-letter="A"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/A' ,'' , '')">A</a></li>
                                    <li><a href="javascript:void(0);" data-letter="B"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/B' ,'' , '')">B</a></li>
                                    <li><a href="javascript:void(0);" data-letter="C"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/C' ,'' , '')">C</a></li>
                                    <li><a href="javascript:void(0);" data-letter="D"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/D' ,'' , '')">D</a></li>
                                    <li><a href="javascript:void(0);" data-letter="E"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/E' ,'' , '')">E</a></li>
                                    <li><a href="javascript:void(0);" data-letter="F"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/F' ,'' , '')">F</a></li>
                                    <li><a href="javascript:void(0);" data-letter="G"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/G' ,'' , '')">G</a></li>
                                    <li><a href="javascript:void(0);" data-letter="H"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/H' ,'' , '')">H</a></li>
                                    <li><a href="javascript:void(0);" data-letter="I"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/I' ,'' , '')">I</a></li>
                                    <li><a href="javascript:void(0);" data-letter="J"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/J' ,'' , '')">J</a></li>
                                    <li><a href="javascript:void(0);" data-letter="K"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/K' ,'' , '')">K</a></li>
                                    <li><a href="javascript:void(0);" data-letter="L"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/L' ,'' , '')">L</a></li>
                                    <li><a href="javascript:void(0);" data-letter="M"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/M' ,'' , '')">M</a></li>
                                    <li><a href="javascript:void(0);" data-letter="N"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/N' ,'' , '')">N</a></li>
                                    <li><a href="javascript:void(0);" data-letter="O"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/O' ,'' , '')">O</a></li>
                                    <li><a href="javascript:void(0);" data-letter="P"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/P' ,'' , '')">P</a></li>
                                    <li><a href="javascript:void(0);" data-letter="Q"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/Q' ,'' , '')">Q</a></li>
                                    <li><a href="javascript:void(0);" data-letter="R"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/R' ,'' , '')">R</a></li>
                                    <li><a href="javascript:void(0);" data-letter="S"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/S' ,'' , '')">S</a></li>
                                    <li><a href="javascript:void(0);" data-letter="T"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/T' ,'' , '')">T</a></li>
                                    <li><a href="javascript:void(0);" data-letter="U"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/U' ,'' , '')">U</a></li>
                                    <li><a href="javascript:void(0);" data-letter="V"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/V' ,'' , '')">V</a></li>
                                    <li><a href="javascript:void(0);" data-letter="W"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/W' ,'' , '')">W</a></li>
                                    <li><a href="javascript:void(0);" data-letter="X"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/X' ,'' , '')">X</a></li>
                                    <li><a href="javascript:void(0);" data-letter="Y"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/Y' ,'' , '')">Y</a></li>
                                    <li><a href="javascript:void(0);" data-letter="Z"   onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/Z' ,'' , '')">Z</a></li>
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
                                                        $url = "artists/album_ajax/" . str_replace('/','@',base64_encode($genres[$i]['Song']['ArtistText'])) . "/" . base64_encode($genre);
                                                        echo "<a onclick=\"showAllAlbumsList('".$url."')\" data-artist='".$ArtistName."'>";
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
			<span class="album-list-span">				
			</span>
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
