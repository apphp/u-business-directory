<?php
    $this->_pageTitle = A::t('directory', 'Send Inquiry');
    A::app()->getClientScript()->registerScriptFile('js/modules/directory/directory.js', 0);
?>
<article id="page-edit-listing" class="page-edit-listing type-page status-publish hentry">
<?php if(!$showWidget){ ?>
    <header class="entry-header">
        <h1 class="entry-title">
            <span><?php echo A::t('directory', 'Send Inquiry'); ?></span>
        </h1>
        <?php
            $breadCrumbLinks = array(array('label'=> A::t('directory', 'Home'), 'url'=>Website::getDefaultPage()));
            $breadCrumbLinks[] = array('label' => A::t('directory', 'Send Inquiry'));
            CWidget::create('CBreadCrumbs', array(
                'links' => $breadCrumbLinks,
                'wrapperClass' => 'category-breadcrumb clearfix',
                'linkWrapperTag' => 'span',
                'separator' => '&nbsp;/&nbsp;',
                'return' => false
            ));
        ?>
    </header>
<?php } ?>
    <div class="block-body">
    <?php
        echo DirectoryComponent::inquiriesBlock();
        echo $actionMessage;
    ?>
    </div>
</article>

