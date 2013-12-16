<ul style="width:27100px;">
    <?php
    //$this->log("index.ctp National Top 100 album start", "siteSpeed"); 
    $count = 1;
    if (count($nationalTopAlbumsDownload) > 0)
    {
        foreach ($nationalTopAlbumsDownload as $key => $value)
        {
            //hide song if library block the explicit content
            if (($this->Session->read('block') == 'yes') && ($value['Albums']['Advisory'] == 'T'))
            {
                continue;
            }
            ?>					
            <li>
                <div class="album-container">							
                    <?php
                    
                    $lazyClass = '';
                    $srcImg = $value['songAlbumImage'];
                    $dataoriginal = '';

                    echo $html->link($html->image($srcImg, array("height" => "250", "width" => "250", "class" => $lazyClass, "data-original" => $dataoriginal)), array('controller' => 'artists', 'action' => 'view', base64_encode($value['Song']['ArtistText']), $value['Song']['ReferenceID'], base64_encode($value['Song']['provider_type'])), array('class' => 'first', 'escape' => false))
                    ?>
                    <div class="top-100-ranking"><?php echo $count; ?></div>
                    <?php
                    if ($this->Session->read("patron"))
                    {
                        if ($this->Session->read('library_type') == 2 && !empty($value['albumSongs'][$value['Albums']['ProdID']]))
                        {
                            echo $this->Queue->getAlbumStreamNowLabel($value['albumSongs'][$value['Albums']['ProdID']]);
                            ?> 
                            <a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)" ></a>
                            <?php
                        }
                        ?>
                        <div class="wishlist-popover">
                            <input type="hidden" id="<?= $value['Albums']['ProdID'] ?>" value="album"/>
                            <?php
                            if ($this->Session->read('library_type') == 2 && !empty($value['albumSongs'][$value['Albums']['ProdID']]))
                            {

                                // echo $this->Queue->getQueuesListAlbums($this->Session->read('patron'), $value['albumSongs'][$value['Albums']['ProdID']], $value['Albums']['ProdID'], $value['Albums']['provider_type']);
                                ?>
                                <a class="add-to-playlist" href="javascript:void(0)">Add To Playlist</a>
                                <?php
                            }
                            ?>

                            <?php //echo $this->Queue->getSocialNetworkinglinksMarkup(); ?>
                        </div>
                        <?php
                    }
                    else
                    {
                        ?>
                        <a class="top-100-download-now-button " href='/users/redirection_manager'> <?php __("Login"); ?></a> 
                        <?php
                    }
                    ?>
                </div>
                <div class="album-title">							
                    <a title="<?php echo $this->getValidText($this->getTextEncode($value['Albums']['AlbumTitle'])); ?>" href="/artists/view/<?= base64_encode($value['Song']['ArtistText']); ?>/<?= $value['Song']['ReferenceID']; ?>/<?= base64_encode($value['Song']['provider_type']); ?>">
                        <?php
                        //echo "<br>Sales Date: ".Country.$value['Country']['SalesDate']."</br>";
                        if (strlen($value['Albums']['AlbumTitle']) > 20)
                            echo $this->getValidText($this->getTextEncode(substr($value['Albums']['AlbumTitle'], 0, 20))) . "...";
                        else
                            echo $value['Albums']['AlbumTitle'];
                        ?>
                    </a><?php
                    if ('T' == $value['Albums']['Advisory'])
                    {
                        ?> <span style="color: red;display: inline;"> (Explicit)</span> <?php } ?>
                </div>
                <div class="artist-name">							
                    <a title="<?php echo $this->getValidText($this->getTextEncode($value['Song']['Artist'])); ?>" href="/artists/album/<?php echo str_replace('/', '@', base64_encode($value['Song']['ArtistText'])); ?>/<?= base64_encode($value['Song']['Genre']) ?>">
                        <?php
                        if (strlen($value['Song']['Artist']) > 32)
                            echo $this->getValidText($this->getTextEncode(substr($value['Song']['Artist'], 0, 32))) . "...";
                        else
                            echo $this->getValidText($this->getTextEncode($value['Song']['Artist']));
                        ?>
                    </a>
                </div>
            </li>
            <?php
            $count++;
        }
    }
    else
    {

        echo '<span style="font-size:14px;">Sorry,there are no downloads.<span>';
    }
// $this->log("index.ctp National Top 100 album end", "siteSpeed"); 
    ?>
</ul>  