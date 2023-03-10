<?php
    $this->_pageTitle = $newsHeader;
    A::app()->getClientScript()->registerCssFile('css/modules/news/news.css');
?>
<article id="news-<?php echo CHtml::encode($id); ?>" class="news-<?php echo CHtml::encode($id); ?> type-news status-publish hentry">
    <header class="entry-header">
        <h1 class="entry-title">
            <span><?php echo $newsHeader; ?></span>
        </h1>
        <?php
        $breadCrumbLinks = array(
            array('label'=>A::t('news', 'Home'), 'url'=>Website::getDefaultPage()),
            array('label'=>A::t('news', 'All News'), 'url'=>'news/viewAll'),
            array('label'=>$newsHeader)
        );
        CWidget::create('CBreadCrumbs', array(
            'links' => $breadCrumbLinks,
            'wrapperClass' => 'news-breadcrumb clearfix',
            'linkWrapperTag' => 'span',
            'separator' => '&nbsp;/&nbsp;',
            'return' => false
        ));
        ?>
    </header>
    <?php // echo $actionMessage; ?>
    <?php echo !empty($introImage) ? '<div class="entry-thumbnail"><img class="news-intro-image" src="images/modules/news/intro_images/'.CHtml::encode($introImage).'" alt="news intro" /></div>' : '' ; ?>
    <div class="entry-meta"><time class="entry-date" datetime="<?php echo CHtml::encode($datePublished); ?>"><?php echo $datePublished; ?></time></div>
    <div class="entry-content"><?php echo $newsText; ?></div>
</article>
