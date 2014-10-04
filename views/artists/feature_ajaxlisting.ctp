
<?php   
if(!empty($featuredArtists)) {
    $count = 1;
    $row = (($page - 1) * 4) + 1;
    $column = 1;
    foreach ($featuredArtists as $k => $v) {
        
        if (strlen($v['Featuredartist']['artist_name']) > 22) {
            $ArtistText = substr($v['Featuredartist']['artist_name'], 0, 22) . "..";
        } else {
            $ArtistText = $v['Featuredartist']['artist_name'];
        }
        ?>
        <div class="featured-grid-item">
            <a onclick="ga('send', 'event', 'Featured Artist and Composers', 'Artwork Click', 'R<?php echo $row; ?>C<?php echo $column; ?>-<?php echo $this->getTextEncode($ArtistText); ?>')" href="/artists/album/<?= base64_encode($this->getTextEncode($v['Featuredartist']['artist_name'])); ?>">
                <?php echo $html->image(Configure::read('App.CDN') . 'featuredimg/' . $v['Featuredartist']['artist_image'], array("height" => "77", "width" => "84", "alt" => $ArtistText)); ?>
            </a>
            <div class="featured-grid-menu">
                <div class="featured-artist-name">
                    <?php echo $this->getTextEncode($ArtistText); ?>

                </div>
                <div class="featured-artist-ctas">
                    <?php
                    if ($this->Session->read("patron")) {
                        if ($this->Session->read('library_type') == 2 && !empty($v['albumSongs'])) {
                            echo $this->Queue->getAlbumStreamNowLabel($v['albumSongs'], 3, 'R' . $row . 'C' . $column . '-' . $this->getTextEncode($ArtistText));
                        }
                    }
                    ?>                     
                    <a onclick="ga('send', 'event', 'Featured Artist and Composers', 'More By', 'R<?php echo $row; ?>C<?php echo $column; ?>-<?php echo $this->getTextEncode($ArtistText); ?>')" title="More by <?php echo $this->getTextEncode($ArtistText); ?>" class="more-by-artist" 
                       href="/artists/album/<?= base64_encode($this->getTextEncode($v['Featuredartist']['artist_name'])); ?>">
                           <?php echo $this->getTextEncode($ArtistText); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
        if ($column == 5) {
            $column = 0;
            $row++;
        }
        $column++;
        if ($count == 20) {
            break;
        }
        $count++;
    }
}
?>
