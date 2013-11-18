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

var message = "Function Disabled!";

///////////////////////////////////
function clickIE4() {
    if (event.button == 2) {
        return false;
    }
}

function clickNS4(e) {
    if (document.layers || document.getElementById && !document.all) {
        if (e.which == 2 || e.which == 3) {
            return false;
        }
    }
}

if (document.layers) {
    document.captureEvents(Event.MOUSEDOWN);
    document.onmousedown = clickNS4;
}
//else if (document.all&&!document.getElementById()) {
//    document.onmousedown=clickIE4;
//}

document.oncontextmenu = new Function("return false");
var id;
function userDownloadIE(prodId)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('download_loader_' + prodId).style.display = 'block';
    document.getElementById('downloading_' + prodId).style.display = 'block';
    document.getElementById('song_' + prodId).style.display = 'none';
    var data = "prodId=" + prodId;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/userDownload", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg == 'error')
            {
                alert("Your download limit has exceeded.");
                location.reload();
                return false;
            }
            else if (msg == 'incld')
            {
                alert("You have already downloaded this song.Get it from your recent downloads");
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('song_' + prodId).innerHTML = '';
                if (languageSet == 'en') {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('song_' + prodId).style.display = 'block';
                addQtip_top(prodId);
                $('.afterClick').hide();
                $('.beforeClick').show();
                return false;
            }
            else if (msg == 'suces')
            {
                $('.afterClick').hide();
                $('.beforeClick').show();
                var downloadUsedArr = response.split('|');
                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('song_' + prodId).innerHTML = '';
                if (languageSet == 'en') {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('song_' + prodId).style.display = 'block';
                addQtip(prodId);
            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}
function changeLang(type) { //alert("http://jeffersonlibrary.freegaldev.com/"+webroot+"homes/language");
    var language = type;
    var data = "lang=" + language;
    $.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/language", // URL to request
        data: data, // post data
        success: function(response) { //alert("in js"+webroot);
            var msg = response.substring(0, 5);
            if (msg == 'error')
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
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
}

function userDownloadOthers(prodId, downloadUrl1, downloadUrl2, downloadUrl3)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('downloading_' + prodId).style.display = 'block';
    document.getElementById('song_' + prodId).style.display = 'none';
    document.getElementById('download_loader_' + prodId).style.display = 'block';
    var finalURL = downloadUrl1;
    finalURL += downloadUrl2;
    finalURL += downloadUrl3;
    var data = "prodId=" + prodId;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/userDownload", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg == 'error')
            {
                alert("Your download limit has exceeded.");
                location.reload();
                return false;
            }
            else if (msg == 'incld')
            {
                alert("You have already downloaded this song.Get it from your recent downloads");
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('song_' + prodId).innerHTML = '';
                if (languageSet == 'en') {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('song_' + prodId).style.display = 'block';
                addQtip_top(prodId);
                $('.afterClick').hide();
                $('.beforeClick').show();
                return false;
            }
            else if (msg == 'suces')
            {
                var downloadUsedArr = response.split('|');
                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('song_' + prodId).innerHTML = '';
                if (languageSet == 'en') {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('song_' + prodId).style.display = 'block';
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
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}

function userDownloadIE_top(prodId)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('download_loader_' + prodId).style.display = 'block';
    document.getElementById('downloading_' + prodId).style.display = 'block';
    document.getElementById('song_' + prodId).style.display = 'none';
    var data = "prodId=" + prodId;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/userDownload", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg == 'error')
            {
                alert("Your download limit has exceeded.");
                location.reload();
                return false;
            }
            else if (msg == 'incld')
            {
                alert("You have already downloaded this song.Get it from your recent downloads");
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('song_' + prodId).innerHTML = '';
                if (languageSet == 'en') {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('song_' + prodId).style.display = 'block';
                addQtip_top(prodId);
                $('.afterClick').hide();
                $('.beforeClick').show();
                return false;
            }
            else if (msg == 'suces')
            {
                $('.afterClick').hide();
                $('.beforeClick').show();
                var downloadUsedArr = response.split('|');
                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('song_' + prodId).innerHTML = '';
                if (languageSet == 'en') {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('song_' + prodId).style.display = 'block';
                addQtip_top(prodId);

            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}


function userDownloadAll(prodId)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('downloading_' + prodId).style.display = 'block';
    document.getElementById('song_' + prodId).style.display = 'none';
    document.getElementById('download_loader_' + prodId).style.display = 'block';
    $('#form' + prodId).submit();
    setTimeout("location.reload(true)", 7000);
}

function userDownloadOthers_top(prodId, downloadUrl1, downloadUrl2, downloadUrl3)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('downloading_' + prodId).style.display = 'block';
    document.getElementById('song_' + prodId).style.display = 'none';
    document.getElementById('download_loader_' + prodId).style.display = 'block';
    var finalURL = downloadUrl1;
    finalURL += downloadUrl2;
    finalURL += downloadUrl3;
    var data = "prodId=" + prodId;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/userDownload", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg == 'error')
            {
                alert("Your download limit has exceeded.");
                location.reload();
                return false;
            }
            else if (msg == 'incld')
            {
                alert("You have already downloaded this song.Get it from your recent downloads");
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('song_' + prodId).innerHTML = '';
                if (languageSet == 'en') {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('song_' + prodId).style.display = 'block';
                addQtip_top(prodId);
                $('.afterClick').hide();
                $('.beforeClick').show();
                return false;
            }
            else if (msg == 'suces')
            {
                var downloadUsedArr = response.split('|');
                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('song_' + prodId).innerHTML = '';
                if (languageSet == 'en') {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('song_' + prodId).style.display = 'block';
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
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}

function userDownloadIE_toptab(prodId)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('download_loader_' + prodId).style.display = 'block';
    document.getElementById('downloading_' + prodId).style.display = 'block';
    document.getElementById('songtab_' + prodId).style.display = 'none';
    var data = "prodId=" + prodId;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/userDownload", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg == 'error')
            {
                alert("Your download limit has exceeded.");
                location.reload();
                return false;
            }
            else if (msg == 'incld')
            {
                alert("You have already downloaded this song.Get it from your recent downloads");
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('songtab_' + prodId).innerHTML = '';
                if (languageSet == 'en') {
                    document.getElementById('songtab_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('songtab_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('songtab_' + prodId).style.display = 'block';
                addQtip_toptab(prodId);
                $('.afterClick').hide();
                $('.beforeClick').show();
                return false;
            }
            else if (msg == 'suces')
            {
                $('.afterClick').hide();
                $('.beforeClick').show();
                var downloadUsedArr = response.split('|');
                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('songtab_' + prodId).innerHTML = '';
                if (languageSet == 'en') {
                    document.getElementById('songtab_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('songtab_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('songtab_' + prodId).style.display = 'block';
                addQtip_toptab(prodId);

            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}

function userDownloadOthers_toptab(prodId, downloadUrl1, downloadUrl2, downloadUrl3)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('downloading_' + prodId).style.display = 'block';
    document.getElementById('songtab_' + prodId).style.display = 'none';
    document.getElementById('download_loader_' + prodId).style.display = 'block';
    var finalURL = downloadUrl1;
    finalURL += downloadUrl2;
    finalURL += downloadUrl3;
    var data = "prodId=" + prodId;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/userDownload", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg == 'error')
            {
                alert("Your download limit has exceeded.");
                location.reload();
                return false;
            }
            else if (msg == 'incld')
            {
                alert("You have already downloaded this song.Get it from your recent downloads");
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('songtab_' + prodId).innerHTML = '';
                if (languageSet == 'en') {
                    document.getElementById('songtab_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('songtab_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('songtab_' + prodId).style.display = 'block';
                addQtip_toptab(prodId);
                $('.afterClick').hide();
                $('.beforeClick').show();
                return false;
            }
            else if (msg == 'suces')
            {
                var downloadUsedArr = response.split('|');
                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('songtab_' + prodId).innerHTML = '';
                if (languageSet == 'en') {
                    document.getElementById('songtab_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('songtab_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('songtab_' + prodId).style.display = 'block';
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
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}


function userDownloadOthers_safari(prodId, downloadUrl1, downloadUrl2, downloadUrl3)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('downloading_' + prodId).style.display = 'block';
    document.getElementById('song_' + prodId).style.display = 'none';
    document.getElementById('download_loader_' + prodId).style.display = 'block';
    var finalURL = downloadUrl1;
    finalURL += downloadUrl2;
    finalURL += downloadUrl3;
    var data = "prodId=" + prodId;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/userDownload", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg == 'error')
            {
                alert("Your download limit has exceeded.");
                location.reload();
                return false;
            }
            else if (msg == 'incld')
            {
                alert("You have already downloaded this song.Get it from your recent downloads");
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                $('.download_links_' + prodId).html('');
                if (languageSet == 'en') {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('song_' + prodId).style.display = 'block';
                addQtip_top(prodId);
                $('.afterClick').hide();
                $('.beforeClick').show();
                return false;
            }
            else if (msg == 'suces')
            {
                $('.afterClick').hide();
                $('.beforeClick').show();
                var downloadUsedArr = response.split('|');
                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                $('.download_links_' + prodId).html('');
                if (languageSet == 'en') {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('song_' + prodId).style.display = 'block';
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
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}

function addQtip(prodId) {
    $('#song_' + prodId).qtip({
        content: "You have already downloaded this song. Get it from your recent downloads.",
        position: {
            corner: {
                target: 'topLeft',
                tooltip: 'bottomRight'
            }
        },
        style: {
            name: 'cream',
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

function addQtip_top(prodId) {
    $('#song_' + prodId).qtip({
        content: "You have already downloaded this song. Get it from your recent downloads.",
        position: {
            corner: {
                target: 'topRight',
                tooltip: 'bottomLeft'
            }
        },
        style: {
            name: 'cream',
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
function addQtip_toptab(prodId) {
    $('#songtab_' + prodId).qtip({
        content: "You have already downloaded this song. Get it from your recent downloads.",
        position: {
            corner: {
                target: 'topRight',
                tooltip: 'bottomLeft'
            }
        },
        style: {
            name: 'cream',
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

function addToWishlist(prodId, providerType)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    //document.getElementById('wishlist_loader_'+prodId).style.display = 'block';

    var data = "prodId=" + prodId + "&provider=" + providerType;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/addToWishlist", // URL to request
        data: data, // post data
        success: function(response) {
            //alert(response);
            var msg = response.substring(0, 5);
            if (msg == 'error')
            {

                document.getElementById('ajaxflashMessage44').innerHTML = 'You can not add more songs to your wishlist.';

                //alert("You can not add more songs to your wishlist.");
                //location.reload();
                return false;
            } else if (msg == 'error1') {

                document.getElementById('wishlist' + prodId).innerHTML = '<a class="add-to-wishlist">Already Added</a>';
            }
            else
            {
                var msg = response.substring(0, 7);
                if (msg == 'Success')
                {
                    $('.beforeClick').show();
                    $('.afterClick').hide();
                    if (languageSet == 'en') {
                        document.getElementById('wishlist' + prodId).innerHTML = '<a class="add-to-wishlist">Added to Wishlist</a>';
                    } else {
                        document.getElementById('wishlist' + prodId).innerHTML = '<a class="add-to-wishlist">Añadido a su Lista Deseos</a>';
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
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}
function addToWishlistVideo(prodId, providerType)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    //document.getElementById('wishlist_loader_'+prodId).style.display = 'block';

    var data = "prodId=" + prodId + "&provider=" + providerType;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/addToWishlistVideo", // URL to request
        data: data, // post data
        success: function(response) {
            //alert(response);
            var msg = response.substring(0, 5);
            if (msg == 'error')
            {

                document.getElementById('ajaxflashMessage44').innerHTML = 'You can not add more songs to your wishlist.';

                //alert("You can not add more songs to your wishlist.");
                location.reload();
                return false;
            } else if (msg == 'error1') {

                document.getElementById('video_wishlist' + prodId).innerHTML = '<a class="add-to-wishlist">Already Added</a>';
            }
            else
            {
                var msg = response.substring(0, 7);
                if (msg == 'Success')
                {
                    $('.beforeClick').show();
                    $('.afterClick').hide();
                    if (languageSet == 'en') {
                        document.getElementById('video_wishlist' + prodId).innerHTML = '<a class="add-to-wishlist">Added to Wishlist</a>';
                    } else {
                        document.getElementById('video_wishlist' + prodId).innerHTML = '<a class="add-to-wishlist">Añadido a su Lista Deseos</a>';
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
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}
function addToWishlist_top(prodId, providerType)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('wishlist_loader_' + prodId).style.display = 'block';
    var data = "prodId=" + prodId + "&provider=" + providerType;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/addToWishlist", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg == 'error')
            {
                alert("You can not add more songs to your wishlist.");
                location.reload();
                return false;
            }
            else
            {
                var msg = response.substring(0, 7);
                if (msg == 'Success')
                {
                    $('.beforeClick').show();
                    $('.afterClick').hide();
                    if (languageSet == 'en') {
                        document.getElementById('wishlist_top' + prodId).innerHTML = 'Added to Wishlist';
                    } else {
                        document.getElementById('wishlist_top' + prodId).innerHTML = 'Añadido a su Lista Deseost';
                    }
                    document.getElementById('wishlist_loader_' + prodId).style.display = 'none';
                }
                else
                {
                    alert("You have been logged out from the system. Please login again.");
                    location.reload();
                    return false;
                }
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}

function wishlistDownloadIE(prodId, id, provider)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('wishlist_loader_' + prodId).style.display = 'block';
    document.getElementById('downloading_' + prodId).style.display = 'block';
    document.getElementById('wishlist_song_' + prodId).style.display = 'none';
    var data = "prodId=" + prodId + "&id=" + id + "&provider=" + provider;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/wishlistDownload", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg == 'error')
            {
                alert("Your download limit has exceeded.");
                location.reload();
                return false;
            }
            else if (msg == 'suces')
            {
                $('.afterClick').hide();
                $('.beforeClick').show();
                var downloadUsedArr = response.split('|');
                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
                document.getElementById('wishlist_song_' + prodId).innerHTML = '<a title="You have already downloaded this Song. Get it from your recent downloads" href="/homes/my_history">Downloaded</a>';
                document.getElementById('wishlist_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('wishlist_song_' + prodId).style.display = 'block';
            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}

function wishlistVideoDownloadIE(prodId, id, provider)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('vdownloading_' + prodId).style.display = 'block';
    document.getElementById('download_video_' + prodId).style.display = 'none';
    document.getElementById('vdownload_loader_' + prodId).style.display = 'block';
    var data = "prodId=" + prodId + "&id=" + id + "&provider=" + provider;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/wishlistVideoDownload", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg == 'error')
            {
                alert("Your download limit has exceeded.");
                location.reload();
                return false;
            }
            else if (msg == 'suces')
            {
                $('.afterClick').hide();
                $('.beforeClick').show();
                var downloadUsedArr = response.split('|');
                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
                document.getElementById('download_video_' + prodId).innerHTML = '<a title="You have already downloaded this Song. Get it from your recent downloads" href="/homes/my_history"><label class="top-10-download-now-button">Downloaded</label></a>';
                document.getElementById('vdownload_loader_' + prodId).style.display = 'none';
                document.getElementById('vdownloading_' + prodId).style.display = 'none';
                document.getElementById('download_video_' + prodId).style.display = 'block';
            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}


function historyDownload(id, libID, patronID)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    //document.getElementById('download_loader_'+id).style.display = 'block';
    var data = "libid=" + libID + "&patronid=" + patronID + "&id=" + id;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/historyDownload", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg == 'error')
            {
                alert("Your have already downloaded this song twice.");
                location.reload();
                return false;
            }
            else if (msg == 'suces')
            {
                var count = response.substring(0, 1);
                if (count == 2) {
                    if (languageSet == 'en') {
                        document.getElementById('download_song_' + id).innerHTML = 'Limit Met';
                    } else {
                        document.getElementById('download_song_' + id).innerHTML = 'Límite Excedido';
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
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}

function historyDownloadOthers(id, libID, patronID, downloadUrl1, downloadUrl2, downloadUrl3)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('download_loader_' + id).style.display = 'block';
    var finalURL = downloadUrl1;
    finalURL += downloadUrl2;
    finalURL += downloadUrl3;
    var data = "libid=" + libID + "&patronid=" + patronID + "&id=" + id;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/historyDownload", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg == 'error')
            {
                alert("Your download limit has exceeded.");
                document.getElementById('download_loader_' + id).style.display = 'none';
                location.reload();
                return false;
            }
            else if (msg == 'suces')
            {
                var count = response.substring(0, 1);
                if (count == 2) {
                    if (languageSet == 'en') {
                        document.getElementById('download_song_' + id).innerHTML = 'Limit Met';
                    } else {
                        document.getElementById('download_song_' + id).innerHTML = 'Límite Excedido';
                    }
                }
                $('.afterClick').hide();
                $('.beforeClick').show();
                document.getElementById('download_loader_' + id).style.display = 'none';
                location.href = unescape(finalURL);
            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}

function historyDownloadVideo(id, libID, patronID)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    //document.getElementById('download_loader_'+id).style.display = 'block';
    var data = "libid=" + libID + "&patronid=" + patronID + "&id=" + id;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/historyDownloadVideo", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg == 'error')
            {
                alert("Your have already downloaded this song twice.");
                location.reload();
                return false;
            }
            else if (msg == 'suces')
            {
                var count = response.substring(0, 1);
                if (count == 2) {
                    if (languageSet == 'en') {
                        document.getElementById('download_song_' + id).innerHTML = 'Limit Met';
                    } else {
                        document.getElementById('download_song_' + id).innerHTML = 'Límite Excedido';
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
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}

function historyDownloadVideoOthers(id, libID, patronID, downloadUrl1, downloadUrl2, downloadUrl3)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('download_loader_' + id).style.display = 'block';
    var finalURL = downloadUrl1;
    finalURL += downloadUrl2;
    finalURL += downloadUrl3;
    var data = "libid=" + libID + "&patronid=" + patronID + "&id=" + id;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/historyDownloadVideo", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg == 'error')
            {
                alert("Your download limit has exceeded.");
                document.getElementById('download_loader_' + id).style.display = 'none';
                location.reload();
                return false;
            }
            else if (msg == 'suces')
            {
                var count = response.substring(0, 1);
                if (count == 2) {
                    if (languageSet == 'en') {
                        document.getElementById('download_song_' + id).innerHTML = 'Limit Met';
                    } else {
                        document.getElementById('download_song_' + id).innerHTML = 'Límite Excedido';
                    }
                }
                $('.afterClick').hide();
                $('.beforeClick').show();
                document.getElementById('download_loader_' + id).style.display = 'none';
                location.href = unescape(finalURL);
            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}


function wishlistDownloadOthers(prodId, id, downloadUrl1, downloadUrl2, downloadUrl3, provider)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('downloading_' + prodId).style.display = 'block';
    document.getElementById('wishlist_song_' + prodId).style.display = 'none';
    document.getElementById('wishlist_loader_' + prodId).style.display = 'block';
    var finalURL = downloadUrl1;
    finalURL += downloadUrl2;
    finalURL += downloadUrl3;
    var data = "prodId=" + prodId + "&id=" + id + "&provider=" + provider;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/wishlistDownload", // URL to request
        data: data, // post data
        success: function(response) {
            // alert(response);
            var msg = response.substring(0, 5);
            if (msg == 'error')
            {
                alert("Your download limit has exceeded.");
                location.reload();
                return false;
            }
            else if (msg == 'suces')
            {
                var downloadUsedArr = response.split('|');
                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
                if (languageSet == 'en') {
                    document.getElementById('wishlist_song_' + prodId).innerHTML = '<a title="You have already downloaded this Song. Get it from your recent downloads" href="/homes/my_history">Downloaded</a>';
                } else {
                    document.getElementById('wishlist_song_' + prodId).innerHTML = '<a href="/homes/my_history">bajaedas</a>';
                }
                document.getElementById('wishlist_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('wishlist_song_' + prodId).style.display = 'block';
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
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}

function wishlistVideoDownloadOthers(prodId, id, downloadUrl1, downloadUrl2, downloadUrl3, provider)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('vdownloading_' + prodId).style.display = 'block';
    document.getElementById('download_video_' + prodId).style.display = 'none';
    document.getElementById('vdownload_loader_' + prodId).style.display = 'block';
    var finalURL = downloadUrl1;
    finalURL += downloadUrl2;
    finalURL += downloadUrl3;
    var data = "prodId=" + prodId + "&id=" + id + "&provider=" + provider;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/wishlistVideoDownload", // URL to request
        data: data, // post data
        success: function(response) {
           //  alert(response);
            var msg = response.substring(0, 5);
            if (msg == 'error')
            {
                alert("Your download limit has exceeded.");
                location.reload();
                return false;
            }
            else if (msg == 'suces')
            {
                var downloadUsedArr = response.split('|');
                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
                if (languageSet == 'en') {
                    document.getElementById('download_video_' + prodId).innerHTML = '<a title="You have already downloaded this Video. Get it from your recent downloads" href="/homes/my_history"><label class="top-10-download-now-button">Downloaded</label></a>';
                } else {
                    document.getElementById('download_video_' + prodId).innerHTML = '<a href="/homes/my_history"><label class="top-10-download-now-button">bajaedas</label></a>';
                }
                document.getElementById('vdownload_loader_' + prodId).style.display = 'none';
                document.getElementById('vdownloading_' + prodId).style.display = 'none';
                document.getElementById('download_video_' + prodId).style.display = 'block';
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
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}

function checkPatron(libid, patronid)
{
    var data = "libid=" + libid + "&patronid=" + patronid.replace('+', '_');
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/checkPatron", // URL to request
        data: data, // post data
        success: function(response)
        {
            var msg = response.substring(0, 7);
            if (msg == 'success')
            {
                setTimeout(function() {
                    checkPatron(libid, patronid)
                }, 30000);
            }
            else if (response != '')
            {
                //	alert("You have been logged out from the system. Please login again.");
                //	location.reload();
                //	return false;
                setTimeout(function() {
                    checkPatron(libid, patronid)
                }, 30000);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
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

function approvePatron(libid, patronid)
{
    var _loaderDiv = $("#loaderDiv");
    _loaderDiv.show();
    var data = "libid=" + libid + "&patronid=" + patronid;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/approvePatron", // URL to request
        data: data, // post data
        success: function(response) {
            location.reload();
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            location.reload();
        }
    });
    return false;
}

var isIE = (navigator.appVersion.indexOf("MSIE") != -1) ? true : false;
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
function GetSwfVer() {
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
    else if (navigator.userAgent.toLowerCase().indexOf("webtv/2.6") != -1)
        flashVer = 4;
    // WebTV 2.5 supports Flash 3
    else if (navigator.userAgent.toLowerCase().indexOf("webtv/2.5") != -1)
        flashVer = 3;
    // older WebTV supports Flash 2
    else if (navigator.userAgent.toLowerCase().indexOf("webtv") != -1)
        flashVer = 2;
    else if (isIE && isWin && !isOpera) {
        flashVer = ControlVersion();
    }
    return flashVer;
}

// When called with reqMajorVer, reqMinorVer, reqRevision returns true if that version or greater is available
function DetectFlashVer(reqMajorVer, reqMinorVer, reqRevision)
{
    versionStr = GetSwfVer();
    if (versionStr == -1) {
        return false;
    } else if (versionStr != 0) {
        if (isIE && isWin && !isOpera) {
            // Given "WIN 2,0,0,11"
            tempArray = versionStr.split(" "); 	// ["WIN", "2,0,0,11"]
            tempString = tempArray[1];			// "2,0,0,11"
            versionArray = tempString.split(",");	// ['2', '0', '0', '11']
        } else {
            versionArray = versionStr.split(".");
        }
        var versionMajor = versionArray[0];
        var versionMinor = versionArray[1];
        var versionRevision = versionArray[2];

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

    hidVideoValue = $("#hid_VideoDownloadStatus").val();

    if (hidVideoValue == 1) {

        var r = confirm('A video download will use up 2 of your available downloads. Are you sure you want to continue?');
        if (r == true)
        {
            $('.beforeClick').hide();
            $('.afterClick').show();
            document.getElementById('downloading_' + prodId).style.display = 'block';
            document.getElementById('song_' + prodId).style.display = 'none';
            document.getElementById('download_loader_' + prodId).style.display = 'block';
            $('#form' + prodId).submit();
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

function addToQueue(songProdId, songProviderType, albumProdId, albumProviderType, queueId)
{
    var data = "songProdId=" + songProdId + "&songProviderType=" + songProviderType + "&albumProdId=" + albumProdId + "&albumProviderType=" + albumProviderType + "&queueId=" + queueId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "queues/addToQueue", // URL to request
        data: data, // post data
        success: function(response) {
            
            
            var playlist_list_popup = $('.playlist-options');
            playlist_list_popup.removeClass('active');
            var wishlist_list_popup = $('.wishlist-popover');
            wishlist_list_popup.removeClass('active');
            
            
            
            if (response.length == 6) {
                var msg = response.substring(0, 6);
            } else {
                var msg = response.substring(0, 5);
            }
            if (msg == 'error')
            {
                if (document.getElementById('flash-message'))
                {
                    document.getElementById('flash-message').innerHTML = '';
                    document.getElementById("flash-message").setAttribute("class", "");
                }

                document.getElementById("ajaxflashMessage44").style.display = "block";
                document.getElementById("ajaxflashMessage44").style.background = "red";
                document.getElementById('ajaxflashMessage44').innerHTML = 'There is some problem in adding song to Queuelist.';

                return false;
            } else if (msg == 'error1') {

                if (document.getElementById('flash-message'))
                {
                    document.getElementById('flash-message').innerHTML = '';
                    document.getElementById("flash-message").setAttribute("class", "");
                }

                document.getElementById("ajaxflashMessage44").style.display = "block";
                document.getElementById('ajaxflashMessage44').innerHTML = 'This song is already added to Queue';
            }
            else if (msg == 'invalid_for_stream')
            {
                if (document.getElementById('flash-message'))
                {
                    document.getElementById('flash-message').innerHTML = '';
                    document.getElementById("flash-message").setAttribute("class", "");
                }

                document.getElementById("ajaxflashMessage44").style.display = "block";
                document.getElementById('ajaxflashMessage44').innerHTML = 'This song is not allowed for Streaming';
            }
            else
            {
                var msg = response.substring(0, 7);
                if (msg == 'Success')
                {
                    if (document.getElementById('flash-message'))
                    {
                        document.getElementById('flash-message').innerHTML = '';
                        document.getElementById("flash-message").setAttribute("class", "");
                    }
                    document.getElementById("ajaxflashMessage44").style.display = "block";
                    document.getElementById('ajaxflashMessage44').innerHTML = 'Successfully added song to Queue';

                }
                else
                {
                    if (document.getElementById('flash-message'))
                    {
                        document.getElementById('flash-message').innerHTML = '';
                        document.getElementById("flash-message").setAttribute("class", "");
                    }

                    document.getElementById("ajaxflashMessage44").style.display = "block";
                    document.getElementById("ajaxflashMessage44").style.background = "red";
                    document.getElementById('ajaxflashMessage44').innerHTML = 'There is some problem arised when adding song to Queue.';
                    return false;
                }
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            if (document.getElementById('flash-message'))
            {
                document.getElementById('flash-message').innerHTML = '';
                document.getElementById("flash-message").setAttribute("class", "");
            }
            document.getElementById("ajaxflashMessage44").style.display = "block";
            document.getElementById("ajaxflashMessage44").style.background = "red";
            document.getElementById('ajaxflashMessage44').innerHTML = 'Ajax call for adding song to queue is unsuccessfull';
        }
    });
    return false;
}


function addAlbumSongsToQueue(albumSongsToBeAdded)
{
    
    var playlist_list_popup = $('.playlist-options');
    playlist_list_popup.removeClass('active');
    var wishlist_list_popup = $('.wishlist-popover');
    wishlist_list_popup.removeClass('active');
    
    
    var data = "albumSongs="+albumSongsToBeAdded;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "queues/addAlbumSongsToQueue", // URL to request
        data: data, // post data
        success: function(response) {
            if (response.length == 6) {
                var msg = response.substring(0, 6);
            } else {
                var msg = response.substring(0, 5);
            }
            if (msg == 'error')
            {
                if (document.getElementById('flash-message'))
                {
                    document.getElementById('flash-message').innerHTML = '';
                    document.getElementById("flash-message").setAttribute("class", "");
                }

                document.getElementById("ajaxflashMessage44").style.display = "block";
                document.getElementById("ajaxflashMessage44").style.background = "red";
                document.getElementById('ajaxflashMessage44').innerHTML = 'There is some problem in adding Album to Queuelist.';

                return false;
            } else if (msg == 'error1') {

                if (document.getElementById('flash-message'))
                {
                    document.getElementById('flash-message').innerHTML = '';
                    document.getElementById("flash-message").setAttribute("class", "");
                }

                document.getElementById("ajaxflashMessage44").style.display = "block";
                document.getElementById('ajaxflashMessage44').innerHTML = 'This Album is already added to Queue';
            }
            else if (msg == 'invalid_for_stream')
            {
                if (document.getElementById('flash-message'))
                {
                    document.getElementById('flash-message').innerHTML = '';
                    document.getElementById("flash-message").setAttribute("class", "");
                }

                document.getElementById("ajaxflashMessage44").style.display = "block";
                document.getElementById('ajaxflashMessage44').innerHTML = 'This Album is not allowed for Streaming';
            }
            else
            {
                var msg = response.substring(0, 7);
                if (msg == 'Success')
                {
                    if (document.getElementById('flash-message'))
                    {
                        document.getElementById('flash-message').innerHTML = '';
                        document.getElementById("flash-message").setAttribute("class", "");
                    }
                    document.getElementById("ajaxflashMessage44").style.display = "block";
                    document.getElementById('ajaxflashMessage44').innerHTML = 'Successfully added Album to Queue';

                }
                else
                {
                    if (document.getElementById('flash-message'))
                    {
                        document.getElementById('flash-message').innerHTML = '';
                        document.getElementById("flash-message").setAttribute("class", "");
                    }

                    document.getElementById("ajaxflashMessage44").style.display = "block";
                    document.getElementById("ajaxflashMessage44").style.background = "red";
                    document.getElementById('ajaxflashMessage44').innerHTML = 'There is some problem arised when adding Album to Queue.';
                    return false;
                }
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            if (document.getElementById('flash-message'))
            {
                document.getElementById('flash-message').innerHTML = '';
                document.getElementById("flash-message").setAttribute("class", "");
            }
            document.getElementById("ajaxflashMessage44").style.display = "block";
            document.getElementById("ajaxflashMessage44").style.background = "red";
            document.getElementById('ajaxflashMessage44').innerHTML = 'Ajax call for adding Album to queue is unsuccessfull';
        }
    });
    return false;
}


function removeSong(pdId,divId){
    
    var data = "songId=" + pdId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "queuelistdetails/removeSongFromQueue", // URL to request
        data: data, // post data
        success: function(response) {
            
            
            var playlist_list_popup = $('.playlist-options');
            playlist_list_popup.removeClass('active');
            var wishlist_list_popup = $('.wishlist-popover');
            wishlist_list_popup.removeClass('active');
            
            
            
            if (response.length == 6) {
                var msg = response.substring(0, 6);
            } else {
                var msg = response.substring(0, 5);
            }
            if (msg == 'error')
            {
                if (document.getElementById('flash-message'))
                {
                    document.getElementById('flash-message').innerHTML = '';
                    document.getElementById("flash-message").setAttribute("class", "");
                }

                document.getElementById("ajaxflashMessage44").style.display = "block";
                document.getElementById("ajaxflashMessage44").style.background = "red";
                document.getElementById('ajaxflashMessage44').innerHTML = 'There is some problem in deleting song from Queue';

                return false;
            } else if (msg == 'error1') {

                if (document.getElementById('flash-message'))
                {
                    document.getElementById('flash-message').innerHTML = '';
                    document.getElementById("flash-message").setAttribute("class", "");
                }

                document.getElementById("ajaxflashMessage44").style.display = "block";
                document.getElementById('ajaxflashMessage44').innerHTML = 'This song cannot be deleted';
            }
            else if (msg == 'error2')
            {
                if (document.getElementById('flash-message'))
                {
                    document.getElementById('flash-message').innerHTML = '';
                    document.getElementById("flash-message").setAttribute("class", "");
                }

                document.getElementById("ajaxflashMessage44").style.display = "block";
                document.getElementById('ajaxflashMessage44').innerHTML = 'You need to login in for Removing a Song from your Queue';
            }
            else
            {
                var msg = response.substring(0, 7);
                if (msg == 'Success')
                {
                    if (document.getElementById('flash-message'))
                    {
                        document.getElementById('flash-message').innerHTML = '';
                        document.getElementById("flash-message").setAttribute("class", "");
                    }
                    document.getElementById("ajaxflashMessage44").style.display = "block";
                    $('.clearfix'+divId).remove();
                    document.getElementById('ajaxflashMessage44').innerHTML = 'Successfully removed song from Queue';

                }
                else
                {
                    if (document.getElementById('flash-message'))
                    {
                        document.getElementById('flash-message').innerHTML = '';
                        document.getElementById("flash-message").setAttribute("class", "");
                    }

                    document.getElementById("ajaxflashMessage44").style.display = "block";
                    document.getElementById("ajaxflashMessage44").style.background = "red";
                    document.getElementById('ajaxflashMessage44').innerHTML = 'There is some problem arised when removing from Queue.';
                    return false;
                }
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            if (document.getElementById('flash-message'))
            {
                document.getElementById('flash-message').innerHTML = '';
                document.getElementById("flash-message").setAttribute("class", "");
            }
            document.getElementById("ajaxflashMessage44").style.display = "block";
            document.getElementById("ajaxflashMessage44").style.background = "red";
            document.getElementById('ajaxflashMessage44').innerHTML = 'Ajax call for removing song from queue is unsuccessfull';
        }
    });
    return false;
}




function loadSong(songFile, songTitle, artistName, songLength, prodId, providerType,playlistId) {
    console.log('load song contains');
    playlistId = (playlistId === undefined) ? 0 : playlistId;
    var newSong = [
        {
            playlistId: playlistId,
            songId: prodId,
            providerType: providerType,
            label: songTitle,
            songTitle: songTitle,
            artistName: artistName,
            songLength: songLength,
            data: songFile
        }
    ];

    //console.log(newSong);
    pushSongs(newSong);

}

function loadAlbumSong(albumSongs) { alert(albumSongs);
        playlist = base64_decode(albumSongs);
        playlist = JSON.parse(playlist);
        if (playlist.length) {
            pushSongs(playlist);
        }
}


function loadAlbumParameters() 
{        
        var albumPara   =   $("#playlist_data").html()
        alert(albumPara);
        loadAlbumSong(albumPara);
}


function base64_decode (data) {
  // http://kevin.vanzonneveld.net
  // +   original by: Tyler Akins (http://rumkin.com)
  // +   improved by: Thunder.m
  // +      input by: Aman Gupta
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   bugfixed by: Onno Marsman
  // +   bugfixed by: Pellentesque Malesuada
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +      input by: Brett Zamir (http://brett-zamir.me)
  // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // *     example 1: base64_decode('S2V2aW4gdmFuIFpvbm5ldmVsZA==');
  // *     returns 1: 'Kevin van Zonneveld'
  // mozilla has this native
  // - but breaks in 2.0.0.12!
  //if (typeof this.window['atob'] === 'function') {
  //    return atob(data);
  //}
  var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
  var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
    ac = 0,
    dec = "",
    tmp_arr = [];

  if (!data) {
    return data;
  }

  data += '';

  do { // unpack four hexets into three octets using index points in b64
    h1 = b64.indexOf(data.charAt(i++));
    h2 = b64.indexOf(data.charAt(i++));
    h3 = b64.indexOf(data.charAt(i++));
    h4 = b64.indexOf(data.charAt(i++));

    bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;

    o1 = bits >> 16 & 0xff;
    o2 = bits >> 8 & 0xff;
    o3 = bits & 0xff;

    if (h3 == 64) {
      tmp_arr[ac++] = String.fromCharCode(o1);
    } else if (h4 == 64) {
      tmp_arr[ac++] = String.fromCharCode(o1, o2);
    } else {
      tmp_arr[ac++] = String.fromCharCode(o1, o2, o3);
    }
  } while (i < data.length);

  dec = tmp_arr.join('');

  return dec;
}




//load the artist list via ajax    
function load_artist(link, id_serial, genre_name) {

    $('.album-list-span').html('');
    $('#album_details_container').html('');
    $('#ajax_artistlist_content').html('<span id="mydiv" style="height: 250px;width: 250px;position: relative;background-color: gray;"><img src="' + webroot + 'app/webroot/img/AjaxLoader.gif" style="display: block; left: 50%; margin-left: 147px; margin-top: 85px; position: absolute; top: 50%;"/></span>');
    // var data = "ajax_genre_name="+genre_name;
    var data = "ajax_genre_name=" + genre_name;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: link, // URL to request
        data: data, // post data
        success: function(response) {
            $('#ajax_artistlist_content').html(response);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            // alert('No artist available for this Genre.');
        }
    });
}

//load the albums list via ajax 
function showAllAlbumsList(albumListURL) {

    $('#album_details_container').html('');
    $('.album-list-span').html('<span id="mydiv" style="height: 250px; width: 250px; position: relative; background-color: gray;">\n\
            <img src="' + webroot + 'app/webroot/img/AjaxLoader.gif" style="display: block; left: 50%; margin-left: 115px; margin-top: 85px; position: absolute; top: 50%;"/></span>');

    var data = "";
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + albumListURL, // URL to request
        data: data, // post data
        success: function(response) {
            $('.album-list-span').html(response);
            $('a[title]').qtip({
                position: {
                    corner: {
                        target: 'topLeft',
                        tooltip: 'bottomRight'
                    }
                },
                style: {
                    color: '#444',
                    fontSize: 12,
                    border: {
                        color: '#444'
                    },
                    width: {
                        max: 350,
                        min: 0
                    },
                    tip: {
                        corner: 'bottomRight',
                        size: {
                            x: 5,
                            y: 5
                        }
                    }
                }
            });
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            // alert('No album available for this artist.');
        }
    });
}

//load the albums details via ajax
function showAlbumDetails(albumDetailURL) {

    $('#album_details_container').html('<span id="mydiv" style="height: 250px;width: 250px;position: relative;background-color: gray;"><img src="' + webroot + 'app/webroot/img/AjaxLoader.gif" style="display: block;left: 50%;margin-left: 398px;margin-top: 3px;position: absolute;top: 50%;"/></span>');

    var data = "";
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + albumDetailURL, // URL to request
        data: data, // post data
        success: function(response) {
            $('#album_details_container').html(response);
            $('a[title]').qtip({
                position: {
                    corner: {
                        target: 'topLeft',
                        tooltip: 'bottomRight'
                    }
                },
                style: {
                    color: '#444',
                    fontSize: 12,
                    border: {
                        color: '#444'
                    },
                    width: {
                        max: 350,
                        min: 0
                    },
                    tip: {
                        corner: 'bottomRight',
                        size: {
                            x: 5,
                            y: 5
                        }
                    }
                }
            });
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            // alert('Album detail not available.');
        }
    });
}


function showHideGrid(varType) {

    var top_100_grids = $('.top-100-grids');
    var top_100_songs_grid = $('#top-100-songs-grid');
    var top_100_videos_grid = $('#top-100-videos-grid');

    var songsIDVal = $('#songsIDVal');
    var videosIDVal = $('#videosIDVal');

    if (varType == 'songs') {
        videosIDVal.removeClass('active');
        songsIDVal.addClass('active');
        top_100_videos_grid.removeClass('active');
        top_100_songs_grid.addClass('active');
    } else {
        songsIDVal.removeClass('active');
        videosIDVal.addClass('active');
        top_100_songs_grid.removeClass('active');
        top_100_videos_grid.addClass('active');
    }
}

function showHideGridCommingSoon(varType) {

    var top_100_grids = $('.top-100-grids');
    var coming_soon_singles_grid = $('#coming-soon-singles-grid');
    var coming_soon_videos_grid = $('#coming-soon-videos-grid');

    var songsIDValComming = $('#songsIDValComming');
    var videosIDValComming = $('#videosIDValComming');



    if (varType == 'songs') {
        videosIDValComming.removeClass('active');
        songsIDValComming.addClass('active');

        coming_soon_videos_grid.removeClass('active');
        coming_soon_singles_grid.addClass('active');
    } else {
        songsIDValComming.removeClass('active');
        videosIDValComming.addClass('active');

        coming_soon_singles_grid.removeClass('active');
        coming_soon_videos_grid.addClass('active');
    }

}

$(document).ready(function() {
    
    $(document).on('click', '.play-queue-btn', function() {
        playlist = $('#playlist_data').text();
        playlist = JSON.parse(playlist);
        if (playlist.length) {
            pushSongs(playlist);
        }

    });
	
    $(document).on('click', '.play-album-btn', function() {
        playlist = $('#playlist_data').text();
        playlist = JSON.parse(playlist);
        if (playlist.length) {
            pushSongs(playlist);
        }

    });	

});




                    // Below method for removal of '#' & '#.' in between URL
                    // this is used in IE8 

                    // checking for #. in url 
//                    var indexOfHash = window.location.href.indexOf('#.');
//                    if (indexOfHash > 0)
//                    {
//                        var current_nav = '';
//
//                        var base_url = window.location.href.slice(0, window.location.href.indexOf('.com/') + 4);
//                        var url_slice = window.location.href.slice(indexOfHash + 2, window.location.href.length);
//
//                        if (url_slice.indexOf('_top_10') > -1 || url_slice.indexOf('my_history') > -1
//                                || url_slice.indexOf('_wishlist') > -1 || url_slice.indexOf('_releases') > -1)
//                        {
//                            if (window.location.href.indexOf('/homes') > -1)
//                            {
//                                current_nav = base_url + '/homes' + url_slice;
//                            }
//                            else
//                            {
//                                if (url_slice.indexOf('homes') > -1)
//                                {
//                                    current_nav = base_url + url_slice;
//                                }
//                                else
//                                {
//                                    current_nav = base_url + '/homes' + url_slice;
//                                }
//                            }
//                        }
//                        else
//                        {
//                            current_nav = base_url + url_slice;
//                        }
//
//                        if (url_slice.indexOf('_notification') > -1 || url_slice.indexOf('_account') > -1 || url_slice.indexOf('logout') > -1)
//                        {
//                            if (window.location.href.indexOf('/users') > -1)
//                            {
//                                current_nav = base_url + '/users' + url_slice;
//                            }
//                            else
//                            {
//                                if (url_slice.indexOf('users') > -1)
//                                {
//                                    current_nav = base_url + url_slice;
//                                }
//                                else
//                                {
//                                    current_nav = base_url + '/users' + url_slice;
//                                }
//                            }
//                        }
//
//
//                        current_nav = current_nav.replace('/homes/homes', '/homes');
//                        current_nav = current_nav.replace('com//', 'com/');
//
//                        window.location.href = current_nav;
//                        return true;
//                    }
//
//                    // chekcing for # in url
//                    var indexOfHash = window.location.href.indexOf('#');
//                    if (indexOfHash > 0)
//                    {
//                        var current_nav = '';
//
//                        var base_url = window.location.href.slice(0, window.location.href.indexOf('.com/') + 5);
//                        var url_slice = window.location.href.slice(indexOfHash + 1, window.location.href.length);
//
//                        if (url_slice.indexOf('_top_10') > -1 || url_slice.indexOf('my_history') > -1
//                                || url_slice.indexOf('_wishlist') > -1 || url_slice.indexOf('_releases') > -1)
//                        {
//                            if (window.location.href.indexOf('/homes') > -1)
//                            {
//                                current_nav = base_url + '/homes' + url_slice;
//                            }
//                            else
//                            {
//                                if (url_slice.indexOf('homes') > -1)
//                                {
//                                    current_nav = base_url + url_slice;
//                                }
//                                else
//                                {
//                                    current_nav = base_url + '/homes' + url_slice;
//                                }
//                            }
//                        }
//                        else
//                        {
//                            current_nav = base_url + url_slice;
//                        }
//
//                        if (url_slice.indexOf('_notification') > -1 || url_slice.indexOf('_account') > -1 || url_slice.indexOf('logout') > -1)
//                        {
//                            if (window.location.href.indexOf('/users') > -1)
//                            {
//                                current_nav = base_url + '/users' + url_slice;
//                            }
//                            else
//                            {
//                                if (url_slice.indexOf('users') > -1)
//                                {
//                                    current_nav = base_url + url_slice;
//                                }
//                                else
//                                {
//                                    current_nav = base_url + '/users' + url_slice;
//                                }
//                            }
//                        }
//
//                        current_nav = current_nav.replace('/homes/homes', '/homes');
//                        current_nav = current_nav.replace('com//', 'com/');
//
//                        window.location.href = current_nav;
//                        return true;
//                    }

                    // After removal of '#' & '#.' the below statements are exceuted

