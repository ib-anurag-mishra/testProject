<section class="videos">

    <section class="featured-videos">
        <header class="clearfix">
            <h3><?php echo __('Featured Videos', true); ?></h3>
        </header>      

        <section id="featured-video-grid" class="horiz-scroll">
            <ul class="clearfix">
                <?php
                $total_videos = count($featuredVideos);
                $sr_no = 0;

                if ($total_videos > 0)
                {
                    foreach ($featuredVideos as $key => $featureVideo)
                    {
                        //hide song if library block the explicit content
                        if (($this->Session->read('block') == 'yes') && isset($featureVideo["FeaturedVideo"]['Advisory']) && ($featureVideo["FeaturedVideo"]['Advisory'] == 'T'))
                        {
                            continue;
                        }
                        ?>
                        <li>
                            <div class="featured-video-detail">
                                <div class="video-thumbnail-container">

                                </div>

                                <div class="video-title">
                                    <a title="<?php echo $this->getValidText($this->getTextEncode($featureVideo['Video']['VideoTitle'])); ?>" 
                                       href="/videos/details/<?php echo $featureVideo["FeaturedVideo"]["ProdID"]; ?>">
                                           <?php
                                           if (strlen($featureVideo['Video']['VideoTitle']) >= 20)
                                           {
                                               $featureVideo['Video']['VideoTitle'] = substr($featureVideo['Video']['VideoTitle'], 0, 20) . '...';
                                           }
                                           ?>
                                           <?php echo $this->getTextEncode($featureVideo['Video']['VideoTitle']); ?>
                                    </a> 
                                    <?php
                                    if (isset($featureVideo['Video']['Advisory']) && 'T' == $featureVideo['Video']['Advisory'])
                                    {
                                        ?> 
                                        <span style="color: red;display: inline;"> (Explicit)</span> 
                                        <?php
                                    }
                                    ?>

                                </div>

                                <div class="video-name">
                                    <?php
                                    if (strlen($featureVideo['Video']['ArtistText']) >= 20)
                                    {
                                        $featureVideo['Video']['ArtistText'] = substr($featureVideo['Video']['ArtistText'], 0, 20) . '...';
                                    }
                                    ?>
                                    <a title="<?php echo $this->getValidText($this->getTextEncode($featureVideo['Video']['ArtistText'])); ?>" 
                                       href="/artists/album/<?php echo base64_encode($featureVideo['Video']['ArtistText']); ?>">
                                           <?php echo $this->getTextEncode($featureVideo['Video']['ArtistText']); ?>
                                    </a>
                                </div>
                            </div>                            
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
        </section>
    </section><!-- end .featured-videos -->








    <section class="video-top-genres">
        <header class="clearfix">
            <h3><?php echo __('Top Videos', true); ?></h3>
        </header>
    </section> <!-- end .video-top-genres -->

</section>