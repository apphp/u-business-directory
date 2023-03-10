<?php
/**
 * Business directory categories controller
 * This controller intended to both Backend and Frontend modes
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct              _checkCategoryAccess
 * indexAction              _prepareSubCategoryCounts
 * addAction                _prepareParentCategoryArray
 * editAction               _prepareSubTabs
 * deleteAction             _getListingsCategory
 * viewAction               _prepareListingsCounts
 * manageAction             _preparePlanNames
 * manageListingsAction     _prepareSortingListingsCategory
 *                          _getRegionNames
 */

class CategoriesController extends CController
{
    /**
     * Class default constructor
     */
    public function __construct()
    {
        parent::__construct();
        // block access if the module is not installed
        if(!Modules::model()->exists("code = 'directory' AND is_installed = 1")){
            if(CAuth::isLoggedInAsAdmin()){
                $this->redirect('modules/index');
            }else{
                $this->redirect('index/index');
            }
        }

        if(CAuth::isLoggedInAsAdmin()){
            // set meta tags according to active busines directory categories
            Website::setMetaTags(array('title'=>A::t('directory', 'Categories Management')));
            // set backend mode
            //Website::setBackend();

            $this->_view->actionMessage = '';
            $this->_view->errorField = '';

            $this->_view->tabs = DirectoryComponent::prepareTabs('categories');
        }
    }

    /**
     * Controller default action handler
     * @return void
     */
    public function indexAction()
    {
        if(CAuth::isLoggedInAsAdmin()){
            $this->redirect('categories/manage');
        }else{
            $this->redirect('Home/index');
        }
    }

    /**
     * Controller view category description and sub-categories
     * @param int $id the category ID
     * @return void
     */
    public function viewAction($id)
    {
        $category = $this->_checkCategoryAccess($id);
        $this->_view->id = $category->id;
        $this->_view->nameCategory = $category->name;
        $this->_view->description = $category->description;
        $this->_view->parentCategories = $this->_prepareParentCategoryArray($category->parent_id);
        $this->_view->markers = DirectoryComponent::printCategoryMarkers($id);
        $this->_view->listingsCategory = $this->_prepareSortingListingsCategory($id);
        $categoryContent = DirectoryComponent::prepareCategories($id);
        $this->_view->contentHtml = $categoryContent['html'];
        $this->_view->contentCss = $categoryContent['css'];
        $this->_view->render('categories/view');
    }

    /**
     * Manage action handler
     * @param int $parentId default 0
     * @return void
     */
    public function manageAction($parentId = 0)
    {
        Website::setBackend();
        Website::prepareBackendAction('manage', 'directory', 'categories/manage');

        $alert = A::app()->getSession()->getFlash('alert');
        $listingsCounts = '';

        if(!empty($alert)){
            $this->_view->actionMessage = CWidget::create('CMessage', array('success', $alert, array('button'=>true)));
        }
        $this->_view->parentId = (int)$parentId;
        $this->_view->ListingsCounts = array();
        $this->_prepareSubTabs($parentId);

        $prepareCategories = array();
        $categories = Categories::model()->findAll('parent_id = :parent_id', array('i:parent_id'=>$parentId));
        if($categories){
            foreach($categories as $category){
                $prepareCategories[] = $category['id'];
            }
            $listingsCounts      = $this->_prepareListingsCounts($prepareCategories);
        }

        $this->_view->listingsCounts      = $listingsCounts;
        $this->_view->subCategoriesCounts = $this->_prepareSubCategoryCounts($parentId);
        $this->_view->render('categories/manage');
    }

    /**
     * Add new action handler
     * @param int $parentId the id parent category, default 0
     * @return void
     */
    public function addAction($parentId = 0)
    {
        Website::setBackend();
        Website::prepareBackendAction('add', 'directory', 'categories/manage');
        $this->_view->parentName = '';

        $result = Categories::model()->find(CConfig::get('db.prefix').'categories.`id`=:id', array(':id' => (int)$parentId));
        if($result){
            $this->_view->parentName = $result->name;
        }
        $this->_view->parentId = (int)$parentId;

        $this->_prepareSubTabs($parentId);
        $this->_view->render('categories/add');
    }

    /**
     * Edit business directory categories action handler
     * @param int $id default 0
     * @parm string $deleteIcon if equally 'icon', then delete icon files; if equally 'iconmap' delete icon_map
     * @return void
     */
    public function editAction($id = 0, $deleteIcon = '')
    {
        Website::setBackend();
        Website::prepareBackendAction('edit', 'directory', 'categories/manage');
        $category = $this->_checkCategoryAccess((int)$id);
        $id = $category->id;
        $parentId = $category->parent_id;
        $alert = '';
        $alertType = '';
        // Delete the icon file
        if($deleteIcon === 'icon' || $deleteIcon === 'iconmap'){
            if($deleteIcon === 'icon'){
                $icon = $category->icon;
                $iconPath = 'images/modules/directory/categories/'.$icon;
                $category->icon = '';
                $conditions = '`icon` = :icon';
                $params = array(':icon'=> $icon);
            }else if($deleteIcon === 'iconmap'){
                $iconMap = $category->icon_map;
                $iconPath = 'images/modules/directory/categories/mapicons/'.$iconMap;
                $category->icon_map = '';
                $conditions = '`icon_map` = :icon_map';
                $params = array(':icon_map'=> $iconMap);
            }

            // Save the changes in admins table
            if($category->save()){
                if($category->find($conditions, $params)){
                    // The files use other Categories
                    $alert = A::t('directory', 'Icon successfully deleted');
                    $alertType = 'success';
                }else{
                    // Delete the files. If deleteThumb == true, then delete image thumb file
                    if(CFile::deleteFile($iconPath)){
                        $alert = A::t('directory', 'Icon successfully deleted');
                        $alertType = 'success';
                    }else{
                        $alert = A::t('directory', 'Category Icon Delete Warning');
                        $alertType = 'warning';
                    }
                }
            }else{
                $alert = A::t('directory', 'Category Delete Error');
                $alertType = 'error';
            }

            if(!empty($alert)){
                $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
            }
        }
        $parentNames = array(0 => '');
        $allCategories = Categories::model()->find();
        foreach($allCategories as $oneCategory){
            if(0 == $oneCategory['parent_id']){
                $parentNames[$oneCategory['id']] = $oneCategory['name'];
            }
        }
        $arrayParentId = array_keys($parentNames);
        foreach($allCategories as $oneCategory){
            if(in_array($oneCategory['parent_id'], $arrayParentId)){
                $parentNames[$oneCategory['id']] = $oneCategory['name'];
            }
        }

        $this->_view->id = $id;
        $this->_view->parentId = $parentId;
        $this->_view->parentNames = $parentNames;
        $this->_prepareSubTabs($parentId);
        $this->_view->render('categories/edit');
    }

    /**
     * Delete business directory categories action handler
     * @param int $id default 0
     * @return void
     */
    public function deleteAction($id = 0)
    {
        Website::setBackend();
        Website::prepareBackendAction('delete', 'directory', 'categories/manage');
        $issetSubCategories = Categories::model()->find('`parent_id`= :id', array(':id' => $id)) ? true : false;
        $category = $this->_checkCategoryAccess($id);
        $parentId = $category->parent_id;
        $alert = '';
        $alertType = '';

        if($issetSubCategories){
            $alert = A::t('directory', 'You cannot delete this category');
            $alertType = 'error';
        }else{
            $icon           = $category->icon;
            $iconMap        = $category->icon_map;
            $iconPath       = $icon ? 'images/modules/directory/categories/'.$icon : '';
            $iconMapPath    = $iconMap ? 'images/modules/directory/categories/mapicons/'.$iconMap : '';

            if($category->delete()){
                ListingsCategories::model()->deleteAll('category_id = :category_id', array('i:category_id' => $id));
                if($category->find('`icon` = :icon OR `icon_map` = :icon_map', array(':icon'=> $icon, ':icon_map' => $iconMap))){
                    // The files use other Categories
                    $alert = A::t('directory', 'Category successfully deleted');
                    $alertType = 'success';
                }else{
                    $errorDeletImage = false;
                    // Delete the files icon
                    if($iconPath){
                        if(!CFile::deleteFile($iconPath)){
                            $errorDeletImage = true;
                        }
                    }
                    // Delete the files map icon
                    if($iconMapPath){
                        if(!CFile::deleteFile($iconMapPath)){
                            $errorDeletImage = true;
                        }
                    }
                    if(!$errorDeletImage){
                        $alert = A::t('directory', 'Category successfully deleted');
                        $alertType = 'success';
                    }else{
                        $alert = A::t('directory', 'Category Icon Delete Warning');
                        $alertType = 'warning';
                    }
                }
            }else{
                if(APPHP_MODE == 'demo'){
                    $alert = CDatabase::init()->getErrorMessage();
                    $alertType = 'warning';
                }else{
                    $alert = A::t('directory', 'Category Delete Error');
                    $alertType = 'error';
                }
            }
        }
        if(!empty($alert)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }
        $this->_view->parentId = $parentId;
        $this->_prepareSubTabs($parentId);
        $subCategoriesCounts = $this->_prepareSubCategoryCounts($parentId);
        $this->_view->listingsCounts      = $this->_prepareListingsCounts($subCategoriesCounts);
        $this->_view->subCategoriesCounts = $subCategoriesCounts;
        $this->_view->render('categories/manage');
    }

    /**
     * Manage listings in category
     * @param int $categoriesId the categories ID
     * @return void
     */
    public function manageListingsAction($categoryId)
    {
        Website::setBackend();
        $category = $this->_checkCategoryAccess($categoryId);

        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');
        $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));

        $this->_view->categoryName     = $category->name;
        $this->_view->categoryId       = $category->id;
        $this->_view->categoryParentId = $category->parent_id;
        $this->_view->dateTimeFormat   = Bootstrap::init()->getSettings()->datetime_format;
        $this->_view->regionNames      = $this->_getRegionNames();
        $this->_preparePlanNames();
        $this->_view->render('categories/manageListings');
    }

    /**
     * Check if passed category ID is valid
     * @param int $id default 0
     * @return Categories
     */
    private function _checkCategoryAccess($id = 0)
    {
        $category = Categories::model()->findByPk($id);
        if(!$category){
            $this->redirect('categories/manage');
        }
        return $category;
    }

    /**
     * Prepares array of total counts for each sub-category
     * @param int $parentId the id parent category; default 0
     * @return array
     */
    private function _prepareSubCategoryCounts($parentId = 0)
    {
        $result = Categories::model()->count(array(
                'condition'=>'parent_id IN (SELECT `id` FROM `'.CConfig::get('db.prefix').'categories` WHERE `parent_id`= :parent_id)',
                'select'=>'parent_id',
                'count'=>'*',
                'groupBy'=>'parent_id',
                'allRows'=>true
            ),
            array(':parent_id' => $parentId)
        );

        $subCategoriesCounts = array();
        foreach($result as $model){
            $subCategoriesCounts[$model['parent_id']] = $model['cnt'];
        }
        return $subCategoriesCounts;
    }

    /**
     * Prepares array of total counts for each listings
     * @param categories $categories the array ID categories
     * @return array
     */
    private function _prepareListingsCounts($categories = array())
    {
        $listingsCounts = array();
        if(!empty($categories) && is_array($categories)){

            $params = array();
            foreach($categories as $category){
                $params[':category_id_'.(int)$category] = (int)$category;
            }
            if(count($params) == 1){
                $condition = 'category_id = '.implode(',',array_keys($params));
            }else{
                $condition = 'category_id IN ('.implode(',',array_keys($params)).')';
            }
            ListingsCategories::model()->setTypeRelations('none');
            $result = ListingsCategories::model()->count(array(
                    'condition'=>$condition,
                    'select'=>'category_id',
                    'count'=>'*',
                    'groupBy'=>'category_id',
                    'allRows'=>true
                ),
                $params
            );
            ListingsCategories::model()->resetTypeRelations();

            foreach($result as $model){
                $listingsCounts[$model['category_id']] = $model['cnt'];
            }
        }
        return $listingsCounts;
    }


    /**
     * Prepares variables $advertisePlanNames, $advertisePlanDefault (in View)
     * @return void
     */
    private function _preparePlanNames()
    {
        $advertisePlans = Plans::model()->findAll();
        $advertisePlanNames = array();
        $advertisePlanDefault = '';
        foreach($advertisePlans as $advertisePlan){
            $advertisePlanNames[$advertisePlan['id']] = $advertisePlan['name'];
            if(1 == $advertisePlan['is_default']){
                $advertisePlanDefault = $advertisePlan['id'];
            }
        }
        $this->_view->advertisePlanNames = $advertisePlanNames;
        $this->_view->advertisePlanDefault = $advertisePlanDefault;
    }

    /**
     * Prepares variable $locationNames (in View)
     * @return array
     */
    private function _getRegionNames()
    {
        $regions = Regions::model()->findAll('parent_id=0');
        $regionNames = array(''=>'');
        foreach($regions as $region){
            $regionNames[$region['id']] = $region['name'];
        }
        return $regionNames;
    }

    /**
    * Prepare array parents categories, start from $parentId
    * @param int $parentId the id start parent, default 0
    * @return array
    */
    private function _prepareParentCategoryArray($parentId = 0)
    {
        $parentCategories = array();
        if(0 != $parentId && is_numeric($parentId)){
            $subTabs = '';

            do{
                if($result = Categories::model()->find(CConfig::get('db.prefix').'categories.`id`= :id', array(':id' => $parentId))){
                    $parentCategory = array('id' => $result->id, 'name' => $result->name);
                    array_unshift($parentCategories, $parentCategory);
                    $parentId = $result->parent_id;
                }
            }while($parentId != 0);
        }

        return $parentCategories;
    }

    /*
     * Prepares variables $subTabs, $parentCategoryCount and $parentCategories (in View)
     * @param int $parentId the id parent category
     * @param boolen $setActive this flag specifies the set class 'active'; if set in false then class always will 'previous'
     * @return void
     * */
    private function _prepareSubTabs ($parentId = 0, $setActive = true)
    {
        $this->_view->parentCategoryCount = 0;
        $this->_view->parentCategories = array();
        $this->_view->subTabs = '';

        if(($parentCategories = $this->_prepareParentCategoryArray($parentId)) !== array()){
            $subTabs = '';
            $parentCategoryCount = count($parentCategories);
            if($parentCategoryCount){
                for($i = 0; $i < $parentCategoryCount; $i++){
                    $subTabs .=  '<a class="sub-tab '.($setActive !== false && $parentCategoryCount-1 == $i ? 'active' : 'previous').'" href="categories/manage/parentId/'.$parentCategories[$i]['id'].'">'.$parentCategories[$i]['name'].'</a>'.($parentCategoryCount-1 == $i ? '' : '&raquo; ');
                }
            }
            $this->_view->parentCategoryCount = $parentCategoryCount;
            $this->_view->parentCategories = $parentCategories;
            $this->_view->subTabs = $subTabs;
        }
    }

    /**
     * Prepare Listings Categories considering the sort fields
     * @return void
     */
    private function _prepareSortingListingsCategory($categoryId = 0)
    {
        $category = $this->_checkCategoryAccess($categoryId);

        $cRequest       = A::app()->getRequest();
        $alert          = '';
        $alertType      = '';
        $pagination     = '';
        $startEndItem   = 1;
        $countListings  = 0;
        $listings       = array();
        $validError     = false;

        $this->_view->actionMessage = '';

        $search     = $cRequest->getQuery('s',          'string', '');
        $count      = $cRequest->getQuery('count',      'integer', 10);
        $sortBy     = $cRequest->getQuery('sortBy',     'string', '');
        $sort       = $cRequest->getQuery('sort',       'integer', 0);
        $page       = $cRequest->getQuery('page',       'integer', 1);

        $sortFields = array('date_published'=>A::t('directory', 'Date Published'), 'finish_publishing'=>A::t('directory', 'Finish Published'), 'business_name'=>A::t('directory', 'Name'));
        $sortType   = array('ASC', 'DESC');
        $keySortFields = array_keys($sortFields);
        $keySortType = array_keys($sortType);
        $sortBy     = empty($sortBy) ? key($sortFields) : $sortBy;

        if((empty($search) && !CValidator::isText($search)) ||
           (empty($count) && !CValidator::isInteger($count)) ||
           (empty($sortBy) && (!is_string($sortBy) || !isset($keySortFields[$sortBy]))) ||
           (empty($sort) && (!CValidator::isInteger($sort) || !isset($keySortType[$sort]))) ||
           (empty($page) && !CValidator::isInteger($page))
        ){
            $validError = true;
        }

        $result = CWidget::create('CFormValidation', array(
            'fields'=>array(
                's'         =>array('title'=>A::t('directory', 'Search keyword...'),'validation'=>array('required'=>false, 'type'=>'text')),
                'count'     =>array('title'=>A::t('directory', 'Count'),            'validation'=>array('required'=>false, 'type'=>'integer')),
                'sortBy'    =>array('title'=>A::t('directory', 'Sort by'),          'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($sortFields))),
                'sort'      =>array('title'=>A::t('directory', 'Sort'),             'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($sortType))),
                'page'      =>array('title'=>A::t('directory', 'Page'),             'validation'=>array('required'=>false, 'type'=>'integer')),
            ),
            'messagesSource'=>'core',
            'showAllErrors'=>false,
        ));

        if($validError){
            $alert     = A::t('directory', 'You do not have entered the correct settings');
            $alertType = 'validation';
        }else{

            $condition  = DirectoryComponent::getListingCondition('not_expired');
            $condition .= ' AND category_id = :category_id AND ';
            $params     = array('i:category_id' => $categoryId);

            if(!empty($search)){
                //$condition .= "(`business_name` LIKE '%".$name."%' OR `business_description` LIKE '%".$name."%') AND ";
                $condition .= "`business_name` LIKE '%".CHtml::encode($search)."%' AND ";
            }

            $condition .= "`is_published` = 1 AND `is_approved` = 1 AND `access_level` <= ".(CAuth::isLoggedIn() ? 1 : 0);

            $countListings = ListingsCategories::model()->count(array('condition'=>$condition,'count'=>'DISTINCT `'.CConfig::get('db.prefix').'listings_categories`.`listing_id`'), $params);

            if(!empty($countListings)){
                $limit = (($page - 1) * $count).', '.($count);
                $orderBy = 'is_featured DESC, '.$sortBy.' '.$sortType[$sort].', `'.CConfig::get('db.prefix').'listings_categories`.`listing_id`';

                $listings = ListingsCategories::model()->findAll(array('condition'=>$condition, 'limit'=>$limit, 'orderBy'=>$orderBy, 'groupBy'=>'`'.CConfig::get('db.prefix').'listings_categories`.`listing_id`'), $params);

                $startItem = (($page - 1) * $count) + 1;
                $endItem = $page * $count;
                $endItem = $endItem > $countListings ? $countListings : $endItem;
                $startEndItem = ($startItem != 1 ? $startItem.' - '.$endItem : $endItem);
            }else{
                $alert = A::t('directory', 'Sorry, but search did not found');
                $alertType = 'info';
            }

            if($countListings > $count){
                $paginationUrl  = $count ? '?count='.$count : '';
                $paginationUrl .= $sortBy ? ($paginationUrl ? '&' : '?').'sortBy='.$sortBy : '';
                $paginationUrl .= $sort ? ($paginationUrl ? '&' : '?').'sort='.$sort : '';
                $paginationUrl .= $search ? ($paginationUrl ? '&' : '?').'s='.$search : '';
                $paginationUrl  = 'listings/searchListings'.$paginationUrl;

                $pagination = CWidget::create('CPagination', array(
                    'actionPath'   => $paginationUrl,
                    'currentPage'  => $page,
                    'pageSize'     => $count,
                    'totalRecords' => $countListings,
                    'linkType' => 0,
                    'paginationType' => 'fullNumbers',
                    'showResultsOfTotal' => false
                ));
            }
        }

        if($alert){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array()));
        }

        $this->_view->startEndItem  = $startEndItem;
        $this->_view->search        = $search;
        $this->_view->countListings = $countListings;
        $this->_view->count         = $count;
        $this->_view->sortBy        = $sortBy;
        $this->_view->sortType      = $sort;
        $this->_view->sortFields    = $sortFields;
        $this->_view->listings      = $listings;
        $this->_view->pagination    = $pagination;
    }
}
