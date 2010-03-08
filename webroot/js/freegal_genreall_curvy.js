addEvent(window, 'load', initCorners);

  function initCorners() {
    var genre = {
    	tl: { radius: 5 },
	    tr: { radius: 5 },
		bl: { radius: 5 },
		br: { radius: 5 },
	    antiAlias: true
    }

	curvyCorners(genre, "#genreAlltl");
	curvyCorners(genre, "#genreAlltr");
	curvyCorners(genre, "#genreAllbl");
	curvyCorners(genre, "#genreAllbr");
  }