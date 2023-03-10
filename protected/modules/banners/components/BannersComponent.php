<?php
/**
 * BannersComponent
 *
 * PUBLIC (static):         PRIVATE:
 * -----------              ------------------
 * init
 * prepareTab
 * drawSlider
 * drawShortcode
 * getBanner
 *
 */

class BannersComponent extends CComponent
{

    const NL = "\n";

    /**
     *  Returns the instance of object
     *  @return current class
     */
    public static function init()
    {
        return parent::init(__CLASS__);
    }

    /**
     * Prepares Banners module tabs
     * @param string $activeTab
     * @param int $menuCatId
     * @param int $menuCatItemId
     * @return bool
     */
    public static function prepareTab($activeTab = 'info', $menuCatId = '', $menuCatItemId = '')
    {
        return CWidget::create('CTabs', array(
            'tabsWrapper' => array('tag' => 'div', 'class' => 'title'),
            'tabsWrapperInner'=>array('tag' => 'div', 'class' => 'tabs'),
            'contentWrapper' => array(),
            'contentMessage' => '',
            'tabs'=>array(
                A::t('banners', 'Settings') => array('href' => 'modules/settings/code/banners', 'id' => 'tabSettings', 'content' => '', 'active' => false, 'htmlOptions' => array('class' => 'modules-settings-tab')),
                A::t('banners', 'Banners Management') => array('href' => 'Banners/index', 'id' => 'tabBanners', 'content' => '', 'active' => ($activeTab == 'banners' ? true : false)),
            ),
            'events' => array(
            ),
            'return' => true,
        ));
    }

    /**
     * Draws banner slider
     * @param array $params     Values: 'width', 'height', 'navigation', 'delay'
     * @return string $output
     */
    public static function drawSlider($params = array())
    {
        // Module settings
        $rotationDelay = (int)ModulesSettings::model()->param('banners', 'rotation_delay');
        $viewerMode = ModulesSettings::model()->param('banners', 'viewer_type');
		$output = '';

        $width = isset($params['width']) ? (int)$params['width'] : '670';
        $height = isset($params['height']) ? (int)$params['height'] : '445';
        $navigation = isset($params['navigation']) ? (string)$params['navigation'] : 'true';
        $paramsDelay = isset($params['delay']) ? (int)$params['delay'] : '';

        // Calculate a delay in milliseconds
        $delay = !empty($paramsDelay) ? $paramsDelay : $rotationDelay * 1000;

        // Setup visability state for banners
        $sliderState = true;
        switch($viewerMode){
            case 'visitors only':
                $sliderState = CAuth::isLoggedIn() ? false : true;
                break;
            case 'registered only':
                $sliderState = CAuth::isLoggedIn() ? true : false;
                break;
            default:
                $sliderState = true;
                break;
        }

        A::app()->getClientScript()->registerScriptFile('js/vendors/jquery/jquery'.(A::app()->getLanguage('direction') == 'rtl' ? '.rtl' : '').'.js', 2);
        A::app()->getClientScript()->registerScriptFile('js/vendors/coin-slider/coin-slider.min.js', 2);
        A::app()->getClientScript()->registerCssFile('js/vendors/coin-slider/coin-slider'.(A::app()->getLanguage('direction') == 'rtl' ? '.rtl' : '').'.css');
        A::app()->getClientScript()->registerScript(
            'viewMenuCategory',
            'jQuery(document).ready(function() {
                if(jQuery("#coin-slider").length > 0){
                    jQuery("#coin-slider").coinslider({ width: '.$width.', height: '.$height.', navigation: '.$navigation.', delay: '.$delay.', });
                }
            });
            ',
            1
        );

        $bannersData = Banners::model()->findAll(array(
            'condition' => 'is_active = 1',
            'order' => 'sort_order ASC'
        ));

        $countData = count($bannersData);

        if($sliderState){
            $output = CHtml::openTag('div', array('id' => 'coin-slider')).self::NL;
            for ($i = 0; $i < $countData; $i++){
                $bannerItem = CHtml::image('images/modules/banners/'.$bannersData[$i]['image_file'], CHtml::encode($bannersData[$i]['banner_description'])).CHtml::tag('span', '', $bannersData[$i]['banner_description']);
                if($bannersData[$i]['link_url'] != ''){
                    $output .= CHtml::link(
                        $bannerItem,
                        $bannersData[$i]['link_url'],
                        array('target'=>'_blank')
                    );
                }else{
                    $output .= $bannerItem;
                }
            }
            $output .= CHtml::closeTag('div').self::NL;
        }

        return $output;
    }

    /**
     * Draws shortcode output
     * @param array $params
     */
    public static function drawShortcode($params = array())
    {
        return self::drawSlider($params);
    }

    /**
     * Get banner by ID
     * @param int $bannerId
     * @return html
     */
    public static function getBanner($bannerId)
    {
        $banner = Banners::model()->findByPk($bannerId, 'is_active = 1');
        if(empty($banner)){
            return '';
        }

        $link   = ($banner->link_url ? $banner->link_url : '');
        $src    = ($banner->image_file ? $banner->image_file : 'no_image.png');

        if(empty($link)){
            $output  = CHtml::openTag('div', array('class'=>'banner', 'href'=>$link, 'target'=>'_blank'));
            $output .= CHtml::image('images/modules/banners/'.$src, A::t('banners', 'Banner'), array('title'=>CHtml::encode($banner->banner_description)));
            $output .= CHtml::closeTag('div');
        }else{
            $output  = CHtml::openTag('a', array('class'=>'banner', 'href'=>$link, 'target'=>'_blank'));
            $output .= CHtml::image('images/modules/banners/'.$src, A::t('banners', 'Banner'), array('title'=>CHtml::encode($banner->banner_description)));
            $output .= CHtml::closeTag('a');
        }

        return $output;
    }
}
