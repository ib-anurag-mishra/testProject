<script type="text/javascript">

var createLinkThis;
var multi_create;

$(document).ready(function() {
     $("#FormRename").submit(function() {
        var frm = $('#FormRename');
           $.ajax({
               type: "post",
               url: webroot+'queuelistdetails/ajaxQueueValidation',
               data: frm.serialize(),
               success: function (response) { 
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

                 //updating the queuelist
                 $(document).find('.playlist-options-new').find('.playlist-menu').find('li').each(function(){
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
                    if( multi_create )
                    {
                         multiSongCreateNewPlaylist(album_data[1]);
                    }
                    else
                    {
                        addToAlbumTest( album_data[1], this ); 
                     }    
                                          
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

                 var updated_queue_list = '<li><a href="JavaScript:void(0);" onclick="JavaScript:addToAlbumTest('+album_data[1]+', this );" id="'+album_data[1]+'">' +album_data[2] + '</a></li>';
                 $(updated_queue_list).insertAfter( $(document).find('.playlist-options-new').find('.playlist-menu').find('li:eq(0)') );

                //History.pushState(null, title, url);
                return false;
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
		<header>
			Rename '<span>Playlist Name</span>'
		</header>
		<form id="FormRename" action="#">
			<div class="rename-form-container">
				<label id="RenameQueueMessage"></label> <label for="name">Name:</label>
				<?php echo $this->Form->input('QueueList.queue_name', array('label' => false, 'div' => false, 'id' => 'name') ); ?>
				<label for="description">Description:</label>
				<?php echo $this->Form->input('QueueList.description', array('label' => false, 'div' => false, 'id' => 'description') ); ?>
				<input type="hidden" id="rqPlid" name="rqPlid" value="" /> <input
					type="hidden" name="hid_action" value="rename_queue" />
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
		<form id="FormDelete" action="#">
			<div class="rename-form-container">
				<label id="CreateQueueMessage"></label> <label for="name">Name:</label>
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
				<p>
					Are you sure you want to delete '<span>Queue Name</span>'?
				</p>
			</div>
			<div class="buttons-container clearfix">
				<div class="text-close">Close</div>
				<input type="hidden" name="hid_action" value="delete_queue" /> <input
					type="hidden" id="dqPlid" name="dqPlid" value="" /> <input
					type="submit" class="save" value="Delete Playlist" />
			</div>
		</form>
	</div>

	<div class="playlist-options-test" style="display: none;">
		<?php
		print_r( $this->Queue->getUserQueuesList($this->Session->read('patron')) );
		?>
	</div>

	<div class="playlist-options-new" style="display: none;">
		<?php
		print_r( $this->Queue->getUserQueuesListNew($this->Session->read('patron')) );
		?>
	</div>
</div>
<div class="wrapper">
	<!-- site header -->
	<header class="site-header">
		<div class="inner-wrapper">
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
			<div style="width: 350px;">
				<a href="<?php echo $libraryInfo['Library']['library_home_url']; ?>"
					target="_blank"><img style="max-width: 100%; padding-top: 20px;"
					src="<?php echo str_replace("test","prod",$cdnPath); ?>libraryimg/<?php echo $libraryInfo['Library']['library_image_name']; ?>"
					alt="<?php echo $libraryInfo['Library']['library_name']; ?>"
					title="<?php echo $libraryInfo['Library']['library_name']; ?>"
					style="padding-top: 20px;"> </a>
			</div>
			<?php
			}else{
				?>
			<div style="width: 350px;">
				<img style="max-width: 100%; padding-top: 20px;"
					src="<?php echo str_replace("test","prod",$cdnPath); ?>libraryimg/<?php echo $libraryInfo['Library']['library_image_name']; ?>"
					alt="<?php echo $libraryInfo['Library']['library_name']; ?>"
					title="<?php echo $libraryInfo['Library']['library_name']; ?>"
					style="padding-top: 20px;" />
			</div>
			<?php
			}
			?>
			<?php
				} elseif(!$libraryInfo['Library']['show_library_name']) {
					if($libraryInfo['Library']['library_home_url'] != "") {
						?>
			<div style="padding-top: 45px;">
				<a href="<?php echo $libraryInfo['Library']['library_home_url']; ?>"
					target="_blank"><?php echo $libraryInfo['Library']['library_name']; ?>
				</a>
			</div>
			<?php
                                            } else { ?>
			<div style="padding-top: 45px;">
				<?php echo $libraryInfo['Library']['library_name']; ?>
			</div>
			<?php
                                            }
				} else {
					?>
			<h1 class="logo"
				style="width: 350px; height: 108px; position: absolute; left: 0; top: 0;">
				<a href="/homes/index"><img
					src="<? echo $this->webroot; ?>app/webroot/img/logo.png" alt="logo"
					width="157" height="108"> </a>
			</h1>
			<?php }
			} else {
				?>
			<h1 class="logo"
				style="width: 350px; height: 108px; position: absolute; left: 0; top: 0;">
				<a href="/homes/index"><img
					src="<? echo $this->webroot; ?>app/webroot/img/logo.png" alt="logo"
					width="157" height="108" /> </a>
			</h1>
			<?php } ?>
			<div class="header-right-col" style="right: 10px;">
				<div class="row-1 clearfix">
					<?php if(!$this->Session->read("patron")){ 
                                                            if($libraryInfo['Library']['library_authentication_method'] == "user_account"){?>
					<div class="forgot-password">
						Forgot your password? <a href="/homes/forgot_password">Click here
							to reset it.</a>
					</div>
					<?php }  
					}else if($this->Session->read("patron")){
                                                                if($libraryInfo['Library']['library_authentication_method'] == "user_account"){?>
					<div class="forgot-password">
						Need to change your password? <a href="/users/my_account">Click
							here to reset it.</a>
					</div>
					<?php    }
					}
					if($this->Session->read("patron")){
						$maxStreamTime    =   $libraryInfo['Library']['library_streaming_hours']*60*60;

						if($this->Session->read('library_type')==2 && $libraryInfo['Library']['library_streaming_hours']==24)

						{
							$streamTime = 'UNLIMITED';
							$libraryunlimited = 1;

						}else if($this->Session->read('library_type')==2){
							$libraryunlimited = 0;

							$lastStreamedDate   =   $this->Streaming->getLastStreamDate($this->Session->read('library'),$this->Session->read('patron'));
							$todaysDate         =   date("Y-m-d");

							if(strtotime(date("Y-m-d",strtotime($lastStreamedDate))) != strtotime(date('Y-m-d'))) // if Patron Logs in for first time in day
							{
								$streamTime =   $maxStreamTime;
							}
							else
							{
								$streamTime = $this->Streaming->getTotalStreamTime($this->Session->read('library'),$this->Session->read('patron'));

								if(empty($streamTime))      // if there is no record of patron in streaming_records table i.e. user is streaming for first time
								{
									$streamTime =   $maxStreamTime;
								}
								else    // if user has streamed one or more time
								{
									$streamTime = ($maxStreamTime - $this->Streaming->getTotalStreamTime($this->Session->read('library'),$this->Session->read('patron')));
								}
							}

							$streamTime =   gmdate("H:i:s", $streamTime);
						}
						?>
					<span id="hid_library_unlimited" style="display: none;"><?php echo $libraryunlimited; ?>
					</span>
					<?php if($this->Session->read('library_type')==2){ ?>
					<div class="streaming-time-remaining">
						Streaming Time Remaining:&nbsp;</span><span
							id="remaining_stream_time"><?php echo $streamTime; ?> </span>
					</div>
					<?php
						}
						//  Hidden variable to be used in site.js for alerting user before video download

						if(($downloadCount+1)<$libraryInfo['Library']['library_user_download_limit'])
						{
							?>
					<input type="hidden" name="hid_VideoDownloadStatus"
						id="hid_VideoDownloadStatus" value="1" />
					<?php
						}
						else
						{
							?>
					<input type="hidden" name="hid_VideoDownloadStatus"
						id="hid_VideoDownloadStatus" value="0" />
					<?php
                                                            } ?>
					<?php if($this->Session->read('library_type')==2){ ?>
					<div class="streaming-arrows-icon"></div>
					<?php }
                                              } ?>
				</div>
				<div class="row-2 clearfix">
					<?php if($this->Session->read("patron")){
						$class = ' logged-in';
						?>
					<div class="download-count-container">
						<div class="download-count"><span id='downloads_used'><?php echo $downloadCount; ?></span>/<?php echo $libraryInfo['Library']['library_user_download_limit']; ?></div>
						<div class="music-note-icon"></div>
					</div>
					<?php } ?>
					<div class="my-account-menu-container<?php echo isset( $class ) ? $class : ''; ?>">
						<?php if($this->Session->read("patron")){  ?>
						<button class="my-account-menu">My Account</button>

						<ul class="account-menu-dropdown">
							<?php 
                                                                if($isLibaryExistInTimzone ==1){ ?>
							<li class="dropdown-item"><a href="/users/manage_notification"
								id="notifications">Notifications</a>
							</li>
							<?php }   
                                                                if($libraryInfo['Library']['library_authentication_method'] == "user_account"){?>
							<li class="dropdown-item"><a href="/users/my_account"
								id="change-password">Change Password</a>
							</li>
							<?php } ?>
							<li class="dropdown-item"><?php echo $html->link(__('Logout', true), array('controller' => 'users', 'action' =>'logout'),array('class' =>'no-ajaxy','id' => 'logout'));?>
							</li>
						</ul>
						<?php } else { 
							$library = substr($_SERVER['HTTP_HOST'],0,strpos($_SERVER['HTTP_HOST'],'.'));
							if($library != 'www' && $library != 'freading' && $library != '50'){
								echo $html->link(__('Login', true), array('controller' => 'users', 'action' => 'redirection_manager'),array('class' => 'login'));
							} else {
								echo $html->link(__('Login', true), array('controller' => 'homes', 'action' => 'chooser'),array('class' => 'login'));
							}
                                                     } ?>
					</div>
					<?php echo $html->link(__('Browse A-Z', true), array('controller' => 'genres', 'action' =>'view'),array('class' => 'browse')); ?>
					<div class="master-search-container">
						<form class="search" name="search" id="HomeSearchForm"
							method="get" action="/search/index" accept-charset="utf-8"
							onsubmit="ajaxSearch(); return false;">
							<div class="select-arrow-ie8"></div>
							<div class="select-arrow-fix">
								<select name="type" id="master-filter"
									class="master-search-select">
									<option value="all">Search All</option>
									<option value="album">Albums</option>
									<option value="artist">Artists</option>
									<option value="composer">Composers</option>
									<option value="genre">Genres</option>
									<option value="song">Songs</option>
									<option value="video">Videos</option>
								</select>
							</div>

							<div class="master-search-field-container">
								<input type="text" placeholder="Press enter or go..."
									class="search-text" id="search-text" name="q"> <a class="go"
									href="javascript:void(0)" id="headerSearchSubmit">Go</a>
							</div>
						</form>
					</div>
					<div class="master-music-search-results">
						<ul>
							<li>
								<div class="master-search-results-image">
									<img
										src="<? echo $this->webroot; ?>app/webroot/img/master_music_search_results/adele.jpg"
										alt="adele">
								</div>
								<div class="master-search-results-detail">
									<p class="song-album-info">
										<span class="album-title"><a href="javascript:void(0)">21</a>
										</span><span class="song-title"><a href="javascript:void(0)"></a>
										</span>
									</p>
									<p class="artist">
										<a href="javascript:void(0)">Adele</a>
									</p>
								</div>
							</li>
							<li>
								<div class="master-search-results-image">
									<img
										src="<? echo $this->webroot; ?>app/webroot/img/master_music_search_results/pitbull.jpg"
										alt="pitbull">
								</div>
								<div class="master-search-results-detail">
									<p class="song-album-info">
										<span class="album-title"><a href="javascript:void(0)"></a> </span><span
											class="song-title"><a href="javascript:void(0)">Mr. Worldwide</a>
										</span>
									</p>
									<p class="artist">
										<a href="javascript:void(0)">Pitbull</a>
									</p>
								</div>
							</li>
							<li>
								<div class="master-search-results-image">
									<img
										src="<? echo $this->webroot; ?>app/webroot/img/master_music_search_results/carrie-underwood.jpg"
										alt="carrie-underwood">
								</div>
								<div class="master-search-results-detail">
									<p class="song-album-info">
										<span class="album-title"><a href="javascript:void(0)"></a> </span><span
											class="song-title"><a href="javascript:void(0)">Before He
												Cheats</a> </span>
									</p>
									<p class="artist">
										<a href="javascript:void(0)">Carrie Underwood</a>
									</p>
								</div>
							</li>
							<li>
								<div class="master-search-results-image">
									<img
										src="<? echo $this->webroot; ?>app/webroot/img/master_music_search_results/kelly-clarkson.jpg"
										alt="kelly-clarkson">
								</div>
								<div class="master-search-results-detail">
									<p class="song-album-info">
										<span class="album-title"><a href="javascript:void(0)"></a> </span><span
											class="song-title"><a href="javascript:void(0)">All I Ever
												Wanted</a> </span>
									</p>
									<p class="artist">
										<a href="javascript:void(0)">Kelly Clarkson</a>
									</p>
								</div>
							</li>
							<li>
								<div class="master-search-results-image">
									<img
										src="<? echo $this->webroot; ?>app/webroot/img/master_music_search_results/michael-jackson.jpg"
										alt="michael-jackson">
								</div>
								<div class="master-search-results-detail">
									<p class="song-album-info">
										<span class="album-title"><a href="javascript:void(0)">Thriller</a>
										</span><span class="song-title"><a href="javascript:void(0)"></a>
										</span>
									</p>
									<p class="artist">
										<a href="javascript:void(0)">Michael Jackson</a>
									</p>
								</div>
							</li>
						</ul>
					</div>
				</div>

			</div>
			<?php if($this->Session->read("patron")){ ?>
			<div class="plays-tooltip">The download usage counter is located in
				the upper right corner of freegalmusic.com displaying your weekly
				allotment. For instance, 1/3 means that you have a weekly limit of 3
				downloads, and you have used 1 of those downloads. The download
				counter resets each week at Monday 12:01 AM (Eastern Time, USA).</div>
			<?php  } ?>
		</div>
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
        <div class="inner-wrapper">
    		<ul class="clearfix">
    			<li class="regular"><?php echo $html->link(__('Home', true), array('controller' => 'homes','action'=>'index'), array("class"=>$newsCss,"id"=>'home07',"onclick"=>"setUpperNavigation('home07')"));?>
    			</li>
    			<li class="regular"><?php echo $html->link(__('Music Videos', true), array('controller' => 'videos', 'action' =>'index'), array("class"=>$videoCss,"id"=>'musicVideo07',"onclick"=>"setUpperNavigation('musicVideo07')")); ?>
    			</li>
    			<li class="most-popular"><?php if($subdomains !== '' && $subdomains != 'www' && $subdomains != 'freegalmusic'){ 
    				echo $html->link(__('Most Popular', true), array('controller' => 'homes', 'action' =>'my_lib_top_10'), array("id"=>'topmylib07' ,"onclick"=>"setUpperNavigation('topmylib07')"));
    			} else { if($this->Session->read("patron")){
    				echo $html->link(__('Most Popular', true), array('controller' => 'homes', 'action' =>'my_lib_top_10'), array("id"=>'topmylib07',"onclick"=>"setUpperNavigation('topmylib07')"));
    			} else { echo $html->link(__('Most Popular', true), array('controller' => 'homes', 'action' =>'us_top_10'), array("id"=>'topustop07',"onclick"=>"setUpperNavigation('topustop07')"));
    			}
    			} ?>
    			</li>
    			<li class="regular"><?php echo $html->link(__('New Releases', true), array('controller' => 'homes', 'action' =>'new_releases'), array("class"=>$newReleaseCss,"id"=>'newsRelease07',"onclick"=>"setUpperNavigation('newsRelease07')")); ?>
    			</li>
    			<li class="regular"><?php echo $html->link(__('Genres', true), array('controller' => 'genres', 'action' =>'view'), array("class"=>$genreCss,"id"=>'genre07',"onclick"=>"setUpperNavigation('genre07')")); ?>
    			</li>
    			<li class="regular"><?php echo $html->link(__('FAQ', true), array('controller' => 'questions', 'action' =>'index'), array("class"=>$faqCss,"id"=>'faq07',"onclick"=>"setUpperNavigation('faq07')")); ?>
    			</li>
    		</ul>

    		<div class="most-popular-sub-nav">
    			<?php if($subdomains !== '' && $subdomains != 'www' && $subdomains != 'freegalmusic'){ ?>
    			<div>
    				<?php echo $html->link(__('My Lib Top 10', true), array('controller' => 'homes', 'action' =>'my_lib_top_10'), array("id"=>'topmylib07',"onclick"=>"setUpperNavigation('topmylib07')")); ?>
    			</div>
    			<?php } else {
                                        if($this->Session->read("patron")){ ?>
    			<div>
    				<?php echo $html->link(__('My Lib Top 10', true), array('controller' => 'homes', 'action' =>'my_lib_top_10'), array("id"=>'topmylib07',"onclick"=>"setUpperNavigation('topmylib07')")); ?>
    			</div>
    			<?php   } 
                                      } ?>
    			<div>
    				<?php echo $html->link(__($this->Session->read('territory').' Top 10', true), array('controller' => 'homes', 'action' =>'us_top_10'), array("id"=>'topustop07',"onclick"=>"setUpperNavigation('topustop07')")); ?>
    			</div>
    		</div>
        </div>
	</nav>
	<div class="gradient-border"></div>

	<div class="top-sub-nav"></div>

	<?php

	$music_videos_css = "sidebar-anchor";
	$my_lib_css = "sidebar-anchor";
	$us_top_css= "sidebar-anchor";
	$download_css = "sidebar-anchor";
	$wishlist_css = "sidebar-anchor";
	$new_releases_css = "sidebar-anchor";
	$ul_class = "sidebar-sub-nav";
	$section_class = "";

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

		<section class="left-nav">
			<?php if($this->Session->read("patron")){ ?>
			<?php if($this->Session->read('library_type') == '2') {
				$defaultQueues = $this->requestAction(array('controller' => 'queues', 'action' => 'getDefaultQueues'));
				?>
			<div class="streaming">
				<h2>Streaming</h2>
				<ul>
					<?php if(!empty($defaultQueues)){  ?>

					<li><a href="javascript:void(0)" class="sidebar-anchor"><?php __('Freegal Playlists'); ?>
					</a>
						<ul class="stream-sidebar-sub-nav">
							<?php foreach($defaultQueues as $key => $value){
								$fqueuesid = 'leftfqueues_'.$value['QueueList']['queue_id'].'_07';
								?>
							<li><a class="leftfqueuesclass" id="<?=$fqueuesid?>"
								onclick="setUpperNavigation('<?=$fqueuesid?>')"
								href="/queuelistdetails/queue_details/<?php echo $value['QueueList']['queue_id'];?>/<?php echo $value['QueueList']['queue_type'];?>/<?php echo base64_encode($value['QueueList']['queue_name']);?>"><?php echo $value['QueueList']['queue_name']; ?>
							</a></li>
							<?php } ?>
						</ul>
					</li>
					<?php } ?>
					<li><a href="javascript:void(0);"
						class="sidebar-anchor saved-queue"><?php __('My Playlists'); ?> </a>
						<ul class="queue-sidebar-sub-nav">


							<li><a id="leftnowstreaming07"
								onclick="setUpperNavigation('leftnowstreaming07')"
								href="/queuelistdetails/now_streaming"><?php __('Now Streaming'); ?>
							</a></li>
							<li><a id="leftsavedqueues07"
								onclick="setUpperNavigation('leftsavedqueues07')"
								href="/queues/savedQueuesList/<?php echo $this->Session->read("patron"); ?>"><?php __('Create and Store Playlists'); ?>
							</a></li>
						</ul>
					</li>
					<li><a href="/queues/my_streaming_history" class="sidebar-anchor"><?php __('History'); ?>
					</a>
					</li>
				</ul>
			</div>
			<?php } ?>
			<div class="my-downloads">
				<h2>
					<?php __('My Downloads'); ?>
				</h2>
				<ul>
					<li><?php echo $html->link(__('Downloads', true), array('controller' => 'homes', 'action' => 'my_history'), array('class' => $download_css,"id"=>'leftmyhistory07',"onclick"=>"setUpperNavigation('leftmyhistory07')")); ?>
					</li>
					<li><?php echo $html->link(__('My Wishlist', true), array('controller' => 'homes', 'action' =>'my_wishlist'), array('class' => $wishlist_css,"id"=>'leftmywishlist07',"onclick"=>"setUpperNavigation('leftmywishlist07')")); ?>
					</li>
				</ul>
			</div>
			<?php } ?>
			<?php                                                                                             
			$temp_text  =   strip_tags($announcment_value);

			if($temp_text!="")
			{
				$announcment_class  =   "display:block;";
			}
			else
			{
				$announcment_class  =   "";
			}

			?>
			<div class="announcements">
				<h2>
					<?php __('Announcements'); ?>
				</h2>
				<div class="announcement" style="<?php echo $announcment_class; ?>">
					<?php echo $announcment_value; ?>
				</div>
			</div>
                        <?php 
                        if(!empty($movieAnnouncmentValue[0]['announcements'])) { 

                            $hostName = $_SERVER['SERVER_NAME'];
                            $domain = explode('.',$hostName);
                            $i = 0;
                        ?>
                            <div class="movie-announcements">
                                 <p style="margin-bottom:5px; border-bottom: 1px solid #000;padding-bottom: 3px; font-weight:bold"> 
                                    Did you know?
                                  </p>
                                  <p style="margin-bottom:5px; border-bottom: 1px solid #000;padding-bottom: 3px;">Freegal is also a movie service!</p>
                                  <p>Stream top movies like:</p> 
                                 <?php foreach($movieAnnouncmentValue as $value) { 
                                            $i++;
                                  ?>   
                                <p><a class="announcments-movie-titles" href="http://<?php echo $domain[0].'.'.Configure::read('App.MoviesPath').'/videos/index/'.$value['announcements']['video_id']; ?>" target ="_blank">
                                    <?php echo $value['announcements']['title']; ?>
                                </a></p>
                                 <?php /*if($i == 1){
                                            echo "and";
                                        }*/
                                  } ?>
                                 
                                 <p><a class="announcments-movie-cta" href="http://<?php echo $domain[0].'.'.Configure::read('App.MoviesPath').'/users/redirection_manager'; ?>" target ="_blank">Click here</a> to log in.</p>
                            </div>
                        <?php } ?> 
		</section>
		<div class="content" style="<?php echo $section_class; ?>">
			<span class="ajaxmessage44" id="ajaxflashMessage44"></span>