<?php
/**
* DirectoryComponent
*
* STATIC
* -------------------------------------------
* PUBLIC:                           PRIVATE
* -----------                       ------------------
* init
* prepareSubTabs
* prepareTabs
* prepareCategories
* getAllCategoriesArray
* getAllRegionsArray
* printAllMarkers
* printCategoryMarkers
* makeMarkerListings
* getListingCondition
* drawLoginBlock
* drawFeaturedBlock
* drawReviewsBlock
* drawRecentListingsBlock
* drawStatisticsBlock
* drawSocialButtonsBlock
* inquiriesBlock
*
*/

class DirectoryComponent extends CComponent{

    const NL = "\n";
    /**
     * Returns the static component of the specified CComponent class
     * @return DirectoryComponent
     * */
    public static function init()
    {
        return parent::init(__CLASS__);
    }

    /**
     * Prepares Busines Directory module tabs
     * @param string $activeTab default 'customers'
     * @return void
     */
    public static function prepareTabs($activeTab = 'customers')
    {
        return CWidget::create('CTabs', array(
            'tabsWrapperInner'=>array('tag'=>'div', 'class'=>'tabs'),
            'tabsWrapper'=>array('tag'=>'div', 'class'=>'title'),
            'contentWrapper'=>array(),
            'contentMessage'=>'',
            'tabs'=>array(
                A::t('directory', 'Settings') => array('href'=>'modules/settings/code/directory', 'id'=>'tabSettings', 'content'=>'', 'active'=>false, 'htmlOptions'=>array('class'=>'modules-settings-tab')),
                A::t('directory', 'Site Info') => array('href'=>'siteInfoFrontend/siteinfo', 'id'=>'tabSiteInfo', 'content'=>'', 'active'=>($activeTab == 'siteinfo' ? true : false), 'htmlOptions'=>array()),
                A::t('directory', 'Categories') => array('href'=>'categories/manage', 'id'=>'tabCategories', 'content'=>'', 'active'=>($activeTab == 'categories' ? true : false)),
                A::t('directory', 'Locations') => array('href'=>'regions/manage', 'id'=>'tabLocations', 'content'=>'', 'active'=>($activeTab == 'locations' ? true : false)),
                A::t('directory', 'Listings') => array('href'=>'listings/manage', 'id'=>'tabListings', 'content'=>'', 'active'=>($activeTab == 'listings' ? true : false)),
                A::t('directory', 'Accounts') => array('href'=>'customers/manage', 'id'=>'tabAccounts', 'content'=>'', 'active'=>($activeTab == 'accounts' ? true : false)),
                A::t('directory', 'Reviews') => array('href'=>'reviews/manage', 'id'=>'tabReview', 'content'=>'', 'active'=>($activeTab == 'reviews' ? true : false)),
                A::t('directory', 'Inquiries') => array('href'=>'inquiries/manage', 'id'=>'tabInquiries', 'content'=>'', 'active'=>($activeTab == 'inquiries' ? true : false)),
                A::t('directory', 'Advertise Plans') => array('href'=>'plans/manage', 'id'=>'tabAdvertisePlans', 'content'=>'', 'active'=>($activeTab == 'plans' ? true : false)),
                A::t('directory', 'Orders') => array('href'=>'orders/manage', 'id'=>'tabOrders', 'content'=>'', 'active'=>($activeTab == 'orders' ? true : false)),
            ),
            'events'=>array(),
            'return'=>true,
        ));
    }

    /**
     * Prepares Business Directory module sub-tabs
     * @param string $parentTab default 'accounts'
     * @param string $activeSubTab default ''
     * @return string
     */
    public static function prepareSubTabs($parentTab = 'accounts', $activeSubTab = '')
    {
        $output = '';

        $activeSubTab = strtolower($activeSubTab);
        if($parentTab == 'accounts'){
            $output .= '<a class="sub-tab'.($activeSubTab == 'customers' ? ' active' : ' previous').'" href="customers/manage">'.A::t('directory', 'Customers').'</a>
            <a class="sub-tab'.($activeSubTab == 'groups' ? ' active' : ' previous').'" href="customerGroups/manage">'.A::t('directory', 'Groups').'</a>';
        }

        return $output;
    }

    /**
     * Prepare Business Directory module categories
     * @param int $parentId default 0
     * @return array array('css' => 'Css Content', 'html' => 'Html Content')
     */
    public static function prepareCategories($parentId = 0)
    {
        $output = '';
        $categories = Categories::model()->findAll('parent_id = :parent_id', array(':parent_id' => $parentId));
        if($categories){
            $subcategoryId = 0;
            $output .= '<h3 class="widget-title"><span>'.A::t('directory', 'Categories').'</span></h3>'.self::NL;
            $output .= '<div class="category-subcategories clearfix">'.self::NL;
            $output .= '<ul class="subcategories">'.self::NL;
            foreach($categories as $category){
                $className = 'sub-category-'.CHtml::encode(++$subcategoryId);
                $output .= '<li class="category">'.self::NL;
                $output .= '<div class="category-wrap-table">'.self::NL;
                $output .= '<div class="category-wrap-row">'.self::NL;
                $output .= '<div class="icon"><img src="images/modules/directory/categories/'.(!empty($category['icon']) ? CHtml::encode($category['icon']) : 'no_image.png').'" /></div>'.self::NL;
                $output .= '<div class="description">'.self::NL;
                $output .= '<h3><a href="categories/view/id/'.CHtml::encode($category['id']).'">'.$category['name'].'</a></h3>';
                $output .= $category['description'];
                $output .= '</div>'.self::NL;
                $output .= '</div>'.self::NL;
                $output .= '</div>'.self::NL;
                $output .= '</li>'.self::NL;
            }
            $output .= '</ul>'.self::NL;
            $output .= '</div>'.self::NL;
        }

        return $output;
    }

    public static function searchListings()
    {
        $cRequest = A::app()->getRequest();
        $view = A::app()->view;

        $view->getType = 'get';
        $view->searchPosition = false;
        $view->dataSearch = $cRequest->getQuery('s', 'string', '');
        $view->dataRadius = 100;
        $view->dataLatitude = '';
        $view->dataLongitude = '';
        $view->dataCategoryId = $cRequest->getQuery('categories', 'integer', 0);
        if($view->dataCategoryId != 0){
            $category = Categories::model()->findByPk($view->dataCategoryId);
        }
        $view->dataCategory = (!empty($category) ? $category->name : '');
        $view->dataRegionId = $cRequest->getQuery('locations', 'integer', 0);
        if($view->dataRegionId != 0){
            $region = Regions::model()->findByPk($view->dataRegionId);
        }
        $view->dataRegion = (!empty($region) ? $region->name : '');
        $view->renderContent('searchlistings');
        //return $result;
    }

    /**
     * Get all categories, previously prepare sub-category (add top of the string '- ' or '-- ')
     * @return array
     */
    public static function getAllCategoriesArray()
    {
        $outCategoryArray = array();
        $allCategories = Categories::model()->findAll();
        if($allCategories){
            $levelOne = array();
            $levelTwo = array();
            $levelThree = array();
            $allCategoryNames = array();

            foreach($allCategories as $category){
                if($category['parent_id'] == 0){
                    if(isset($levelThree[$category['id']])){
                        $levelTwo[$category['id']] = $levelThree[$category['id']];
                        unset($levelThree[$category['id']]);
                    }
                    $levelOne[$category['id']] = $category['name'];
                }else if (in_array($category['parent_id'], array_keys($levelOne))){
                    isset($levelTwo[$category['parent_id']]) ?
                        $levelTwo[$category['parent_id']][$category['id']] = $category['name'] :
                        $levelTwo[$category['parent_id']] = array($category['id'] => $category['name']);
                }else{
                    isset($levelThree[$category['parent_id']]) ?
                        $levelThree[$category['parent_id']][$category['id']] = $category['name'] :
                        $levelThree[$category['parent_id']] = array($category['id'] => $category['name']);
                }
            }

            asort($levelOne);
            foreach($levelOne as $parentId => $parentName){
                $outCategoryArray[$parentId] = $parentName;
                if(!empty($levelTwo[$parentId])){
                    asort($levelTwo[$parentId]);
                    foreach($levelTwo[$parentId] as $keyLevelTwo => $nameLevelTwo){
                        $outCategoryArray[$keyLevelTwo] = '- '.$nameLevelTwo;
                        if(!empty($levelThree[$keyLevelTwo])){
                            asort($levelThree[$keyLevelTwo]);
                            foreach($levelThree[$keyLevelTwo] as $keyLevelTree => $nameLevelTree){
                                $outCategoryArray[$keyLevelTree] = '-- '.$nameLevelTree;
                            }
                        }
                    }
                }
            }
        }

        return $outCategoryArray;
    }

    /**
     * Get all locations, previously prepare sub-location (add top of the string '- ')
     * @return array
     */
    public static function getAllRegionsArray()
    {
        $outRegionsArray = array();
        $locations = Regions::model()->findAll();
        $levelOne = array();
        $levelTwo = array();
        if($locations){
            foreach($locations as $location){
                if($location['parent_id'] == 0){
                    $levelOne[$location['id']] = $location['name'];
                }else{
                    isset($levelTwo[$location['parent_id']]) ?
                        $levelTwo[$location['parent_id']][$location['id']] = $location['name'] :
                        $levelTwo[$location['parent_id']] = array($location['id'] => $location['name']);
                }
            }

            asort($levelOne);
            foreach($levelOne as $parentId => $parentName){
                $outRegionsArray[$parentId] = $parentName;
                if(!empty($levelTwo[$parentId])){
                    asort($levelTwo[$parentId]);
                    foreach($levelTwo[$parentId] as $keyLevelTwo => $nameLevelTwo){
                        $outRegionsArray[$keyLevelTwo] = '- '.$nameLevelTwo;
                    }
                }
            }
        }
        return $outRegionsArray;
    }

    /**
     * Makes markers from first 100 Listings and return ready javascript code
     * @return string
     **/
    public static function printAllMarkers()
    {
        $output = '';
        $accessLevel = CAuth::isLoggedIn() ? 1 : 0;
        $condition = self::getListingCondition('not_expired');
        $conditions = array('condition'=>'is_published = 1 AND is_approved = 1 AND access_level <= :access_level AND region_longitude != \'\' AND region_latitude != \'\' AND ('.$condition.')','limit'=>100,'groupBy'=>'listing_id');
        $listingCategories = ListingsCategories::model()->findAll($conditions, array('i:access_level'=>$accessLevel));
        if($listingCategories){
            $allListingId = array();
            $categoryListings = array();
            foreach($listingCategories as $key => $listingCategory){
                if($listingCategory['category_id']){
                    $categoryId = (int)$listingCategory['category_id'];
                    if(!isset($categoryListings[$categoryId])){
                        $categoryListings[$categoryId] = array();
                    }
                    $categoryListings[$categoryId][] = $listingCategory['listing_id'];
                }
                $allListingId[$listingCategory['listing_id']] = $key;
            }
            $conditions = '`category_id` IN ('.implode(',', array_keys($categoryListings)).')';
            $categories = Categories::model()->findAll($conditions);
            if($categories){
                $parentNotIcon = array();
                $parentIcons = array();
                foreach($categories as $category){
                    if(empty($category['icon_map'])){
                        if(!in_array($category['parent_id'], $parentNotIcon)){
                            $parentNotIcon[] = $category['parent_id'];
                        }
                    }
                }

                // If there is no icon, then take them to the parent category
                if(!empty($parentNotIcon)){
                    // Make a selection of all categories of top-level, to later not to re-query the database
                    $parentCategories = Categories::model()->findAll(CConfig::get('db.prefix').'categories.id IN ('.implode(',',$parentNotIcon).') OR parent_id = 0');
                    if(is_array($parentCategories) && !empty($parentCategories)){
                        $categoriesNotIcon = array();
                        foreach($parentCategories as $category){
                            $parentIcons[$category['id']] = $category['icon_map'];
                            // If the parent category not contain an icon, learn grandparents (category top-level)
                            if(empty($category['icon_map']) && !empty($category['parent_id'])){
                                $categoriesNotIcon[$category['id']] = $category['parent_id'];
                            }
                        }
                        if(!empty($categoriesNotIcon)){
                            foreach($categoriesNotIcon as $id => $parentCategory){
                                $parentIcons[$id] = $parentIcons[$parentCategory];
                            }
                        }
                    }
                }

                foreach($categories as $category){
                    if(!empty($categoryListings[$category['id']])){
                        foreach($categoryListings[$category['id']] as $listingId){
                            $key = $allListingId[$listingId];
                            $listingCategories[$key]['icon_map'] = (!empty($category['icon_map']) ? $category['icon_map'] : $parentIcons[$category['parent_id']]);
                            $listingCategories[$key]['category_id'] = $category['id'];
                        }
                    }
                }
                $output = self::makeMarkerListings($listingCategories);
            }
        }

        return $output;
    }

    /**
     * Makes markers from Listings Category and return ready javascript code
     * @param int $categoryId the category Id
     * @return string
     **/
    public static function printCategoryMarkers($categoryId)
    {
        $output = '';

        $dbPrefix = CConfig::get('db.prefix');
        $categories = CDatabase::init()->select(
                'SELECT id, icon_map, parent_id FROM '.$dbPrefix.'categories WHERE id=:id OR parent_id=:parent_id OR parent_id IN (SELECT id FROM '.$dbPrefix.'categories WHERE parent_id=:s_parent_id)',
            array('i:id' => $categoryId, 'i:parent_id' => $categoryId, 'i:s_parent_id' => $categoryId));
        if($categories){
            $parentNotIcons = array();
            $parentCategoryIcons = array();

            if(count($categories) > 1){
                $allIdCategories = array();
                foreach($categories as $key => $category){
                    $allIdCategories[$category['id']] = $key;
                    if(empty($category['icon_map']) && !in_array($category['parent_id'], $parentNotIcons)){
                        $parentNotIcons[] = $category['parent_id'];
                    }
                }
                $condition = 'category_id IN ('.implode(', ', array_keys($allIdCategories)).')';
            }else{
                $condition = 'category_id='.(int)$categories[0]['id'];
                $allIdCategories[$categories[0]['id']] = 0;
                if(empty($categories[0]['icon_map'])){
                    $parentNotIcons[] = $categories[0]['parent_id'];
                }
            }

            if(!empty($parentNotIcons)){
                // Make a selection of all categories of top-level, to later not to re-query the database
                $parentCategories = Categories::model()->findAll($dbPrefix.'categories.id IN ('.implode(',', $parentNotIcons).') OR parent_id = 0');
                if(is_array($parentCategories) && !empty($parentCategories)){
                    $categoriesNotIcon = array();
                    foreach($parentCategories as $category){
                        $parentCategoryIcons[$category['id']] = $category['icon_map'];
                        // If the parent category not contain an icon, learn grandparents (category top-level)
                        if(empty($category['icon_map']) && !empty($category['parent_id'])){
                            $categoriesNotIcon[$category['id']] = $category['parent_id'];
                        }
                    }
                    if(!empty($categoriesNotIcon)){
                        foreach($categoriesNotIcon as $id => $parentCategory){
                            $parentCategoryIcons[$id] = $parentCategoryIcons[$parentCategory];
                        }
                    }
                }
            }

            $accessLevel = CAuth::isLoggedIn() ? 1 : 0;
            $condition = $condition.' AND ('.self::getListingCondition('not_expired').')';
            $conditions = array('condition'=>'is_published = 1 AND is_approved = 1 AND access_level <= :access_level AND '.$condition,'limit'=>100,'groupBy'=>'listing_id');
            $listingsCategories = ListingsCategories::model()->findAll($conditions, array('i:access_level'=>$accessLevel));
            if($listingsCategories){
                for($i = 0; $i < count($listingsCategories); $i++){
                    $key = $allIdCategories[$listingsCategories[$i]['category_id']];
                    $listingsCategories[$i]['icon_map'] = (!empty($categories[$key]['icon_map']) ? $categories[$key]['icon_map'] : $parentCategoryIcons[$categories[$key]['parent_id']]);
                }
                $output = self::makeMarkerListings($listingsCategories);
            }
        }

        return $output;
    }

    /**
     * Draws login form side block
     * @param string $title
     * @param string $activeMenu
     */
    public static function drawLoginBlock($title = '', $activeMenu = '')
    {
        $controller = A::app()->view->getController();
        $action = A::app()->view->getAction();
        $output = '';
        // check if login action is allowed
        if(!ModulesSettings::model()->param('directory', 'customer_allow_login')){
            return '';
        }

        // block content for logged in customers
        if(CAuth::isLoggedInAs('customer')){
            $output .= CHtml::openTag('aside', array('class'=>'widget widget_directory', 'id'=>'customer-login-block'));
            $output .= CHtml::openTag('h3', array('class'=>'widget-title'));
            $output .= CHtml::tag('span', array(), A::t('directory', 'My Account')).self::NL;
            $output .= CHtml::closeTag('h3');

            $output .= CHtml::openTag('div', array('id'=>'ait-login-tabs'));

            $output .= CWidget::create('CMenu', array(
                'items'=>array(
                    array('label'=>A::t('directory', 'Dashboard'), 'url'=>'customers/dashboard', 'target'=>'', 'id'=>''),
                    array('label'=>A::t('directory', 'My Account'), 'url'=>'customers/myAccount', 'target'=>'', 'id'=>''),
                    array('label'=>A::t('directory', 'My Listings'), 'url'=>'listings/myListings', 'target'=>'', 'id'=>''),
                    array('label'=>A::t('directory', 'My Orders'), 'url'=>'orders/myOrders', 'target'=>'', 'id'=>''),
                    array('label'=>A::t('directory', 'Inquiries'), 'url'=>'inquiries/myInquiries', 'target'=>'', 'id'=>''),
                    array('label'=>A::t('directory', 'Logout'), 'url'=>'customers/logout', 'target'=>'', 'id'=>''),
                ),
                'type'=>'vertical',
                'class'=>'side-panel-links',
                'subMenuClass'=>'',
                'dropdownItemClass'=>'',
                'separator'=>'',
                'id'=>'',
                'selected'=>$activeMenu,
                'return'=>true
            ));
            $output .= CHtml::closeTag('div').self::NL; /* side-panel-block */
            $output .= CHtml::closeTag('aside').self::NL; /* side-panel-block */

        }else if ('customers' != strtolower($controller) || ('registration' != strtolower($action) && 'login' != strtolower($action))){
            // Not show 'customers/registration', 'customers/login'
            $countries = array(''=>A::t('directory', '- select -'));
            $countriesResult = Countries::model()->findAll(array('condition'=>'is_active = 1', 'order'=>'sort_order DESC, country_name ASC'));
            $defaultCountryCode = '';
            if(is_array($countriesResult)){
                foreach($countriesResult as $key => $val){
                    $countries[$val['code']] = $val['country_name'];
                    if($val['is_default']) $defaultCountryCode = $val['code'];
                }
            }

            $approvalType    = ModulesSettings::model()->param('directory', 'customer_approval_type');

            if($approvalType == 'by_admin'){
                $messageSuccess = A::t('directory', 'Account successfully created! Admin approval required.');
                $messageInfo    = A::t('directory', 'Admin approve registration? Click <a href="{url}">here</a> to proceed.', array('{url}'=>'customers/login'));
            }else if($approvalType == 'by_email'){
                $messageSuccess = A::t('directory', 'Account successfully created! Email confirmation required.');
                $messageInfo    = A::t('directory', 'Already confirmed your registration? Click <a href="{url}">here</a> to proceed.', array('{url}'=>'customers/login'));
            }else{
                $messageSuccess = A::t('directory', 'Account successfully created!');
                $messageInfo    = A::t('directory', 'Click <a href="{url}">here</a> to proceed.', array('{url}'=>'customers/login'));
            }

            $allowFirstName  = ModulesSettings::model()->param('directory', 'customer_field_first_name');
            $allowLastName   = ModulesSettings::model()->param('directory', 'customer_field_last_name');
            $allowBirthDate  = ModulesSettings::model()->param('directory', 'customer_field_birth_date');
            $allowWebsite    = ModulesSettings::model()->param('directory', 'customer_field_website');
            $allowCompany    = ModulesSettings::model()->param('directory', 'customer_field_company');
            $allowPhone      = ModulesSettings::model()->param('directory', 'customer_field_phone');
            $allowFax        = ModulesSettings::model()->param('directory', 'customer_field_fax');
            $allowEmail      = ModulesSettings::model()->param('directory', 'customer_field_email');
            $allowAddress    = ModulesSettings::model()->param('directory', 'customer_field_address');
            $allowCity       = ModulesSettings::model()->param('directory', 'customer_field_city');
            $allowZipCode    = ModulesSettings::model()->param('directory', 'customer_field_zip_code');
            $allowCountry    = ModulesSettings::model()->param('directory', 'customer_field_country');
            $allowState      = ModulesSettings::model()->param('directory', 'customer_field_state');
            $allowCaptcha    = ModulesSettings::model()->param('directory', 'customer_field_captcha');
            $allowRememberMe = ModulesSettings::model()->param('directory', 'customer_allow_remember_me');


            $output .= CHtml::openTag('aside', array('class'=>'widget widget_directory', 'id'=>'customer-login-block'));
            $output .= CHtml::openTag('h3', array('class'=>'widget-title'));
            $output .= CHtml::tag('span', array(), $title).self::NL;
            $output .= CHtml::closeTag('h3').self::NL;

            $output .= CHtml::openTag('div', array('class'=>'not-logged'));
            $output .= CHtml::openTag('div', array('id'=>'ait-login-tabs'));

            $output .= CHtml::openTag('ul');

            $output .= CHtml::openTag('li', array('class'=>'active'));
            $output .= CHtml::tag('a', array('class'=>'login', 'href'=>'#ait-dir-login-tab'), A::t('directory', 'Login')).self::NL;
            $output .= CHtml::closeTag('li').self::NL;
            $output .= CHtml::openTag('li');
            $output .= CHtml::tag('a', array('class'=>'register', 'href'=>'#ait-dir-register-tab'), A::t('directory', 'Register')).self::NL;
            $output .= CHtml::closeTag('li').self::NL;

            $output .= CHtml::closeTag('ul').self::NL;

            $output .= CHtml::openTag('div', array('id'=>'ait-dir-login-tab'));

            $output .= CHtml::openForm('customers/login', 'post', array('name'=>'ait-login-form-widget', 'id'=>'ait-login-form-widget'));
            $output .= CHtml::hiddenField('act', 'send');

            // username row
            $output .= CHtml::openTag('div', array('class'=>'login-username'));
            $output .= CHtml::tag('label', array('for'=>'user_login'), A::t('directory', 'Username'));
            $output .= CHtml::textField('login_username', '', array('data-required'=>('true'), 'maxlength'=>25, 'autocomplete'=>'off'));
            $output .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'usernameLoginErrorEmpty'), A::t('directory', 'Username field cannot be empty!'));
            $output .= CHtml::closeTag('div').self::NL;

            // password row
            $output .= CHtml::openTag('div', array('class'=>'login-password'));
            $output .= CHtml::tag('label', array('for'=>'user_pass'), A::t('directory', 'Password').':');
            $output .= CHtml::passwordField('login_password', '', array('data-required'=>('true'), 'maxlength'=>20, 'autocomplete'=>'off'));
            $output .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'passwordLoginErrorEmpty'), A::t('directory', 'Password field cannot be empty!'));
            $output .= CHtml::closeTag('div').self::NL;

            // remember me
            if($allowRememberMe){
                $output .= CHtml::openTag('div', array('class'=>'login-remember'));
                $output .= CHtml::openTag('label');
                $output .= CHtml::checkBox('rememberme', false, array('id'=>'rememberme', 'value'=>'forever')).A::t('directory', 'Remember Me').self::NL;
                $output .= CHtml::closeTag('label');
                $output .= CHtml::closeTag('div');
            }

            $output .= CHtml::openTag('p', array('class'=>'login-submit'));
            $output .= CHtml::tag('button', array('type'=>'button', 'class'=>'button', 'onclick'=>'javascript:customers_SideLoginForm(this)'), A::t('directory', 'Login')).self::NL;
            $output .= CHtml::closeTag('p');

            $output .= CHtml::closeForm().self::NL;
            $output .= CHtml::closeTag('div');


            $output .= CHtml::openTag('div', array('id'=>'ait-dir-register-tab'));
            $output .= CHtml::openTag('div', array('id'=>'messageSuccess', 'style'=>'display:none'));
            $output .= CHtml::tag('p', array('class'=>'alert alert-success'), $messageSuccess);
            $output .= CHtml::tag('p', array('class'=>'alert alert-info'), $messageInfo);
            $output .= CHtml::closeTag('div');
            $output .= CHtml::openForm('customers/registration', 'post', array('name'=>'ait-register-form-widget', 'id'=>'ait-register-form-widget', 'class'=>'wp-user-form'));
            $output .= CHtml::hiddenField('act', 'send');


            if('allow-required' == $allowFirstName){
                $output .= CHtml::openTag('div', array('class'=>'register-first-name'));
                $output .= CHtml::tag('label', array('for'=>'customer-first-name'), A::t('directory', 'First Name').':');
                $output .= CHtml::textField('first_name', '', array('placeholder'=>'', 'data-required'=>true, 'maxlength'=>32, 'autocomplete'=>'off'));
                $output .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'firstNameRegisterErrorEmpty'), A::t('directory', 'The field first name cannot be empty!'));
                $output .= CHtml::closeTag('div');
            }
            if('allow-required' == $allowLastName){
                $output .= CHtml::openTag('div', array('class'=>'register-last-name'));
                $output .= CHtml::tag('label', array('for'=>'customer-last-name'), A::t('directory', 'Last Name').':');
                $output .= CHtml::textField('last_name', '', array('placeholder'=>'', 'data-required'=>true, 'maxlength'=>32, 'autocomplete'=>'off'));
                $output .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'lastNameRegisterErrorEmpty'), A::t('directory', 'The field last name cannot be empty!'));
                $output .= CHtml::closeTag('div');
            }
            if('allow-required' == $allowBirthDate){
                $output .= CHtml::openTag('div', array('class'=>'register-birth-date'));
                $output .= CHtml::tag('label', array('for'=>'customer-birth-date'), A::t('directory', 'Birth Date').':');
                $output .= CHtml::textField('birth_date', '', array('placeholder'=>'', 'data-required'=>true, 'maxlength'=>10, 'autocomplete'=>'off'));
                $output .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'birthDateRegisterErrorEmpty'), A::t('directory', 'The field birth date cannot be empty!'));
                $output .= CHtml::closeTag('div');
            }
            if('allow-required' == $allowWebsite){
                $output .= CHtml::openTag('div', array('class'=>'register-website'));
                $output .= CHtml::tag('label', array('for'=>'customer-website'), A::t('directory', 'Website').':');
                $output .= CHtml::textField('website', '', array('placeholder'=>'', 'data-required'=>true, 'maxlength'=>255, 'autocomplete'=>'off'));
                $output .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'websiteRegisterErrorEmpty'), A::t('directory', 'The field website cannot be empty!'));
                $output .= CHtml::closeTag('div');
            }
            if('allow-required' == $allowCompany){
                $output .= CHtml::openTag('div', array('class'=>'register-name-company'));
                $output .= CHtml::tag('label', array('for'=>'customer-name-company'), A::t('directory', 'Company').':');
                $output .= CHtml::textField('company', '', array('placeholder'=>'', 'data-required'=>true, 'maxlength'=>128, 'autocomplete'=>'off'));
                $output .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'companyRegisterErrorEmpty'), A::t('directory', 'The field company cannot be empty!'));
                $output .= CHtml::closeTag('div');
            }
            if('allow-required' == $allowPhone){
                $output .= CHtml::openTag('div', array('class'=>'register-phone'));
                $output .= CHtml::tag('label', array('for'=>'customer-phone'), A::t('directory', 'Phone').':');
                $output .= CHtml::textField('phone', '', array('placeholder'=>'', 'data-required'=>true, 'maxlength'=>32, 'autocomplete'=>'off'));
                $output .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'phoneRegisterErrorEmpty'), A::t('directory', 'The field phone cannot be empty!'));
                $output .= CHtml::closeTag('div');
            }
            if('allow-required' == $allowFax){
                $output .= CHtml::openTag('div', array('class'=>'register-fax'));
                $output .= CHtml::tag('label', array('for'=>'customer-fax'), A::t('directory', 'Fax').':');
                $output .= CHtml::textField('fax', '', array('placeholder'=>'','data-required'=>true, 'maxlength'=>32, 'autocomplete'=>'off'));
                $output .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'faxRegisterErrorEmpty'), A::t('directory', 'The field fax cannot be empty!'));
                $output .= CHtml::closeTag('div');
            }
            if('allow-required' == $allowEmail){
                $output .= CHtml::openTag('div', array('class'=>'register-email'));
                $output .= CHtml::tag('label', array('for'=>'customer-email'), A::t('directory', 'Email').':');
                $output .= CHtml::textField('email', '', array('placeholder'=>'', 'data-required'=>true, 'maxlength'=>128, 'autocomplete'=>'off'));
                $output .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'emailRegisterErrorValid'), A::t('directory', 'You must provide a valid email address!'));
                $output .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'emailRegisterErrorEmpty'), A::t('directory', 'The field email cannot be empty!'));
                $output .= CHtml::closeTag('div');
            }
            if('allow-required' == $allowAddress){
                $output .= CHtml::openTag('div', array('class'=>'register-address'));
                $output .= CHtml::tag('label', array('for'=>'customer-address'), A::t('directory', 'Address').':');
                $output .= CHtml::textField('address', '', array('placeholder'=>'', 'data-required'=>true, 'maxlength'=>64, 'autocomplete'=>'off'));
                $output .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'addressRegisterErrorEmpty'), A::t('directory', 'The field address cannot be empty!'));
                $output .= CHtml::closeTag('div');
            }
            if('allow-required' == $allowCity){
                $output .= CHtml::openTag('div', array('class'=>'register-city'));
                $output .= CHtml::tag('label', array('for'=>'customer-city'), A::t('directory', 'City').':');
                $output .= CHtml::textField('city', '', array('placeholder'=>'', 'data-required'=>true, 'maxlength'=>64, 'autocomplete'=>'off'));
                $output .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'cityRegisterErrorEmpty'), A::t('directory', 'The field city cannot be empty!'));
                $output .= CHtml::closeTag('div');
            }
            if('allow-required' == $allowZipCode){
                $output .= CHtml::openTag('div', array('class'=>'register-zip-code'));
                $output .= CHtml::tag('label', array('for'=>'customer-zip-code'), A::t('directory', 'Zip Code').':');
                $output .= CHtml::textField('zip_code', '', array('placeholder'=>'', 'data-required'=>true, 'maxlength'=>32, 'autocomplete'=>'off'));
                $output .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'zipcodeRegisterErrorEmpty'), A::t('directory', 'The field zip code cannot be empty!'));
                $output .= CHtml::closeTag('div');
            }
            if('allow-required' == $allowCountry){
                $onchange = ($allowState == 'allow-required') ? "customers_ChangeCountry('ait-register-form-widget',this.value)" : '';

                $output .= CHtml::openTag('div', array('class'=>'register-country'));
                $output .= CHtml::tag('label', array('for'=>'customer-country'), A::t('directory', 'Country').':');
                $output .= CHtml::dropDownList('country_code', $defaultCountryCode, $countries, array('data-required'=>true, 'onchange'=>$onchange));
                $output .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'countrycodeRegisterErrorEmpty'), A::t('directory', 'The field country cannot be empty!'));
                $output .= CHtml::closeTag('div');
            }
            if('allow-required' == $allowState){
                $output .= CHtml::openTag('div', array('class'=>'register-state'));
                $output .= CHtml::tag('label', array('for'=>'customer-state'), A::t('directory', 'State/Province').':');
                $output .= CHtml::textField('state', '', array('data-required'=>true, 'maxlength'=>64, 'autocomplete'=>'off'));
                $output .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'stateRegisterErrorEmpty'), A::t('directory', 'The field state cannot be empty!'));
                $output .= CHtml::closeTag('div');
            }

            // username row
            $output .= CHtml::openTag('div', array('class'=>'register-username'));
            $output .= CHtml::tag('label', array('for'=>'customer-username'), A::t('directory', 'Username').':');
            $output .= CHtml::textField('username', '', array('placeholder'=>'myuser', 'data-required'=>('true'), 'maxlength'=>25, 'autocomplete'=>'off'));
            $output .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'usernameRegisterErrorEmpty'), A::t('directory', 'Username field cannot be empty!'));
            $output .= CHtml::closeTag('div').self::NL;

            // password row
            $output .= CHtml::openTag('div', array('class'=>'register-password'));
            $output .= CHtml::tag('label', array('for'=>'customer-password'), A::t('directory', 'Password').':');
            $output .= CHtml::passwordField('password', '', array('placeholder'=>'******', 'data-required'=>('true'), 'maxlength'=>20, 'autocomplete'=>'off'));
            $output .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'passwordRegisterErrorEmpty'), A::t('directory', 'Password field cannot be empty!'));
            $output .= CHtml::closeTag('div').self::NL;

            if('allow' == $allowCaptcha){
                $output .= CHtml::openTag('div', array('class'=>'register-captcha'));
                $output .= CWidget::create('CCaptcha', array('math', true, array('return'=>true)));
                $output .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'captchaRegisterError'), A::t('directory', 'The field captcha cannot be empty!'));
                $output .= CHtml::closeTag('div').self::NL;
            }

            $output .= CHtml::openTag('div', array('class'=>'register-licensie'));
            $output .= CHtml::checkBox('i_agree', false, array('data-required'=>('true'), 'style'=>'width:16px;vertical-align:sub;', 'value'=>'1'));
            $output .= A::t('directory', 'By signing up, I agree to the {terms_and_conditions}', array(
                '{terms_and_conditions}'=>CHtml::tag('a', array('target'=>'_blank', 'href'=>Website::prepareLinkByFormat('cms', 'page_link_format', 4, A::t('directory', 'Terms & Conditions')), 'id'=>'linkTermCondition', 'title'=>A::te('directory', 'Terms & Conditions')), A::t('directory', 'Terms & Conditions'))
            ));
            $output .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none;', 'id'=>'iAgreeError'), A::t('directory', 'You must agree with the terms and conditions before you create an account.'));
            $output .= CHtml::closeTag('div');

            $output .= CHtml::tag('p', array('style'=>'display:none', 'class'=>'error', 'id'=>'messageError'), '');
            $output .= '<br>';

            $output .= CHtml::openTag('div', array('class'=>'register-submit'));
            $output .= CHtml::tag('button', array('type'=>'button', 'class'=>'button', 'data-sending'=>A::t('directory', 'Sending...'), 'data-send'=>A::t('directory', 'Send'), 'onclick'=>'javascript:customers_SideRegisterForm(this)'), A::t('directory', 'Register')).self::NL;
            $output .= CHtml::closeTag('div');

            $output .= CHtml::closeForm().self::NL;
            $output .= CHtml::closeTag('div');

            $output .= CHtml::closeTag('div').self::NL; /* block-body */
            $output .= CHtml::closeTag('div').self::NL;
            $output .= CHtml::closeTag('aside').self::NL; /* side-panel-block */
            $buttons = SocialLogin::drawButtons(array(
                'facebook'=>'customers/login/type/facebook',
                'twitter'=>'customers/login/type/twitter',
                'google'=>'customers/login/type/google')
            );
            if(!empty($buttons)){
                $output .= CHtml::openTag('aside', array('class'=>'widget widget_social_login'));
                $output .= $buttons;
                $output .= CHtml::closeTag('aside');
            }

            // Prepare Terms & Conditions
            if(Modules::model()->exists("code = 'cms' AND is_installed = 1")){
                $pageModel = Pages::model()->findByPk('4', 'publish_status = 1');
                if(!empty($pageModel)){
                    $title = str_replace(array('"',"\n\r","\r\n","\r","\n","\t"), array("'",'','','','',''), $pageModel->page_header);
                    $text = str_replace(array('"',"\n\r","\r\n","\r","\n","\t"), array("'",'','','','',''), $pageModel->page_text);
                    A::app()->getClientScript()->registerScript(
                        'openModalRegistration',
                        '$(document).ready(function(){
                            jQuery("#linkTermCondition").click(function(){
                                modal({
                                    type:alert,
                                    title:"'.$title.'",
                                    text: "'.$text.'"
                                });
                                return false;
                            });
                        });'
                    );
                }
            }
            // register module javascript and css
            A::app()->getClientScript()->registerScriptFile('js/modules/directory/directory.js');
            if('allow-required' == $allowCountry && 'allow-required' == $allowState){
                A::app()->getClientScript()->registerScript(
                    'customersChangeCountry',
                    '$(document).ready(function(){
                        customers_ChangeCountry(
                            "ait-register-form-widget","'.$defaultCountryCode.'"
                        );
                    });'
                );
            }
        }

        return $output;
    }

    /**
     * Draws buttons for login social networks a block
     * @param string $title
     * @param string $activeMenu
     */
    public static function drawSocialButtonsBlock($title = '', $activeMenu = '')
    {
        $output = '';

        $activeSocial = SocialNetworksLogin::model()->findAll('is_active = 1');
        if(!empty($activeSocial) && is_array($activeSocial)){
            $dir = 'images/modules/directory/siteinfo/sociallogin/';
            $output .= CHtml::openTag('div', array('class'=>'social-login-block'));
            foreach($activeSocial as $social){
                $imagePath = is_file($dir.$social['button_image']) ? $dir.$social['button_image'] : $dir.'no_image.png';
                $name = $social['name'];

                $output .= CHtml::openTag('div', array('class'=>'thumb-wrap'));
                $output .= CHtml::openTag('a', array('href'=>'customers/login/type/'.$social['type']));
                $output .= CHtml::image($imagePath, '', array('class'=>'social-login-image', 'height'=>'32px', 'width'=>'32px'));
                $output .= CHtml::closeTag('a');
                $output .= CHtml::closeTag('div');
            }
            $output .= CHtml::closeTag('div');
        }

        return $output;
    }


    /**
     * Draws listing featured side block
     * @param string $title
     * @param string $activeMenu
     */
    public static function drawFeaturedBlock($title = '', $activeMenu = '')
    {
        $output = '';

        $condition = DirectoryComponent::getListingCondition('not_expired');
        $condition = 'is_published = 1 AND is_featured = 1 AND is_approved = 1 AND access_level <= '.(CAuth::isLoggedIn() ? 1 : 0).' AND ('.$condition.')';
        $orderBy = 'RAND()';
        $limit = ModulesSettings::model()->param('directory', 'featured_listings_count');

        $listingsFeatured = Listings::model()->findAll(array('condition'=>$condition, 'orderBy'=>$orderBy, 'limit'=>$limit));

        $output .= CHtml::openTag('aside', array('class'=>'widget widget_directory', 'id'=>'listings-featured-block'));
        $output .= CHtml::openTag('h3', array('class'=>'widget-title'));
        $output .= CHtml::tag('span', array(), A::t('directory', 'Featured Listings')).self::NL;
        $output .= CHtml::closeTag('h3');

        $output .= CHtml::openTag('div', array('id'=>'featured-wrapper'));
        foreach($listingsFeatured as $listing){
            $output .= CHtml::openTag('div', array('class'=>'featured clearfix with-thumbnail'));
            $output .= CHtml::openTag('div', array('class'=>'thumb-wrap fl'));
            $output .= CHtml::openTag('a', array('href'=>'listings/view/id/'.$listing['id']));
            $output .= CHtml::image('images/modules/directory/listings/thumbs/'.(!empty($listing['image_file_thumb']) ? $listing['image_file_thumb'] : 'no_logo.jpg'), '', array('class'=>'thumb'));
            $output .= CHtml::closeTag('a');
            $output .= CHtml::closeTag('div');
            $output .= CHtml::openTag('h3');
            $output .= CHtml::tag('a', array('href'=>'listings/view/id/'.$listing['id']), $listing['business_name']);
            $output .= CHtml::closeTag('h3');
            $output .= CHtml::openTag('p');
            $output .= CHtml::tag('small', array(), CString::substr($listing['business_description'], 60, false, true));
            $output .= CHtml::closeTag('p');
            $output .= CHtml::closeTag('div');
        }
        $output .= CHtml::closeTag('div');
        $output .= CHtml::closeTag('aside');

        return $output;
    }

    /**
     * Draws recent comments side block
     * @param string $title
     * @param string $activeMenu
     */
    public static function drawReviewsBlock($title = '', $activeMenu = '')
    {
        $output = '';

        $condition = DirectoryComponent::getListingCondition('not_expired');
        $condition = CConfig::get('db.prefix').'listings.is_published = 1 AND '.
            CConfig::get('db.prefix').'listings.is_approved = 1 AND '.
            CConfig::get('db.prefix').'listings.access_level <= '.(CAuth::isLoggedIn() ? 1 : 0).' AND '.
            '('.$condition.') AND '.CConfig::get('db.prefix').'reviews.is_public = 1';
        // Join with tabel 'listings'
        Reviews::model()->setTypeRelations('listings');
        $lastReviews = Reviews::model()->findAll(array('condition'=>$condition, 'limit'=>'5', 'orderBy'=>'created_at DESC'));
        Reviews::model()->resetTypeRelations();

        if(!empty($lastReviews) && is_array($lastReviews)){

            $output .= CHtml::openTag('div', array('class'=>'box widget-container widget_recent_comments', 'id'=>'recent-comments'));
            $output .= CHtml::openTag('div', array('class'=>'box-wrapper'));
            $output .= CHtml::openTag('div', array('class'=>'title-border-bottom'));
            $output .= CHtml::openTag('div', array('class'=>'title-border-top'));
            $output .= CHtml::tag('div', array('class'=>'title-decoration'), '');
            $output .= CHtml::tag('h2', array('class'=>'widget-title'), A::t('directory', 'Recent Reviews')).self::NL;
            $output .= CHtml::closeTag('div');
            $output .= CHtml::closeTag('div');
            $output .= CHtml::openTag('ul', array('id'=>'recentcomments'));
            foreach($lastReviews as $review){
                $output .= CHtml::openTag('li', array('class'=>'recentcomments'));
                $output .= $review['customer_name'].' '.A::t('directory', 'on').' ';
                $output .= CHtml::tag('a', array('href'=>'listings/view/id/'.CHtml::encode($review['listing_id'])), $review['listing_name']);
                $output .= CHtml::closeTag('li');
            }
            $output .= CHtml::closeTag('ul');
            $output .= CHtml::closeTag('div');
            $output .= CHtml::closeTag('div');
        }

        return $output;
    }

    /**
     * Draws recent comments side block
     * @param string $title
     * @param string $activeMenu
     */
    public static function drawIncomingJobs($title = '', $activeMenu = '')
    {
        $output = '';

        // fetch datetime format from settings table
        $dateTimeFormat = Bootstrap::init()->getSettings('datetime_format');

        $condition = CConfig::get('db.prefix').'inquiries.is_active = 1';
        Inquiries::model()->setTypeRelations('categories');
        $lastInquiries = Inquiries::model()->findAll(array(
            'condition'=>$condition,
            'orderBy'=>CConfig::get('db.prefix').'inquiries.id DESC',
            'groupBy'=>CConfig::get('db.prefix').'inquiries.date_created,'.
                CConfig::get('db.prefix').'inquiries.category_id,'.
                CConfig::get('db.prefix').'inquiries.region_id',
            'limit'=>'5'
        ));
        Inquiries::model()->resetTypeRelations();

        if(!empty($lastInquiries) && is_array($lastInquiries)){

            $output .= CHtml::openTag('aside', array('class'=>'widget widget_inquiry', 'id'=>'incoming-jobs-block'));
            $output .= CHtml::openTag('h3', array('class'=>'widget-title'));
            $output .= CHtml::tag('span', array(), A::t('directory', 'Incoming Jobs'));
            $output .= CHtml::closeTag('h3');
            $output .= CHtml::tag('div', array('id'=>'incoming-wrapper'));
            foreach($lastInquiries as $inquiry){
                $output .= CHtml::openTag('div', array('class'=>'incoming inquiryitem clearfix'));
                $output .= CHtml::tag('h3', array('class'=>'category'), !empty($inquiry['category_name']) ? $inquiry['category_name'] : A::t('directory', 'All categories'));
                $output .= CHtml::openTag('p', array());
                $output .= CHtml::tag('div', array('class'=>'left region'), !empty($inquiry['region_name']) ? $inquiry['region_name'] : A::t('directory', 'All locations'));
                $output .= CHtml::tag('span', array('class'=>'right block-date date-created'), CLocale::date($dateTimeFormat, $inquiry['date_created']));
                $output .= CHtml::closeTag('p');
                $output .= CHtml::closeTag('div');
            }
            $output .= CHtml::closeTag('div');
            $output .= CHtml::closeTag('aside');
        }

        return $output;
    }

    /**
     * Draws recent comments side block
     * @param string $title
     * @param string $activeMenu
     */
    public static function drawRecentListingsBlock($title = '', $activeMenu = '')
    {
        $output = '';

        $condition = DirectoryComponent::getListingCondition('not_expired');
        $condition = CConfig::get('db.prefix').'listings.is_published = 1 AND '.
            CConfig::get('db.prefix').'listings.is_approved = 1 AND '.
            CConfig::get('db.prefix').'listings.access_level <= '.(CAuth::isLoggedIn() ? 1 : 0).' AND ('.$condition.')';
        $lastListings = Listings::model()->findAll(array('condition'=>$condition, 'limit'=>'5', 'orderBy'=>'date_published DESC'));

        if(!empty($lastListings) && is_array($lastListings)){

            $output .= CHtml::openTag('div', array('class'=>'box widget-container widget_recent_entries', 'id'=>'recent-listings'));
            $output .= CHtml::openTag('div', array('class'=>'box-wrapper'));
            $output .= CHtml::openTag('div', array('class'=>'title-border-bottom'));
            $output .= CHtml::openTag('div', array('class'=>'title-border-top'));
            $output .= CHtml::tag('div', array('class'=>'title-decoration'), '');
            $output .= CHtml::tag('h2', array('class'=>'widget-title'), A::t('directory', 'Recent Listings')).self::NL;
            $output .= CHtml::closeTag('div');
            $output .= CHtml::closeTag('div');
            $output .= CHtml::openTag('ul', array('id'=>'recentlistings'));
            foreach($lastListings as $listing){
                $output .= CHtml::openTag('li', array('class'=>'recentlistings'));
                $output .= CHtml::tag('a', array('href'=>'listings/view/id/'.CHtml::encode($listing['id'])), $listing['business_name']);
                $output .= CHtml::closeTag('li');
            }
            $output .= CHtml::closeTag('ul');
            $output .= CHtml::closeTag('div');
            $output .= CHtml::closeTag('div');
        }

        return $output;
    }

    /**
     * Draws recent comments side block
     * @param string $title
     * @param string $activeMenu
     */
    public static function drawStatisticsBlock($title = '', $activeMenu = '')
    {
        $output = '';

        // We learn a number of approved and trained listings
        $condition = DirectoryComponent::getListingCondition('not_expired');
        $condition = CConfig::get('db.prefix').'listings.is_published = 1 AND '.
            CConfig::get('db.prefix').'listings.is_approved = 1 AND '.
            CConfig::get('db.prefix').'listings.access_level <= '.(CAuth::isLoggedIn() ? 1 : 0).' AND ('.$condition.')';
        $condition = '('.$condition.') OR ('.CConfig::get('db.prefix').'listings.is_approved = 0)';
        $countListings = Listings::model()->count(array('condition'=>$condition, 'select'=>'is_approved', 'groupBy'=>'is_approved', 'allRows'=>true));

        $countActive = 0;
        $countPending = 0;
        if(isset($countListings[0]['cnt']) && isset($countListings[0]['is_approved'])){
            if($countListings[0]['is_approved'] == 1){
                $countActive = $countListings[0]['cnt'];
            }else{
                $countPending = $countListings[0]['cnt'];
            }
        }

        if(isset($countListings[1]['cnt']) && isset($countListings[1]['is_approved'])){
            if($countListings[1]['is_approved'] == 1){
                $countActive = $countListings[1]['cnt'];
            }else{
                $countPending = $countListings[1]['cnt'];
            }
        }

        $condition = DirectoryComponent::getListingCondition('not_expired');
        $condition = CConfig::get('db.prefix').'listings.is_published = 1 AND '.
            CConfig::get('db.prefix').'listings.is_approved = 1 AND '.
            CConfig::get('db.prefix').'listings.access_level <= '.(CAuth::isLoggedIn() ? 1 : 0).' AND ('.$condition.') AND '.
            CConfig::get('db.prefix').'listings.date_published >= NOW() - INTERVAL 1 DAY';
        $countNewListings = Listings::model()->count($condition);
        $countCategories = Categories::model()->count();

        if(!empty($countListings) || !empty($countNewListings) || !empty($countCategories)){
            $output .= CHtml::openTag('div', array('class'=>'box widget_directory_statistics', 'id'=>'directory-statistics'));
            $output .= CHtml::openTag('div', array('class'=>'box-wrapper'));
            $output .= CHtml::openTag('div', array('class'=>'title-border-top'));
            $output .= CHtml::tag('div', array('class'=>'title-decoration'), '');
            $output .= CHtml::tag('h2', array('class'=>'widget-title'), A::t('directory', 'Directory Statistics')).self::NL;
            $output .= CHtml::closeTag('div');
            $output .= CHtml::openTag('ul', array('id'=>'directorystatistic'));
            $output .= CHtml::tag('li', array('class'=>'directorystatistic'), A::t('directory', 'Active Listings').': '.$countActive);
            $output .= CHtml::tag('li', array('class'=>'directorystatistic'), A::t('directory', 'Pending Listings').': '.$countPending);
            $output .= CHtml::tag('li', array('class'=>'directorystatistic'), A::t('directory', 'New submission in 24 hours').': '.(!empty($countNewListings) ? $countNewListings : 0));
            $output .= CHtml::tag('li', array('class'=>'directorystatistic'), A::t('directory', 'Categories').': '.(!empty($countCategories) ? $countCategories : 0));
            $output .= CHtml::closeTag('ul');
            $output .= CHtml::closeTag('div');
            $output .= CHtml::closeTag('div');
        }

        return $output;
    }

    /**
     * Draws 3 step for inquiries blok
     * @param string $title
     * @param string $activeMenu
     */
    public static function inquiriesBlock()
    {
        $output = '';
        $controller = strtolower(A::app()->view->getController());
        $action = strtolower(A::app()->view->getAction());
        $activeStep1 = false;
        $activeStep2 = false;

        if($controller == 'inquiries' && $action == 'firststep'){
            $activeStep1 = true;
        }else if($controller == 'inquiries' && $action == 'secondstep'){
            $activeStep2 = true;
        }

        $output .= CHtml::openTag('div', array('class'=>'inquiry-three-step', 'id'=>'inquiryThreeStep'));
        $output .= CHtml::openTag('div', array('class'=>'three-step'));
        $output .= CHtml::openTag('div', array('class'=>'step col-sm-4 col-xs-12 first-step'.($activeStep1 ? ' active' : '')));
        $output .= CHtml::tag('h3', array(), A::t('directory', 'Step').' 1');
        $output .= CHtml::openTag('div', array('class'=>'inquiry-content'));
        $output .= CHtml::tag('b', array(), A::t('directory', 'Send an Inquiry'));
        $output .= CHtml::tag('div', array(), A::t('directory', 'Completely free, enter what you want help with and where.'));
        $output .= CHtml::closeTag('div');
        $output .= CHtml::closeTag('div');
        $output .= CHtml::openTag('div', array('class'=>'step col-sm-4 col-xs-12 second-step'.($activeStep2 ? ' active' : '')));
        $output .= CHtml::tag('h3', array(), A::t('directory', 'Step').' 2');
        $output .= CHtml::openTag('div', array('class'=>'inquiry-content'));
        $output .= CHtml::tag('b', array(), A::t('directory', 'Wait for quotes'));
        $output .= CHtml::tag('div', array(), A::t('directory', 'Receive quotes from skilled craftsmen.'));
        $output .= CHtml::closeTag('div');
        $output .= CHtml::closeTag('div');
        $output .= CHtml::openTag('div', array('class'=>'step col-sm-4 col-xs-12 third-step'));
        $output .= CHtml::tag('h3', array(), A::t('directory', 'Step').' 3');
        $output .= CHtml::openTag('div', array('class'=>'inquiry-content'));
        $output .= CHtml::tag('b', array(), A::t('directory', 'Compare and choose'));
        $output .= CHtml::tag('div', array(), A::t('directory', 'Choose the company you like best among quotations.'));
        $output .= CHtml::closeTag('div');
        $output .= CHtml::closeTag('div');
        if($controller != 'inquiries' || ($action != 'firststep' && $action != 'secondstep')){
            $allCategories = array(''=>'--') + self::getAllCategoriesArray();
            $output .= CHtml::openTag('div', array('class'=>'guide-block'));
            $output .= CHtml::tag('div', array('class'=>'help-text'), A::t('directory', 'What do you need help with?'));
            $output .= CHtml::button(A::t('directory', 'Submit'), array('id'=>'buttonInquiryCategory', 'class'=>'button'));
            $output .= CHtml::dropDownList('category', '', $allCategories, array('id'=>'inquiryCategory'));
            $output .= CHtml::closeTag('div');

            A::app()->getClientScript()->registerScript(
                'inquiryThreeStep',
                'jQuery(document).ready(function(){
                    jQuery("#buttonInquiryCategory").click(function(){
                        var category = jQuery("#inquiryCategory option:selected").val();
                        if(!category){
                            jQuery(location).attr("href", "inquiries/firstStep");
                        }else{
                            jQuery(location).attr("href", "inquiries/firstStep/categoryId/" + category);
                        }
                    });
                });'
            );
        }
        $output .= CHtml::closeTag('div');
        $output .= CHtml::closeTag('div');
        $output .= CHtml::tag('div', array('class'=>'clear'));

        return $output;
    }

    /**
     * Makes markers from Listings and and return ready javascript code
     * @param array $listings the array Listings + an additional field 'icon_map' for each listing
     * @return string
     **/
    public static function makeMarkerListings($listings)
    {
        $output = '';
        if(!empty($listings) && is_array($listings)){
            if(count($listings) > 100){
                $listings = array_slice($listings, 0, 100);
            }
            $newLine = array();
            for($i = 0; $i < 10; $i++){
                $printComments = false;
                if(APPHP_MODE == 'debug'){
                    $printComments = true;
                    if($i > 0){
                        $newLine[$i] = $newLine[$i-1]."\t";
                    }else{
                        $newLine[$i] = "\r\n";
                    }
                }else{
                    $newLine[$i] = '';
                }
            }

            $output = 'var mapDiv,map,smallMapDiv,infobox;'.$newLine[0]
            .'jQuery(document).ready(function($) {'.$newLine[1]
                .'"use strict";'.$newLine[1]
                .'mapDiv = $("#directory-main-bar");'.$newLine[1]
                .'mapDiv.height(500).gmap3({'.$newLine[2]
                    .'map: {'.$newLine[3]
                        .'options: {'.$newLine[4]
                            .'"draggable":true,'.$newLine[4]
                            .'"mapTypeControl":true,'.$newLine[4]
                            .'"mapTypeId":google.maps.MapTypeId.ROADMAP,'.$newLine[4]
                            .'"scrollwheel":false,'.$newLine[4]
                            .'"panControl":true,'.$newLine[4]
                            .'"rotateControl":false,'.$newLine[4]
                            .'"scaleControl":true,'.$newLine[4]
                            .'"streetViewControl":true,'.$newLine[4]
                            .'"zoomControl":true,'.$newLine[4]
                            .'"maxZoom":15'.$newLine[3]
                        .'}'.$newLine[2]
                    .'},'.$newLine[2]
                    .'marker: {'.$newLine[3]
                        ."values: [";
            $outputArray = array();
            foreach($listings as $listing){
                if(!empty($listing['region_latitude']) && !empty($listing['region_longitude'])){
                    $outputArray[] = $newLine[4]
                        .'{'.$newLine[5]
                            .'latLng: ['.(!empty($listing['region_latitude']) ? CHtml::encode($listing['region_latitude']) : '0').','.(!empty($listing['region_longitude']) ? CHtml::encode($listing['region_longitude']) : '0').'],'.$newLine[5]
                            .'options: {'.$newLine[6]
                                .'icon: "images/modules/directory/categories/mapicons/'.(!empty($listing['icon_map']) ? CHtml::encode($listing['icon_map']) : 'no_image.png').'",'.$newLine[6]
                                .'shadow: "images/modules/directory/categories/mapicons/icon-shadow.png",'.$newLine[5]
                            .'},'.$newLine[5]
                            .'data: \'<div class="marker-holder">\'+'.$newLine[7]
                                    .'\'<div class="marker-content with-image">\'+'.$newLine[8]
                                        .'\'<img src="images/modules/directory/listings/thumbs/'.(!empty($listing['image_file_thumb']) ? CHtml::encode($listing['image_file_thumb']) : 'no_logo.jpg').'" alt="">\'+'.$newLine[8]
                                        .'\'<div class="map-item-info">\'+'.$newLine[9]
                                            .'\'<div class="title">\'+"'.CHtml::encode($listing['business_name']).'"+\'</div>\'+'.$newLine[9]
                                            .'\'<div class="address">\'+"'.CHtml::encode($listing['business_address']).'"+\'</div>\'+'.$newLine[9]
                                            .'\'<div data-link="listings/view/id/'.CHtml::encode($listing['listing_id']).'" class="more-button">\'+"'.CHtml::encode(A::t('directory', 'View More')).'" + \'</div>\'+'.$newLine[8]
                                        .'\'</div>\'+'.$newLine[8]
                                        .'\'<div class="arrow"></div>\'+'.$newLine[8]
                                        .'\'<div class="close"></div>\'+'.$newLine[8]
                                    .'\'</div>\'+'.$newLine[7]
                                .'\'</div>\','.$newLine[5]
                            .'tag: "listing-'.CHtml::encode($listing['id']).'"'.$newLine[4]
                        ."}";
                }
            }
            $output .= implode(',', $outputArray);
            $output .= $newLine[3]
                        .'], '.$newLine[3]
                        .'options:{draggable:false},'.$newLine[3]
                        .'cluster:{'.$newLine[4]
                            .'radius: 20,'
                            .($printComments ? $newLine[4].'// This style will be used for clusters with more than 0 markers'.$newLine[4] : '')
                            .'0: {content: "<div class=\'cluster cluster-1\'>CLUSTER_COUNT</div>",width: 90,height: 80},'
                            .($printComments ? $newLine[4].'// This style will be used for clusters with more than 20 markers'.$newLine[4] : '')
                            .'20: {content: "<div class=\'cluster cluster-2\'>CLUSTER_COUNT</div>",width: 90,height: 80},'
                            .($printComments ? $newLine[4].'// This style will be used for clusters with more than 50 markers'.$newLine[4] : '')
                            .'50: {content: "<div class=\'cluster cluster-3\'>CLUSTER_COUNT</div>",width: 90,height: 80},'.$newLine[4]
                            .'events: {'.$newLine[5]
                                .'click: function(cluster) {'.$newLine[6]
                                    .'map.panTo(cluster.main.getPosition());'.$newLine[6]
                                    .'map.setZoom(map.getZoom() + 2);'.$newLine[5]
                                .'}'.$newLine[4]
                            .'}'.$newLine[3]
                        .'},'.$newLine[3]
                        .'events: {'.$newLine[4]
                            .'click: function(marker, event, context){'.$newLine[5]
                                .'map.panTo(marker.getPosition());'.$newLine[5]
                                .'infobox.setContent(context.data);'.$newLine[5]
                                .'infobox.open(map,marker);'
                                .($printComments ? $newLine[5].'// if map is small'.$newLine[5] : '')
                                .'var iWidth = 260;'.$newLine[5]
                                .'var iHeight = 300;'.$newLine[5]
                                .'if((mapDiv.width() / 2) < iWidth ){'.$newLine[6]
                                    .'var offsetX = iWidth - (mapDiv.width() / 2);'.$newLine[6]
                                    .'map.panBy(offsetX,0);'.$newLine[5]
                                .'}'.$newLine[5]
                                .'if((mapDiv.height() / 2) < iHeight ){'.$newLine[6]
                                    .'var offsetY = -(iHeight - (mapDiv.height() / 2));'.$newLine[6]
                                    .'map.panBy(0,offsetY);'.$newLine[5]
                                .'}'.$newLine[4]
                            .'}'.$newLine[3]
                        .'}'.$newLine[2]
                    .'}'.$newLine[1]
                .'},'.$newLine[1]
                .'"autofit");'.$newLine[1]
                .'map = mapDiv.gmap3("get");'.$newLine[1]
                .'infobox = new InfoBox({'.$newLine[2]
                    .'pixelOffset: new google.maps.Size(-50, -65),'.$newLine[2]
                    .'closeBoxURL: "",'.$newLine[2]
                    .'enableEventPropagation: true'.$newLine[1]
                .'});'.$newLine[1]
                .'mapDiv.delegate(\'.infoBox .close\',\'click\',function () {'.$newLine[2]
                    .'infobox.close();'.$newLine[1]
                .'});'
                .($printComments ? $newLine[1].'// hotfix for chrome on android'.$newLine[1] : '')
                .'mapDiv.delegate(\'.infoBox div.more-button\', \'click\', function() {'.$newLine[2]
                    .'window.location = $(this).data(\'link\');'.$newLine[1]
                .'});'.$newLine[1]
                .'if (Modernizr.touch){'.$newLine[2]
                    .'map.setOptions({ draggable : false });'.$newLine[2]
                    .'var draggableButton = $(\'<div class="draggable-toggle-button inactive">'.A::t('directory', 'Activate map').'</div>\').appendTo(mapDiv);'.$newLine[2]
                    .'draggableButton.click(function () {'.$newLine[3]
                        .'if($(this).hasClass(\'active\')){'.$newLine[4]
                            .'$(this).removeClass(\'active\').addClass(\'inactive\').text("'.A::t('directory', 'Activate map').'");'.$newLine[4]
                            .'map.setOptions({ draggable : false });'.$newLine[3]
                        .'} else {'.$newLine[4]
                            .'$(this).removeClass(\'inactive\').addClass(\'active\').text("'.A::t('directory', 'Deactivate map').'");'.$newLine[4]
                            .'map.setOptions({ draggable : true });'.$newLine[3]
                        .'}'.$newLine[2]
                    .'});'.$newLine[1]
                .'}'.$newLine[1]
            .'});';
        }

        return $output;
    }

    /**
     * Returns listing condition for sql statement
     * @param string $type
     * @return string
     */
    public static function getListingCondition($type = '')
    {
        $condition = '';

        if($type == 'not_expired' || $type == 'listing_not_expired'){
            $condition .= " (
               ".CConfig::get('db.prefix')."listings.finish_publishing = '0000-00-00 00:00:00' OR
               ".CConfig::get('db.prefix')."listings.finish_publishing = '' OR
              (".CConfig::get('db.prefix')."listings.finish_publishing != '0000-00-00 00:00:00' AND ".CConfig::get('db.prefix')."listings.finish_publishing >= '".LocalTime::currentDateTime()."')
            )";
        }else if($type == 'expired' || $type == 'listing_expired'){
            $condition .= " (
                ".CConfig::get('db.prefix')."listings.finish_publishing != '0000-00-00 00:00:00' AND
                ".CConfig::get('db.prefix')."listings.finish_publishing != '' AND
                ".CConfig::get('db.prefix')."listings.finish_publishing < '".LocalTime::currentDateTime()."'
            )";
        }

        return $condition;
    }
}
