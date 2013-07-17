<?php
/**
	File Name : navigation.php
	File Description : View page for navigation file for front-end site
	Author : m68interactive
**/

/**
 * Navigation file for front-end site
 **/
if($this->Session->read('library') && $this->Session->read('library') != '')
{
	$libraryInfo = $library->getLibraryDetails($this->Session->read('library'));
            
        $isLibaryExistInTimzone =  $this->Session->read('isLibaryExistInTimzone');
	$downloadCount = $download->getDownloadDetails($this->Session->read('library'),$this->Session->read('patron'));
	if($libraryInfo['Library']['library_unlimited'] != "1" && $libraryInfo['Library']['library_authentication_method'] == "user_account"){
		$width = 125;
	}elseif($libraryInfo['Library']['library_unlimited'] == "1" && $libraryInfo['Library']['library_authentication_method'] == "user_account"){
		$width = 140;
	}elseif($libraryInfo['Library']['library_unlimited'] != "1" && $libraryInfo['Library']['library_authentication_method'] != "user_account"){
		$width = 140;
	}else{
		$width = 166;
	}
}
    
?>
    <div class="queue-overlay">
            <div class="rename-queue-dialog-box">
                    <div class="close"></div>
                    <header>Rename '<span>Queue Name</span>'</header>
                    <form action="" method="post">
                    <div class="rename-form-container">

                                    <label for="name">Name:</label>
                                    <input type="text" name="name" id="name" />
                                    <label for="description">Description:</label>
                                    <textarea name="description" id="description"></textarea>


                    </div>
                    <div class="buttons-container clearfix">
                            <div class="text-close">Close</div>
                            <input type="submit" class="save" value="Save Changes"></input>
                    </div>
                    </form>
            </div>
            <div class="create-queue-dialog-box">
                    <div class="close"></div>
                    <header>Create Queue</header>
                    <form action="/queues/createQueue" method="post">
                    <div class="rename-form-container">

                                    <label for="name">Name:</label>
                                    <?php echo $this->Form->input('Queuelist.PlaylistName', array('label' => false, 'div' => false, 'class' => 'form_fields') ); ?>
                                    <label for="description">Description:</label>
                                    <?php echo $this->Form->input('Queuelist.description', array('label' => false, 'div' => false, 'class' => 'form_fields') ); ?>


                    </div>
                    <div class="buttons-container clearfix">
                            <div class="text-close">Close</div>
                            <input type="submit" class="save" value="Create New Queue"></input>
                    </div>
            </div>
            <div class="delete-queue-dialog-box">
                    <div class="close"></div>
                    <header>Delete Queue?</header>
                    <form action="" method="post">
                    <div class="confirm-text">


                            <p>Are you sure you want to delete '<span>Queue Name</span>'?</p>

                    </div>
                    <div class="buttons-container clearfix">
                            <div class="text-close">Close</div>
                            <input type="submit" class="save" value="Delete Queue"></input>
                    </div>
                    </form>
            </div>

    </div>
    <div class="wrapper">
			<!-- site header -->
			<header class="site-header">                                    
                                    <h1 class="logo"><a href="/homes/index"><img src="<? echo $this->webroot; ?>app/webroot/img/logo.png" alt="logo" width="157" height="108"></a></h1>
					<div class="master-music-search-wrapper">
						<form class="search" name="search" id="HomeSearchForm" method="get" action="/search/index" accept-charset="utf-8">							
							<input type="text" id="search-text" name="q" value="<?php echo $keyword; ?>" />							
                            <input type="hidden" name="type" value="all" />
						</form>
						<button type="submit" onclick="document.getElementById('HomeSearchForm').submit()"><img src="<? echo $this->webroot; ?>app/webroot/img/magnifying-glass.png" alt="magnifying-glass" width="17" height="18"></button>
                                                <?php echo $html->link(__('Browse A-Z', true), array('controller' => 'genres', 'action' =>'view')); ?>
					</div>
					<div class="master-music-search-results">
						<ul>
							<li>
								<div class="master-search-results-image">
									<img src="<? echo $this->webroot; ?>app/webroot/img/master_music_search_results/adele.jpg" alt="adele">
								</div>
								<div class="master-search-results-detail">
									<p class="song-album-info"><span class="album-title"><a href="#">21</a></span><span class="song-title"><a href="#"></a></span></p>
									<p class="artist"><a href="#">Adele</a></p>
								</div>
							</li>
							<li>
								<div class="master-search-results-image">
									<img src="<? echo $this->webroot; ?>app/webroot/img/master_music_search_results/pitbull.jpg" alt="pitbull">
								</div>
								<div class="master-search-results-detail">
									<p class="song-album-info"><span class="album-title"><a href="#"></a></span><span class="song-title"><a href="#">Mr. Worldwide</a></span></p>
									<p class="artist"><a href="#">Pitbull</a></p>
								</div>
							</li>
							<li>
								<div class="master-search-results-image">
									<img src="<? echo $this->webroot; ?>app/webroot/img/master_music_search_results/carrie-underwood.jpg" alt="carrie-underwood">
								</div>
								<div class="master-search-results-detail">
									<p class="song-album-info"><span class="album-title"><a href="#"></a></span><span class="song-title"><a href="#">Before He Cheats</a></span></p>
									<p class="artist"><a href="#">Carrie Underwood</a></p>
								</div>
							</li>
							<li>
								<div class="master-search-results-image">
									<img src="<? echo $this->webroot; ?>app/webroot/img/master_music_search_results/kelly-clarkson.jpg" alt="kelly-clarkson">
								</div>
								<div class="master-search-results-detail">
									<p class="song-album-info"><span class="album-title"><a href="#"></a></span><span class="song-title"><a href="#">All I Ever Wanted</a></span></p>
									<p class="artist"><a href="#">Kelly Clarkson</a></p>
								</div>
							</li>
							<li>
								<div class="master-search-results-image">
									<img src="<? echo $this->webroot; ?>app/webroot/img/master_music_search_results/michael-jackson.jpg" alt="michael-jackson">
								</div>
								<div class="master-search-results-detail">
									<p class="song-album-info"><span class="album-title"><a href="#">Thriller</a></span><span class="song-title"><a href="#"></a></span></p>
									<p class="artist"><a href="#">Michael Jackson</a></p>
								</div>
							</li>
						</ul>
					</div>
					
				<?php if($this->Session->read("patron")){ ?>
				<div class="weekly-downloads-container clearfix">
					<div class="label">
						<p>My Account</p>
					</div>
                                        <a class="select-arrow" href="#"></a>
					<div class="small-divider"></div>
					<div class="tooltip">
						<a href="#"><img src="<? echo $this->webroot; ?>app/webroot/img/note-icon.png" alt="tooltip_play_btn" width="17" height="17"></a>						
					</div>
                                        <div class="account-options-menu">                                            
                                            <?php 
                                                if($libraryInfo['Library']['library_authentication_method'] == "user_account")
                                                {  
                                                    echo "<div>".$html->link(__('Change Password', true), array('controller' => 'users', 'action' => 'my_account'))."</div>";                                                
                                                } 
                                                if($isLibaryExistInTimzone ==1)
                                                { 
                                                    echo "<div>".$html->link(__('Notifications', true), array('controller' => 'users', 'action' => 'manage_notification'))."</div>";
                                                }
                                            ?>
                                            <div><?php echo $html->link(__('Logout', true), array('controller' => 'users', 'action' =>'logout'));?></div>
                                        </div>
					<div class="play-count"><span id='downloads_used'><?php echo $downloadCount; ?></span>/<?php echo $libraryInfo['Library']['library_user_download_limit']; ?></div>                                          
				</div>

				<div class="plays-tooltip">
					<div class="tooltip-content">
						<p>The download usage counter is located in the upper right corner of freegalmusic.com displaying your weekly allotment. For instance, 1/3 means that you have a weekly limit of 3 downloads, and you have used 1 of those downloads. The download counter resets each week at Monday 12:01 AM (Eastern Time, USA).</p>
					</div>
				</div>
                                <?php  }else{ ?>
                               <div class="weekly-downloads-container clearfix">
                                   <div class="label">
                                       <?php 
                                            $library = substr($_SERVER['HTTP_HOST'],0,strpos($_SERVER['HTTP_HOST'],'.'));
                                            if($library != 'www' && $library != 'freading' && $library != '50'){
                                                echo $html->link(__('Login', true), array('controller' => 'users', 'action' => 'redirection_manager'),array('class' => 'btn'));
                                            } else {
                                                echo $html->link(__('Login', true), array('controller' => 'homes', 'action' => 'chooser'),array('class' => 'btn'));
                                            }
                                       ?>
                                       
                                   </div>
                                    
					<div class="small-divider"></div>
					<div class="tooltip">
						<a href="#"><img src="<? echo $this->webroot; ?>app/webroot/img/note-icon.png" alt="tooltip_play_btn" width="17" height="17"></a>						
					</div>
                                        <div class="account-options-menu">                                            
                                            
                                            <div><?php echo $html->link(__('Logout', true), array('controller' => 'users', 'action' =>'logout'));?></div>
                                        </div>
					<div class="play-count"><span id='downloads_used'>0</div>     
                               </div>
                                <?php  } ?>
                                    
                                    
			</header>
                        
                       
			<!-- site nav -->
		<nav class="site-nav">
                    <?php
                    $newsCss = "regular";
                    $videoCss = "regular";
                    $mostPopularCss = "regular";
                    $genreCss = "regular";
                    $faqCss = "regular";

                    if($_SERVER['REQUEST_URI'] == '/homes/index' || $_SERVER['REQUEST_URI'] == '/index'  || $_SERVER['REQUEST_URI'] == '/')
                    {
                        $newsCss = "regular active";
                    }
                    else if($_SERVER['REQUEST_URI'] == '/videos')
                    {
                        $videoCss = "regular active";
                    }
                    else if($_SERVER['REQUEST_URI'] == '/homes/my_lib_top_10' || $_SERVER['REQUEST_URI'] == '/homes/us_top_10')
                    {
                        $mostPopularCss = "regular active";
                    }
                    else if($_SERVER['REQUEST_URI'] == 'genres/view')
                    {
                        $genreCss = "regular active";
                    }
                     else if($_SERVER['REQUEST_URI'] == 'homes/new_releases')
                    {
                        $newReleaseCss = "regular active";
                    }
                    else if($_SERVER['REQUEST_URI'] == '/questions')
                    {
                        $faqCss = "regular active";
                    }
                    ?>
                    <ul class="clearfix">
			<li class="regular"><?php echo $html->link(__('News', true), array('controller' => 'homes','action'=>'index'), array("class"=>$newsCss));?></li>			
                        <li class="regular"><?php echo $html->link(__('Music Videos', true), array('controller' => 'videos', 'action' =>'index'), array("class"=>$videoCss)); ?></li></li>
                        <li class="most-popular"><a href="#" class="<?php echo $mostPopularCss; ?>">Most Popular</a></li>
                        <li class="regular"><?php echo $html->link(__('New Releases', true), array('controller' => 'homes', 'action' =>'new_releases'), array("class"=>$newReleaseCss)); ?></li></li> 
                        <li class="regular"><?php echo $html->link(__('Genres', true), array('controller' => 'genres', 'action' =>'view'), array("class"=>$genreCss)); ?></li></li>   
                        <li class="regular"><?php echo $html->link(__('FAQ', true), array('controller' => 'questions', 'action' =>'index'), array("class"=>$faqCss)); ?></li>
                    </ul>
                    
                    <div class="most-popular-sub-nav">
                            <?php if($this->Session->read("patron")){ ?>
                            <div><?php echo $html->link(__('My Lib Top 10', true), array('controller' => 'homes', 'action' =>'my_lib_top_10')); ?></div>
                            <?php } ?>
                            <div><?php echo $html->link(__('US Top 10', true), array('controller' => 'homes', 'action' =>'us_top_10')); ?></div>
                    </div>                   

			</nav>
			<div class="gradient-border"></div>
                        
			<div class="top-sub-nav">
				
			</div>
                        
                        <?php
                        
                                 $music_videos_css = "sidebar-anchor";
                                 $my_lib_css = "sidebar-anchor";
                                 $us_top_css= "sidebar-anchor";
                                 $download_css = "sidebar-anchor";
                                 $wishlist_css = "sidebar-anchor";
                                 $new_releases_css = "sidebar-anchor";
                                 $ul_class = "sidebar-sub-nav";
                                 
                                 //echo $_SERVER['REQUEST_URI'];

                                if($_SERVER['REQUEST_URI'] == '/videos')
                                {
                                    $music_videos_css = "sidebar-anchor active";
                                }
                                else if($_SERVER['REQUEST_URI'] == '/homes/my_lib_top_10')
                                {
                                    $my_lib_css = "sidebar-anchor active";
                                    $ul_class = "sidebar-sub-nav active";
                                }
                                else if($_SERVER['REQUEST_URI'] == '/homes/us_top_10')
                                {
                                    $us_top_css = "sidebar-anchor active";
                                    $ul_class = "sidebar-sub-nav active";
                                }
                                else if($_SERVER['REQUEST_URI'] == '/homes/my_history')
                                {
                                    $download_css ="sidebar-anchor active";
                                }
                                else if($_SERVER['REQUEST_URI'] == '/homes/my_wishlist')
                                {
                                    $wishlist_css = "sidebar-anchor active";
                                }
                                else if($_SERVER['REQUEST_URI'] == '/homes/new_releases')
                                {
                                    $new_releases_css = "sidebar-anchor active";
                                }
                                
                        
                        
                        ?>
                        
			<div class="content-wrapper clearfix">	
                            
					<section class="left-sidebar">
                                            <ul class="browse sidebar-nav"><h3>Browse</h3>
                                                    <li>
                                                            <?php echo $html->link(__('Music Videos', true), array('controller' => 'videos', 'action' => 'index'),array('class'=>$music_videos_css)); ?>
                                                    </li>                                                    
                                                    <li>
                                                            <a href="#" class="sidebar-anchor">Most Popular</a>
                                                            <ul class="<?php echo $ul_class; ?>">
                                                                    <?php if($this->Session->read("patron")){ ?>
                                                                    <li><?php echo $html->link(__('My Lib Top 10', true), array('controller' => 'homes', 'action' =>'my_lib_top_10'),array('class'=>$my_lib_css)); ?></li>
                                                                    <?php } ?>
                                                                    <li><?php echo $html->link(__('US Top 10', true), array('controller' => 'homes', 'action' =>'us_top_10'),array('class'=>$us_top_css)); ?></li>
                                                            </ul>
                                                    </li>  
                                                    <li>
                                                            <?php echo $html->link(__('New Releases', true), array('controller' => 'homes', 'action' => 'new_releases'),array('class'=>$new_releases_css)); ?>
                                                    </li> 
                                            </ul>
                                            <?php if($this->Session->read("patron")){ ?>
                                            <?php if($this->Session->read('library_type') == '2') {
                                                $defaultQueues = $this->requestAction(array('controller' => 'queues', 'action' => 'getDefaultQueues'));
                                            ?>
                                            <ul class="streaming sidebar-nav"><h3>Streaming</h3>								
                                                    <?php if(!empty($defaultQueues)){ ?>
                                                    
                                                    <li>
                                                            <a href="#" class="sidebar-anchor">Freegal Playlists</a>
                                                            <ul class="sidebar-sub-nav">
                                                                <?php foreach($defaultQueues as $key => $value){?>
                                                                    <li><a href="/queuelistdetails/index/<?php echo $value['Queuelist']['Plid'];?>"><?php echo $value['Queuelist']['PlaylistName']; ?></a></li>
                                                                <?php } ?>    
                                                            </ul>
                                                    </li>
                                                    <?php } ?>
                                                    <li>
                                                            <a href="#" class="sidebar-anchor saved-queue">My Queues</a>
                                                            <ul class="sidebar-sub-nav">
                                                                    <li><a href="now-streaming.php">Now Streaming</a></li>
                                                                    <li><a href="/queues/savedQueuesList/<?php echo $this->Session->read("patron"); ?>">Saved Queues</a></li>
                                                            </ul>
                                                    </li>
                                                    <li>
                                                            <a href="#" class="sidebar-anchor">History</a>
                                                    </li>
                                            </ul>
                                            <?php } ?>
                                            <ul class="my-downloads sidebar-nav"><h3>My Downloads</h3>
                                                    <li><?php echo $html->link(__('Downloads', true), array('controller' => 'homes', 'action' => 'my_history'), array('class' => $download_css)); ?></li>
                                                    <li><a href="#" class="sidebar-anchor">My Playlists</a></li>
                                                    <?php /*if($libraryInfo['Library']['library_unlimited'] != "1"){ */?>
                                                    <li><?php echo $html->link(__('My Wishlist', true), array('controller' => 'homes', 'action' =>'my_wishlist'), array('class' => $wishlist_css)); ?></li>
                                                    <?php /* } */ ?>     
                                            </ul>                                            
                                            <div class="announcements">
                                                    <h4><a href="#">Announcements</a></h4>
                                                    <div class="poll">
                                                        <?php echo $announcment_value; ?>
                                                    </div>
                                            </div>
                                            <?php } ?>
					</section>					
					<div class="content">
                                            <span class="ajaxmessage44" id="ajaxflashMessage44"></span>
 