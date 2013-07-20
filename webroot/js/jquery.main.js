// init page
jQuery(function() {
	clearFormFields({
		clearInputs: true,
		clearTextareas: true,
		passwordFieldText: true,
		addClassFocus: "focus",
		filterClass: "default"
	});
	initAutoScalingNav({
		menuId: "nav",
		sideClasses: true
	});
	
	jQuery('#tab-1').ajaxStop(function() {
		//load_scroller('tab-1');
	});
	
	jQuery('#tab-2').ajaxStop(function() {
		//load_scroller('tab-2');
	});
	
	jQuery('#genre-tab-2-content').ajaxStop(function() {
		//load_scroller('genre-tab-2-content');
	});
	
	jQuery('#genre-tab-3-content').ajaxStop(function() {
		//load_scroller('genre-tab-3-content');
	});

	jQuery('#genre-tab-4-content').ajaxStop(function() {
		//load_scroller('genre-tab-4-content');
	});
	

	load_scroller('genre-tab-1-content');



	initCustomForms();
	initGallery();
	initTabs();
	jQuery('#tab-1-li').click(function(){
	
		load_my_lib();
	});
	
	jQuery('#tab-2-li').click(function(){
	
		national_top_100();
	});
	
	jQuery('#genre-tab-2').click(function(){
	
		get_content_for_genre_tab(2 , 'Rock');
	});
	
	jQuery('#genre-tab-3').click(function(){
	
		get_content_for_genre_tab(3 , 'Country');
	});
	
	jQuery('#genre-tab-4').click(function(){
	
		get_content_for_genre_tab(4 , 'Classical');
	});
});


function get_content_for_genre_tab(tabno,genre)
{
	var load_info = jQuery('#genre-load-info-'+tabno).val();
	if(load_info == 0)
	{
		jQuery('#genre-tab-'+tabno+'-content').empty().html('<img src="/img/ajax-loader-big.gif" img="" style="margin-left: 150px; margin-top: 80px;">');
		jQuery('#genre-tab-'+tabno+'-content').load('/homes/get_genre_tab_content/'+tabno+'/'+genre);
		jQuery('#genre-load-info-' + tabno).val(1);
		//setInterval('VSA_initScrollbars()' , 200);
	}
	
}

function load_my_lib()
{
	//jQuery("#tab-1").empty().html(jQuery("#ajx_loader").html());
	var load_info = jQuery('#my-lib-load-info').val();
	if(load_info == 0)
	{
		jQuery("#tab-1").empty().html('<img src="/img/ajax-loader-big.gif" img="" style="margin-left: 246px; margin-top: 82px;">');
		jQuery('#tab-1').load('/homes/my_lib_top_10');
		jQuery('#my-lib-load-info').val(1);
		//setInterval('VSA_initScrollbars()' , 200);
	}
	//jQuery('.genre_list_item').css('font-weight' , 'normal');
	//jQuery('#genre_list_item_'+id_serial).css('font-weight' , 'bold');
}

function national_top_100()
{
	var load_info = jQuery('#nationsl-top-load-info').val();
	if(load_info == 0)
	{
		jQuery("#tab-2").empty().html('<img src="/img/ajax-loader-big.gif" img="" style="margin-left: 246px; margin-top: 82px;">');
		jQuery('#tab-2').load('/homes/national_top_download');
		jQuery('#nationsl-top-load-info').val(1);
		//setInterval('VSA_initScrollbars()' , 200);
	}
	//jQuery('.genre_list_item').css('font-weight' , 'normal');
	//jQuery('#genre_list_item_'+id_serial).css('font-weight' , 'bold');
}

// initGallery
function initGallery() {
	jQuery('.carousel').each(function() {
		var holder = jQuery(this),
			list = jQuery('.carousel-box > ul', this),
			prev = jQuery('.prev', this),
			next = jQuery('.next', this);
		
		list.carouFredSel({
			next: next,
			prev: prev,
			circular: false,
			auto: 5000,
			scroll: 650
		});
		
		jQuery(window).focus(function() {
			list.trigger('play');
		}).blur(function() {
			list.trigger('pause');
		})
	});
	
	jQuery('.gallery').each(function() {
		var holder = jQuery(this);
		
		holder.fadeGallery({
			slideElements:'.gallery-box > ul > li',
			pagerLinks:'.switcher > li',
			pauseOnHover:true,
			autoRotation:true,
			switchTime:5000,
			duration:650
		})
		
		jQuery(window).focus(function() {
			holder.trigger('play');
		}).blur(function() {
			holder.trigger('pause');
		})
	});
}

// clearFormFields
function clearFormFields(o){
	if (o.clearInputs == null) o.clearInputs = true;
	if (o.clearTextareas == null) o.clearTextareas = true;
	if (o.passwordFieldText == null) o.passwordFieldText = false;
	if (o.addClassFocus == null) o.addClassFocus = false;
	if (!o.filter) o.filter = "default";
	if(o.clearInputs) {
		var inputs = document.getElementsByTagName("input");
		for (var i = 0; i < inputs.length; i++ ) {
			if((inputs[i].type == "text" || inputs[i].type == "password") && inputs[i].className.indexOf(o.filterClass)) {
				inputs[i].valueHtml = inputs[i].value;
				inputs[i].onfocus = function ()	{
					if(this.valueHtml == this.value) this.value = "";
					if(this.fake) {
						inputsSwap(this, this.previousSibling);
						this.previousSibling.focus();
					}
					if(o.addClassFocus && !this.fake) {
						this.className += " " + o.addClassFocus;
						this.parentNode.className += " parent-" + o.addClassFocus;
					}
				}
				inputs[i].onblur = function () {
					if(this.value == "") {
						this.value = this.valueHtml;
						if(o.passwordFieldText && this.type == "password") inputsSwap(this, this.nextSibling);
					}
					if(o.addClassFocus) {
						this.className = this.className.replace(o.addClassFocus, "");
						this.parentNode.className = this.parentNode.className.replace("parent-"+o.addClassFocus, "");
					}
				}
				if(o.passwordFieldText && inputs[i].type == "password") {
					var fakeInput = document.createElement("input");
					fakeInput.type = "text";
					fakeInput.value = inputs[i].value;
					fakeInput.className = inputs[i].className;
					fakeInput.fake = true;
					inputs[i].parentNode.insertBefore(fakeInput, inputs[i].nextSibling);
					inputsSwap(inputs[i], null);
				}
			}
		}
	}
	if(o.clearTextareas) {
		var textareas = document.getElementsByTagName("textarea");
		for(var i=0; i<textareas.length; i++) {
			if(textareas[i].className.indexOf(o.filterClass)) {
				textareas[i].valueHtml = textareas[i].value;
				textareas[i].onfocus = function() {
					if(this.value == this.valueHtml) this.value = "";
					if(o.addClassFocus) {
						this.className += " " + o.addClassFocus;
						this.parentNode.className += " parent-" + o.addClassFocus;
					}
				}
				textareas[i].onblur = function() {
					if(this.value == "") this.value = this.valueHtml;
					if(o.addClassFocus) {
						this.className = this.className.replace(o.addClassFocus, "");
						this.parentNode.className = this.parentNode.className.replace("parent-"+o.addClassFocus, "");
					}
				}
			}
		}
	}
	function inputsSwap(el, el2) {
		if(el) el.style.display = "none";
		if(el2) el2.style.display = "inline";
	}
}

// initAutoScalingNav
function initAutoScalingNav(o) {
	if (!o.menuId) o.menuId = "nav";
	if (!o.tag) o.tag = "a";
	if (!o.spacing) o.spacing = 0;
	if (!o.constant) o.constant = 0;
	if (!o.minPaddings) o.minPaddings = 0;
	if (!o.liHovering) o.liHovering = false;
	if (!o.sideClasses) o.sideClasses = false;
	if (!o.equalLinks) o.equalLinks = false;
	if (!o.flexible) o.flexible = false;
	var nav = document.getElementById(o.menuId);
	if(nav) {
		nav.className += " scaling-active";
		var lis = nav.getElementsByTagName("li");
		var asFl = [];
		var lisFl = [];
		var width = 0;
		for (var i=0, j=0; i<lis.length; i++) {
			if(lis[i].parentNode == nav) {
				var t = lis[i].getElementsByTagName(o.tag).item(0);
				asFl.push(t);
				asFl[j++].width = t.offsetWidth;
				lisFl.push(lis[i]);
				if(width < t.offsetWidth) width = t.offsetWidth;
			}
			if(o.liHovering) {
				lis[i].onmouseover = function() {
					this.className += " hover";
				}
				lis[i].onmouseout = function() {
					this.className = this.className.replace("hover", "");
				}
			}
		}
		var menuWidth = nav.clientWidth - asFl.length*o.spacing - o.constant;
		if(o.equalLinks && width * asFl.length < menuWidth) {
			for (var i=0; i<asFl.length; i++) {
				asFl[i].width = width;
			}
		}
		width = getItemsWidth(asFl);
		if(width < menuWidth) {
			var version = navigator.userAgent.toLowerCase();
			for (var i=0; getItemsWidth(asFl) < menuWidth; i++) {
				asFl[i].width++;
				if(!o.flexible) {
					asFl[i].style.width = asFl[i].width + "px";
				}
				if(i >= asFl.length-1) i=-1;
			}
			if(o.flexible) {
				for (var i=0; i<asFl.length; i++) {
					width = (asFl[i].width - o.spacing - o.constant/asFl.length)/menuWidth*100;
					if(i != asFl.length-1) {
						lisFl[i].style.width = width + "%";
					}
					else {
						if(navigator.appName.indexOf("Microsoft Internet Explorer") == -1 || version.indexOf("msie 8") != -1 || version.indexOf("msie 9") != -1)
							lisFl[i].style.width = width + "%";
					}
				}
			}
		}
		else if(o.minPaddings > 0) {
			for (var i=0; i<asFl.length; i++) {
				asFl[i].style.paddingLeft = o.minPaddings + "px";
				asFl[i].style.paddingRight = o.minPaddings + "px";
			}
		}
		if(o.sideClasses) {
			lisFl[0].className += " first-child";
			lisFl[0].getElementsByTagName(o.tag).item(0).className += " first-child-a";
			lisFl[lisFl.length-1].className += " last-child";
			lisFl[lisFl.length-1].getElementsByTagName(o.tag).item(0).className += " last-child-a";
		}
		nav.className += " scaling-ready";
	}
	function getItemsWidth(a) {
		var w = 0;
		for(var q=0; q<a.length; q++) {
			w += a[q].width;
		}
		return w;
	}
}

// custom form START
// custom forms script
var maxVisibleOptions = 20;
var all_selects = false;
var active_select = null;
var selectText = "please select";

function initCustomForms() {
	getElements();
	separateElements();
	replaceRadios();
	replaceCheckboxes();
	replaceSelects();

	// hide drop when scrolling or resizing window
	if (window.addEventListener) {
		window.addEventListener("scroll", hideActiveSelectDrop, false);
		window.addEventListener("resize", hideActiveSelectDrop, false);
	}
	else if (window.attachEvent) {
		window.attachEvent("onscroll", hideActiveSelectDrop);
		window.attachEvent("onresize", hideActiveSelectDrop);
	}
}

function refreshCustomForms() {
	// remove prevously created elements
	if(window.inputs) {
		for(var i = 0; i < checkboxes.length; i++) {
			if(checkboxes[i].checked) {checkboxes[i]._ca.className = "checkboxAreaChecked";}
			else {checkboxes[i]._ca.className = "checkboxArea";}
		}
		for(var i = 0; i < radios.length; i++) {
			if(radios[i].checked) {radios[i]._ra.className = "radioAreaChecked";}
			else {radios[i]._ra.className = "radioArea";}
		}
		for(var i = 0; i < selects.length; i++) {
			var newText = document.createElement('div');
			if (selects[i].options[selects[i].selectedIndex].title.indexOf('image') != -1) {
				newText.innerHTML = '<img src="'+selects[i].options[selects[i].selectedIndex].title+'" alt="" />';
				newText.innerHTML += '<span>'+selects[i].options[selects[i].selectedIndex].text+'</span>';
			} else {
				newText.innerHTML = selects[i].options[selects[i].selectedIndex].text;
			}
			document.getElementById("mySelectText"+i).innerHTML = newText.innerHTML;
		}
	}
}

// getting all the required elements
function getElements() {
	// remove prevously created elements
	if(window.inputs) {
		for(var i = 0; i < inputs.length; i++) {
			inputs[i].className = inputs[i].className.replace('outtaHere','');
			if(inputs[i]._ca) inputs[i]._ca.parentNode.removeChild(inputs[i]._ca);
			else if(inputs[i]._ra) inputs[i]._ra.parentNode.removeChild(inputs[i]._ra);
		}
		for(i = 0; i < selects.length; i++) {
			selects[i].replaced = null;
			selects[i].className = selects[i].className.replace('outtaHere','');
			selects[i]._optionsDiv._parent.parentNode.removeChild(selects[i]._optionsDiv._parent);
			selects[i]._optionsDiv.parentNode.removeChild(selects[i]._optionsDiv);
		}
	}

	// reset state
	inputs = new Array();
	selects = new Array();
	labels = new Array();
	radios = new Array();
	radioLabels = new Array();
	checkboxes = new Array();
	checkboxLabels = new Array();
	for (var nf = 0; nf < document.getElementsByTagName("form").length; nf++) {
		if(document.forms[nf].className.indexOf("default") < 0) {
			for(var nfi = 0; nfi < document.forms[nf].getElementsByTagName("input").length; nfi++) {inputs.push(document.forms[nf].getElementsByTagName("input")[nfi]);}
			for(var nfl = 0; nfl < document.forms[nf].getElementsByTagName("label").length; nfl++) {labels.push(document.forms[nf].getElementsByTagName("label")[nfl]);}
			for(var nfs = 0; nfs < document.forms[nf].getElementsByTagName("select").length; nfs++) {selects.push(document.forms[nf].getElementsByTagName("select")[nfs]);}
		}
	}
}

// separating all the elements in their respective arrays
function separateElements() {
	var r = 0; var c = 0; var t = 0; var rl = 0; var cl = 0; var tl = 0; var b = 0;
	for (var q = 0; q < inputs.length; q++) {
		if(inputs[q].type == "radio") {
			radios[r] = inputs[q]; ++r;
			for(var w = 0; w < labels.length; w++) {
				if((inputs[q].id) && labels[w].htmlFor == inputs[q].id)
				{
					radioLabels[rl] = labels[w];
					++rl;
				}
			}
		}
		if(inputs[q].type == "checkbox") {
			checkboxes[c] = inputs[q]; ++c;
			for(var w = 0; w < labels.length; w++) {
				if((inputs[q].id) && (labels[w].htmlFor == inputs[q].id))
				{
					checkboxLabels[cl] = labels[w];
					++cl;
				}
			}
		}
	}
}

//replacing radio buttons
function replaceRadios() {
	for (var q = 0; q < radios.length; q++) {
		radios[q].className += " outtaHere";
		var radioArea = document.createElement("div");
		if(radios[q].checked) {
			radioArea.className = "radioAreaChecked";
		}
		else
		{
			radioArea.className = "radioArea";
		}
		radioArea.id = "myRadio" + q;
		radios[q].parentNode.insertBefore(radioArea, radios[q]);
		radios[q]._ra = radioArea;

		radioArea.onclick = new Function('rechangeRadios('+q+')');
		if (radioLabels[q]) {
			if(radios[q].checked) {
				radioLabels[q].className += "radioAreaCheckedLabel";
			}
			radioLabels[q].onclick = new Function('rechangeRadios('+q+')');
		}
	}
	return true;
}

//checking radios
function checkRadios(who) {
	var what = radios[who]._ra;
	for(var q = 0; q < radios.length; q++) {
		if((radios[q]._ra.className == "radioAreaChecked") && (radios[q]._ra.nextSibling.name == radios[who].name))
		{
			radios[q]._ra.className = "radioArea";
		}
	}
	what.className = "radioAreaChecked";
}

//changing radios
function changeRadios(who) {
	if(radios[who].checked) {
		for(var q = 0; q < radios.length; q++) {
			if(radios[q].name == radios[who].name) {
				radios[q].checked = false;
			} 
			radios[who].checked = true; 
			checkRadios(who);
		}
	}
}

//rechanging radios
function rechangeRadios(who) {
	if(!radios[who].checked) {
		for(var q = 0; q < radios.length; q++) {
			if(radios[q].name == radios[who].name) {
				radios[q].checked = false; 
				if(radioLabels[q]) radioLabels[q].className = radioLabels[q].className.replace("radioAreaCheckedLabel","");
			}
		}
		radios[who].checked = true; 
		if(radioLabels[who] && radioLabels[who].className.indexOf("radioAreaCheckedLabel") < 0) {
			radioLabels[who].className += " radioAreaCheckedLabel";
		}
		checkRadios(who);
		
		if(window.$ && window.$.fn) {
			$(radios[who]).trigger('change');
		}
	}
}

//replacing checkboxes
function replaceCheckboxes() {
	for (var q = 0; q < checkboxes.length; q++) {
		checkboxes[q].className += " outtaHere";
		var checkboxArea = document.createElement("div");
		if(checkboxes[q].checked) {
			checkboxArea.className = "checkboxAreaChecked";
			if(checkboxLabels[q]) {
				checkboxLabels[q].className += " checkboxAreaCheckedLabel"
			}
		}
		else {
			checkboxArea.className = "checkboxArea";
		}
		checkboxArea.id = "myCheckbox" + q;
		checkboxes[q].parentNode.insertBefore(checkboxArea, checkboxes[q]);
		checkboxes[q]._ca = checkboxArea;
		checkboxArea.onclick = new Function('rechangeCheckboxes('+q+')');
		if (checkboxLabels[q]) {
			checkboxLabels[q].onclick = new Function('changeCheckboxes('+q+')');
		}
		checkboxes[q].onkeydown = checkEvent;
	}
	return true;
}

//checking checkboxes
function checkCheckboxes(who, action) {
	var what = checkboxes[who]._ca;
	if(action == true) {
		what.className = "checkboxAreaChecked";
		what.checked = true;
	}
	if(action == false) {
		what.className = "checkboxArea";
		what.checked = false;
	}
	if(checkboxLabels[who]) {
		if(checkboxes[who].checked) {
			if(checkboxLabels[who].className.indexOf("checkboxAreaCheckedLabel") < 0) {
				checkboxLabels[who].className += " checkboxAreaCheckedLabel";
			}
		} else {
			checkboxLabels[who].className = checkboxLabels[who].className.replace("checkboxAreaCheckedLabel", "");
		}
	}
	
}

//changing checkboxes
function changeCheckboxes(who) {
	setTimeout(function(){
		if(checkboxes[who].checked) {
			checkCheckboxes(who, true);
		} else {
			checkCheckboxes(who, false);
		}
	},10);
}

// rechanging checkboxes
function rechangeCheckboxes(who) {
	var tester = false;
	if(checkboxes[who].checked == true) {
		tester = false;
	}
	else {
		tester = true;
	}
	checkboxes[who].checked = tester;
	checkCheckboxes(who, tester);
	if(window.$ && window.$.fn) {
		$(checkboxes[who]).trigger('change');
	}
}

// check event
function checkEvent(e) {
	if (!e) var e = window.event;
	if(e.keyCode == 32) {for (var q = 0; q < checkboxes.length; q++) {if(this == checkboxes[q]) {changeCheckboxes(q);}}} //check if space is pressed
}

// replace selects
function replaceSelects() {
	for(var q = 0; q < selects.length; q++) {
		if (!selects[q].replaced && selects[q].offsetWidth) {
			selects[q]._number = q;
			//create and build div structure
			var selectArea = document.createElement("div");
			var left = document.createElement("span");
			left.className = "left";
			selectArea.appendChild(left);

			var disabled = document.createElement("span");
			disabled.className = "disabled";
			selectArea.appendChild(disabled);

			selects[q]._disabled = disabled;
			var center = document.createElement("span");
			var button = document.createElement("a");
			var text = document.createTextNode(selectText);
			center.id = "mySelectText"+q;

			var stWidth = selects[q].offsetWidth;
			selectArea.style.width = stWidth + "px";
			if (selects[q].parentNode.className.indexOf("type2") != -1){
				button.href = "javascript:showOptions("+q+",true)";
			} else {
				button.href = "javascript:showOptions("+q+",false)";
			}
			button.className = "selectButton";
			selectArea.className = "selectArea";
			selectArea.className += " " + selects[q].className;
			selectArea.id = "sarea"+q;
			center.className = "center";
			center.appendChild(text);
			selectArea.appendChild(center);
			selectArea.appendChild(button);

			//insert select div
			selects[q].parentNode.insertBefore(selectArea, selects[q]);
			//build & place options div

			var optionsDiv = document.createElement("div");
			var optionsList = document.createElement("ul");
			var optionsListHolder = document.createElement("div");
			
			optionsListHolder.className = "select-center";
			optionsListHolder.innerHTML =  "<div class='select-center-right'></div>";
			optionsDiv.innerHTML += "<div class='select-top'><div class='select-top-left'></div><div class='select-top-right'></div></div>";
			
			optionsListHolder.appendChild(optionsList);
			optionsDiv.appendChild(optionsListHolder);
			
			selects[q]._optionsDiv = optionsDiv;
			selects[q]._options = optionsList;

			optionsDiv.style.width = stWidth + "px";
			optionsDiv._parent = selectArea;

			optionsDiv.className = "optionsDivInvisible";
			optionsDiv.id = "optionsDiv"+q;

			if(selects[q].className.length) {
				optionsDiv.className += ' drop-'+selects[q].className;
			}

			populateSelectOptions(selects[q]);
			optionsDiv.innerHTML += "<div class='select-bottom'><div class='select-bottom-left'></div><div class='select-bottom-right'></div></div>";
			document.body.appendChild(optionsDiv);
			selects[q].replaced = true;
			
			// too many options
			if(selects[q].options.length > maxVisibleOptions) {
				optionsDiv.className += ' optionsDivScroll';
			}
			
			//hide the select field
			if(selects[q].className.indexOf('default') != -1) {
				selectArea.style.display = 'none';
			} else {
				selects[q].className += " outtaHere";
			}
		}
	}
	all_selects = true;
}

//collecting select options
function populateSelectOptions(me) {
	me._options.innerHTML = "";
	for(var w = 0; w < me.options.length; w++) {
		var optionHolder = document.createElement('li');
		var optionLink = document.createElement('a');
		var optionTxt;
		if (me.options[w].title.indexOf('image') != -1) {
			optionTxt = document.createElement('img');
			optionSpan = document.createElement('span');
			optionTxt.src = me.options[w].title;
			optionSpan = document.createTextNode(me.options[w].text);
		} else {
			optionTxt = document.createTextNode(me.options[w].text);
		}
		
		// hidden default option
		if(me.options[w].className.indexOf('default') != -1) {
			optionHolder.style.display = 'none'
		}
		
		optionLink.href = "javascript:showOptions("+me._number+"); selectMe('"+me.id+"',"+w+","+me._number+");";
		if (me.options[w].title.indexOf('image') != -1) {
			optionLink.appendChild(optionTxt);
			optionLink.appendChild(optionSpan);
		} else {
			optionLink.appendChild(optionTxt);
		}
		optionHolder.appendChild(optionLink);
		me._options.appendChild(optionHolder);
		//check for pre-selected items
		if(me.options[w].selected) {
			selectMe(me.id,w,me._number,true);
		}
	}
	if (me.disabled) {
		me._disabled.style.display = "block";
	} else {
		me._disabled.style.display = "none";
	}
}

//selecting me
function selectMe(selectFieldId,linkNo,selectNo,quiet) {
	selectField = selects[selectNo];
	for(var k = 0; k < selectField.options.length; k++) {
		if(k==linkNo) {
			selectField.options[k].selected = true;
		}
		else {
			selectField.options[k].selected = false;
		}
	}
	
	//show selected option
	textVar = document.getElementById("mySelectText"+selectNo);
	var newText;
	var optionSpan;
	if (selectField.options[linkNo].title.indexOf('image') != -1) {
		newText = document.createElement('img');
		newText.src = selectField.options[linkNo].title;
		optionSpan = document.createElement('span');
		optionSpan = document.createTextNode(selectField.options[linkNo].text);
	} else {
		newText = document.createTextNode(selectField.options[linkNo].text);
	}
	if (selectField.options[linkNo].title.indexOf('image') != -1) {
		if (textVar.childNodes.length > 1) textVar.removeChild(textVar.childNodes[0]);
		textVar.replaceChild(newText, textVar.childNodes[0]);
		textVar.appendChild(optionSpan);
	} else {
		if (textVar.childNodes.length > 1) textVar.removeChild(textVar.childNodes[0]);
		textVar.replaceChild(newText, textVar.childNodes[0]);
	}
	if (!quiet && all_selects) {
		if(typeof selectField.onchange === 'function') {
			selectField.onchange();
		}
		if(window.$ && window.$.fn) {
			$(selectField).trigger('change');
		}
	}
}

//showing options
function showOptions(g) {
	_elem = document.getElementById("optionsDiv"+g);
	var divArea = document.getElementById("sarea"+g);
	if (active_select && active_select != _elem) {
		active_select.className = active_select.className.replace('optionsDivVisible',' optionsDivInvisible');
		active_select.style.height = "auto";
	}
	if(_elem.className.indexOf("optionsDivInvisible") != -1) {
		_elem.style.left = "-9999px";
		_elem.style.top = findPosY(divArea) + divArea.offsetHeight + 'px';
		_elem.className = _elem.className.replace('optionsDivInvisible','');
		_elem.className += " optionsDivVisible";
		/*if (_elem.offsetHeight > 200)
		{
			_elem.style.height = "200px";
		}*/
		_elem.style.left = findPosX(divArea) + 'px';
		
		active_select = _elem;
		if(_elem._parent.className.indexOf('selectAreaActive') < 0) {
			_elem._parent.className += ' selectAreaActive';
		}
		
		if(document.documentElement) {
			document.documentElement.onclick = hideSelectOptions;
		} else {
			window.onclick = hideSelectOptions;
		}
	}
	else if(_elem.className.indexOf("optionsDivVisible") != -1) {
		hideActiveSelectDrop();
	}
	
	// for mouseout
	/*_elem.timer = false;
	_elem.onmouseover = function() {
		if (this.timer) clearTimeout(this.timer);
	}
	_elem.onmouseout = function() {
		var _this = this;
		this.timer = setTimeout(function(){
			_this.style.height = "auto";
			_this.className = _this.className.replace('optionsDivVisible','');
			if (_elem.className.indexOf('optionsDivInvisible') == -1)
				_this.className += " optionsDivInvisible";
		},200);
	}*/
}

function hideActiveSelectDrop() {
	if(active_select) {
		active_select.style.height = "auto";
		active_select.className = active_select.className.replace('optionsDivVisible', '');
		active_select.className = active_select.className.replace('optionsDivInvisible', '');
		active_select._parent.className = active_select._parent.className.replace('selectAreaActive','')
		active_select.className += " optionsDivInvisible";
		active_select = false;
	}
}

function hideSelectOptions(e) {
	if(active_select) {
		if(!e) e = window.event;
		var _target = (e.target || e.srcElement);
		if(!isElementBefore(_target,'selectArea') && !isElementBefore(_target,'optionsDiv')) {
			hideActiveSelectDrop();
			if(document.documentElement) {
				document.documentElement.onclick = function(){};
			}
			else {
				window.onclick = null;
			}
		}
	}
}

function isElementBefore(_el,_class) {
	var _parent = _el;
	do {
		_parent = _parent.parentNode;
	}
	while(_parent && _parent.className != null && _parent.className.indexOf(_class) == -1)
	return _parent.className && _parent.className.indexOf(_class) != -1;
}

function findPosY(obj) {
	if (obj.getBoundingClientRect) {
		var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
		var clientTop = document.documentElement.clientTop || document.body.clientTop || 0;
		return Math.round(obj.getBoundingClientRect().top + scrollTop - clientTop);
	} else {
		var posTop = 0;
		while (obj.offsetParent) {posTop += obj.offsetTop; obj = obj.offsetParent;}
		return posTop;
	}
}

function findPosX(obj) {
	if (obj.getBoundingClientRect) {
		var scrollLeft = window.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft;
		var clientLeft = document.documentElement.clientLeft || document.body.clientLeft || 0;
		return Math.round(obj.getBoundingClientRect().left + scrollLeft - clientLeft);
	} else {
		var posLeft = 0;
		while (obj.offsetParent) {posLeft += obj.offsetLeft; obj = obj.offsetParent;}
		return posLeft;
	}
}
// custom form END


// init tabs when page ready
function initTabs() {
	if(typeof ContentTabs !== 'undefined') {
		ContentTabs.init();
	}
}

// content tabs module
ContentTabs = {
	options: {
		classOnParent: false,
		hiddenClass: 'tab-hidden',
		visibleClass: 'tab-active',
		activeClass: 'active',
		tabsets: 'ul.tabset',
		tablinks: 'a.tab',
		event: 'click'
	},
	init: function(){
		this.createStyleSheet();
		this.getTabsets();
		return this;
	},
	createStyleSheet: function() {
		this.tabStyleSheet = document.createElement('style');
		this.tabStyleSheet.setAttribute('type', 'text/css');
		this.tabStyleRule = '.'+this.options.hiddenClass;
		this.tabStyleRule += '{position:absolute !important;left:-9999px !important;top:-9999px !important;display:block !important}';
		document.getElementsByTagName('head')[0].appendChild(this.tabStyleSheet);
		if (this.tabStyleSheet.styleSheet) {
			this.tabStyleSheet.styleSheet.cssText = this.tabStyleRule;
		} else {
			this.tabStyleSheet.appendChild(document.createTextNode(this.tabStyleRule));
		}
	},
	getTabsets: function() {
		this.tabsets = this.queryElements(this.options.tabsets);
		for(var i = 0; i < this.tabsets.length; i++) {
			this.initTabset(this.tabsets[i]);
		}
	},
	initTabset: function(tabset) {
		var tabLinks = this.queryElements(this.options.tablinks, tabset), instance = this;
		for(var i = 0; i < tabLinks.length; i++) {
			tabLinks[i]['on'+this.options.event] = function(){
				instance.switchTab(this, tabLinks);
				return false;
			}
			if(this.hasClass(this.options.classOnParent ? tabLinks[i].parentNode : tabLinks[i], this.options.activeClass)) {
				this.switchTab(tabLinks[i], tabLinks);
			}
		}
	},
	switchTab: function(link, set) {
		for(var i = 0; i < set.length; i++) {
			var curLink = set[i];
			var curTab = document.getElementById(curLink.href.substr(curLink.href.indexOf('#')+1));
			if(curLink === link) {
				this.addClass(this.options.classOnParent ? curLink.parentNode : curLink, this.options.activeClass);
				this.addClass(curTab,this.options.visibleClass);
				this.removeClass(curTab,this.options.hiddenClass);
			} else {
				this.removeClass(this.options.classOnParent ? curLink.parentNode : curLink, this.options.activeClass);
				this.removeClass(curTab,this.options.visibleClass);
				this.addClass(curTab,this.options.hiddenClass);
			}
		}
	},
	queryElements: function(selector, holder) {
		var box = holder || document;
		if(box.querySelectorAll) {
			return box.querySelectorAll(selector);
		} else {
			var res = [], selectorData = selector.split('.');
			var tagName = selectorData[0];
			var set = box.getElementsByTagName(tagName);
			if(selectorData.length > 1) {
				for(var i = 0; i < set.length; i++) {
					if(this.hasClass(set[i], selectorData[1])) res.push(set[i]);
				}
				return res;
			} else {
				return set;
			}
		}
	},
	hasClass: function (obj,cname) {
		return (obj.className ? obj.className.match(new RegExp('(\\s|^)'+cname+'(\\s|$)')) : false);
	},
	addClass: function (obj,cname) {
		if (!this.hasClass(obj,cname)) obj.className += " "+cname;
	},
	removeClass: function (obj,cname) {
		if (this.hasClass(obj,cname)) obj.className=obj.className.replace(new RegExp('(\\s|^)'+cname+'(\\s|$)'),' ');
	}
};

/*	
 *	jQuery carouFredSel 5.0.7
 *	Demo's and documentation:
 *	caroufredsel.frebsite.nl
 *	
 *	Copyright (c) 2011 Fred Heusschen
 *	www.frebsite.nl
 *
 *	Dual licensed under the MIT and GPL licenses.
 *	http://en.wikipedia.org/wiki/MIT_License
 *	http://en.wikipedia.org/wiki/GNU_General_Public_License
 */


eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('(C($){7($.1D.1z)E;$.1D.1z=C(r,u){7(17.T==0){11(G,\'4L 3W 5O 1q "\'+17.3s+\'".\');E 17}7(17.T>1){E 17.1O(C(){$(17).1z(r,u)})}8 w=17,$15=17[0];w.3X=C(o,b,c){o=3t($15,o);7(o.11){12.11=o.11;11(12,\'5P "11" 5Q 4M 4N 5R 31 5S 5T 3Y-1e.\')}8 e=[\'9\',\'1a\',\'Q\',\'V\',\'X\',\'13\'];1q(8 a=0,l=e.T;a<l;a++){o[e[a]]=3t($15,o[e[a]])}7(D o.1a==\'Y\'){7(o.1a<=50)o.1a={\'9\':o.1a};I o.1a={\'1b\':o.1a}}I{7(D o.1a==\'1m\')o.1a={\'1w\':o.1a}}7(D o.9==\'Y\')o.9={\'J\':o.9};I 7(o.9==\'1j\')o.9={\'J\':o.9,\'M\':o.9,\'1h\':o.9};7(b)2s=$.26(G,{},$.1D.1z.3Z,o);6=$.26(G,{},$.1D.1z.3Z,o);6.d={};6.1T=K;6.3u=K;7(6.9.2t==0&&D c==\'Y\'){6.9.2t=c}y.27=(6.27==\'41\'||6.27==\'1k\')?\'X\':\'V\';8 f=[[\'M\',\'3v\',\'2d\',\'1h\',\'4O\',\'2u\',\'1k\',\'2v\',\'1r\',0,1,2,3],[\'1h\',\'4O\',\'2u\',\'M\',\'3v\',\'2d\',\'2v\',\'1k\',\'3w\',3,2,1,0]];8 g=f[0].T,4P=(6.27==\'2w\'||6.27==\'1k\')?0:1;1q(8 d=0;d<g;d++){6.d[f[0][d]]=f[4P][d]}8 h=w.S(),42=43(h,6,\'2u\',K);7(6[6.d[\'1h\']]==\'Q\'){6[6.d[\'1h\']]=42;6.9[6.d[\'1h\']]=42}7(!6.9[6.d[\'M\']]){6.9[6.d[\'M\']]=(44(h,6,\'2d\'))?\'1j\':h[6.d[\'2d\']](G)}7(!6.9[6.d[\'1h\']]){6.9[6.d[\'1h\']]=(44(h,6,\'2u\'))?\'1j\':h[6.d[\'2u\']](G)}7(!6[6.d[\'1h\']]){6[6.d[\'1h\']]=6.9[6.d[\'1h\']]}1s(6.9.J){L\'+1\':L\'-1\':L\'3x\':L\'3x+\':L\'3y\':L\'3y+\':6.3u=6.9.J;6.9.J=K;16}7(!6.9.J){7(6.9[6.d[\'M\']]==\'1j\'){6.9.J=\'1j\'}I{7(D 6[6.d[\'M\']]==\'Y\'){6.9.J=1L.3z(6[6.d[\'M\']]/6.9[6.d[\'M\']])}I{8 i=45($1E.3A(),6,\'3v\');6.9.J=1L.3z(i/6.9[6.d[\'M\']]);6[6.d[\'M\']]=6.9.J*6.9[6.d[\'M\']];7(!6.3u)6.1x=K}7(6.9.J==\'5U\'||6.9.J<0){11(G,\'1U a 46 Y 32 J 9: 5V 31 "1".\');6.9.J=1}}}7(!6[6.d[\'M\']]){7(6.9.J!=\'1j\'&&6.9[6.d[\'M\']]!=\'1j\'){6[6.d[\'M\']]=6.9.J*6.9[6.d[\'M\']];6.1x=K}I{6[6.d[\'M\']]=\'1j\'}}7(6.9.J==\'1j\'){6.1T=G;6.3B=(6[6.d[\'M\']]==\'1j\')?45($1E.3A(),6,\'3v\'):6[6.d[\'M\']];7(6.1x===K){6[6.d[\'M\']]=\'1j\'}6.9.J=33(h,6,0);7(6.9.J>F.N){6.9.J=F.N}}7(D 6.18==\'1y\'){6.18=0}7(D 6.1x==\'1y\'){6.1x=(6[6.d[\'M\']]==\'1j\')?K:\'47\'}6.9.J=3C(6.9.J,6);6.9.1V=6.9.J;6.1f=K;6.18=4Q(6.18);7(6.1x==\'2v\')6.1x=\'1k\';7(6.1x==\'48\')6.1x=\'2w\';1s(6.1x){L\'47\':L\'1k\':L\'2w\':7(6[6.d[\'M\']]!=\'1j\'){8 p=3D(2F(h,6),6);6.1f=G;6.18[6.d[1]]=p[1];6.18[6.d[3]]=p[0]}16;2x:6.1x=K;6.1f=(6.18[0]==0&&6.18[1]==0&&6.18[2]==0&&6.18[3]==0)?K:G;16}7(D 6.1P==\'1l\'&&6.1P)6.1P=\'5W\'+w.5X(\'5Y\');7(D 6.9.2G!=\'Y\')6.9.2G=6.9.J;7(D 6.1a.9!=\'Y\')6.1a.9=(6.1T)?\'1j\':6.9.J;7(D 6.1a.1b!=\'Y\')6.1a.1b=5Z;6.Q=34($15,6.Q,\'Q\');6.V=34($15,6.V);6.X=34($15,6.X);6.13=34($15,6.13,\'13\');6.Q=$.26(G,{},6.1a,6.Q);6.V=$.26(G,{},6.1a,6.V);6.X=$.26(G,{},6.1a,6.X);6.13=$.26(G,{},6.1a,6.13);7(D 6.13.3E!=\'1l\')6.13.3E=K;7(D 6.13.4a!=\'C\')6.13.4a=$.1D.1z.4R;7(D 6.Q.1A!=\'1l\')6.Q.1A=G;7(D 6.Q.4b!=\'Y\')6.Q.4b=0;7(D 6.Q.2H!=\'Y\')6.Q.2H=(6.Q.1b<10)?60:6.Q.1b*5;6.Q.1M=(6.Q.1M)?6.Q.1M.61():\'\';7(6.1W){6.1W=4c(6.1W)}7(12.11){11(12,\'2I M: \'+6.M);11(12,\'2I 1h: \'+6.1h);7(6[6.d[\'M\']]==\'1j\')11(12,\'62 \'+6.d[\'M\']+\': \'+6.3B);11(12,\'4S 63: \'+6.9.M);11(12,\'4S 64: \'+6.9.1h);11(12,\'3F 32 9 J: \'+6.9.J);7(6.Q.1A)11(12,\'3F 32 9 4d 65: \'+6.Q.9);7(6.V.19)11(12,\'3F 32 9 4d 4T: \'+6.V.9);7(6.X.19)11(12,\'3F 32 9 4d 4U: \'+6.X.9)}};w.4V=C(){w.1n(\'4e\',G);7(w.U(\'2e\')==\'3G\'||w.U(\'2e\')==\'66\'){11(12,\'67 68-69 "2e" 4M 4N "6a" 4W "4X".\')}8 a={\'4f\':w.U(\'4f\'),\'2e\':w.U(\'2e\'),\'2v\':w.U(\'2v\'),\'2w\':w.U(\'2w\'),\'48\':w.U(\'48\'),\'1k\':w.U(\'1k\'),\'M\':w.U(\'M\'),\'1h\':w.U(\'1h\'),\'4g\':w.U(\'4g\'),\'1r\':w.U(\'1r\'),\'3w\':w.U(\'3w\'),\'4h\':w.U(\'4h\')};$1E.U(a).U({\'6b\':\'2J\',\'2e\':(a.2e==\'3G\')?\'3G\':\'4X\'});w.1n(\'4Y\',a).U({\'4f\':\'3H\',\'2e\':\'3G\',\'2v\':0,\'1k\':0,\'4g\':0,\'1r\':0,\'3w\':0,\'4h\':0});7(6.1f){w.S().1O(C(){8 m=2f($(17).U(6.d[\'1r\']));7(2y(m))m=0;$(17).1n(\'1G\',m)})}};w.4Z=C(){w.4i();w.Z(\'4j.R\'+P,C(e,a){e.1o();y.1X=G;7(6.Q.1A){6.Q.1A=K;w.O(\'2K\',a)}});w.Z(\'51.R\'+P,C(e){e.1o();7(y.1H){35(H)}});w.Z(\'2K.R\'+P,C(e,a,b){e.1o();1t=2L(1t);7(a&&y.1H){H.1X=G;8 c=2g()-H.2z;H.1b-=c;7(H.1c)H.1c.1b-=c;7(H.1F)H.1F.1b-=c;35(H,K)}7(!y.2h&&!y.1H){7(b)1t.36+=2g()-1t.2z}y.2h=G;7(6.Q.52){8 d=6.Q.2H-1t.36,3a=3I-1L.2M(d*3I/6.Q.2H);6.Q.52.1u($15,3a,d)}});w.Z(\'1A.R\'+P,C(e,b,c,d){e.1o();1t=2L(1t);8 v=[b,c,d],t=[\'1m\',\'Y\',\'1l\'],a=2N(v,t);8 b=a[0],c=a[1],d=a[2];7(b!=\'V\'&&b!=\'X\')b=y.27;7(D c!=\'Y\')c=0;7(D d!=\'1l\')d=K;7(d){y.1X=K;6.Q.1A=G}7(!6.Q.1A){e.1Y();E 11(12,\'2I 53: 1U 2A.\')}y.2h=K;1t.2z=2g();8 f=6.Q.2H+c;3b=f-1t.36;3a=3I-1L.2M(3b*3I/f);1t.Q=6c(C(){7(6.Q.54){6.Q.54.1u($15,3a,3b)}7(y.1H){w.O(\'1A\',b)}I{w.O(b,6.Q)}},3b);7(6.Q.55){6.Q.55.1u($15,3a,3b)}});w.Z(\'2B.R\'+P,C(e){e.1o();7(H.1X){H.1X=K;y.2h=K;y.1H=G;H.2z=2g();1Z(H)}I{w.O(\'1A\')}});w.Z(\'V.R\'+P+\' X.R\'+P,C(e,b,f,g){e.1o();7(y.1X||w.56(\':2J\')){e.1Y();E 11(12,\'2I 53 4W 2J: 1U 2A.\')}8 v=[b,f,g],t=[\'1e\',\'Y/1m\',\'C\'],a=2N(v,t);8 b=a[0],f=a[1],g=a[2];7(D b!=\'1e\'||b==28)b=6[e.1I];7(D g==\'C\')b.1Q=g;7(D f!=\'Y\'){7(f==\'J\'){7(!6.1T)f=6.9.J}I{7(D b.9==\'Y\')f=b.9;I 7(D 6[e.1I].9==\'Y\')f=6[e.1I].9;I 7(6.1T)f=\'J\';I f=6.9.J}}7(H.1X){w.O(\'2B\');w.O(\'2O\',[e.1I,[b,f,g]]);e.1Y();E 11(12,\'2I 6d 2A.\')}7(6.9.2G>=F.N){e.1Y();E 11(12,\'1U 57 9 (\'+F.N+\', \'+6.9.2G+\' 59): 1U 2A.\')}7(b.1b>0){7(y.1H){7(b.2O)w.O(\'2O\',[e.1I,[b,f,g]]);e.1Y();E 11(12,\'2I 6e 2A.\')}}7(b.4k&&!b.4k.1u($15)){e.1Y();E 11(12,\'6f "4k" 6g K.\')}1t.36=0;w.O(\'5a\'+e.1I,[b,f]);7(6.1W){8 s=6.1W,c=[b,f];1q(8 j=0,l=s.T;j<l;j++){8 d=e.1I;7(!s[j][1])c[0]=s[j][0].2P(\'3Y\',e.1I);7(!s[j][2])d=(d==\'V\')?\'X\':\'V\';c[1]=f+s[j][3];s[j][0].O(\'5a\'+d,c)}}});w.Z(\'6h.R\'+P,C(e,f,g){e.1o();8 h=w.S();7(!6.1R){7(F.W==0){7(6.2Q){w.O(\'X\',F.N-1)}E e.1Y()}}7(6.1f)1B(h,6);7(6.1T){7(D g!=\'Y\'){g=4l(h,6,F.N-1)}}7(!6.1R){7(F.N-g<F.W){g=F.N-F.W}}7(6.1T){8 i=33(h,6,F.N-g);6.9.1V=6.9.J;6.9.J=3C(i,6)}7(6.1f)1B(h,6,G);7(g==0){e.1Y();E 11(12,\'0 9 31 1a: 1U 2A.\')}11(12,\'5b \'+g+\' 9 4T.\');F.W+=g;2i(F.W>=F.N)F.W-=F.N;7(!6.1R){7(F.W==0&&f.3J)f.3J.1u($15);7(!6.2Q)2C(6,F.W)}w.S().1g(F.N-g).6i(w);7(F.N<6.9.J+g){w.S().1g(0,(6.9.J+g)-F.N).3K(G).3c(w)}8 h=w.S(),29=5c(h,6,g),1J=5d(h,6),2a=h.20(g-1),2b=29.2D(),2j=1J.2D();7(6.1f)1B(h,6);7(6.1x)8 p=3D(1J,6);7(f.1v==\'5e\'&&6.9.1V<g){8 j=h.1g(6.9.1V,g).3d(),3L=6.9[6.d[\'M\']];6.9[6.d[\'M\']]=\'1j\'}I{8 j=K}8 k=2R(h.1g(0,g),6,\'M\'),21=3M(2k(1J,6,G),6,!6.1f);7(j)6.9[6.d[\'M\']]=3L;7(6.1f){1B(h,6,G);1B(2b,6,6.18[6.d[1]]);1B(2a,6,6.18[6.d[3]])}7(6.1x){6.18[6.d[1]]=p[1];6.18[6.d[3]]=p[0]}8 l={},4m={},2S={},2T={},1i=f.1b;7(f.1v==\'3H\')1i=0;I 7(1i==\'Q\')1i=6.1a.1b/6.1a.9*g;I 7(1i<=0)1i=0;I 7(1i<10)1i=k/1i;H=1S(1i,f.1w);7(6[6.d[\'M\']]==\'1j\'||6[6.d[\'1h\']]==\'1j\'){H.14.1d([$1E,21])}7(6.1f){8 m=6.18[6.d[3]];2S[6.d[\'1r\']]=2a.1n(\'1G\');4m[6.d[\'1r\']]=2j.1n(\'1G\')+6.18[6.d[1]];2T[6.d[\'1r\']]=2b.1n(\'1G\');7(2j.5f(2a).T){H.14.1d([2a,2S])}H.14.1d([2j,4m]);H.14.1d([2b,2T])}I{8 m=0}l[6.d[\'1k\']]=m;8 n=[29,1J,21,1i];7(f.22)f.22.3e($15,n);1N.22=3f(1N.22,$15,n);1s(f.1v){L\'2l\':L\'23\':L\'2m\':L\'24\':H.1c=1S(H.1b,H.1w);H.1F=1S(H.1b,H.1w);H.1b=0;16}1s(f.1v){L\'23\':L\'2m\':L\'24\':8 o=w.3K().3c($1E);16}1s(f.1v){L\'24\':o.S().1g(0,g).1C();L\'23\':L\'2m\':o.S().1g(6.9.J).1C();16}1s(f.1v){L\'2l\':H.1c.14.1d([w,{\'25\':0}]);16;L\'23\':o.U({\'25\':0});H.1c.14.1d([w,{\'M\':\'+=0\'},C(){o.1C()}]);H.1F.14.1d([o,{\'25\':1}]);16;L\'2m\':H=4n(H,w,o,6,G);16;L\'24\':H=4o(H,w,o,6,G,g);16}8 q=C(){8 b=6.9.J+g-F.N;7(b>0){w.S().1g(F.N).1C();29=w.S().1g(F.N-(g-b)).5g().6j(w.S().1g(0,b).5g())}7(j)j.3g();7(6.1f){8 c=w.S().20(6.9.J+g-1);c.U(6.d[\'1r\'],c.1n(\'1G\'))}H.14=[];7(H.1c)H.1c=1S(H.4p,H.1w);8 d=C(){1s(f.1v){L\'2l\':L\'23\':w.U(\'5h\',\'\');16}H.1F=1S(0,28);y.1H=K;8 a=[29,1J,21];7(f.1Q)f.1Q.3e($15,a);1N.1Q=3f(1N.1Q,$15,a);7(1K.T){w.O(1K[0][0],1K[0][1]);1K.5i()}7(!y.2h)w.O(\'1A\')};1s(f.1v){L\'2l\':H.1c.14.1d([w,{\'25\':1},d]);1Z(H.1c);16;L\'24\':H.1c.14.1d([w,{\'M\':\'+=0\'},d]);1Z(H.1c);16;2x:d();16}};H.14.1d([w,l,q]);y.1H=G;w.U(6.d[\'1k\'],-k);1t=2L(1t);1Z(H);4q(6.1P,w.2P(\'3h\'));w.O(\'2E\',[K,21])});w.Z(\'6k.R\'+P,C(e,f,g){e.1o();8 h=w.S();7(!6.1R){7(F.W==6.9.J){7(6.2Q){w.O(\'V\',F.N-1)}E e.1Y()}}7(6.1f)1B(h,6);7(6.1T){7(D g!=\'Y\'){g=6.9.J}}8 i=(F.W==0)?F.N:F.W;7(!6.1R){7(6.1T){8 j=33(h,6,g),4r=4l(h,6,i-1)}I{8 j=6.9.J,4r=6.9.J}7(g+j>i){g=i-4r}}7(6.1T){8 j=4s(h,6,g,i);2i(6.9.J-g>=j&&g<F.N){g++;j=4s(h,6,g,i)}6.9.1V=6.9.J;6.9.J=3C(j,6)}7(6.1f)1B(h,6,G);7(g==0){e.1Y();E 11(12,\'0 9 31 1a: 1U 2A.\')}11(12,\'5b \'+g+\' 9 4U.\');F.W-=g;2i(F.W<0)F.W+=F.N;7(!6.1R){7(F.W==6.9.J&&f.3J)f.3J.1u($15);7(!6.2Q)2C(6,F.W)}7(F.N<6.9.J+g){w.S().1g(0,(6.9.J+g)-F.N).3K(G).3c(w)}8 h=w.S(),29=4t(h,6),1J=4u(h,6,g),2a=h.20(g-1),2b=29.2D(),2j=1J.2D();7(6.1f)1B(h,6);7(6.1x)8 p=3D(1J,6);7(f.1v==\'5e\'&&6.9.1V<g){8 k=h.1g(6.9.1V,g).3d(),3L=6.9[6.d[\'M\']];6.9[6.d[\'M\']]=\'1j\'}I{8 k=K}8 l=2R(h.1g(0,g),6,\'M\'),21=3M(2k(1J,6,G),6,!6.1f);7(k)6.9[6.d[\'M\']]=3L;7(6.1f){1B(h,6,G);1B(2b,6,6.18[6.d[1]]);1B(2j,6,6.18[6.d[1]])}7(6.1x){6.18[6.d[1]]=p[1];6.18[6.d[3]]=p[0]}8 m={},2T={},2S={},1i=f.1b;7(f.1v==\'3H\')1i=0;I 7(1i==\'Q\')1i=6.1a.1b/6.1a.9*g;I 7(1i<=0)1i=0;I 7(1i<10)1i=l/1i;H=1S(1i,f.1w);7(6[6.d[\'M\']]==\'1j\'||6[6.d[\'1h\']]==\'1j\'){H.14.1d([$1E,21])}7(6.1f){2T[6.d[\'1r\']]=2b.1n(\'1G\');2S[6.d[\'1r\']]=2a.1n(\'1G\')+6.18[6.d[3]];2j.U(6.d[\'1r\'],2j.1n(\'1G\')+6.18[6.d[1]]);7(2a.5f(2b).T){H.14.1d([2b,2T])}H.14.1d([2a,2S])}m[6.d[\'1k\']]=-l;8 n=[29,1J,21,1i];7(f.22)f.22.3e($15,n);1N.22=3f(1N.22,$15,n);1s(f.1v){L\'2l\':L\'23\':L\'2m\':L\'24\':H.1c=1S(H.1b,H.1w);H.1F=1S(H.1b,H.1w);H.1b=0;16}1s(f.1v){L\'23\':L\'2m\':L\'24\':8 o=w.3K().3c($1E);16}1s(f.1v){L\'24\':o.S().1g(6.9.1V).1C();16;L\'23\':L\'2m\':o.S().1g(0,g).1C();o.S().1g(6.9.J).1C();16}1s(f.1v){L\'2l\':H.1c.14.1d([w,{\'25\':0}]);16;L\'23\':o.U({\'25\':0});H.1c.14.1d([w,{\'M\':\'+=0\'},C(){o.1C()}]);H.1F.14.1d([o,{\'25\':1}]);16;L\'2m\':H=4n(H,w,o,6,K);16;L\'24\':H=4o(H,w,o,6,K,g);16}8 q=C(){8 b=6.9.J+g-F.N,5j=(6.1f)?6.18[6.d[3]]:0;w.U(6.d[\'1k\'],5j);7(b>0){w.S().1g(F.N).1C()}8 c=w.S().1g(0,g).3c(w).2D();7(b>0){1J=2F(h,6)}7(k)k.3g();7(6.1f){7(F.N<6.9.J+g){8 d=w.S().20(6.9.J-1);d.U(6.d[\'1r\'],d.1n(\'1G\')+6.18[6.d[3]])}c.U(6.d[\'1r\'],c.1n(\'1G\'))}H.14=[];7(H.1c)H.1c=1S(H.4p,H.1w);8 e=C(){1s(f.1v){L\'2l\':L\'23\':w.U(\'5h\',\'\');16}H.1F=1S(0,28);y.1H=K;8 a=[29,1J,21];7(f.1Q)f.1Q.3e($15,a);1N.1Q=3f(1N.1Q,$15,a);7(1K.T){w.O(1K[0][0],1K[0][1]);1K.5i()}7(!y.2h)w.O(\'1A\')};1s(f.1v){L\'2l\':H.1c.14.1d([w,{\'25\':1},e]);1Z(H.1c);16;L\'24\':H.1c.14.1d([w,{\'M\':\'+=0\'},e]);1Z(H.1c);16;2x:e();16}};H.14.1d([w,m,q]);y.1H=G;1t=2L(1t);1Z(H);4q(6.1P,w.2P(\'3h\'));w.O(\'2E\',[K,21])});w.Z(\'2U.R\'+P,C(e,b,c,d,f,g){e.1o();8 v=[b,c,d,f,g],t=[\'1m/Y/1e\',\'Y\',\'1l\',\'1e\',\'1m\'],a=2N(v,t);8 f=a[3],g=a[4];b=2V(a[0],a[1],a[2],F,w);7(b==0)E;7(D f!=\'1e\')f=K;7(y.1H){7(D f!=\'1e\'||f.1b>0)E}7(g!=\'V\'&&g!=\'X\'){7(6.1R){7(b<=F.N/2)g=\'X\';I g=\'V\'}I{7(F.W==0||F.W>b)g=\'X\';I g=\'V\'}}7(g==\'V\')w.O(\'V\',[f,F.N-b]);I w.O(\'X\',[f,b])});w.Z(\'5k.R\'+P,C(e,s){7(s)s=2V(s,0,G,F,w);I s=0;s+=F.W;7(s!=0){2i(s>F.N)s-=F.N;w.6l(w.S().1g(s))}});w.Z(\'1W.R\'+P,C(e,s){7(s)s=4c(s);I 7(6.1W)s=6.1W;I E 11(12,\'4L 6m 31 1W.\');8 n=w.2P(\'3h\');1q(8 j=0,l=s.T;j<l;j++){s[j][0].O(\'2U\',[n,s[j][3],G])}});w.Z(\'2O.R\'+P,C(e,a,b){7(D a==\'C\'){a.1u($15,1K)}I 7(2W(a)){1K=a}I 7(D a!=\'1y\'){1K.1d([a,b])}E 1K});w.Z(\'6n.R\'+P,C(e,b,c,d,f){e.1o();8 v=[b,c,d,f],t=[\'1m/1e\',\'1m/Y/1e\',\'1l\',\'Y\'],a=2N(v,t);8 b=a[0],c=a[1],d=a[2],f=a[3];7(D b==\'1e\'&&D b.3i==\'1y\')b=$(b);7(D b==\'1m\')b=$(b);7(D b!=\'1e\'||D b.3i==\'1y\'||b.T==0)E 11(12,\'1U a 46 1e.\');7(D c==\'1y\')c=\'3N\';7(6.1f){b.1O(C(){8 m=2f($(17).U(6.d[\'1r\']));7(2y(m))m=0;$(17).1n(\'1G\',m)})}8 g=c,3j=\'3j\';7(c==\'3N\'){7(d){7(F.W==0){c=F.N-1;3j=\'5l\'}I{c=F.W;F.W+=b.T}7(c<0)c=0}I{c=F.N-1;3j=\'5l\'}}I{c=2V(c,f,d,F,w)}7(g!=\'3N\'&&!d){7(c<F.W)F.W+=b.T}7(F.W>=F.N)F.W-=F.N;8 h=w.S().20(c);7(h.T){h[3j](b)}I{w.5m(b)}F.N=w.S().T;8 i=3k(w,6);3l(6,F.N,12);2C(6,F.W);w.O(\'4v\');w.O(\'2E\',[G,i])});w.Z(\'6o.R\'+P,C(e,b,c,d){e.1o();8 v=[b,c,d],t=[\'1m/Y/1e\',\'1l\',\'Y\'],a=2N(v,t);8 b=a[0],c=a[1],d=a[2];7(D b==\'1y\'||b==\'3N\'){w.S().2D().1C()}I{b=2V(b,d,c,F,w);8 f=w.S().20(b);7(f.T){7(b<F.W)F.W-=f.T;f.1C()}}F.N=w.S().T;8 g=3k(w,6);3l(6,F.N,12);2C(6,F.W);w.O(\'2E\',[G,g])});w.Z(\'22.R\'+P+\' 1Q.R\'+P,C(e,a){e.1o();7(2W(a))1N[e.1I]=a;7(D a==\'C\')1N[e.1I].1d(a);E 1N[e.1I]});w.Z(\'3h.R\'+P,C(e,a){e.1o();7(F.W==0)8 b=0;I 8 b=F.N-F.W;7(D a==\'C\')a.1u($15,b);E b});w.Z(\'5n.R\'+P,C(e,a){e.1o();8 b=6.13.9||6.9.J,3O=1L.2M(F.N/b-1);7(F.W==0)8 c=0;I 7(F.W<F.N%b)8 c=0;I 7(F.W==b&&!6.1R)8 c=3O;I 8 c=1L.6p((F.N-F.W)/b);7(c<0)c=0;7(c>3O)c=3O;7(D a==\'C\')a.1u($15,c);E c});w.Z(\'6q.R\'+P,C(e,a){e.1o();$i=2F(w.S(),6);7(D a==\'C\')a.1u($15,$i);E $i});w.Z(\'2h.R\'+P+\' 1X.R\'+P+\' 1H.R\'+P,C(e,a){e.1o();7(D a==\'C\')a.1u($15,y[e.1I]);E y[e.1I]});w.Z(\'3Y.R\'+P,C(e,a,b,c){e.1o();8 d=K;7(D a==\'C\'){a.1u($15,6)}I 7(D a==\'1e\'){2s=$.26(G,{},2s,a);7(b!==K)d=G;I 6=$.26(G,{},6,a)}I 7(D a!=\'1y\'){7(D b==\'C\'){8 f=3P(\'6.\'+a);7(D f==\'1y\')f=\'\';b.1u($15,f)}I 7(D b!=\'1y\'){7(D c!==\'1l\')c=G;3P(\'2s.\'+a+\' = b\');7(c!==K)d=G;I 3P(\'6.\'+a+\' = b\')}I{E 3P(\'6.\'+a)}}7(d){1B(w.S(),6);w.3X(2s);w.4w();8 g=3k(w,6);w.O(\'2E\',[G,g])}E 6});w.Z(\'4v.R\'+P,C(e,a,b){e.1o();7(D a==\'1y\'||a.T==0)a=$(\'6r\');I 7(D a==\'1m\')a=$(a);7(D a!=\'1e\')E 11(12,\'1U a 46 1e.\');7(D b!=\'1m\'||b.T==0)b=\'a.5o\';a.6s(b).1O(C(){8 h=17.5p||\'\';7(h.T>0&&w.S().5q($(h))!=-1){$(17).2n(\'4x\').4x(C(e){e.2c();w.O(\'2U\',h)})}})});w.Z(\'2E.R\'+P,C(e,b,c){e.1o();7(!6.13.1p)E;7(D b==\'1l\'&&b){6.13.1p.S().1C();8 d=6.13.9||6.9.J;1q(8 a=0,l=1L.2M(F.N/d);a<l;a++){8 i=w.S().20(2V(a*d,0,G,F,w));6.13.1p.5m(6.13.4a(a+1,i))}6.13.1p.1O(C(){$(17).S().2n(6.13.3m).1O(C(a){$(17).Z(6.13.3m,C(e){e.2c();w.O(\'2U\',[a*d,0,G,6.13])})})})}6.13.1p.1O(C(){$(17).S().2X(\'5r\').20(w.2P(\'5n\')).3n(\'5r\')})});w.Z(\'5s.R\'+P,C(e,a){e.1o();1t=2L(1t);w.1n(\'4e\',K);w.O(\'51\');7(a){w.O(\'5k\')}7(6.1f){1B(w.S(),6)}w.U(w.1n(\'4Y\'));w.4i();w.4y();$1E.6t(w)})};w.4i=C(){w.2n(\'.R\'+P)};w.4w=C(){w.4y();3l(6,F.N,12);2C(6,F.W);7(6.Q.1M){8 c=3o(6.Q.1M);$1E.Z(\'3Q.R\'+P,C(){w.O(\'2K\',[c[0],c[1]])}).Z(\'3R.R\'+P,C(){w.O(\'2B\')})}7(6.V.19){6.V.19.Z(6.V.3m+\'.R\'+P,C(e){e.2c();w.O(\'V\')});7(6.V.1M){8 c=3o(6.V.1M);6.V.19.Z(\'3Q.R\'+P,C(){w.O(\'2K\',[c[0],c[1]])}).Z(\'3R.R\'+P,C(){w.O(\'2B\')})}}7(6.X.19){6.X.19.Z(6.X.3m+\'.R\'+P,C(e){e.2c();w.O(\'X\')});7(6.X.1M){8 c=3o(6.X.1M);6.X.19.Z(\'3Q.R\'+P,C(){w.O(\'2K\',[c[0],c[1]])}).Z(\'3R.R\'+P,C(){w.O(\'2B\')})}}7($.1D.2o){7(6.V.2o){7(!y.4z){y.4z=G;$1E.2o(C(e,a){7(a>0){e.2c();8 b=4A(6.V.2o);w.O(\'V\',b)}})}}7(6.X.2o){7(!y.4B){y.4B=G;$1E.2o(C(e,a){7(a<0){e.2c();8 b=4A(6.X.2o);w.O(\'X\',b)}})}}}7($.1D.3p){8 d=(6.V.4C)?C(){w.O(\'V\')}:28,3q=(6.X.4C)?C(){w.O(\'X\')}:28;7(3q||3q){7(!y.3p){y.3p=G;8 f={\'6u\':30,\'6v\':30,\'6w\':G};1s(6.27){L\'41\':L\'5t\':f.6x=3q;f.6y=d;16;2x:f.6z=3q;f.6A=d}$1E.3p(f)}}}7(6.13.1p){7(6.13.1M){8 c=3o(6.13.1M);6.13.1p.Z(\'3Q.R\'+P,C(){w.O(\'2K\',[c[0],c[1]])}).Z(\'3R.R\'+P,C(){w.O(\'2B\')})}}7(6.V.2p||6.X.2p){$(2Y).Z(\'5u.R\'+P,C(e){8 k=e.5v;7(k==6.X.2p){e.2c();w.O(\'X\')}7(k==6.V.2p){e.2c();w.O(\'V\')}})}7(6.13.3E){$(2Y).Z(\'5u.R\'+P,C(e){8 k=e.5v;7(k>=49&&k<58){k=(k-49)*6.9.J;7(k<=F.N){e.2c();w.O(\'2U\',[k,0,G,6.13])}}})}7(6.Q.1A){w.O(\'1A\',6.Q.4b)}};w.4y=C(){$(2Y).2n(\'.R\'+P);$1E.2n(\'.R\'+P);7(6.V.19)6.V.19.2n(\'.R\'+P);7(6.X.19)6.X.19.2n(\'.R\'+P);7(6.13.1p)6.13.1p.2n(\'.R\'+P);3l(6,\'3d\',12);2C(6,\'2X\');7(6.13.1p){6.13.1p.S().1C()}};7(w.1n(\'4e\')){8 x=w.2P(\'3h\');w.O(\'5s\',G)}I{8 x=K}8 y={\'27\':\'X\',\'2h\':G,\'1H\':K,\'1X\':K,\'4B\':K,\'4z\':K,\'3p\':K},F={\'N\':w.S().T,\'W\':0},1t={\'6B\':28,\'Q\':28,\'2O\':28,\'2z\':2g(),\'36\':0},H={\'1X\':K,\'1b\':0,\'2z\':0,\'1w\':\'\',\'14\':[]},1N={\'22\':[],\'1Q\':[]},1K=[],12=$.26(G,{},$.1D.1z.5w,u),6={},2s=r,P=$.1D.1z.P++,$1E=w.6C(\'<\'+12.4D.3W+\' 6D="\'+12.4D.5x+\'" />\').3A();12.3s=w.3s;w.3X(2s,G,x);w.4V();w.4Z();w.4w();7(6.1P){6.9.2t=5y(6.1P);8 z=6.1P+\'=\';8 A=2Y.1P.3r(\';\');1q(8 a=0,l=A.T;a<l;a++){8 c=A[a];2i(c.5z(0)==\' \'){c=c.3S(1,c.T)}7(c.2q(z)==0){6.9.2t=c.3S(z.T,c.T);16}}}7(6.9.2t!=0){8 s=6.9.2t;7(s===G){s=3T.6E.5p;7(!s.T)s=0}I 7(s===\'5A\'){s=1L.3z(1L.5A()*F.N)}w.O(\'2U\',[s,0,G,{1v:\'3H\'}])}8 B=3k(w,6,K),5B=2F(w.S(),6);7(6.5C){6.5C.1u($15,5B,B)}w.O(\'2E\',[G,B]);w.O(\'4v\');E w};$.1D.1z.P=0;$.1D.1z.3Z={\'1W\':K,\'2Q\':G,\'1R\':G,\'27\':\'1k\',\'9\':{\'2t\':0},\'1a\':{\'1w\':\'6F\',\'1M\':K,\'2o\':K,\'4C\':K,\'3m\':\'4x\',\'2O\':K}};$.1D.1z.5w={\'11\':K,\'4D\':{\'3W\':\'6G\',\'5x\':\'6H\'}};$.1D.1z.4R=C(a,b){E\'<a 6I="#"><5D>\'+a+\'</5D></a>\'};C 1S(d,e){E{14:[],1b:d,4p:d,1w:e,2z:2g()}}C 1Z(s){7(D s.1c==\'1e\'){1Z(s.1c)}1q(8 a=0,l=s.14.T;a<l;a++){8 b=s.14[a];7(!b)6J;7(b[3])b[0].4j();b[0].5E(b[1],{5F:b[2],1b:s.1b,1w:s.1w})}7(D s.1F==\'1e\'){1Z(s.1F)}}C 35(s,c){7(D c!=\'1l\')c=G;7(D s.1c==\'1e\'){35(s.1c,c)}1q(8 a=0,l=s.14.T;a<l;a++){8 b=s.14[a];b[0].4j(G);7(c){b[0].U(b[1]);7(D b[2]==\'C\')b[2]()}}7(D s.1F==\'1e\'){35(s.1F,c)}}C 2L(t){7(t.Q)6K(t.Q);E t}C 3f(b,t,c){7(b.T){1q(8 a=0,l=b.T;a<l;a++){b[a].3e(t,c)}}E[]}C 6L(a,c,x,d,f){8 o={\'1b\':d,\'1w\':a.1w};7(D f==\'C\')o.5F=f;c.5E({25:x},o)}C 4n(a,b,c,o,d){8 e=2k(4t(b.S(),o),o,G)[0],4E=2k(c.S(),o,G)[0],3U=(d)?-4E:e,2r={},2Z={};2r[o.d[\'M\']]=4E;2r[o.d[\'1k\']]=3U;2Z[o.d[\'1k\']]=0;a.1c.14.1d([b,{\'25\':1}]);a.1F.14.1d([c,2Z,C(){$(17).1C()}]);c.U(2r);E a}C 4o(a,b,c,o,d,n){8 e=2k(4u(b.S(),o,n),o,G)[0],4F=2k(c.S(),o,G)[0],3U=(d)?-4F:e,2r={},2Z={};2r[o.d[\'M\']]=4F;2r[o.d[\'1k\']]=0;2Z[o.d[\'1k\']]=3U;a.1F.14.1d([c,2Z,C(){$(17).1C()}]);c.U(2r);E a}C 3l(o,t,c){7(t==\'3g\'||t==\'3d\'){8 f=t}I 7(o.9.2G>=t){11(c,\'1U 57 9: 6M 6N (\'+t+\' 9, \'+o.9.2G+\' 59).\');8 f=\'3d\'}I{8 f=\'3g\'}8 s=(f==\'3g\')?\'2X\':\'3n\';7(o.V.19)o.V.19[f]()[s](\'2J\');7(o.X.19)o.X.19[f]()[s](\'2J\');7(o.13.1p)o.13.1p[f]()[s](\'2J\')}C 2C(o,f){7(o.1R||o.2Q)E;8 a=(f==\'2X\'||f==\'3n\')?f:K;7(o.X.19){8 b=a||(f==o.9.J)?\'3n\':\'2X\';o.X.19[b](\'5G\')}7(o.V.19){8 b=a||(f==0)?\'3n\':\'2X\';o.V.19[b](\'5G\')}}C 3t(a,b){7(D b==\'C\')b=b.1u(a);7(D b==\'1y\')b={};E b}C 34(a,b,c){7(D c!=\'1m\')c=\'\';b=3t(a,b);7(D b==\'1m\'){8 d=4G(b);7(d==-1)b=$(b);I b=d}7(c==\'13\'){7(D b==\'1l\')b={\'3E\':b};7(D b.3i!=\'1y\')b={\'1p\':b};7(D b.1p==\'C\')b.1p=b.1p.1u(a);7(D b.1p==\'1m\')b.1p=$(b.1p);7(D b.9!=\'Y\')b.9=K}I 7(c==\'Q\'){7(D b==\'1l\')b={\'1A\':b};7(D b==\'Y\')b={\'2H\':b}}I{7(D b.3i!=\'1y\')b={\'19\':b};7(D b==\'Y\')b={\'2p\':b};7(D b.19==\'C\')b.19=b.19.1u(a);7(D b.19==\'1m\')b.19=$(b.19);7(D b.2p==\'1m\')b.2p=4G(b.2p)}E b}C 2V(a,b,c,d,e){7(D a==\'1m\'){7(2y(a))a=$(a);I a=2f(a)}7(D a==\'1e\'){7(D a.3i==\'1y\')a=$(a);a=e.S().5q(a);7(a==-1)a=0;7(D c!=\'1l\')c=K}I{7(D c!=\'1l\')c=G}7(2y(a))a=0;I a=2f(a);7(2y(b))b=0;I b=2f(b);7(c){a+=d.W}a+=b;7(d.N>0){2i(a>=d.N){a-=d.N}2i(a<0){a+=d.N}}E a}C 4l(i,o,s){8 t=0,x=0;1q(8 a=s;a>=0;a--){t+=i.20(a)[o.d[\'2d\']](G);7(t>o.3B)E x;7(a==0)a=i.T;x++}}C 33(i,o,s){8 t=0,x=0;1q(8 a=s,l=i.T-1;a<=l;a++){t+=i.20(a)[o.d[\'2d\']](G);7(t>o.3B)E x;7(a==l)a=-1;x++}}C 4s(i,o,s,l){8 v=33(i,o,s);7(!o.1R){7(s+v>l)v=l-s}E v}C 2F(i,o){E i.1g(0,o.9.J)}C 5c(i,o,n){E i.1g(n,o.9.1V+n)}C 5d(i,o){E i.1g(0,o.9.J)}C 4t(i,o){E i.1g(0,o.9.1V)}C 4u(i,o,n){E i.1g(n,o.9.J+n)}C 1B(i,o,m){8 x=(D m==\'1l\')?m:K;7(D m!=\'Y\')m=0;i.1O(C(){8 t=2f($(17).U(o.d[\'1r\']));7(2y(t))t=0;$(17).1n(\'5H\',t);$(17).U(o.d[\'1r\'],((x)?$(17).1n(\'5H\'):m+$(17).1n(\'1G\')))})}C 3k(a,o,p){8 b=a.3A(),$i=a.S(),$v=2F($i,o),3V=3M(2k($v,o,G),o,p);b.U(3V);7(o.1f){8 c=$v.2D();c.U(o.d[\'1r\'],c.1n(\'1G\')+o.18[o.d[1]]);a.U(o.d[\'2v\'],o.18[o.d[0]]);a.U(o.d[\'1k\'],o.18[o.d[3]])}a.U(o.d[\'M\'],3V[o.d[\'M\']]+(2R($i,o,\'M\')*2));a.U(o.d[\'1h\'],4H($i,o,\'1h\'));E 3V}C 2k(i,o,a){5I=2R(i,o,\'M\',a);5J=4H(i,o,\'1h\',a);E[5I,5J]}C 4H(i,o,a,b){7(D b!=\'1l\')b=K;7(D o[o.d[a]]==\'Y\'&&b)E o[o.d[a]];7(D o.9[o.d[a]]==\'Y\')E o.9[o.d[a]];8 c=(a.4I().2q(\'M\')>-1)?\'2d\':\'2u\';E 43(i,o,c)}C 43(i,o,a){8 s=0;i.1O(C(){8 m=$(17)[o.d[a]](G);7(s<m)s=m});E s}C 45(b,o,c){8 d=b[o.d[c]](),4J=(o.d[c].4I().2q(\'M\')>-1)?[\'6O\',\'6P\']:[\'6Q\',\'6R\'];1q(a=0,l=4J.T;a<l;a++){8 m=2f(b.U(4J[a]));7(2y(m))m=0;d-=m}E d}C 2R(i,o,a,b){7(D b!=\'1l\')b=K;7(D o[o.d[a]]==\'Y\'&&b)E o[o.d[a]];7(D o.9[o.d[a]]==\'Y\')E o.9[o.d[a]]*i.T;8 d=(a.4I().2q(\'M\')>-1)?\'2d\':\'2u\',s=0;i.1O(C(){8 j=$(17);7(j.56(\':J\')){s+=j[o.d[d]](G)}});E s}C 44(i,o,a){8 s=K,v=K;i.1O(C(){c=$(17)[o.d[a]](G);7(s===K)s=c;I 7(s!=c)v=G;7(s==0)v=G});E v}C 3M(a,o,p){7(D p!=\'1l\')p=G;8 b=(o.1f&&p)?o.18:[0,0,0,0];8 c={};c[o.d[\'M\']]=a[0]+b[1]+b[3];c[o.d[\'1h\']]=a[1]+b[0]+b[2];E c}C 2N(c,d){8 e=[];1q(8 a=0,5K=c.T;a<5K;a++){1q(8 b=0,5L=d.T;b<5L;b++){7(d[b].2q(D c[a])>-1&&!e[b]){e[b]=c[a];16}}}E e}C 4Q(p){7(D p==\'1y\')E[0,0,0,0];7(D p==\'Y\')E[p,p,p,p];I 7(D p==\'1m\')p=p.3r(\'6S\').5M(\'\').3r(\'6T\').5M(\'\').3r(\' \');7(!2W(p)){E[0,0,0,0]}1q(8 i=0;i<4;i++){p[i]=2f(p[i])}1s(p.T){L 0:E[0,0,0,0];L 1:E[p[0],p[0],p[0],p[0]];L 2:E[p[0],p[1],p[0],p[1]];L 3:E[p[0],p[1],p[2],p[1]];2x:E[p[0],p[1],p[2],p[3]]}}C 3D(a,o){8 x=(D o[o.d[\'M\']]==\'Y\')?1L.2M(o[o.d[\'M\']]-2R(a,o,\'M\')):0;1s(o.1x){L\'1k\':E[0,x];L\'2w\':E[x,0];L\'47\':2x:E[1L.2M(x/2),1L.3z(x/2)]}}C 3C(x,o){1s(o.3u){L\'+1\':E x+1;L\'-1\':E x-1;L\'3x\':E(x%2==0)?x-1:x;L\'3x+\':E(x%2==0)?x+1:x;L\'3y\':E(x%2==1)?x-1:x;L\'3y+\':E(x%2==1)?x+1:x;2x:E x}}C 4c(s){7(!2W(s))s=[[s]];7(!2W(s[0]))s=[s];1q(8 j=0,l=s.T;j<l;j++){7(D s[j][0]==\'1m\')s[j][0]=$(s[j][0]);7(D s[j][1]!=\'1l\')s[j][1]=G;7(D s[j][2]!=\'1l\')s[j][2]=G;7(D s[j][3]!=\'Y\')s[j][3]=0}E s}C 4G(k){7(k==\'2w\')E 39;7(k==\'1k\')E 37;7(k==\'41\')E 38;7(k==\'5t\')E 40;E-1}C 4q(n,v){7(n)2Y.1P=n+\'=\'+v+\'; 6U=/\'}C 5y(n){n+=\'=\';8 b=2Y.1P.3r(\';\');1q(8 a=0,l=b.T;a<l;a++){8 c=b[a];2i(c.5z(0)==\' \'){c=c.3S(1,c.T)}7(c.2q(n)==0){E c.3S(n.T,c.T)}}E 0}C 3o(p){8 i=(p.2q(\'6V\')>-1)?G:K,r=(p.2q(\'2B\')>-1)?G:K;E[i,r]}C 4A(a){E(D a==\'Y\')?a:28}C 2W(a){E D(a)==\'1e\'&&(a 6W 6X)}C 2g(){E 6Y 6Z().2g()}C 11(d,m){7(D d==\'1e\'){8 s=\' (\'+d.3s+\')\';d=d.11}I{8 s=\'\'}7(!d)E K;7(D m==\'1m\')m=\'1z\'+s+\': \'+m;I m=[\'1z\'+s+\':\',m];7(3T.4K&&3T.4K.5N)3T.4K.5N(m);E K}$.1D.5o=C(o){E 17.1z(o)}})(70);',62,435,'||||||opts|if|var|items|||||||||||||||||||||||||||||function|typeof|return|itms|true|scrl|else|visible|false|case|width|total|trigger|serial|auto|cfs|children|length|css|prev|first|next|number|bind||debug|conf|pagination|anims|tt0|break|this|padding|button|scroll|duration|pre|push|object|usePadding|slice|height|a_dur|variable|left|boolean|string|data|stopPropagation|container|for|marginRight|switch|tmrs|call|fx|easing|align|undefined|carouFredSel|play|sz_resetMargin|remove|fn|wrp|post|cfs_origCssMargin|isScrolling|type|c_new|queu|Math|pauseOnHover|clbk|each|cookie|onAfter|circular|sc_setScroll|variableVisible|Not|oldVisible|synchronise|isStopped|stopImmediatePropagation|sc_startScroll|eq|w_siz|onBefore|crossfade|uncover|opacity|extend|direction|null|c_old|l_cur|l_old|preventDefault|outerWidth|position|parseInt|getTime|isPaused|while|l_new|ms_getSizes|fade|cover|unbind|mousewheel|key|indexOf|css_o|opts_orig|start|outerHeight|top|right|default|isNaN|startTime|scrolling|resume|nv_enableNavi|last|updatePageStatus|gi_getCurrentItems|minimum|pauseDuration|Carousel|hidden|pause|sc_clearTimers|ceil|cf_sortParams|queue|triggerHandler|infinite|ms_getTotalSize|a_cur|a_old|slideTo|gn_getItemIndex|is_array|removeClass|document|ani_o||to|of|gn_getVisibleItemsNext|go_getNaviObject|sc_stopScroll|timePassed||||perc|dur2|appendTo|hide|apply|sc_callCallbacks|show|currentPosition|jquery|before|sz_setSizes|nv_showNavi|event|addClass|bt_pauseOnHoverConfig|touchwipe|wN|split|selector|go_getObject|visibleAdjust|innerWidth|marginBottom|odd|even|floor|parent|maxDimention|cf_getVisibleItemsAdjust|cf_getAlignPadding|keys|Number|absolute|none|100|onEnd|clone|orgW|cf_mapWrapperSizes|end|max|eval|mouseenter|mouseleave|substring|window|cur_l|sz|element|init|configuration|defaults||up|lrgst_b|ms_getTrueLargestSize|ms_hasVariableSizes|ms_getTrueInnerSize|valid|center|bottom||anchorBuilder|delay|cf_getSynchArr|scrolled|cfs_isCarousel|float|marginTop|marginLeft|unbind_events|stop|conditions|gn_getVisibleItemsPrev|a_new|fx_cover|fx_uncover|orgDuration|cf_setCookie|xI|gn_getVisibleItemsNextTestCircular|gi_getOldItemsNext|gi_getNewItemsNext|linkAnchors|bind_buttons|click|unbind_buttons|mousewheelPrev|bt_mousesheelNumber|mousewheelNext|wipe|wrapper|new_w|old_w|cf_getKeyCode|ms_getLargestSize|toLowerCase|arr|console|No|should|be|innerHeight|dx|cf_getPadding|pageAnchorBuilder|Item|backward|forward|build|or|relative|cfs_origCss|bind_events||finish|onPausePause|stopped|onPauseEnd|onPauseStart|is|enough||needed|slide_|Scrolling|gi_getOldItemsPrev|gi_getNewItemsPrev|directscroll|not|get|filter|shift|new_m|jumpToStart|after|append|currentPage|caroufredsel|hash|index|selected|destroy|down|keyup|keyCode|configs|classname|cf_readCookie|charAt|random|itm|onCreate|span|animate|complete|disabled|cfs_tempCssMargin|s1|s2|l1|l2|join|log|found|The|option|moved|the|second|Infinity|Set|caroufredsel_cookie_|attr|id|500|2500|toString|Available|widths|heights|automatically|fixed|Carousels|CSS|attribute|static|overflow|setTimeout|resumed|currently|Callback|returned|slide_prev|prependTo|concat|slide_next|prepend|carousel|insertItem|removeItem|round|currentVisible|body|find|replaceWith|min_move_x|min_move_y|preventDefaultEvents|wipeUp|wipeDown|wipeLeft|wipeRight|timer|wrap|class|location|swing|div|caroufredsel_wrapper|href|continue|clearTimeout|fx_fade|hiding|navigation|paddingLeft|paddingRight|paddingTop|paddingBottom|px|em|path|immediate|instanceof|Array|new|Date|jQuery'.split('|'),0,{}));

// slideshow plugin
jQuery.fn.fadeGallery = function(_options){
	var _options = jQuery.extend({
		slideElements:'div.slideset > div',
		pagerLinks:'div.pager a',
		generatePagination:null,
		btnNext:'a.next',
		btnPrev:'a.prev',
		btnPlayPause:'a.play-pause',
		btnPlay:'a.play',
		btnPause:'a.pause',
		pausedClass:'paused',
		disabledPrevClass: 'prev-disabled',
		disabledNextClass: 'next-disabled',
		playClass:'playing',
		activeClass:'active',
		loadingClass:'ajax-loading',
		loadedClass:'slide-loaded',
		dynamicImageLoad:false,
		dynamicImageLoadAttr:'alt',
		currentNum:false,
		allNum:false,
		startSlide:null,
		noCircle:false,
		pauseOnHover:true,
		autoRotation:false,
		autoHeight:false,
		onBeforeFade:false,
		onAfterFade:false,
		onChange:false,
		disableWhileAnimating:false,
		stopAfterClick:false,
		switchTime:5000,
		duration:650,
		event:'click'
	},_options);

	return this.each(function(){
		// gallery options
		if(this.slideshowInit) return; else this.slideshowInit;
		var _this = jQuery(this);
		var _slides = jQuery(_options.slideElements, _this);
		var _pagerLinks = jQuery(_options.pagerLinks, _this);
		var _generatePagination = jQuery(_options.generatePagination, _this);
		var _btnPrev = jQuery(_options.btnPrev, _this);
		var _btnNext = jQuery(_options.btnNext, _this);
		var _btnPlayPause = jQuery(_options.btnPlayPause, _this);
		var _btnPause = jQuery(_options.btnPause, _this);
		var _btnPlay = jQuery(_options.btnPlay, _this);
		var _pauseOnHover = _options.pauseOnHover;
		var _dynamicImageLoad = _options.dynamicImageLoad;
		var _dynamicImageLoadAttr = _options.dynamicImageLoadAttr;
		var _autoRotation = _options.autoRotation;
		var _activeClass = _options.activeClass;
		var _loadingClass = _options.loadingClass;
		var _loadedClass = _options.loadedClass;
		var _disabledNextClass = _options.disabledNextClass;
		var _disabledPrevClass = _options.disabledPrevClass;
		var _pausedClass = _options.pausedClass;
		var _playClass = _options.playClass;
		var _autoHeight = _options.autoHeight;
		var _stopAfterClick = _options.stopAfterClick;
		var _duration = _options.duration;
		var _switchTime = _options.switchTime;
		var _controlEvent = _options.event;
		var _currentNum = (_options.currentNum ? jQuery(_options.currentNum, _this) : false);
		var _allNum = (_options.allNum ? jQuery(_options.allNum, _this) : false);
		var _startSlide = _options.startSlide;
		var _noCycle = _options.noCircle;
		var _onChange = _options.onChange;
		var _onBeforeFade = _options.onBeforeFade;
		var _onAfterFade = _options.onAfterFade;
		var _disableWhileAnimating = _options.disableWhileAnimating;

		// gallery init
		var _anim = false;
		var _hover = false;
		var _pause = false;
		var _prevIndex = 0;
		var _currentIndex = 0;
		var _slideCount = _slides.length;
		var _timer;
		if(_slideCount < 2) return;

		_prevIndex = _slides.index(_slides.filter('.'+_activeClass));
		if(_prevIndex < 0) _prevIndex = _currentIndex = 0;
		else _currentIndex = _prevIndex;
		if(_startSlide != null) {
			if(_startSlide == 'random') _prevIndex = _currentIndex = Math.floor(Math.random()*_slideCount);
			else _prevIndex = _currentIndex = parseInt(_startSlide);
		}
		_slides.hide().eq(_currentIndex).show();
		if(_autoRotation) _this.removeClass(_pausedClass).addClass(_playClass);
		else _this.removeClass(_playClass).addClass(_pausedClass);
		if(_autoHeight) _slides.eq(_currentIndex).parent().css({height:_slides.eq(_currentIndex).outerHeight(true)});
		
		// gallery control
		if(_btnPrev.length) {
			_btnPrev.bind(_controlEvent,function(){
				if ( _stopAfterClick ) {
					_autoRotation = false;
					if(_timer) clearTimeout(_timer);
					_this.removeClass(_playClass).addClass(_pausedClass);
				}
				prevSlide();
				return false;
			});
		}
		if(_btnNext.length) {
			_btnNext.bind(_controlEvent,function(){
				if ( _stopAfterClick ) {
					_autoRotation = false;
					if(_timer) clearTimeout(_timer);
					_this.removeClass(_playClass).addClass(_pausedClass);
				}
				nextSlide();
				return false;
			});
		}
		if(_generatePagination.length){
			_generatePagination.empty();
			var _list = jQuery('<ul class="switcher" />').appendTo(_generatePagination);
			for(var i=0; i<_slideCount; i++) $('<li><a href="#">'+(i+1)+'</a></li>').appendTo(_list);
			_pagerLinks = _list.children();
		}
		if(_pagerLinks.length) {
			_pagerLinks.each(function(_ind){
				jQuery(this).bind(_controlEvent,function(){
					if(_currentIndex != _ind) {
						if(_disableWhileAnimating && _anim) return;
						_prevIndex = _currentIndex;
						_currentIndex = _ind;
						if ( _stopAfterClick ) {
							_autoRotation = false;
							if(_timer) clearTimeout(_timer);
							_this.removeClass(_playClass).addClass(_pausedClass);
						}
						switchSlide();
					}
					return false;
				});
			});
		}

		// play pause section
		if(_btnPlayPause.length) {
			_btnPlayPause.bind(_controlEvent,function(){
				if(_this.hasClass(_pausedClass)) {
					_this.removeClass(_pausedClass).addClass(_playClass);
					_autoRotation = true;
					autoSlide();
				} else {
					_autoRotation = false;
					if(_timer) clearTimeout(_timer);
					_this.removeClass(_playClass).addClass(_pausedClass);
				}
				return false;
			});
		}
		if(_btnPlay.length) {
			_btnPlay.bind(_controlEvent,function(){
				_this.removeClass(_pausedClass).addClass(_playClass);
				_autoRotation = true;
				autoSlide();
				return false;
			});
		}
		if(_btnPause.length) {
			_btnPause.bind(_controlEvent,function(){
				_autoRotation = false;
				if(_timer) clearTimeout(_timer);
				_this.removeClass(_playClass).addClass(_pausedClass);
				return false;
			});
		}

		// dynamic image loading (swap from ATTRIBUTE)
		function loadSlide(slide) {
			if(!slide.hasClass(_loadingClass) && !slide.hasClass(_loadedClass)) {
				var images = slide.find(_dynamicImageLoad) // pass selector here
				var imagesCount = images.length;
				if(imagesCount) {
					slide.addClass(_loadingClass);
					images.each(function(){
						var img = this;
						img.onload = function(){
							img.loaded = true;
							img.onload = null;
							setTimeout(reCalc,_duration);
						}
						img.setAttribute('src', img.getAttribute(_dynamicImageLoadAttr));
						img.setAttribute(_dynamicImageLoadAttr,'');
					}).css({opacity:0});

					function reCalc() {
						var cnt = 0;
						images.each(function(){
							if(this.loaded) cnt++;
						});
						if(cnt == imagesCount) {
							slide.removeClass(_loadingClass);
							images.animate({opacity:1},{duration:_duration,complete:function(){
								if(jQuery.browser.msie && jQuery.browser.version < 9) jQuery(this).css({opacity:'auto'})
							}});
							slide.addClass(_loadedClass)
						}
					}
				}
			}
		}

		// gallery animation
		function prevSlide() {
			if(_disableWhileAnimating && _anim) return;
			_prevIndex = _currentIndex;
			if(_currentIndex > 0) _currentIndex--;
			else {
				if(_noCycle) return;
				else _currentIndex = _slideCount-1;
			}
			switchSlide();
		}
		function nextSlide() {
			if(_disableWhileAnimating && _anim) return;
			_prevIndex = _currentIndex;
			if(_currentIndex < _slideCount-1) _currentIndex++;
			else {
				if(_noCycle) return;
				else _currentIndex = 0;
			}
			switchSlide();
		}
		function refreshStatus() {
			if(_dynamicImageLoad) loadSlide(_slides.eq(_currentIndex));
			if(_pagerLinks.length) _pagerLinks.removeClass(_activeClass).eq(_currentIndex).addClass(_activeClass);
			if(_currentNum) _currentNum.text(_currentIndex+1);
			if(_allNum) _allNum.text(_slideCount);
			_slides.eq(_prevIndex).removeClass(_activeClass);
			_slides.eq(_currentIndex).addClass(_activeClass);
			if(_noCycle) {
				if(_btnPrev.length) {
					if(_currentIndex == 0) _btnPrev.addClass(_disabledPrevClass);
					else _btnPrev.removeClass(_disabledPrevClass);
				}
				if(_btnNext.length) {
					if(_currentIndex == _slideCount-1) _btnNext.addClass(_disabledNextClass);
					else _btnNext.removeClass(_disabledNextClass);
				}
			}
			if(typeof _onChange === 'function') {
				_onChange(_this, _slides, _prevIndex, _currentIndex);
			}
		}
		function switchSlide() {
			_anim = true;
			if(typeof _onBeforeFade === 'function') _onBeforeFade(_this, _slides, _prevIndex, _currentIndex);
			_slides.eq(_prevIndex).fadeOut(_duration,function(){
				_anim = false;
			});
			_slides.eq(_currentIndex).fadeIn(_duration,function(){
				if(typeof _onAfterFade === 'function') _onAfterFade(_this, _slides, _prevIndex, _currentIndex);
			});
			if(_autoHeight) _slides.eq(_currentIndex).parent().animate({height:_slides.eq(_currentIndex).outerHeight(true)},{duration:_duration,queue:false});
			refreshStatus();
			autoSlide();
		}

		// autoslide function
		function autoSlide() {
			if(!_autoRotation || _hover || _pause) return;
			if(_timer) clearTimeout(_timer);
			_timer = setTimeout(nextSlide,_switchTime+_duration);
		}
		if(_pauseOnHover) {
			_this.hover(function(){
				_hover = true;
				if(_timer) clearTimeout(_timer);
			},function(){
				_hover = false;
				autoSlide();
			});
		}
		
		_this.bind('pause', function() {
			_pause = true;
			if(_timer) clearTimeout(_timer);
		}).bind('play', function() {
			_pause = false;
			autoSlide();
		})
		
		refreshStatus();
		autoSlide();
	});
}

// mobile browsers detect
browserPlatform = {
	platforms: [
		{ uaString:['symbian','midp'], cssFile:'symbian.css' }, // Symbian phones
		{ uaString:['opera','mobi'], cssFile:'opera.css' }, // Opera Mobile
		{ uaString:['msie','ppc'], cssFile:'ieppc.css' }, // IE Mobile <6
		{ uaString:'iemobile', cssFile:'iemobile.css' }, // IE Mobile 6+
		{ uaString:'webos', cssFile:'webos.css' }, // Palm WebOS
		{ uaString:'Android', cssFile:'android.css' }, // Android
		{ uaString:['BlackBerry','/6.0','mobi'], cssFile:'blackberry6.css' },	// Blackberry 6
		{ uaString:['BlackBerry','/7.0','mobi'], cssFile:'blackberry7.css' },	// Blackberry 7+
		{ uaString:'ipad', cssFile:'ipad.css', miscHead:'<meta name="viewport" content="width=device-width" />' }, // iPad
		{ uaString:['safari','mobi'], cssFile:'safari.css', miscHead:'<meta name="viewport" content="width=device-width" />' } // iPhone and other webkit browsers
	],
	options: {
		cssPath:'css/',
		mobileCSS:'allmobile.css'
	},
	init:function(){
		this.checkMobile();
		this.parsePlatforms();
		return this;
	},
	checkMobile: function() {
		if(this.uaMatch('mobi') || this.uaMatch('midp') || this.uaMatch('ppc') || this.uaMatch('webos')) {
			this.attachStyles({cssFile:this.options.mobileCSS});
		}
	},
	parsePlatforms: function() {
		for(var i = 0; i < this.platforms.length; i++) {
			if(typeof this.platforms[i].uaString === 'string') {
				if(this.uaMatch(this.platforms[i].uaString)) {
					this.attachStyles(this.platforms[i]);
					break;
				}
			} else {
				for(var j = 0, allMatch = true; j < this.platforms[i].uaString.length; j++) {
					if(!this.uaMatch(this.platforms[i].uaString[j])) {
						allMatch = false;
					}
				}
				if(allMatch) {
					this.attachStyles(this.platforms[i]);
					break;
				}
			}
		}
	},
	attachStyles: function(platform) {
		var head = document.getElementsByTagName('head')[0], fragment;
		var cssText = '<link rel="stylesheet" href="' + this.options.cssPath + platform.cssFile + '" type="text/css"/>';
		var miscText = platform.miscHead;
		if(platform.cssFile) {
			if(document.body) {
				fragment = document.createElement('div');
				fragment.innerHTML = cssText;
				head.appendChild(fragment.childNodes[0]);
			} else {
				//document.write(cssText);
			}
		}
		if(platform.miscHead) {
			if(document.body) {
				fragment = document.createElement('div');
				fragment.innerHTML = miscText;
				head.appendChild(fragment.childNodes[0]);
			} else {
				document.write(miscText);
			}
		}
	},
	uaMatch:function(str) {
		if(!this.ua) {
			this.ua = navigator.userAgent.toLowerCase();
		}
		return this.ua.indexOf(str.toLowerCase()) != -1;
	}
}.init();