addEvent(window, 'load', initCorners);

function initCorners() {
  var genre = {
      tl: { radius: 5 },
	  tr: { radius: 5 },
	  bl: { radius: 5 },
	  br: { radius: 5 },
	  antiAlias: true
  }
  var all = {
	tl: { radius: 5 },
	tr: { radius: 5 },
	antiAlias: true
  }

  curvyCorners(genre, ".genreAlltl");
  curvyCorners(genre, ".genreAlltr");
  curvyCorners(all, "#genreViewAllBox");
}

function show_uploaded_images() {
  $("a[rel='image']").colorbox();
}