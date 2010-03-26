addEvent(window, 'load', initCorners);

  function initCorners() {
    var settings = {
		tl: { radius: 5 },
		tr: { radius: 5 },
		bl: { radius: 0 },
	    br: { radius: 0 },
		antiAlias: true
	}
	curvyCorners(settings, "#advance_search_box");
  }