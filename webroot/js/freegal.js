//jQuery(document).ready(function() {
//	jQuery('#slideshow').cycle({
//		fx: 'fade',
//		sync: 0,
//		speed: 'slow',
//		delay: -8000,
//		timeout: 12000,
//		random: 1
//	});
//});
//
//jQuery(document).ready(function() {
//	jQuery('#featured_artist').cycle({
//		fx: 'fade',
//		sync: 0,
//		speed: 'slow',
//		delay: -4000,
//		timeout: 12000,
//		random: 1
//	});
//});
//
//jQuery(document).ready(function() {
//	jQuery('#newly_added').cycle({
//		fx: 'fade',
//		sync: 0,
//		speed: 'slow',
//		delay: -2000,
//		timeout: 12000,
//		random: 1
//	});
//});

//Disable right mouse click Script
//By Maximus (maximus@nsimail.com) w/ mods by DynamicDrive
//For full source code, visit http://www.dynamicdrive.com

var message="Function Disabled!";

///////////////////////////////////
function clickIE4() {
    if (event.button==2){
        return false;
    }
}

function clickNS4(e) {
    if (document.layers||document.getElementById&&!document.all) {
        if (e.which==2||e.which==3) {
            return false;
        }
    }
}

if (document.layers) {
    document.captureEvents(Event.MOUSEDOWN);
    document.onmousedown=clickNS4;
}
//else if (document.all&&!document.getElementById()) {
//    document.onmousedown=clickIE4;
//}

document.oncontextmenu=new Function("return false");
var id;
function userDownloadIE(prodId)
{
	$('.beforeClick').hide();
	$('.afterClick').show();		
	document.getElementById('download_loader_'+prodId).style.display = 'block';
	document.getElementById('downloading_'+prodId).style.display = 'block';
	document.getElementById('song_'+prodId).style.display = 'none';
	var data = "prodId="+prodId;
	id = prodId;
	jQuery.ajax({
		type: "post",  // Request method: post, get
		url: webroot+"homes/userDownload", // URL to request
		data: data,  // post data
		success: function(response) {
			var msg = response.substring(0,5);
			if(msg == 'error')
			{
				alert("Your download limit has exceeded.");
				location.reload();
				return false;
			}
			else if(msg == 'incld')
			{
				alert("You have already downloaded this song.Get it from your recent downloads");
				document.getElementById('download_loader_'+prodId).style.display = 'none';
				document.getElementById('downloading_'+prodId).style.display = 'none';
				document.getElementById('song_'+prodId).innerHTML='';
				if(languageSet == 'en'){
					document.getElementById('song_'+prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";	
				}else{
					document.getElementById('song_'+prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
				}
				document.getElementById('song_'+prodId).style.display = 'block';
				addQtip_top(prodId);
				$('.afterClick').hide();
				$('.beforeClick').show();				
				return false;
			}			
			else if(msg == 'suces')
			{
				$('.afterClick').hide();
				$('.beforeClick').show();
				var downloadUsedArr = response.split('|');		
				document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
				document.getElementById('download_loader_'+prodId).style.display = 'none';
				document.getElementById('downloading_'+prodId).style.display = 'none';
				document.getElementById('song_'+prodId).innerHTML='';
				if(languageSet == 'en'){
					document.getElementById('song_'+prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
				}else{
					document.getElementById('song_'+prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
				}
				document.getElementById('song_'+prodId).style.display = 'block';
				addQtip(prodId);
			}
			else
			{
				alert("You have been logged out from the system. Please login again.");
				location.reload();
				return false;
			}
		},
		error:function (XMLHttpRequest, textStatus, errorThrown) {}
	});
	return false;
}
function changeLang(type){ //alert("http://jeffersonlibrary.freegaldev.com/"+webroot+"homes/language");
	var language = type;
	var data = "lang="+language;
	$.ajax({ 
		type: "post",  // Request method: post, get
		url: webroot+"homes/language", // URL to request
		data: data,  // post data
		success: function(response) { //alert("in js"+webroot);
			var msg = response.substring(0,5);
			if(msg == 'error')
			{
				alert("There was an error while saving your request.");
				location.reload();
				return false;
			}
			else
			{
				location.reload();
			}
		},
		error:function (XMLHttpRequest, textStatus, errorThrown) {}
	});
}

function userDownloadOthers(prodId,downloadUrl1,downloadUrl2,downloadUrl3)
{
	$('.beforeClick').hide();
	$('.afterClick').show();
	document.getElementById('downloading_'+prodId).style.display = 'block';
	document.getElementById('song_'+prodId).style.display = 'none';
	document.getElementById('download_loader_'+prodId).style.display = 'block';
	var finalURL = downloadUrl1;
	finalURL += downloadUrl2;
	finalURL += downloadUrl3;
	var data = "prodId="+prodId;
	id = prodId;
	jQuery.ajax({
		type: "post",  // Request method: post, get
		url: webroot+"homes/userDownload", // URL to request
		data: data,  // post data
		success: function(response) {
			var msg = response.substring(0,5);
			if(msg == 'error')
			{
				alert("Your download limit has exceeded.");
				location.reload();
				return false;
			}
			else if(msg == 'incld')
			{
				alert("You have already downloaded this song.Get it from your recent downloads");
				document.getElementById('download_loader_'+prodId).style.display = 'none';
				document.getElementById('downloading_'+prodId).style.display = 'none';
				document.getElementById('song_'+prodId).innerHTML='';
				if(languageSet == 'en'){
					document.getElementById('song_'+prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";	
				}else{
					document.getElementById('song_'+prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
				}
				document.getElementById('song_'+prodId).style.display = 'block';
				addQtip_top(prodId);
				$('.afterClick').hide();
				$('.beforeClick').show();				
				return false;
			}			
			else if(msg == 'suces')
			{		
				var downloadUsedArr = response.split('|');		
				document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
				document.getElementById('download_loader_'+prodId).style.display = 'none';
				document.getElementById('downloading_'+prodId).style.display = 'none';
				document.getElementById('song_'+prodId).innerHTML='';
				if(languageSet == 'en'){
					document.getElementById('song_'+prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
				}else{
					document.getElementById('song_'+prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
				}
				document.getElementById('song_'+prodId).style.display = 'block';
				addQtip(prodId);
				location.href = unescape(finalURL);
				$('.afterClick').hide();
				$('.beforeClick').show();				
			}
			else
			{
				alert("You have been logged out from the system. Please login again.");
				location.reload();
				return false;
			}
		},
		error:function (XMLHttpRequest, textStatus, errorThrown) {}
	});
	return false;
}

function userDownloadIE_top(prodId)
{
	$('.beforeClick').hide();
	$('.afterClick').show();		
	document.getElementById('download_loader_'+prodId).style.display = 'block';
	document.getElementById('downloading_'+prodId).style.display = 'block';
	document.getElementById('song_'+prodId).style.display = 'none';
	var data = "prodId="+prodId;
	id = prodId;
	jQuery.ajax({
		type: "post",  // Request method: post, get
		url: webroot+"homes/userDownload", // URL to request
		data: data,  // post data
		success: function(response) {
			var msg = response.substring(0,5);
			if(msg == 'error')
			{
				alert("Your download limit has exceeded.");
				location.reload();
				return false;
			}			
			else if(msg == 'incld')
			{
				alert("You have already downloaded this song.Get it from your recent downloads");
				document.getElementById('download_loader_'+prodId).style.display = 'none';
				document.getElementById('downloading_'+prodId).style.display = 'none';
				document.getElementById('song_'+prodId).innerHTML='';
				if(languageSet == 'en'){
					document.getElementById('song_'+prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
				}else{
					document.getElementById('song_'+prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
				}				
				document.getElementById('song_'+prodId).style.display = 'block';
				addQtip_top(prodId);
				$('.afterClick').hide();
				$('.beforeClick').show();				
				return false;
			}			
			else if(msg == 'suces')
			{
				$('.afterClick').hide();
				$('.beforeClick').show();
				var downloadUsedArr = response.split('|');		
				document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
				document.getElementById('download_loader_'+prodId).style.display = 'none';
				document.getElementById('downloading_'+prodId).style.display = 'none';
				document.getElementById('song_'+prodId).innerHTML='';
				if(languageSet == 'en'){
					document.getElementById('song_'+prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
				}else{
					document.getElementById('song_'+prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
				}
				document.getElementById('song_'+prodId).style.display = 'block';
				addQtip_top(prodId);

			}
			else
			{
				alert("You have been logged out from the system. Please login again.");
				location.reload();
				return false;				
			}
		},
		error:function (XMLHttpRequest, textStatus, errorThrown) {}
	});
	return false;
}


function userDownloadAll(prodId)
{
	$('.beforeClick').hide();
	$('.afterClick').show();
	document.getElementById('downloading_'+prodId).style.display = 'block';
	document.getElementById('song_'+prodId).style.display = 'none';
	document.getElementById('download_loader_'+prodId).style.display = 'block';	
	$('#form'+prodId).submit();
	setTimeout("location.reload(true)", 7000);
}

function userDownloadOthers_top(prodId,downloadUrl1,downloadUrl2,downloadUrl3)
{
	$('.beforeClick').hide();
	$('.afterClick').show();
	document.getElementById('downloading_'+prodId).style.display = 'block';
	document.getElementById('song_'+prodId).style.display = 'none';
	document.getElementById('download_loader_'+prodId).style.display = 'block';
	var finalURL = downloadUrl1;
	finalURL += downloadUrl2;
	finalURL += downloadUrl3;
	var data = "prodId="+prodId;
	id = prodId;
	jQuery.ajax({
		type: "post",  // Request method: post, get
		url: webroot+"homes/userDownload", // URL to request
		data: data,  // post data
		success: function(response) {
			var msg = response.substring(0,5);
			if(msg == 'error')
			{
				alert("Your download limit has exceeded.");
				location.reload();
				return false;
			}			
			else if(msg == 'incld')
			{
				alert("You have already downloaded this song.Get it from your recent downloads");
				document.getElementById('download_loader_'+prodId).style.display = 'none';
				document.getElementById('downloading_'+prodId).style.display = 'none';
				document.getElementById('song_'+prodId).innerHTML='';
				if(languageSet == 'en'){
					document.getElementById('song_'+prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";	
				}else{
					document.getElementById('song_'+prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
				}
				document.getElementById('song_'+prodId).style.display = 'block';
				addQtip_top(prodId);
				$('.afterClick').hide();
				$('.beforeClick').show();				
				return false;
			}
			else if(msg == 'suces')
			{	
				var downloadUsedArr = response.split('|');		
				document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
				document.getElementById('download_loader_'+prodId).style.display = 'none';
				document.getElementById('downloading_'+prodId).style.display = 'none';
				document.getElementById('song_'+prodId).innerHTML='';
				if(languageSet == 'en'){
					document.getElementById('song_'+prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";	
				}else{
					document.getElementById('song_'+prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
				}
				document.getElementById('song_'+prodId).style.display = 'block';
				addQtip_top(prodId);
				location.href = unescape(finalURL);
				$('.afterClick').hide();
				$('.beforeClick').show();				
			}
			else
			{
				alert("You have been logged out from the system. Please login again.");
				location.reload();
				return false;				
			}			
		},
		error:function (XMLHttpRequest, textStatus, errorThrown) {}
	});
	return false;
}

function userDownloadIE_toptab(prodId)
{
	$('.beforeClick').hide();
	$('.afterClick').show();		
	document.getElementById('download_loader_'+prodId).style.display = 'block';
	document.getElementById('downloading_'+prodId).style.display = 'block';
	document.getElementById('songtab_'+prodId).style.display = 'none';
	var data = "prodId="+prodId;
	id = prodId;
	jQuery.ajax({
		type: "post",  // Request method: post, get
		url: webroot+"homes/userDownload", // URL to request
		data: data,  // post data
		success: function(response) {
			var msg = response.substring(0,5);
			if(msg == 'error')
			{
				alert("Your download limit has exceeded.");
				location.reload();
				return false;
			}	
			else if(msg == 'incld')
			{
				alert("You have already downloaded this song.Get it from your recent downloads");
				document.getElementById('download_loader_'+prodId).style.display = 'none';
				document.getElementById('downloading_'+prodId).style.display = 'none';
				document.getElementById('songtab_'+prodId).innerHTML='';
				if(languageSet == 'en'){
					document.getElementById('songtab_'+prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
				}else{
					document.getElementById('songtab_'+prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
				}				
				document.getElementById('songtab_'+prodId).style.display = 'block';
				addQtip_toptab(prodId);
				$('.afterClick').hide();
				$('.beforeClick').show();				
				return false;
			}			
			else if(msg == 'suces')
			{
				$('.afterClick').hide();
				$('.beforeClick').show();
				var downloadUsedArr = response.split('|');		
				document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
				document.getElementById('download_loader_'+prodId).style.display = 'none';
				document.getElementById('downloading_'+prodId).style.display = 'none';
				document.getElementById('songtab_'+prodId).innerHTML='';
				if(languageSet == 'en'){
					document.getElementById('songtab_'+prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
				}else{
					document.getElementById('songtab_'+prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
				}
				document.getElementById('songtab_'+prodId).style.display = 'block';
				addQtip_toptab(prodId);

			}
			else
			{
				alert("You have been logged out from the system. Please login again.");
				location.reload();
				return false;				
			}
		},
		error:function (XMLHttpRequest, textStatus, errorThrown) {}
	});
	return false;
}

function userDownloadOthers_toptab(prodId,downloadUrl1,downloadUrl2,downloadUrl3)
{
	$('.beforeClick').hide();
	$('.afterClick').show();
	document.getElementById('downloading_'+prodId).style.display = 'block';
	document.getElementById('songtab_'+prodId).style.display = 'none';
	document.getElementById('download_loader_'+prodId).style.display = 'block';
	var finalURL = downloadUrl1;
	finalURL += downloadUrl2;
	finalURL += downloadUrl3;
	var data = "prodId="+prodId;
	id = prodId;
	jQuery.ajax({
		type: "post",  // Request method: post, get
		url: webroot+"homes/userDownload", // URL to request
		data: data,  // post data
		success: function(response) {
			var msg = response.substring(0,5);
			if(msg == 'error')
			{
				alert("Your download limit has exceeded.");
				location.reload();
				return false;
			}		
			else if(msg == 'incld')
			{
				alert("You have already downloaded this song.Get it from your recent downloads");
				document.getElementById('download_loader_'+prodId).style.display = 'none';
				document.getElementById('downloading_'+prodId).style.display = 'none';
				document.getElementById('songtab_'+prodId).innerHTML='';
				if(languageSet == 'en'){
					document.getElementById('songtab_'+prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
				}else{
					document.getElementById('songtab_'+prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
				}
				document.getElementById('songtab_'+prodId).style.display = 'block';
				addQtip_toptab(prodId);
				$('.afterClick').hide();
				$('.beforeClick').show();					
				return false;
			}			
			else if(msg == 'suces')
			{		
				var downloadUsedArr = response.split('|');		
				document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
				document.getElementById('download_loader_'+prodId).style.display = 'none';
				document.getElementById('downloading_'+prodId).style.display = 'none';
				document.getElementById('songtab_'+prodId).innerHTML='';
				if(languageSet == 'en'){
					document.getElementById('songtab_'+prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
				}else{
					document.getElementById('songtab_'+prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
				}				
				document.getElementById('songtab_'+prodId).style.display = 'block';
				addQtip_toptab(prodId);
				location.href = unescape(finalURL);
				$('.afterClick').hide();
				$('.beforeClick').show();				
			}
			else
			{
				alert("You have been logged out from the system. Please login again.");
				location.reload();
				return false;				
			}
		},
		error:function (XMLHttpRequest, textStatus, errorThrown) {}
	});
	return false;
}


function userDownloadOthers_safari(prodId,downloadUrl1,downloadUrl2,downloadUrl3)
{
	$('.beforeClick').hide();
	$('.afterClick').show();
	document.getElementById('downloading_'+prodId).style.display = 'block';
	document.getElementById('song_'+prodId).style.display = 'none';
	document.getElementById('download_loader_'+prodId).style.display = 'block';
	var finalURL = downloadUrl1;
	finalURL += downloadUrl2;
	finalURL += downloadUrl3;
	var data = "prodId="+prodId;
	id = prodId;
	jQuery.ajax({
		type: "post",  // Request method: post, get
		url: webroot+"homes/userDownload", // URL to request
		data: data,  // post data
		success: function(response) {
			var msg = response.substring(0,5);
			if(msg == 'error')
			{
				alert("Your download limit has exceeded.");
				location.reload();
				return false;
			}
			else if(msg == 'incld')
			{
				alert("You have already downloaded this song.Get it from your recent downloads");
				document.getElementById('download_loader_'+prodId).style.display = 'none';
				document.getElementById('downloading_'+prodId).style.display = 'none';
				$('.download_links_'+prodId).html('');
				if(languageSet == 'en'){
					document.getElementById('song_'+prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";	
				}else{
					document.getElementById('song_'+prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
				}
				document.getElementById('song_'+prodId).style.display = 'block';
				addQtip_top(prodId);
				$('.afterClick').hide();
				$('.beforeClick').show();				
				return false;
			}			
			else if(msg == 'suces')
			{
				$('.afterClick').hide();
				$('.beforeClick').show();
				var downloadUsedArr = response.split('|');		
				document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
				document.getElementById('download_loader_'+prodId).style.display = 'none';
				document.getElementById('downloading_'+prodId).style.display = 'none';
				$('.download_links_'+prodId).html('');
				if(languageSet == 'en'){
					document.getElementById('song_'+prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
				}else{
					document.getElementById('song_'+prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
				}
				document.getElementById('song_'+prodId).style.display = 'block';
				addQtip(prodId);
				location.href = unescape(finalURL);
			}
			else
			{
				alert("You have been logged out from the system. Please login again.");
				location.reload();
				return false;				
			}
		},
		error:function (XMLHttpRequest, textStatus, errorThrown) {}
	});
	return false;
}

function addQtip(prodId){
   $('#song_'+prodId).qtip({
      content : "You have already downloaded this song. Get it from your recent downloads.",
      position: {
         corner: {
            target: 'topLeft',
            tooltip: 'bottomRight'
         }
      },
      style: {
	 name:'cream',
         padding: '2px 5px',
         width: {
            max: 350,
            min: 0
         },
         border: {
               width: 1,
               radius: 8,
               color: '#FAF7AA'
         },
         tip: true
      }
   });
}

function addQtip_top(prodId){
   $('#song_'+prodId).qtip({
      content : "You have already downloaded this song. Get it from your recent downloads.",
      position: {
         corner: {
            target: 'topRight',
            tooltip: 'bottomLeft'
         }
      },
      style: {
	 name:'cream',
         padding: '2px 5px',
         width: {
            max: 350,
            min: 0
         },
         border: {
               width: 1,
               radius: 8,
               color: '#FAF7AA'
         },
         tip: true
      }
   });
}
function addQtip_toptab(prodId){
   $('#songtab_'+prodId).qtip({
      content : "You have already downloaded this song. Get it from your recent downloads.",
      position: {
         corner: {
            target: 'topRight',
            tooltip: 'bottomLeft'
         }
      },
      style: {
	 name:'cream',
         padding: '2px 5px',
         width: {
            max: 350,
            min: 0
         },
         border: {
               width: 1,
               radius: 8,
               color: '#FAF7AA'
         },
         tip: true
      }
   });
}

function addToWishlist(prodId , providerType)
{
	$('.beforeClick').hide();
	$('.afterClick').show();
	//document.getElementById('wishlist_loader_'+prodId).style.display = 'block';
	
	var data = "prodId="+prodId+"&provider="+providerType;	
	jQuery.ajax({
		type: "post",  // Request method: post, get
		url: webroot+"homes/addToWishlist", // URL to request
		data: data,  // post data
		success: function(response) {	
                    //alert(response);
			var msg = response.substring(0,5);
			if(msg == 'error')
			{
                                                           
                            document.getElementById('ajaxflashMessage44').innerHTML = 'You can not add more songs to your wishlist.';

                            //alert("You can not add more songs to your wishlist.");
                            //location.reload();
                            return false;
			}else if(msg == 'error1'){                    
                            
                            document.getElementById('wishlist'+prodId).innerHTML = '<a class="add-to-wishlist">Already Added</a>';
                        }
			else
			{	
				var msg = response.substring(0,7);
				if(msg == 'Success')
				{
					$('.beforeClick').show();
					$('.afterClick').hide();
					if(languageSet == 'en'){
						document.getElementById('wishlist'+prodId).innerHTML = '<a class="add-to-wishlist">Added to Wishlist</a>';
					}else{
						document.getElementById('wishlist'+prodId).innerHTML = '<a class="add-to-wishlist">Añadido a su Lista Deseos</a>';
					}
					//document.getElementById('wishlist_loader_'+prodId).style.display = 'none';
				}
				else
				{
					 document.getElementById('ajaxflashMessage44').innerHTML = 'You have been logged out from the system. Please login again.';
                                        //alert("You have been logged out from the system. Please login again.");
					//location.reload();
					return false;				
				}
			}			
		},
		error:function (XMLHttpRequest, textStatus, errorThrown) {						
		}
	});
	return false; 
}
function addToWishlistVideo(prodId , providerType)
{
	$('.beforeClick').hide();
	$('.afterClick').show();
	//document.getElementById('wishlist_loader_'+prodId).style.display = 'block';
	
	var data = "prodId="+prodId+"&provider="+providerType;	
	jQuery.ajax({
		type: "post",  // Request method: post, get
		url: webroot+"homes/addToWishlistVideo", // URL to request
		data: data,  // post data
		success: function(response) {	
                    //alert(response);
			var msg = response.substring(0,5);
			if(msg == 'error')
			{
                                                           
                            document.getElementById('ajaxflashMessage44').innerHTML = 'You can not add more songs to your wishlist.';

                            //alert("You can not add more songs to your wishlist.");
                            location.reload();
                            return false;
			}else if(msg == 'error1'){                    
                            
                            document.getElementById('video_wishlist'+prodId).innerHTML = '<a class="add-to-wishlist">Already Added</a>';
                        }
			else
			{	
				var msg = response.substring(0,7);
				if(msg == 'Success')
				{
					$('.beforeClick').show();
					$('.afterClick').hide();
					if(languageSet == 'en'){
						document.getElementById('video_wishlist'+prodId).innerHTML = '<a class="add-to-wishlist">Added to Wishlist</a>';
					}else{
						document.getElementById('video_wishlist'+prodId).innerHTML = '<a class="add-to-wishlist">Añadido a su Lista Deseos</a>';
					}
					//document.getElementById('wishlist_loader_'+prodId).style.display = 'none';
				}
				else
				{
					document.getElementById('ajaxflashMessage44').innerHTML = 'You have been logged out from the system. Please login again.';
                                        //alert("You have been logged out from the system. Please login again.");
					location.reload();
					return false;				
				}
			}			
		},
		error:function (XMLHttpRequest, textStatus, errorThrown) {						
		}
	});
	return false; 
}
function addToWishlist_top(prodId , providerType)
{
	$('.beforeClick').hide();
	$('.afterClick').show();
	document.getElementById('wishlist_loader_'+prodId).style.display = 'block';	
	var data = "prodId="+prodId+"&provider="+providerType;
	jQuery.ajax({
		type: "post",  // Request method: post, get
		url: webroot+"homes/addToWishlist", // URL to request
		data: data,  // post data
		success: function(response) {			
			var msg = response.substring(0,5);
			if(msg == 'error')
			{
				alert("You can not add more songs to your wishlist.");
				location.reload();
				return false;
			}
			else
			{	
				var msg = response.substring(0,7);
				if(msg == 'Success')
				{
					$('.beforeClick').show();
					$('.afterClick').hide();
					if(languageSet == 'en'){
						document.getElementById('wishlist_top'+prodId).innerHTML = 'Added to Wishlist';
					}else{
						document.getElementById('wishlist_top'+prodId).innerHTML = 'Añadido a su Lista Deseost';
					}
					document.getElementById('wishlist_loader_'+prodId).style.display = 'none';
				}
				else
				{
					alert("You have been logged out from the system. Please login again.");
					location.reload();
					return false;				
				}				
			}			
		},
		error:function (XMLHttpRequest, textStatus, errorThrown) {						
		}
	});
	return false; 
}

function wishlistDownloadIE(prodId,id,provider)
{
	$('.beforeClick').hide();
	$('.afterClick').show();
	document.getElementById('wishlist_loader_'+prodId).style.display = 'block';
	document.getElementById('downloading_'+prodId).style.display = 'block';
	document.getElementById('wishlist_song_'+prodId).style.display = 'none';
	var data = "prodId="+prodId+"&id="+id+"&provider="+provider;
	id = prodId;
	jQuery.ajax({
		type: "post",  // Request method: post, get
		url: webroot+"homes/wishlistDownload", // URL to request
		data: data,  // post data
		success: function(response) {
			var msg = response.substring(0,5);
			if(msg == 'error')
			{
				alert("Your download limit has exceeded.");
				location.reload();
				return false;
			}		
			else if(msg == 'suces')
			{
				$('.afterClick').hide();
				$('.beforeClick').show();			
				var downloadUsedArr = response.split('|');		
				document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
				document.getElementById('wishlist_song_'+prodId).innerHTML = '<a title="You have already downloaded this Song. Get it from your recent downloads" href="/homes/my_history">Downloaded</a>';
				document.getElementById('wishlist_loader_'+prodId).style.display = 'none';
				document.getElementById('downloading_'+prodId).style.display = 'none';
				document.getElementById('wishlist_song_'+prodId).style.display = 'block';
                        }
			else
			{
				alert("You have been logged out from the system. Please login again.");
				location.reload();
				return false;
			}			
		},
		error:function (XMLHttpRequest, textStatus, errorThrown) {}
	});
	return false;
}

function wishlistVideoDownloadIE(prodId,id,provider)
{
    	$('.beforeClick').hide();
	$('.afterClick').show();
	document.getElementById('vdownloading_'+prodId).style.display = 'block';
	document.getElementById('download_video_'+prodId).style.display = 'none';
	document.getElementById('vdownload_loader_'+prodId).style.display = 'block';
	var data = "prodId="+prodId+"&id="+id+"&provider="+provider;
	id = prodId;
	jQuery.ajax({
		type: "post",  // Request method: post, get
		url: webroot+"homes/wishlistVideoDownload", // URL to request
		data: data,  // post data
		success: function(response) {
			var msg = response.substring(0,5);
			if(msg == 'error')
			{
				alert("Your download limit has exceeded.");
				location.reload();
				return false;
			}		
			else if(msg == 'suces')
			{
				$('.afterClick').hide();
				$('.beforeClick').show();			
				var downloadUsedArr = response.split('|');		
				document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
				document.getElementById('download_video_'+prodId).innerHTML = '<a title="You have already downloaded this Song. Get it from your recent downloads" href="/homes/my_history"><label class="top-10-download-now-button" style="width:120px;cursor:pointer;"><?php __('Downloaded'); ?></label></a>';
				document.getElementById('vdownload_loader_'+prodId).style.display = 'none';
				document.getElementById('vdownloading_'+prodId).style.display = 'none';
				document.getElementById('download_video_'+prodId).style.display = 'block';
                        }
			else
			{
				alert("You have been logged out from the system. Please login again.");
				location.reload();
				return false;				
			}			
		},
		error:function (XMLHttpRequest, textStatus, errorThrown) {}
	});
	return false;
}


function historyDownload(id,libID,patronID)
{
	$('.beforeClick').hide();
	$('.afterClick').show();
	//document.getElementById('download_loader_'+id).style.display = 'block';
	var data = "libid="+libID+"&patronid="+patronID+"&id="+id;
	jQuery.ajax({
		type: "post",  // Request method: post, get
		url: webroot+"homes/historyDownload", // URL to request
		data: data,  // post data
		success: function(response) {
			var msg = response.substring(0,5);
			if(msg == 'error')
			{
				alert("Your have already downloaded this song twice.");
				location.reload();
				return false;
			}			
			else if(msg == 'suces')
			{
				var count = response.substring(0,1);
					if(count == 2){
						if(languageSet == 'en'){
							document.getElementById('download_song_'+id).innerHTML = 'Limit Met';
						}else{
							document.getElementById('download_song_'+id).innerHTML = 'Límite Excedido';
						}
					}
				//document.getElementById('download_loader_'+id).style.display = 'none';
				$('.afterClick').hide();
				$('.beforeClick').show();				
			}
			else
			{
				alert("You have been logged out from the system. Please login again.");
				location.reload();
				return false;				
			}
		},
		error:function (XMLHttpRequest, textStatus, errorThrown) {}
	});
	return false;
}

function historyDownloadOthers(id,libID,patronID,downloadUrl1,downloadUrl2,downloadUrl3)
{
	$('.beforeClick').hide();
	$('.afterClick').show();
	document.getElementById('download_loader_'+id).style.display = 'block';
	var finalURL = downloadUrl1;
	finalURL += downloadUrl2;
	finalURL += downloadUrl3;
	var data = "libid="+libID+"&patronid="+patronID+"&id="+id;
	jQuery.ajax({
		type: "post",  // Request method: post, get
		url: webroot+"homes/historyDownload", // URL to request
		data: data,  // post data
		success: function(response) {
			var msg = response.substring(0,5);
			if(msg == 'error')
			{
				alert("Your download limit has exceeded.");
				document.getElementById('download_loader_'+id).style.display = 'none';
				location.reload();
				return false;
			}
			else if(msg == 'suces')
			{
				var count = response.substring(0,1);
					if(count == 2){
						if(languageSet == 'en'){
							document.getElementById('download_song_'+id).innerHTML = 'Limit Met';
						}else{
							document.getElementById('download_song_'+id).innerHTML = 'Límite Excedido';
						}
					}
				$('.afterClick').hide();
				$('.beforeClick').show();					
				document.getElementById('download_loader_'+id).style.display = 'none';
				location.href = unescape(finalURL);
			}
			else
			{
				alert("You have been logged out from the system. Please login again.");
				location.reload();
				return false;				
			}	
		},
		error:function (XMLHttpRequest, textStatus, errorThrown) {}
	});
	return false; 
}

function historyDownloadVideo(id,libID,patronID)
{
	$('.beforeClick').hide();
	$('.afterClick').show();
	//document.getElementById('download_loader_'+id).style.display = 'block';
	var data = "libid="+libID+"&patronid="+patronID+"&id="+id;
	jQuery.ajax({
		type: "post",  // Request method: post, get
		url: webroot+"homes/historyDownloadVideo", // URL to request
		data: data,  // post data
		success: function(response) {
			var msg = response.substring(0,5);
			if(msg == 'error')
			{
				alert("Your have already downloaded this song twice.");
				location.reload();
				return false;
			}			
			else if(msg == 'suces')
			{
				var count = response.substring(0,1);
					if(count == 2){
						if(languageSet == 'en'){
							document.getElementById('download_song_'+id).innerHTML = 'Limit Met';
						}else{
							document.getElementById('download_song_'+id).innerHTML = 'Límite Excedido';
						}
					}
				//document.getElementById('download_loader_'+id).style.display = 'none';
				$('.afterClick').hide();
				$('.beforeClick').show();				
			}
			else
			{
				alert("You have been logged out from the system. Please login again.");
				location.reload();
				return false;				
			}
		},
		error:function (XMLHttpRequest, textStatus, errorThrown) {}
	});
	return false;
}

function historyDownloadVideoOthers(id,libID,patronID,downloadUrl1,downloadUrl2,downloadUrl3)
{
	$('.beforeClick').hide();
	$('.afterClick').show();
	document.getElementById('download_loader_'+id).style.display = 'block';
	var finalURL = downloadUrl1;
	finalURL += downloadUrl2;
	finalURL += downloadUrl3;
	var data = "libid="+libID+"&patronid="+patronID+"&id="+id;
	jQuery.ajax({
		type: "post",  // Request method: post, get
		url: webroot+"homes/historyDownloadVideo", // URL to request
		data: data,  // post data
		success: function(response) {
			var msg = response.substring(0,5);
			if(msg == 'error')
			{
				alert("Your download limit has exceeded.");
				document.getElementById('download_loader_'+id).style.display = 'none';
				location.reload();
				return false;
			}
			else if(msg == 'suces')
			{
				var count = response.substring(0,1);
					if(count == 2){
						if(languageSet == 'en'){
							document.getElementById('download_song_'+id).innerHTML = 'Limit Met';
						}else{
							document.getElementById('download_song_'+id).innerHTML = 'Límite Excedido';
						}
					}
				$('.afterClick').hide();
				$('.beforeClick').show();					
				document.getElementById('download_loader_'+id).style.display = 'none';
				location.href = unescape(finalURL);
			}
			else
			{
				alert("You have been logged out from the system. Please login again.");
				location.reload();
				return false;				
			}	
		},
		error:function (XMLHttpRequest, textStatus, errorThrown) {}
	});
	return false; 
}


function wishlistDownloadOthers(prodId,id,downloadUrl1,downloadUrl2,downloadUrl3,provider)
{
	$('.beforeClick').hide();
	$('.afterClick').show();
	document.getElementById('downloading_'+prodId).style.display = 'block';
	document.getElementById('wishlist_song_'+prodId).style.display = 'none';
	document.getElementById('wishlist_loader_'+prodId).style.display = 'block';
	var finalURL = downloadUrl1;
	finalURL += downloadUrl2;
	finalURL += downloadUrl3;
	var data = "prodId="+prodId+"&id="+id+"&provider="+provider;
	id = prodId;
	jQuery.ajax({
		type: "post",  // Request method: post, get
		url: webroot+"homes/wishlistDownload", // URL to request
		data: data,  // post data
		success: function(response) {
                   // alert(response);
			var msg = response.substring(0,5);
			if(msg == 'error')
			{
				alert("Your download limit has exceeded.");
				location.reload();
				return false;
			}
			else if(msg == 'suces')
			{
				var downloadUsedArr = response.split('|');		
				document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
				if(languageSet == 'en'){
					document.getElementById('wishlist_song_'+prodId).innerHTML = '<a title="You have already downloaded this Song. Get it from your recent downloads" href="/homes/my_history">Downloaded</a>';
				}else{
					document.getElementById('wishlist_song_'+prodId).innerHTML = '<a href="/homes/my_history">bajaedas</a>';
				}
				document.getElementById('wishlist_loader_'+prodId).style.display = 'none';
				document.getElementById('downloading_'+prodId).style.display = 'none';
				document.getElementById('wishlist_song_'+prodId).style.display = 'block';
				location.href = unescape(finalURL);
				$('.afterClick').hide();
				$('.beforeClick').show();				
			}
			else
			{
				alert("You have been logged out from the system. Please login again.");
				location.reload();
				return false;				
			}	
		},
		error:function (XMLHttpRequest, textStatus, errorThrown) {}
	});
	return false;	
}

function wishlistVideoDownloadOthers(prodId,id,downloadUrl1,downloadUrl2,downloadUrl3,provider)
{
	$('.beforeClick').hide();
	$('.afterClick').show();
	document.getElementById('vdownloading_'+prodId).style.display = 'block';
	document.getElementById('download_video_'+prodId).style.display = 'none';
	document.getElementById('vdownload_loader_'+prodId).style.display = 'block';
	var finalURL = downloadUrl1;
	finalURL += downloadUrl2;
	finalURL += downloadUrl3;
	var data = "prodId="+prodId+"&id="+id+"&provider="+provider;
	id = prodId;
	jQuery.ajax({
		type: "post",  // Request method: post, get
		url: webroot+"homes/wishlistVideoDownload", // URL to request
		data: data,  // post data
		success: function(response) {
                   // alert(response);
			var msg = response.substring(0,5);
			if(msg == 'error')
			{
				alert("Your download limit has exceeded.");
				location.reload();
				return false;
			}
			else if(msg == 'suces')
			{
				var downloadUsedArr = response.split('|');		
				document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
				if(languageSet == 'en'){
					document.getElementById('download_video_'+prodId).innerHTML = '<a title="You have already downloaded this Video. Get it from your recent downloads" href="/homes/my_history"><label class="top-10-download-now-button">Downloaded</label></a>';
				}else{
					document.getElementById('download_video_'+prodId).innerHTML = '<a href="/homes/my_history"><label class="top-10-download-now-button">bajaedas</label></a>';
				}
                                document.getElementById('vdownload_loader_'+prodId).style.display = 'none';
				document.getElementById('vdownloading_'+prodId).style.display = 'none';
				document.getElementById('download_video_'+prodId).style.display = 'block';
				location.href = unescape(finalURL);
				$('.afterClick').hide();
				$('.beforeClick').show();
			}
			else
			{
				alert("You have been logged out from the system. Please login again.");
				location.reload();
				return false;				
			}	
		},
		error:function (XMLHttpRequest, textStatus, errorThrown) {}
	});
	return false;
}

function checkPatron(libid,patronid)
{	
	var data = "libid="+libid+"&patronid="+patronid.replace('+','_');
	jQuery.ajax({
		type: "post",  // Request method: post, get
		url: webroot+"homes/checkPatron", // URL to request
		data: data,  // post data
		success: function(response)
		{
				var msg = response.substring(0,7);
				if(msg == 'success')
				{
					setTimeout(function(){ checkPatron(libid,patronid) }, 30000);
				}
				else if(response != '')
				{
				//	alert("You have been logged out from the system. Please login again.");
				//	location.reload();
				//	return false;
					setTimeout(function(){ checkPatron(libid,patronid) }, 30000);
				}
		},
		error:function (XMLHttpRequest, textStatus, errorThrown) {						
		}
	});
	return false; 
}

/*function auto_check()
{	
	jQuery.ajax({
		type: "post",  // Request method: post, get
		url: webroot+"homes/auto_check", // URL to request
		success: function(response)
		{
				var msg = response.substring(0,7);
				if(msg == 'success')
				{
					//nothing
				}
				else
				{
					alert("You have been logged out from the system. Please login again.");
					location.reload();
					return false;				
				}
		},
		error:function (XMLHttpRequest, textStatus, errorThrown) {						
		}
	});
	return false; 
}*/

function approvePatron(libid,patronid)
{
	var _loaderDiv = $("#loaderDiv");
	_loaderDiv.show();
	var data = "libid="+libid+"&patronid="+patronid;      
	jQuery.ajax({
		type: "post",  // Request method: post, get
		url: webroot+"homes/approvePatron", // URL to request
		data: data,  // post data
		success: function(response) {                      
			location.reload();
		},
		error:function (XMLHttpRequest, textStatus, errorThrown) {
			location.reload();
		}
	});
	return false; 
}

var isIE  = (navigator.appVersion.indexOf("MSIE") != -1) ? true : false;
var isWin = (navigator.appVersion.toLowerCase().indexOf("win") != -1) ? true : false;
var isOpera = (navigator.userAgent.indexOf("Opera") != -1) ? true : false;

function ControlVersion()
{
	var version;
	var axo;
	var e;

	// NOTE : new ActiveXObject(strFoo) throws an exception if strFoo isn't in the registry

	try {
		// version will be set for 7.X or greater players
		axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.7");
		version = axo.GetVariable("$version");
	} catch (e) {
	}

	if (!version)
	{
		try {
			// version will be set for 6.X players only
			axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.6");
			
			// installed player is some revision of 6.0
			// GetVariable("$version") crashes for versions 6.0.22 through 6.0.29,
			// so we have to be careful. 
			
			// default to the first public version
			version = "WIN 6,0,21,0";

			// throws if AllowScripAccess does not exist (introduced in 6.0r47)		
			axo.AllowScriptAccess = "always";

			// safe to call for 6.0r47 or greater
			version = axo.GetVariable("$version");

		} catch (e) {
		}
	}

	if (!version)
	{
		try {
			// version will be set for 4.X or 5.X player
			axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.3");
			version = axo.GetVariable("$version");
		} catch (e) {
		}
	}

	if (!version)
	{
		try {
			// version will be set for 3.X player
			axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.3");
			version = "WIN 3,0,18,0";
		} catch (e) {
		}
	}

	if (!version)
	{
		try {
			// version will be set for 2.X player
			axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash");
			version = "WIN 2,0,0,11";
		} catch (e) {
			version = -1;
		}
	}
	
	return version;
}

// JavaScript helper required to detect Flash Player PlugIn version information
function GetSwfVer(){
	// NS/Opera version >= 3 check for Flash plugin in plugin array
	var flashVer = -1;
	
	if (navigator.plugins != null && navigator.plugins.length > 0) {
		if (navigator.plugins["Shockwave Flash 2.0"] || navigator.plugins["Shockwave Flash"]) {
			var swVer2 = navigator.plugins["Shockwave Flash 2.0"] ? " 2.0" : "";
			var flashDescription = navigator.plugins["Shockwave Flash" + swVer2].description;
			var descArray = flashDescription.split(" ");
			var tempArrayMajor = descArray[2].split(".");			
			var versionMajor = tempArrayMajor[0];
			var versionMinor = tempArrayMajor[1];
			var versionRevision = descArray[3];
			if (versionRevision == "") {
				versionRevision = descArray[4];
			}
			if (versionRevision[0] == "d") {
				versionRevision = versionRevision.substring(1);
			} else if (versionRevision[0] == "r") {
				versionRevision = versionRevision.substring(1);
				if (versionRevision.indexOf("d") > 0) {
					versionRevision = versionRevision.substring(0, versionRevision.indexOf("d"));
				}
			}
			var flashVer = versionMajor + "." + versionMinor + "." + versionRevision;
		}
	}
	// MSN/WebTV 2.6 supports Flash 4
	else if (navigator.userAgent.toLowerCase().indexOf("webtv/2.6") != -1) flashVer = 4;
	// WebTV 2.5 supports Flash 3
	else if (navigator.userAgent.toLowerCase().indexOf("webtv/2.5") != -1) flashVer = 3;
	// older WebTV supports Flash 2
	else if (navigator.userAgent.toLowerCase().indexOf("webtv") != -1) flashVer = 2;
	else if ( isIE && isWin && !isOpera ) {
		flashVer = ControlVersion();
	}	
	return flashVer;
}

// When called with reqMajorVer, reqMinorVer, reqRevision returns true if that version or greater is available
function DetectFlashVer(reqMajorVer, reqMinorVer, reqRevision)
{
	versionStr = GetSwfVer();
	if (versionStr == -1 ) {
		return false;
	} else if (versionStr != 0) {
		if(isIE && isWin && !isOpera) {
			// Given "WIN 2,0,0,11"
			tempArray         = versionStr.split(" "); 	// ["WIN", "2,0,0,11"]
			tempString        = tempArray[1];			// "2,0,0,11"
			versionArray      = tempString.split(",");	// ['2', '0', '0', '11']
		} else {
			versionArray      = versionStr.split(".");
		}
		var versionMajor      = versionArray[0];
		var versionMinor      = versionArray[1];
		var versionRevision   = versionArray[2];

        	// is the major.revision >= requested major.revision AND the minor version >= requested minor
		if (versionMajor > parseFloat(reqMajorVer)) {
			return true;
		} else if (versionMajor == parseFloat(reqMajorVer)) {
			if (versionMinor > parseFloat(reqMinorVer))
				return true;
			else if (versionMinor == parseFloat(reqMinorVer)) {
				if (versionRevision >= parseFloat(reqRevision))
					return true;
			}
		}
		return false;
	}
}

function videoDownloadAll(prodId)
{
    
                 hidVideoValue    =   $("#hid_VideoDownloadStatus").val();
                         
                 if (hidVideoValue==1) {
                                      
                        var r=confirm('A video download will use up 2 of your available downloads. Are you sure you want to continue?');
                        if (r==true)
                         {
                               $('.beforeClick').hide();
                               $('.afterClick').show();
                               document.getElementById('downloading_'+prodId).style.display = 'block';
                               document.getElementById('song_'+prodId).style.display = 'none';
                               document.getElementById('download_loader_'+prodId).style.display = 'block';
                               $('#form'+prodId).submit();
                               setTimeout("location.reload(true)", 7000);
                         }
                       else
                         {
                             return;
                         }
                 }
                 else
                     {
                        alert('Sorry, you do not have enough credits to download a video.'); 
                     }
    
    
} 

function addToQueue(songProdId , songProviderType, albumProdId, albumProviderType, queueId)
{
	var data = "songProdId="+songProdId+"&songProviderType="+songProviderType+"&albumProdId="+albumProdId+"&albumProviderType="+albumProviderType+"&queueId="+queueId;	
	jQuery.ajax({
		type: "post",  // Request method: post, get
		url: webroot+"queues/addToQueue", // URL to request
		data: data,  // post data
		success: function(response) {	
                        if(response.length == 6){
                            var msg = response.substring(0,6);
                        }else{
                            var msg = response.substring(0,5);
                        }
			if(msg == 'error')
			{
                            document.getElementById("ajaxflashMessage44").style.display="block";
                            document.getElementById("ajaxflashMessage44").style.background="red";
                            document.getElementById('ajaxflashMessage44').innerHTML = 'There is some problem in adding song to Queuelist.';
                            return false;
			}else if(msg == 'error1'){
                            document.getElementById("ajaxflashMessage44").style.display="block";
                            document.getElementById('ajaxflashMessage44').innerHTML = 'This song is already added to Queue';
                        }
			else
			{	
				var msg = response.substring(0,7);
				if(msg == 'Success')
				{
                                    document.getElementById("ajaxflashMessage44").style.display="block";
                                    document.getElementById('ajaxflashMessage44').innerHTML = 'Successfully added song to Queue';

				}
				else
				{
                                        document.getElementById("ajaxflashMessage44").style.display="block";
        				document.getElementById("ajaxflashMessage44").style.background="red";
                                        document.getElementById('ajaxflashMessage44').innerHTML = 'There is some problem arised when adding song to Queue.';
					return false;				
				}
			}			
		},
		error:function (XMLHttpRequest, textStatus, errorThrown) {
                    document.getElementById("ajaxflashMessage44").style.display="block";
                    document.getElementById("ajaxflashMessage44").style.background="red";
                    document.getElementById('ajaxflashMessage44').innerHTML = 'Ajax call for adding song to queue is unsuccessfull';
		}
	} );
	return false; 
}
