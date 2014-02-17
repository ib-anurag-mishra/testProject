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

                //History.pushState(null, title, url);
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
               <?php } 
               ?>
                <div class="header-right-col">
                        <div class="row-1 clearfix">
                                <div class="forgot-password">Forgot your password? <a href="#">Click here to reset it.</a></div>
                                <div class="streaming-time-remaining">Streaming Time Remaining 3:00:00</div>
                                <div class="streaming-arrows-icon"></div>
                                <div class="apple-icon"></div>
                                <div class="android-icon"></div>


                        </div>
                        <div class="row-2 clearfix">
                                <div class="download-count-container">
                                        <div class="download-count">0/3</div>
                                        <div class="music-note-icon"></div>
                                </div>

                                <div class="my-account-menu-container">

                                        <button class="my-account-menu">My Account</button>

                                        <ul class="account-menu-dropdown">
                                                <li class="dropdown-item">
                                                        <a href="#" id="notifications">Notifications</a>
                                                </li>
                                                <li class="dropdown-item">
                                                        <a href="#" id="change-password">Change Password</a>
                                                </li>
                                                <li class="dropdown-item">
                                                        <a href="#" id="logout">Logout</a>
                                                </li>
                                        </ul>


                                </div>
                                <a class="browse" href="#">Browse Artist A-Z</a>
                                <div class="master-search-container">
                                        <select name="master-search-select" class="master-search-select">
                                                <option value="all">Search All</option>
                                                <option value="artist">Artist</option>
                                                <option value="album">Album</option>
                                                <option value="genre">Genre</option>
                                                <option value="composer">Composer</option>
                                        </select>
                                        <div class="master-search-field-container">

                                                <input type="search" placeholder="Press enter or go..." class="search-text">
                                                <a class="go" href="#">Go</a>

                                        </div>


                                </div>
                        </div>

                </div>
        </div>
</header>