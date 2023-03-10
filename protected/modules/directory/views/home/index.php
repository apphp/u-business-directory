<?php

    A::app()->getClientScript()->registerCss(1, $contentCss);

    if($markers){
        A::app()->getClientScript()->registerScript('mapMarkers', $markers, 2);
        A::app()->getClientScript()->registerCss('backgroundMap', "#directory-main-bar { background: url('templates/default/img/photo/bd2.png') no-repeat center top !important; height: 500px !important; }");
    }
    A::app()->getClientScript()->registerCssFile('templates/default/vendors/slick/slick.css');
    A::app()->getClientScript()->registerScriptFile('templates/default/vendors/slick/slick.min.js', 2);

?>
<article id="page-home" class="home-home type-page status-publish hentry">
    <header class="entry-header">
        <h1 class="entry-title">
            <a htef=''><?php echo A::t('directory', 'Company Directory') ?></a>
        </h1>
    </header>
    <?php echo DirectoryComponent::inquiriesBlock(); ?>
    <div class="entry-content">
        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.</p>
    </div>
    <?php echo $contentHtml ?>
    <?php
        if(Modules::model()->exists("code = 'banners' AND is_installed = 1")){
    ?>
    <div class="alternative-content">
        <?php echo BannersComponent::getBanner(1); ?>
    </div>
    <?php } ?>
    <?php
        // TODO: Add Slick plugin
        if(!empty($lastListings)){
    ?>
        <h3 class="widget-title"><span><?php echo A::t('directory', 'Latest published listings'); ?></span></h3>
        <div class="last-listings-slider">
        <?php foreach($lastListings as $listing){ ?>
            <div>
                <a href="listings/view/id/<?php echo (int)$listing['id']; ?>>" title="<?php echo CHtml::encode($listing['business_name']); ?>">
                    <img class="listing-logo thumb" src="images/modules/directory/listings/thumbs/<?php echo CHtml::encode($listing['image_file_thumb']); ?>" alt="<?php echo CHtml::encode($listing['business_name']); ?>">
                </a>
                <h3 class="listing-name"><a href="listings/view/id/<?php echo (int)$listing['id']; ?>"><?php echo CString::substr($listing['business_name'], 35, false, true); ?></a></h3>
                <div class="short-content"><?php echo CString::substr($listing['business_description'], 100, false, true); ?></div>
                <div class="clearfix"></div>
                <a class="button morebutton" href="listings/view/id/<?php echo CHtml::encode($listing['id']); ?>" title="<?php echo CHtml::encode($listing['business_name']); ?>"><?php echo A::t('directory', 'More'); ?></a>
            </div>
        <?php } ?>
        </div>
    <?php
        }
    ?>
</article>
<?php
    A::app()->getClientScript()->registerScript(
        'directorySlider',
        'jQuery(document).ready(function(){
            jQuery(".last-listings-slider").slick({
                accessibility : false,
                dots: false,
                arrows: false,
                infinite: false,
                speed: 600,
                slidesToShow: 4,
                slidesToScroll: 2,
                autoplay: true,
                autoplaySpeed: 5000,
                responsive: [
                    {
                      breakpoint: 768,
                      settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                      }
                    },
                    {
                      breakpoint: 480,
                      settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                      }
                    }
                    // You can unslick at a given breakpoint now by adding:
                    // settings: "unslick"
                    // instead of a settings object
                ]
            });
        });',
        3
    );
