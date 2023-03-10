<?php
    $this->_pageTitle = A::t('directory', 'Checkout');
    $this->_activeMenu = 'listings/myListings';
?>
<article id="checkout" class="checkout type-listing status-publish hentry">
    <header class="entry-header">
        <h1 class="entry-title">
            <span><?php echo A::t('directory', 'Checkout'); ?></span>
        </h1>
        <?php
        $breadCrumbLinks = array(array('label'=> A::t('directory', 'Home'), 'url'=>Website::getDefaultPage()));
        $breadCrumbLinks[] = array('label'=>A::t('directory', 'My Listings'), 'url'=>'listings/myListings/type/pending');
        $breadCrumbLinks[] = array('label' => A::t('directory', 'Checkout'));
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
        <div class='content'>
        <?php
            echo $actionMessage; 
        ?>
        </div>
    </div>
</article>
