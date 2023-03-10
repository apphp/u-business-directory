<?php
/**
 * Business directory orders controller
 * This controller intended to both Backend and Frontend modes
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct              _checkActionAccess
 * indexAction
 * manageAction
 * previewAction
 * deleteAction
 * checkoutAction
 * myOrdersAction
 * formPayAction
 * handlePaymentAction
 *
 */

class OrdersController extends CController
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
            // set meta tags according to active business directory orders
            Website::setMetaTags(array('title'=>A::t('directory', 'Orders Management')));
            // set backend mode
            Website::setBackend();

            $this->_view->actionMessage = '';
            $this->_view->errorField = '';

            $this->_view->tabs = DirectoryComponent::prepareTabs('orders');
        }
        $this->_settings = Bootstrap::init()->getSettings();
        $this->_view->dateTimeFormat = $this->_settings->datetime_format;
        $this->_view->dateFormat = $this->_settings->date_format;
        $this->_view->numberFormat = $this->_settings->number_format;
        $this->_view->currencySymbol = A::app()->getCurrency('symbol');
    }

    /**
     * Controller default action handler
     * @return void
     */
    public function indexAction()
    {
        $this->redirect('orders/manage');
    }

    /**
     * Manage action handler
     * @return void
     */
    public function manageAction()
    {
        Website::prepareBackendAction('manage', 'directory', 'orders/manage');

        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');

        $allStatus= array('0'=>A::t('directory', 'Preparing'), '1'=>A::t('directory', 'Pending Payment'), '2'=>A::t('directory', 'Completed'), '3'=>A::t('directory', 'Payment Error'), '4'=>A::t('directory', 'Refunded'), '5'=>A::t('directory', 'Canceled'));
        $adverticePlans = array();
        $allAdvertisePlans = Plans::model()->findAll();
        if(!empty($allAdvertisePlans)){
            foreach($allAdvertisePlans as $plan){
                $adverticePlans[$plan['id']] = $plan['name'];
            }
        }

        if(!empty($alert)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }

        $this->_view->allStatus = $allStatus;
        $this->_view->adverticePlans = $adverticePlans;
        $this->_view->render('orders/manage');
    }

    /**
     * Preview action handler
     * @return void
     */
    public function editAction($id)
    {
        Website::prepareBackendAction('edit', 'directory', 'orders/manage');

        $unknown            = A::t('directory', 'Unknown');
        $planName           = $unknown;
        $listingName        = $unknown;
        $allStatus          = array('0'=>A::t('directory', 'Preparing'), '1'=>A::t('directory', 'Pending'), '2'=>A::t('directory', 'Paid'), '3'=>A::t('directory', 'Completed'), '4'=>A::t('directory', 'Refunded'));
        $allPaymentTypes    = array();
        $allPayments        = PaymentProviders::model()->findAll('is_active = 1');
        if(!empty($allPayments) && is_array($allPayments)){
            foreach($allPayments as $payment){
                $allPaymentTypes[$payment['id']] = $payment['name'];
            }
        }
        $allPaymentMethods  = array('0'=>'Payment Company Account', '1'=>'Credit Card', '2'=>'E-Check');
        $order              = $this->_checkActionAccess($id);
        $allStatus = array();
        switch($order->status){
            case '0':
                $allStatus[0] = A::t('directory', 'Preparing');
            case '1':
                $allStatus[1] = A::t('directory', 'Pending Payment');
            case '2':
                $allStatus[2] = A::t('directory', 'Completed');
        }
        if($order->status < 2 || $order->status == 5){
            $allStatus[5] = A::t('directory', 'Canceled');
        }else if($order->status == 2 || $order->status == 4){
            $allStatus[4] = A::t('directory', 'Refunded');
        }else if($order->status == 3){
            $allStatus[3] = A::t('directory', 'Payment Error');
        }
        $customerName       = $order->first_name.' '.$order->last_name;
        $orderStatus        = isset($allStatus[$order->status]) ? $allStatus[$order->status] : $unknown;
        $orderPaymentMethod = isset($allPaymentMethods[$order->payment_method]) ? $allPaymentMethods[$order->payment_method] : $unknown;

        $listing = Listings::model()->findByPk($order->listing_id);
        if(!empty($listing)){
            $listingName = $listing->business_name;
        }

        $plan = Plans::model()->findByPk($order->advertise_plan_id);
        if(!empty($plan)){
            $planName = $plan->name;
        }

        $this->_view->id                 = $order->id;
        $this->_view->listingName        = $listingName;
        $this->_view->planName           = $planName;
        $this->_view->customerName       = $customerName;
        $this->_view->orderPaymentMethod = $orderPaymentMethod;
        $this->_view->orderStatus        = $orderStatus;
        $this->_view->allStatus          = $allStatus;
        $this->_view->allPaymentTypes    = $allPaymentTypes;
        $this->_view->allPaymentMethods  = $allPaymentMethods;

        $this->_view->render('orders/edit');
    }

    /**
     * Preview action handler
     * @return void
     */
    public function previewAction($id)
    {
        Website::prepareBackendAction('manage', 'directory', 'orders/manage');

        $unknown            = A::t('directory', 'Unknown');
        $planName           = $unknown;
        $listingName        = $unknown;
        $allPaymentTypes    = array();
        $allPayments        = PaymentProviders::model()->findAll('is_active = 1');
        if(!empty($allPayments) && is_array($allPayments)){
            foreach($allPayments as $payment){
                $allPaymentTypes[$payment['id']] = $payment['name'];
            }
        }
        $allPaymentMethods  = array('0'=>'Payment Company Account', '1'=>'Credit Card', '2'=>'E-Check');
        $allStatus          = array('0'=>A::t('directory', 'Preparing'), '1'=>A::t('directory', 'Pending Payment'), '2'=>A::t('directory', 'Completed'), '3'=>A::t('directory', 'Payment Error'), '4'=>A::t('directory', 'Refunded'), '5'=>A::t('directory', 'Canceled'));
        $order              = $this->_checkActionAccess($id);
        $customerName       = $order->first_name.' '.$order->last_name;
        $orderStatus        = isset($allStatus[$order->status]) ? $allStatus[$order->status] : $unknown;
        $orderPaymentType   = isset($allPaymentTypes[$order->payment_type]) ? $allPaymentTypes[$order->payment_type] : $unknown;
        $orderPaymentMethod = isset($allPaymentMethods[$order->payment_method]) ? $allPaymentMethods[$order->payment_method] : $unknown;

        $listing = Listings::model()->findByPk($order->listing_id);
        if(!empty($listing)){
            $listingName = $listing->business_name;
        }

        $plan = Plans::model()->findByPk($order->advertise_plan_id);
        if(!empty($plan)){
            $planName = $plan->name;
        }

        $this->_view->listingName        = $listingName;
        $this->_view->planName           = $planName;
        $this->_view->customerName       = $customerName;
        $this->_view->orderPaymentType   = $orderPaymentType;
        $this->_view->orderPaymentMethod = $orderPaymentMethod;
        $this->_view->orderStatus        = $orderStatus;
        $this->_view->orderStatusId      = $order->status;
        $this->_view->listingId          = $order->listing_id;
        $this->_view->orderNumber        = $order->order_number;
        $this->_view->orderDescription   = $order->order_description;
        $this->_view->orderPrice         = $order->order_price;
        $this->_view->orderDateCreated   = $order->created_date;
        $this->_view->orderDatePayment   = $order->payment_date;
        $this->_view->orderStatusChanged = $order->status_changed;

        $this->_view->render('orders/preview');
    }

    /**
     * The action handler allows customers to view their all listings
     * @param $type the type orders (all, preparing, pending, paid, completed, refunded)
     * @return void
     */
    public function myOrdersAction()
    {
         // block access to this controller for not-logged customers
        CAuth::handleLogin('customers/login', 'customer');
        // set frontend settings
        Website::setFrontend();

        $actionMessage = '';
        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');

        $accountId = CAuth::getLoggedId();
        $customer = Customers::model()->find('account_id = :account_id AND is_active = 1 AND is_removed = 0', array(':account_id'=>$accountId));
        if(empty($customer)){
            $this->redirect('customers/logout');
        }

        $adverticePlans = array();
        $allAdvertisePlans = Plans::model()->findAll();
        if(!empty($allAdvertisePlans)){
            foreach($allAdvertisePlans as $plan){
                $adverticePlans[$plan['id']] = $plan['name'];
            }
        }

        if(!empty($alert)){
            $actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }

        $this->_view->customerId = $customer->id;
        $this->_view->actionMessage = $actionMessage;
        $this->_view->adverticePlans = $adverticePlans;
        $this->_view->typeTab = 'all';
        $this->_view->render('orders/myOrders');
    }

    /**
     * Pay listing action hendler
     * @param int $listingId
     * @param string $typePayment
     * @return void
     */
    public function checkoutAction($listingId = 0)
    {
         // block access to this controller for not-logged customers
        CAuth::handleLogin('customers/login', 'customer');
        // set frontend settings
        Website::setFrontend();

        $listingIds    = array();
        $validIds      = array();
        $realIds       = array();
        $planList      = array();
        $freePlanId    = 0;
        $totalPrice    = 0;
        $alert         = '';
        $alertType     = '';
        $actionMessage = '';

        $cRequest = A::app()->getRequest();
        $listingList = '';
        // Next Version
        //$listingList = $cRequest->getPost('listingIds');

        $alert     = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');

        if(!is_array($listingList) || $listingList){
            if(!empty($listingId)){
                $listingIds[] = $listingId;
            }
        }else{
            $listingIds = array_merge($listingId, $listingList);
        }

        if(!empty($listingIds)){
            foreach($listingIds as $listingId){
                if(CValidator::isInteger($listingId)){
                    $validIds[] = $listingId;
                }
            }
        }

        if(empty($validIds)){
            A::app()->getSession()->setFlash('alert', 'Input incorrect parameters');
            A::app()->getSession()->setFlash('alertType', 'error');
            $this->redirect('orders/myOrders');
        }

        $allAdvertisePlans = Plans::model()->findAll();
        if(is_array($allAdvertisePlans)){
            foreach($allAdvertisePlans as $plan){
                $days = $plan['duration'];
                if($days < 0){
                    $duration = A::t('directory', 'Unlimited');
                }else if($days < 30){
                    $duration = ($days == 1 ? '1 '.A::t('directory', 'Day') : $days.' '.A::t('directory', 'Days'));
                }else if($days < 365){
                    $duration = (round($days/30,1) == 1 ? '1 '.A::t('directory', 'Month') : round($days/30,1).' '.A::t('directory', 'Months'));
                }else{
                    $duration = (round($days/365,1) == 1 ? '1 '.A::t('directory', 'Year') : round($days/365,1).' '.A::t('directory', 'Years'));
                }
                $planList[$plan['id']] = array('name'=>$plan['name'], 'price'=>$plan['price'], 'duration'=>$duration);
                if($plan['price'] == 0){
                    $freePlanId = $plan['id'];
                }
            }
        }
        $condition = CConfig::get('db.prefix').'listings.is_approved = 0 '.($freePlanId ? ' AND '.CConfig::get('db.prefix').'listings.advertise_plan_id != '.(int)$freePlanId : '');

        $accountId = CAuth::getLoggedId();
        $customer = Customers::model()->find('account_id = :account_id AND is_active = 1 AND is_removed = 0', array(':account_id'=>$accountId));
        if(!empty($customer)){
            $condition = '('.CConfig::get('db.prefix').'listings.customer_id = '.(int)$customer->id.' AND '.$condition.')';
        }else{
            $this->redirect('customers/logout');
        }

        $listings = Listings::model()->findAll(CConfig::get('db.prefix').'listings.id IN ('.implode(',',$validIds).') AND '.$condition);

        if(!empty($listings) && is_array($listings)){
            foreach($listings as $listing){
                $totalPrice += $planList[$listing['advertise_plan_id']]['price'];
            }
        }

        if(count($listings) < count($listingIds)){
            $alert = 'Input incorrect parameters';
            $alertType = 'warning';
            $this->redirect('orders/myOrders');
        }

        if(!empty($alert)){
            $actionMessage = CWidget::create('CMessage', array($alertType, $alert, array()));
        }

        $this->_view->listing           = array_shift($listings);
        $this->_view->adverticePlanList = $planList;
        $this->_view->totalPrice        = $totalPrice;
        $this->_view->actionMessage     = $actionMessage;
        $this->_view->providers         = PaymentProviders::model()->findAll('is_active = 1');

        $this->_view->render('orders/checkout');
    }

    /**
     * View Form for pay
     * @param int $listingId
     * @return void
     */
    public function formPayAction($listingId = 0)
    {
         // block access to this controller for not-logged customers
        CAuth::handleLogin('customers/login', 'customer');
        // set frontend settings
        Website::setFrontend();

        $accountId = CAuth::getLoggedId();
        $customer = Customers::model()->find(CConfig::get('db.prefix').'customers.account_id = :account_id', array(':account_id'=>$accountId));
        if(empty($customer)){
            $this->redirect('customers/logout');
        }

        $act = A::app()->getRequest()->getPost('act', 'string');
        $type = A::app()->getRequest()->getPost('type', 'string');
        if(empty($act)){
            $alert = '';
            $alertType = '';
        }else if(empty($type)){
            $alert = A::t('directory', 'The field {field} cannot be empty! Please re-enter.', array('{field}'=>A::t('directory', 'Payment Method')));
            $alertType = 'validation';
        }else{
            $providers = PaymentProviders::model()->findAll('is_active = 1');
            $providers = CArray::flipByField($providers, 'code');
            if(!in_array($type, array_keys($providers))){
                $alert = A::t('directory', 'Input incorrect parameters');
                $alertType = 'error';
            }
        }

        if(!empty($alertType) || $act != 'send'){
                A::app()->getSession()->setFlash('alert', $alert);
                A::app()->getSession()->setFlash('alertType', $alertType);
            $this->redirect('orders/checkout/listingId/'.$listingId);
        }

        $listing = $this->_checkListingAccess($listingId, CConfig::get('db.prefix').'listings.customer_id = :customer_id AND '.CConfig::get('db.prefix').'listings.is_approved = 0', array(':customer_id'=>$customer->id));

        // Check whether the payment is made
        $order = Orders::model()->find(CConfig::get('db.prefix').'orders.listing_id = :listing_id AND '.CConfig::get('db.prefix').'orders.status != 0', array(':listing_id'=>$listingId));
        if(!empty($order)){
            $this->redirect('orders/myOrders');
        }

        $adverticePlan = Plans::model()->findByPk($listing->advertise_plan_id);
        if(empty($adverticePlan) || $adverticePlan->price == 0){
            $this->redirect('orders/myOrders');
        }

        CLoader::library('ipgw/PaymentProvider.php');
        $provider = PaymentProvider::init($type);
        $providerSettings = PaymentProviders::model()->find("code = :code", array(':code'=>$type));

        $order = Orders::model()->find('customer_id = :customer_id AND status = 0', array(':customer_id'=>$customer->id));
        if(empty($order)){
            $order = new Orders();
            $order->order_number = CHash::getRandomString(15); // Make function random string
        }
        $order->order_description = '';
        $order->order_price = $adverticePlan->price;
        $order->total_price = $adverticePlan->price;
        $order->listing_id = $listing->id;
        $order->currency = 1;
        $order->advertise_plan_id = $adverticePlan->id;
        $order->customer_id = $customer->id;
        $order->transaction_number = '';
        $order->created_date = date('Y-m-d H:i:s');
        $order->payment_id = $providerSettings->id;
        $order->payment_method = 0;
        $order->status = 0;

        $order->save();

        $merchantId = 'sales-facilitator@apphp.com';
        $back = 'orders/checkout/listingId/'.$listing->id;
        $currencyCode = CAuth::getLoggedParam('currency_code');

        $params = array(
            'item_name'     => $listing->business_name,
            'item_number'   => $listing->id,
            'amount'        => $order->total_price,
            'custom'        => $order->order_number,      // order ID
            'lc'            => '',      // country's language
            'cn'            => '',      // If this variable is omitted, the default label above the note field is "Add special instructions to merchant."
            'rm'            => '',      // Return method. 0  all shopping cart payments use the GET method, 1  the buyer's browser is redirected to the return URL by using the GET method, but no payment variables are included, 2  the buyer's browser is redirected to the return URL by using the POST method, and all payment variables are included
                                        // The rm variable takes effect only if the return variable is set.
            'currency_code' => $currencyCode,   // The currency of the payment. The default is USD.
            'no_shipping'   => '',      // Do not prompt buyers for a shipping address.
            'address1'      => $customer->address,
            'address2'      => $customer->address_2,
            'city'          => $customer->city,
            'zip'           => $customer->zip_code,
            'state'         => $customer->state,
            'country'       => $customer->country_code,
            'first_name'    => $customer->first_name,
            'last_name'     => $customer->last_name,
            'email'         => $customer->email,
            'phone'         => $customer->phone,
            'mode'          => $providerSettings->mode,
            'back'          => $back,       // Back to Shopping Cart - defined by developer
            'notify'        => A::app()->getRequest()->getBaseUrl().'paymentProviders/handlePayment/provider/'.$type.'/handler/orders', // IPN processing link
            'cancel'        => A::app()->getRequest()->getBaseUrl().'orders/checkout/listingId/'.$listing->id,                       // Cancel order link
            'cancel_return' => A::app()->getRequest()->getBaseUrl().'orders/checkout/listingId/'.$listing->id,                       // Cancel & return to site link
        );

        if($type == 'paypal'){
            $params = array_merge($params, array(
                'merchant_id'   => $providerSettings->merchant_id,
            ));
        }

        $form = $provider->drawPaymentForm($params);

        $this->_view->actionMessage = '';
        $this->_view->form = $form;
        $this->_view->customer = $customer;
        $this->_view->listing = $listing;
        $this->_view->order = $order;
        $this->_view->providerSettings = $providerSettings;
        $this->_view->render('orders/form');
    }

    /**
     * Notify payment
     * @param string $type
     * @return void
     */
    public function paymentHandlerAction($type)
    {
        Website::setFrontend();

        $alert = '';
        $alertType = '';

        $paymentProvider = PaymentProviders::model()->find('code = :code AND is_active = 1', array(':code'=>$provider));
        if($paymentProvider){

            // Load payment library
            CLoader::library('ipgw/PaymentProvider.php');

            $paymentHandler = PaymentProvider::init($provider);
            $result = $paymentHandler->handlePayment(array('log' => true));

            if(!empty($result['error'])){
                $alert = A::t('directory', 'Unknown Payment Status - please try again.');
                $alertType = 'error';
            }else{
                if(!empty($result['other']) && !empty($result['other']['orderNumber'])){
                    $order = Orders::model()->find('order_number = :order_number', array(':order_number'=>$result['other']['orderNumber']));
                }else{
                    $accountId = CAuth::getLoggedId();
                    if($accountId){
                        $customer = Customers::model()->find('account_id = :account_id', array(':account_id'=>$accountId));
                        if(!empty($customer)){
                            $order = Orders::model()->find('customer_id = :customer_id AND status = 0', array(':customer_id'=>$customer->id));
                        }else{
                            $this->redirect('customers/logout');
                        }
                    }else{
                        $this->redirect('customers/login');
                    }
                }
                if(!empty($order)){
                    $order->status_changed = date('Y-m-d H:i:s');
                    if($result['status'] == 1){
                        // Pending
                        $order->status = 1;
                    }else if($result['status'] == 2 || $result['status'] == 3){
                        // Completed
                        $order->status = 2;
                        $order->payment_date = date('Y-m-d H:i:s');
                    }else if($result['status'] > 3){
                        // Rejected
                        $order->status = 3;
                    }
                    $order->save();
                    $alert = A::t('directory', '');
                    $alertType = 'success';
                }else{
                    // echo 'error .....';
                }
            }
        }else{
            //echo 'error .....';
        }

        if(!empty($alert)){
            A::app()->getSession()->setFlash('alert', $alert);
            A::app()->getSession()->setFlash('alertType', $alertType);
        }
        $this->_view->render('orders/myOrders');
    }

    /**
     * Check if passed record ID is valid
     * @param int $id
     * @return Orders
     */
    private function _checkActionAccess($id = 0)
    {
        $order = Orders::model()->findByPk($id);
        if(!$order){
            $this->redirect('orders/manage');
        }
        return $order;
    }

    /**
     * Check listing Id is valid
     * @param int $listingId
     * @return Listings
     */
    private function _checkListingAccess($listingId, $condition = '', $params = array())
    {
        $listing = Listings::model()->findByPk($listingId, $condition, $params);
        if(empty($listing)){
            $this->redirect('orders/myOrders');
        }
        return $listing;
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
}
