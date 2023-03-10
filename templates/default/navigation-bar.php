<header id="branding" role="banner">
    <div class="defaultContentWidth clearfix">
        <div id="logo" class="left">
            <a class="trademark" href="<?php echo Website::getDefaultPage() ?>">
<?php
            if(!empty($logo)){
                echo '<img src="images/modules/directory/siteinfo/'.CHtml::encode($logo).'" alt="logo">';
            }else{
                echo '<img src="templates/default/img/logo.png" alt="logo">';
            }
?>

            </a>
        </div>

        <nav id="access" role="navigation">
            <h3 class="assistive-text">Main menu</h3>
            <nav class="mainmenu">
                <?php echo FrontendMenu::draw(
                        'top',
                        $this->_activeMenu,
                        array('menuClass'=>'nav', 'subMenuClass'=>'dropdown-menu', 'dropdownItemClass'=>'dropdown')
                    );
                ?>
                <!--ul id="menu-main-menu" class="menu">
                    <li class="current-menu-item ">
                        <a href="index.html">Homepage</a>
                        <ul class="sub-menu">
                            <li><a href="#">Example Item 1</a></li>
                            <li><a href="#">Example Item 2</a></li>
                            <li><a href="#">Example Item 3</a>
                                <ul class="sub-menu">
                                    <li><a href="#">Example Item 4</a></li>
                                    <li><a href="#">Example Item 5</a></li>
                                    <li><a href="#">Example Item 6</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a href="directory-category-detail.html">Category Detail</a></li>
                    <li><a href="catalog-item.html">Catalog Item</a></li>
                    <li><a href="blog.html">Blog</a></li>
                    <li><a href="blog-post.html">Blog Post</a></li>
                </ul-->
            </nav>
        </nav><!-- #accs -->

    </div>
</header><!-- #branding -->
