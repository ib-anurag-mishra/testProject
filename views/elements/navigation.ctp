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

    <div class="wrapper">
			<!-- site header -->
			<header class="site-header">                                    
                                    <h1 class="logo"><a href="/homes/index"><img src="<? echo $this->webroot; ?>app/webroot/img/logo.png" alt="logo" width="157" height="108"></a></h1>
					<div class="master-music-search-wrapper">
						<form class="search" name="search" action="" method="post">							
							<input type="text" id="search-text" name="search-text" />							
						</form>
						<button type="submit"><img src="<? echo $this->webroot; ?>app/webroot/img/magnifying-glass.png" alt="magnifying-glass" width="17" height="18"></button>
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
                                                    echo "<div>".$html->link(__('Notifications', true), array('controller' => 'users', 'action' => 'my_account'))."</div>";
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
                                                echo $html->link(__('Log in', true), array('controller' => 'users', 'action' => 'redirection_manager'),array('class' => 'btn'));
                                            } else {
                                                echo $html->link(__('Log in', true), array('controller' => 'homes', 'action' => 'chooser'),array('class' => 'btn'));
                                            }
                                       ?>
                                   </div>
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

                    if($_SERVER['REQUEST_URI'] == '/homes/index' || $_SERVER['REQUEST_URI'] == '/index')
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
                    else if($_SERVER['REQUEST_URI'] == '/questions')
                    {
                        $faqCss = "regular active";
                    }
                    ?>
                    <ul class="clearfix">
			<li><?php echo $html->link(__('News', true), array('controller' => 'homes','action'=>'index'), array("class"=>$newsCss));?></li>			
                        <li><?php echo $html->link(__('Music Videos', true), array('controller' => 'videos', 'action' =>'index'), array("class"=>$videoCss)); ?></li></li>
                        <li><a href="#" class="<?php echo $mostPopularCss; ?>">Most Popular</a></li>
                        <li><?php echo $html->link(__('Genres', true), array('controller' => 'genres', 'action' =>'view'), array("class"=>$genreCss)); ?></li></li>   
                        <li><?php echo $html->link(__('FAQ', true), array('controller' => 'questions', 'action' =>'index'), array("class"=>$faqCss)); ?></li>
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
			<div class="content-wrapper clearfix">				
					<section class="left-sidebar">
                                            <ul class="browse sidebar-nav"><h3>Browse</h3>
                                                    <li>
                                                            <?php echo $html->link(__('Music Videos', true), array('controller' => 'videos', 'action' => 'index'),array('class'=>'sidebar-anchor')); ?>
                                                    </li>                                                    
                                                    <li>
                                                            <a href="#" class="sidebar-anchor">Most Popular</a>
                                                            <ul class="sidebar-sub-nav">
                                                                    <?php if($this->Session->read("patron")){ ?>
                                                                    <li><?php echo $html->link(__('My Lib Top 10', true), array('controller' => 'homes', 'action' =>'my_lib_top_10')); ?></li>
                                                                    <?php } ?>
                                                                    <li><?php echo $html->link(__('US Top 10', true), array('controller' => 'homes', 'action' =>'us_top_10')); ?></li>
                                                            </ul>
                                                    </li>                                                    
                                            </ul>
                                            <?php if($this->Session->read("patron")){ ?>
                                            <ul class="streaming sidebar-nav"><h3>Streaming</h3>								
                                                    <li>
                                                            <a href="#" class="sidebar-anchor">Freegal Playlists</a>
                                                            <ul class="sidebar-sub-nav">

                                                                    <li><a href="#">Top 40</a></li>
                                                                    <li><a href="#">90's</a></li>
                                                                    <li><a href="#">Classic Rock</a></li>
                                                                    <li><a href="#">Heavy Metal</a></li>
                                                                    <li><a href="#">Electronic</a></li>
                                                                    <li><a href="#">Hip Hop</a></li>
                                                                    <li><a href="#">Jazz</a></li>
                                                                    <li><a href="#">Shuffle</a></li>
                                                            </ul>
                                                    </li>
                                                    <li>
                                                            <a href="#" class="sidebar-anchor saved-queue">My Queues</a>
                                                            <ul class="sidebar-sub-nav">
                                                                    <li><a href="now-streaming.php">Now Streaming</a></li>
                                                                    <li><a href="#">Saved Queues</a></li>
                                                            </ul>
                                                    </li>
                                                    <li>
                                                            <a href="#" class="sidebar-anchor">History</a>
                                                    </li>
                                            </ul>                                           
                                            <ul class="my-downloads sidebar-nav"><h3>My Downloads</h3>
                                                    <li><?php echo $html->link(__('Downloads', true), array('controller' => 'homes', 'action' => 'my_history'), array('class' => 'sidebar-anchor')); ?></li>
                                                    <li><a href="#" class="sidebar-anchor">My Playlists</a></li>
                                                    <?php if($libraryInfo['Library']['library_unlimited'] != "1"){ ?>
                                                    <li><?php echo $html->link(__('Wishlist', true), array('controller' => 'homes', 'action' =>'my_wishlist'), array('class' => 'sidebar-anchor')); ?></li>
                                                    <?php } ?>     
                                            </ul>                                            
                                            <div class="announcements">
                                                    <h4><a href="#">Announcements</a></h4>
                                                    <div class="poll">
                                                    </div>
                                            </div>
                                            <?php } ?>
					</section>					
					<div class="content">
