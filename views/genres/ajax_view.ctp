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
                                          <!--  <li><a href="#" data-artist="A.J. Croce">A.J. Croce</a></li> -->
				
					</ul>
				</div>
			</div>