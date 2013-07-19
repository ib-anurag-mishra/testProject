<section class="saved-queues-page">
		<div class="breadcrumbs">               
                 <?php
                echo $html->link('Home', array('controller' => 'homes', 'action' => 'index'));
                echo " > "; 
                echo $html->link('Queues', array('controller'=>'queues', 'action'=>'savedQueuesList'));
                ?>
                </div>
		<header class="clearfix">
			<h2>Saved Queues</h2>
			<div class="create-new-queue-btn"></div>
			<div class="faq-link">Need help? Visit our <a href="/questions">FAQ section.</a></div>
		</header>
                <?php
                echo $session->flash();
                ?>
                <?php if(!empty($queueData)){ ?>
		<div class="playlists-shadow-container">
			<div class="playlists-scrollable">
				<div class="playlists-container">
                                    <div class="row clearfix">
                                    <?php $i = 0;
                                          foreach($queueData as $key => $value){
                                                   ?>
                                                <?php if($i%3 == 0){ ?>
                                                       </div>
                                                       <div class="row clearfix">
                                                <?php } ?>
                                                <div class="item">
                                                <div class="playlist-info-container">
                                                        <?php if(!empty($value['QueueList']['queue_name'])){ ?>
                                                        <div class="playlist-title"><a href="/queuelistdetails/queue_details/<?php echo $value['Queuelist']['queue_id'];?>"><?php echo $value['Queuelist']['queue_name']; ?></a></div>
                                                        <a href="#"><img src="/img/my-playlists/album-cover.jpg" alt="album-cover" width="85" height="85" /></a>
                                                        <?php } ?>
                                                        <?php if(!empty($value['QueueDetail'])){ ?>
                                                        <div class="playlist-length"><?php echo count($value['QueueDetail']); ?> Songs</div>
                                                        <?php } ?>
                                                 </div>
                                                 </div>
                                    <?php $i++;} ?>
                                    </div>
                                </div>
                        </div>
                 </div>
                 <?php }else{ ?>
                 <h2>There are no Queues which has been saved till now </h1>
                 <?php } ?>
</section>