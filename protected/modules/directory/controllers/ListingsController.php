<?php
/**
 * Listings controller
 * This controller intended to both Backend and Frontend modes
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct              _checkListingAccess
 * indexAction              _checkListingFrontendAccess
 * manageAction             _prepareCategoryCounts
 * viewAction               _prepareListingCategories
 * addAction                _preparePlanNames
 * editAction               _prepareRegionNames
 * deleteAction             _prepareParentCategoryArray
 * addCategoryAction        _prepareViewListingFields
 * deleteCategoryAction     _prepareCustomerFullNames
 * manageCategoriesAction   _getCodeSmallMap
 * publishedStatusAction    _updateFeed
 * searchListingsAction
 * addListingAction
 * editListingAction
 * deleteListingAction
 * myListingsAction
 * addMyCategoriesAction
 * manageMyCategoriesAction
 * approvedStatusAction
 *
 */

class ListingsController extends CController
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
            // set meta tags according to active business directory listings
            Website::setMetaTags(array('title'=>A::t('directory', 'Listings Management')));

            $this->_view->actionMessage = '';
            $this->_view->errorField = '';

            $this->_view->tabs = DirectoryComponent::prepareTabs('listings');
        }

        $this->_view->dateTimeFormat  = Bootstrap::init()->getSettings()->datetime_format;
    }

    /**
     * Controller default action handler
     * @return void
     */
    public function indexAction()
    {
        if(CAuth::isLoggedInAsAdmin()){
            $this->redirect('listings/manage');
        }else{
            $this->redirect('Home/index');
        }
    }

    /**
     * Manage action handler
     * @param string $typeTab the type sub-tab (pending or approved)
     * @return void
     */
    public function manageAction($typeTab = 'all')
    {
        // set backend mode
        Website::setBackend();
        Website::prepareBackendAction('manage', 'directory', 'listings/manage');

        $typeTab = in_array($typeTab, array('approved', 'expired', 'pending', 'canceled')) ? $typeTab : 'all';
        $cRequest = A::app()->getRequest();
        $filter = $cRequest->getQuery('but_filter', 'string');
        $isFilter = strtolower($filter) == 'filter' ? true : false;
        $getUrl = '';

        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');

        if(!empty($alert)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }

        // Save settings filter
        if($isFilter){
            $name = $cRequest->getQuery('business_name', 'string');
            $plan = $cRequest->getQuery('advertise_plan_id', 'int');
            $customer = $cRequest->getQuery('customer_id', 'int');
            $region = $cRequest->getQuery('region_id', 'int');
            $published = $cRequest->getQuery('is_published', 'int');
            $featured = $cRequest->getQuery('is_featured', 'int');

            $getUrl = $name || $plan || $region || $published || $featured
                ? '?'.($name ? 'business_name='.$name.'&' : '')
                    .($plan ? 'advertise_plan_id='.$plan.'&' : '')
                    .($customer ? 'customer_id='.$customer.'&' : '')
                    .($region ? 'region_id='.$region.'&' : '')
                    .($published ? 'is_published='.$published.'&' : '')
                    .($featured ? 'is_featured='.$featured.'&' : '')
                    .'but_filter=Filter'
                : '';
        }

        $condition = DirectoryComponent::getListingCondition('not_expired');
        if($typeTab == 'pending'){
            $condition = CConfig::get('db.prefix').'listings.is_approved = 0 AND '.$condition;
        }else if($typeTab == 'approved'){
            $condition = CConfig::get('db.prefix').'listings.is_approved = 1 AND '.$condition;
        }else if($typeTab == 'canceled'){
            $condition = CConfig::get('db.prefix').'listings.is_approved = 2 AND '.$condition;
        }else if($typeTab == 'expired'){
            $condition = DirectoryComponent::getListingCondition('expired');
        }else{
            $condition = '';
        }


        $this->_view->categoryCounts = $this->_prepareCategoryCounts();
        $this->_view->typeTab = $typeTab;
        $this->_view->getUrl = $getUrl;
        $this->_view->condition = $condition;
        $this->_preparePlanNames();
        $this->_prepareRegionNames();
        $this->_view->render('listings/manage');
    }

    /**
     * Controller view listings description
     * @param int $id the listing ID
     * @return void
     */
    public function viewAction($id = 0)
    {
        $id = (int)$id;
        $countShowReview   = 20;
        $customerLogin     = false;
        $customer          = '';
        $showFormReview    = true;
        $showViewMore      = false;
        $categoriesListing = array();
        $actionMessage     = '';
        $condition         = '';

        if(!CAuth::isLoggedInAsAdmin()){
            // 0 - Public, 1 - Registered
            $accessLevel = (int)CAuth::isLoggedIn();
            $condition   = DirectoryComponent::getListingCondition('not_expired');
            $condition   = 'is_published = 1 AND is_approved = 1 AND access_level <= '.$accessLevel.' AND '.$condition;

            $accountId = CAuth::getLoggedId();
            if(!empty($accountId)){
                $customer = Customers::model()->find('account_id = :account_id', array(':account_id'=>$accountId));
                if(!empty($customer)){
                    $customerLogin = true;
                    $condition = '(('.CConfig::get('db.prefix').'listings.customer_id = '.(int)$customer->id.') OR ('.$condition.'))';
                }
            }
        }

        $listing = Listings::model()->findByPk($id, $condition);
        if(empty($listing)){
            $this->redirect('home/index');
        }

        $listingId = $listing->id;

        // If see the Creator, not display form 'Send Review'
        $showFormReview = ($listing->is_approved == 1 && $listing->status_expired == 0 && $listing->is_published) ? true : false;

        // Join table categories to listings_categories
        ListingsCategories::model()->setTypeRelations('categories');
        $categoriesListing = ListingsCategories::model()->findAll(CConfig::get('db.prefix').'listings_categories.listing_id = :listing_id', array('i:listing_id'=>$listingId));
        if(!$customerLogin && (!is_array($categoriesListing) || empty($categoriesListing))){
            $this->redirect('home/index');
        }
        ListingsCategories::model()->resetTypeRelations();

        $condition = CConfig::get('db.prefix').'reviews.listing_id = :listing_id';
        $condition .= ' AND is_public = 1';

        $reviews = Reviews::model()->findAll(array('condition'=>$condition, 'orderBy'=>'created_at DESC', 'limit'=>($countShowReview+1)), array(':listing_id'=>$listingId));
        // Show button 'View More' if reviews more than 20
        if(is_array($reviews) && count($reviews) == ($countShowReview+1)){
            // Remove 21 Review
            array_pop($reviews);
            $showViewMore = true;
        }
        // If the customer has left a review, not show form 'Send Review'
        if($customerLogin && $showFormReview){
            $cusomerReview = Reviews::model()->find('customer_id = :customer_id AND '.CConfig::get('db.prefix').'reviews.listing_id = :listing_id', array(':customer_id'=>$customer->id, ':listing_id'=>$listingId));
            if(!empty($cusomerReview)){
                $showFormReview = false;
                $actionMessage = CWidget::create('CMessage', array('info', A::t('directory', 'You\'ve already rate this listing')));
                if($cusomerReview->is_public == 0){
                    $actionMessage .= CWidget::create('CMessage', array('info', A::t('directory', 'Your review will be published after approval of administration')));
                }
            }
        }

        $smallMap = $this->_getCodeSmallMap($listingId);
        $parentCategoryId = !empty($categoriesListing) ? $categoriesListing[0]['category_id'] : 0;

        $this->_view->actionMessage     = $actionMessage;
        $this->_view->categoriesListing = $categoriesListing;
        $this->_view->customerLogin     = $customerLogin;
        $this->_view->listingId         = $listingId;
        $this->_view->listingKeywords   = $listing->keywords;
        $this->_view->listingName       = $listing->business_name;
        $this->_view->showFormReview    = $showFormReview;
        $this->_view->showViewMore      = $showViewMore;
        $this->_view->smallMap          = $smallMap;
        $this->_view->reviews           = $reviews;
        $this->_view->listingExpired    = $listing->status_expired == 1 ? true : false;
        $this->_view->listingPending    = $listing->is_approved == 0 ? true : false;
        $this->_view->listingCanceled   = $listing->is_approved == 2 ? true : false;

        $this->_prepareViewListingFields($listing);

        $this->_view->parentCategories = $this->_prepareParentCategoryArray($parentCategoryId);
        $this->_view->render('listings/view');
    }

    /**
     * Add new action handler
     * @param string $typeTab the type sub-tab ('approved', 'pending', 'expired' and 'all')
     * @return void
     */
    public function addAction($typeTab='')
    {
        // set backend mode
        Website::setBackend();
        Website::prepareBackendAction('add', 'directory', 'listings/manage');
        $cRequest = A::app()->getRequest();

        if($cRequest->isPostRequest()){
            $region = $cRequest->getPost('region_id');
            $subregion = $cRequest->getPost('subregion_id');
            $this->_view->region = !empty($region) ? $region : 0;
            $this->_view->subregion = !empty($subregion) ? $subregion : 0;
        }else{
            $this->_view->region = 0;
            $this->_view->subregion = 0;
        }
        $subRegionNames = array(0=>'--');
        if(!empty($region)){
            $listSubRegions = Regions::model()->findAll('parent_id = :parent_id', array('i:parent_id'=>$this->_view->region));
            foreach($listSubRegions as $subRegion){
                $subRegionNames[$subRegion['id']] = $subRegion['name'];
            }
        }

        $typeTab = in_array($typeTab, array('approved', 'expired', 'pending', 'canceled')) ? $typeTab : 'all';
        $this->_view->typeTab = $typeTab;
        $this->_view->subRegionNames = $subRegionNames;
        $this->_prepareCustomerFullNames();
        $this->_prepareRegionNames();
        $this->_preparePlanNames();
        $this->_updateFeed();
        $this->_view->render('listings/add');
    }

    /**
     * Edit action handler
     * @param int $id
     * @param string $typeTab
     * @param string $deleteImage the type image delete ('image|image2|image3|image4')
     * @return void
     */
    public function editAction($id = 0, $typeTab = '', $deleteImage = '')
    {
        // set backend mode
        Website::setBackend();
        Website::prepareBackendAction('edit', 'directory', 'listings/manage');
        $cRequest = A::app()->getRequest();
        $listing = $this->_checkListingAccess($id);

        $typeTab = in_array($typeTab, array('approved', 'expired', 'pending', 'canceled')) ? $typeTab : 'all';

        if($cRequest->isPostRequest()){
            $region = $cRequest->getPost('region_id');
            $subregion = $cRequest->getPost('subregion_id');
            $this->_view->region = !empty($region) ? $region : 0;
            $this->_view->subregion = !empty($subregion) ? $subregion : 0;
        }else{
            $region = $listing->region_id;
            $this->_view->region = $region;
            $this->_view->subregion = $listing->subregion_id;
        }

        $subRegionNames = array(0=>'--');
        if(!empty($region)){
            $listSubRegions = Regions::model()->findAll('parent_id = :parent_id', array('i:parent_id'=>$this->_view->region));
            foreach($listSubRegions as $subRegion){
                $subRegionNames[$subRegion['id']] = $subRegion['name'];
            }
        }

        $customerName = '';
        $customersList = array();
        $customer = Customers::model()->findByPk($listing->customer_id);
        if(!empty($customer)){
            $customerName = $customer->first_name.' '.$customer->last_name;
            $customersList[$customer->id] = $customerName;
        }

        // Delete the icon file
        if($deleteImage){
            $deleteFlag = false;
            if($deleteImage === 'image'){
                $deleteFlag = true;
                $image = $listing->image_file;
                $imageThumb = $listing->image_file_thumb;
                $listing->image_file = '';
                $listing->image_file_thumb = '';
            }else if($deleteImage === 'image2'){
                $deleteFlag = true;
                $image = $listing->image_1;
                $imageThumb = $listing->image_1_thumb;
                $listing->image_1 = '';
                $listing->image_1_thumb = '';
            }else if($deleteImage === 'image3'){
                $deleteFlag = true;
                $image = $listing->image_2;
                $imageThumb = $listing->image_2_thumb;
                $listing->image_2 = '';
                $listing->image_2_thumb = '';
            }else if($deleteImage === 'image4'){
                $deleteFlag = true;
                $image = $listing->image_3;
                $imageThumb = $listing->image_3_thumb;
                $listing->image_3 = '';
                $listing->image_3_thumb = '';
            }

            if(!empty($image) && !empty($imageThumb)){
                $imagePath = 'images/modules/directory/listings/'.$image;
                $imageThumbPath = 'images/modules/directory/listings/thumbs/'.$imageThumb;
            }

            if($deleteFlag)
            {
                // Save the changes in admins table
                if($listing->save()){
                    // Delete the files
                    if(!empty($imagePath) && CFile::deleteFile($imagePath) && !empty($imageThumbPath) && CFile::deleteFile($imageThumbPath)){
                        $alert = A::t('directory', 'Image successfully deleted');
                        $alertType = 'success';
                    }else{
                        $alert = A::t('directory', 'Listing Image Delete Warning');
                        $alertType = 'warning';
                    }
                }else{
                    $alert = A::t('directory', 'Listing Delete Error');
                    $alertType = 'error';
                }
            }else{
                $alert = A::t('directory', 'Input incorrect parameters');
                $alertType = 'error';
            }

            if(!empty($alert)){
                $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
            }
        }

        $this->_view->typeTab = $typeTab;
        $this->_view->subRegionNames = $subRegionNames;
        $this->_view->id = $listing->id;
        $this->_view->customerName = $customerName;
        $this->_view->customersList = $customersList;
        $this->_prepareCustomerFullNames();
        $this->_prepareRegionNames();
        $this->_preparePlanNames();
        $this->_updateFeed();
        $this->_view->render('listings/edit');
    }

    /**
     * Delete action handler
     * @param int $id
     * @param string $typeTab ('all', 'pending', 'approved', 'expired')
     * @return void
     */
    public function deleteAction($id = 0, $typeTab = '')
    {
        // Set backend mode
        Website::setBackend();
        Website::prepareBackendAction('delete', 'directory', 'listings/manage');
        $listing = $this->_checkListingAccess($id);
        $typeTab = in_array($typeTab, array('approved', 'expired', 'pending', 'canceled')) ? $typeTab : 'all';
        $alert = '';
        $alertType = '';

        $image = $listing->image_file;
        $imageThumb = $listing->image_file_thumb;
        $image1 = $listing->image_1;
        $image1Thumb = $listing->image_1_thumb;
        $image2 = $listing->image_2;
        $image2Thumb = $listing->image_2_thumb;
        $image3 = $listing->image_3;
        $image3Thumb = $listing->image_3_thumb;
        $imagePath = 'images/modules/directory/listings/';
        $imageThumbPath = 'images/modules/directory/listings/thumbs/';

        if($listing->delete()){
            // Delete the files
            if(
                ($image ? CFile::deleteFile($imagePath.$image) : true) &&
                ($imageThumb ? CFile::deleteFile($imageThumbPath.$imageThumb) : true) &&
                ($image1 ? CFile::deleteFile($imagePath.$image1) : true) &&
                ($image1Thumb ? CFile::deleteFile($imageThumbPath.$image1Thumb) : true) &&
                ($image2 ? CFile::deleteFile($imagePath.$image2) : true) &&
                ($image1Thumb ? CFile::deleteFile($imageThumbPath.$image2Thumb) : true) &&
                ($image3 ? CFile::deleteFile($imagePath.$image3) : true) &&
                ($image1Thumb ? CFile::deleteFile($imageThumbPath.$image3Thumb) : true)
            ){
                $alert = A::t('directory', 'Listing successfully deleted');
                $alertType = 'success';
            }else{
                $alert = A::t('directory', 'Listing Image Delete Warning');
                $alertType = 'warning';
            }
            ListingsCategories::model()->deleteAll('listing_id = :listing_id', array('i:listing_id' => $id));
        }else{
            if(APPHP_MODE == 'demo'){
                $alert = CDatabase::init()->getErrorMessage();
                $alertType = 'warning';
            }else{
                $alert = A::t('directory', 'Listing Delete Error');
                $alertType = 'error';
            }
        }

        $condition = DirectoryComponent::getListingCondition('not_expired');
        if($typeTab == 'pending'){
            $condition = CConfig::get('db.prefix').'listings.is_approved = 0 AND '.$condition;
        }else if($typeTab == 'approved'){
            $condition = CConfig::get('db.prefix').'listings.is_approved = 1 AND '.$condition;
        }else if($typeTab == 'canceled'){
            $condition = CConfig::get('db.prefix').'listings.is_approved = 2 AND '.$condition;
        }else if($typeTab == 'expired'){
            $condition = DirectoryComponent::getListingCondition('expired');
        }else{
            $condition = '';
        }


        if(!empty($alert)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }
        $this->_view->typeTab = $typeTab;
        $this->_view->allCategories = DirectoryComponent::getAllCategoriesArray();
        $this->_view->categoryCounts = $this->_prepareCategoryCounts();
        $this->_view->condition = $condition;
        $this->_view->getUrl = '';
        $this->_preparePlanNames();
        $this->_prepareRegionNames();
        $this->_updateFeed();
        $this->_view->render('listings/manage');
    }

    /**
     * Manage add and delete categories in listing
     * @param int $listingId the listing ID
     * @param string $typeTab
     * @return void
     */
    public function manageCategoriesAction($listingId, $typeTab = 'all')
    {
        // set backend mode
        Website::setBackend();
        Website::prepareBackendAction('manageCategories', 'directory', 'listings/manage');

        $listing = $this->_checkListingAccess($listingId);
        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');
        $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        $typeTab = in_array($typeTab, array('approved', 'expired', 'pending', 'canceled')) ? $typeTab : 'all';
        $displayButtonAdd = true;
        $listingId = $listing->id;
        $categoriesId = array();

        $model = ListingsCategories::model();
        $model->setTypeRelations('none');
        $categories = $model->findAll('listing_id = :listing_id', array('i:listing_id' => $listingId));
        if($categories){
            foreach($categories as $category){
                $categoriesId[] = $category['category_id'];
            }
        }

        $allCategoriesId = array_keys(DirectoryComponent::getAllCategoriesArray());
        if(!array_diff($allCategoriesId, $categoriesId)){
            $displayButtonAdd = false;
        }
        $this->_view->typeTab = $typeTab;
        $this->_view->displayButtonAdd = $displayButtonAdd;
        $this->_view->categoriesId = $categoriesId;
        $this->_view->listingId = $listing->id;
        $this->_view->listingName = $listing->business_name;
        $this->_view->render('listings/manageCategories');
    }


    /**
     * Delete category in listing action handler
     * @param int $id the listing category ID
     * @param string $typeTab
     * @return void
     */
    public function deleteCategoryAction($id, $typeTab = 'all')
    {
        // set backend mode
        Website::setBackend();
        Website::prepareBackendAction('edit', 'directory', 'listings/manage');
        ListingsCategories::model()->setTypeRelations('none');
        $category = ListingsCategories::model()->findByPk($id);
        $typeTab = in_array($typeTab, array('approved', 'expired', 'pending', 'canceled')) ? $typeTab : 'all';
        $listingId = '';

        if(!empty($category)){
            $listingId = $category->listing_id;
            if($category->delete())
            {
                $alert = A::t('directory', 'Listing Category successfully deleted');
                $alertType = 'success';
            }else{
                if(APPHP_MODE == 'demo'){
                    $alert = CDatabase::init()->getErrorMessage();
                    $alertType = 'warning';
                }else{
                    $alert = A::t('directory', 'Listing Category deleting error');
                    $alertType = 'error';
                }
            }
            $alert = A::app()->getSession()->setFlash('alert', $alert);
            $alertType = A::app()->getSession()->setFlash('alertType', $alertType);
        }
        if($listingId){
            $this->redirect('listings/manageCategories/listingId/'.$listingId.'/type/'.$typeTab);
        }else{
            $this->redirect('listings/manage/type/'.$typeTab);
        }
    }

    /**
     * Add category in listing action handler
     * @param int $listingId the listing ID
     * @param string $typeTab
     * @return void
     */
    public function addCategoryAction($listingId, $typeTab = 'all')
    {
        // set backend mode
        Website::setBackend();
        Website::prepareBackendAction('edit', 'directory', 'listings/manage');
        $optionsAtributs = array();
        $sourceCategoriesId = array();
        $listing = $this->_checkListingAccess($listingId);
        $typeTab = in_array($typeTab, array('approved', 'expired', 'pending', 'canceled')) ? $typeTab : 'all';

        $this->_view->listingId = $listing->id;
        $this->_view->listingName = $listing->business_name;
        $allCategories = DirectoryComponent::getAllCategoriesArray();

        $dbPrefix = CConfig::get('db.prefix');
        $categories = CDatabase::init()->select(
            'SELECT `'.$dbPrefix.'listings_categories`.`category_id`
             FROM `'.$dbPrefix.'listings_categories`
             WHERE `'.$dbPrefix.'listings_categories`.`listing_id`=:id',
            array('i:id' => $listingId));
        if($categories){
            foreach($categories as $category){
                if($allCategories[$category['category_id']])
                {
                    $optionsAtributs[$category['category_id']] = array('disabled'=>'disabled');
                }
            }
            if(empty($allCategories)){
                A::app()->getSession()->setFlash('alert', A::t('directory', 'You cannot add a category'));
                A::app()->getSession()->setFlash('alertType', 'warning');
                $this->redirect('listings/manageCategories/listingId/'.$listingId);
            }else{
                $sourceCategoriesId = array_keys($allCategories);
            }
        }

        $this->_view->typeTab = $typeTab;
        $this->_view->sourceCategoriesId = $sourceCategoriesId;
        $this->_view->optionsAtributs = $optionsAtributs;
        $this->_view->allCategories = $allCategories;
        $this->_view->render('listings/addcategory');
    }

    /**
     * Change status listing action handler
     * @param int $id the menu ID
     * @return void
     */
    public function publishedStatusAction($id, $typeTab = '')
    {
        $typeTab = in_array($typeTab, array('approved', 'expired', 'pending', 'canceled')) ? $typeTab : 'all';

        if(CAuth::getLoggedAvatar()){
            // set backend mode
            Website::setBackend();
            Website::prepareBackendAction('edit', 'directory', 'listings/manage');
            $loginType = 'admin';
            $listing = Listings::model()->findbyPk((int)$id);
        }else if(CAuth::isLoggedIn()){
            Website::setFrontend();
            $accountId = CAuth::getLoggedId();
            $customer = Customers::model()->find('account_id = :account_id', array('account_id'=>$accountId));
            if(empty($customer)){
                $customerController = new CustomersController();
                $customerController->logoutAction();
            }

            $condition = DirectoryComponent::getListingCondition('not_expired');

            $listing = Listings::model()->findByPk($id, 'customer_id = :customer_id AND '.$condition.' AND is_approved = 1', array(':customer_id'=>$customer->id));
            if(empty($listing)){
                A::app()->getSession()->setFlash('alert', A::t('directory', 'Status changing error'));
                A::app()->getSession()->setFlash('alertType', 'error');
                $this->redirect('listings/myListings/typeTab/'.$typeTab);
            }
            $loginType = 'customer';
        }else{
            $this->redirect(Website::getDefaultPage());
        }

        if(!empty($listing)){
            if(Listings::model()->updateByPk($listing->id, array('is_published'=>($listing->is_published == 1 ? '0' : '1')))){
                A::app()->getSession()->setFlash('alert', A::t('directory', 'Status has been successfully changed!'));
                A::app()->getSession()->setFlash('alertType', 'success');
            }else{
                A::app()->getSession()->setFlash('alert', A::t('directory', 'Status changing error'));
                A::app()->getSession()->setFlash('alertType', 'error');
            }
        }
        if($loginType == 'admin'){
            $this->redirect('listings/manage/type/'.$typeTab);
        }else{
            $this->redirect('listings/myListings/type/'.$typeTab);
        }
    }

    /**
     * Change status approved listing action handler
     * @param int $id the menu ID
     * @return void
     */
    public function approvedStatusAction($id, $typeTab = '')
    {
        // set backend mode
        Website::setBackend();
        Website::prepareBackendAction('edit', 'directory', 'listings/manage');

        $listing = Listings::model()->findbyPk((int)$id);

        if(!empty($listing) && $listing->is_approved == 0){
            $datePublished = date('Y-m-d H:i:s');
            $finishPublished = date('Y-m-d H:i:s');
            $adverticePlan = Plans::model()->findByPk($listing->advertise_plan_id);
            if(!empty($adverticePlan)){
                // Change status of Approved
                $duration = $adverticePlan->duration;
                if(!empty($duration) && $duration != '-1'){
                    $finishTime = time() + $duration*24*60*60;
                    $finishPublished = date('Y-m-d H:i:s', $finishTime);
                }else if($duration == -1){
                    $finishPublished = '0000-00-00 00:00:00';
                }
            }
            if(Listings::model()->updateByPk($listing->id, array('is_approved'=>'1', 'date_published'=>$datePublished, 'finish_publishing'=>$finishPublished))){
                $this->_updateFeed();
                $typeTab = in_array($typeTab, array('approved', 'expired', 'pending', 'canceled')) ? $typeTab : 'all';
                A::app()->getSession()->setFlash('alert', A::t('directory', 'Status has been successfully changed!'));
                A::app()->getSession()->setFlash('alertType', 'success');
            }else{
                A::app()->getSession()->setFlash('alert', A::t('directory', 'Status changing error'));
                A::app()->getSession()->setFlash('alertType', 'error');
            }
        }
        $this->redirect('listings/manage/type/'.$typeTab);
    }

    /**
     * Controller to search and output listings
     * @return void
     */
    public function searchListingsAction()
    {
        $cRequest = A::app()->getRequest();
//        $sortingListings = DirectoryComponent::prepareSortingListingsCategory();

        $this->_prepareSortingListingsCategory();
        $listings = $this->_view->listings;
        $this->_view->markers = '';
        // prepare markers
        if(!empty($listings)){
            $categoriesId = array();
            $categoryListings = array();
            $keyListings = array();
            foreach($listings as $key => $listing){
                $categoryId = $listing['category_id'];
                $keyListings[$listing['listing_id']] = $key;
                if(!isset($categoriesId[':category_id_'.$categoryId])){
                    $categoriesId[':category_id_'.$categoryId] = $categoryId;
                    $categoryListings[$categoryId] = array();
                }
                $categoryListings[$categoryId][] = $listing['listing_id'];
            }
            $condition = 'category_id IN ('.implode(',',array_keys($categoriesId)).')';
            $categories = Categories::model()->findAll($condition, $categoriesId);
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
                            $key = $keyListings[$listingId];
                            $listings[$key]['icon_map'] = (!empty($category['icon_map']) ? $category['icon_map'] : $parentIcons[$category['parent_id']]);
                            $listings[$key]['category_id'] = $category['id'];
                        }
                    }
                }
                $this->_view->markers = DirectoryComponent::makeMarkerListings($listings);
                $this->_view->listings = $listings;
            }
        }

        $this->_view->render('listings/searchlistings');
    }

    /**
     * The action handler allows customers to create new listing
     * @param string $typeTab
     * @return void
     */
    public function addListingAction($typeTab = '')
    {
         // block access to this controller for not-logged customers
        CAuth::handleLogin('customers/login', 'customer');
        // set frontend settings
        Website::setFrontend();
        $listingsApproval = ModulesSettings::model()->param('directory', 'listing_approval');
        $duration = 0;
        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');
        $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert));
        $typeTab = in_array($typeTab, array('approved', 'expired', 'pending', 'canceled')) ? $typeTab : 'all';

        $customer = Customers::model()->find('account_id = :account_id', array('i:account_id'=>A::app()->getSession()->get('loggedId')));
        if($customer){
            $cRequest = A::app()->getRequest();

            $advertisePlanId = $cRequest->getPost('advertise_plan_id','integer');
            $defaultRegion = $cRequest->getPost('region_id','integer');
            $defaultSubRegion = $cRequest->getPost('subregion_id','integer');

            $allPlans = array();
            $allRegions = array('--');
            $allSubRegions = array('--');

            $regions = Regions::model()->findAll(array('condition'=>'parent_id = 0', 'orderBy'=>'sort_order DESC'));
            if(!empty($regions)){
                foreach($regions as $region){
                    $allRegions[$region['id']] = $region['name'];
                }
            }

            if(!empty($defaultRegion)){
                $subRegions = Regions::model()->findAll(array('condition'=>'parent_id = :parent_id', 'orderBy'=>'sort_order DESC'), array('i:parent_id'=>$defaultRegion));
                foreach($subRegions as $subRegion){
                    $allSubRegions[$subRegion['id']] = $subRegion['name'];
                }
            }

            $adverticePlans = Plans::model()->findAll(array('orderBy'=>'price ASC'));

            if($adverticePlans){
                $allPlans = array();
                $selectAdvertisePlan = '';
                foreach($adverticePlans as $plan){
                    if(!empty($advertisePlanId)){
                        $selectAdvertisePlan = ($plan['id'] == $advertisePlanId);
                    }else{
                        $selectAdvertisePlan = ($plan['is_default'] == 1);
                    }

                    // current advertise plan
                    if($selectAdvertisePlan){
                        $this->_view->defaultPlan          = $plan['id'];
                        $this->_view->fieldLogo            = $plan['logo'];
                        $this->_view->fieldImagesCount     = $plan['images_count'];
                        $this->_view->fieldDescription     = $plan['business_description'];
                        $this->_view->fieldEmail           = $plan['email'];
                        $this->_view->fieldPhone           = $plan['phone'];
                        $this->_view->fieldFax             = $plan['fax'];
                        $this->_view->fieldAddress         = $plan['address'];
                        $this->_view->fieldWebsite         = $plan['website'];
                        $this->_view->fieldVideo           = $plan['video_link'];
                        $this->_view->fieldMap             = $plan['map'];
                        $this->_view->fieldKeywordsCount   = $plan['keywords_count'];
                        $this->_view->fieldInquiriesCount  = $plan['inquiries_count'];
                        $this->_view->fieldInquiriesButton = $plan['inquiry_button'];
                        $this->_view->fieldRatingButton    = $plan['rating_button'];
                        $this->_view->price                = $plan['price'];
                        $duration = $plan['duration'];
                    }
                    $allPlans[$plan['id']] = $plan['name'];
                }
            }else{
                A::app()->getSession()->setFlash('alert', A::t('directory', 'You can not create a listing'));
                A::app()->getSession()->setFlash('alertType', 'error');
                $this->redirect('customers/dashboard');
            }
        }


        // Set date if select free plan and set option 'Listings Approval'
        if($listingsApproval == 'automatic' && $this->_view->price == 0){
            $datePublished = date('Y-m-d H:i:s');
            // 1 - Approved
            $approvel = 1;
            if(!empty($duration) && $duration != '-1'){
                $finishTime = time() + $duration*24*60*60;
                $finishPublished = date('Y-m-d H:i:s', $finishTime);
            }else if($duration == -1){
                $finishPublished = '0000-00-00 00:00:00';
            }else{
                $finishPublished = date('Y-m-d H:i:s', time());
            }
        }else{
            // 0 - Pending
            $approvel = 0;
            $datePublished = '0000-00-00 00:00:00';
            $finishPublished = '0000-00-00 00:00:00';
        }

        $this->_view->approvel        = $approvel;
        $this->_view->finishPublished = $finishPublished;
        $this->_view->datePublished   = $datePublished;
        $this->_view->typeTab         = $typeTab;
        $this->_view->allPlans        = $allPlans;
        $this->_view->allRegions      = $allRegions;
        $this->_view->allSubRegions   = $allSubRegions;
        $this->_view->customerId      = $customer->id;

        $this->_updateFeed();

        $this->_view->render('listings/addlisting');
    }

    /**
     * The action handler allows customers to correction listing
     * @param int $id
     * @param string $typeTab
     * @param string $deleteImage the type image delete ('image|image2|image3|image4')
     * @return void
     */
    public function editListingAction($id = 0, $typeTab = '',  $deleteImage = '')
    {
         // block access to this controller for not-logged customers
        CAuth::handleLogin('customers/login', 'customer');
        // set frontend settings
        Website::setFrontend();
        $listingsApproval = ModulesSettings::model()->param('directory', 'listing_approval');

        $listing = $this->_checkListingFrontendAccess($id);

        if($listing->status_expired == 1){
            A::app()->getSession()->setFlash('alert', A::t('directory', 'You can not edit a listing'));
            A::app()->getSession()->setFlash('alertType', 'error');
            $this->redirect('listings/myListings/typeTab/'.$typeTab);
        }

        $defaultRegion    = '';
        $defaultSubRegion = '';
        $actionMessage    = '';
        $customerId       = 0;
		$duration		  = 0;

        $typeTab = in_array($typeTab, array('approved', 'expired', 'pending', 'canceled')) ? $typeTab : 'all';

        //$alert     = A::app()->getSession()->getFlash('alert');
        //$alertType = A::app()->getSession()->getFlash('alertType');
        $alert     = '';
        $alertType = '';

        // Delete the icon file
        if($deleteImage){
            $deleteImage = strtolower($deleteImage);
            $deleteFlag = false;
            if($deleteImage === 'image'){
                $deleteFlag = true;
                $image = $listing->image_file;
                $imageThumb = $listing->image_file_thumb;
                $listing->image_file = '';
                $listing->image_file_thumb = '';
            }else if($deleteImage === 'image2'){
                $deleteFlag = true;
                $image = $listing->image_1;
                $imageThumb = $listing->image_1_thumb;
                $listing->image_1 = '';
                $listing->image_1_thumb = '';
            }else if($deleteImage === 'image3'){
                $deleteFlag = true;
                $image = $listing->image_2;
                $imageThumb = $listing->image_2_thumb;
                $listing->image_2 = '';
                $listing->image_2_thumb = '';
            }else if($deleteImage === 'image4'){
                $deleteFlag = true;
                $image = $listing->image_3;
                $imageThumb = $listing->image_3_thumb;
                $listing->image_3 = '';
                $listing->image_3_thumb = '';
            }

            if(!empty($image) && !empty($imageThumb)){
                $imagePath = 'images/modules/directory/listings/'.$image;
                $imageThumbPath = 'images/modules/directory/listings/thumbs/'.$imageThumb;
            }

            if($deleteFlag)
            {
                // Save the changes in admins table
                if($listing->save()){
                    // Delete the files
                    if(!empty($imagePath) && CFile::deleteFile($imagePath) && !empty($imageThumbPath) && CFile::deleteFile($imageThumbPath)){
                        $alert = A::t('directory', 'Image successfully deleted');
                        $alertType = 'success';
                    }else{
                        $alert = A::t('directory', 'Listing Image Delete Warning');
                        $alertType = 'warning';
                    }
                }else{
                    $alert = A::t('directory', 'Listing Delete Error');
                    $alertType = 'error';
                }
            }else{
                $alert = A::t('directory', 'Input incorrect parameters');
                $alertType = 'error';
            }

            if(!empty($alert)){
                $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
            }
        }


        $customer = Customers::model()->find('account_id = :account_id', array('i:account_id'=>A::app()->getSession()->get('loggedId')));
        if($customer){
            $cRequest = A::app()->getRequest();

            $customerId = $customer->id;

            $defaultRegion = $cRequest->getPost('region_id', 'integer', 0);
            $defaultRegion = $defaultRegion ? (int)$defaultRegion : $listing->region_id;
            $defaultSubRegion = $cRequest->getPost('subregion_id', 'integer', 0);
            $defaultSubRegion = $defaultSubRegion ? (int)$defaultSubRegion : $listing->subregion_id;

            $allRegions = array('--');
            $allSubRegions = array('--');

            $regions = Regions::model()->findAll(array('condition'=>'parent_id = 0', 'orderBy'=>'sort_order DESC'));
            if(!empty($regions)){
                foreach($regions as $region){
                    $allRegions[$region['id']] = $region['name'];
                }
            }

            if(!empty($defaultRegion)){
                $subRegions = Regions::model()->findAll(array('condition'=>'parent_id = :parent_id', 'orderBy'=>'sort_order DESC'), array('i:parent_id'=>$defaultRegion));
                foreach($subRegions as $subRegion){
                    $allSubRegions[$subRegion['id']] = $subRegion['name'];
                }
            }

            if($listing->advertise_plan_id){
                $plan = Plans::model()->findByPk($listing->advertise_plan_id);
            }else{
                $plan = Plans::model()->find('is_default = 1');
            }

            if($plan){
                $this->_view->defaultPlan          = $plan->id;
                $this->_view->defaultPlanName      = $plan->name;
                $this->_view->fieldLogo            = $plan->logo;
                $this->_view->fieldImagesCount     = $plan->images_count;
                $this->_view->fieldDescription     = $plan->business_description;
                $this->_view->fieldEmail           = $plan->email;
                $this->_view->fieldPhone           = $plan->phone;
                $this->_view->fieldFax             = $plan->fax;
                $this->_view->fieldAddress         = $plan->address;
                $this->_view->fieldWebsite         = $plan->website;
                $this->_view->fieldVideo           = $plan->video_link;
                $this->_view->fieldMap             = $plan->map;
                $this->_view->fieldKeywordsCount   = $plan->keywords_count;
                $this->_view->fieldInquiriesCount  = $plan->inquiries_count;
                $this->_view->fieldInquiriesButton = $plan->inquiry_button;
                $this->_view->fieldRatingButton    = $plan->rating_button;
                $this->_view->price                = $plan->price;
                $duration                          = $plan->duration;
            }else{
                A::app()->getSession()->setFlash('alert', A::t('directory', 'You can not edit a listing'));
                A::app()->getSession()->setFlash('alertType', 'error');
                $this->redirect('customers/dashboard');
            }
        }

        // Set date if select free plan and set option 'Listings Approval'
        if($listingsApproval == 'automatic' && $this->_view->price == 0 && $listing->is_approved == 0){
            $datePublished = date('Y-m-d H:i:s');
            // 1 - Approved
            $approvel = 1;
            if(!empty($duration) && $duration != '-1'){
                $finishTime = time() + $duration*24*60*60;
                $finishPublished = date('Y-m-d H:i:s', $finishTime);
            }else if($duration == -1){
                $finishPublished = '0000-00-00 00:00:00';
            }else{
                $finishPublished = date('Y-m-d H:i:s', time());
            }
        }else{
            $approvel = $listing->is_approved;
            $datePublished = $listing->date_published != '' ? $listing->date_published : '0000-00-00 00:00:00';
            $finishPublished = $listing->finish_publishing != '' ? $listing->finish_publishing : '0000-00-00 00:00:00';
        }

        if($alert){
            $actionMessage = CWidget::create('CMessage', array($alertType, $alert));
        }

        $this->_view->approvel         = $approvel;
        $this->_view->finishPublished  = $finishPublished;
        $this->_view->datePublished    = $datePublished;
        $this->_view->typeTab          = $typeTab;
        $this->_view->allRegions       = $allRegions;
        $this->_view->allSubRegions    = $allSubRegions;
        $this->_view->defaultRegion    = $defaultRegion;
        $this->_view->defaultSubRegion = $defaultSubRegion;
        $this->_view->actionMessage    = $actionMessage;
        $this->_view->customerId       = $customerId;
        $this->_view->id               = $listing->id;

        $this->_updateFeed();

        $this->_view->render('listings/editlisting');
    }

    /**
     * Delete action handler
     * @param int $id
     * @param string $typeTab
     * @return void
     */
    public function deleteListingAction($id = 0, $typeTab = '')
    {
        // Set backend mode
         // block access to this controller for not-logged customers
        CAuth::handleLogin('customers/login', 'customer');
        // set frontend settings
        Website::setFrontend();

        $listing = $this->_checkListingFrontendAccess($id);

        // Delete if listing with status 'pending'
        if($listing->is_approved == 1){
            A::app()->getSession()->setFlash('alert', A::t('directory', 'You can not delete this listing'));
            A::app()->getSession()->setFlash('alertType', 'error');
            $this->redirect('listings/myListings/typeTab/'.$typeTab);
        }

        $customer = Customers::model()->find('account_id = :account_id', array('i:account_id'=>A::app()->getSession()->get('loggedId')));
        if(!$customer){
            $this->redirect('customers/login');
        }

        $typeTab = in_array($typeTab, array('approved', 'expired', 'pending', 'canceled')) ? $typeTab : 'all';
        $alert = '';
        $alertType = '';

        $image = $listing->image_file;
        $imageThumb = $listing->image_file_thumb;
        $image1 = $listing->image_1;
        $image1Thumb = $listing->image_1_thumb;
        $image2 = $listing->image_2;
        $image2Thumb = $listing->image_2_thumb;
        $image3 = $listing->image_3;
        $image3Thumb = $listing->image_3_thumb;
        $imagePath = 'images/modules/directory/listings/';
        $imageThumbPath = 'images/modules/directory/listings/thumbs/';

        if($listing->delete()){
            // Delete the files
            if(
                ($image ? CFile::deleteFile($imagePath.$image) : true) &&
                ($imageThumb ? CFile::deleteFile($imageThumbPath.$imageThumb) : true) &&
                ($image1 ? CFile::deleteFile($imagePath.$image1) : true) &&
                ($image1Thumb ? CFile::deleteFile($imageThumbPath.$image1Thumb) : true) &&
                ($image2 ? CFile::deleteFile($imagePath.$image2) : true) &&
                ($image1Thumb ? CFile::deleteFile($imageThumbPath.$image2Thumb) : true) &&
                ($image3 ? CFile::deleteFile($imagePath.$image3) : true) &&
                ($image1Thumb ? CFile::deleteFile($imageThumbPath.$image3Thumb) : true)
            ){
                $alert = A::t('directory', 'Listing successfully deleted');
                $alertType = 'success';
            }else{
                $alert = A::t('directory', 'Listing Image Delete Warning');
                $alertType = 'warning';
            }
            ListingsCategories::model()->deleteAll('listing_id = :listing_id', array('i:listing_id' => $id));
        }else{
            if(APPHP_MODE == 'demo'){
                $alert = CDatabase::init()->getErrorMessage();
                $alertType = 'warning';
            }else{
                $alert = A::t('directory', 'Listing Delete Error');
                $alertType = 'error';
            }
        }

        if(!empty($alert)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }

        $condition = DirectoryComponent::getListingCondition('not_expired');
        if($typeTab == 'pending'){
            $condition = CConfig::get('db.prefix').'listings.is_approved = 0 AND '.$condition;
        }else if($typeTab == 'approved'){
            $condition = CConfig::get('db.prefix').'listings.is_approved = 1 AND '.$condition;
        }else if($typeTab == 'canceled'){
            $condition = CConfig::get('db.prefix').'listings.is_approved = 2 AND '.$condition;
        }else if($typeTab == 'expired'){
            $condition = DirectoryComponent::getListingCondition('expired');
        }else{
            $condition = '';
        }

        $this->_preparePlanNames();
        $this->_view->categoryCounts = $this->_prepareCategoryCounts();
        $this->_view->condition      = $condition;
        $this->_view->typeTab        = $typeTab;
        $this->_view->customerId     = $customer->id;
        $this->_updateFeed();
        $this->_view->render('listings/mylistings');
    }


    /**
     * The action handler allows customers to view their all listings
     * @param $type the type listings (all, approved, pending, expired, canceled)
     * @return void
     */
    public function myListingsAction($type = 'all')
    {
         // block access to this controller for not-logged customers
        CAuth::handleLogin('customers/login', 'customer');
        // set frontend settings
        Website::setFrontend();
        $customer = Customers::model()->find('account_id = :account_id', array('i:account_id'=>A::app()->getSession()->get('loggedId')));
        if(!$customer){
            $this->redirect('customers/login');
        }

        $typeTab = in_array($type, array('approved', 'expired', 'pending', 'canceled')) ? $type : 'all';

        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');
        $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert));

        $condition = DirectoryComponent::getListingCondition('not_expired');
        if($typeTab == 'pending'){
            $condition = CConfig::get('db.prefix').'listings.is_approved = 0 AND '.$condition;
        }else if($typeTab == 'approved'){
            $condition = CConfig::get('db.prefix').'listings.is_approved = 1 AND '.$condition;
        }else if($typeTab == 'canceled'){
            $condition = CConfig::get('db.prefix').'listings.is_approved = 2 AND '.$condition;
        }else if($typeTab == 'expired'){
            $condition = DirectoryComponent::getListingCondition('expired');
        }else{
            $condition = '';
        }

        $this->_preparePlanNames();
        $this->_view->categoryCounts = $this->_prepareCategoryCounts();
        $this->_view->condition      = $condition;
        $this->_view->typeTab        = $typeTab;
        $this->_view->customerId     = $customer->id;
        $this->_view->render('listings/mylistings');
    }

    /**
     * Add category in listing action handler
     * @param int $listingId the listing ID
     * @return void
     */
    public function addMyCategoryAction($listingId = 0, $typeTab = '')
    {
        // block access to this controller for not-logged customers
        CAuth::handleLogin('customers/login', 'customer');
        // set frontend settings
        Website::setFrontend();
        $customer = Customers::model()->find('account_id = :account_id', array('i:account_id'=>A::app()->getSession()->get('loggedId')));
        if(!$customer){
            $this->redirect('customers/login');
        }

        $typeTab = in_array($typeTab, array('approved', 'expired', 'pending', 'canceled')) ? $typeTab : 'all';

        $optionsAtributs = array();
        $sourceCategoriesId = array();
        $listingDublicate = false;

        $listing = $this->_checkListingFrontendAccess($listingId);

        $this->_view->listingId = $listing->id;
        $this->_view->listingName = $listing->business_name;
        $allCategories = DirectoryComponent::getAllCategoriesArray();

        $adverticePlan = Plans::model()->findByPk($listing->advertise_plan_id);
        if(empty($adverticePlan)){
            $alert = A::t('directory', 'Listing declared non-existent advertising plan');
            $alertType = 'error';

            $thie->redirect('listings/manageMyCategories/listingId/'.$listingId);
        }

        ListingsCategories::model()->setTypeRelations('none');
        $categories = ListingsCategories::model()->findAll('listing_id = :listing_id', array(':listing_id'=>$listingId));
        ListingsCategories::model()->resetTypeRelations();


        if($categories){
            if($adverticePlan->categories_count > count($categories)){
                foreach($categories as $category){
                    if($allCategories[$category['category_id']]) {
                        $optionsAtributs[$category['category_id']] = array('disabled'=>'disabled');
                    }
                }
                if(empty($allCategories)){
                    $alert = A::t('directory', 'You cannot add a category');
                    $alertType = 'warning';
                }else{
                    $sourceCategoriesId = array_keys($allCategories);
                }
            }else{
                $alert = A::t('directory', 'Add new categories impossible. Limit exceeded.');
                $alertType = 'warning';
            }
        }

        if(!empty($alert)){
            A::app()->getSession()->setFlash('alert', $alert);
            A::app()->getSession()->setFlash('alertType', $alertType);

            $this->redirect('listings/manageMyCategories/listingId/'.$listingId.'/typeTab/'.$typeTab);
        }

        $this->_view->typeTab = $typeTab;
        $this->_view->sourceCategoriesId = $sourceCategoriesId;
        $this->_view->optionsAtributs = $optionsAtributs;
        $this->_view->allCategories = $allCategories;
        $this->_view->render('listings/addmycategory');
    }


    /**
     * Delete category in listing action handler
     * @param int $id the listing category ID
     * @return void
     */
    public function deleteMyCategoryAction($id = 0, $typeTab = '')
    {
        // block access to this controller for not-logged customers
        CAuth::handleLogin('customers/login', 'customer');
        // set frontend settings
        Website::setFrontend();
        $customer = Customers::model()->find('account_id = :account_id', array('i:account_id'=>A::app()->getSession()->get('loggedId')));
        if(!$customer){
            $this->redirect('customers/login');
        }

        $typeTab = in_array($typeTab, array('approved', 'expired', 'pending', 'canceled')) ? $typeTab : 'all';

        $listingId = '';
        $category = ListingsCategories::model()->findByPk($id);
        if(!empty($category)){
            $listingId = $category->listing_id;
            $listing   = $this->_checkListingFrontendAccess($listingId);
            if($category->delete())
            {
                $alert = A::t('directory', 'Listing Category successfully deleted');
                $alertType = 'success';
            }else{
                if(APPHP_MODE == 'demo'){
                    $alert = CDatabase::init()->getErrorMessage();
                    $alertType = 'warning';
                }else{
                    $alert = A::t('directory', 'Listing Category deleting error');
                    $alertType = 'error';
                }
            }
            $alert = A::app()->getSession()->setFlash('alert', $alert);
            $alertType = A::app()->getSession()->setFlash('alertType', $alertType);
        }
        if($listingId){
            $this->redirect('listings/manageMyCategories/listingId/'.$listingId.'/typeTab/'.$typeTab);
        }else{
            $this->redirect('listings/myListings');
        }
    }


    /**
     * Manage add and delete categories in listing
     * @param int $listingId the listing ID
     * @param $type the type listings (all, approved, pending, expired, canceled)
     * @return void
     */
    public function manageMyCategoriesAction($listingId = 0, $type = '')
    {
        // block access to this controller for not-logged customers
        CAuth::handleLogin('customers/login', 'customer');
        // set frontend settings
        Website::setFrontend();
        $customer = Customers::model()->find('account_id = :account_id', array('i:account_id'=>A::app()->getSession()->get('loggedId')));
        if(!$customer){
            $this->redirect('customers/login');
        }

        $typeTab = in_array($type, array('approved', 'expired', 'pending', 'canceled')) ? $type : 'all';

        Listings::model()->setTypeRelations('plans');
        $listing   = $this->_checkListingFrontendAccess($listingId);
        Listings::model()->resetTypeRelations();
        $alert     = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');

        $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));

        $displayButtonAdd = true;
        $listingId        = $listing->id;
        $categoryIds      = array();

        ListingsCategories::model()->setTypeRelations('none');
        $categories = ListingsCategories::model()->findAll('listing_id = :listing_id', array('i:listing_id' => $listingId));
        if($categories){
            foreach($categories as $category){
                $categoryIds[] = $category['category_id'];
            }
        }
        ListingsCategories::model()->resetTypeRelations();

        if($listing->categories_count <= count($categories)){
            $displayButtonAdd = false;
        }else{
            $allCategoriesId = array_keys(DirectoryComponent::getAllCategoriesArray());
            if(!array_diff($allCategoriesId, $categoryIds)){
                $displayButtonAdd = false;
            }
        }
        $this->_view->typeTab = $typeTab;
        $this->_view->displayButtonAdd = $displayButtonAdd;
        $this->_view->categoryIds = $categoryIds;
        $this->_view->listingId = $listing->id;
        $this->_view->listingName = $listing->business_name;
        $this->_view->render('listings/managemycategories');
    }

    /**
     * Check if passed record ID is valid
     * @param int $id
     * @return Listings
     */
    private function _checkListingAccess($id = 0)
    {
        $model = Listings::model()->findByPk($id);
        if(!$model){
            $this->redirect('listings/manage');
        }
        return $model;
    }

    /**
     * Check if passed record ID is valid
     * @param int $id
     * @return Listings
     */
    private function _checkListingFrontendAccess($id = 0)
    {
        $redirect = true;
        $listing  = '';
        $accountId = A::app()->getSession()->get('loggedId');

        if($accountId && $id){
            $customer = Customers::model()->find('account_id = :account_id', array('i:account_id'=>$accountId));
            if($customer){
                $listing = Listings::model()->find(CConfig::get('db.prefix').'listings.id = :listing_id AND customer_id = :customer_id', array('i:listing_id'=>$id, 'i:customer_id'=>$customer->id));
                if($listing){
                    $redirect = false;
                }
            }
        }

        if($redirect){
            $this->redirect('customers/dashboard');
        }else{
            return $listing;
        }
    }

    /**
     * Prepares variables $categoryNames, $categoryDescriptions, $categoryIcons and $categoryIconMaps (in View)
     * @return void
     */
    private function _prepareListingCategories($listingId = 0)
    {
        $categoryNames = array();
        $categoryDescriptions = array();
        $categoryIcons = array();
        $categoryIconsMap = array();

        ListingsCategories::model()->setTypeRelations('categories');
        $listingCategories = ListingsCategories::model()->findAll('`'.CConfig::get('db.prefix').'listings_categories`.`listing_id` = :id', array('i:id' => (int)$listingId));

        if($listingCategories){
            foreach($listingCategories as $listingCategory){
                $categoryNames[$listingCategory['category_id']] = $listingCategory['name'];
                $categoryDescriptions[$listingCategory['category_id']] = $listingCategory['description'];
                $categoryIcons[$listingCategory['category_id']] = $listingCategory['icon'];
                $categoryIconsMap[$listingCategory['category_id']] = $listingCategory['icon_map'];
            }
        }

        $this->_view->categoryNames         = $categoryNames;
        $this->_view->categoryDescriptions  = $categoryDescriptions;
        $this->_view->categoryIcons         = $categoryIcons;
        $this->_view->categoryIconsMap      = $categoryIconsMap;
    }

    /**
     * Prepares variables $advertisePlanNames, $advertisePlanDefault and $allAdvertisePlans (in View)
     * @return void
     */
    private function _preparePlanNames()
    {
        $advertisePlanNames = array();
        $defaultPlanId = 0;
        $allAdvertisePlans = array();

        $advertisePlans = Plans::model()->findAll();
        if(is_array($advertisePlans) && !empty($advertisePlans)){
            $advertisePlanNames = array();
            $advertisePlanDefault = '';
            foreach($advertisePlans as $advertisePlan){
                $advertisePlanNames[$advertisePlan['id']] = $advertisePlan['name'];
                $allAdvertisePlans[$advertisePlan['id']] = $advertisePlan;
                if(1 == $advertisePlan['is_default']){
                    $defaultPlanId = $advertisePlan['id'];
                }
            }
        }
        $this->_view->advertisePlanNames = $advertisePlanNames;
        $this->_view->advertisePlanDefault = $defaultPlanId;
        $this->_view->allAdvertisePlans = $allAdvertisePlans;
    }

    /**
     * Prepares variable $locationNames (in View)
     * @return void
     */
    private function _prepareRegionNames()
    {
        $regions = Regions::model()->findAll('parent_id=0');
        $regionNames = array(''=>'--');
        foreach($regions as $region){
            $regionNames[$region['id']] = $region['name'];
        }
        $this->_view->regionNames = $regionNames;
    }

    /**
     * Prepares array location names (in View)
     * @return void
     */
    private function _prepareCustomerFullNames()
    {
        $result = Customers::model()->findAll();
        $customerFullNames = array();
        foreach($result as $customer){
            $customerFullNames[$customer['id']] = $customer['first_name'].' '.$customer['last_name'];
        }
        asort($customerFullNames);
        $this->_view->customerFullNames = $customerFullNames;
    }

    /**
     * Prepares array of total counts for each categories
     * @return array
     */
    private function _prepareCategoryCounts()
    {
        $result = ListingsCategories::model()->count(array('condition'=>'', 'select'=>'`'.CConfig::get('db.prefix').'listings_categories`.`listing_id`', 'count'=>'*', 'groupBy'=>'listing_id', 'allRows'=>true));
        $categoryCounts = array();
        foreach($result as $key => $model){
            $categoryCounts[$model['listing_id']] = $model['cnt'];
        }
        return $categoryCounts;
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

    /**
     * Returns the javascript code to the small map
     * @param int $listingId
     */
    private function _getCodeSmallMap($listingId)
    {
        $jsCode = '';
        $listing = Listings::model()->findByPk((int)$listingId);
        if($listing){
            $newLine = array();
            for($i = 0; $i < 6; $i++){
                if(APPHP_MODE == 'debug'){
                    if($i > 0){
                        $newLine[$i] = $newLine[$i-1]."\t";
                    }else{
                        $newLine[$i] = "\r\n\t";
                    }
                }else{
                    $newLine[$i] = '';
                }
            }

            $jsCode = 'smallMapDiv = $(".item-map");'.$newLine[1]
            .'smallMapDiv.width(300).height(300).gmap3({'.$newLine[2]
                .'map: {'.$newLine[3]
                    .'options: {'.$newLine[4]
                        .'center: ["'.CHtml::encode($listing->region_latitude).'","'.CHtml::encode($listing->region_longitude).'"],'.$newLine[4]
                        .'zoom: 18,'.$newLine[4]
                        .'scrollwheel: false'.$newLine[3]
                    .'}'.$newLine[2]
                .'},'.$newLine[2]
                .'marker: {'.$newLine[3]
                    .'values: ['.$newLine[4]
                        .'{'.$newLine[5]
                            .'latLng: ["'.CHtml::encode($listing->region_latitude).'","'.CHtml::encode($listing->region_longitude).'"]'.$newLine[4]
                        .'}'.$newLine[3]
                    .']'.$newLine[2]
                .'}'.$newLine[1]
            .'});'.$newLine[0];

        }
        return $jsCode;
    }

    /**
     * Prepares account fields under the Advertise plan
     * @param Listings $listing the model listing
     * @return void
     */
    private function _prepareViewListingFields($listing)
    {
        $this->_view->listingImage           = '';
        $this->_view->listingImagesMiniature = '';
        $this->_view->listingWebsite         = '';
        $this->_view->listingVideo           = '';
        $this->_view->listingMap             = '';
        $this->_view->listingLongitude       = '';
        $this->_view->listingLatitude        = '';

        $this->_view->listingPhone           = '';
        $this->_view->listingFax             = '';
        $this->_view->listingEmail           = '';

        $this->_view->listingAddress         = '';
        //$this->_view->listingKeywords        = '';
        $this->_view->listingDescription     = '';
        if($listing instanceof Listings){
            if($plan = Plans::model()->findByPk((int)$listing->advertise_plan_id)){
                if($plan->business_description) $this->_view->listingDescription = $listing->business_description;
                if($plan->logo) $this->_view->listingImage = $listing->image_file;
                if($plan->images_count){
                    $listingImagesMiniature = array();
                    for($i = 1; $i <= $plan->images_count; $i++){
                        $imageName = 'image_'.$i;
                        if($listing->$imageName){
                            $listingImagesMiniature[] = array('image'=>$listing->$imageName, 'thumb'=>$listing->{$imageName.'_thumb'});
                        }
                    }
                    $this->_view->listingImagesMiniature = $listingImagesMiniature;
                }
                if($plan->email) $this->_view->listingEmail = $listing->business_email;
                if($plan->fax) $this->_view->listingFax = $listing->business_fax;
                if($plan->website) $this->_view->listingWebsite = $listing->website_url;
                if($plan->video_link) $this->_view->listingVideo = $listing->video_url;
                if($plan->phone) $this->_view->listingPhone = $listing->business_phone;
                if($plan->address) $this->_view->listingAddress = $listing->business_address;
                if($plan->map && ($listing->region_latitude || $listing->region_longitude)) {
                    $this->_view->listingLongitude = $listing->region_longitude;
                    $this->_view->listingLatitude = $listing->region_latitude;
                    $this->_view->listingMap = true;
                }
                $this->_view->planPrice = $plan->price;
            }
        }
    }

    /**
     * Prepare Listings Categories considering the sort fields
     * @return void
     */
    private function _prepareSortingListingsCategory()
    {
        $cRequest       = A::app()->getRequest();
        $alert          = '';
        $alertType      = '';
        $pagination     = '';
        $startEndItem   = 1;
        $countListings  = 0;
        $listings       = array();
        $validError     = false;

        $this->_view->actionMessage = '';

        $categoryId = $cRequest->getQuery('categories', 'integer', 0);
        $locationId = $cRequest->getQuery('locations',  'integer', 0);
        $search     = $cRequest->getQuery('s',          'string', '');
        $count      = $cRequest->getQuery('count',      'integer', 10);
        $sortBy     = $cRequest->getQuery('sortBy',     'string', '');
        $sort       = $cRequest->getQuery('sort',       'integer', 0);
        $page       = $cRequest->getQuery('page',       'integer', 1);

		// Validate sort by value
		if(!in_array($sortBy, array('asc', 'desc'))){
			$sortBy = '';
		}

        //var_dump($search);
        $sortFields = array('date_published'=>A::t('directory', 'Date Published'), 'finish_publishing'=>A::t('directory', 'Finish Published'), 'business_name'=>A::t('directory', 'Name'));
        $sortType   = array('ASC', 'DESC');
        $keySortFields = array_keys($sortFields);
        $keySortType = array_keys($sortType);
        $sortBy     = empty($sortBy) ? key($sortFields) : $sortBy;

        if((empty($categoryId) && !CValidator::isInteger($categoryId)) ||
           (empty($locationId) && !CValidator::isInteger($locationId)) ||
           (empty($search) && !CValidator::isText($search)) ||
           (empty($count) && !CValidator::isInteger($count)) ||
           (empty($sortBy) && (!is_string($sortBy) || !isset($keySortFields[$sortBy]))) ||
           (empty($sort) && (!CValidator::isInteger($sort) || !isset($keySortType[$sort]))) ||
           (empty($page) && !CValidator::isInteger($page))
        ){
            $validError = true;
        }

        $result = CWidget::create('CFormValidation', array(
            'fields'=>array(
                'categories'=>array('title'=>A::t('directory', 'Categories'),       'validation'=>array('required'=>false, 'type'=>'integer')),
                'locations' =>array('title'=>A::t('directory', 'Locations'),        'validation'=>array('required'=>false, 'type'=>'integer')),
                's'         =>array('title'=>A::t('directory', 'Search keyword...'),'validation'=>array('required'=>false, 'type'=>'text')),
                'count'     =>array('title'=>A::t('directory', 'Count'),            'validation'=>array('required'=>false, 'type'=>'integer')),
                'sortBy'    =>array('title'=>A::t('directory', 'Sort by'),          'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($sortFields))),
                'sort'      =>array('title'=>A::t('directory', 'Sort'),             'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($sortType))),
                'page'      =>array('title'=>A::t('directory', 'Page'),             'validation'=>array('required'=>false, 'type'=>'integer')),
            ),
            'messagesSource'=>'core',
            'showAllErrors'=>false,
        ));

        if($result['error']){
            $alert     = $result['errorMessage'];
            $alertType = 'validation';
            $this->_view->errorField = $result['errorField'];
        }else{

            $condition  = DirectoryComponent::getListingCondition('not_expired');
            $condition .= ' AND ';
            $params     = array();

            if(!empty($categoryId)){
                $condition .= 'category_id = :category_id AND ';
                $params['i:category_id'] = $categoryId;
            }

            if(!empty($locationId)){
                $condition .= '(region_id = :region_id OR subregion_id = :subregion_id) AND ';
                $params['i:region_id'] = $locationId;
                $params['i:subregion_id'] = $locationId;
            }

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
                $paginationUrl  = $categoryId ? '?categories='.$categoryId : '';
                $paginationUrl .= $locationId ? ($paginationUrl ? '&' : '?').'locations='.$locationId : '';
                $paginationUrl .= $count ? ($paginationUrl ? '&' : '?').'count='.$count : '';
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
        $this->_view->categoryId    = $categoryId;
        $this->_view->locationId    = $locationId;
        $this->_view->countListings = $countListings;
        $this->_view->count         = $count;
        $this->_view->sortBy        = $sortBy;
        $this->_view->sortType      = $sort;
        $this->_view->sortFields    = $sortFields;
        $this->_view->listings      = $listings;
        $this->_view->pagination    = $pagination;
    }

    /**
     * Function for update feeds list
     */
    private function _updateFeed()
    {
        $feedSettings = Bootstrap::init()->getSettings();

        $countFeedPosts = 0;
        $typeFeedPosts = $feedSettings->rss_feed_type;
        $feedChannel = 'directory';
        $nameChannel = '';

        // Save rss-file with the default language
        $defaultLang = Languages::model()->find('is_default = 1');
        if(!empty($defaultLang)){
            $lang = $defaultLang->code;
        }else{
            $lang = A::app()->getLanguage();
        }

        $rssData = Rss::model()->find('channel_code = \'directory\' AND mode_code = \'listings\'');
        $rssIds = '';
        $rssLastIds = '';

        if(!empty($rssData)){
            $countFeedPosts = (int)$rssData->items_count;
            $nameChannel = $rssData->channel_name;
        }else{
            $rssData = new Rss();
            $countFeedPosts = (int)$feedSettings->rss_items_per_feed;
            $nameChannel = 'Business Directory';
            $rssData->items_count = $countFeedPosts;
            $rssData->channel_name = 'Business Directory';
            $rssData->channel_code = 'directory';
            $rssData->mode_code = 'listings';
        }
        $rssData->updated_at = LocalTime::currentDateTime();

        if($rssData->last_items != ''){
            $rssLastIds = $rssData->last_items;
        }

        CRss::setType($typeFeedPosts);
        CRss::setFile('listings_rss.xml');
        CRss::setChannel(
            array(
                'url'           => 'feeds/listings_rss.xml',
                'title'         => $nameChannel,
                'description'   => $nameChannel,
                'lang'          => $lang,
                'copyright'     => '(c) copyright',
                'creator'       => $this->_view->customerFullNames,
                'author'        => $this->_view->customerFullNames,
                'subject'       => $nameChannel
            )
        );

        CRss::setImage(A::app()->getRequest()->getBaseUrl().'/images/modules/directory/icon.png');

        $condition   = DirectoryComponent::getListingCondition('not_expired');
        $condition   = CConfig::get('db.prefix').'listings.is_published = 1 AND '.CConfig::get('db.prefix').'listings.is_approved = 1 AND '.CConfig::get('db.prefix').'listings.access_level = 0 AND '.$condition;

        // get last 10 active posts
        $allListings = CDatabase::init()->select('SELECT
                '.CConfig::get('db.prefix').'listings.*,
                '.CConfig::get('db.prefix').'listing_translations.business_name,
                '.CConfig::get('db.prefix').'listing_translations.business_description
            FROM '.CConfig::get('db.prefix').'listings
                LEFT OUTER JOIN '.CConfig::get('db.prefix').'listing_translations ON '.CConfig::get('db.prefix').'listings.id = '.CConfig::get('db.prefix').'listing_translations.listing_id AND language_code = \''.$lang.'\'
            WHERE '.$condition.'
            ORDER BY '.CConfig::get('db.prefix').'listings.date_published DESC
            LIMIT 0, '.$countFeedPosts
        );


        $totalListings = count($allListings);

        for($i = 0; $i < $totalListings; $i++){
            $rssIds .= (($i > 0) ? '-' : '').$allListings[$i]['id'];
        }

        $rssData->last_items = $rssIds;
        $rssData->save();

        // check if there difference between RSS IDs, so we have to update RSS file
        if($rssLastIds != $rssIds){
            for($i = 0; $i < $totalListings; $i++){
                $rss_text = CRss::cleanTextRss(strip_tags($allListings[$i]['business_description']));
                if(strlen($rss_text) > 512) $rss_text = substr($rss_text, 0, 512).'...';
                //$rss_text = htmlentities($rss_text, ENT_COMPAT, 'UTF-8');
                $link = CHtml::encode(A::app()->getRequest()->getBaseUrl().'listings/view/id/'.$allListings[$i]['id']);
                CRss::setItem($link, $allListings[$i]['business_name'], $rss_text, $allListings[$i]['date_published']);
            }
            CRss::saveFeed();
        }
    }
}
