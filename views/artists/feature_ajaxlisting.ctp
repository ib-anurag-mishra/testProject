
<?php   
if(!empty($featuredArtists))
{
    $count = 1;
    foreach ($featuredArtists as $k => $v)
    {
        if (strlen($v['Featuredartist']['artist_name']) > 22)
        {
            $ArtistText = substr($v['Featuredartist']['artist_name'], 0, 22) . "..";
        }
        else
        {
            $ArtistText = $v['Featuredartist']['artist_name'];
        }
        ?>
        <div class="featured-grid-item">
            <a href="/artists/album/<?= base64_encode($this->getTextEncode($v['Featuredartist']['artist_name'])); ?>">
                <?php echo $html->image(Configure::read('App.CDN') . 'featuredimg/' . $v['Featuredartist']['artist_image'], array("height" => "77", "width" => "84", "alt" => $ArtistText)); ?>
            </a>
            <div class="featured-grid-menu">
                <div class="featured-artist-name">
                    <?php echo $this->getTextEncode($ArtistText); ?>

                </div>
                <div class="featured-artist-ctas">
                    <?php
                    if ($this->Session->read("patron"))
                    {
                        if ($this->Session->read('library_type') == 2 && !empty($v['albumSongs']))
                        {
                            echo $this->Queue->getAlbumStreamNowLabel($v['albumSongs'],3);
                        }
                    }
                    ?>                     
                    <a title="More by <?php echo $this->getTextEncode($ArtistText); ?>" class="more-by-artist" 
                       href="/artists/album/<?= base64_encode($this->getTextEncode($v['Featuredartist']['artist_name'])); ?>">
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
}
?>
