// -------------------------------------------------------
// Executes when HTML-Document is loaded and DOM is ready
// -------------------------------------------------------
jQuery(document).ready(function($){
    // ASSIGN ACTION TO ALL CLOSE BUTTONS
    $('.alert .close').click(function(){
        $(this).parent().hide();
    });

	var ua = navigator.userAgent;
	var meta = document.createElement('meta');
	if((ua.toLowerCase().indexOf('android') > -1 && ua.toLowerCase().indexOf('mobile')) || ((ua.match(/iPhone/i)) || (ua.match(/iPad/i)))){
		meta.name = 'viewport';
		meta.content = 'target-densitydpi=device-dpi, width=device-width';
	}
	var m = document.getElementsByTagName('meta')[0];
	m.parentNode.insertBefore(meta,m);
	
});

