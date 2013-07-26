<script> 
var ajaxartistPage = 2;
   $("#artistscroll").scroll(function(){  
       if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight)
       {
            var data = "";
            jQuery.ajax({
                    type: "post",  // Request method: post, get
                    url: '/genres/ajax_view_pagination/<?=base64_encode($genre); ?>'+'/<?=$selectedAlpha?>/'+ajaxartistPage, // URL to request
                    data: data,  // post data
                    success: function(newitems) { 
                        ajaxartistPage++;                      
                        $('#artistlistrecord').append(newitems);                        
                    },
                    async:   false,
                    error:function (XMLHttpRequest, textStatus, errorThrown) { alert('No artist list available')}
            }); 
       }
        
   });  

</script> 
		<div class="alphabetical-shadow-container">
				<h3><?php __('Artist'); echo $selectedCallFlag; ?></h3>
				<div class="alphabetical-filter">
                                                                        <ul>
                                    <li><a href="javascript:void(0);" data-letter="All" <?php if($selectedAlpha =="All") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/All' ,'' , '')">ALL</a></li>                                            
                                    <li><a href="javascript:void(0);" data-letter="#"   <?php if($selectedAlpha =="spl") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/spl' ,'' , '')" >#</a></li> 
                                    <li><a href="javascript:void(0);" data-letter="A"   <?php if($selectedAlpha =="A") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/A' ,'' , '')">A</a></li>
                                    <li><a href="javascript:void(0);" data-letter="B"   <?php if($selectedAlpha =="B") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/B' ,'' , '')">B</a></li>
                                    <li><a href="javascript:void(0);" data-letter="C"   <?php if($selectedAlpha =="C") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/C' ,'' , '')">C</a></li>
                                    <li><a href="javascript:void(0);" data-letter="D"   <?php if($selectedAlpha =="D") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/D' ,'' , '')">D</a></li>
                                    <li><a href="javascript:void(0);" data-letter="E"   <?php if($selectedAlpha =="E") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/E' ,'' , '')">E</a></li>
                                    <li><a href="javascript:void(0);" data-letter="F"   <?php if($selectedAlpha =="F") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/F' ,'' , '')">F</a></li>
                                    <li><a href="javascript:void(0);" data-letter="G"   <?php if($selectedAlpha =="G") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/G' ,'' , '')">G</a></li>
                                    <li><a href="javascript:void(0);" data-letter="H"   <?php if($selectedAlpha =="H") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/H' ,'' , '')">H</a></li>
                                    <li><a href="javascript:void(0);" data-letter="I"   <?php if($selectedAlpha =="I") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/I' ,'' , '')">I</a></li>
                                    <li><a href="javascript:void(0);" data-letter="J"   <?php if($selectedAlpha =="J") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/J' ,'' , '')">J</a></li>
                                    <li><a href="javascript:void(0);" data-letter="K"   <?php if($selectedAlpha =="K") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/K' ,'' , '')">K</a></li>
                                    <li><a href="javascript:void(0);" data-letter="L"   <?php if($selectedAlpha =="L") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/L' ,'' , '')">L</a></li>
                                    <li><a href="javascript:void(0);" data-letter="M"   <?php if($selectedAlpha =="M") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/M' ,'' , '')">M</a></li>
                                    <li><a href="javascript:void(0);" data-letter="N"   <?php if($selectedAlpha =="N") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/N' ,'' , '')">N</a></li>
                                    <li><a href="javascript:void(0);" data-letter="O"   <?php if($selectedAlpha =="O") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/O' ,'' , '')">O</a></li>
                                    <li><a href="javascript:void(0);" data-letter="P"   <?php if($selectedAlpha =="P") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/P' ,'' , '')">P</a></li>
                                    <li><a href="javascript:void(0);" data-letter="Q"   <?php if($selectedAlpha =="Q") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/Q' ,'' , '')">Q</a></li>
                                    <li><a href="javascript:void(0);" data-letter="R"   <?php if($selectedAlpha =="R") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/R' ,'' , '')">R</a></li>
                                    <li><a href="javascript:void(0);" data-letter="S"   <?php if($selectedAlpha =="S") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/S' ,'' , '')">S</a></li>
                                    <li><a href="javascript:void(0);" data-letter="T"   <?php if($selectedAlpha =="T") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/T' ,'' , '')">T</a></li>
                                    <li><a href="javascript:void(0);" data-letter="U"   <?php if($selectedAlpha =="U") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/U' ,'' , '')">U</a></li>
                                    <li><a href="javascript:void(0);" data-letter="V"   <?php if($selectedAlpha =="V") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/V' ,'' , '')">V</a></li>
                                    <li><a href="javascript:void(0);" data-letter="W"   <?php if($selectedAlpha =="W") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/W' ,'' , '')">W</a></li>
                                    <li><a href="javascript:void(0);" data-letter="X"   <?php if($selectedAlpha =="X") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/X' ,'' , '')">X</a></li>
                                    <li><a href="javascript:void(0);" data-letter="Y"   <?php if($selectedAlpha =="Y") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/Y' ,'' , '')">Y</a></li>
                                    <li><a href="javascript:void(0);" data-letter="Z"   <?php if($selectedAlpha =="Z") {?>class="selected" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/Z' ,'' , '')">Z</a></li>
                                    </ul>
				</div>
			</div> 
                    
                    
                    
                    
                    
                    
			<div class="artist-list-shadow-container">
				<h3>&nbsp;</h3>
				<div class="artist-list" id="artistscroll">					
					<ul id="artistlistrecord">	                                        
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