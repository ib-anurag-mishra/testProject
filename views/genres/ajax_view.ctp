<?php
if(count($genres) == 0)
{
	echo "<li><a href='javascript:void(0)' data-artist='No Results Found'>No Results Found</a></li>";
	exit;
}

?>
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
                                                        echo "<a onclick=\"showAllAlbumsList('".$url."')\" data-artist='".$ArtistName."' style='cursor:pointer;'>";
                                                        echo $ArtistName;
                                                        echo '</a>';
                                                        echo '</li>';                                                                    
                                                }
                                            }else{
                                                    echo "<li><a href='javascript:void(0)' data-artist='No Results Found'>No Results Found</a></li>";
                                            }
                                         ?>
					</ul>
				</div>
			</div>