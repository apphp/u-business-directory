<?php
    $this->_pageTitle = A::t('directory', 'Dashboard');
    $this->_activeMenu = 'customers/dashboard';
?>
<article id="page-dashboard" class="page-dashboard page-customers type-page status-publish hentry">
    <header class="entry-header">
        <h1 class="entry-title">
            <span><?php echo A::t('directory', 'Dashboard'); ?></span>
        </h1>
        <?php
            $breadCrumbLinks = array(array('label'=> A::t('directory', 'Home'), 'url'=>Website::getDefaultPage()));
            $breadCrumbLinks[] = array('label'=>A::t('directory', 'Dashboard'));
            CWidget::create('CBreadCrumbs', array(
                'links' => $breadCrumbLinks,
                'wrapperClass' => 'category-breadcrumb clearfix',
                'linkWrapperTag' => 'span',
                'separator' => '&nbsp;/&nbsp;',
                'return' => false
            ));
        ?>
    </header>
    <div class="block-body">
        <fieldset>
            <legend><?php echo A::t('directory', 'Hi').', '.CAuth::getLoggedName(); ?></legend>
            <p><?php echo A::t('directory', 'Welcome to the Customer Dashboard!'); ?></p>
            <br>
            <ul class="dashboard-links">
                <li><a href="customers/dashboard"><?php echo A::t('directory', 'Dashboard'); ?></a><br></li>
                <li><a href="customers/myAccount"><?php echo A::t('directory', 'My Account'); ?></a></li>
                <li><a href="listings/myListings"><?php echo A::t('directory', 'My Listings'); ?></a></li>
                <li><a href="orders/myOrders"><?php echo A::t('directory', 'My Orders'); ?></a></li>
                <li><a href="inquiries/myInquiries"><?php echo A::t('directory', 'Inquiries'); ?></a></li>
                <li><a href="customers/logout"><?php echo A::t('directory', 'Logout'); ?></a></li>
            </ul>
        </fieldset>
    </div>
    <div class="cb"></div>
</article>
