<script type="text/javascript">

var createLinkThis;

/*
$(document).ready(function() {
     $("#FormRegisterConcert").submit(function() {
     var frm = $('#FormRegisterConcert');
        $.ajax({
            type: "post",
            url: webroot+'registerconcerts/ajax_submit_register_concert',
            data: frm.serialize(),
            success: function (response) { 
                //alert("["+response+"]");
                if(response=='Failure')
                {
                  $('#FailureMessage').html("<br><span style='color:red;'>Please fill information in all fields.</span><br>");   
                }
                else
                {
                    $('#FormRegisterConcert').hide();   
                    $('#FailureMessage').hide();
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
}); */

$(document).ready(function() {
     $("#FormRename").submit(function() {
        var frm = $('#FormRename');
           $.ajax({
               type: "post",
               url: webroot+'queuelistdetails/ajaxQueueValidation',
               data: frm.serialize(),
               success: function (response) { 
                   //alert("["+response+"]");
                   if(response=='Insertion Allowed')
                   {     
                        $(this).unbind('submit').submit();
                        renameQueue();
                        return false;
                   }
                   else
                   {
                          $('#RenameQueueMessage').html("<span style='color:red;'>"+response+"</span><br>");                                               
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

function renameQueue()
{
    //alert($('.rename-form-container').find('#name').val());

    $.ajax({
            type: "post",
            url: webroot+'queuelistdetails/index/'+ $('#rqPlid').val(),

            data : $('#FormRename').serialize(),

            success: function (response) { 
                $('.col-container').find('.queue-name').text($('.rename-form-container').find('#name').val());
                $('.breadcrumbs').find('a:first').next().text($('.rename-form-container').find('#name').val());

                var name = $('.rename-form-container').find('#name').val();
                              
                $('#hid_playlist_name').val( name );
                $('#hid_description').val( $('.rename-form-container').find('#description').val() );

                //updating the queuelist
                 $(document).find('.playlist-options-test').find('.playlist-options').find('li').each(function(){
                    if( $(this).find('a').attr('id') === $('#rqPlid').val() )
                    {
                        $(this).find('a').text( name );
                    }
                 });
                
                document.getElementById('ajaxflashMessage44').innerHTML = '' ;
                document.getElementById('ajaxflashMessage44').innerHTML = response ;
                $('#ajaxflashMessage44').css('display','block');
                
                $('.rename-queue-dialog-box').removeClass('active');
                $('.queue-overlay').removeClass('active');
                resetForms();
                
            },
            error: function(jqXHR, textStatus, errorThrown){
                // log the error to the console
                console.log(
                    "The following error occured: "+
                    textStatus, errorThrown );
            }                          
        });
}


$(document).ready(function() {
     $("#FormDelete").submit(function() { 
     var frm = $('#FormDelete');
        $.ajax({
            type: "post",
            url: webroot+'queuelistdetails/ajaxQueueValidation',
            data: frm.serialize(),
            success: function (response) { 
               
                if(response=='Insertion Allowed')
                {                   
                    $(this).unbind('submit').submit();
                    createQueue();
                }
                else
                {
                       $('#CreateQueueMessage').html("<span style='color:red;'>"+response+"</span><br>");                                               
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

function createQueue(){
    $.ajax({
            type: "post",
            url: webroot+'queues/createQueue',

            data : $('#FormDelete').serialize(),

            success: function (response) { 
                
            var album_data = response.split('&');
            
              var
                    $this = $(this),
                    url = window.location.pathname ,
                    title = $this.attr('title') || null;
                
                document.getElementById('ajaxflashMessage44').innerHTML = '' ;
                document.getElementById('ajaxflashMessage44').innerHTML = album_data[0] ;
                $('#ajaxflashMessage44').css('display','block');
                
                $('.delete-queue-dialog-box').removeClass('active');
                $('.queue-overlay').removeClass('active');
                 resetForms();
                 
                 if(createLinkThis !== null)
                 {
                 //adding the current song / album to newly create playlist
                 addToAlbumTest( album_data[1], this );
                 }
                else
                {
                    $(document).find('.playlists-shadow-container').find('.playlists-container').remove();
                    
                    var loading_div = "<div class='loader'>";
                    loading_div += "</div>";
                    $(document).find('.playlists-shadow-container').append(loading_div);

                    $.ajax({
                         type: "post",
                         url: webroot+'queues/ajaxSavedQueuesList',
                          success: function (response) { 
                            $(document).find('.playlists-shadow-container').find('.playlists-scrollable').append(response);

                            $(document).find('.playlists-shadow-container').find('.loader').remove();
                          }
                    });
                }

                 var updated_queue_list = '<li><a href="JavaScript:void(0);" onclick="JavaScript:addToAlbumTest('+album_data[1]+', this );" id="'+album_data[1]+'">' +album_data[2] + '</a></li>';
                 $(updated_queue_list).insertAfter( $(document).find('.playlist-options-test').find('.playlist-options').find('ul li:eq(0)') );

                History.pushState(null, title, url);
                event.preventDefault();
            },
            error: function(jqXHR, textStatus, errorThrown){
                // log the error to the console
                console.log(
                    "The following error occured: "+
                    textStatus, errorThrown );
            }                          
        });
}

function resetForms()
{
    $('#FormDelete').find("input[type=text], textarea").val("");
    $('#CreateQueueMessage').html("");

     $('#FormRename').find("input[type=text], textarea").val("");
    $('#RenameQueueMessage').html("");

    $('.delete-queue-dialog-box').closest('form').find("input[type=text], textarea").val("");
}

$(document).ready(function() {

    $(document).on('click', '.sidebar-anchor', function(e) {

        if ($(this).next('ul').hasClass('active')) {

            $(this).next('ul').removeClass('active');

        } else {

            $(this).next('ul').addClass('active');
            /* var home07 = $('#home07');
            home07.removeClass('active');
            var musicVideo07 = $('#musicVideo07');
            musicVideo07.removeClass('active');
            var newsRelease07 = $('#newsRelease07');
            newsRelease07.removeClass('active');
            var genre07 = $('#genre07');
            genre07.removeClass('active');
            var faq07 = $('#faq07');
            faq07.removeClass('active'); */
        }

    });

    $('.select-arrow').on('click', function(e) {
        if ($('.account-options-menu').hasClass('active')) {
            $('.account-options-menu').removeClass('active');
        } else {
            $('.account-options-menu').addClass('active');
        }
    });

    $('.delete-queue-dialog-box').submit(function(){
                        
            $.ajax({
            type: "post",
            url: webroot+'queuelistdetails/index/'+ $('#dqPlid').val(),
            data : {'hid_action' :'delete_queue' , 'dqPlid':$('#dqPlid').val() },

            success: function (response) { 
              var
                    $this = $(this),
                    url = "/queues/savedQueuesList/<?php echo $this->Session->read("patron"); ?>" ,
                    title = $this.attr('title') || null;
                    
                $('.delete-queue-dialog-box').removeClass('active');
                $('.queue-overlay').removeClass('active');
               
                //updating the queuelist
                 $(document).find('.playlist-options-test').find('.playlist-options').find('li').each(function(){
                    if( $(this).find('a').attr('id') === $('#rqPlid').val() )
                    {
                        $(this).remove();
                    }
                 });

                History.pushState(null, title, url);
                event.preventDefault();
            },
            error: function(jqXHR, textStatus, errorThrown){
                // log the error to the console
                console.log(
                    "The following error occured: "+
                    textStatus, errorThrown );
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
                    <header>Rename '<span>Playlist Name</span>'</header>
                    <form id="FormRename" action="#">
                    <div class="rename-form-container">
                        <label id="RenameQueueMessage"></label> 
                        <label for="name">Name:</label>
                        <?php echo $this->Form->input('QueueList.queue_name', array('label' => false, 'div' => false, 'id' => 'name') ); ?>
                        <label for="description">Description:</label>
                        <?php echo $this->Form->input('QueueList.description', array('label' => false, 'div' => false, 'id' => 'description') ); ?>                                                                        
                        <input type="hidden" id="rqPlid" name="rqPlid" value="" />
                        <input type="hidden" name="hid_action" value="rename_queue" />
                    </div>
                    <div class="buttons-container clearfix">
                            <div class="text-close">Close</div>
                            <input type="submit" class="save" value="Save Changes" />
                    </div>
                    </form>
            </div>
            <div class="create-queue-dialog-box">
                <div class="close"></div>
                <header>Create Playlist</header>
                <form id="FormDelete" action="#" >
                    <div class="rename-form-container">
                        <label id="CreateQueueMessage"></label> 
                        <label for="name">Name:</label>
                        <?php echo $this->Form->input('QueueList.queue_name', array('label' => false, 'div' => false, 'class' => 'form_fields') ); ?>
                        <label for="description">Description:</label>
                        <?php echo $this->Form->input('QueueList.description', array('label' => false, 'div' => false, 'class' => 'form_fields') ); ?>
                    </div>
                    <div class="buttons-container clearfix">
                            <div class="text-close">Close</div>
                            <input type="submit" class="save" value="Create New Playlist" />
                    </div>
                </form>
            </div>
        
            <div class="delete-queue-dialog-box">                
                    <div class="close"></div>
                    <header>Delete Playlist?</header>
                   <form action="#">
                    <div class="confirm-text">
                             <p>Are you sure you want to delete '<span>Queue Name</span>'?</p>
                     </div>                   
                     <div class="buttons-container clearfix">
                         <div class="text-close">Close</div>
                         <input type="hidden" name="hid_action" value="delete_queue" />
                         <input type="hidden" id="dqPlid" name="dqPlid" value="" />
                         <input type="submit" class="save" value="Delete Playlist" />
                     </div>
                    </form>
            </div>
       
            <div class="playlist-options-test" style="display:none;">
                <?php
                    print_r( $this->Queue->getUserQueuesList($this->Session->read('patron')) );
                ?>
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
						<form class="search" name="search" id="HomeSearchForm" method="get" action="/search/index" accept-charset="utf-8" onsubmit="ajaxSearch(); return false;">							
                            <select name="type" id="master-filter">
								<option value="all">Search All</option>
								<option value="album">Albums</option>
								<option value="artist">Artists</option>
								<option value="composer">Composers</option>
								<option value="genre">Genres</option>
								<option value="song">Songs</option>
								<option value="video">Videos</option>
							</select>
							<input type="text" id="search-text" name="q" value="" />							
                            <!-- <input type="hidden" name="type" id="header-search-type" value="all" /> -->
						</form>
						<!-- onclick="document.getElementById('HomeSearchForm').submit()" -->
                        <button id="headerSearchSubmit"><img src="<? echo $this->webroot; ?>app/webroot/img/magnifying-glass.png" alt="magnifying-glass" width="13" height="13"></button>
                                                <?php echo $html->link(__('Browse A-Z', true), array('controller' => 'genres', 'action' =>'view')); ?>
					</div>
					<div class="master-music-search-results">
						<ul>
							<li>
								<div class="master-search-results-image">
									<img src="<? echo $this->webroot; ?>app/webroot/img/master_music_search_results/adele.jpg" alt="adele">
								</div>
								<div class="master-search-results-detail">
									<p class="song-album-info"><span class="album-title"><a href="javascript:void(0)">21</a></span><span class="song-title"><a href="javascript:void(0)"></a></span></p>
									<p class="artist"><a href="javascript:void(0)">Adele</a></p>
								</div>
							</li>
							<li>
								<div class="master-search-results-image">
									<img src="<? echo $this->webroot; ?>app/webroot/img/master_music_search_results/pitbull.jpg" alt="pitbull">
								</div>
								<div class="master-search-results-detail">
									<p class="song-album-info"><span class="album-title"><a href="javascript:void(0)"></a></span><span class="song-title"><a href="javascript:void(0)">Mr. Worldwide</a></span></p>
									<p class="artist"><a href="javascript:void(0)">Pitbull</a></p>
								</div>
							</li>
							<li>
								<div class="master-search-results-image">
									<img src="<? echo $this->webroot; ?>app/webroot/img/master_music_search_results/carrie-underwood.jpg" alt="carrie-underwood">
								</div>
								<div class="master-search-results-detail">
									<p class="song-album-info"><span class="album-title"><a href="javascript:void(0)"></a></span><span class="song-title"><a href="javascript:void(0)">Before He Cheats</a></span></p>
									<p class="artist"><a href="javascript:void(0)">Carrie Underwood</a></p>
								</div>
							</li>
							<li>
								<div class="master-search-results-image">
									<img src="<? echo $this->webroot; ?>app/webroot/img/master_music_search_results/kelly-clarkson.jpg" alt="kelly-clarkson">
								</div>
								<div class="master-search-results-detail">
									<p class="song-album-info"><span class="album-title"><a href="javascript:void(0)"></a></span><span class="song-title"><a href="javascript:void(0)">All I Ever Wanted</a></span></p>
									<p class="artist"><a href="javascript:void(0)">Kelly Clarkson</a></p>
								</div>
							</li>
							<li>
								<div class="master-search-results-image">
									<img src="<? echo $this->webroot; ?>app/webroot/img/master_music_search_results/michael-jackson.jpg" alt="michael-jackson">
								</div>
								<div class="master-search-results-detail">
									<p class="song-album-info"><span class="album-title"><a href="javascript:void(0)">Thriller</a></span><span class="song-title"><a href="javascript:void(0)"></a></span></p>
									<p class="artist"><a href="javascript:void(0)">Michael Jackson</a></p>
								</div>
							</li>
						</ul>
					</div>
					
				<?php if($this->Session->read("patron")){ ?>
				<div class="weekly-downloads-container clearfix">
					<div class="label">
						<p>My Account</p>
					</div>
                                        <a class="select-arrow" href="javascript:void(0);"></a>
					<div class="small-divider"></div>
					<div class="tooltip">
						<a href="javascript:void(0);" class="no-ajaxy"><img src="<? echo $this->webroot; ?>app/webroot/img/note-icon.png" alt="tooltip_play_btn" width="17" height="17"></a>						
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
                                            <div><?php echo $html->link(__('Logout', true), array('controller' => 'users', 'action' =>'logout'),array('class' =>'no-ajaxy'));?></div>
                                        </div>
					<div class="play-count"><span id='downloads_used'><?php echo $this->Session->read('downloadCount'); ?></span>/<?php echo $libraryInfo['Library']['library_user_download_limit']; ?></div> 
                                        <?php

                                             if($this->Session->read('library_type')==2 && $libraryInfo['Library']['library_unlimited']==1 && $libraryInfo['Library']['library_user_download_limit']> 4)
                                               { 
                                                     $streamTime = 'UNLIMITED';

                                               }else if($this->Session->read('library_type')==2){

                                                    $lastStreamedDate   =   $this->Streaming->getLastStreamDate($this->Session->read('library'),$this->Session->read('patron'));
                                                    $todaysDate         =   date("Y-m-d");                                                    
                                                    
                                                    if(strtotime(date("Y-m-d",strtotime($lastStreamedDate))) != strtotime(date('Y-m-d'))) // if Patron Logs in for first time in day 
                                                    {
                                                        $streamTime =   10800;                                                        
                                                    }
                                                    else
                                                    {
                                                        $streamTime = $this->Streaming->getTotalStreamTime($this->Session->read('library'),$this->Session->read('patron'));

                                                        if(empty($streamTime))      // if there is no record of patron in streaming_records table i.e. user is streaming for first time
                                                        {
                                                            $streamTime =   10800;
                                                        }
                                                        else    // if user has streamed one or more time
                                                        {
                                                            $streamTime = (10800-$this->Streaming->getTotalStreamTime($this->Session->read('library'),$this->Session->read('patron'))); 
                                                        }
                                                                                                           
                                                    } 

                                                     $streamTime =   gmdate("H:i:s", $streamTime);
                                               }   
                                        ?>
                                                <span id="hid_library_unlimited" style="display:none;"><?php echo $libraryInfo['Library']['library_unlimited']; ?></span>
                                                <?php if($this->Session->read('library_type')==2){ ?>
                                                    <div class="stream-time" ><span>Streaming Time Remaining:&nbsp;</span><span id="remaining_stream_time"><?php echo $streamTime; ?></span></div> 
                                                <?php
                                                    }
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
						<a href="javascript:void(0)"><img src="<? echo $this->webroot; ?>app/webroot/img/note-icon.png" alt="tooltip_play_btn" width="17" height="17"></a>						
					</div>
                                        <div class="account-options-menu">                                            
                                            
                                            <div><?php echo $html->link(__('Logout', true), array('controller' => 'users', 'action' =>'logout'),array('class' =>'no-ajaxy'));?></div>
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
		    $newReleaseCss="";

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
			<li class="regular"><?php echo $html->link(__('Home', true), array('controller' => 'homes','action'=>'index'), array("class"=>$newsCss,"id"=>'home07',"onclick"=>"setUpperNavigation('home07')"));?></li>			
                        <li class="regular"><?php echo $html->link(__('Music Videos', true), array('controller' => 'videos', 'action' =>'index'), array("class"=>$videoCss,"id"=>'musicVideo07',"onclick"=>"setUpperNavigation('musicVideo07')")); ?></li>
                        <li class="most-popular"><?php if($subdomains !== '' && $subdomains != 'www' && $subdomains != 'freegalmusic'){ echo $html->link(__('Most Popular', true), array('controller' => 'homes', 'action' =>'my_lib_top_10'), array("id"=>'topmylib07' ,"onclick"=>"setUpperNavigation('topmylib07')")); } else { if($this->Session->read("patron")){ echo $html->link(__('Most Popular', true), array('controller' => 'homes', 'action' =>'my_lib_top_10'), array("id"=>'topmylib07',"onclick"=>"setUpperNavigation('topmylib07')")); } else { echo $html->link(__('Most Popular', true), array('controller' => 'homes', 'action' =>'us_top_10'), array("id"=>'topustop07',"onclick"=>"setUpperNavigation('topustop07')")); } } ?></li>
                        <li class="regular"><?php echo $html->link(__('New Releases', true), array('controller' => 'homes', 'action' =>'new_releases'), array("class"=>$newReleaseCss,"id"=>'newsRelease07',"onclick"=>"setUpperNavigation('newsRelease07')")); ?></li> 
                        <li class="regular"><?php echo $html->link(__('Genres', true), array('controller' => 'genres', 'action' =>'view'), array("class"=>$genreCss,"id"=>'genre07',"onclick"=>"setUpperNavigation('genre07')")); ?></li>   
                        <li class="regular"><?php echo $html->link(__('FAQ', true), array('controller' => 'questions', 'action' =>'index'), array("class"=>$faqCss,"id"=>'faq07',"onclick"=>"setUpperNavigation('faq07')")); ?></li>
                    </ul>
                    
                    <div class="most-popular-sub-nav">
                            <?php if($subdomains !== '' && $subdomains != 'www' && $subdomains != 'freegalmusic'){ ?>
                                        <div><?php echo $html->link(__('My Lib Top 10', true), array('controller' => 'homes', 'action' =>'my_lib_top_10'), array("id"=>'topmylib07',"onclick"=>"setUpperNavigation('topmylib07')")); ?></div>
                            <?php } else {
                                    if($this->Session->read("patron")){ ?>
                                        <div><?php echo $html->link(__('My Lib Top 10', true), array('controller' => 'homes', 'action' =>'my_lib_top_10'), array("id"=>'topmylib07',"onclick"=>"setUpperNavigation('topmylib07')")); ?></div>
                            <?php   } 
                                  } ?>
                            <div><?php echo $html->link(__($this->Session->read('territory').' Top 10', true), array('controller' => 'homes', 'action' =>'us_top_10'), array("id"=>'topustop07',"onclick"=>"setUpperNavigation('topustop07')")); ?></div>
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
                                                            <?php echo $html->link(__('Music Videos', true), array('controller' => 'videos', 'action' => 'index'),array('class'=>$music_videos_css,"id"=>'leftmusicVideo07',"onclick"=>"setUpperNavigation('leftmusicVideo07')")); ?>
                                                    </li>                                                    
                                                    <li>
                                                            <a class="sidebar-anchor"  style="cursor:pointer" href="javascript:void(0);" ><?php __('Most Popular'); ?></a>
                                                            <ul class="<?php echo $ul_class; ?>">
                                                                <?php if($subdomains !== '' && $subdomains != 'www' && $subdomains != 'freegalmusic'){ ?>
                                                                        <li><?php echo $html->link(__('My Lib Top 10', true), array('controller' => 'homes', 'action' =>'my_lib_top_10'),array('class'=>$my_lib_css,"id"=>'leftmylib07',"onclick"=>"setUpperNavigation('leftmylib07')")); ?></li>
                                                                <?php } else {
                                                                        if($this->Session->read("patron")){ ?>
                                                                            <li><?php echo $html->link(__('My Lib Top 10', true), array('controller' => 'homes', 'action' =>'my_lib_top_10'),array('class'=>$my_lib_css,"id"=>'leftmylib07',"onclick"=>"setUpperNavigation('leftmylib07')")); ?></li>
                                                                  <?php } 
                                                                      } ?>
                                                                <li>
                                                                        <?php echo $html->link(__($this->Session->read('territory').' Top 10', true), array('controller' => 'homes', 'action' =>'us_top_10'),array('class'=>$us_top_css,"id"=>'ustoplib07',"onclick"=>"setUpperNavigation('ustoplib07')")); ?>
                                                                </li>
                                                            </ul>
                                                    </li>  
                                                    <li>
                                                            <?php echo $html->link(__('New Releases', true), array('controller' => 'homes', 'action' => 'new_releases'),array('class'=>$new_releases_css,"id"=>'leftnewrelease07',"onclick"=>"setUpperNavigation('leftnewrelease07')")); ?>
                                                    </li> 
                                            </ul>
                                          <?php if($this->Session->read("patron")){ ?>
                                            <?php if($this->Session->read('library_type') == '2') {
                                                $defaultQueues = $this->requestAction(array('controller' => 'queues', 'action' => 'getDefaultQueues'));
                                            ?>
                                            <ul class="streaming sidebar-nav">
                                                    <h3>Streaming</h3>								
                                                    <?php if(!empty($defaultQueues)){  ?>
                                                    
                                                    <li>
                                                            <a href="javascript:void(0)" class="sidebar-anchor"><?php __('Freegal Playlists'); ?></a>
                                                            <ul class="sidebar-sub-nav">
                                                                <?php foreach($defaultQueues as $key => $value){
                                                                    $fqueuesid = 'leftfqueues_'.$value['QueueList']['queue_id'].'_07';
                                                                    ?>
                                                                    <li><a class="leftfqueuesclass" id="<?=$fqueuesid?>" onclick="setUpperNavigation('<?=$fqueuesid?>')" href="/queuelistdetails/queue_details/<?php echo $value['QueueList']['queue_id'];?>/<?php echo $value['QueueList']['queue_type'];?>/<?php echo base64_encode($value['QueueList']['queue_name']);?>"><?php echo $value['QueueList']['queue_name']; ?></a></li>
                                                                <?php } ?>    
                                                            </ul>
                                                    </li>
                                                    <?php } ?>
                                                    <li>
                                                            <a href="javascript:void(0);" class="sidebar-anchor saved-queue"><?php __('My Playlists'); ?></a>
                                                            <ul class="sidebar-sub-nav">


                                                                    <li><a id="leftnowstreaming07" onclick="setUpperNavigation('leftnowstreaming07')" href="/queuelistdetails/now_streaming"><?php __('Now Streaming'); ?></a></li>
                                                                    <li><a id="leftsavedqueues07" onclick="setUpperNavigation('leftsavedqueues07')" href="/queues/savedQueuesList/<?php echo $this->Session->read("patron"); ?>"><?php __('Create and Store Playlists'); ?></a></li>
                                                            </ul>
                                                    </li>
                                                    <li>
                                                            <a href="/queues/my_streaming_history" class="sidebar-anchor"><?php __('History'); ?></a>
                                                    </li>
                                            </ul>
                                            <?php } ?>
                                            <ul class="my-downloads sidebar-nav"><h3><?php __('My Downloads'); ?></h3>
                                                    <li><?php echo $html->link(__('Downloads', true), array('controller' => 'homes', 'action' => 'my_history'), array('class' => $download_css,"id"=>'leftmyhistory07',"onclick"=>"setUpperNavigation('leftmyhistory07')")); ?></li>
                                                    <?php /*if($libraryInfo['Library']['library_unlimited'] != "1"){ */?>
                                                    <li><?php echo $html->link(__('My Wishlist', true), array('controller' => 'homes', 'action' =>'my_wishlist'), array('class' => $wishlist_css,"id"=>'leftmywishlist07',"onclick"=>"setUpperNavigation('leftmywishlist07')")); ?></li>
                                                    <?php /* } */ ?>     
                                            </ul>
                                           <?php                                                                                             

                                                   /* if($this->Session->read("lId")==602 || $this->Session->read("lId")==85 || $this->Session->read("lId")==486)                                                   
                                                    {                                                         
                                                        ?>    
                                                             <div class="announcements">
                                                                <h4><?php __('Announcements'); ?></h4>
                                                                <div class="poll1" style="display:block;height:350px;">                                                                                                                                                                       
                                                                 Register for the Great Fall Concert Ticket Giveaway<br><br>
                                                                 One entry only<br><br>
                                                                 <?php echo $html->link(__('More Info', true), array('controller' => 'registerconcerts','action'=>'great_fall_concert'));?><br> 
                                                                 
                                                                 <?php                                                                    
                                                                        if($register_concert_id=='') // If User has  not registered for concert
                                                                        {
                                                                  ?>

                                                                <span id="FailureMessage"></span> <br> 
                                                                <form  id="FormRegisterConcert" method="post">
                                                                    <label for="UserEmail">First Name :</label>
                                                                    <?php echo $this->Form->input('first_name', array('label' => false, 'div' => false, 'style' => 'width:120px; padding:4px 6px 2px 0px;') ); ?> <br><br>
                                                                    <label for="UserEmail">Last Name :</label>
                                                                    <?php echo $this->Form->input('last_name', array('label' => false, 'div' => false, 'style' => 'width:120px; padding:4px 2px 2px 0px; float:right;') ); ?> <br><br><br><br>                                                                  
                                                                    <!-- <label for="UserEmail">Library Card :</label> -->
                                                                    <?php //echo $this->Form->input('library_card', array('label' => false, 'div' => false, 'style' => 'width:120px; padding:4px 6px 2px 0px;') ); ?>                                                                     
                                                                    <label for="UserEmail">Phone :</label>                                                                    
                                                                    <?php echo $this->Form->input('phone_no', array('label' => false, 'div' => false, 'style' => 'width:120px; padding:4px 6px 2px 0px;') ); ?> <br>    
                                                                    <input type="hidden" name="library_id" value="<?php echo $this->Session->read("lId"); ?>" /><br>
                                                                    <input type="submit" class="save" value="Submit" />
                                                                </form>
                                                                        <?php 
                                                                                $reutrn_message='';
                                                                          }
                                                                          else
                                                                          {
                                                                                $reutrn_message='<br><font style="color:green;">Thanks for entering the Great Fall Concert Ticket Giveaway.</font><br><br>Contest runs Nov 1 - Dec 7, 2013.'; 
                                                                          }
                                                                          ?>
                                                                    <span id="ReturnMessage" ><?php echo $reutrn_message; ?></span>
                                                                
                                                                 </div>
                                                            </div>
                                            
                                            
                                                        <?php
                                                    }
                                                    else    // For other Libraries
                                                    {   */
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
                                                 //  }
                                                    
                                            ?>
                                            <?php } ?>
					</section>					
					<div class="content" style="<?php echo $section_class; ?>">
                                            <span class="ajaxmessage44" id="ajaxflashMessage44"></span>
 
