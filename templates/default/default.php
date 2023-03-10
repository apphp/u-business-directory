<?php header('content-type: text/html; charset=utf-8'); ?>
<?php
    if(Modules::model()->exists("code = 'directory' AND is_installed = 1")){
        $socialNetworks = SocialNetworks::model()->findAll('site_id = :site_id AND is_published = 1', array(':site_id'=>1));

        $siteInfo = SiteInfoFrontend::model()->findByPk(1);
        $logo                  = !empty($siteInfo) ? $siteInfo->logo : 'no_logo.png';
        $favicon               = !empty($siteInfo) ? $siteInfo->favicon : '';
        $phone                 = !empty($siteInfo) ? $siteInfo->phone : '';
        $phoneVisible          = !empty($siteInfo) ? $siteInfo->phone_visible : 0;
        $email                 = !empty($siteInfo) ? $siteInfo->email : '';
        $emailVisible          = !empty($siteInfo) ? $siteInfo->email_visible : 0;
        $customTextName        = !empty($siteInfo) ? $siteInfo->custom_text_name : '';
        $customTextDescription = !empty($siteInfo) ? $siteInfo->custom_text_description : '';
    }else{
        $logo                  = 'no_logo.png';
        $favicon               = '';
        $phone                 = '';
        $phoneVisible          = 0;
        $email                 = '';
        $emailVisible          = 0;
        $customTextName        = '';
        $customTextDescription = '';
    }

    $configModule = CLoader::config('directory', 'main');
    $googleApiKey = (isset($configModule['googleApiKey'])) ? $configModule['googleApiKey'] : '';
?>
<!doctype html>
<!--[if IE 8]><html class="no-js oldie ie8 ie" lang="en-US"><![endif]-->
<!--[if gte IE 9]><!--><html class="no-js" lang="en-US"><!--<![endif]-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="keywords" content="<?php echo CHtml::encode($this->_pageKeywords); ?>" />
    <meta name="description" content="<?php echo CHtml::encode($this->_pageDescription); ?>" />
    <meta name="generator" content="<?php echo CConfig::get('name').' v'.CConfig::get('version'); ?>" />
    <!-- don't move it -->
    <base href="<?php echo A::app()->getRequest()->getBaseUrl(); ?>" />
    <title><?php echo CHtml::encode($this->_pageTitle); ?></title>
<?php
    if(!empty($favicon)){
        echo '<link rel="shortcut icon" href="images/modules/directory/siteinfo/'.$favicon.'" />';
    }else{
        echo '<link rel="shortcut icon" href="images/apphp.ico" />';
    }
?>
    <?php echo CHtml::cssFile('templates/default/css/style.css'); ?>
    <?php echo CHtml::cssFile('templates/default/css/custom.css'); ?>
    <?php //if(A::app()->getLanguage('direction') == 'rtl') echo CHtml::cssFile('templates/default/css/style.rtl.css'); ?>
    <?php echo CHtml::cssFile('templates/default/css/jquery-ui-1.10.1.custom.min.css'); ?>

<?php echo CHtml::scriptFile('templates/default/js/jquery.js'); ?>

    <!-- template files -->
<?php echo CHtml::scriptFile('templates/default/js/main.js'); ?>
</head>
<body id="frontend" class="frontend">
<header class='site-header'>
    <?php
        if(CAuth::isLoggedInAsAdmin()){
            echo CHtml::link(A::t('app', 'Back to Admin Panel'), 'backend/index', array('class'=>'back-to'));
        }
    ?>
</header>
<div id="page">

    <?php include('top-bar.php'); ?>

    <?php include('navigation-bar.php'); ?>

    <?php include('map-bar.php'); ?>

    <?php
        //include('search-bar.php');
        if(Modules::model()->exists("code = 'directory' AND is_installed = 1")){
            DirectoryComponent::searchListings();
        }
    ?>


    <div id="main" class="defaultContentWidth">
        <div id="wrapper-row">
            <div id='content' role='main'>
                <?php echo A::app()->view->getContent(); ?>
            </div>

            <div id="secondary" class="widget-area" role="complementary">
                <?php echo FrontendMenu::draw('right', $this->_activeMenu); ?>
                <?php
                    if(Modules::model()->exists("code = 'banners' AND is_installed = 1")){
                ?>
                <aside id="text-9" class="widget widget_text">
                    <h3 class="widget-title"><span><?php echo A::t('directory', 'Advertisements'); ?></span></h3>
                    <div class="textwidget">
                        <?php echo BannersComponent::getBanner(2); ?>
                        <?php echo BannersComponent::getBanner(3); ?>
                        <?php echo BannersComponent::getBanner(4); ?>
                        <?php echo BannersComponent::getBanner(5); ?>
                    </div>
                </aside>
                <?php } ?>

                <aside id="search-3" class="widget widget_search">
                    <?php echo SearchForm::draw(); ?>
                </aside>

            </div>

        </div>
    </div> <!-- /#main -->

    <?php include('footer.php'); ?>


</div><!-- #page -->

<?php echo CHtml::scriptFile('templates/default/js/jquery-migrate.min.js'); ?>
<?php echo CHtml::scriptFile('templates/default/js/jquery.html5-placeholder-shim.js'); ?>
<script type='text/javascript' src='//maps.google.com/maps/api/js?sensor=false&language=en&ver=3.8.3&key=<?php echo $googleApiKey; ?>'></script>
<?php echo CHtml::scriptFile('templates/default/js/jquery.fancycheckbox.min.js'); ?>
<?php echo CHtml::scriptFile('templates/default/plugins/prettyphoto/jquery.prettyPhoto.js'); ?>
<?php echo CHtml::scriptFile('templates/default/js/gmap3.infobox.js'); ?>
<?php echo CHtml::scriptFile('templates/default/js/gmap3.min.js'); ?>
<?php echo CHtml::scriptFile('templates/default/js/jquery.easing-1.3.min.js'); ?>
<?php echo CHtml::scriptFile('templates/default/js/jquery.nicescroll.min.js'); ?>
<?php echo CHtml::scriptFile('templates/default/js/jquery.finishedTyping.js'); ?>
<?php echo CHtml::scriptFile('templates/default/js/spin.min.js'); ?>
<?php echo CHtml::scriptFile('templates/default/js/modernizr.touch.js'); ?>
<?php echo CHtml::scriptFile('templates/default/js/rating.js'); ?>
<?php echo CHtml::scriptFile('templates/default/js/script.js'); ?>
<?php echo CHtml::scriptFile('templates/default/js/scripts-2.js'); ?>
<?php echo CHtml::scriptFile('templates/default/js/jquery-ui/jquery.ui.core.min.js'); ?>
<?php echo CHtml::scriptFile('templates/default/js/jquery-ui/jquery.ui.widget.min.js'); ?>
<?php echo CHtml::scriptFile('templates/default/js/jquery-ui/jquery.ui.tabs.min.js'); ?>
<?php echo CHtml::scriptFile('templates/default/js/jquery-ui/jquery.ui.position.min.js'); ?>
<?php echo CHtml::scriptFile('templates/default/js/jquery-ui/jquery.ui.menu.min.js'); ?>
<?php echo CHtml::scriptFile('templates/default/js/jquery-ui/jquery.ui.autocomplete.min.js'); ?>
<?php echo CHtml::scriptFile('templates/default/js/jquery-ui/jquery.ui.mouse.min.js'); ?>

</body>
</html>