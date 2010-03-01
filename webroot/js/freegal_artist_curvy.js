addEvent(window, 'load', initCorners);

  function initCorners() {
    var artist = {
    	tl: { radius: 5 },
	    tr: { radius: 5 },
	    antiAlias: true
    }

	var title = {
		tl: { radius: 5 },
		tr: { radius: 5 },
		bl: { radius: 5 },
	    br: { radius: 5 },
		antiAlias: true
	}

    curvyCorners(artist, "#artistBox");
	// curvyCorners(title, "#albumBox");
	// 	curvyCorners(title, "#songBox");
  }