addEvent(window, 'load', initCorners);

  function initCorners() {
    var about = {
    	tl: { radius: 5 },
	    tr: { radius: 5 },
	    antiAlias: true
    }

    curvyCorners(about, "#aboutBox");
  }