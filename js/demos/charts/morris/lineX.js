$(function () {

	if (!$('#line-chart').length) { return false; }
	
	line ();

	$(window).resize (target_admin.debounce (line, 325));

});