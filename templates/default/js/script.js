$j = jQuery.noConflict();

$j(document).ready(function() {
	"use strict";
	ApplyLightbox();
	ResponsiveMenu();
	RollUpMenu();
	SubmenuClass();
	WidgetsSize('footer-widgets');


});


function SubmenuClass() {
	"use strict";
	var menuLinks = $j('nav.mainmenu a');
	$j(menuLinks).each(function () {
		if($j(this).next().is('ul')){
			$j(this).addClass('has-submenu');
		}
	});
}

function RollUpMenu(){
	"use strict";
	$j("nav.mainmenu ul li").hover(function(){
		var submenu = $j(this).find('> ul');
		var submenuHeight = submenu.innerHeight();
		submenu.show().height(0).stop(true,true).animate({
			height: submenuHeight
		});
	}, function(){
		$j(this).find('> ul').hide();
	});
}

function ResponsiveMenu() {
	"use strict";
	// Save list menu and create select
	var mainNavigation = $j('nav.mainmenu').clone();
	$j('nav.mainmenu').append('<select class="responsive-menu"></select>');
	var selectMenu = $j('select.responsive-menu');
	$j(selectMenu).append('<option>Main Menu...</option>');

	// Loop through each first level list items
	$j(mainNavigation).children('ul').children('li').each(function() {

		// Save menu item's attributes
 		var href = $j(this).children('a').attr('href'),
			text = $j(this).children('a').text();

		// Create menu item's option
		$j(selectMenu).append('<option value="'+href+'">'+text+'</option>');

		// Check if there is a second level of menu
		if ($j(this).children('ul').length > 0) {

			// Loop through each second level list items
			$j(this).children('ul').children('li').each(function() {

				// Save menu item's attributes
				var href2 = $j(this).children('a').attr('href'),
					text2 = $j(this).children('a').text();

				// Create menu item's option
				$j(selectMenu).append('<option value="'+href2+'">--- '+text2+'</option>');

				// Check if there is a third level of menu
				if ($j(this).children('ul').length > 0) {

					// Loop through each third level list items
					$j(this).children('ul').children('li').each(function() {

						// Save menu item's attributes
						var href3 = $j(this).children('a').attr('href'),
							text3 = $j(this).children('a').text();
						// Create menu item's option
						$j(selectMenu).append('<option value="'+href3+'">------ '+text3+'</option>');

					}); 	// End of third level loop
				} 			// If there is third level
			}); 			// End of second level loop
		} 					// If there is second level
	}); 					// End of first level loop

	$j(selectMenu).change(function() {
		location = this.options[this.selectedIndex].value;
	});
}


function WidgetsSize(sidebar) {
	"use strict";
	 $j('#'+sidebar+' .widget-container').each( function(index) {
	 	$j(this).addClass('col-' + (index + 1));
	 });
}



function ApplyLightbox(){
	"use strict";
	// make galleries
	//var selector = "a[href$='gif'], a[href$='jpg'], a[href$='png']",
	//	linksWithImages = $j(selector);
	//// groups
	//linksWithImages.attr('rel', 'prettyPhoto[]');
	//$j(".widget_flickr").find(selector).attr('rel', 'prettyPhoto[flickr]');
	//$j(".gallery").find(selector).attr('rel', 'prettyPhoto[gallery]');
	//// apply pretty photo

	//linksWithImages.prettyPhoto({
	//	allow_resize: true,
	//	//default_width: window.innerWidth - 20,
	//	deeplinking: false,
	//	social_tools: '',
	//});


}

