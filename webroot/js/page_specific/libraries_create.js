// Page Specific JavaScript Document

$(function() {
	var _loadingDiv = $("#loadingDiv");
	$('#next_btn1').click(function(){
		_loadingDiv.show();
		$("#LibraryLibraryStepNum").val('step1');
		$.post(webroot+'admin/libraries/ajax_validate',
			$('#LibraryAdminLibraryformForm').serializeArray(),
			afterValidate,
			"json"
		);
		return false;
	});
	
	// Post-submit callback 
	function afterValidate(data, status)  {
		$(".message").remove();
		$(".error-message").remove();
		$(".error-br").remove();
		
		if (data.errors) {
			onError(data.errors);
		} else if (data.success) {
			onSuccess(data.success);
		}
	}
	
	function onSuccess(data) {
		flashMessage(data.message);
		_loadingDiv.hide();
		window.setTimeout(function() {
			window.location.href = webroot+'admin/libraries/ajax_validate';
		}, 2000);
	};
	
	function onError(data) {
		flashMessage(data.message);
		$.each(data.data, function(model, errors) {
			for (fieldName in this) {
				var element = $("#" + camelize(model + '_' + fieldName));
				var _insertBR = $(document.createElement('br')).insertAfter(element);
				_insertBR.addClass('error-br');
				var _insert = $(document.createElement('span')).insertAfter(_insertBR);
				_insert.addClass('error-message').text(this[fieldName])
			}
			_loadingDiv.hide();
		});
	};
	
	function flashMessage(message) {
		var _insert = $(document.createElement('div')).css('display', 'none');
		_insert.attr('id', 'flashMessage').addClass('message').text(message);
		$(".formError").append(_insert);
		_insert.fadeIn();
	}

	function camelize(string) {
		var a = string.split('_'), i;
		s = [];
		for (i=0; i<a.length; i++){
			s.push(a[i].charAt(0).toUpperCase() + a[i].substring(1));
		}
		s = s.join('');
		return s;
	}

});