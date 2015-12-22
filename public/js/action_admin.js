$('#wrapper').on('change', '#check_all', function() {
	$('tbody input[type=checkbox]').prop('checked', $(this).prop('checked'));
});
$('table').on('change', 'tbody input[type=checkbox]', function() {
	var checked = $('tbody input[type=checkbox]').length == $('tbody input[type=checkbox]:checked').length;

	$('#check_all').prop('checked', checked);
});