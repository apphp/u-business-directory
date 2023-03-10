<div id="directory-search" data-interactive="yes">
	<div class="defaultContentWidth clearfix">
		<form action="directoryListing/searchListing" id="dir-search-form" class="dir-searchform" enctype='get'>
			<div id="dir-search-inputs">
				<div id="dir-holder">
					<div class="dir-holder-wrap">
						<input type="text" name="s" id="dir-searchinput-text" placeholder="Search keyword..." class="dir-searchinput">

						<div id="dir-searchinput-settings" class="dir-searchinput-settings">
							<div class="icon"></div>
							<div id="dir-search-advanced">
								<div class="text">Search around my position</div>
								<div class="text-geo-radius clear">
									<div class="geo-radius">Radius:</div>
									<div class="metric">km</div>
									<input type="text" name="geo-radius" id="dir-searchinput-geo-radius" value="100" data-default-value="100">
								</div>
								<div class="geo-slider">
									<div class="value-slider"></div>
								</div>
								<div class="geo-button">
									<input type="checkbox" name="geo" id="dir-searchinput-geo">
								</div>
								<div id="dir-search-advanced-close"></div>
							</div>
						</div>
						<input type="hidden" name="geo-lat" id="dir-searchinput-geo-lat" value="">
						<input type="hidden" name="geo-lng" id="dir-searchinput-geo-lng" value="">

						<input type="text" id="dir-searchinput-category" placeholder="All categories">
						<input type="text" name="categories" id="dir-searchinput-category-id" value="0">

						<input type="text" id="dir-searchinput-location" placeholder="All locations">
						<input type="text" name="locations" id="dir-searchinput-location-id" value="0">

						<div class="reset-ajax"></div>
					</div>
				</div>
			</div>

			<div id="dir-search-button">
				<input type="submit" value="Search" class="dir-searchsubmit">
			</div>
			<input type="hidden" name="act" value="send">
		</form>
	</div>
</div><!-- end directory-search -->
