$(document).ready(function() {
	Reorder.init();
});

var Reorder = {
	init: function() {
		$('span[move]').click(function() {
			Reorder.move(this);
		});
	},
	
	move: function(obj) {
		var row = $(obj).closest('tr');
		var form = $('#OptionMoveForm');
		$('.option-id').val(row.attr('row-id'));
		$('.option-move').val($(obj).attr('move'));
		form.submit();
	}
};