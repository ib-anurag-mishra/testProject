// Page Specific JavaScript Document
$(function() {
	var _loadingDiv = $("#loadingDiv");

	$('#ContractLibraryStreamingPurchaseLibraryContractStartDate , #ContractLibraryStreamingPurchaseLibraryContractEndDate').click(function(){
		$('#ui-datepicker-div').css('left','363px');

	});
	
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
	var upgrade = 1;
	$('#upgrade').click(function(){
		if(upgrade == 1){
			$("#pur_order").show();
			$("#pur_amount").show();
			$("#pur_track").show();
			upgrade = 0;	
		} else {
			$("#pur_order").hide();
			$("#pur_amount").hide();
			$("#pur_track").hide();
			upgrade = 1;			
		}
	});
        
	var strupgrade = 1;
	$('#strupgrade').click(function(){
		if(strupgrade == 1){
			$("#str_order").show();
			$("#str_amount").show();
			$("#str_track").show();
			strupgrade = 0;	
		} else {
			$("#str_order").hide();
			$("#str_amount").hide();
			$("#str_track").hide();
			strupgrade = 1;			
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
	
	$('#preview').click(function() {
		var bgColor = document.getElementById('LibraryLibraryBgcolor').value;
		var navBgColor = document.getElementById('LibraryLibraryNavBgcolor').value;
		var boxheaderBgColor = document.getElementById('LibraryLibraryBoxheaderBgcolor').value;
		var boxheaderTextColor = document.getElementById('LibraryLibraryBoxheaderTextColor').value;
		var boxHoverColor = document.getElementById('LibraryLibraryBoxHoverColor').value;
		var textColor = document.getElementById('LibraryLibraryTextColor').value;
		var linkColor = document.getElementById('LibraryLibraryLinksColor').value;
		var linkHoverColor = document.getElementById('LibraryLibraryLinksHoverColor').value;
		var navLinksColor = document.getElementById('LibraryLibraryNavlinksColor').value;
		var navLinksHoverColor = document.getElementById('LibraryLibraryNavlinksHoverColor').value;
		if($('#LibraryShowLibraryName').attr('checked')){
			var libraryName = '';
		}
		else{
			var libraryName = document.getElementById('LibraryLibraryName').value;
		}
		
		if ($('#imagePreview').length != 0){
			var imagePreview = document.getElementById('imagePreview').src;
		}
		else{
			var imagePreview = '';
		}
		
		var data = "bgColor="+bgColor+"&libraryName="+libraryName+"&navBgColor="+navBgColor+"&boxheaderBgColor="+boxheaderBgColor+"&boxheaderTextColor="+boxheaderTextColor+"&boxHoverColor="+boxHoverColor+"&textColor="+textColor+"&linkColor="+linkColor+"&linkHoverColor="+linkHoverColor+"&imagePreview="+imagePreview+"&navLinksColor="+navLinksColor+"&navLinksHoverColor="+navLinksHoverColor;
		var getURL = webroot+"admin/libraries/ajax_preview?"+data;
		
		newwindow=window.open(getURL,'name','top=150,left=150,height=700,width=800,scrollbars=yes');
		return false;
		
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
var incr = 0;
var fields;
function addVariable(val) {
		if(counter < 1){
			fields = val;
		}
                
		if($("#dropDown").val() == 'sip2_var' || $("#dropDown").val() == 'sip2_var_wo_pin'){
                    var textData =  "<table id='table"+fields+"' cellspacing='6' cellpadding='0' border='0'><tr id='var"+fields+"'><td align='right' width='250' class='libalign'><label>Library Authentication Variable</label></td><td aligh='left'><input type='text' name='data[Variable]["+fields+"][authentication_variable]' class='form_fields' size='50'></td></tr><tr><td align='right' width='250'><label>Library Comparison Operator</label></td><td align='left' style='padding-left:20px' class='libselect'><select name='data[Variable]["+fields+"][comparison_operator]' id='oprDrop"+fields+"' onchange='getResponse("+fields+");getArray("+fields+");showLibAuthIndex("+fields+")'><option value=''>Select a Operator</option><option value='='>=</option><option value='>'>></option><option value='<'><</option><option value='<>'><></option><option value='begins'>begins</option><option value='notbegins'>does not begin with</option><option value='cmp_pos'>cmp_pos</option><option value='contains'>Contains</option><option value='notcontain'>does not contain</option><option value='date'>Expired</option><option value='=(Fixed)'>=(Fixed)</option><option value='>(Fixed)'>>(Fixed)</option><option value='<(Fixed)'><(Fixed)</option><option value='<>(Fixed)'><>(Fixed)</option><option value='begins(Fixed)'>begins(Fixed)</option><option value='notbegins(Fixed)'>does not begin with(Fixed)</option><option value='cmp_pos(Fixed)'>cmp_pos(Fixed)</option><option value='contains(Fixed)'>Contains(Fixed)</option><option value='notcontain(Fixed)'>does not contain(Fixed)</option></select></td></tr><tr id='response"+fields+"'><td align='right' width='250' class='libalign'><label>Library Authentication Response</label></td><td aligh='left'><input type='text' name='data[Variable]["+fields+"][authentication_response]' class='form_fields' size='50' id='responseField"+fields+"'></td></tr><tr id='authentication_response_pos"+fields+"' style='display:none' ><td width='250' align='right'><label>Library Response Position</label></td><td align='left' class='libselect' ><input type='text' value='' size='20' class='form_fields' name='data[Variable]["+fields+"][authentication_response_pos]'></td></tr><tr id='authentication_variable_index"+fields+"'  style='font-size: 12px;' ><td width='250' align='right'><label>Library Authentication Variable Index</label></td><td align='left' class='libselect' ><input type='radio' value='1' class='form_fields' name='data[Variable]["+fields+"][variable_index]' id='varialbe_index_flag0"+fields+"'> Index value <input type='text' value='' size='15' class='form_fields' name='data[Variable]["+fields+"][authentication_variable_index]' id='authentication_variable_index"+fields+"'> <input type='radio' value='2' class='form_fields' name='data[Variable]["+fields+"][variable_index]' id='varialbe_index_flag1"+fields+"'> All Index </td></tr><tr id='resArr"+fields+"' style='display:none'><td align='right' width='250'><label>Result Array</label></td><td align='left' style='padding-left:20px' class='libselect'><select name='data[Variable]["+fields+"][result_arr]' id='resArr"+fields+"'><option value='fixed'>Fixed</option><option value='variable'>Variable</option></select></td></tr><tr><td align='right' width='250'><label>Message No</label></td><td align='left' style='padding-left:20px' class='libselect'><select name='data[Variable]["+fields+"][message_no]' id='msg"+fields+"'><option value=''>Select a Message No</option><option value='24'>24</option><option value='64'>64</option><option value='98'>98</option></select></td></tr><tr id='error"+fields+"'><td align='right' width='250' class='libalign'><label>Library Error Message</label></td><td aligh='left'><input type='text' name='data[Variable]["+fields+"][error_msg]' class='form_fields' size='50'><input type='button' value='Remove' class='form_fields' onClick='removeVariable("+fields+");'></td></tr></table>";
                }else{                     
                    var textData =  "<table id='table"+fields+"' cellspacing='6' cellpadding='0' border='0'><tr id='var"+fields+"'><td align='right' width='250' class='libalign'><label>Library Authentication Variable</label></td><td aligh='left'><input type='text' name='data[Variable]["+fields+"][authentication_variable]' class='form_fields' size='50'></td></tr><tr><td align='right' width='250'><label>Library Comparison Operator</label></td><td align='left' style='padding-left:20px' class='libselect'><select name='data[Variable]["+fields+"][comparison_operator]' id='oprDrop"+fields+"' onchange='getResponse("+fields+");getArray("+fields+");showLibAuthIndex("+fields+")'><option value=''>Select a Operator</option><option value='='>=</option><option value='>'>></option><option value='<'><</option><option value='<>'><></option><option value='begins'>begins</option><option value='notbegins'>does not begin with</option><option value='cmp_pos'>cmp_pos</option><option value='contains'>Contains</option><option value='notcontain'>does not contain</option><option value='date'>Expired</option><option value='date'>Expired</option><option value='=(Fixed)'>=(Fixed)</option><option value='>(Fixed)'>>(Fixed)</option><option value='<(Fixed)'><(Fixed)</option><option value='<>(Fixed)'><>(Fixed)</option><option value='begins(Fixed)'>begins(Fixed)</option><option value='notbegins(Fixed)'>does not begin with(Fixed)</option><option value='cmp_pos(Fixed)'>cmp_pos(Fixed)</option><option value='contains(Fixed)'>Contains(Fixed)</option><option value='notcontain(Fixed)'>does not contain(Fixed)</option></select></td></tr><tr id='response"+fields+"'><td align='right' width='250' class='libalign'><label>Library Authentication Response</label></td><td aligh='left'><input type='text' name='data[Variable]["+fields+"][authentication_response]' class='form_fields' size='50' id='responseField"+fields+"'></td></tr><tr id='authentication_variable_index"+fields+"'  style='font-size: 12px;'><td width='250' align='right'><label>Library Authentication Variable Index</label></td><td align='left' class='libselect' ><input type='radio' value='1' class='form_fields' name='data[Variable]["+fields+"][variable_index]' id='varialbe_index_flag0"+fields+"'> Index value <input type='text' value='' size='15' class='form_fields' name='data[Variable]["+fields+"][authentication_variable_index]' id='authentication_variable_index"+fields+"'> <input type='radio' value='2' class='form_fields' name='data[Variable]["+fields+"][variable_index]' id='varialbe_index_flag1"+fields+"' checked> All Index</td></tr><tr id='authentication_response_pos"+fields+"' style='display:none' ><td width='250' align='right'><label>Library Response Position</label></td><td align='left' class='libselect' ><input type='text' value='' size='20' class='form_fields' name='data[Variable]["+fields+"][authentication_response_pos]'></td></tr><tr id='resArr"+fields+"' style='display:none'><td align='right' width='250'><label>Result Array</label></td><td align='left' style='padding-left:20px' class='libselect'><select name='data[Variable]["+fields+"][result_arr]' id='resArr"+fields+"'><option value='fixed'>Fixed</option><option value='variable'>Variable</option></select></td></tr><tr id='error"+fields+"'><td align='right' width='250' class='libalign'><label>Library Error Message</label></td><td aligh='left'><input type='text' name='data[Variable]["+fields+"][error_msg]' class='form_fields' size='50'><input type='button' value='Remove' class='form_fields' onClick='removeVariable("+fields+");'></td></tr></table>";
		}
		$("#innv_var").append(textData);
		fields++;
		counter++;
}
function removeVariable(val){
	$("#table"+val).html('');
}
function addUrl(fieldVal) {
	if(incr < 1){
		fields = fieldVal;
	}
	var data = "<table id='tab"+fields+"' cellspacing='6' cellpadding='0' border='0'><tr><td align='right' width='250'><label>Library Referral URL</label></td><td aligh='left'><input type='text' name='data[Libraryurl]["+fields+"][domain_name]' class='form_fields' size='50'><input type='button' value='Remove' class='form_fields' onClick='removeUrl("+fields+");'></td></tr></table>";
	$("#allurl").append(data);
	fields++;
	incr++;
}
function removeUrl(val){
	$("#tab"+val).html('');
}
function getResponse(v){
	var val = $("#oprDrop"+v).val();
	if(val == 'date'){
		$("#responseField"+v).val('Current Date');
	} else {
		$("#responseField"+v).val('');
	}

}
function getArray(v){
	var val = $("#oprDrop"+v).val();
	if(val == 'contains'){
		$("#resArr"+v).show();
	} else {
		$("#resArr"+v).hide();
	}
	if(val == 'cmp_pos' || val == 'cmp_pos(Fixed)'){
		$("#authentication_response_pos"+v).show();
	} else {
		$("#authentication_response_pos"+v).hide();
	}		
}
function get_purFields(val){
	if(document.getElementById("LibraryShowContract").checked==true){
		if(val == 1){
			$("#pur_order").show();
			$("#pur_amount").show();
			$("#pur_track").show();
			$("#LibraryPurchasePurchasedTracks").val('Unlimited');
		} else {
			$("#pur_order").show();
			$("#pur_amount").show();
			$("#pur_track").show();
			$("#LibraryPurchasePurchasedTracks").val('');
		}
	} else{
		if(val ==1){
			$("#upgrd").hide();
		} else {
			if($("#LibraryLibraryContractStartDate").val() != ''){
				$("#upgrd").show();
			}
		}
	}	
}

function get_strFields(val){
	if(document.getElementById("ShowContract").checked==true){
		if(val == 1){
			$("#sur_order").show();
			$("#sur_amount").show();
			$("#sur_track").show();
			$("#LibraryPurchasesStreamingPurchasedHours").val(Unlimited);
		} else {
			$("#sur_order").show();
			$("#sur_amount").show();
			$("#sur_track").show();
			$("#LibraryPurchasesStreamingPurchasedHours").val($( "select#LibraryLibraryStreamingHours" ).val());
		}
	} else{
		if(val ==1){
			$("#strupgrd").hide();
		} else {
                        $( "select#LibraryLibraryStreamingHours" ).val();
			if($("#ContractLibraryStreamingPurchaseLibraryContractStartDate").val() != ''){
				$("#strupgrd").show();
			}
		}
	}	
}
    

function showContract(){
		if(document.getElementById("LibraryShowContract").checked==true){
			$("#contract_start").show();
			$("#contract_end").show();
			if(document.getElementById("redio2").checked==true){
				$("#pur_order").show();
				$("#pur_amount").show();
				$("#pur_track").show();
				$("#LibraryPurchasePurchasedTracks").val('Unlimited');
			} else {
				$("#pur_order").show();
				$("#pur_amount").show();
				$("#pur_track").show();
				$("#LibraryPurchasePurchasedTracks").val('');
			}
			$("#LibraryLibraryContractStartDate").val('');
			$("#LibraryLibraryContractEndDate").val('');
			$("#upgrd").hide();			
		} else {
			var start = $("#contractStart").val();
			var end = $("#contractEnd").val();
			$("#LibraryLibraryContractStartDate").val(start);
			$("#LibraryLibraryContractEndDate").val(end);
			$("#contract_start").show();
			$("#contract_end").show();
			$("#pur_order").hide();
			$("#pur_amount").hide();
			$("#pur_track").hide();
			$("#upgrd").hide();
			if($("#LibraryLibraryContractStartDate").val() != '' && document.getElementById("redio1").checked==true){
				$("#upgrd").show();
			}			
		}
}

function showStreamContract(){
		if(document.getElementById("ShowContract").checked==true){
			$("#stream_contract_start").show();
			$("#stream_contract_end").show(); 
                        
			if($("#redio2").is(":checked")){ 
                            $("#str_order").show();
                            $("#str_amount").show();
                            $("#str_track").show();
                            $("#LibraryPurchasesStreamingPurchasedHours").val('24');
			} else {
                            $("#str_order").show();
                            $("#str_amount").show();
                            $("#str_track").show();
                            $("#LibraryPurchasesStreamingPurchasedHours").val($( "select#LibraryLibraryStreamingHours" ).val());
			}
			$("#ContractLibraryStreamingPurchaseLibraryContractStartDate").val('');
			$("#ContractLibraryStreamingPurchaseLibraryContractEndDate").val('');
			$("#strupgrd").hide();			
		} else {
			var start = $("#stream_contract_start").val();
			var end = $("#stream_contract_end").val();
			$("#ContractLibraryStreamingPurchaseLibraryContractStartDate").val(start);
			$("#ContractLibraryStreamingPurchaseLibraryContractEndDate").val(end);
			$("#stream_contract_start").show();
			$("#stream_contract_end").show();
			$("#str_order").hide();
			$("#str_amount").hide();
			$("#str_track").hide();
			$("#strupgrd").hide();
			if($("#ContractLibraryStreamingPurchaseLibraryContractStartDate").val() != '' && document.getElementById("redio1").checked==true){
				$("#strupgrd").show();
			}			
		}
}

function convertString(){
	var str = $("#LibraryConsortiumName").val();
	var data = "str="+str;
	$.ajax({
		type: "post",  // Request method: post, get
		url: webroot+"homes/convertString", // URL to request
		data: data,  // post data
		success: function(response) {
			$("#LibraryConsortiumKey").val(response);
		},
		error:function (XMLHttpRequest, textStatus, errorThrown) {}
	});	
}

function showRow(){
	if($("#UserTypeId").val() == 6){
		$("#showConsortium").show();
	}
	else{
		$("#showConsortium").hide();
	}
}
