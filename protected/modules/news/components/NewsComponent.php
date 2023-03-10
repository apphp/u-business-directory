<?php
/**
 * News component for drawing news
 *
 * PUBLIC (static):         PRIVATE
 * -----------              ------------------
 * init
 * drawNewsBlock
 * drawSubscribeBlock
 * drawLastNewsBlock
 * drawShortcode
 */

class NewsComponent extends CComponent
{
    const NL = "\n";

    /**
     * Returns the instance of object
     * @return current class
     */
    public static function init()
    {
        return parent::init(__CLASS__);
    }

    /**
     * Draws last news side block
     * @param string $title
     * @return string
     */
    public static function drawNewsBlock($title = '')
    {
        $output       = '';
        $headerLength = 80;
        $newsCount    = 3;

        $headerLength = ModulesSettings::model()->param('news', 'news_header_length');
        $newsCount    = ModulesSettings::model()->param('news', 'news_count');
        $viewAll      = ModulesSettings::model()->param('news', 'view_all_link');
        $moduleLink   = ModulesSettings::model()->param('news', 'modulelink');
        $showDate     = ModulesSettings::model()->param('news', 'show_block_date');

        // fetch datetime format from settings table
        $dateTimeFormat = Bootstrap::init()->getSettings('datetime_format');

        $news = News::model()->findAll(
            array(
                'condition' => 'is_published = :isPublished AND created_at < :currentDate',
                'order'     => 'created_at DESC',
                'limit'     => '0, '.(int)$newsCount,
                'cacheId'   => 'NewsComponent::drawNewsBlock'
            ),
            array(':isPublished' => 1, ':currentDate' => LocalTime::currentDateTime())
        );

        $output .= '<aside id="latest-news-block" class="widget widget_posts">';
        $output .= '<h3 class="widget-title"><span>'.$title.'</span></h3>';
        $count = 0;
        $showViewAll = false;
        if(is_array($news) && count($news) > 0){
            foreach($news as $key => $val){
                if(!empty($val['intro_image']) && is_file(APPHP_PATH.'/images/modules/news/intro_images/'.$val['intro_image'])){
                    $imageFile = CHtml::encode($val['intro_image']);
                }else{
                    $imageFile = 'no_image.png';
                }

                $shortHeader = CString::substr($val['news_header'], $headerLength);
                $newsLink    = Website::prepareLinkByFormat('news', 'news_link_format', $val['id'], $val['news_header']);
                $output .= '<div class="postitems-wrapper">';
                $output .= '<div class="postitem clearfix with-thumbnail">';
                $output .= '<div class="thumb-wrap fl">';
                $output .= '<a href="'.$newsLink.'" class="greyscale">';
                $output .= '<img class="thumb" src="images/modules/news/intro_images/'.$imageFile.'" alt="">';
                $output .= '</a>';
                $output .= '</div>';
                $output .= '<h3><a href="'.CHtml::encode($newsLink).'">'.CString::substr($shortHeader, 50, '', true).'</a></h3>';
                $output .= '<p class="block-content">'.CString::substr(strip_tags($val['news_text']), 125, '', true);
                //$output .= '<a class="more more-news" href="'.$newsLink.'">'.A::t('news', 'read more').' &raquo;</a>';
                if($showDate) {
                    $output .= '<span class="block-date">'.CLocale::date($dateTimeFormat, $val['created_at']).'</span>';
                }
                $output .= '</p>';
                $output .= '</div>';
                $output .= '</div>';
                $count++;
            }
            if($viewAll == 'always'){
                $showViewAll = true;
            }else if(in_array($viewAll, array('show-after_1_item','show-after_2_items','show-after_3_items','show-after_4_items','show-after_5_items')) && ($count >= preg_replace('/[^0-9]/', '', $viewAll))){
                $showViewAll = true;
            }
            if($showViewAll) {
                $output .= '<a class="news-all" href="'.$moduleLink.'">'.A::t('news', 'View All').'</a>';
            }
        }else{
            $output .= A::t('news', 'No news yet');
        }
        $output .= '</aside>';

        return $output;
    }

    /**
     * Draws last news side block
     * @param string $title
     * @return string
     */
    public static function drawLastNewsBlock($title = '')
    {
        $output       = '';
        $newsCount    = 2;

        $news = News::model()->findAll(
            array(
                'condition' => 'is_published = :isPublished AND created_at < :currentDate',
                'order'     => 'created_at DESC',
                'limit'     => '0, '.(int)$newsCount,
                'cacheId'   => 'NewsComponent::drawLastNewsBlock'
            ),
            array(':isPublished' => 1, ':currentDate' => LocalTime::currentDateTime())
        );


        if(!empty($news) && is_array($news)){
            $output .= CHtml::openTag('div', array('class'=>'box widget-container widget_posts', 'id'=>'widget_posts'));
            $output .= CHtml::openTag('div', array('class'=>'box-wrapper'));
            $output .= CHtml::openTag('div', array('class'=>'title-border-bottom'));
            $output .= CHtml::openTag('div', array('class'=>'title-border-top'));
            $output .= CHtml::tag('div', array('class'=>'title-decoration'), '');
            $output .= CHtml::tag('h2', array('class'=>'widget-title'), A::t('news', 'The Latest News')).self::NL;
            $output .= CHtml::closeTag('div');
            $output .= CHtml::closeTag('div');
            $output .= CHtml::openTag('div', array('class'=>'postitems-wrapper'));
            foreach($news as $oneNews){
                $output .= CHtml::openTag('div', array('class'=>'postitem clearfix with-thumbnail'));
                $output .= CHtml::openTag('div', array('class'=>'thumb-wrap fl'));
                $output .= CHtml::openTag('a', array('href'=>'news/view/id/'.CHtml::encode($oneNews['id']), 'class'=>'greycale'));
                $output .= CHtml::tag('img', array('src'=>'images/modules/news/intro_images/'.(!empty($oneNews['intro_image']) ? CHtml::encode($oneNews['intro_image']) : 'no_image.png'), 'class'=>'thumb'));
                $output .= CHtml::closeTag('a');
                $output .= CHtml::closeTag('div');
                $output .= CHtml::openTag('h3', array());
                $output .= CHtml::tag('a', array('href'=>'news/view/id/'.CHtml::encode($oneNews['id'])), CString::substr($oneNews['news_header'], 25, '', true));
                $output .= CHtml::closeTag('h3');
                $output .= CHtml::openTag('p', array());
                $output .= CHtml::tag('small', array(), CString::substr($oneNews['news_text'], 75, '', true));
                $output .= CHtml::closeTag('p');
                $output .= CHtml::closeTag('div');
            }
            $output .= CHtml::closeTag('div');
            $output .= CHtml::closeTag('div');
            $output .= CHtml::closeTag('div');
        }

        return $output;
    }

    /**
     * Draws subscribe block
     * @return string
     */
    public static function drawSubscriptionBlock()
    {
        $controller = A::app()->view->getController();
        $action = A::app()->view->getAction();
        $result = '';
        // If the subscription page, do not show this form
        if('NewsSubscribers' != $controller || 'subscribe' != $action){
            $view = A::app()->view;
            $view->typeFirstName = ModulesSettings::model()->param('news', 'news_subscribers_first_name');
            $view->typeLastName  = ModulesSettings::model()->param('news', 'news_subscribers_last_name');
            $view->typeFullName  = ModulesSettings::model()->param('news', 'news_subscribers_full_name');
            $result = $view->renderContent('newssubscribers');
        }
        return $result;
    }

    /**
     * Draws news
     * @param array $params
     * @return string
     */
    public static function drawShortcode($params = array())
    {
        $output = '';

        $currentPage = A::app()->getRequest()->getQuery('page', 'integer', 1);
        if($currentPage <= 0) {
            $currentPage = 1;
        }
        $pageSize  = ModulesSettings::model()->param('news', 'news_per_page');
        $totalNews = News::model()->count();

        $news = News::model()->findAll(array(
            'limit' => (($currentPage - 1) * $pageSize).', '.$pageSize,
            'order' => 'created_at DESC'
        ));
        $showNews = count($news);

        if(!$showNews){
            $output .= A::t('news', 'Wrong parameter passed or there are no news!');
        }else{
            $dateTimeFormat = Bootstrap::init()->getSettings('date_format');

            for($i=0; $i < $showNews; $i++){
                $output .= '<h3>'.$news[$i]['news_header'].'</h3>';
                $output .= '<p class="news-text">'.$news[$i]['news_text'].'</p>';
                $output .= '<p class="news-info">'.A::t('news', 'Published at').': '.date($dateTimeFormat, strtotime($news[$i]['created_at'])).'</p>';
                $output .= '<div class="news-divider"></div>';
            }

            if($totalNews > 1){
                $output .= CWidget::create('CPagination', array(
                    'actionPath'         => A::app()->router->getCurrentUrl(),
                    'currentPage'        => $currentPage,
                    'pageSize'           => $pageSize,
                    'totalRecords'       => $totalNews,
                    'showResultsOfTotal' => false,
                    'linkType'           => 0,
                    'paginationType'     => 'justNumbers'
                ));
            }
        }
        return $output;
    }
}
