addEvent(window, 'load', initCorners);

  function initCorners() {
    var genre = {
    	tl: { radius: 5 },
	    tr: { radius: 5 },
	    antiAlias: true
    }
    var genreArtist = {
    	tl: { radius: 5 },
	    tr: { radius: 5 },
	    antiAlias: true
    }
	var artist = {
		tl: { radius: 5},
		bl: { radius: 5},
		antiAlias: true
	}
	
	var middle = {
		tl: { radius: 0 },
		tr: { radius: 0 },
		bl: { radius: 0 },
	    br: { radius: 0 },
		antiAlias: true
	}
	
	var download = {
		tr: { radius: 5},
		br: { radius: 5},
		antiAlias: true
	}

    curvyCorners(genre, "#genre");
    curvyCorners(genreArtist, "#genre_artist_search");
	// curvyCorners(artist, "#genreArtist");
	// 	curvyCorners(middle, "#genreAlbum");
	// 	curvyCorners(middle, "#genreTrack");
	// 	curvyCorners(download, "#genreDownload");
  }