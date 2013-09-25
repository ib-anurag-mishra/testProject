<script language="javascript" type="text/javascript">
/*    
function submit_registeration_details(){ alert("HI");
	
        //$('#ajax_artistlist_content').html('<span id="mydiv"><img src="<? echo $this->webroot; ?>app/webroot/img/AjaxLoader.gif" class="ajax-loader"/></span>');        
        var data = '';
        var link = webroot+'homes/ajax_submit_register_concert';
        alert(link);
        jQuery.ajax({
            type: "post",  // Request method: post, get
            url: link, // URL to request
            data: data,  // post data
            success: function(response) {  alert(response);              
                $('#ReturnMessage').html(response);
            },
            error:function (XMLHttpRequest, textStatus, errorThrown) { 
               // alert('No artist available for this Genre.');
            }
        });
}
*/

</script>


<script type="text/javascript">
$(document).ready(function() {
     $("#FormRegisterConcert").submit(function() {
     var frm = $('#FormRegisterConcert');
        $.ajax({
            type: "post",
            url: webroot+'homes/ajax_submit_register_concert',
            data: frm.serialize(),
            success: function (response) { 

                if(response=='Failure')
                {
                  $('#ReturnMessage').append("Please fill information in all fields.");   
                }
                else
                {
                    $('#FormRegisterConcert').hide();                          
                    $('#ReturnMessage').append(response); 
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
            // log the error to the console
            console.log(
                "The following error occured: "+
                textStatus, errorThrown
            );
            }
 
        });
 
        return false;
    });
});
</script>



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
                    <form action="/queuelistdetails/index/<?php echo $this->params['pass'][0]; ?>" method="post">
                    <div class="rename-form-container">
                                    <label for="name">Name:</label>
                                    <?php echo $this->Form->input('QueueList.queue_name', array('label' => false, 'div' => false, 'id' => 'name') ); ?>
                                    <label for="description">Description:</label>
                                    <?php echo $this->Form->input('QueueList.description', array('label' => false, 'div' => false, 'id' => 'description') ); ?>
                                    <input type="hidden" id="rqPlid" name="rqPlid" value="" />
                                    <input type="hidden" name="hid_action" value="rename_queue" />
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
                        <?php echo $this->Form->input('QueueList.queue_name', array('label' => false, 'div' => false, 'class' => 'form_fields') ); ?>
                        <label for="description">Description:</label>
                        <?php echo $this->Form->input('QueueList.description', array('label' => false, 'div' => false, 'class' => 'form_fields') ); ?>


                    </div>
                    <div class="buttons-container clearfix">
                            <div class="text-close">Close</div>
                            <input type="submit" class="save" value="Create New Queue"></input>
                    </div>
                    </form>
            </div>
        
            <div class="delete-queue-dialog-box">
                
                    <div class="close"></div>
                    <header>Delete Queue?</header>
                   <form  action="/queuelistdetails/index/<?php echo $this->params['pass'][0]; ?>" method="post">
                   <div class="confirm-text">
                            <p>Are you sure you want to delete '<span>Queue Name</span>'?</p>

                    </div>                   
                    <div class="buttons-container clearfix">
                    <div class="text-close">Close</div>
                    <input type="hidden" name="hid_action" value="delete_queue" />
                    <input type="hidden" id="dqPlid" name="dqPlid" value="" />
                    <input type="submit" class="save" value="Delete Queue"></input>
                    </div>
                    </form>
            </div>
            

    </div>
    <div class="wrapper">
			<!-- site header -->
			<header class="site-header">                                    
                                        <div>
                                            <?php
                                            $url = $_SERVER['SERVER_NAME'];
                                            $host = explode('.', $url);
                                            $subdomains = array_slice($host, 0, count($host) - 2 );									
                                            $subdomains = $subdomains[0] ;  

                                            if($subdomains !== '' && $subdomains != 'www' && $subdomains != 'freegalmusic'){
                                                if($libraryInfo['Library']['library_image_name'] != "") {
                                                    ?>
                                                    <?php
                                                    if($libraryInfo['Library']['library_home_url'] != "") {
                                                    ?>
                                                        <a href="<?php echo $libraryInfo['Library']['library_home_url']; ?>" target="_blank"><img height="60px" src="<?php echo str_replace("test","prod",$cdnPath); ?>libraryimg/<?php echo $libraryInfo['Library']['library_image_name']; ?>" alt="<?php echo $libraryInfo['Library']['library_name']; ?>" title="<?php echo $libraryInfo['Library']['library_name']; ?>" style="padding-top: 20px;"></a>
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <img height="60px" src="<?php echo str_replace("test","prod",$cdnPath); ?>libraryimg/<?php echo $libraryInfo['Library']['library_image_name']; ?>" alt="<?php echo $libraryInfo['Library']['library_name']; ?>" title="<?php echo $libraryInfo['Library']['library_name']; ?>" style="padding-top: 20px;" />
                                                        <?php
                                                    }
                                                    ?>
                                                    <?php
                                                } elseif(!$libraryInfo['Library']['show_library_name']) {
                                                    if($libraryInfo['Library']['library_home_url'] != "") {
                                                ?>
                                                   <div style="padding-top: 45px;"><a href="<?php echo $libraryInfo['Library']['library_home_url']; ?>" target="_blank"><?php echo $libraryInfo['Library']['library_name']; ?></a></div>
                                                <?php
                                                    } else { ?>
                                                   <div style="padding-top: 45px;"><?php echo $libraryInfo['Library']['library_name']; ?></div>
                                                   <?php
                                                    }
                                                } else {
                                                ?>
                                                <h1 class="logo"><a href="/homes/index"><img src="<? echo $this->webroot; ?>app/webroot/img/logo.png" alt="logo" width="157" height="108"></a></h1>
                                               <?php }
	                                    } else {
                                            ?>
                                            <h1 class="logo"><a href="/homes/index"><img src="<? echo $this->webroot; ?>app/webroot/img/logo.png" alt="logo" width="157" height="108" /></a></h1>
                                           <?php } ?>
                                        </div>
					<div class="master-music-search-wrapper">
						<form class="search" name="search" id="HomeSearchForm" method="get" action="/search/index" accept-charset="utf-8">							
                            <select name="type" id="master-filter">
								<option value="all">Search All</option>
								<option value="album">Albums</option>
								<option value="artist">Artists</option>
								<option value="composer">Composers</option>
								<option value="genre">Genres</option>
								<option value="song">Songs</option>
								<option value="video">Videos</option>
							</select>
							<input type="text" id="search-text" name="q" value="<?php echo $keyword; ?>" />							
                            <!-- <input type="hidden" name="type" id="header-search-type" value="all" /> -->
						</form>
						<button type="submit" onclick="document.getElementById('HomeSearchForm').submit()"><img src="<? echo $this->webroot; ?>app/webroot/img/magnifying-glass.png" alt="magnifying-glass" width="13" height="13"></button>
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
                                        <?php
                                                //  Hidden variable to be used in site.js for alerting user before video download
                                        
                                                    if(($downloadCount+1)<$libraryInfo['Library']['library_user_download_limit'])
                                                    {
                                                        ?>
                                                            <input type="hidden" name="hid_VideoDownloadStatus" id="hid_VideoDownloadStatus" value="1" />
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                          ?>
                                                            <input type="hidden" name="hid_VideoDownloadStatus" id="hid_VideoDownloadStatus" value="0" />
                                                        <?php
                                                    }
                                        
                                        
                                        ?>
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
					<div class="play-count"><span id='downloads_used'>0</span></div>     
                               </div>
                                <?php  } ?>
                                    
                                    
			</header>
                        
                       
			<!-- site nav -->
		<nav class="site-nav">
                    <?php
                    $newsCss = "regular";
                    $videoCss = "regular";
                    $mostPopularCss = "";
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
                        $mostPopularCss = "active";
                    }
                    else if($_SERVER['REQUEST_URI'] == '/genres/view')
                    {
                        $genreCss = "regular active";
                    }
                     else if($_SERVER['REQUEST_URI'] == '/homes/new_releases')
                    {
                        $newReleaseCss = "regular active";
                    }
                    else if($_SERVER['REQUEST_URI'] == '/questions')
                    {
                        $faqCss = "regular active";
                    }
                    ?>
                    <ul class="clearfix">
			<li class="regular"><?php echo $html->link(__('Home', true), array('controller' => 'homes','action'=>'index'), array("class"=>$newsCss));?></li>			
                        <li class="regular"><?php echo $html->link(__('Music Videos', true), array('controller' => 'videos', 'action' =>'index'), array("class"=>$videoCss)); ?></li>
                        <li class="most-popular"><?php if($subdomains !== '' && $subdomains != 'www' && $subdomains != 'freegalmusic'){ echo $html->link(__('Most Popular', true), array('controller' => 'homes', 'action' =>'my_lib_top_10')); } else { if($this->Session->read("patron")){ echo $html->link(__('Most Popular', true), array('controller' => 'homes', 'action' =>'my_lib_top_10')); } else { echo $html->link(__('Most Popular', true), array('controller' => 'homes', 'action' =>'us_top_10')); } } ?></li>
                        <li class="regular"><?php echo $html->link(__('New Releases', true), array('controller' => 'homes', 'action' =>'new_releases'), array("class"=>$newReleaseCss)); ?></li> 
                        <li class="regular"><?php echo $html->link(__('Genres', true), array('controller' => 'genres', 'action' =>'view'), array("class"=>$genreCss)); ?></li>   
                        <li class="regular"><?php echo $html->link(__('FAQ', true), array('controller' => 'questions', 'action' =>'index'), array("class"=>$faqCss)); ?></li>
                    </ul>
                    
                    <div class="most-popular-sub-nav">
                            <?php if($subdomains !== '' && $subdomains != 'www' && $subdomains != 'freegalmusic'){ ?>
                                        <div><?php echo $html->link(__('My Lib Top 10', true), array('controller' => 'homes', 'action' =>'my_lib_top_10')); ?></div>
                            <?php } else {
                                    if($this->Session->read("patron")){ ?>
                                        <div><?php echo $html->link(__('My Lib Top 10', true), array('controller' => 'homes', 'action' =>'my_lib_top_10')); ?></div>
                            <?php   } 
                                  } ?>
                            <div><?php echo $html->link(__($this->Session->read('territory').' Top 10', true), array('controller' => 'homes', 'action' =>'us_top_10')); ?></div>
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
                                 $section_class = "";
                                 
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
                                else if($_SERVER['REQUEST_URI'] == '/homes/aboutus')
                                {
                                    $section_class = "height:900px;";
                                }
                                else if($_SERVER['REQUEST_URI'] == '/homes/new_releases')
                                {
                                    $new_releases_css = "sidebar-anchor active";
                                }
                                
                        
                        
                        ?>
                        
			<div class="content-wrapper clearfix">	
                            
					<section class="left-sidebar">
                                            <ul class="browse sidebar-nav"><h3><?php __('Browse'); ?></h3>
                                                    <li>
                                                            <?php echo $html->link(__('Music Videos', true), array('controller' => 'videos', 'action' => 'index'),array('class'=>$music_videos_css)); ?>
                                                    </li>                                                    
                                                    <li>
                                                            <a class="sidebar-anchor" style="cursor:pointer"><?php __('Most Popular'); ?></a>
                                                            <ul class="<?php echo $ul_class; ?>">
                                                                <?php if($subdomains !== '' && $subdomains != 'www' && $subdomains != 'freegalmusic'){ ?>
                                                                        <li><?php echo $html->link(__('My Lib Top 10', true), array('controller' => 'homes', 'action' =>'my_lib_top_10'),array('class'=>$my_lib_css)); ?></li>
                                                                <?php } else {
                                                                        if($this->Session->read("patron")){ ?>
                                                                            <li><?php echo $html->link(__('My Lib Top 10', true), array('controller' => 'homes', 'action' =>'my_lib_top_10'),array('class'=>$my_lib_css)); ?></li>
                                                                  <?php } 
                                                                      } ?>
                                                                <li><?php echo $html->link(__($this->Session->read('territory').' Top 10', true), array('controller' => 'homes', 'action' =>'us_top_10'),array('class'=>$us_top_css)); ?></li>
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
                                                    <?php if(!empty($defaultQueues)){  ?>
                                                    
                                                    <li>
                                                            <a href="#" class="sidebar-anchor"><?php __('Freegal Queues'); ?></a>
                                                            <ul class="sidebar-sub-nav">
                                                                <?php foreach($defaultQueues as $key => $value){?>
                                                                    <li><a href="/queuelistdetails/queue_details/<?php echo $value['QueueList']['queue_id'];?>/<?php echo $value['QueueList']['queue_type'];?>"><?php echo $value['QueueList']['queue_name']; ?></a></li>
                                                                <?php } ?>    
                                                            </ul>
                                                    </li>
                                                    <?php } ?>
                                                    <li>
                                                            <a href="#" class="sidebar-anchor saved-queue"><?php __('My Queues'); ?></a>
                                                            <ul class="sidebar-sub-nav">
                                                                    <li><a href="/queuelistdetails/now_streaming"><?php __('Now Streaming'); ?></a></li>
                                                                    <li><a href="/queues/savedQueuesList/<?php echo $this->Session->read("patron"); ?>"><?php __('Saved Queues'); ?></a></li>
                                                            </ul>
                                                    </li>
                                                    <li>
                                                            <a href="#" class="sidebar-anchor"><?php __('History'); ?></a>
                                                    </li>
                                            </ul>
                                            <?php } ?>
                                            <ul class="my-downloads sidebar-nav"><h3><?php __('My Downloads'); ?></h3>
                                                    <li><?php echo $html->link(__('Downloads', true), array('controller' => 'homes', 'action' => 'my_history'), array('class' => $download_css)); ?></li>
                                                    <?php /*if($libraryInfo['Library']['library_unlimited'] != "1"){ */?>
                                                    <li><?php echo $html->link(__('My Wishlist', true), array('controller' => 'homes', 'action' =>'my_wishlist'), array('class' => $wishlist_css)); ?></li>
                                                    <?php /* } */ ?>     
                                            </ul>
                                            <?php
                                            
                                                    if($this->Session->read("lId")==486 || $this->Session->read("lId")==602 || $this->Session->read("lId")==85)                                                     
                                                    {                                                         
                                                        ?>    
                                                             <div class="announcements">
                                                                <h4><?php __('Announcements'); ?></h4>
                                                                <div class="poll1" style="display:block;height:400px;">                                                                                                                                                                       
                                                                 Register for Concert Tickets Giveway<br><br>
                                                                 One entry only<br><br>
                                                                 <?php echo $html->link(__('More Info', true), array('controller' => 'homes','action'=>'great_fall_concert'));?><br><br>   
                                                                 <span id="ReturnMessage"></span> <br><br>  
                                                                <form  id="FormRegisterConcert" method="post">
                                                                    <label for="UserEmail">First Name :</label>
                                                                    <?php echo $this->Form->input('first_name', array('label' => false, 'div' => false, 'style' => 'width:120px; padding:7px 6px 2px 0px;') ); ?> <br><br>
                                                                    <label for="UserEmail">Last Name :</label>
                                                                    <?php echo $this->Form->input('last_name', array('label' => false, 'div' => false, 'style' => 'width:120px; padding:7px 6px 2px 0px; float:right;') ); ?> <br><br><br><br>                                                                  
                                                                    <label for="UserEmail">Library Card :</label>
                                                                    <?php echo $this->Form->input('library_card', array('label' => false, 'div' => false, 'style' => 'width:120px; padding:7px 6px 2px 0px;') ); ?> <br><br>                                                                    
                                                                    <label for="UserEmail">Phone :</label>                                                                    
                                                                    <?php echo $this->Form->input('phone_no', array('label' => false, 'div' => false, 'style' => 'width:120px; padding:7px 6px 2px 0px;') ); ?> <br>    
                                                                    <input type="hidden" name="library_id" value="<?php echo $this->Session->read("lId"); ?>"></input><br>
                                                                    <input type="submit" class="save" value="Submit"></input>                                                                
                                                                </form>
                                                                    <span id="ReturnMessage"></span>
                                                                
                                                                 </div>
                                                            </div>
                                            
                                            
                                                        <?php
                                                    }
                                                    else    // For other Libraries
                                                    {
                                                        $temp_text  =   strip_tags($announcment_value);
                                                        
                                                        if($temp_text!="")
                                                        {
                                                            $announcment_class  =   "display:block;overflow-y:scroll;";
                                                        }
                                                        else
                                                        {
                                                            $announcment_class  =   "";
                                                        }
                                                        
                                                        ?>
                                                                <div class="announcements">
                                                                <h4><?php __('Announcements'); ?></h4>
                                                                <div class="poll1" style="<?php echo $announcment_class; ?>">
                                                                    <?php echo $announcment_value; ?>
                                                                </div>
                                                                </div>
                                            
                                                        <?php
                                                    }
                                                    
                                            ?>
                                            
                                            <?php } ?>
					</section>					
					<div class="content" style="<?php echo $section_class; ?>">
                                            <span class="ajaxmessage44" id="ajaxflashMessage44"></span>
 