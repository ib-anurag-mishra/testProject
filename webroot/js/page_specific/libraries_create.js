// Page Specific JavaScript Document
$(function() {
	var _loadingDiv = $("#loadingDiv");
	$('#next_btn1').click(function(){
		$('#next_btn1').attr('disabled', 'disabled');
		_loadingDiv.show();
		$("#LibraryLibraryStepNum").val('1');
		$.post(webroot+'admin/libraries/ajax_validate',
			$('#LibraryAdminLibraryformForm').serializeArray(),
			afterValidate,
			"json"
		);
		return false;
	});
	
	$('#next_btn2').click(function(){
		$('#next_btn2').attr('disabled', 'disabled');
		_loadingDiv.show();
		$("#LibraryLibraryStepNum").val('2');
		$.post(webroot+'admin/libraries/ajax_validate',
			$('#LibraryAdminLibraryformForm').serializeArray(),
			afterValidate,
			"json"
		);
		return false;
	});
	
	$('#next_btn3').click(function(){
		$('#next_btn3').attr('disabled', 'disabled');
		_loadingDiv.show();
		$("#LibraryLibraryStepNum").val('3');
		$.post(webroot+'admin/libraries/ajax_validate',
			$('#LibraryAdminLibraryformForm').serializeArray(),
			afterValidate,
			"json"
		);
		return false;
	});
	
	$('#next_btn4').click(function(){
		$('#next_btn4').attr('disabled', 'disabled');
		_loadingDiv.show();
		$("#LibraryLibraryStepNum").val('4');
		$.post(webroot+'admin/libraries/ajax_validate',
			$('#LibraryAdminLibraryformForm').serializeArray(),
			afterValidate,
			"json"
		);
		return false;
	});
	
	$('#next_btn5').click(function(){
		$('#next_btn5').attr('disabled', 'disabled');
		_loadingDiv.show();
		$("#LibraryLibraryStepNum").val('5');
		$.post(webroot+'admin/libraries/ajax_validate',
			$('#LibraryAdminLibraryformForm').serializeArray(),
			afterValidate,
			"json"
		);
		return false;
	});
	
	$('input').bind('keydown',function(e){
		if(e.which == 13) {
			var currentStep = $("#LibraryLibraryStepNum").val();
			$('#next_btn'+currentStep).focus();
		}
	});
	
	$('#LibraryLibraryDownloadLimit').change(function(){
		if($(this).val() == 'manual') {
			$('#manual_download').show();
		}
		else {
			$('#LibraryLibraryDownloadLimitManual').val('');
			$('#manual_download').hide();
		}
	});
	
	// Post-submit callback 
	function afterValidate(data, status)  {
		$(".error_message").remove();
		$(".success_message").remove();
		$(".error-message").remove();
		$(".error-br").remove();
		
		if (data.errors) {
			onError(data.errors, '');
		} else if (data.success) {
			onSuccess(data.success);
		}
	}
	
	function onSuccess(data) {
		var currentStep = $("#LibraryLibraryStepNum").val();
		if(typeof(data.data.LibraryPurchase.library_id) != 'undefined')
		{
			var libraryID = data.data.LibraryPurchase.library_id;
		}
		else {
			var libraryID = '';
		}
		if(currentStep == '1' || currentStep == '5') {
			$.ajaxFileUpload
			(
				
				{
					url:webroot+'admin/libraries/doajaxfileupload',
					secureuri:false,
					fileElementId:'fileToUpload',
					StepId:"LibraryLibraryStepNum",
					LibraryId:libraryID,
					dataType: 'json',
					success: function (data, status)
					{
						if(typeof(data.error) != 'undefined')
						{
							if(data.error != '') {
								onError(data.error, 'file_field');
							}
							else {
								if(currentStep == '5') {
									flashMessage('The library has been added successfully! You will be redirected shortly...', 'success');
									window.setTimeout(function() {
										window.location = webroot+'admin/libraries/managelibrary';
									}, 1000);
								}
								else {
									flashMessage('You will be redirected to the next step shortly...', 'success');
									window.setTimeout(function() {
										var nextStep = parseInt(currentStep)+1;
										$("#LibraryLibraryStepNum").val(nextStep);
										$('#step'+currentStep).removeClass('active_step').addClass('inactive_step');
										$('#step'+nextStep).removeClass('inactive_step').addClass('active_step');
										$("#form_step"+currentStep).hide();
										$("#form_step"+nextStep).fadeIn();
										_loadingDiv.hide();
										$('#next_btn'+currentStep).removeAttr("disabled");
										$('#next_btn'+nextStep).removeAttr("disabled");
									}, 1000);
								}
							}
						}
					},
					error: function (data, status, e)
					{
					    onError(e, 'file_field');
					}
				}
			)
		}
		else {
			flashMessage(data.message, 'success');
			window.setTimeout(function() {
				var nextStep = parseInt(currentStep)+1;
				$("#LibraryLibraryStepNum").val(nextStep);
				$('#step'+currentStep).removeClass('active_step').addClass('inactive_step');
				$('#step'+nextStep).removeClass('inactive_step').addClass('active_step');
				$("#form_step"+currentStep).hide();
				$("#form_step"+nextStep).fadeIn();
				_loadingDiv.hide();
				$('#next_btn'+currentStep).removeAttr("disabled");
				$('#next_btn'+nextStep).removeAttr("disabled");
			}, 1000);
		}
	}
	
	function onError(data, arg) {
		var currentStep = $("#LibraryLibraryStepNum").val();
		if(arg == 'file_field') {
			var errorStep = '1';
			$("#LibraryLibraryStepNum").val(errorStep);
			$('#step'+currentStep).removeClass('active_step').addClass('inactive_step');
			$('#step'+errorStep).removeClass('inactive_step').addClass('active_step');
			$("#form_step"+currentStep).hide();
			$("#form_step"+errorStep).fadeIn();
			flashMessage('To proceed further please enter the data correctly.', 'error');
			var element = $("#fileToUpload");
			var _insertBR = $(document.createElement('br')).insertAfter(element);
			_insertBR.addClass('error-br');
			var _insert = $(document.createElement('span')).insertAfter(_insertBR);
			_insert.addClass('error-message').text(data)
			_loadingDiv.hide();
			$('#next_btn'+currentStep).removeAttr("disabled");
			$('#next_btn'+errorStep).removeAttr("disabled");
		}
		else {
			flashMessage(data.message, 'error');
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
			$('#next_btn'+currentStep).removeAttr("disabled");
		}
	}
	
	function flashMessage(message, status) {
		var _insert = $(document.createElement('div')).css('display', 'none');
		if (status == 'success') {
			_insert.attr('id', 'flashMessage').addClass('success_message').text(message);
		}
		else {
			_insert.attr('id', 'flashMessage').addClass('error_message').text(message);	
		}
		var currentStep = $("#LibraryLibraryStepNum").val();
		$("#formError"+currentStep).append(_insert);
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