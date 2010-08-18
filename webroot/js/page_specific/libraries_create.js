// Page Specific JavaScript Document
$(function() {
	var _loadingDiv = $("#loadingDiv");
	$('#next_btn1').click(function(){
		$('#next_btn1').attr('disabled', 'disabled');
		_loadingDiv.show();
		$("#LibraryLibraryStepNum").val('1');
		if($("#LibraryId").val() != "") {
			var postURL = webroot+'admin/libraries/ajax_validate/id:'+$("#LibraryId").val();
		}
		else {
			var postURL = webroot+'admin/libraries/ajax_validate';
		}
		$.post( postURL,
			$('#LibraryAdminForm').serializeArray(),
			afterValidate,
			"json"
		);
		return false;
	});
	
	$('#next_btn2').click(function(){
		$('#next_btn2').attr('disabled', 'disabled');
		_loadingDiv.show();
		$("#LibraryLibraryStepNum").val('2');
		if($("#LibraryId").val() != "") {
			var postURL = webroot+'admin/libraries/ajax_validate/id:'+$("#LibraryId").val();
		}
		else {
			var postURL = webroot+'admin/libraries/ajax_validate';
		}
		$.post( postURL,
			$('#LibraryAdminForm').serializeArray(),
			afterValidate,
			"json"
		);
		return false;
	});
	
	$('#next_btn3').click(function(){
		$('#next_btn3').attr('disabled', 'disabled');
		_loadingDiv.show();
		$("#LibraryLibraryStepNum").val('3');
		if($("#LibraryId").val() != "") {
			var postURL = webroot+'admin/libraries/ajax_validate/id:'+$("#LibraryId").val();
		}
		else {
			var postURL = webroot+'admin/libraries/ajax_validate';
		}
		$.post( postURL,
			$('#LibraryAdminForm').serializeArray(),
			afterValidate,
			"json"
		);
		return false;
	});
	
	$('#next_btn4').click(function(){
		$('#next_btn4').attr('disabled', 'disabled');
		_loadingDiv.show();
		$("#LibraryLibraryStepNum").val('4');
		if($("#LibraryId").val() != "") {
			var postURL = webroot+'admin/libraries/ajax_validate/id:'+$("#LibraryId").val();
		}
		else {
			var postURL = webroot+'admin/libraries/ajax_validate';
		}
		$.post( postURL,
			$('#LibraryAdminForm').serializeArray(),
			afterValidate,
			"json"
		);
		return false;
	});
	
	$('#next_btn5').click(function(){
		$('#next_btn5').attr('disabled', 'disabled');
		_loadingDiv.show();
		$("#LibraryLibraryStepNum").val('5');
		if($("#LibraryId").val() != "") {
			var postURL = webroot+'admin/libraries/ajax_validate/id:'+$("#LibraryId").val();
		}
		else {
			var postURL = webroot+'admin/libraries/ajax_validate';
		}
		$.post( postURL,
			$('#LibraryAdminForm').serializeArray(),
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
	
	$('#step1').click(function(){
		$(".error_message").remove();
		$(".success_message").remove();
		$(".error-message").remove();
		$(".error-br").remove();
		$("#LibraryLibraryStepNum").val(1);
		$("div[id^='step']").each(function() {
			$(this).removeClass('active_step').addClass('inactive_step');
		});
		$(this).removeClass('inactive_step').addClass('active_step');
		$("div[id^='form_step']").each(function() {
			$(this).hide();
		});
		$("#form_step1").fadeIn();
		_loadingDiv.hide();
		$("input[id^='next_btn']").each(function() {
			$(this).removeAttr("disabled");
		});
	});
	
	$('#step2').click(function(){
		$(".error_message").remove();
		$(".success_message").remove();
		$(".error-message").remove();
		$(".error-br").remove();
		$("#LibraryLibraryStepNum").val(2);
		$("div[id^='step']").each(function() {
			$(this).removeClass('active_step').addClass('inactive_step');
		});
		$(this).removeClass('inactive_step').addClass('active_step');
		$("div[id^='form_step']").each(function() {
			$(this).hide();
		});
		$("#form_step2").fadeIn();
		_loadingDiv.hide();
		$("input[id^='next_btn']").each(function() {
			$(this).removeAttr("disabled");
		});
	});
	
	$('#step3').click(function(){
		$(".error_message").remove();
		$(".success_message").remove();
		$(".error-message").remove();
		$(".error-br").remove();
		$("#LibraryLibraryStepNum").val(3);
		$("div[id^='step']").each(function() {
			$(this).removeClass('active_step').addClass('inactive_step');
		});
		$(this).removeClass('inactive_step').addClass('active_step');
		$("div[id^='form_step']").each(function() {
			$(this).hide();
		});
		$("#form_step3").fadeIn();
		_loadingDiv.hide();
		$("input[id^='next_btn']").each(function() {
			$(this).removeAttr("disabled");
		});
	});
	
	$('#step4').click(function(){
		$(".error_message").remove();
		$(".success_message").remove();
		$(".error-message").remove();
		$(".error-br").remove();
		$("#LibraryLibraryStepNum").val(4);
		$("div[id^='step']").each(function() {
			$(this).removeClass('active_step').addClass('inactive_step');
		});
		$(this).removeClass('inactive_step').addClass('active_step');
		$("div[id^='form_step']").each(function() {
			$(this).hide();
		});
		$("#form_step4").fadeIn();
		_loadingDiv.hide();
		$("input[id^='next_btn']").each(function() {
			$(this).removeAttr("disabled");
		});
	});
	
	$('#step5').click(function(){
		$(".error_message").remove();
		$(".success_message").remove();
		$(".error-message").remove();
		$(".error-br").remove();
		$("#LibraryLibraryStepNum").val(5);
		$("div[id^='step']").each(function() {
			$(this).removeClass('active_step').addClass('inactive_step');
		});
		$(this).removeClass('inactive_step').addClass('active_step');
		$("div[id^='form_step']").each(function() {
			$(this).hide();
		});
		$("#form_step5").fadeIn();
		_loadingDiv.hide();
		$("input[id^='next_btn']").each(function() {
			$(this).removeAttr("disabled");
		});
	});
	
	$('#LibraryLibraryBgcolor, #LibraryLibraryNavBgcolor, #LibraryLibraryBoxheaderBgcolor, #LibraryLibraryBoxheaderTextColor, #LibraryLibraryTextColor, #LibraryLibraryLinksColor, #LibraryLibraryLinksHoverColor, #LibraryLibraryNavlinksColor, #LibraryLibraryNavlinksHoverColor, #LibraryLibraryBoxHeaderColor, #LibraryLibraryBoxHoverColor').ColorPicker({
		onSubmit: function(hsb, hex, rgb, el) {
			$(el).val(hex.toUpperCase());
			$(el).ColorPickerHide();
		},
		onBeforeShow: function () {
			$(this).ColorPickerSetColor(this.value);
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
		if(typeof(data.data.Library.id) != 'undefined')
		{
			var libraryID = data.data.Library.id;
		}
		else {
			var libraryID = '';
		}
		
		if($("#LibraryId").val() != "") {
			var postURL = webroot+'admin/libraries/doajaxfileupload/id:'+$("#LibraryId").val();
		}
		else {
			var postURL = webroot+'admin/libraries/doajaxfileupload';
		}
		
		if(currentStep == '5') {
			$.ajaxFileUpload
			(
				
				{
					url:postURL,
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
									if(libraryID == "") {
										flashMessage('The library has been added successfully! You will be redirected shortly...', 'success');
									}
									else {
										flashMessage('The library has been updated successfully! You will be redirected shortly...', 'success');
									}
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
			$("#LibraryLibraryStepNum").val(data.stepNum);
			$("div[id^='step']").each(function() {
				$(this).removeClass('active_step').addClass('inactive_step');
			});
			$('#step'+data.stepNum).removeClass('inactive_step').addClass('active_step');
			$("div[id^='form_step']").each(function() {
				$(this).hide();
			});
			$("#form_step"+data.stepNum).fadeIn();
			$("input[id^='next_btn']").each(function() {
				$(this).removeAttr("disabled");
			});
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
var counter = 0;
var fields;
function addVariable(val) {
		if(counter < 1){
			fields = val;
		}
		var textData =  "<table id='table"+fields+"' cellspacing='6' cellpadding='0' border='0'><tr id='var"+fields+"'><td align='right' width='250'><label>Library Authentication Variable</label></td><td aligh='left' class='libalign'><input type='text' name='data[Variable]["+fields+"][authentication_variable]' class='form_fields' size='50'></td></tr><tr id='response"+fields+"'><td align='right' width='250'><label>Library Authentication Response</label></td><td aligh='left' class='libalign'><input type='text' name='data[Variable]["+fields+"][authentication_response]' class='form_fields' size='50'></td></tr><tr><td align='right' width='250'><label>Library Comparison Operator</label></td><td align='left' style='padding-left:20px' class='libselect'><select name='data[Variable]["+fields+"][comparison_operator]'><option value=''>Select a Operator</option><option value='='>=</option><option value='>'>></option><option value='<'><</option></select></td></tr><tr id='error"+fields+"'><td align='right' width='250'><label>Library Error Message</label></td><td aligh='left' class='libalign'><input type='text' name='data[Variable]["+fields+"][error_msg]' class='form_fields' size='50'><input type='button' value='Remove' class='form_fields' onClick='removeVariable("+fields+");'></td></tr></table>";
		$("#innv_var").append(textData);
		fields++;
		counter++;
}
function removeVariable(val){
	$("#table"+val).html('');
}