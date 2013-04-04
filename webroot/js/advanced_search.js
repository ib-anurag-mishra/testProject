

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
      //alert($('#search_query').val());
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
    },
    formatItem:function(data){
      return data[0];
    },
    formatResult:function(data){
      return data[1];
    }
	}).result(function(e, item) {
    $('#auto').attr('value', 1);
    if(item[2]==1){
      $('#search_type').val('artist');
    } else if(item[2]==2){
      $('#search_type').val('album');
    } else if(item[2]==3){
      $('#search_type').val('song');
    }
  });
});