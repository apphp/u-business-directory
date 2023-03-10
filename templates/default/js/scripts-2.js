// selected
var mapDiv,
	map,
	infobox;

// the variable stores all Categories
var allCategories = null;
// the variable stores all Listings
var allLocations = null;

// the variable stores maximum length of drop-down list
//var maxLengthList = 9;

jQuery(document).ready(function($) {
	"use strict";
	
	var tabRegister = jQuery('#ait-dir-register-tab'),
		tabLogin =  jQuery('#ait-dir-login-tab'),
		linkLogin = jQuery('#ait-login-tabs .login'),
		linkRegister = jQuery('#ait-login-tabs .register');
	linkLogin.click(function(event) {
		linkRegister.parent().removeClass('active');
		tabRegister.hide();
		linkLogin.parent().addClass('active');
		tabLogin.show();
		event.preventDefault();
	});
	linkRegister.click(function(event) {
		linkLogin.parent().removeClass('active');
		tabLogin.hide();
		linkRegister.parent().addClass('active');
		tabRegister.show();
		event.preventDefault();
	});
	
	var catInput = jQuery( "#dir-searchinput-category" ),
		catInputID = jQuery( "#dir-searchinput-category-id" ),
		locInput = jQuery( "#dir-searchinput-location" ),
		locInputID = jQuery( "#dir-searchinput-location-id" );
		
	catInput.autocomplete({
		minLength: 0,
		source: function(request, response){
			var error = false;
			if(allCategories === null){
				$.ajax({
					url: 'AjaxHandler/getAllCategories',
					global: false,
					type: 'POST',
					data: ({
					}),
					dataType: 'html',
					async: true,
					error: function(html){
						console.error("AJAX: cannot connect to the server or server response error!");
						console.log(html);
					},
					success: function(html){
						try{
							var obj = $.parseJSON(html);
							allCategories = obj;
						}catch(err){
							console.error("AJAX: cannot connect to the server or server response error!");
							console.log(err)
						}
					}
				})
			}
			if(allCategories!==null){
				var getCategories = [];
				allCategories.forEach(function(item, i, arr){
					if(0 <= item.label.toLowerCase().indexOf(request.term.toLowerCase())){
						getCategories.push(item);
					}
				});
				response(getCategories);
				//response(getCategories.slice(0,maxLengthList));
			}
		},
		focus: function(event, ui){
			var val = ui.item.label.replace(/&amp;/g, "&");
				val = val.replace(/&nbsp;/g, " ");
			catInput.val(val);
			return false;
		},
		select: function(event, ui){
			var val = ui.item.label.replace(/&amp;/g, "&");
				val = val.replace(/&nbsp;/g, " ");
			catInput.val(val);
			catInputID.val(ui.item.value);
			return false;
		}
	}).data("ui-autocomplete")._renderItem = function( ul, item ) {
		return jQuery("<li>")
			.data("item.autocomplete", item)
			.append("<a>" + item.label + "</a>")
			.appendTo( ul );
	};

	var catList = catInput.autocomplete("widget");
	catList.niceScroll({ autohidemode: false });

	catInput.click(function(){
		catInput.val('');
		catInputID.val('0');
		catInput.autocomplete("search", "");
	});

	locInput.autocomplete({
		minLength: 0,
		source: function(request, response){
			var error = false;
			if(allLocations === null){
				$.ajax({
					url: 'AjaxHandler/getAllLocations',
					global: false,
					type: 'POST',
					data: ({
					}),
					dataType: 'html',
					async: true,
					error: function(html){
						console.error("AJAX: cannot connect to the server or server response error!");
						console.log(html);
					},
					success: function(html){
						try{
							var obj = $.parseJSON(html);
							allLocations = obj;
						}catch(err){
							console.error("AJAX: cannot connect to the server or server response error!");
							console.log(err)
						}
					}
				})
			}
			if(allLocations!==null){
				var getLocations = [];
				allLocations.forEach(function(item, i, arr){
					if(0 <= item.label.toLowerCase().indexOf(request.term.toLowerCase())){
						getLocations.push(item);
					}
				});
				response(getLocations);
				//response(getLocations.slice(0,maxLengthList));
			}
		},
		focus: function(event, ui){
			var val = ui.item.label.replace(/&amp;/g, "&");
				val = val.replace(/&nbsp;/g, " ");
			locInput.val(val);
			return false;
		},
		select: function(event, ui){
			var val = ui.item.label.replace(/&amp;/g, "&");
				val = val.replace(/&nbsp;/g, " ");
			locInput.val(val);
			locInputID.val(ui.item.value);
			return false;
		}
	}).data("ui-autocomplete")._renderItem = function(ul, item){
		return jQuery("<li>")
			.data("item.autocomplete", item)
			.append("<a>" + item.label + "</a>")
			.appendTo( ul );
	};
	var locList = locInput.autocomplete("widget");
	locList.niceScroll({ autohidemode: false });

	locInput.click(function(){
		locInput.val('');
		locInputID.val('0');
		locInput.autocomplete( "search", "" );
	});
});

jQuery(document).ready(function($) {
// loading spinner
"use strict";
mapDiv = jQuery("#directory-main-bar");
var opts = {
	lines: 13, // The number of lines to draw
	length: 9, // The length of each line
	width: 9, // The line thickness
	radius: 27, // The radius of the inner circle
	corners: 1, // Corner roundness (0..1)
	rotate: 0, // The rotation offset
	color: '#FFF', // #rgb or #rrggbb
	speed: 1.8, // Rounds per second
	trail: 81, // Afterglow percentage
	shadow: true, // Whether to render a shadow
	hwaccel: false, // Whether to use hardware acceleration
	//className: 'spinner', // The CSS class to assign to the spinner
	zIndex: 2e9, // The z-index (defaults to 2000000000)
	top: 'auto', // Top position relative to parent in px
	left: 'auto' // Left position relative to parent in px
};
var target = document.getElementById('directory-main-bar');
//var spinner = new Spinner(opts).spin(target);
//var spinnerDiv = mapDiv.find('.spinner');

var search = jQuery('#directory-search'),
	searchInput = jQuery('#dir-searchinput-text'),
	categoryInput = jQuery('#dir-searchinput-category'),
	locationInput = jQuery('#dir-searchinput-location'),
	geoInput = jQuery('#dir-searchinput-geo'),
	geoRadiusInput = jQuery('#dir-searchinput-geo-radius');

// find location before submit form for classic search
jQuery('#dir-search-form').submit(function(event) {
	"use strict";
	if(geoInput.is(':checked')){
		mapDiv.gmap3({
			getgeoloc:{
				callback : function(latLng){
					if (latLng){
						jQuery('#dir-searchinput-geo-lat').val(latLng.lat());
						jQuery('#dir-searchinput-geo-lng').val(latLng.lng());
					}
					jQuery('#dir-search-form').submit();
				}
			}
		});
		if(!event.hasOwnProperty('isTrigger')) {
			return false;
		}
	}
});

// set interactive search
if(search.data('interactive') == 'yes'){
	"use strict";
	searchInput.typeWatch({
		callback: function() {
			ajaxGetMarkers(true,false);
		},
		wait: 500,
		highlight: false,
		captureLength: 0
	});

	categoryInput.on( "autocompleteselect", function( event, ui ) {
		ajaxGetMarkers(true,false,ui.item.value,false);
	});
	locationInput.on( "autocompleteselect", function( event, ui ) {
		ajaxGetMarkers(true,false,false,ui.item.value);
	});
	categoryInput.on( "autocompleteclose", function( event, ui ) {
		if(jQuery('#dir-searchinput-category-id').val() == '0'){
			ajaxGetMarkers(true,false);
		}
	});
	locationInput.on( "autocompleteclose", function( event, ui ) {
		if(jQuery('#dir-searchinput-location-id').val() == '0'){
			ajaxGetMarkers(true,false);
		}
	});
}

// ajax geolocation
jQuery('#dir-searchinput-geo').FancyCheckbox().bind("afterChangeIphone",function(event) {
	"use strict";
	if(jQuery(this).is(':checked')){
		mapDiv.gmap3({
			getgeoloc:{
				callback : function(latLng){
					if (latLng){
						jQuery('#dir-searchinput-geo-lat').val(latLng.lat());
						jQuery('#dir-searchinput-geo-lng').val(latLng.lng());
						if(search.data('interactive') == 'yes'){
							ajaxGetMarkers(true,false);
						}
					}
				}
			}
		});
	} else {
		if(search.data('interactive') == 'yes'){
			ajaxGetMarkers(true,false);
		}
	}
});

jQuery('#dir-searchinput-settings .icon').click(function() {
	"use strict";
	jQuery('#dir-search-advanced').toggle();
});

jQuery('#dir-search-advanced-close').click(function() {
	"use strict";
	jQuery('#dir-search-advanced').hide();
});

/* jQuery('#dir-search-advanced .value-slider').slider({
	value: jQuery('#dir-searchinput-geo-radius').val(),
	min: 5,
	max: 1000,
	step: 5,
	change: function( event, ui ) {
		if(search.data('interactive') == 'yes' && geoInput.is(':checked')){
			ajaxGetMarkers(true,false);
		}
	},
	slide: function( event, ui ) {
		jQuery( "#dir-searchinput-geo-radius" ).val( ui.value );
	}
}); */

function ajaxGetMarkers(ajax,geoloc,rewriteCategory,rewriteLocation,reset) {
	"use strict";
	//map.panTo(new google.maps.LatLng(0,0));
	var topPosition = mapDiv.height() / 2;
	//spinnerDiv.css('top',topPosition+'px').fadeIn();

	var radius = new Array();

	var category = 0;
	var location = 0;
	var search = '';

	if(ajax){
		if(rewriteCategory){
			category = rewriteCategory;
		} else {
			category = jQuery('#dir-searchinput-category-id').val();
		}
		if(rewriteLocation){
			location = rewriteLocation;
		} else {
			location = jQuery('#dir-searchinput-location-id').val();
		}
		search = jQuery('#dir-searchinput-text').val();

		var ajaxGeo = jQuery('#dir-searchinput-geo').attr("checked");

		if(ajaxGeo && !reset){
			var inputRadius = jQuery('#dir-searchinput-geo-radius').val();
			if(!isNaN(inputRadius)){
				radius.push(parseInt(inputRadius));
			} else {
				jQuery('#dir-searchinput-geo-radius').val(jQuery('#dir-searchinput-geo-radius').data('default-value'));
				radius.push(parseInt(jQuery('#dir-searchinput-geo-radius').data('default-value')));
			}
			radius.push(parseFloat(jQuery('#dir-searchinput-geo-lat').val()));
			radius.push(parseFloat(jQuery('#dir-searchinput-geo-lng').val()));
		}
	} else {
		if(reset){
			category = parseInt(mapDiv.data('category'));
			location = parseInt(mapDiv.data('location'));
			search = mapDiv.data('search');
		} else {
			if(geoloc){
				radius.push(parseInt());
				radius.push(geoloc.lat());
				radius.push(geoloc.lng());

				category = parseInt(mapDiv.data('category'));
				location = parseInt(mapDiv.data('location'));
				search = mapDiv.data('search');
			}
		}
	}
	// get items from ajax
	/*  */
}

function generateMarkers(dataRaw,geoloc,ajaxGeo) {
	"use strict";
	// clear map
	infobox.close();
	mapDiv.gmap3({ clear: { } });

	map.setZoom(10);

	var len = $.map(dataRaw, function(n, i) { return i; }).length;

	var i = 0;
	// prepare data
	var data = new Array();
    for(var key in dataRaw){

    	var rating = '';
		if(dataRaw[key].rating){
			rating += '<div class="rating">';
			for (var j = 1; j <= dataRaw[key].rating['max']; j++) {
				rating += '<div class="star';
				if(j <= dataRaw[key].rating['val']) {
					rating += ' active';
				}
				rating += '"></div>';
			}
			rating += '</div>';
		}

    	var thumbCode = (dataRaw[key].timthumbUrl) ? ' with-image"><img src="'+dataRaw[key].timthumbUrl+'" alt="" />' : '">';
    	data[i] = {
			latLng: [dataRaw[key].optionsDir['gpsLatitude'],dataRaw[key].optionsDir['gpsLongitude']],
			options: {
				icon: dataRaw[key].marker,
				shadow: "img/map-icon/icon-shadow.png",
			},
			data: '<div class="marker-holder"><div class="marker-content'+thumbCode+'<div class="map-item-info"><div class="title">'+dataRaw[key].post_title+'</div>'+rating+'<div class="address">'+dataRaw[key].optionsDir["address"]+'</div><a href="'+dataRaw[key].link+'" class="more-button">VIEW MORE</a></div><div class="arrow"></div><div class="close"></div></div></div></div>'
		};
		i++;
    }

    // show geoloc marker
    if(geoloc){
    	mapDiv.gmap3({
			marker: {
    			latLng: geoloc,
    			options: {
    				animation: google.maps.Animation.DROP,
    				zIndex: 1000,
    				icon: "img/geolocation-pin.png"
    			}
    		}
    	});
	}

	// generate markers in map
	var mapObj = {
		marker: {
			values: data,
			options:{
				draggable: false
			},
			cluster:{
          		radius: 20,
				// This style will be used for clusters with more than 0 markers
				0: {
					content: "<div class='cluster cluster-1'>CLUSTER_COUNT</div>",
					width: 90,
					height: 80
				},
				// This style will be used for clusters with more than 20 markers
				20: {
					content: "<div class='cluster cluster-2'>CLUSTER_COUNT</div>",
					width: 90,
					height: 80
				},
				// This style will be used for clusters with more than 50 markers
				50: {
					content: "<div class='cluster cluster-3'>CLUSTER_COUNT</div>",
					width: 90,
					height: 80
				},
				events: {
					click: function(cluster) {
						map.panTo(cluster.main.getPosition());
						map.setZoom(map.getZoom() + 2);
					}
				}
          	},
			events: {
				click: function(marker, event, context){
					map.panTo(marker.getPosition());

					infobox.setContent(context.data);
					infobox.open(map,marker);

					// if map is small
					var iWidth = 260;
					var iHeight = 300;
					if((mapDiv.width() / 2) < iWidth ){
						var offsetX = iWidth - (mapDiv.width() / 2);
						map.panBy(offsetX,0);
					}
					if((mapDiv.height() / 2) < iHeight ){
						var offsetY = -(iHeight - (mapDiv.height() / 2));
						map.panBy(0,offsetY);
					}

				}
			}
		}
	};

	if(geoloc){
		if(ajaxGeo){
			var inputRadius = jQuery('#dir-searchinput-geo-radius').val();
			if(!isNaN(inputRadius)){
				var radiusInM = parseInt(jQuery('#dir-searchinput-geo-radius').val()) * 1000;
			} else {
				var radiusInM = parseInt(jQuery('#dir-searchinput-geo-radius').data('default-value')) * 1000;
			}
			// autofit by circle
			mapObj.circle = {
				options: {
					center: geoloc,
					radius : radiusInM,
					visible : true,
					fillOpacity : 0.15,
					fillColor : "#2c82be",
					strokeColor : "#2c82be"
				}
			}
		} else {
			var radiusInM = parseInt() * 1000;
			// autofit by circle
			mapObj.circle = {
				options: {
					center: geoloc,
					radius : radiusInM,
					visible : false,
					fillOpacity : 0.15,
					fillColor : "#2c82be",
					strokeColor : "#2c82be"
				}
			}
		}
	}

	/*spinner*/Div.fadeOut();

	mapDiv.gmap3( mapObj, "autofit" );

	if(len == 1 && !geoloc){
		map.setZoom(10);
	}

}

function generateOnlyGeo(lat,lng,radius) {
	"use strict";
	var geoloc = new google.maps.LatLng(parseFloat(lat),parseFloat(lng));
	// generate geo pin
	mapDiv.gmap3({
		marker: {
			latLng: geoloc,
			options: {
				animation: google.maps.Animation.DROP,
				zIndex: 1000,
				icon: "img/geolocation-pin.png"
			}
		}
	});
	// generate and autofit by circle
	var mapObj = {};
	var radiusInM = parseInt(radius) * 1000;
	// autofit by circle
	mapObj.circle = {
		options: {
			center: geoloc,
			radius : radiusInM,
			visible : true,
			fillOpacity : 0.15,
			fillColor : "#2c82be",
			strokeColor : "#2c82be"
		}
	}
	mapDiv.gmap3( mapObj, "autofit" );
}

var contentDiv = jQuery('#main #content');
var currContent = contentDiv.html();
var ajaxContent;

function generateContent(data) {
	"use strict";
	var length = $.map(data, function(n, i) { return i; }).length;

	contentDiv.find('.ajax-content').remove();
	var title;
	if(length == 0){
		title = jQuery('<header class="entry-header"><h1 class="entry-title"><span>No result found</span></h1></header>');
	} else {
		title = jQuery('<header class="entry-header"><h1 class="entry-title"><span>Search result</span></h1></header>');
	}

	var html;
	if(length > 0){
		html = jQuery('<ul class="items"></ul>');
	}
	var limit = 30;
	if(limit > length) {
		limit = length;
	}
	var i = 0;
	for(var key in data){
		var thumbnailHtml;
		if(data[key].timthumbUrlContent){
			var thumbnailHtml = '<div class="thumbnail"><img src="'+data[key].timthumbUrlContent+'" alt="Item thumbnail" /><div class="comment-count">'+data[key].comment_count+'</div></div>';
		} else {
			thumbnailHtml = '';
		}
		var rating = '';
		if(data[key].rating){
			rating += '<span class="rating">';
			for (var i = 1; i <= data[key].rating['max']; i++) {
				rating += '<span class="star';
				if(i <= data[key].rating['val']) {
					rating += ' active';
				}
				rating += '"></span>';
			}
			rating += '</span>';
		}
		var descriptionHtml = '<div class="description"><h3><a href="'+data[key].link+'">'+data[key].post_title+'</a>'+rating+'</h3>'+data[key].excerptDir+'</div>';
		html.append('<li class="item clear">'+thumbnailHtml+descriptionHtml+'</li>');
		if(i <= limit){
			i++;
		} else {
			break;
		}
	};
	ajaxContent = jQuery('<div class="ajax-content"></div>').html(title).append(html);
	contentDiv.find('>').hide();
	contentDiv.append(ajaxContent);
}

// reset search ajax values
jQuery('#directory-search .reset-ajax').click(function () {
	"use strict";
	// get default values
	ajaxGetMarkers(false,false,false,false,true);

	contentDiv.find('.ajax-content').remove();
	contentDiv.find('>').show();

	jQuery('#dir-searchinput-text').val("");
	// for IE
	jQuery('span.for-dir-searchinput-text label').show();

	jQuery('#dir-searchinput-location').val("");
	jQuery('#dir-searchinput-location-id').val("0");
	// for IE
	jQuery('span.for-dir-searchinput-category label').show();

	jQuery('#dir-searchinput-category').val("");
	jQuery('#dir-searchinput-category-id').val("0");
	// for IE
	jQuery('span.for-dir-searchinput-location label').show();

	jQuery('#dir-searchinput-geo').attr('checked',false);
	jQuery('div.iphone-style[rel=dir-searchinput-geo]').removeClass('on').addClass('off');

	//jQuery('#dir-searchinput-geo-radius').val(jQuery('#dir-searchinput-geo-radius').data('default-value'));

	// hide close icon
	jQuery(this).hide();
});

});