<section class="saved-queues-page">
    <div class="breadcrumbs">               
        <?php
        echo $html->link('Home', array('controller' => 'homes', 'action' => 'index'));
        echo " > ";
        echo $html->link('Playlists', array('controller' => 'queues', 'action' => 'savedQueuesList'));
        ?>
    </div>
    <header class="clearfix">
        <h2>Saved Playlists</h2>
        <div class="create-new-queue-btn no-ajaxy"></div>
        <div class="faq-link">Need help? Visit our <a href="/questions">FAQ section.</a></div>
    </header>
    <?php
    echo $session->flash();
    ?>
    <?php
    if (!empty($queueData))
    {
        ?>
        <div class="playlists-shadow-container">
            <div class="playlists-scrollable">
                <div class="playlists-container">
                    <div class="row clearfix">
                        <?php
                        $i = 0;
                        foreach ($queueData as $key => $value)
                        {
                            ?>
                            <?php
                            if ($i % 3 == 0)
                            {
                                ?>
                            </div>
                            <div class="row clearfix">
                            <?php } ?>
                            <div class="item">
                                <div class="playlist-info-container">
                                    <?php
                                    if (!empty($value['QueueList']['queue_name']))
                                    {
                                        ?>
                                        <div class="playlist-title">
                                            <a href="/queuelistdetails/queue_details/<?php echo $value['QueueList']['queue_id']; ?>/0/<?php echo base64_encode($value['QueueList']['queue_name']); ?>">
                                            <?php echo $value['QueueList']['queue_name']; ?></a>
                                        </div>
                                        <a href="/queuelistdetails/queue_details/<?php echo $value['QueueList']['queue_id']; ?>/0/<?php echo base64_encode($value['QueueList']['queue_name']); ?>">
                                            <img src="/img/my-playlists/album-cover.jpg" alt="album-cover" width="85" height="85" />
                                        </a>
                                    <?php } ?>
                                    <?php
                                    
                                        ?>
                                        <div class="playlist-length"><?php echo $this->Queue->getQueueListCountUnique($value['QueueDetail']);  ?> Songs</div>
                                    <?php  ?>
                                </div>
                            </div>
                            <?php
                            $i++;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else
    {
        ?>
        <h2><?php echo __('There are currently no playlists created. Please add a new playlist to start enjoying streaming.'); ?></h2>
        <?php } ?>
</section>