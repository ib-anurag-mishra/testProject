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
        <span><?php echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes'); ?></span>
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
                        <a class="genre_list_item_all <?php echo ($genre == 'All') ? selected : '' ?>" href="#" data-genre="All Artists" id="genre_list_item_0" 
                           onclick="load_artist('/genres/ajax_view/<?php echo base64_encode('All'); ?>/All', '0', '<?php echo addslashes('All'); ?>')">
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

        </div>

        <div class="alphabetical-shadow-container">
            <h3><?php __('Artist'); ?></h3>
            <div class="alphabetical-filter">
                <ul>
                    <li><a   href="javascript:void(0);" data-letter="All"  onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>All', '', '')">ALL</a></li>                                            
                    <li><a   href="javascript:void(0);" data-letter="#"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/spl', '', '')">#</a></li> 
                    <li><a   href="javascript:void(0);" data-letter="A"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/A', '', '')">A</a></li>
                    <li><a   href="javascript:void(0);" data-letter="B"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/B', '', '')">B</a></li>
                    <li><a   href="javascript:void(0);" data-letter="C"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/C', '', '')">C</a></li>
                    <li><a   href="javascript:void(0);" data-letter="D"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/D', '', '')">D</a></li>
                    <li><a   href="javascript:void(0);" data-letter="E"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/E', '', '')">E</a></li>
                    <li><a   href="javascript:void(0);" data-letter="F"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/F', '', '')">F</a></li>
                    <li><a   href="javascript:void(0);" data-letter="G"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/G', '', '')">G</a></li>
                    <li><a   href="javascript:void(0);" data-letter="H"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/H', '', '')">H</a></li>
                    <li><a   href="javascript:void(0);" data-letter="I"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/I', '', '')">I</a></li>
                    <li><a   href="javascript:void(0);" data-letter="J"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/J', '', '')">J</a></li>
                    <li><a   href="javascript:void(0);" data-letter="K"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/K', '', '')">K</a></li>
                    <li><a   href="javascript:void(0);" data-letter="L"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/L', '', '')">L</a></li>
                    <li><a   href="javascript:void(0);" data-letter="M"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/M', '', '')">M</a></li>
                    <li><a   href="javascript:void(0);" data-letter="N"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/N', '', '')">N</a></li>
                    <li><a   href="javascript:void(0);" data-letter="O"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/O', '', '')">O</a></li>
                    <li><a   href="javascript:void(0);" data-letter="P"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/P', '', '')">P</a></li>
                    <li><a   href="javascript:void(0);" data-letter="Q"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/Q', '', '')">Q</a></li>
                    <li><a   href="javascript:void(0);" data-letter="R"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/R', '', '')">R</a></li>
                    <li><a   href="javascript:void(0);" data-letter="S"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/S', '', '')">S</a></li>
                    <li><a   href="javascript:void(0);" data-letter="T"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/T', '', '')">T</a></li>
                    <li><a   href="javascript:void(0);" data-letter="U"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/U', '', '')">U</a></li>
                    <li><a   href="javascript:void(0);" data-letter="V"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/V', '', '')">V</a></li>
                    <li><a   href="javascript:void(0);" data-letter="W"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/W', '', '')">W</a></li>
                    <li><a   href="javascript:void(0);" data-letter="X"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/X', '', '')">X</a></li>
                    <li><a   href="javascript:void(0);" data-letter="Y"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/Y', '', '')">Y</a></li>
                    <li><a   href="javascript:void(0);" data-letter="Z"   onclick="load_artist('/genres/ajax_view/<?php echo base64_encode($genre); ?>/Z', '', '')">Z</a></li>
                </ul>
            </div>
        </div>


        <div class="artist-list-shadow-container">
            <h3>&nbsp;</h3>
            <div class="artist-list" id="artistscroll">
                <ul id="artistlistrecord">
                </ul>

                <span id="artist_loader" style="display:none;">
                    <img src="<? echo $this->webroot; ?>app/webroot/img/aritst-ajax-loader.gif" border="0" 
                         style="padding-left:115px;padding-buttom:25px;"/>
                </span>
            </div>
        </div>

        <div class="border"></div>
        
        <span class="album-list-span"></span>

    </section>
</section>