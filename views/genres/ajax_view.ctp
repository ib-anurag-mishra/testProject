<script type="text/javascript" src="/js/ajaxify-html5.js"></script> 
<script> 
var ajaxartistPage = 2;
var preValue= 1;
   $("#artistscroll").scroll(function(){  
       
        if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
           
           var totalPages = <?=$totalPages?>;           

            $('#artist_loader').show();
            if( preValue != ajaxartistPage ){  

                if(ajaxartistPage <= totalPages ){
              
                    preValue= ajaxartistPage ;
                    var data = "npage="+ajaxartistPage;
                    jQuery.ajax({
                            type: "post",  // Request method: post, get 
                            url: '/genres/ajax_view_pagination/page:'+ajaxartistPage+'/<?=base64_encode($genre); ?>'+'/<?=$selectedAlpha?>',
                            data: data,  // post data
                            success: function(newitems) { 
                                if(newitems){
                                    ajaxartistPage++; 
                                    $('#artist_loader').hide();                      
                                    $('#artistlistrecord').append(newitems);  
                                }else{
                                    $('#artist_loader').hide(); 
                                    return;
                                }
                                

                            },
                            async:   true,
                            error:function (XMLHttpRequest, textStatus, errorThrown) { 
                            }
                        });
                }else{
                    $('#artist_loader').hide();          
                }
            } 
       }
        
   });  

</script> 

				<div class="alpha-artist-list-column">
                                    <ul>
                                    <li><a  href="javascript:void(0);" data-letter="All" <?php if($selectedAlpha =="All") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/All' ,'' , '')">ALL</a></li>                                            
                                    <li><a  href="javascript:void(0);" data-letter="#"   <?php if($selectedAlpha =="spl") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/spl' ,'' , '')" >#</a></li> 
                                    <li><a  href="javascript:void(0);" data-letter="A"   <?php if($selectedAlpha =="A") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/A' ,'' , '')">A</a></li>
                                    <li><a  href="javascript:void(0);" data-letter="B"   <?php if($selectedAlpha =="B") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/B' ,'' , '')">B</a></li>
                                    <li><a  href="javascript:void(0);" data-letter="C"   <?php if($selectedAlpha =="C") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/C' ,'' , '')">C</a></li>
                                    <li><a  href="javascript:void(0);" data-letter="D"   <?php if($selectedAlpha =="D") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/D' ,'' , '')">D</a></li>
                                    <li><a  href="javascript:void(0);" data-letter="E"   <?php if($selectedAlpha =="E") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/E' ,'' , '')">E</a></li>
                                    <li><a  href="javascript:void(0);" data-letter="F"   <?php if($selectedAlpha =="F") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/F' ,'' , '')">F</a></li>
                                    <li><a  href="javascript:void(0);" data-letter="G"   <?php if($selectedAlpha =="G") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/G' ,'' , '')">G</a></li>
                                    <li><a  href="javascript:void(0);" data-letter="H"   <?php if($selectedAlpha =="H") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/H' ,'' , '')">H</a></li>
                                    <li><a  href="javascript:void(0);" data-letter="I"   <?php if($selectedAlpha =="I") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/I' ,'' , '')">I</a></li>
                                    <li><a  href="javascript:void(0);" data-letter="J"   <?php if($selectedAlpha =="J") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/J' ,'' , '')">J</a></li>
                                    <li><a  href="javascript:void(0);" data-letter="K"   <?php if($selectedAlpha =="K") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/K' ,'' , '')">K</a></li>
                                    <li><a  href="javascript:void(0);" data-letter="L"   <?php if($selectedAlpha =="L") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/L' ,'' , '')">L</a></li>
                                    <li><a  href="javascript:void(0);" data-letter="M"   <?php if($selectedAlpha =="M") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/M' ,'' , '')">M</a></li>
                                    <li><a  href="javascript:void(0);" data-letter="N"   <?php if($selectedAlpha =="N") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/N' ,'' , '')">N</a></li>
                                    <li><a  href="javascript:void(0);" data-letter="O"   <?php if($selectedAlpha =="O") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/O' ,'' , '')">O</a></li>
                                    <li><a  href="javascript:void(0);" data-letter="P"   <?php if($selectedAlpha =="P") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/P' ,'' , '')">P</a></li>
                                    <li><a  href="javascript:void(0);" data-letter="Q"   <?php if($selectedAlpha =="Q") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/Q' ,'' , '')">Q</a></li>
                                    <li><a  href="javascript:void(0);" data-letter="R"   <?php if($selectedAlpha =="R") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/R' ,'' , '')">R</a></li>
                                    <li><a  href="javascript:void(0);" data-letter="S"   <?php if($selectedAlpha =="S") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/S' ,'' , '')">S</a></li>
                                    <li><a  href="javascript:void(0);" data-letter="T"   <?php if($selectedAlpha =="T") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/T' ,'' , '')">T</a></li>
                                    <li><a  href="javascript:void(0);" data-letter="U"   <?php if($selectedAlpha =="U") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/U' ,'' , '')">U</a></li>
                                    <li><a  href="javascript:void(0);" data-letter="V"   <?php if($selectedAlpha =="V") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/V' ,'' , '')">V</a></li>
                                    <li><a  href="javascript:void(0);" data-letter="W"   <?php if($selectedAlpha =="W") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/W' ,'' , '')">W</a></li>
                                    <li><a  href="javascript:void(0);" data-letter="X"   <?php if($selectedAlpha =="X") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/X' ,'' , '')">X</a></li>
                                    <li><a  href="javascript:void(0);" data-letter="Y"   <?php if($selectedAlpha =="Y") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/Y' ,'' , '')">Y</a></li>
                                    <li><a  href="javascript:void(0);" data-letter="Z"   <?php if($selectedAlpha =="Z") {?>class="selected active" <?php } ?>   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/Z' ,'' , '')">Z</a></li>
                                    </ul>
				</div>

				<div class="artist-column" id="artistscroll">					
					<ul id="artistlistrecord">	                                        
                                         <?php
                                                           
                                            if(count($genres) > 0){                                                    
                                                for ($i = 0; $i < count($genres); $i++) {
                                                        echo " <li>";
                                                        $ArtistName = $this->getTextEncode($genres[$i]['Song']['ArtistText']);                                                       
                                                        $url = "artists/album_ajax/" . str_replace('/','@',base64_encode($genres[$i]['Song']['ArtistText'])) . "/" . base64_encode($genre);
                                                       ?>
                                                        <a href="/artists/album/<?php echo str_replace('/', '@', base64_encode($ArtistName)); ?>/<?= base64_encode($genre) ?>">
                                                       <?php
                                                        echo wordwrap($ArtistName, 35, "<br />\n", TRUE);
                                                        echo '</a>';
                                                        echo '</li>';                                                                    
                                                }
                                            }else{
                                                    echo "<li><a href='javascript:void(0)' data-artist='No Results Found'>No Results Found</a></li>";
                                            }
                                         ?>
					</ul>
                                <span id="artist_loader" style="padding-left:115px;display:none;" ><img src="<? echo $this->webroot; ?>app/webroot/img/aritst-ajax-loader.gif" border="0"/></span>
				</div>