<?php
    $this->_activeMenu = 'news/viewAll';
    $this->_pageTitle = A::t('news', 'All News');
    A::app()->getClientScript()->registerCssFile('css/modules/news/news.css');
?>
<article id="all-news" class="all-news type-news status-publish hentry">
    <header class="entry-header">
        <h1 class="entry-title">
            <span><?php echo  A::t('news', 'All News'); ?></span>
        </h1>
        <?php
        $breadCrumbLinks = array(
            array('label'=>A::t('news', 'Home'), 'url'=>Website::getDefaultPage()),
            array('label'=>A::t('news', 'All News'))
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
<?php
    if($actionMessage != ''){
        echo $actionMessage;
    }else{
        $showNews = count($news);
        for($i=0; $i < $showNews; $i++){
            $newsLink = Website::prepareLinkByFormat('news', 'news_link_format', $news[$i]['id'], $news[$i]['news_header']);
?>
    <article>
        <header class="entry-header">
            <h3 class="entry-title widget-title">
                <span><a href="<?php echo $newsLink; ?>"><?php echo $news[$i]['news_header']; ?></a></span>
            </h3>
        </header>
        <?php echo !empty($news[$i]['intro_image']) ? '<div class="entry-thumbnail"><img class="news-intro-image" src="images/modules/news/intro_images/'.CHtml::encode($news[$i]['intro_image']).'" alt="news intro" /></div>' : '' ; ?>
        <div class="entry-meta"><time class="entry-date" datetime="<?php echo CHtml::encode(date($dateTimeFormat, strtotime($news[$i]['created_at']))); ?>"><?php echo date($dateTimeFormat, strtotime($news[$i]['created_at'])); ?></time></div>
        <div class="entry-content"><?php echo $news[$i]['news_text']; ?></div>
        <div class="news-divider"></div>
    </article>
<?php
        }
        if($totalNews > 1){
            echo CWidget::create('CPagination', array(
                'actionPath'   => 'news/viewAll',
                'currentPage'  => $currentPage,
                'pageSize'     => $pageSize,
                'totalRecords' => $totalNews,
                'showResultsOfTotal' => false,
                'linkType' => 0,
                'paginationType' => 'justNumbers'
            ));
        }
    }
?>
</article>
