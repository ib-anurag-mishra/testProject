$(document).ready(function() {
	$('#table_1').tableDnD({
			onDrop: function(table, row) {
				 $.ajax({
					type: "GET",
					url: "/admin/questions/reorder&data="+$.tableDnD.serialize()
				});
			}
	});
});