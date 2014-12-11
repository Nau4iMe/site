$(document).ready(function() {
	$('#main-search-input').focus(function() {
        $(this).animate({ width: '250px' }, { queue: false } )
    });
    $('#main-search-input').focusout(function() {
        $(this).animate({ width: '85px' }, { queue: false } )
    });
});