<footer id="colophon" role="contentinfo">
    <div id="supplementary" class="widgets defaultContentWidth">
        <div id="footer-widgets" class="widget-area" role="complementary">
            <?php echo FrontendMenu::draw('bottom', $this->_activeMenu); ?>

            <div id="text-6" class="box widget-container widget_text">
<?php
                if(!empty($customTextName) || !empty($customTextDescription)){
?>
                <div class="box-wrapper">
                    <div class="title-border-bottom">
                        <div class="title-border-top">
                            <div class="title-decoration"></div>
                            <h2 class="widget-title"><?php echo $customTextName; ?></h2>
                        </div>
                    </div>

                    <div class="textwidget">
                    <?php echo $customTextDescription; ?>
                    </div>
                </div>
<?php
                }

                if(Modules::model()->exists("code = 'directory' AND is_installed = 1")){
                    echo DirectoryComponent::drawStatisticsBlock();
                }
?>
            </div>
        </div>

        <?php
            $uri = CUri::init()->uriString();
            $defaultPage   = Website::getDefaultPage();
            $newsPage      = 'news/viewAll';
            $contactUsPage = Website::prepareLinkByFormat('cms', 'page_link_format', 2, A::t('directory', 'Contact Us'));
            $rssListings   = 'feeds/listings_rss.xml';
            $rssNews       = 'feeds/news_rss.xml';
        ?>

        <div id="site-generator" class="clearfix">
            <div class="defaultContentWidth">
                <div id="footer-text">
                    <?php echo $this->siteFooter; ?>
                </div>
                <nav class="footer-menu">
                    <ul id="menu-footer-menu" class="menu">
                        <?php if(Modules::model()->exists("code = 'news' AND is_installed = 1") && is_file(APPHP_PATH.'/feeds/news_rss.xml')){ ?>
                            <li class="<?php echo ((strtolower($uri) == strtolower($rssNews)) ? 'current-menu-item' : ''); ?>"><a href="<?php echo $rssNews; ?>"><img src="templates/default/img/rss-icon-blue.png" title="<?php echo A::te('news', 'Rss feed news'); ?>" /></a></li>
                        <?php } ?>
                        <?php if(is_file(APPHP_PATH.'/feeds/listings_rss.xml')){ ?>
                            <li class="<?php echo ((strtolower($uri) == strtolower($rssListings)) ? 'current-menu-item' : ''); ?>"><a href="<?php echo $rssListings; ?>"><img src="templates/default/img/rss-icon.png" title="<?php echo A::te('directory', 'Rss feed listings'); ?>" /></a></li>
                        <?php } ?>
                        <li class="<?php echo ((strtolower($uri) == strtolower($defaultPage) || $uri == '/' || empty($uri)) ? 'current-menu-item' : ''); ?>"><a href="<?php echo $defaultPage; ?>"><?php echo A::t('directory', 'Home'); ?></a></li>
                        <?php if(Modules::model()->exists("code = 'news' AND is_installed = 1")){ ?>
                            <li class="<?php echo ((strtolower($uri) == strtolower($newsPage)) ? 'current-menu-item' : ''); ?>"><a href="<?php echo $newsPage; ?>"><?php echo A::t('news', 'News'); ?></a></li>
                        <?php } ?>
                        <li class="<?php echo ((strtolower($uri) == strtolower($contactUsPage)) ? 'current-menu-item' : ''); ?>"><a href="<?php echo $contactUsPage; ?>"><?php echo A::t('directory', 'Contact Us'); ?></a></li>
                    </ul>
                </nav>
            </div>
        </div>
   </div>
</footer>
