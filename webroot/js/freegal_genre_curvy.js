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

  if(document.getElementById('genre') != null) {
    curvyCorners(genre, "#genre");
  }
  
  if(document.getElementById('genre_artist_search') != null) {
    curvyCorners(genreArtist, "#genre_artist_search");
  }
}