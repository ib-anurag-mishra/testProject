<?php
/*
  File Name : index.ctp
  File Description : View page for genre index
  Author : m68interactive
 */
?>    

<style>

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
    //load the artist list when  scroll reached at the end
    $(document).ready(function() {
        var preValue = 1;
        var artistPage = 2;
        $("#artistscroll").scroll(function() {
            if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {

                $('#artist_loader').show();
                var totalPages = <?= $totalPages ?>;
                var data = "npage=" + artistPage;

                if ((preValue != artistPage) && (artistPage <= totalPages)) {

                    if (artistPage <= totalPages) {

                        preValue = artistPage;
                        var link = webroot + 'genres/ajax_view_pagination/page:' + artistPage + '/<?= base64_encode($genre); ?>' + '/All';

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
                                //alert('No artist list available');
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

//$genre_crumb_name = isset($genre_text_conversion[trim($genre)])?$genre_text_conversion[trim($genre)]:trim($genre);
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


    <section class="genre-filter-container clearfix">
        <div class="genre-shadow-container">
            <h3>Genre</h3>
            <div class="genre-list">
                <ul>
                    <li>
                        <a class="genre_list_item_all <?php echo ($genre == 'All') ? 'selected' : '' ?>" href="javascript:void(0)" data-genre="All Artists" id="genre_list_item_0" 
                           onclick="load_artist('/genres/ajax_view/<?php echo base64_encode('All'); ?>/', '0', '<?php echo addslashes('All'); ?>')">
                               <?php echo __('All Artists'); ?>
                        </a>
                    </li>

                    <?php
                    $genre_count = 1;
                    foreach ($genresAll as $genre_all):

                        if ($genre_all['Genre']['Genre'] != '')
                        {
                            //$genre_name = isset($genre_text_conversion[trim($genre_all['Genre']['Genre'])])?$genre_text_conversion[trim($genre_all['Genre']['Genre'])]:$genre_all['Genre']['Genre'];	
                            $genre_name = $genre_all['Genre']['Genre'];

                            if ($genre_name != 'Porn Groove')
                            {
                                if ($genre_name == $genre)
                                {
                                    ?>
                                    <li> 
                                        <a  class="genre_list_item_all selected" 
                                            href="javascript:void(0);" data-genre="<?php echo addslashes($this->getTextEncode($genre_name)); ?>" 
                                            id="genre_list_item_<?php echo $genre_count; ?>" 
                                            onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>/All', '<?php echo $genre_count; ?>', '<?php echo addslashes($this->getTextEncode($genre_name)); ?>')" >
                                                <?php echo $this->getTextEncode($genre_name); ?>
                                        </a>
                                    </li>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <li> 
                                        <a  class="genre_list_item_all" href="javascript:void(0);" 
                                            data-genre="<?php echo addslashes($this->getTextEncode($genre_name)); ?>" 
                                            id="genre_list_item_<?php echo $genre_count; ?>"  
                                            onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre_name); ?>/All', '<?php echo $genre_count; ?>', '<?php echo addslashes($this->getTextEncode($genre_name)); ?>')" >
                                                <?php echo $this->getTextEncode($genre_name); ?>
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
        </div>


        <div class="border"></div>

        <div id="ajax_artistlist_content">     

            <div class="alphabetical-shadow-container">
                <h3><?php __('Artist'); ?></h3>
                <div class="alphabetical-filter">
                    <ul>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "All")
                            {
                                ?>class="selected" <?php } ?> data-letter="All"  onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>', '', '')">ALL</a></li>                                            
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "#")
                            {
                                ?>class="selected" <?php } ?>  data-letter="#"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/spl', '', '')">#</a></li> 
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "A")
                            {
                                ?>class="selected" <?php } ?>  data-letter="A"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/A', '', '')">A</a></li>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "B")
                            {
                                ?>class="selected" <?php } ?>  data-letter="B"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/B', '', '')">B</a></li>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "C")
                            {
                                ?>class="selected" <?php } ?>  data-letter="C"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/C', '', '')">C</a></li>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "D")
                            {
                                ?>class="selected" <?php } ?>  data-letter="D"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/D', '', '')">D</a></li>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "E")
                            {
                                ?>class="selected" <?php } ?>  data-letter="E"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/E', '', '')">E</a></li>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "F")
                            {
                                ?>class="selected" <?php } ?>  data-letter="F"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/F', '', '')">F</a></li>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "G")
                            {
                                ?>class="selected" <?php } ?>  data-letter="G"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/G', '', '')">G</a></li>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "H")
                            {
                                ?>class="selected" <?php } ?>  data-letter="H"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/H', '', '')">H</a></li>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "I")
                            {
                                ?>class="selected" <?php } ?>  data-letter="I"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/I', '', '')">I</a></li>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "J")
                            {
                                ?>class="selected" <?php } ?>  data-letter="J"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/J', '', '')">J</a></li>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "K")
                            {
                                ?>class="selected" <?php } ?>  data-letter="K"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/K', '', '')">K</a></li>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "L")
                            {
                                ?>class="selected" <?php } ?>  data-letter="L"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/L', '', '')">L</a></li>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "M")
                            {
                                ?>class="selected" <?php } ?>  data-letter="M"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/M', '', '')">M</a></li>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "N")
                            {
                                ?>class="selected" <?php } ?>  data-letter="N"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/N', '', '')">N</a></li>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "O")
                            {
                                ?>class="selected" <?php } ?>  data-letter="O"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/O', '', '')">O</a></li>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "P")
                            {
                                ?>class="selected" <?php } ?>  data-letter="P"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/P', '', '')">P</a></li>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "Q")
                            {
                                ?>class="selected" <?php } ?>  data-letter="Q"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/Q', '', '')">Q</a></li>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "R")
                            {
                                ?>class="selected" <?php } ?>  data-letter="R"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/R', '', '')">R</a></li>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "S")
                            {
                                ?>class="selected" <?php } ?>  data-letter="S"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/S', '', '')">S</a></li>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "T")
                            {
                                ?>class="selected" <?php } ?>  data-letter="T"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/T', '', '')">T</a></li>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "U")
                            {
                                ?>class="selected" <?php } ?>  data-letter="U"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/U', '', '')">U</a></li>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "V")
                            {
                                ?>class="selected" <?php } ?>  data-letter="V"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/V', '', '')">V</a></li>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "W")
                            {
                                ?>class="selected" <?php } ?>  data-letter="W"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/W', '', '')">W</a></li>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "X")
                            {
                                ?>class="selected" <?php } ?>  data-letter="X"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/X', '', '')">X</a></li>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "Y")
                            {
                                ?>class="selected" <?php } ?>  data-letter="Y"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/Y', '', '')">Y</a></li>
                        <li><a   href="javascript:void(0);" <?php
                            if ($selectedAlpha == "Z")
                            {
                                ?>class="selected" <?php } ?>  data-letter="Z"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/Z', '', '')">Z</a></li>
                    </ul>
                </div>
            </div>


            <div class="artist-list-shadow-container">
                <h3></h3>
                <div class="artist-list" id="artistscroll">					
                    <ul id="artistlistrecord">						                                            
                        <?php
                        if (count($genres) > 0)
                        {
                            for ($i = 0; $i < count($genres); $i++)
                            {
                                echo " <li>";
                                $ArtistName = $this->getTextEncode($genres[$i]['Song']['ArtistText']);
                                $selected = ($ArtistName == $this->Session->read('calledArtist')) ? "class='selected'" : "";

                                $ArtistName = str_replace("'", '', ($ArtistName));

                                $url = "artists/album_ajax/" . str_replace('/', '@', base64_encode($genres[$i]['Song']['ArtistText'])) . "/" . base64_encode($genre);



                                echo "<a href=\"javascript:void(0);\" onclick=\"showAllAlbumsList('" . $url . "')\" data-artist='" . $ArtistName . "'" . " $selected >";
                                echo wordwrap($ArtistName, 35, "<br />\n", TRUE);
                                echo '</a>';
                                echo '</li>';
                            }
                        }
                        else
                        {
                            echo "<li><a href='javascript:void(0)' data-artist='No Results Found'>No Results Found</a></li>";
                        }
                        ?> 

                        <!--  <li><a href="#" data-artist="A.J. Croce">A.J. Croce</a></li> -->

                    </ul>
                    <span id="artist_loader" style="display:none;"   ><img src="<? echo $this->webroot; ?>app/webroot/img/aritst-ajax-loader.gif"  style="padding-left:115px;padding-buttom:25px;border:0;" alt=""/></span>
                </div>
            </div>
        </div>

        <div class="border"></div>

        <span class="album-list-span"></span>

    </section>

    <section class="album-detail-container clearfix" id='album_details_container'></section>


</section>



<?php
/**
 * Below code is used for when user comes from 
 * any other page to genres page and its data are 
 * saved in session then Artist , 
 * Album list and selected 
 * album will be shown.
 */
if ($this->Session->check('selectedAlpha'))
{
    ?>
    <script>
    $(document).ready(function() {

        $(document).find('.alphabetical-filter li').each(function() {
            if ($(this).find('a').hasClass('selected'))
            {
                $(this).find('a').focus();
            }
        });


    <?php
    if ($this->Session->check('page'))
    {
        ?>
            var total_page_called = <?= $this->Session->check('page') ?>;

            var to_scroll = $("#artistscroll");
            var scroll_distance = $("#artistscroll").get(0).scrollHeight;

            for (i = 0; i < total_page_called; i++)
            {
                to_scroll.animate({
                    scrollTop: scroll_distance
                }, 3000);
                
                $(document).find('#artist_loader').hide();

            }
        <?php
    }
    ?>
    });
    </script>
    <?php
}

if ($this->Session->check('calledArtist') && !$this->Session->check('calledAlbum'))
{
    $album_list_url = "artists/album_ajax/" . str_replace('/', '@', base64_encode($this->Session->read('calledArtist'))) . "/" . $this->Session->read('calledGenre');
    echo "<input type='hidden' id='allAlbumUrl' value='" . $album_list_url . "'  />";
    ?>
    <script>
        $(document).ready(function()
        {
    <?php
    if ($this->Session->check('page'))
    {
        ?>
                var total_page_called = <?= $this->Session->check('page') ?>;

                var height = $("#artistscroll").height();

                for (i = 0; i < total_page_called; i++)
                {
                    $(document).find("#artistscroll").animate({
                        scrollTop: height
                    }, 2000);

                    $(document).find('#artist_loader').hide();

                    $(document).find("#artistscroll").scrollTop(height);
                }
        <?php
    }
    ?>

            $("#artistlistrecord li").each(function() {
                if ($(this).find('a').hasClass('selected'))
                {
                    $(this).find('a').focus();
                }
            });


            var all_album_url = $("#allAlbumUrl").attr('value');
            showAllAlbumsList(all_album_url);
        });
    </script>
    <?php
}
else if ($this->Session->check('calledAlbum'))
{
    $album_list_url = "artists/album_ajax/" . str_replace('/', '@', base64_encode($this->Session->read('calledArtist'))) . "/" . $this->Session->read('calledGenre');
    echo "<input type='hidden' id='allAlbumUrl' value='" . $album_list_url . "'  />";

    $albumURL = "artists/album_ajax_view/" . str_replace('/', '@', base64_encode($this->Session->read('calledArtist'))) . "/" . $this->Session->read('calledAlbum') . "/" . base64_encode($this->Session->read('calledProvider'));
    echo "<input type='hidden' id='selectedAlbumUrl' value='" . $albumURL . "'  />";
    ?>
    <script>
        $(document).ready(function() {
            var all_album_url = $("#allAlbumUrl").attr('value');
            showAllAlbumsList(all_album_url);

            setTimeout(function() {
                if ($(document).find('div.album-list-shadow-container'))
                {

                    //focus on selected Artist
                    $("#artistlistrecord li").each(function() {
                        if ($(this).find('a').hasClass('selected'))
                        {
                            $(this).find('a').focus();
                        }
                    });

                    //focus on selected Album
                    $(document).find('div.album-list-shadow-container .album-list').children().each(function() {
                        var album_title = $(this).find('div.album-title').find('a').text();
                        var called_Album = "<?php echo $this->Session->read('calledAlbumText') ?>";
                        if (album_title === called_Album)
                        {
                            var scrollTo = $(this).find('div.album-title');
                            var scrollPos = scrollTo.offset().top - $(this).parent().offset().top;
                            $(document).find('div.album-list-shadow-container .album-list').scrollTop(scrollPos);
                            //$(this).find('div.album-title').find('a').focus();
                        }
                    });

                    //calling the selected Album Details
                    var album_url = $("#selectedAlbumUrl").attr('value');
                    showAlbumDetails(album_url);
                }
            }, 3000);
        });
    </script>
    <?php
}
?>