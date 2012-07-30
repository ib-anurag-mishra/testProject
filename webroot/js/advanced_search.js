

show_blocksdiv = document.getElementById('show_blocks');
if(show_blocksdiv){
	show_blocksdiv.style.display="none";
}

function advanced_search_show_hide(id){

	leftColblockdiv = document.getElementById('leftColblock');
	rightColblockdiv = document.getElementById('rightCol');
	hide_blocksdiv = document.getElementById('hide_blocks');
	show_blocksdiv = document.getElementById('show_blocks');
	if(id == 'show_div'){
		//To show
		leftColblockdiv.style.display="block";
		rightColblockdiv.style.display="block";
		hide_blocksdiv.style.display="block";
		show_blocksdiv.style.display="none";
	}
	else {
		//To hide
		leftColblockdiv.style.display="none";
		rightColblockdiv.style.display="none";
		hide_blocksdiv.style.display="none";
		show_blocksdiv.style.display="block";

	}
}

$(document).ready(function() {
  $('#search_query').keypress(function(event) {
		//auto_check();
		if (event.which == '13') {
      //$('#search_query').val();
      $('#searchQueryForm').submit();
		}
	});
  $("#search_query").autocomplete(webroot+"search/autocomplete",
  {
		minChars: 1,
		cacheLength: 10,
		autoFill: false,
    extraParams: {
      type:$('#search_type').val()
    }
	}).result(function(e, item) {
    $('#auto').attr('value', 1);
  });
});