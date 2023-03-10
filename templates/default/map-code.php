<script type="text/javascript">
  //'article aside footer header nav section time'.replace(/\w+/g,function(n){ document.createElement(n) })
</script>


<script type="text/javascript">
var mapDiv,
	map,
	infobox;
	
	jQuery(document).ready(function($) {
	"use strict";

	mapDiv = $("#directory-main-bar");
	mapDiv.height(500).gmap3({
		map: {
			options: {
				"draggable": true
				,"mapTypeControl": true
				,"mapTypeId": google.maps.MapTypeId.ROADMAP
				,"scrollwheel": false
				,"panControl": true
				,"rotateControl": false
				,"scaleControl": true
				,"streetViewControl": true
				,"zoomControl": true
			}
		}
		,marker: {
			values: [
					{
						latLng: [40.71958306715651,-74.0379810333252],
						options: {
							icon: "templates/default/img/map-icon/furniture.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/young-people-dancing-in-a-study.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"Dance & Relax"+'</div>'+
											'<div class="address">'+"276 Canal Street, New York, NY 10013, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.7053026089413,-73.95326614379883],
						options: {
							icon: "templates/default/img/map-icon/sports.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/all-metal.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"Bike PRO"+'</div>'+
											'<div class="address">'+"276 Canal Street, New York, NY 10013, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.71322392474486,-73.9909029006958],
						options: {
							icon: "templates/default/img/map-icon/sports.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/Golf-Swing-In-Riva-Dei-Tessali.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"Golf Game"+'</div>'+
											'<div class="address">'+"276 Canal Street, New York, NY 10013, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.71283357396648,-74.00152444839478],
						options: {
							icon: "templates/default/img/map-icon/food1.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/bigstock-Pepperoni-Pizza-Slice.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"Pizza House JARO"+'</div>'+
											'<div class="address">'+"66 Pearl Street, New York, NY 10000, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.703135062150984,-73.9937835931778],
						options: {
							icon: "templates/default/img/map-icon/travel1.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/bigstock-Tab-And-Travel.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"Age Travel"+'</div>'+
											'<div class="address">'+"66 Pearl Street, New York, NY 10000, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.70379794042075,-73.98971736431122],
						options: {
							icon: "templates/default/img/map-icon/pets.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/Ball-view-from-golf-course.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"School of Golf"+'</div>'+
											'<div class="address">'+"66 Pearl Street, New York, NY 10000, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.70849076720841,-74.01805758476257],
						options: {
							icon: "templates/default/img/map-icon/sports.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/bigstock-Ballroom-Dance.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"Step 2 Step"+'</div>'+
											'<div class="address">'+"66 Pearl Street, New York, NY 10000, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.71357361204046,-74.0397834777832],
						options: {
							icon: "templates/default/img/map-icon/music.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/bikes-parked.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"Ride Shop"+'</div>'+
											'<div class="address">'+"66 Pearl Street, New York, NY 10000, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.71309787422285,-74.03395771980286],
						options: {
							icon: "templates/default/img/map-icon/shopping2.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/bigstock-Shopping-online.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"KE Shop"+'</div>'+
											'<div class="address">'+"66 Pearl Street, New York, NY 10000, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.7149077945163,-74.00572210550308],
						options: {
							icon: "templates/default/img/map-icon/shopping2.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/Shopping-cart-Vector.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"X-Buy Shop"+'</div>'+
											'<div class="address">'+"66 Pearl Street, New York, NY 10000, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.703376017131866,-74.0079402923584],
						options: {
							icon: "templates/default/img/map-icon/tech.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/Internet.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"IT Solving PRO"+'</div>'+
											'<div class="address">'+"66 Pearl Street, New York, NY 10000, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.71006852376983,-74.01072978973389],
						options: {
							icon: "templates/default/img/map-icon/tech.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/repairman-working-with-compute.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"IT MAX"+'</div>'+
											'<div class="address">'+"66 Pearl Street, New York, NY 10000, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.71138600318452,-73.95681738853455],
						options: {
							icon: "templates/default/img/map-icon/medical.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/Male-Patient-And-Doctor-Talkin.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"Dr. Martinko"+'</div>'+
											'<div class="address">'+"66 Pearl Street, New York, NY 10000, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.715618839321955,-74.03407037258148],
						options: {
							icon: "templates/default/img/map-icon/food1.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/bigstock-Family-Eating-Lunch-Together.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"Art Restaurant"+'</div>'+
											'<div class="address">'+"66 Pearl Street, New York, NY 10000, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.70436727845332,-73.9669132232666],
						options: {
							icon: "templates/default/img/map-icon/food1.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/bigstock-Collage-of-pub-food-including.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"Corner Fast Food"+'</div>'+
											'<div class="address">'+"66 Pearl Street, New York, NY 10000, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.706549018067186,-74.0119394659996],
						options: {
							icon: "templates/default/img/map-icon/education1.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/bigstock-Multi-ethnic-Group-Of-Students.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"St. Patrick's School"+'</div>'+
											'<div class="address">'+"66 Pearl Street, New York, NY 10000, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.70513994371307,-73.96133422851562],
						options: {
							icon: "templates/default/img/map-icon/education1.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/bigstock-group-of-friends-or-college-st.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"Brooklyn Public School"+'</div>'+
											'<div class="address">'+"66 Pearl Street, New York, NY 10000, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.71288236793894,-73.96618366241455],
						options: {
							icon: "templates/default/img/map-icon/medical.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/bigstock-Businessman-with-Female-Doctor.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"Dr. Sabol"+'</div>'+
											'<div class="address">'+"66 Pearl Street, New York, NY 10000, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.72153460029748,-74.03381824493408],
						options: {
							icon: "templates/default/img/map-icon/medical.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/bigstock-Happy-Old-Man-Having-A-Casual.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"Dr. Martin"+'</div>'+
											'<div class="address">'+"33 Stanton Street, manhattan, NY 07302, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.716004880353196,-73.96316471664431],
						options: {
							icon: "templates/default/img/map-icon/travel1.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/bigstock-illustration-of-world-famous.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"Trip & Travel"+'</div>'+
											'<div class="address">'+"66 Pearl Street, New York, NY 10000, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.71916,-74.001295],
						options: {
							icon: "templates/default/img/map-icon/travel1.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/bigstock-Girl-in-the-airport.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"Tour 4 You"+'</div>'+
											'<div class="address">'+"276 Canal Street, New York, NY 10013, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.704928,-73.986336],
						options: {
							icon: "templates/default/img/map-icon/shopping2.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/bigstock-shopping-cart-icon.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"BB Shoping Center"+'</div>'+
											'<div class="address">'+"10 Jay Street #404, Brooklyn, NY 11201, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.711093232233985,-73.98279190063477],
						options: {
							icon: "templates/default/img/map-icon/tech.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/shopping-cart-over-a-laptop.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"IT Shop NYC"+'</div>'+
											'<div class="address">'+"23 Park Row, New York, NY 10038, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.713043,-74.005992],
						options: {
							icon: "templates/default/img/map-icon/education1.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/Multicultural-College-students.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"Pace Public School"+'</div>'+
											'<div class="address">'+"163 William Street #2 New York, NY 10038, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.712415,-73.960541],
						options: {
							icon: "templates/default/img/map-icon/company2.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/Business-Consultation.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"Business PRO"+'</div>'+
											'<div class="address">'+"749 Driggs Ave Brooklyn, NY 11211"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.706457520637585,-74.00463581085205],
						options: {
							icon: "templates/default/img/map-icon/company2.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/Business-Presentation.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"Pro Agent"+'</div>'+
											'<div class="address">'+"25 W 25th St 1204, New York, NY 10036 "+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.71408594127331,-73.97918701171875],
						options: {
							icon: "templates/default/img/map-icon/company2.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/Real-Estate-Agent-Consulting.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"AVA Consult Center"+'</div>'+
											'<div class="address">'+"71 5th Ave, New York, NY 10003"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.71746884168838,-73.97819995880127],
						options: {
							icon: "templates/default/img/map-icon/automotive1.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/New-Cars.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"Car PRO"+'</div>'+
											'<div class="address">'+"10 State Street Manhattan, NY 11201, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.71492355836883,-74.01167711917117],
						options: {
							icon: "templates/default/img/map-icon/automotive1.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/Cars.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"Car AVA"+'</div>'+
											'<div class="address">'+"86 Murray Street Street Manhattan, NY 11201, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.70310660279219,-73.98577770892337],
						options: {
							icon: "templates/default/img/map-icon/automotive1.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/Man-buying-a-car-in-dealership.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"WRC Cars"+'</div>'+
											'<div class="address">'+"10 Water Street Brooklyn, NY 11201, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.72106298503115,-74.04534101486206],
						options: {
							icon: "templates/default/img/map-icon/automotive1.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/Mechanic-with-clipboard-giving.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"Cypro Car Consult"+'</div>'+
											'<div class="address">'+"184 Bay Street Jersey City, NJ 07302, United States<br \/>\n"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.695542,-73.995262],
						options: {
							icon: "templates/default/img/map-icon/automotive1.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/Cars-Lot-For-Sale.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"Viktor's Cars"+'</div>'+
											'<div class="address">'+"107 Montague Street Brooklyn, NY 11201, United States"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				,
					{
						latLng: [40.71493168249224,-73.95352363586426],
						options: {
							icon: "templates/default/img/map-icon/automotive1.png",
							shadow: "templates/default/img/map-icon/icon-shadow.png",
						},
						data: 	'<div class="marker-holder">'+
									'<div class="marker-content with-image"><img src="templates/default/img/map-photo/Business-man-working-at-a-car.jpg" alt="">'+
										'<div class="map-item-info">'+
											'<div class="title">'+"LLA - Car"+'</div>'+
											'<div class="address">'+"330 Adams Street Brooklyn, NY 11201, United States<br \/>\n"+'</div>'+
											'<div data-link="catalog-item.html" class="more-button">' + "VIEW MORE" + '</div>'+
											'</div><div class="arrow"></div><div class="close"></div>'+
										'</div>'+
									'</div>'+
								'</div>'
					}
				
			],
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
		 		 	},"autofit");

	map = mapDiv.gmap3("get");
	infobox = new InfoBox({
		pixelOffset: new google.maps.Size(-50, -65),
		closeBoxURL: '',
		enableEventPropagation: true
	});
	mapDiv.delegate('.infoBox .close','click',function () {
		infobox.close();
	});
	// hotfix for chrome on android
	mapDiv.delegate('.infoBox div.more-button', 'click', function() {
		window.location = $(this).data('link');
	});

	if (Modernizr.touch){
		map.setOptions({ draggable : false });
		var draggableClass = 'inactive';
		var draggableTitle = "Activate map";
		var draggableButton = $('<div class="draggable-toggle-button '+draggableClass+'">'+draggableTitle+'</div>').appendTo(mapDiv);
		draggableButton.click(function () {
			if($(this).hasClass('active')){
				$(this).removeClass('active').addClass('inactive').text("Activate map");
				map.setOptions({ draggable : false });
			} else {
				$(this).removeClass('inactive').addClass('active').text("Deactivate map");
				map.setOptions({ draggable : true });
			}
		});
	}

});
</script>