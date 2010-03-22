jQuery(document).ready(function() {
	jQuery('#slideshow').cycle({
		fx: 'fade',
		sync: 0,
		speed: 'slow',
		delay: -8000,
		timeout: 12000
	});
});
jQuery(document).ready(function() {
	jQuery('#featured_artist').cycle({
		fx: 'fade',
		sync: 0,
		speed: 'slow',
		delay: -4000,
		timeout: 12000
	});
});
jQuery(document).ready(function() {
	jQuery('#newly_added').cycle({
		fx: 'fade',
		sync: 0,
		speed: 'slow',
		delay: -2000,
		timeout: 12000
	});
});
function userDownload(prodId,downloadUrl1,downloadUrl2,downloadUrl3)
{	
	var finalURL = downloadUrl1;
	finalURL += downloadUrl2;
	finalURL += downloadUrl3;
	var data = "prodId="+prodId;	
	jQuery.ajax({
	type: "post",  // Request method: post, get
	url: webroot+"homes/userDownload", // URL to request
	data: data,  // post data
	success: function(response) {
	location.href = unescape(finalURL);
	},
	error:function (XMLHttpRequest, textStatus, errorThrown) {
	alert(textStatus);
	}
	});
	return false; 
}

