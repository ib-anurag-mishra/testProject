<?php
/*
 * 
  File Name : index.ctp
  File Description : View page for genre index
  Author : m68interactive
 */
?>    

<style>
    
    header.clearfix{
        border: none;
        background: none;
        margin-bottom: 10px;
    }

   .genres-page{
	margin-top: 0px;
	margin-left: 0px;
    }

    .genres-page .genre-filter-container{
        height: auto;    
    }
    
    .genre_list_item{
        cursor: pointer;
        display:block;
    }

    .genre_list_item_all{
        cursor: pointer;
        display:block;
    }

    #mydiv {
        height: 250px;
        width: 250px;
        position: relative;
        background-color: gray; /* for demonstration */
    }

    .ajax-loader {
        display: block;
        left: 50%;
        margin-left: 147px;
        margin-top: 85px;
        position: absolute;
        top: 50%;
    }   

    .ajax-loader1 {
        display: block;
        left: 50%;
        margin-left: 115px;
        margin-top: 85px;
        position: absolute;
        top: 50%;
    }

    .ajax-loader2 {
        display: block;
        left: 50%;
        margin-left: 398px;
        margin-top: 3px;
        position: absolute;
        top: 50%;
    }

</style>

<script>
    
    $(document).ready(function() {
        var preValue = 1;
        var artistPage = 2;
        var selectedAlpha = '<? echo ($this->Session->read('selectedAlpha') != '') ? $this->Session->read('selectedAlpha') : 'All' ?>';
	
	var split = location.search.replace('?', '').split('=');
       
        if(split!=''){
            split[1]= split[1].replace('%20',' ');
            var genre = 'li a[data-genre="'+split[1]+'"]';
            $(genre).click();
        }

        $("#artistscroll").scroll(function() {
            if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {

                $('#artist_loader').show();
                var totalPages = <?= $totalPages ?>;
                var data = "npage=" + artistPage;

                if ((preValue != artistPage) && (artistPage <= totalPages)) {

                    if (artistPage <= totalPages) {

                        preValue = artistPage;
                        var link = webroot + 'genres/ajax_view_pagination/page:' + artistPage + '/<?= base64_encode($genre); ?>' + '/' + selectedAlpha;

                        jQuery.ajax({
                            type: "post", // Request method: post, get
                            url: link, // URL to request
                            data: data, // post data
                            success: function(newitems) {
                                if (newitems) {
                                    artistPage++;
                                    $('#artist_loader').hide();
                                    $('#artistlistrecord').append(newitems);
                                } else {
                                    $('#artist_loader').hide();
                                    return;
                                }
                            },
                            async: true,
                            error: function(XMLHttpRequest, textStatus, errorThrown) {
                            }
                        });

                    } else {
                        $('#artist_loader').hide();
                    }
                }
            }
        });
    });

</script>

<?php
$genre_text_conversion = array(
    "Children's Music" => "Children's",
    "Classic" => "Soundtracks",
    "Comedy/Humor" => "Comedy",
    "Country/Folk" => "Country",
    "Dance/House" => "Dance",
    "Easy Listening Vocal" => "Easy Listening",
    "Easy Listening Vocals" => "Easy Listening",
    "Folk/Blues" => "Folk",
    "Folk/Country" => "Folk",
    "Folk/Country/Blues" => "Folk",
    "Hip Hop Rap" => "Hip-Hop Rap",
    "Rap/Hip-Hop" => "Hip-Hop Rap",
    "Rap / Hip-Hop" => "Hip-Hop Rap",
    "Jazz/Blues" => "Jazz",
    "Kindermusik" => "Children's",
    "Miscellaneous/Other" => "Miscellaneous",
    "Other" => "Miscellaneous",
    "Age/Instumental" => "New Age",
    "Pop / Rock" => "Pop/Rock",
    "R&B/Soul" => "R&B",
    "Soundtracks" => "Soundtrack",
    "Soundtracks/Musicals" => "Soundtrack",
    "World Music (Other)" => "World Music"
);

$genre_crumb_name = $genre;

$html->addCrumb(__('All Genre', true), '/genres/view/');
$html->addCrumb($this->getTextEncode($genre_crumb_name), '/genres/view/' . base64_encode($genre_crumb_name));
$totalRows = count($genresAll);
?>


<section class="genres-page">

    <div class="breadcrumbs">
        <span><?php echo $html->getCrumbs('>', __('Home', true), '/homes'); ?></span>
    </div>

    <header class="clearfix">
        <h2> <?php echo __('Search for your favorite music.', true); ?></h2>
        <div class="faq-link"><?php echo __('Need help? Visit our', true); ?> <?php echo $html->link(__('FAQ section.', true), array('controller' => 'questions', 'action' => 'index')); ?></div>
    </header>

    <div class="genres-container">
        <header style="margin-bottom:0px;">
	 	<div class="genres-header">Genres</div>
		<div class="a-z-header">A - Z</div>
		<div class="artist-header">Artist</div>
							</header>
    <section class="genre-filter-container clearfix">
            <div class="genre-column">
                <ul>
                    <li>
                        <a class="genre_list_item_all <?php echo ($genre == 'All') ? 'active' : '' ?>" href="javascript:void(0)" data-genre="All Artists" id="genre_list_item_0" 
                           onclick="load_artist('/genres/ajax_view/<?php echo base64_encode('All'); ?>/', '0', '<?php echo addslashes('All'); ?>')">
                               <?php echo __('All Artists'); ?>
                        </a>
                    </li>

                    <?php
                    $genre_count = 1;
                   
                    foreach ($genresAll as $genre_name):
                       $genreNnameWithoutEncode = $genre_name;
                       $genre_name= $this->getTextEncode($genre_name);
                       

                        if ($genre_name != '')
                        {	
                          
                           
                            if ($genre_name != 'Porn Groove')
                            {
                                if ($genre_name == $genre)
                                {
                                    ?>
                                    <li> 
                                        <a  class="genre_list_item_all selected" 
                                            href="javascript:void(0);" data-genre="<?php echo addslashes($genreNnameWithoutEncode); ?>" 
                                            id="genre_list_item_<?php echo $genre_count; ?>" 
                                            onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genreNnameWithoutEncode); ?>/All', '<?php echo $genre_count; ?>', '<?php echo addslashes($genre_name); ?>')" >
                                                <?php echo $genre_name; ?>
                                        </a>
                                    </li>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <li> 
                                        <a  class="genre_list_item_all" href="javascript:void(0);" 
                                            data-genre="<?php echo addslashes($genreNnameWithoutEncode); ?>" 
                                            id="genre_list_item_<?php echo $genre_count; ?>"  
                                            onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genreNnameWithoutEncode); ?>/All', '<?php echo $genre_count; ?>', '<?php echo addslashes($genre_name); ?>')" >
                                                <?php echo $genre_name; ?>
                                        </a>
                                    </li>
                                    <?php
                                }
                            }
                        }
                        $genre_count++;
                    endforeach;
                    ?>    


                </ul>
            </div>

       <div id="ajax_artistlist_content">      

                <div class="alpha-artist-list-column">
                    <ul>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "All")
                            {
                                ?>class=" selected active" <?php } ?> data-letter="All"  onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/All', '', '')">ALL</a></li>                                            
                        
                       <?php  if(!in_array('spl',$artistsNoAlpha)){ ?>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "spl")
                            {
                                ?>class="selected active" <?php } ?>  data-letter="#"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/spl', '', '')">#</a>
                        </li>
                        <?php } ?>
                        <?php  if(!in_array('A',$artistsNoAlpha)){ ?>                            
                            <li><a   href="javascript:void(0);" <?php
                                if ($selectedAlpha == "A")
                                {
                                    ?>class="selected active" <?php } ?>  data-letter="A"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/A', '', '')">A</a>
                            </li>
                        <?php } ?>
                        <?php  if(!in_array('B',$artistsNoAlpha)){ ?>     
                            <li><a   href="javascript:void(0);" <?php
                                if ($selectedAlpha == "B")
                                {
                                    ?>class="selected active" <?php } ?>  data-letter="B"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/B', '', '')">B</a>
                            </li>
                        <?php } ?>
                        <?php  if(!in_array('C',$artistsNoAlpha)){ ?>    
                            <li><a   href="javascript:void(0);" <?php
                                if ($selectedAlpha == "C")
                                {
                                    ?>class="selected active" <?php } ?>  data-letter="C"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/C', '', '')">C</a>
                            </li>
                        <?php } ?>
                        <?php  if(!in_array('D',$artistsNoAlpha)){ ?>     
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "D")
                            {
                                ?>class="selected active" <?php } ?>  data-letter="D"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/D', '', '')">D</a>
                        </li>
                        <?php } ?>
                        <?php  if(!in_array('E',$artistsNoAlpha)){ ?>
                            <li><a   href="javascript:void(0);" <?php
                                if ($selectedAlpha == "E")
                                {
                                    ?>class="selected active" <?php } ?>  data-letter="E"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/E', '', '')">E</a>
                            </li>
                        <?php } ?>
                        <?php  if(!in_array('F',$artistsNoAlpha)){ ?>
                            <li><a   href="javascript:void(0);" <?php
                                if ($selectedAlpha == "F")
                                {
                                    ?>class="selected active" <?php } ?>  data-letter="F"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/F', '', '')">F</a>
                            </li>
                        <?php } ?>
                        <?php  if(!in_array('G',$artistsNoAlpha)){ ?>
                            <li><a   href="javascript:void(0);" <?php
                                if ($selectedAlpha == "G")
                                {
                                    ?>class="selected active" <?php } ?>  data-letter="G"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/G', '', '')">G</a>
                            </li>
                        <?php } ?>
                        <?php  if(!in_array('H',$artistsNoAlpha)){ ?>
                            <li><a   href="javascript:void(0);" <?php
                                if ($selectedAlpha == "H")
                                {
                                    ?>class="selected active" <?php } ?>  data-letter="H"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/H', '', '')">H</a>
                            </li>
                        <?php } ?>
                        <?php  if(!in_array('I',$artistsNoAlpha)){ ?>
                            <li><a   href="javascript:void(0);" <?php
                                if ($selectedAlpha == "I")
                                {
                                    ?>class="selected active" <?php } ?>  data-letter="I"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/I', '', '')">I</a>
                            </li>
                        <?php } ?>
                        <?php  if(!in_array('J',$artistsNoAlpha)){ ?>
                            <li><a   href="javascript:void(0);" <?php
                                if ($selectedAlpha == "J")
                                {
                                    ?>class="selected active" <?php } ?>  data-letter="J"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/J', '', '')">J</a>
                            </li>
                        <?php } ?>
                        <?php  if(!in_array('K',$artistsNoAlpha)){ ?>
                            <li><a   href="javascript:void(0);" <?php
                                if ($selectedAlpha == "K")
                                {
                                    ?>class="selected active" <?php } ?>  data-letter="K"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/K', '', '')">K</a>
                            </li>
                        <?php } ?>
                        <?php  if(!in_array('L',$artistsNoAlpha)){ ?>
                            <li><a   href="javascript:void(0);" <?php
                                if ($selectedAlpha == "L")
                                {
                                    ?>class="selected active" <?php } ?>  data-letter="L"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/L', '', '')">L</a>
                            </li>
                        <?php } ?>
                        <?php  if(!in_array('M',$artistsNoAlpha)){ ?>
                            <li><a   href="javascript:void(0);" <?php
                                if ($selectedAlpha == "M")
                                {
                                    ?>class="selected active" <?php } ?>  data-letter="M"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/M', '', '')">M</a>
                            </li>
                        <?php } ?>
                        <?php  if(!in_array('N',$artistsNoAlpha)){ ?>
                            <li><a   href="javascript:void(0);" <?php
                                if ($selectedAlpha == "N")
                                {
                                    ?>class="selected active" <?php } ?>  data-letter="N"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/N', '', '')">N</a>
                            </li>
                        <?php } ?>
                       <?php  if(!in_array('O',$artistsNoAlpha)){ ?>
                            <li><a   href="javascript:void(0);" <?php
                                if ($selectedAlpha == "O")
                                {
                                    ?>class="selected active" <?php } ?>  data-letter="O"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/O', '', '')">O</a>
                            </li>
                        <?php } ?>
                        <?php  if(!in_array('P',$artistsNoAlpha)){ ?>
                            <li><a   href="javascript:void(0);" <?php
                                if ($selectedAlpha == "P")
                                {
                                    ?>class="selected active" <?php } ?>  data-letter="P"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/P', '', '')">P</a>
                            </li>
                        <?php } ?>
                        <?php  if(!in_array('Q',$artistsNoAlpha)){ ?>
                            <li><a   href="javascript:void(0);" <?php
                                if ($selectedAlpha == "Q")
                                {
                                    ?>class="selected active" <?php } ?>  data-letter="Q"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/Q', '', '')">Q</a>
                            </li>
                        <?php } ?>
                        <?php  if(!in_array('R',$artistsNoAlpha)){ ?>
                            <li><a   href="javascript:void(0);" <?php
                                if ($selectedAlpha == "R")
                                {
                                    ?>class="selected active" <?php } ?>  data-letter="R"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/R', '', '')">R</a>
                            </li>
                        <?php } ?>
                        <?php  if(!in_array('S',$artistsNoAlpha)){ ?>
                            <li><a   href="javascript:void(0);" <?php
                                if ($selectedAlpha == "S")
                                {
                                    ?>class="selected active" <?php } ?>  data-letter="S"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/S', '', '')">S</a>
                            </li>
                        <?php } ?>
                        <?php  if(!in_array('T',$artistsNoAlpha)){ ?>
                            <li><a   href="javascript:void(0);" <?php
                                if ($selectedAlpha == "T")
                                {
                                    ?>class="selected active" <?php } ?>  data-letter="T"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/T', '', '')">T</a>
                            </li>
                        <?php } ?>
                        <?php  if(!in_array('U',$artistsNoAlpha)){ ?>
                            <li><a   href="javascript:void(0);" <?php
                                if ($selectedAlpha == "U")
                                {
                                    ?>class="selected active" <?php } ?>  data-letter="U"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/U', '', '')">U</a>
                            </li>
                        <?php } ?>
                        <?php  if(!in_array('V',$artistsNoAlpha)){ ?>
                            <li><a   href="javascript:void(0);" <?php
                                if ($selectedAlpha == "V")
                                {
                                    ?>class="selected active" <?php } ?>  data-letter="V"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/V', '', '')">V</a>
                            </li>
                        <?php } ?>
                        <?php  if(!in_array('W',$artistsNoAlpha)){ ?>
                            <li><a   href="javascript:void(0);" <?php
                                if ($selectedAlpha == "W")
                                {
                                    ?>class="selected active" <?php } ?>  data-letter="W"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/W', '', '')">W</a>
                            </li>
                        <?php } ?>
                        <?php  if(!in_array('X',$artistsNoAlpha)){ ?>
                            <li><a   href="javascript:void(0);" <?php
                                if ($selectedAlpha == "X")
                                {
                                    ?>class="selected active" <?php } ?>  data-letter="X"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/X', '', '')">X</a>
                            </li>
                        <?php } ?>
                        <?php  if(!in_array('Y',$artistsNoAlpha)){ ?>
                            <li><a   href="javascript:void(0);" <?php
                                if ($selectedAlpha == "Y")
                                {
                                    ?>class="selected active" <?php } ?>  data-letter="Y"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/Y', '', '')">Y</a>
                            </li>
                        <?php } ?>
                        <?php  if(!in_array('Z',$artistsNoAlpha)){ ?>
                            <li><a   href="javascript:void(0);" <?php
                                if ($selectedAlpha == "Z")
                                {
                                    ?>class="selected active" <?php } ?>  data-letter="Z"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/Z', '', '')">Z</a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>

                <div class="artist-column" id="artistscroll">					
                    <ul id="artistlistrecord">						                                            
                        <?php
                        if (count($artistList) > 0)
                        {
                            for ($i = 0; $i < count($artistList); $i++)
                            {
                                $artistName = $this->getTextEncode($artistList[$i]['Song']['ArtistText']);
                                
                                if ($artistName != "")
                                {
                                    echo " <li>";
                                    $selected = (str_replace('/', '@', base64_encode($artistList[$i]['Song']['ArtistText'])) == $this->Session->read('calledArtist')) ? "class='selected'" : "";
                                    $artistName = str_replace("'", '', ($artistName));
                                    $url = "artists/album_ajax/" . str_replace('/', '@', base64_encode($artistList[$i]['Song']['ArtistText'])) . "/" . base64_encode($genre);
                                    ?>
                                    <a href="/artists/album/<?php echo str_replace('/', '@', base64_encode($artistList[$i]['Song']['ArtistText'])); ?>/<?= base64_encode($genre) ?>">
                                        <?php
                                    echo wordwrap($artistName, 35, "<br />\n", TRUE);
                                    echo '</a>';
                                    echo '</li>';                                    
                                }
                            }
                        }
                        else
                        {
                            echo "<li><a href='javascript:void(0)' data-artist='No Results Found'>No Results Found</a></li>";
                        }
                        ?> 

                    </ul>
                    <span id="artist_loader" style="display:none;"   ><img src="<? echo $this->webroot; ?>app/webroot/img/aritst-ajax-loader.gif"  style="padding-left:115px;padding-buttom:25px;border:0;" alt=""/></span>
                </div>
        </div>
    </section>
        
    </div>
    <div class="genre-button-container">
	<button class="genre-scroll-up"></button>
	<button class="genre-scroll-down"></button>
	<button class="artist-scroll-up"></button>
        <button class="artist-scroll-down"></button>
   </div>
</section>


<?php
/**
 * Below code is used for when user comes from 
 * any other page to genres page and its data are 
 * saved in session then Artist ,Album list and selected 
 * album will be shown.
 */
if ($this->Session->check('calledArtist') && !$this->Session->check('calledAlbum'))
{
    $album_list_url = "artists/album_ajax/" . $this->Session->read('calledArtist') . "/" . $this->Session->read('calledGenre');
    echo "<input type='hidden' id='allAlbumUrl' value='" . $album_list_url . "'  />";
    ?>
    <script>

        $(document).ready(function()
        {
            <?php
            //doing pagination 
            if ($this->Session->check('page'))
            {
                ?>
                        toScrollArtist(<?= $this->Session->read('page') ?>);
                <?php
            }
            else
            {
                ?>
                        scrolltoSelectedArtist();
                <?php
            }
            ?>
                    
            sleep(100);
            var all_album_url = $("#allAlbumUrl").attr('value');
            showAllAlbumsList(all_album_url);
        });

    </script>

    <?php
}
elseif ($this->Session->check('calledAlbum'))
{
    $album_list_url = "artists/album_ajax/" . $this->Session->read('calledArtist') . "/" . $this->Session->read('calledGenre');
    echo "<input type='hidden' id='allAlbumUrl' value='" . $album_list_url . "'  />";

    $albumURL = "artists/album_ajax_view/" . str_replace('/', '@', base64_encode($this->Session->read('calledArtist'))) . "/" . $this->Session->read('calledAlbum') . "/" . base64_encode($this->Session->read('calledProvider'));
    echo "<input type='hidden' id='selectedAlbumUrl' value='" . $albumURL . "'  />";
    ?>
    <script>
        $(document).ready(function() {

            //Paginate the Artist list if it was already done before
            <?php
            if ($this->Session->check('page'))
            {
                ?>
                        toScrollArtist(<?= $this->Session->read('page') ?>);
                <?php
            }
            else
            {
                ?>
                    scrolltoSelectedArtist();
               <?php 
            }
            ?>

            sleep(100);
            var all_album_url = $("#allAlbumUrl").attr('value');            
            $('#album_details_container').html('');
            $('.album-list-span').html('<span id="mydiv" style="height: 250px; width: 250px; position: relative; background-color: gray;">\n\
                    <img src="' + webroot + 'app/webroot/img/AjaxLoader.gif" style="display: block; left: 50%; margin-left: 115px; margin-top: 85px; position: absolute; top: 50%;"/></span>');

            var data = "";
            $.ajax({
                type: "post", // Request method: post, get
                url: webroot + all_album_url, // URL to request
                data: data, // post data
                success: function(response) {
                    $('.album-list-span').html(response);
                    $('a[title]').qtip({
                        position: {
                            corner: {
                                target: 'topLeft',
                                tooltip: 'bottomRight'
                            }
                        },
                        style: {
                            color: '#444',
                            fontSize: 12,
                            border: {
                                color: '#444'
                            },
                            width: {
                                max: 350,
                                min: 0
                            },
                            tip: {
                                corner: 'bottomRight',
                                size: {
                                    x: 5,
                                    y: 5
                                }
                            }
                        }
                    });
                    
                    //scroll to selected album
                    scrollToSelectedAlbum();
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    // alert('No album available for this artist.');
                }
            });
            
            sleep(100);
            //calling the selected Album Details
            var album_url = $("#selectedAlbumUrl").attr('value');
            showAlbumDetails(album_url);
        });
    </script>
    <?php
}
?>


<script>

    $(document).ready(function() {
        scrollToSelectedGenre();
        scrollToSelectedAlpha();
    });

    function toScrollArtist(totalPageCalled)
    {

        for (var i = 2; i <= totalPageCalled; i++) {
            var data = "npage=" + i;
            var link = webroot + 'genres/ajax_view_pagination/page:' + i + "/<?= $this->Session->read('calledGenre') ?>" + "/<?= $this->Session->read('selectedAlpha') ?>";
            jQuery.ajax({
                type: "post", // Request method: post, get
                url: link, // URL to request
                data: data, // post data
                success: function(newitems) {
                    if (newitems) {
                        $('#artist_loader').hide();
                        $('#artistlistrecord').append(newitems);
                    } else {
                        $('#artist_loader').hide();
                        return;
                    }
                },
                async: false,
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                }
            });
        }

        sleep(1000);
        scrolltoSelectedArtist();
        return;
    }

    function scrolltoSelectedArtist()
    {
        sleep(100);
        var to_scroll = $(document).find("#artistscroll");
        $("#artistlistrecord li").each(function() {
            if ($(this).find('a').hasClass('selected'))
            {
                var scroll_distance = $(this).offset().top - $(this).parent().offset().top;
                to_scroll.animate({
                    scrollTop: scroll_distance
                }, 2000);

                $(this).find('a').focus();

            }
        });
    }

    function scrollToSelectedGenre()
    {
        sleep(100);
        var to_scroll = $(document).find(".genre-list");
        $(document).find(".genre-list li").each(function() {
            if ($(this).find('a').hasClass('selected'))
            {
                var scroll_distance = $(this).offset().top - $(this).parent().offset().top;
                to_scroll.animate({
                    scrollTop: scroll_distance
                }, 2000);

                $(this).find('a').focus();

            }
        });
    }

    function scrollToSelectedAlpha()
    {
        var to_scroll = $(document).find(".alphabetical-filter");
        $(document).find('.alphabetical-filter li').each(function() {
            if ($(this).find('a').hasClass('selected'))
            {
                var scroll_distance = $(this).offset().top - $(this).parent().offset().top;
                to_scroll.animate({
                    scrollTop: scroll_distance
                }, 2000);
                $(this).find('a').focus();
            }
        });
    }

    function scrollToSelectedAlbum()
    {
        //focus on selected Album
        var album_list = $(document).find('div.album-list-shadow-container .album-list > div.album-overview-container');
        var called_Album = "<?php echo $this->Session->read('calledAlbumText') ?>";
         
        album_list.each(function() {
            var album_id = $(this).attr('id');          

            if (album_id === called_Album)
            {
                var to_scroll = $(document).find('div.album-list-shadow-container .album-list');
                var scroll_distance = $(this).offset().top - $(this).parent().offset().top;
                to_scroll.animate({
                    scrollTop: scroll_distance
                }, 2000);

            }
        });
    }
</script>