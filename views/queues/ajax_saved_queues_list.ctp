<?php
    echo $session->flash();
    ?>
    <?php
    if (!empty($queueData))
    {
        ?>      
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
                                    if (!empty($value['QueueDetail']))
                                    {
                                        ?>
                                        <div class="playlist-length"><?php echo count($value['QueueDetail']); ?> Songs</div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php
                            $i++;
                        }
                        ?>
                    </div>
                </div>           
        <?php
    }
    else
    {
        ?>
        <h2>There are no Playlists which has been saved till now </h2>
        <?php } ?>