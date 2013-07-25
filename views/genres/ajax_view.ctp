
		<div class="alphabetical-shadow-container">
				<h3><?php __('Artist'); ?></h3>
				<div class="alphabetical-filter">
                                                                        <ul>
                                    <li><a href="javascript:void(0);" data-letter="All<?=$isCallFromGenre;?>" <?php if($isCallFromGenre != 0){?>class="selected"<?php } ?> onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>' ,'' , '')">ALL</a></li>                                            
                                    <li><a href="javascript:void(0);" data-letter="#"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/spl' ,'' , '')" >#</a></li> 
                                    <li><a href="javascript:void(0);" data-letter="A"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/A' ,'' , '')">A</a></li>
                                    <li><a href="javascript:void(0);" data-letter="B"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/B' ,'' , '')">B</a></li>
                                    <li><a href="javascript:void(0);" data-letter="C"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/C' ,'' , '')">C</a></li>
                                    <li><a href="javascript:void(0);" data-letter="D"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/D' ,'' , '')">D</a></li>
                                    <li><a href="javascript:void(0);" data-letter="E"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/E' ,'' , '')">E</a></li>
                                    <li><a href="javascript:void(0);" data-letter="F"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/F' ,'' , '')">F</a></li>
                                    <li><a href="javascript:void(0);" data-letter="G"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/G' ,'' , '')">G</a></li>
                                    <li><a href="javascript:void(0);" data-letter="H"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/H' ,'' , '')">H</a></li>
                                    <li><a href="javascript:void(0);" data-letter="I"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/I' ,'' , '')">I</a></li>
                                    <li><a href="javascript:void(0);" data-letter="J"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/J' ,'' , '')">J</a></li>
                                    <li><a href="javascript:void(0);" data-letter="K"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/K' ,'' , '')">K</a></li>
                                    <li><a href="javascript:void(0);" data-letter="L"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/L' ,'' , '')">L</a></li>
                                    <li><a href="javascript:void(0);" data-letter="M"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/M' ,'' , '')">M</a></li>
                                    <li><a href="javascript:void(0);" data-letter="N"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/N' ,'' , '')">N</a></li>
                                    <li><a href="javascript:void(0);" data-letter="O"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/O' ,'' , '')">O</a></li>
                                    <li><a href="javascript:void(0);" data-letter="P"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/P' ,'' , '')">P</a></li>
                                    <li><a href="javascript:void(0);" data-letter="Q"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/Q' ,'' , '')">Q</a></li>
                                    <li><a href="javascript:void(0);" data-letter="R"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/R' ,'' , '')">R</a></li>
                                    <li><a href="javascript:void(0);" data-letter="S"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/S' ,'' , '')">S</a></li>
                                    <li><a href="javascript:void(0);" data-letter="T"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/T' ,'' , '')">T</a></li>
                                    <li><a href="javascript:void(0);" data-letter="U"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/U' ,'' , '')">U</a></li>
                                    <li><a href="javascript:void(0);" data-letter="V"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/V' ,'' , '')">V</a></li>
                                    <li><a href="javascript:void(0);" data-letter="W"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/W' ,'' , '')">W</a></li>
                                    <li><a href="javascript:void(0);" data-letter="X"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/X' ,'' , '')">X</a></li>
                                    <li><a href="javascript:void(0);" data-letter="Y"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/Y' ,'' , '')">Y</a></li>
                                    <li><a href="javascript:void(0);" data-letter="Z"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/Z' ,'' , '')">Z</a></li>
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