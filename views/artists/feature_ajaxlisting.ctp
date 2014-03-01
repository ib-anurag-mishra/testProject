<div class="featured-artists-grid">
        <?php    
        $count = 1;
        foreach ($featuredArtists as $k => $v)
        {
            //$albumArtwork = shell_exec('perl files/tokengen ' . $v['Files']['CdnPath']."/".$v['Files']['SourceURL']);
            //$image =  Configure::read('App.Music_Path').$albumArtwork;
            if (strlen($v['Album']['AlbumTitle']) > 22)
            {
                $title = substr($v['Album']['AlbumTitle'], 0, 22) . "..";
            }
            else
            {
                $title = $v['Album']['AlbumTitle'];
            }

            if (strlen($v['Album']['ArtistText']) > 22)
            {
                $ArtistText = substr($v['Album']['ArtistText'], 0, 22) . "..";
            }
            else
            {
                $ArtistText = $v['Album']['ArtistText'];
            }
            ?>
            <div class="featured-grid-item">
                <a href="/artists/view/<?= base64_encode($v['Album']['ArtistText']); ?>/<?= $v['Album']['ProdID']; ?>/<?= base64_encode($v['Album']['provider_type']); ?>">
                    <?php echo $html->image($v['featuredImage'], array("height" => "77", "width" => "84", "alt" => $ArtistText . ' - ' . $v['Album']['AlbumTitle'])); ?>
                </a>
                <div class="featured-grid-menu">
                    <div class="featured-artist-name">
                        <?php echo $this->getTextEncode($ArtistText); ?>
                    </div>
                    <div class="featured-album-name">
                        <a title="<?php echo $this->getValidText($this->getTextEncode($v['Album']['AlbumTitle'])); ?>" 
                           href="/artists/view/<?= base64_encode($v['Album']['ArtistText']); ?>/<?= $v['Album']['ProdID']; ?>/<?= base64_encode($v['Album']['provider_type']); ?>">
                               <?php echo $this->getTextEncode($title); ?>
                        </a>
                    </div>
                    <div class="featured-artist-ctas">
                        <?php
                        if ($this->Session->read("patron"))
                        {
                            if ($this->Session->read('library_type') == 2 && !empty($v['albumSongs'][$v['Album']['ProdID']]))
                            {
                                echo $this->Queue->getAlbumStreamNowLabel($v['albumSongs'][$v['Album']['ProdID']], 2);
                            }
                        }
                        ?>                     
                        <a title="<?php echo $this->getValidText($this->getTextEncode($v['Album']['ArtistText'])); ?>" class="more-by-artist" 
                           href="/artists/album/<?php echo str_replace('/', '@', base64_encode($v['Album']['ArtistText'])); ?>/<?= base64_encode($v['Genre']['Genre']) ?>">
                               <?php echo $this->getTextEncode($ArtistText); ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php
            if ($count == 20)
            {
                break;
            }
            $count++;
        }
        ?>
    </div>