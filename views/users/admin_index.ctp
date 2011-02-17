<?php
echo $this->Html->css('jquery-ui-1.8.2.custom.css');
echo $this->Html->css('ui.jqgrid.css'); 
echo $javascript->link('grid.locale-en');	
echo $javascript->link('jquery.jqGrid.min');
echo $javascript->link('jquery-ui-1.8.6.custom.min');
?>
<?php $this->pageTitle = 'Admin'; ?>
<form>
<fieldset>
<legend>Welcome <?php echo $session->read('Auth.User.first_name'); ?></legend>
Welcome to the Administrative Section of <b><i>Freegal Music</i></b>
</fieldset>
</form>
<?php if($this->Session->read('Auth.User.type_id') == 1){ ?>
<?php $this->pageTitle = 'Libraries'; ?>
<form>
<fieldset>
<legend>Library Listing</legend>
<br class="clr">
<div id="library_search">
 <a name="bottom">Library Search&nbsp;</a>&nbsp;
 <?php echo $html->link('ALL',array('controller' => 'users', 'action' => 'admin_index'));?>&nbsp;
 <?php echo $html->link('A',array('controller' => 'users', 'action' => 'admin_index', 'A'));?>&nbsp;
 <?php echo $html->link('B',array('controller' => 'users', 'action' => 'admin_index', 'B'));?>&nbsp;
 <?php echo $html->link('C',array('controller' => 'users', 'action' => 'admin_index', 'C'));?>&nbsp;
 <?php echo $html->link('D',array('controller' => 'users', 'action' => 'admin_index', 'D'));?>&nbsp;
 <?php echo $html->link('E',array('controller' => 'users', 'action' => 'admin_index', 'E'));?>&nbsp;
 <?php echo $html->link('F',array('controller' => 'users', 'action' => 'admin_index', 'F'));?>&nbsp;
 <?php echo $html->link('G',array('controller' => 'users', 'action' => 'admin_index', 'G'));?>&nbsp;
 <?php echo $html->link('H',array('controller' => 'users', 'action' => 'admin_index', 'H'));?>&nbsp;
 <?php echo $html->link('I',array('controller' => 'users', 'action' => 'admin_index', 'I'));?>&nbsp;
 <?php echo $html->link('J',array('controller' => 'users', 'action' => 'admin_index', 'J'));?>&nbsp;
 <?php echo $html->link('K',array('controller' => 'users', 'action' => 'admin_index', 'K'));?>&nbsp;
 <?php echo $html->link('L',array('controller' => 'users', 'action' => 'admin_index', 'L'));?>&nbsp;
 <?php echo $html->link('M',array('controller' => 'users', 'action' => 'admin_index', 'M'));?>&nbsp;
 <?php echo $html->link('N',array('controller' => 'users', 'action' => 'admin_index', 'N'));?>&nbsp;
 <?php echo $html->link('O',array('controller' => 'users', 'action' => 'admin_index', 'O'));?>&nbsp;
 <?php echo $html->link('P',array('controller' => 'users', 'action' => 'admin_index', 'P'));?>&nbsp;
 <?php echo $html->link('Q',array('controller' => 'users', 'action' => 'admin_index', 'Q'));?>&nbsp;
 <?php echo $html->link('R',array('controller' => 'users', 'action' => 'admin_index', 'R'));?>&nbsp;
 <?php echo $html->link('S',array('controller' => 'users', 'action' => 'admin_index', 'S'));?>&nbsp;
 <?php echo $html->link('T',array('controller' => 'users', 'action' => 'admin_index', 'T'));?>&nbsp;
 <?php echo $html->link('U',array('controller' => 'users', 'action' => 'admin_index', 'U'));?>&nbsp;
 <?php echo $html->link('V',array('controller' => 'users', 'action' => 'admin_index', 'V'));?>&nbsp;
 <?php echo $html->link('W',array('controller' => 'users', 'action' => 'admin_index', 'W'));?>&nbsp;
 <?php echo $html->link('X',array('controller' => 'users', 'action' => 'admin_index', 'X'));?>&nbsp;
 <?php echo $html->link('Y',array('controller' => 'users', 'action' => 'admin_index', 'Y'));?>&nbsp;
 <?php echo $html->link('Z',array('controller' => 'users', 'action' => 'admin_index', 'Z'));?>&nbsp;
</div>
<br class="clr">
<p>
<script type="text/javascript">
var sort;
<?php if($sortBy != ''){ ?> 
	var sort = '<?php echo $sortBy;?>';
<?php } else {?>
	var sort = '';
<?php } ?>
var gridUrl = webroot+'admin/users/data/'+sort;
 $(document).ready(function() {
	jQuery("#reportList").jqGrid({ url:gridUrl, 
		datatype: 'xml', 
		mtype: 'POST',
		loadonce:true,
		rowNum: 20,
		colNames:['First Name', 'Contract Start Date', 'Contract End Date', 'Today', 'Week', 'Month', 'YTD', 'Remaining'], 
		colModel :[
				   {name:'library_name', index:'library_name', width:145,editable:true, edittype:'text',align:"left", formatter:linkFormatter},
				   {name:'library_contract_start_date', index:'library_contract_start_date', width:155,editable:true, edittype:'text',align:"left",sorttype: "date", datefmt: "Y-m-d"},
				   {name:'library_contract_end_date', index:'library_contract_end_date', width:155,editable:true, edittype:'text',align:"left",sorttype: "date", datefmt: "Y-m-d"},
				   {name:'today', index:'today', width:85,editable:true, edittype:'text',align:"left"},
				   {name:'week', index:'week', width:85,editable:true, edittype:'text',align:"left"},
				   {name:'month', index:'month', width:85,editable:true, edittype:'text',align:"left"},
				   {name:'ytd', index:'ytd', width:85,editable:true, edittype:'text',align:"left"},
				   {name:'library_available_downloads', index:'library_available_downloads', width:85,editable:true, edittype:'text',align:"left"},
				  ],
		pager: jQuery('#pager'), 
		rowList:[20,40,60,80], 
		sortname: 'library_name', 
		sortorder: "asc",
		viewrecords: true, 
		height:440,
		width:895,
		userToolbar:'<span class="grid_title">Library Management</span>',
		imgpath: webroot+'css/themes/redmond/images/', caption: 'Library Report'
		});
	
	//getUserList(gridUrl);
 });
 
 function linkFormatter(el, cellval, opts) {
	//console.log(el+'---'+cellval+'---'+opts);
	//console.log(cellval.rowId);
	//console.log(el);
	//console.log(opts);
	
	return '<a href="/admin/libraries/libraryform/id:'+cellval.rowId+'">'+el+'</a>';
 }
</script>

	<table id="reportList"></table>
	<div id="pager" class="scroll"></div>
 </fieldset>
<?php 
 echo $session->flash();
?>
</form>
<?php } ?>