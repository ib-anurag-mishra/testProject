<script type="text/javascript" src="/js/ajaxify-html5.js"></script>
<?php
    foreach ($albumData as $album_key => $album):
    //hide album if library block the explicit content
    if (($this->Session->read('block') == 'yes') && ($album['Album']['Advisory'] == 'T'))
    {
            continue;
    }
    ?>
    <li>
            <div class="album-container">
                    <?php                                         
                    $albumArtwork = $this->Token->artworkToken($album['Files']['CdnPath'] . "/" . $album['Files']['SourceURL']);
                    ?>
                    <a
                            href="/artists/view/<?php echo str_replace('/', '@', base64_encode($artisttext)); ?>/<?php echo $album['Album']['ProdID']; ?>/<?php echo base64_encode($album['Album']['provider_type']); ?>">
                            <img
                            src="<?php echo Configure::read('App.Music_Path') . $albumArtwork; ?>"
                            width="162" height="162" alt="">
                    </a>

                    <?php
                    if ($this->Session->read('library_type') == 2 && !empty($album['albumSongs'][$album['Album']['ProdID']]) && $this->Session->read("patron"))
                    {
                            echo $this->Queue->getAlbumStreamLabel($album['albumSongs'][$album['Album']['ProdID']]);
                            ?>
                    <a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)"></a>
                    <div class="wishlist-popover">
                            <input type="hidden" id="<?= $album['Album']['ProdID'] ?>"
                                    value="album" /> <a class="add-to-playlist"
                                    href="javascript:void(0)"><?php __('Add To Playlist'); ?></a>
                    </div>
                    <?php
                    }
                    ?>
                    <a
                            href="/artists/view/<?php echo str_replace('/', '@', base64_encode($artisttext)); ?>/<?php echo $album['Album']['ProdID']; ?>/<?php echo base64_encode($album['Album']['provider_type']); ?>">
                            <?php
                            $image = Configure::read('App.Music_Path') . $albumArtwork;
                            ?>

                    </a>
            </div>
            <div class="album-title">
                    <a
                            title="<?php echo $this->getTextEncode($album['Album']['AlbumTitle']); ?>"
                            href="/artists/view/<?php echo str_replace('/', '@', base64_encode($album['Album']['ArtistText'])); ?>/<?php echo $album['Album']['ProdID']; ?>/<?php echo base64_encode($album['Album']['provider_type']); ?>">

                            <b> <?php
                            if (strlen($album['Album']['AlbumTitle']) >= 50)
                            {
                                    $album['Album']['AlbumTitle'] = substr($album['Album']['AlbumTitle'], 0, 50) . '...';
                            }
                            ?> <?php echo $this->getTextEncode($album['Album']['AlbumTitle']); ?>
                    </b>
                    </a>
            </div>
            <div class="genre">
                    <?php
                    echo __('Genre') . ": " . $html->link($this->getTextEncode($album['Genre']['Genre']), array('controller' => 'genres', 'action' => 'view', base64_encode($album['Genre']['Genre'])), array("title" => $this->getTextEncode($album['Genre']['Genre']))) . '<br />';
                    if ($album['Album']['ArtistURL'] != '')
                    {
                            echo $ArtistURL = $html->link('http://' . $album['Album']['ArtistURL'], 'http://' . $album['Album']['ArtistURL'], array('target' => 'blank', 'style' => 'word-wrap:break-word;word-break:break-word;width:160px;'));
                            echo '<br />';
                    }
                    if ($album['Album']['Advisory'] == 'T')
                    {
                            echo '<span class="explicit"> (' . __('Explicit', true) . ')</span>';
                            echo '<br />';
                    }
                    ?>
            </div>
            <div class="label">
                    <?php
                    if ($album['Album']['Label'] != '')
                    {
                            echo __("Label") . ': ' . $this->getTextEncode($album['Album']['Label']);
                            echo '<br />';
                    }
                    if ($album['Album']['Copyright'] != '' && $album['Album']['Copyright'] != 'Unknown')
                    {
                            echo $this->getTextEncode($album['Album']['Copyright']);
                    }
                    ?>
            </div>
    </li>
    <?php
    endforeach;
    $totalPages = $this->Paginator->params['paging']['Album']['pageCount'];
    $artistText = base64_encode($artisttext);
    if($current_page+1 <= $totalPages) {
        ?>
       <input type="hidden" class="artist_text" value="<?php echo $artistText; ?>" />
       <input type="hidden" class="next_page" value="<?php echo $current_page+1; ?>" />
<?php } ?>
