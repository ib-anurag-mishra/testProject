addEvent(window, 'load', initCorners);

function initCorners() {
  var settings = {
    tl: { radius: 5 },
    tr: { radius: 5 },
    bl: { radius: 5 },
    br: { radius: 5 },
    antiAlias: true
  }

  var search_settings = {
    tl: { radius: 5 },
    tr: { radius: 5 },
    antiAlias: true
  }

  /*
  Usage:

  curvyCorners(settingsObj, selectorStr);
  curvyCorners(settingsObj, Obj1[, Obj2[, Obj3[, . . . [, ObjN]]]]);

  selectorStr ::= complexSelector [, complexSelector]...
  complexSelector ::= singleSelector[ singleSelector]
  singleSelector ::= idType | classType
  idType ::= #id
  classType ::= [tagName].className
  tagName ::= div|p|form|blockquote|frameset // others may work
  className : .name
  selector examples:
    #mydiv p.rounded
    #mypara
    .rounded
  */
  
  curvyCorners(settings, "#suggestionsBox");
  curvyCorners(settings, "#suggestions");
  curvyCorners(settings, "#artist_searchBox");
  curvyCorners(settings, "#artist_search");
  curvyCorners(settings, "#featured_artist");
  curvyCorners(settings, "#newly_added");
  curvyCorners(settings, "#slideshow");
}

function searchArtist(searchID) {
  var data = "search="+ searchID;
  jQuery.ajax({
	type: "post",  // Request method: post, get
	url: webroot+"homes/artistSearch", // URL to request
	data: data,  // post data
	success: function(response) {
		document.getElementById("artist_searchBox").innerHTML = response;
	},
	error:function (XMLHttpRequest, textStatus, errorThrown) {
		alert(textStatus);
	}
  });
  return false; 
}