<?php
/*
  File Name : my_wishlist.ctp
  File Description : View page for wishlist information
  Author : m68interactive
 */
?>
<?php
$setLang = 'en';

if ($this->Session->read('Config.language') == 'en')
{
    $setLang = 'en';
}
else
{
    $setLang = 'es';
}

function ieversion()
{
    ereg('MSIE ([0-9]\.[0-9])', $_SERVER['HTTP_USER_AGENT'], $reg);
    if (!isset($reg[1]))
    {
        return -1;
    }
    else
    {
        return floatval($reg[1]);
    }
}

$ieVersion = ieversion();
?>
<style type="text/css">

</style>
<script lenguage="javascript">
    var languageSet = '<?php echo $setLang; ?>';

</script>
<form id="sortForm" name="sortForm" method='post'>
    <input id='sort' type='hidden' name="sort" value="<?php echo $sort; ?>" />
    <input id='sortOrder' type='hidden' name="sortOrder" value="<?php echo $sortOrder; ?>" />
</form>
<section class="my-wishlist-page">

    <div class="breadcrumbs">
        <?php
        $html->addCrumb(__('My Wishlist', true), '/homes/my_wishlist');
        echo $html->getCrumbs(' > ', __('Home', true), '/homes');
        ?>
    </div>
    <header class="clearfix">
        <h2><?php echo __('My Wishlist', true); ?></h2>
        <div class="faq-link"><?php echo __('Need help? Visit our', true); ?> <?php echo $html->link(__('FAQ section.', true), array('controller' => 'questions', 'action' => 'index')); ?></div>
    </header>
    <div class="instructions">
        <?php echo $page->getPageContent('wishlist'); ?>	
    </div>
    <nav class="my-wishlist-filter-container clearfix">
        <?php
        if ($sort == 'date')
        {
            if ($sortOrder == 'asc')
            {
                ?>    
                <div class="date-filter-button filter active" style="cursor:pointer;"><?php echo __('Date'); ?></div>
                <?php
            }
            else
            {
                ?>
                <div class="date-filter-button filter active toggled" style="cursor:pointer;"><?php echo __('Date'); ?></div>
                <?php
            }
        }
        else
        {
            ?>
            <div class="date-filter-button filter " style="cursor:pointer;"><?php echo __('Date'); ?></div>
            <?php
        }
        if ($sort == 'song')
        {
            if ($sortOrder == 'asc')
            {
                ?>    
                <div class="song-filter-button filter active" style="cursor:pointer;"><?php echo __('Song'); ?></div>
                <?php
            }
            else
            {
                ?>
                <div class="song-filter-button filter active toggled" style="cursor:pointer;"><?php echo __('Song'); ?></div>
                <?php
            }
        }
        else
        {
            ?>
            <div class="song-filter-button filter" style="cursor:pointer;"><?php echo __('Song'); ?></div>
            <?php
        }
        ?>
        <div class="music-filter-button tab" style="cursor:pointer;"><?php echo __('Music'); ?></div>
        <div class="video-filter-button tab" style="cursor:pointer;"><?php echo __('Video'); ?></div>
        <?php
        if ($sort == 'artist')
        {
            if ($sortOrder == 'asc')
            {
                ?>    
                <div class="artist-filter-button filter active" style="cursor:pointer;"><?php echo __('Artist'); ?></div>
                <?php
            }
            else
            {
                ?>
                <div class="artist-filter-button filter active toggled" style="cursor:pointer;"><?php echo __('Artist'); ?></div>
                <?php
            }
        }
        else
        {
            ?>
            <div class="artist-filter-button filter" style="cursor:pointer;"><?php echo __('Artist'); ?></div>
            <?php
        }
        if ($sort == 'album')
        {
            if ($sortOrder == 'asc')
            {
                ?>    
                <div class="album-filter-button filter active" style="cursor:pointer;"><?php echo __('Album'); ?></div>
                <?php
            }
            else
            {
                ?>
                <div class="album-filter-button filter active toggled" style="cursor:pointer;"><?php echo __('Album'); ?></div>
                <?php
            }
        }
        else
        {
            ?>
            <div class="album-filter-button filter" style="cursor:pointer;"><?php echo __('Album'); ?></div>
            <?php
        }
        ?>  
        <div class="download-button filter" ><?php echo __('Download'); ?></div>

    </nav>
    <div class="my-wishlist-shadow-container">
        <div class="my-wishlist-scrollable">
            <div class="row-container">
                <?php
                if (is_array($wishlistResults) && count($wishlistResults) > 0)
                {


                    for ($i = 0; $i < count($wishlistResults); $i++)
                    {
                        ?>

                        <div class="row clearfix wishlistsong"  id="wishlistsong-<?php echo $wishlistResults[$i]['wishlists']['id'] . "-" . $wishlistResults[$i]['Song']['ProdID'] ?>">
                            <div class="date"><?php echo date('Y-m-d', strtotime($wishlistResults[$i]['wishlists']['created'])); ?></div>
                            <div class="small-album-container">                                     

                                <?php
                                $filePath = shell_exec(Configure::read('App.tokengen_streaming') . $wishlistResults[$i]['File']['CdnPath'] . "/" . $wishlistResults[$i]['File']['SaveAsName']);


                                if ($this->Session->read('library_type') == 2 && $wishlistResults[$i]['Country']['StreamingSalesDate'] <= date('Y-m-d') && $wishlistResults[$i]['Country']['StreamingStatus'] == 1)
                                {
                                    //do the streaming work

                                    $filePath = shell_exec(Configure::read('App.tokengen_streaming'). $wishlistResults[$i]['Full_Files']['CdnPath'] . "/" . $wishlistResults[$i]['Full_Files']['SaveAsName']);
                                    if (!empty($filePath))
                                    {
                                        $songPath = explode(':', $filePath);
                                        $streamUrl = trim($songPath[1]);
                                        $wishlistResults[$i]['streamUrl'] = $streamUrl;
                                        $wishlistResults[$i]['totalseconds'] = $this->Queue->getSeconds($wishlistResults[$i]['Song']['FullLength_Duration']);
                                    }

                                    if ('T' == $wishlistResults[$i]['Song']['Advisory'])
                                    {
                                        $song_title = $wishlistResults[$i]['wishlists']['track_title'] . '(Explicit)';
                                    }
                                    else
                                    {
                                        $song_title = $wishlistResults[$i]['wishlists']['track_title'];
                                    }

                                    echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview", "style" => "cursor:pointer;display:block;", "id" => "play_audio" . $i, "onClick" => 'loadSong("' . $wishlistResults[$i]['streamUrl'] . '", "' . base64_encode($song_title) . '","' . base64_encode($wishlistResults[$i]['wishlists']['artist']) . '",' . $wishlistResults[$i]['totalseconds'] . ',"' . $wishlistResults[$i]['Song']['ProdID'] . '","' . $wishlistResults[$i]['Song']['provider_type'] . '");'));
                                }
                                else
                                {
                                    //do the simple player(this code will update after discussion)
                                    echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview", "style" => "cursor:pointer;display:block;", "id" => "play_audio" . $i, "onClick" => 'playSample(this, "' . $i . '", ' . $wishlistResults[$i]['Song']['ProdID'] . ', "' . base64_encode($wishlistResults[$i]['Song']['provider_type']) . '", "' . $this->webroot . '");'));
                                    echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio" . $i));
                                    echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio" . $i, "onClick" => 'stopThis(this, "' . $i . '");'));
                                }
                                ?>
                            </div>
                            <div class="song-title"><a title="<?php echo $this->getTextEncode($wishlistResults[$i]['wishlists']['track_title']); ?>" href="javascript:void(0)">
                                    <?php
                                    if (strlen($wishlistResults[$i]['wishlists']['track_title']) >= 15)
                                    {

                                        echo '<a title="' . $this->getTextEncode(htmlentities($wishlistResults[$i]['wishlists']['track_title'])) . '">' . $this->getTextEncode(substr($wishlistResults[$i]['wishlists']['track_title'], 0, 15)) . '...</a>';
                                    }
                                    else
                                    {
                                        echo $this->getTextEncode($wishlistResults[$i]['wishlists']['track_title']);
                                    }
                                    ?></a></div>
                            <!-- <a class="add-to-wishlist-button" href="#"></a> -->
                            <div class="album-title"><a title="<?php echo $this->getTextEncode(htmlentities($wishlistResults[$i]['wishlists']['album'])); ?>" href="/artists/view/<?= base64_encode($wishlistResults[$i]['Song']['ArtistText']); ?>/<?= $wishlistResults[$i]['Song']['ReferenceID']; ?>/<?= base64_encode($wishlistResults[$i]['Song']['provider_type']); ?>">
                                    <?php
                                    if (strlen($wishlistResults[$i]['wishlists']['album']) >= 15)
                                    {
                                        echo '<a title="' . $this->getTextEncode(htmlentities($wishlistResults[$i]['wishlists']['album'])) . '">' . $this->getTextEncode(substr($wishlistResults[$i]['wishlists']['album'], 0, 15)) . '...</a>';
                                    }
                                    else
                                    {
                                        echo $this->getTextEncode($wishlistResults[$i]['wishlists']['album']);
                                    }
                                    ?>
                                </a></div>
                            <div class="artist-name"><a title="<?php echo $this->getTextEncode(htmlentities($wishlistResults[$i]['wishlists']['artist'])); ?>" href="/artists/album/<?= base64_encode($wishlistResults[$i]['Song']['ArtistText']); ?>">
                                    <?php
                                    if (strlen($wishlistResults[$i]['wishlists']['artist']) >= 15)
                                    {
                                        echo '<a title="' . $this->getTextEncode(htmlentities($wishlistResults[$i]['wishlists']['artist'])) . '">' . $this->getTextEncode(substr($wishlistResults[$i]['wishlists']['artist'], 0, 15)) . '...</a>';
                                    }
                                    else
                                    {
                                        $ArtistName = $wishlistResults[$i]['wishlists']['artist'];
                                        echo $this->getTextEncode($ArtistName);
                                    }
                                    ?>
                                </a></div>

                            <!-- <div class="wishlist-popover">
                            <?php
                            if ($this->Session->read('library_type') == 2)
                            {
                                echo $this->Queue->getQueuesList($this->Session->read('patron'), $wishlistResults[$i]["Song"]["ProdID"], $wishlistResults[$i]["Song"]["provider_type"], $wishlistResults[$i]["Albums"]["ProdID"], $wishlistResults[$i]["Albums"]["provider_type"]);
                                ?>
                                                                                                                    <a class="add-to-playlist" href="#">Add To Playlist</a>
                            <?php } ?>
                            <?php //echo $this->Queue->getSocialNetworkinglinksMarkup(); ?>            
                            </div> -->
                            <div class="download">

                                <?php
                                $productInfo = $song->getDownloadData($wishlistResults[$i]['wishlists']['ProdID'], $wishlistResults[$i]['wishlists']['provider_type']);
                                if ($libraryDownload == '1' && $patronDownload == '1')
                                {
//                                    $songUrl = shell_exec('perl files/tokengen ' . $productInfo[0]['Full_Files']['CdnPath'] . "/" . $productInfo[0]['Full_Files']['SaveAsName']);
//                                    $finalSongUrl = Configure::read('App.Music_Path') . $songUrl;
//                                    $finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl) / 3));
                                    ?>
                                    <p>
                                        <span class="beforeClick" id="wishlist_song_<?php echo $wishlistResults[$i]['wishlists']['ProdID']; ?>">
                                            <?php
                                            if ($wishlistResults[$i]['Country']['SalesDate'] <= date('Y-m-d') && ($wishlistResults[$i]['Country']['DownloadStatus'] == 1))
                                            {
                                                ?>
                                                <![if !IE]>
                                                <a href='javascript:void(0);' title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick='return wishlistDownloadOthersHome("<?php echo $wishlistResults[$i]['wishlists']['ProdID']; ?>", "<?php echo $wishlistResults[$i]['wishlists']['id']; ?>", "<?php echo $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?php echo $productInfo[0]['Full_Files']['SaveAsName']; ?>", "<?php echo $wishlistResults[$i]['wishlists']["provider_type"]; ?>");'><?php __('Download'); ?></a>
                                                <![endif]>
                                                <!--[if IE]>
                                                        <a title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick='wishlistDownloadIEHome("<?php echo $wishlistResults[$i]['wishlists']['ProdID']; ?>", "<?php echo $wishlistResults[$i]['wishlists']['id']; ?>" , "<?php echo $wishlistResults[$i]['wishlists']["provider_type"]; ?>", "<?php echo $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?php echo $productInfo[0]['Full_Files']['SaveAsName']; ?>");' href="javascript:void(0);"><?php __('Download'); ?></a>
                                                <![endif]-->	
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <![if !IE]>
                                                <?php __('Coming Soon'); ?>
                                                <![endif]>
                                                <!--[if IE]>
                                                <?php __('Coming Soon'); ?>
                                                <![endif]-->							
                                            <?php } ?>
                                        </span>
                                        <span class="afterClick" id="downloading_<?php echo $wishlistResults[$i]['wishlists']['ProdID']; ?>" style="display:none;float:left;"><?php __('Please Wait..'); ?></span>
                                        <span id="wishlist_loader_<?php echo $wishlistResults[$i]['wishlists']['ProdID']; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
                                    </p>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <p><?php __("Limit Met"); ?></p>
                                    <?php
                                }
                                ?>

                            </div>						
                            <div class="delete-btn songdelete"></div>
                        </div>
                        <?php
                    }
                }
                else
                {
                    echo __("You have no songs in your wishlist.");
                }
                ?>
            </div>
        </div>
    </div>
    <!--(this is the html for the videos) -->
    <div class="my-video-wishlist-shadow-container" style="display:none;">
        <div class="my-video-wishlist-scrollable">
            <div class="row-container">
                <?php
                if (count($wishlistResultsVideos) != 0)
                {
                    //$i = 1;
                    foreach ($wishlistResultsVideos as $key => $wishlistResultsVideo):
                        /* $class = null;
                          if ($i++ % 2 == 0) {
                          $class = ' class="altrow"';
                          } */
                        ?>

                        <div class="row clearfix" id="wishlistvideo-<?php echo $wishlistResultsVideo['WishlistVideo']['id'] . '-' . $wishlistResultsVideo['WishlistVideo']['ProdID'] ?>">
                            <div class="date"><?php echo date("Y-m-d", strtotime($wishlistResultsVideo['WishlistVideo']['created'])); ?></div>
                            <div class="small-album-container">
                                <?php
                                $videoImage = shell_exec(Configure::read('App.tokengen_artwork') . $wishlistResultsVideo['File']['CdnPath'] . "/" . $wishlistResultsVideo['File']['SourceURL']);
                                $videoImageUrl = Configure::read('App.Music_Path') . $videoImage;
                                ?>
                                <img src="<?php echo $videoImageUrl; ?>" alt="video-cover" width="67" height="40" />
                                <!-- <a class="preview" href="javascript:void(0)"></a> -->
                            </div>
                            <div class="song-title"><a title="<?php echo $this->getTextEncode($wishlistResultsVideo['WishlistVideo']['track_title']); ?>" href="javascript:void(0)">
                                    <?php
                                    if (strlen($wishlistResultsVideo['WishlistVideo']['track_title']) >= 15)
                                    {
                                        echo '<a title="' . htmlentities($wishlistResultsVideo['WishlistVideo']['track_title']) . '">' . $this->getTextEncode(substr($wishlistResultsVideo['WishlistVideo']['track_title'], 0, 15)) . '...</a>';
                                    }
                                    else
                                    {
                                        echo $this->getTextEncode($wishlistResultsVideo['WishlistVideo']['track_title']);
                                    }
                                    ?>
                                </a></div>
                            <!--<a class="add-to-wishlist-button" href="javascript:void(0)"></a>-->
                            <div class="album-title"><a title="<?php echo $this->getTextEncode(htmlentities($wishlistResultsVideo['Video']['Title'])); ?>" href="javascript:void(0)"><?php echo $this->getTextEncode(substr($wishlistResultsVideo['Video']['Title'], 0, 15)); ?>...</a></div>
                            <div class="artist-name"><a title="<?php echo $this->getTextEncode(htmlentities($wishlistResultsVideo['WishlistVideo']['artist'])); ?>" href="/artists/album/<?= base64_encode($wishlistResultsVideo['Video']['ArtistText']); ?>">
                                    <?php
                                    if (strlen($wishlistResultsVideo['WishlistVideo']['artist']) >= 15)
                                    {
                                        echo '<a title="' . htmlentities($wishlistResultsVideo['WishlistVideo']['artist']) . '">' . $this->getTextEncode(substr($wishlistResultsVideo['WishlistVideo']['artist'], 0, 15)) . '...</a>';
                                    }
                                    else
                                    {
                                        $ArtistName = $this->getTextEncode($wishlistResultsVideo['WishlistVideo']['artist']);
                                        echo $ArtistName;
                                    }
                                    ?></a></div>

                            <!--<div class="wishlist-popover">
                                    
                                    <div class="share clearfix">
                                            <p>Share via</p>
                                            <a class="facebook" href="#"></a>
                                            <a class="twitter" href="#"></a>
                                    </div>
                                    
                            </div>-->
                            <div class="download">
                                <p>
                                    <?php
                                    $productInfo = $mvideo->getDownloadData($wishlistResultsVideo['WishlistVideo']['ProdID'], $wishlistResultsVideo['WishlistVideo']['provider_type']);
                                    $videoUrl = shell_exec('perl files/tokengen ' . $productInfo[0]['Full_Files']['CdnPath'] . "/" . $productInfo[0]['Full_Files']['SaveAsName']);
                                    $finalVideoUrl = Configure::read('App.Music_Path') . $videoUrl;
                                    $finalVideoUrlArr = str_split($finalVideoUrl, ceil(strlen($finalVideoUrl) / 3));
                                    ?>
                                    <span class="beforeClick" id="download_video_<?php echo $wishlistResultsVideo['WishlistVideo']['ProdID']; ?>">
                                        <?php
                                        if ($wishlistResultsVideo['Country']['SalesDate'] <= date('Y-m-d'))
                                        {
                                            ?>
                                            <![if !IE]>
                                            <a title="IMPORTANT: Please note that once you press `Download Now` you have used up two of your downloads, regardless of whether you then press 'Cancel' or not." href='#' onclick='return wishlistVideoDownloadOthers("<?php echo $wishlistResultsVideo['WishlistVideo']['ProdID']; ?>", "<?php echo $wishlistResultsVideo['WishlistVideo']['id']; ?>", "<?php echo urlencode($finalVideoUrlArr[0]); ?>", "<?php echo urlencode($finalVideoUrlArr[1]); ?>", "<?php echo urlencode($finalVideoUrlArr[2]); ?>", "<?php echo $wishlistResultsVideo['WishlistVideo']["provider_type"]; ?>");'><?php __('Download'); ?></a>
                                            <![endif]>
                                            <!--[if IE]>
                                                    <a title="IMPORTANT: Please note that once you press `Download Now` you have used up two of your downloads, regardless of whether you then press 'Cancel' or not." onclick='wishlistVideoDownloadIE("<?php echo $wishlistResultsVideo['WishlistVideo']['ProdID']; ?>","<?php echo $wishlistResultsVideo['WishlistVideo']['id']; ?>","<?php echo $wishlistResultsVideo['WishlistVideo']['provider_type']; ?>");' href="<?php echo trim($finalVideoUrl); ?>"><?php __('Download'); ?></a>
                                            <![endif]-->
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <![if !IE]>
                                            <?php __('Coming Soon'); ?>
                                            <![endif]>
                                            <!--[if IE]>
                                            <?php __('Coming Soon'); ?>
                                            <![endif]-->
                                        <?php } ?>
                                    </span>
                                    <span class="afterClick" id="vdownloading_<?php echo $wishlistResultsVideo['WishlistVideo']['ProdID']; ?>"style="display:none;float:left;"><?php __("Please Wait..."); ?></span>
                                    <span id="vdownload_loader_<?php echo $wishlistResultsVideo['WishlistVideo']['ProdID']; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
                                </p>
                            </div>
                            <div class="delete-btn videodelete"></div>
                        </div>
                        <?php
                    endforeach;
                }
                else
                {
                    echo '<tr><td valign="top"><p>';
                    ?><?php echo __("You have no videos in your wishlist."); ?><?php
                    echo '</p></td></tr>';
                }
                ?>
            </div>
        </div>
    </div>

</section>
