$(function () {

	if (!$('#byday').length) { return false; }
	
	byday ();

	$(window).resize (target_admin.debounce (byday, 325));

});
$(function () {

	if (!$('#byqtr').length) { return false; }
	
	byqtr ();

	$(window).resize (target_admin.debounce (byqtr, 325));

});
$(function () {

	if (!$('#byyear').length) { return false; }
	
	byyear ();

	$(window).resize (target_admin.debounce (byyear, 325));

});



