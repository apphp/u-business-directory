<?php
/**
 * Business directory inquiries controller
 * This controller intended to both Backend and Frontend modes
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct              _checkInquiryAccess
 * indexAction
 * manageAction
 * previewAction
 * deleteAction
 * firstStepAction
 * secondStepAction
 * myInquiriesAction
 * customerPreviewAction
 *
 */

class InquiriesController extends CController
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
            // set meta tags according to active business directory inquiries
            Website::setMetaTags(array('title'=>A::t('directory', 'Inquiries Management')));
            // set backend mode
            Website::setBackend();

            $this->_view->actionMessage = '';
            $this->_view->errorField = '';

            $this->_view->tabs = DirectoryComponent::prepareTabs('inquiries');
        }
        $this->_view->dateTimeFormat = Bootstrap::init()->getSettings()->datetime_format;
    }

    /**
     * Controller default action handler
     * @return void
     */
    public function indexAction()
    {
        if(CAuth::isLoggedInAsAdmin()){
            $this->redirect('inquiries/manage');
        }else if(CAuth::isLoggedIn()){
            $this->redirect('customers/dashboard');
        }else{
            $this->redirect(Website::getDefaultPage());
        }
    }

    /**
     * Manage action handler
     * @return void
     */
    public function manageAction()
    {
        Website::prepareBackendAction('manage', 'directory', 'inquiries/manage');

        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');

        if(!empty($alert)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }

        $this->_prepareFields();

        $this->_view->render('inquiries/manage');
    }

    /**
     * Edit business directory inquiries action handler
     *
     * @param int $id
     * @return void
     */
    public function previewAction($id = 0)
    {
        Website::prepareBackendAction('edit', 'directory', 'inquiries/manage');

        $inquiry = $this->_checkInquiryAccess($id);
        $regionName = A::t('directory', 'Unknown');
        $subregionName = A::t('directory', 'Unknown');
        $regions = array();
        if($inquiry->region_id != 0){
            $regions[] = $inquiry->region_id;
        }
        if($inquiry->subregion_id != 0){
            $regions[] = $inquiry->subregion_id;
        }
        if(!empty($regions)){
            $regions = Regions::model()->findAll(CConfig::get('db.prefix').'regions.id IN ('.implode(',', $regions).')');

            if(!empty($regions) && is_array($regions)){
                foreach($regions as $region){
                    if($region['id'] == $inquiry->region_id){
                        $regionName = $region['name'];
                    }
                    if($region['id'] == $inquiry->subregion_id){
                        $subregionName = $region['name'];
                    }
                }
            }
        }

        $inquiryTypeList = array('0'=>A::t('directory', 'Standard'), '1'=>A::t('directory', 'Direct'));
        $availabilityList = array('0'=>A::t('directory', 'Anytime'), '1'=>A::t('directory', 'Morning'), '2'=>A::t('directory', 'Lunch'), '3'=>A::t('directory', 'Afternoon'), '4'=>A::t('directory', 'Evening'), '5'=>A::t('directory', 'Weekend'));
        $preferredContactsList = array('0'=>A::t('directory', 'By Phone or Email'), '1'=>A::t('directory', 'By Phone'), '2'=>A::t('directory', 'Via Email'));
        $activeList = array('0'=>'<span class="label-red">'.A::t('directory', 'No').'</span>', '1'=>'<span class="label-green">'.A::t('directory', 'Yes').'</span>');

        $this->_view->id = $id;
        $this->_view->name = $inquiry->name;
        $this->_view->inquiryType = isset($inquiryTypeList[$inquiry->inquiry_type]) ? $inquiryTypeList[$inquiry->inquiry_type] : A::t('directory', 'Unknown');
        $this->_view->listingName = $inquiry->listing_name;
        $this->_view->email = $inquiry->email;
        $this->_view->phone = $inquiry->phone;
        $this->_view->region = $regionName;
        $this->_view->subregion = $subregionName;
        $this->_view->availability = isset($availabilityList[$inquiry->availability]) ? $availabilityList[$inquiry->availability] : A::t('directory', 'Unknown');
        $this->_view->preferredContacts = isset($preferredContactsList[$inquiry->preferred_contact]) ? $preferredContactsList[$inquiry->preferred_contact] : A::t('directory', 'Unknown');
        $this->_view->description = $inquiry->description;
        $this->_view->dateCreated = CLocale::date($this->_view->dateTimeFormat, $inquiry->date_created);
        $this->_view->active = isset($activeList[$inquiry->is_active]) ? $activeList[$inquiry->is_active] : '<span class="label-gray">'.A::t('directory', 'Unknown').'</span>';
        $this->_view->render('inquiries/preview');
    }

    /**
     * Delete action handler
     * @param int $id
     * @return void
     */
    public function deleteAction($id = 0)
    {
        Website::prepareBackendAction('delete', 'directory', 'inquiries/manage');
        $model = $this->_checkInquiryAccess($id);

        $alert = '';
        $alertType = '';

        if($model->delete()){
            if($model->getError()){
                $alert     = A::t('directory', 'Delete Warning Message');
                $alertType = 'warning';
            }else{
                $alert     = A::t('directory', 'Delete Success Message');
                $alertType = 'success';
            }
        }else{
            if(APPHP_MODE == 'demo'){
                $alert = CDatabase::init()->getErrorMessage();
                $alertType = 'warning';
            }else{
                $alert = A::t('directory', 'Delete Error Message');
                $alertType = 'error';
            }
        }

        if(!empty($alert)){
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
        }

        $this->_prepareFields();
        $this->_view->render('inquiries/manage');
    }

    /**
     * First step in inquiry block (get form) action hendler
     *
     * There is a 2-mode for this method
     * - "Normal" at which the search for the necessary listings.
     * - "Redirect" when you click on a link from a particular listing, then there is no need to find a suitable listings
     * @return void
     */
    public function firstStepAction()
    {
        Website::setFrontend();

        $allLocations          = array();
        $subLocations          = array();
        $params                = array();
        $listings              = array();
        $defaultCategory       = 0;
        $errorField            = '';
        $alert                 = '';
        $alertType             = '';
        $actionMessage         = '';
        $availability          = 0;
        $description           = '';
        $email                 = '';
        $name                  = '';
        $phone                 = '';
        $subRegion             = '';
        $condition             = '';
        $conditionForPlan      = '';
        $contacted             = 0;
        $region                = 0;
        $specificListing       = '';
        $showNotFoundMessage   = true;
        $allCategories         = array();

        $cRequest              = A::app()->getRequest();
        $categoryId            = (int)$cRequest->getQuery('categoryId', 'int');
        $locationId            = (int)$cRequest->getQuery('locationId', 'int');
        $listingId             = (int)$cRequest->getQuery('listingId', 'int');
        $showWidget            = (int)$cRequest->getQuery('widget', 'int') ? true : false;

        $availabilityList      = array('0'=>A::t('directory', 'Anytime'), '1'=>A::t('directory', 'Morning'), '2'=>A::t('directory', 'Lunch'), '3'=>A::t('directory', 'Afternoon'), '4'=>A::t('directory', 'Evening'), '5'=>A::t('directory', 'Weekend'));
        $preferredContactsList = array('0'=>A::t('directory', 'By Phone or Email'), '1'=>A::t('directory', 'By Phone'), '2'=>A::t('directory', 'Via Email'));

        $accessLevel = (int)CAuth::isLoggedIn();
        $condition   = DirectoryComponent::getListingCondition('not_expired');
        $condition   = 'is_published = 1 AND is_approved = 1 AND access_level <= '.$accessLevel.' AND '.$condition;

        if(!empty($listingId)){
            $specificListing = ListingsCategories::model()->find(CConfig::get('db.prefix').'listings_categories.listing_id = :listing_id AND '.$condition, array(':listing_id'=>$listingId));
            // If the variable "$specificListing" is empty then use "Normal" mode otherwise the method works in "Redirect" mode
            if(empty($specificListing)){
                $alert = A::t('directory', 'Input incorrect parameters');
                $alertType = 'error';
            }
        }

        if(empty($specificListing)){
            $allCategories         = DirectoryComponent::getAllCategoriesArray();

            $locations = Regions::model()->findAll('parent_id = 0');
            if(!empty($locations) && is_array($locations)){
                foreach($locations as $location){
                    $allLocations[$location['id']] = $location['name'];
                }
            }

            $plans = array();
            $allPlans = Plans::model()->findAll();
            if(!empty($allPlans)){
                foreach($allPlans as $plan){
                    $plans[$plan['id']] = $plan['inquiries_count'];
                }
            }

            // Check whether the number of inquiries exceeds the last month
            $switchPlan  = '(SELECT CASE '.CConfig::get('db.prefix').'listings.advertise_plan_id ';
            foreach($plans as $key => $number){
                $switchPlan .= 'WHEN '.(int)$key.' THEN '.(int)$number."\n";
            }
            $switchPlan .= 'END)';
            // This type of sorting is used when the user has not chosen listings to send inquiries
            $conditionForPlan .= ' AND ('.$switchPlan.' = -1 OR '.$switchPlan.' > (SELECT COUNT(*) FROM '.CConfig::get('db.prefix').'inquiries WHERE date_created > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND '.CConfig::get('db.prefix').'inquiries.listing_id = '.CConfig::get('db.prefix').'listings_categories.listing_id))';
        }

        if($cRequest->getPost('act') == 'send'){
            $availability     = (int)$cRequest->getPost('availability', 'int');
            $description      = $cRequest->getPost('description', 'string');
            $email            = $cRequest->getPost('email', 'string');
            $name             = $cRequest->getPost('name', 'string');
            $phone            = $cRequest->getPost('phone', 'string');
            $contacted        = (int)$cRequest->getPost('contacted', 'int');
            if(empty($specificListing)){
                $categoryId       = (int)$cRequest->getPost('category_id', 'int');
                $region           = (int)$cRequest->getPost('region_id', 'int');
                $subRegion        = (int)$cRequest->getPost('subregion_id', 'int');
            }else{
                $categoryId       = $specificListing->category_id;
                $region           = $specificListing->region_id;
                $subRegion        = $specificListing->subregion_id;
            }

            if(empty($specificListing)){
                if(!empty($region)){
                    $result = Regions::model()->findAll('parent_id = :parent_id', array('i:parent_id'=>$region));
                    if(!empty($result) && is_array($result)){
                        foreach($result as $subLocation){
                            $subLocations[$subLocation['id']] = $subLocation['name'];
                        }
                    }
                }

                if(!empty($categoryId)){
                    $condition  .= ' AND category_id = :category_id';
                    $params['i:category_id'] = $categoryId;
                }
                if(!empty($region)){
                    $condition  .= ' AND region_id = :region_id';
                    $params['i:region_id'] = $region;
                    if(!empty($subRegion)){
                        $condition .= ' AND subregion_id = :subregion_id';
                        $params['i:subregion_id'] = $subRegion;
                    }
                }
            }

            $fields = array(
                'description'  => array('title'=>A::t('directory', 'Description'),       'validation'=>array('required'=>true,  'type'=>'text',  'maxValue'=>'1024')),
                'name'         => array('title'=>A::t('directory', 'Name'),              'validation'=>array('required'=>true,  'type'=>'text',  'maxValue'=>'50')),
                'email'        => array('title'=>A::t('directory', 'Email'),             'validation'=>array('required'=>true,  'type'=>'email', 'maxValue'=>'70')),
                'phone'        => array('title'=>A::t('directory', 'Phone'),             'validation'=>array('required'=>true,  'type'=>'phone', 'maxValue'=>'20')),
                'availability' => array('title'=>A::t('directory', 'Availability'),      'validation'=>array('required'=>true,  'type'=>'set',   'source'=>array_keys($availabilityList))),
                'contacted'    => array('title'=>A::t('directory', 'Preferred Contact'), 'validation'=>array('required'=>true,  'type'=>'set',   'source'=>array_keys($preferredContactsList))),
            );
            if(empty($specificListing)){
               $fields['category_id']  = array('title'=>A::t('directory', 'Categories'),   'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($allCategories)));
               $fields['region_id']    = array('title'=>A::t('directory', 'Location'),     'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($allLocations)));
               $fields['subregion_id'] = array('title'=>A::t('directory', 'Sub-Location'), 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_merge(array('', '0'), array_keys($subLocations))));
            }
            $result = CWidget::create('CFormValidation', array(
                'fields'=>$fields,
                'messagesSource'=>'core',
                'showAllErrors'=>false,
            ));

            if($result['error']){
                $alert = $result['errorMessage'];
                $alertType = 'validation';
                $errorField = $result['errorField'];

                $listings    = ListingsCategories::model()->findAll(array('condition'=>$condition.$conditionForPlan, 'groupBy'=>CConfig::get('db.prefix').'listings_categories.listing_id' ,'orderBy'=>CConfig::get('db.prefix').'listings.is_featured DESC, '.CConfig::get('db.prefix').'listings.date_published DESC, '.CConfig::get('db.prefix').'listings_categories.listing_id DESC', 'limit'=>'5'), $params);
                if(!empty($listings) && is_array($listings)){
                    $showNotFoundMessage = false;
                    $listings = CArray::flipByField($listings, 'listing_id');
                }
            }else{
                $alertType = '';
                $alert = '';
                $sendListings = array();
                $listingIds = array();
                $customerListings = array();
                $sendInquiries = false;
                $emptyListings = true;
                $keyListings = array();

                if(!empty($specificListing)){
                    $listingId = (int)$cRequest->getPost('listing1');
                    if($specificListing->listing_id == $listingId){
                        $sendInquiries = true;

                        $inquiry = new Inquiries();
                        $inquiry->inquiry_type = 1;
                        $inquiry->category_id = $specificListing->category_id;
                        $inquiry->listing_id = $listingId;
                        $inquiry->name = $name;
                        $inquiry->email = $email;
                        $inquiry->phone = $phone;
                        $inquiry->region_id = $specificListing->region_id;
                        $inquiry->subregion_id = $specificListing->subregion_id;
                        $inquiry->availability = $availability;
                        $inquiry->preferred_contact = $contacted;
                        $inquiry->description = $description;
                        $inquiry->date_created = date('Y-m-d H:i:s');
                        $inquiry->is_active = 1;

                        $inquiry->save();

                        // Send email if set notifications
                        $customer = Customers::model()->findByPk($specificListing->customer_id);
                        if(!empty($customer) && $customer->notifications){
                            $emailResult = Website::sendEmailByTemplate(
                                $customer->email,
                                'directory_new_inquiry',
                                $customer->languages_code,
                                array('{FIRST_NAME}' => $customer->first_name, '{LAST_NAME}' => $customer->last_name, '{LINK}' => A::app()->getRequest()->getBaseUrl().'inquiries/myInquiries')
                            );
                        }
                    }else{
                        $alert = A::t('directory', 'Input incorrect parameters').'.';
                        $alertType = 'error';
                    }
                }else{
                    if(APPHP_MODE == 'demo'){
                        $alert = A::t('directory', 'This operation is blocked in Demo Mode!');
                        $alertType = 'warning';
                        A::app()->getSession()->setFlash('alertType', $alertType);
                        A::app()->getSession()->setFlash('alert', $alert);
                        $this->redirect('inquiries/secondStep'.($showWidget ? '/widget/1' : ''));
                    }else{
                        $customerIds = array();
                        $customers = array();
                        $customerSent = array();

                        $inputListingIds = array();
                        for($i = 1; $i <= 5; $i++){
                            if((int)$cRequest->getPost('listing'.$i)){
                                $listingId = (int)$cRequest->getPost('listing'.$i);
                                if($listingId != 0){
                                    $inputListingIds[] = $listingId;
                                }
                            }
                        }
                        if($inputListingIds){
                            $listings    = ListingsCategories::model()->findAll(array('condition'=>$condition.' AND '.CConfig::get('db.prefix').'listings_categories.listing_id IN ('.implode(',',$inputListingIds).')', 'groupBy'=>CConfig::get('db.prefix').'listings_categories.listing_id' ,'orderBy'=>CConfig::get('db.prefix').'listings.is_featured DESC, '.CConfig::get('db.prefix').'listings.date_published DESC, '.CConfig::get('db.prefix').'listings_categories.listing_id DESC', 'limit'=>'5'), $params);
                            if(!empty($listings) && is_array($listings)){
                                $showNotFoundMessage = false;
                                $listings = CArray::flipByField($listings, 'listing_id');
                            }
                        }

                        // Prepare customer ids
                        foreach($listings as $listing){
                            $customerId = $listing['customer_id'];
                            if(!in_array($customerId, $customerIds)){
                                $customerIds[] = (int)$customerId;
                            }
                        }

                        // Select all customers
                        if(!empty($customerIds)){
                            $customers = Customers::model()->findAll(CConfig::get('db.prefix').'customers.id IN ('.implode(',', $customerIds).')');
                            if(!empty($customers)){
                                $customers = CArray::flipByField($customers, 'id');
                            }
                        }

                        // Send inquiries and emails
                        for($i = 1; $i <= 5; $i++){
                            if((int)$cRequest->getPost('listing'.$i)){
                                $emptyListings = false;
                                $listingId = (int)$cRequest->getPost('listing'.$i);
                                if(isset($listings[$listingId])){
                                    $sendInquiries = true;

                                    $inquiry = new Inquiries();
                                    $inquiry->inquiry_type = 0;
                                    $inquiry->category_id = $categoryId;
                                    $inquiry->listing_id = $listingId;
                                    $inquiry->name = $name;
                                    $inquiry->email = $email;
                                    $inquiry->phone = $phone;
                                    $inquiry->region_id = $region;
                                    $inquiry->subregion_id = $subRegion;
                                    $inquiry->availability = $availability;
                                    $inquiry->preferred_contact = $contacted;
                                    $inquiry->description = $description;
                                    $inquiry->date_created = date('Y-m-d H:i:s');
                                    $inquiry->is_active = 1;

                                    $inquiry->save();

                                    // Prepare customer ids for email sent
                                    $customerId = $listings[$listingId]['customer_id'];
                                    if(!empty($customers[$customerId]) && $customers[$customerId]['notifications'] == 1){
                                        if(!in_array($customerId, $customerSent)){
                                            $customerSent[] = $customerId;
                                        }
                                    }
                                }else{
                                    $alert = A::t('directory', 'Input incorrect parameters').'. '.A::t('directory', 'Not all inquiries send').'.';
                                    $alertType = 'error';
                                }
                            }
                        }
                        // Send Emails
                        if(!empty($customerSent)){
                            foreach($customerSent as $customerId){
                                $emailResult = Website::sendEmailByTemplate(
                                    $customers[$customerId]['email'],
                                    'directory_new_inquiry',
                                    $customers[$customerId]['language_code'],
                                    array('{FIRST_NAME}' => $customers[$customerId]['first_name'], '{LAST_NAME}' => $customers[$customerId]['last_name'], '{LINK}' => A::app()->getRequest()->getBaseUrl().'inquiries/myInquiries')
                                );
                            }
                        }

                        if(empty($specificListing) && $emptyListings){
                            $alert = A::t('directory', 'You didn\'t select any listing');
                            $alertType = 'info';
                        }
                        if($sendInquiries){
                            if(empty($alert)){
                                $alert = A::t('directory', 'Thank you! Your inquiry has been successfully submitted.');
                                $alertType = 'success';
                            }
                            $alert .= ' '.A::t('directory', 'Please wait for at least few business days to receive a response to your inquiry. If you have any questions, please contact administration of the site.');
                            A::app()->getSession()->setFlash('alertType', $alertType);
                            A::app()->getSession()->setFlash('alert', $alert);
                            $this->redirect('inquiries/secondStep'.($showWidget ? '/widget/1' : ''));
                        }
                    }
                }
            }
        }else if(empty($specificListing)){
            if(!empty($categoryId)){
                $condition .= ' AND category_id = :category_id';
                $params = array(':category_id'=>$categoryId);
            }

            $listings    = ListingsCategories::model()->findAll(array('condition'=>$condition, 'groupBy'=>CConfig::get('db.prefix').'listings_categories.listing_id' ,'orderBy'=>CConfig::get('db.prefix').'listings.is_featured DESC, '.CConfig::get('db.prefix').'listings.date_published DESC, '.CConfig::get('db.prefix').'listings_categories.listing_id DESC', 'limit'=>'5'), $params);
            if(!empty($listings) && is_array($listings)){
                $showNotFoundMessage = false;
            }

            $defaultCategory = isset($allCategories[$categoryId]) ? $categoryId : 0;
        }

        if(!empty($specificListing)){
            $listings = array($specificListing->getFieldsAsArray());
        }

        if(!empty($alert)){
            $actionMessage = CWidget::create('CMessage', array($alertType, $alert, array()));
        }


        $this->_view->showWidget            = $showWidget;
        $this->_view->specificListing       = $specificListing;
        $this->_view->actionMessage         = $actionMessage;
        $this->_view->allCategories         = $allCategories;
        $this->_view->allLocations          = $allLocations;
        $this->_view->subLocations          = $subLocations;
        $this->_view->availabilityList      = $availabilityList;
        $this->_view->defaultAvailability   = $availability;
        $this->_view->defaultCategory       = $defaultCategory;
        $this->_view->defaultContacted      = $contacted;
        $this->_view->defaultDescription    = $description;
        $this->_view->defaultEmail          = $email;
        $this->_view->defaultName           = $name;
        $this->_view->defaultPhone          = $phone;
        $this->_view->defaultRegion         = $region;
        $this->_view->defaultSubRegion      = $subRegion;
        $this->_view->listings              = $listings;
        $this->_view->errorField            = $errorField;
        $this->_view->preferredContactsList = $preferredContactsList;
        $this->_view->showNotFoundMessage   = $showNotFoundMessage;

        if(!$showWidget){
            $this->_view->render('inquiries/firststep');
        }else{
            $this->_view->content = $this->_view->render('inquiries/firststep', true, true);
            $this->_view->render('inquiries/showwidget', true);
        }
    }

    /**
     * Second step in inquiry block action handler
     * @return void
     */
    public function secondStepAction()
    {
        $showWidget = (int)A::app()->getRequest()->getQuery('widget', 'int', 0) ? true : false;
        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');

        if($alertType){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array()));
            $this->_view->showWidget = $showWidget;
            if(!$showWidget){
                $this->_view->render('inquiries/secondstep');
            }else{
                $this->_view->content = $this->_view->render('inquiries/secondstep', true, true);
                $this->_view->render('inquiries/showwidget', true);
            }
        }else if($showWidget){
            $this->redirect('inquiries/firststep/widget/1');
        }else{
            if(CAuth::isLoggedIn() && !CAuth::isLoggedInAsAdmin()){
                $this->redirect('customers/dashboard');
            }else{
                $this->redirect(Website::getDefaultPage());
            }
        }
    }

    /**
     * manageReplies
     * This method to management replies
     *
     * @param int $inquiryId
     * @access public
     * @return void
     */
    public function manageReplies($inquiryId)
    {
        // set backend mode
        Website::setBackend();
        Website::prepareBackendAction('manageReplies', 'directory', 'inquiries/manage');

        $inquiry   = $this->_checkInquiryAccess($inquiryId);
        $alert     = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');
        $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));


    }

    /**
     * The action handler allows customers to view their all listings
     * @param $type the type inquiries (active, archived)
     * @return void
     */
    public function myInquiriesAction($type = 'active')
    {
         // block access to this controller for not-logged customers
        CAuth::handleLogin('customers/login', 'customer');
        // set frontend settings
        Website::setFrontend();

        $accountId = CAuth::getLoggedId();
        $customer = Customers::model()->find('account_id = :account_id AND is_active = 1 AND is_removed = 0', array(':account_id'=>$accountId));
        if(empty($customer)){
            $this->redirect('customers/logout');
        }

        $actionMessage = '';
        $typeTab = in_array($type, array('all', 'active', 'archived')) ? $type : 'active';
        $condition = '';
        $params = array();
        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');

        if($typeTab != 'all'){
            $condition .= 'is_active = '.($typeTab == 'archived' ? 0 : 1).' AND ';
        }
        $condition .= 'customer_id = '.(int)$customer->id;

        if(!empty($alert)){
            $actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }

        $this->_prepareFields();
        $this->_view->customerId = $customer->id;
        $this->_view->actionMessage = $actionMessage;
        $this->_view->condition = $condition;
        $this->_view->typeTab = $typeTab;
        $this->_view->render('inquiries/myinquiries');
    }

    public function customerPreviewAction($id, $type = '')
    {
        Website::setFrontend();

        $accountId = CAuth::getLoggedId();
        $customer = Customers::model()->find('account_id = :account_id AND is_active = 1 AND is_removed = 0', array(':account_id'=>$accountId));
        if(empty($customer)){
            $this->redirect('customers/logout');
        }

        $inquiry = Inquiries::model()->findByPk($id, 'customer_id = :customer_id', array('i:customer_id'=>$customer->id));
        if(!empty($inquiry)){
            $regionName = A::t('directory', 'Unknown');
            $subregionName = A::t('directory', 'Unknown');
            $regions = array();
            if($inquiry->region_id != 0){
                $regions[] = $inquiry->region_id;
            }
            if($inquiry->subregion_id != 0){
                $regions[] = $inquiry->subregion_id;
            }
            if(!empty($regions)){
                $regions = Regions::model()->findAll(CConfig::get('db.prefix').'regions.id IN ('.implode(',', $regions).')');

                if(!empty($regions) && is_array($regions)){
                    foreach($regions as $region){
                        if($region['id'] == $inquiry->region_id){
                            $regionName = $region['name'];
                        }
                        if($region['id'] == $inquiry->subregion_id){
                            $subregionName = $region['name'];
                        }
                    }
                }
            }

            $inquiryTypeList = array('0'=>A::t('directory', 'Standard'), '1'=>A::t('directory', 'Direct'));
            $availabilityList = array('0'=>A::t('directory', 'Anytime'), '1'=>A::t('directory', 'Morning'), '2'=>A::t('directory', 'Lunch'), '3'=>A::t('directory', 'Afternoon'), '4'=>A::t('directory', 'Evening'), '5'=>A::t('directory', 'Weekend'));
            $preferredContactsList = array('0'=>A::t('directory', 'By Phone or Email'), '1'=>A::t('directory', 'By Phone'), '2'=>A::t('directory', 'Via Email'));
            $activeList = array('0'=>'<span class="label-red">'.A::t('directory', 'No').'</span>', '1'=>'<span class="label-green">'.A::t('directory', 'Yes').'</span>');

			$this->_view->id = $id;
			$this->_view->type = $type;
            $this->_view->name = $inquiry->name;
            $this->_view->inquiryType = isset($inquiryTypeList[$inquiry->inquiry_type]) ? $inquiryTypeList[$inquiry->inquiry_type] : A::t('directory', 'Unknown');
            $this->_view->listingId = $inquiry->listing_id;
            $this->_view->listingName = $inquiry->listing_name;
            $this->_view->email = $inquiry->email;
            $this->_view->phone = $inquiry->phone;
            $this->_view->region = $regionName;
            $this->_view->subregion = $subregionName;
            $this->_view->availability = isset($availabilityList[$inquiry->availability]) ? $availabilityList[$inquiry->availability] : A::t('directory', 'Unknown');
            $this->_view->preferredContacts = isset($preferredContactsList[$inquiry->preferred_contact]) ? $preferredContactsList[$inquiry->preferred_contact] : A::t('directory', 'Unknown');
            $this->_view->description = $inquiry->description;
            $this->_view->dateCreated = CLocale::date($this->_view->dateTimeFormat, $inquiry->date_created);
            $this->_view->active = isset($activeList[$inquiry->is_active]) ? $activeList[$inquiry->is_active] : '<span class="label-gray">'.A::t('directory', 'Unknown').'</span>';
            $this->_view->render('inquiries/customerpreview');
        }else{
            A::app()->getSession()->setFlash('alert', '');
            A::app()->getSession()->setFlash('alertType', '');
            $this->redirect('inquiries/myInquiries');
        }
    }

    /**
     * Change status inquiries action handler
     * @param int $inquiryId
     * @param int $type
     * @return void
     */
    public function changeStatusAction($inquiryId, $type = '')
    {
        $urlRedirect = '';
        $inquiry = null;
        $alert = '';
        $alertType = '';
        $redirect = true;

        if(CAuth::isLoggedInAsAdmin()){
            Website::prepareBackendAction('edit', 'directory', 'customers/manage');
            $urlRedirect = 'inquiries/manage'.(!empty($type) ? '/type/'.$type : '');
            $redirect = false;
        }else if(CAuth::isLoggedIn()){
            $urlRedirect = 'inquiries/myInquiries'.(!empty($type) ? '/type/'.$type : '');
            $accountId = CAuth::getLoggedId();
            $customer = Customers::model()->find('account_id = :account_id', array('i:account_id'=>$accountId));
            if(empty($customer)){
                $this->redirect('customers/logout');
            }
            $inquiry = Inquiries::model()->findByPk($inquiryId, 'customer_id = :customer_id', array('i:customer_id'=>$customer->id));
            if(empty($inquiry)){
                $alert = A::t('directory', 'Input incorrect parameters');
                $alertType = 'error';
            }else{
                $redirect = false;
            }
        }else{
            $urlRedirect = 'customers/login';
        }

        if(!$redirect){
            if(empty($inquiry)){
                $inquiry = Inquiries::model()->findByPk($inquiryId);
            }
            if(!empty($inquiry)){
                if(Inquiries::model()->updateByPk($inquiry->id, array('is_active'=>($inquiry->is_active == 1 ? '0' : '1')))){
                    $alert = A::t('directory', 'Status has been successfully changed!');
                    $alertType = 'success';
                }else{
                    $alert = A::t('directory', 'Status changing error');
                    $alertType = 'error';
                }
            }
        }

        if(!empty($alertType)){
            A::app()->getSession()->setFlash('alert', $alert);
            A::app()->getSession()->setFlash('alertType', $alertType);
        }
        $this->redirect($urlRedirect);
    }

    /**
     * Check if passed record ID is valid
     * @param int $id
     * @return Inquiries
     */
    private function _checkInquiryAccess($id = 0)
    {
        $inquiry = Inquiries::model()->findByPk($id);
        if(!$inquiry){
            $this->redirect('inquiries/manage');
        }
        return $inquiry;
    }

    /**
     * Prepare fields
     * @return void
     */
    private function _prepareFields()
    {
        $allLocations       = Regions::model()->findAll();
        $locations          = array();
        $filterLocations    = array();
        if(!empty($allLocations)){
            foreach($allLocations as $location){
                $locations[$location['id']] = $location['name'];
                if($location['parent_id'] == 0){
                    $filterLocations[$location['id']] = $location['name'];
                }
            }
        }
        $allCategories = DirectoryComponent::getAllCategoriesArray();
        $categories = array();
        if(!empty($allCategories)){
            foreach($allCategories as $id => $category){
                $categories[$id] = ltrim($category, ' -');
            }
        }
        $this->_view->categories         = $categories;
        $this->_view->filterCategories   = $allCategories;
        $this->_view->locations          = $locations;
        $this->_view->filterLocations    = $filterLocations;
    }
}
