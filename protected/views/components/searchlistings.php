<div id="directory-search" data-interactive="yes">
    <div class="defaultContentWidth clearfix">
        <form action="listings/searchListings" id="dir-search-form" class="dir-searchform" enctype='<?php echo $getType?>'>
            <div id="dir-search-inputs">
                <div id="dir-holder">
                    <div class="dir-holder-wrap">
                        <input type="text" name="s" id="dir-searchinput-text" placeholder="<?php echo A::t('directory', 'Search keyword...'); ?>" class="dir-searchinput" value="<?php echo $dataSearch ?>">
                    <?php
                        if($searchPosition){
                    ?>
                        <div id="dir-searchinput-settings" class="dir-searchinput-settings">
                            <div class="icon"></div>
                            <div id="dir-search-advanced">
                                <div class="text"><?php echo A::t('directory', 'Search around my position'); ?></div>
                                <div class="text-geo-radius clear">
                                    <div class="geo-radius"><?php echo A::t('directory', 'Radius')?>:</div>
                                    <div class="metric"><?php echo A::t('directory', 'km')?></div>
                                    <input type="text" name="geo-radius" id="dir-searchinput-geo-radius" value="<?php echo $dataRadius; ?>" data-default-value="100">
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
                        <input type="hidden" name="latitude" id="dir-searchinput-geo-lat" value="<?php echo $dataLatitude; ?>">
                        <input type="hidden" name="longitude" id="dir-searchinput-geo-lng" value="<?php echo $dataLongitude; ?>">
                    <?php
                        }
                    ?>

                        <input type="text" id="dir-searchinput-category" placeholder="<?php echo A::t('directory', 'All categories'); ?>" value="<?php echo $dataCategory; ?>">
                        <input type="text" name="categories" id="dir-searchinput-category-id" value="<?php echo $dataCategoryId; ?>">

                        <input type="text" id="dir-searchinput-location" placeholder="<?php echo A::t('directory', 'All locations'); ?>" value="<?php echo $dataRegion; ?>">
                        <input type="text" name="locations" id="dir-searchinput-location-id" value="<?php echo $dataRegionId; ?>">

                        <div class="reset-ajax"></div>
                    </div>
                </div>
            </div>

            <div id="dir-search-button">
                <input type="submit" value="<?php echo A::t('directory', 'Search'); ?>" class="dir-searchsubmit">
            </div>
            <input type="hidden" name="act" value="send">
        </form>
    </div>
</div>
