<?php
/**
 * Business directory customers controller
 * This controller intended to both Backend and Frontend modes
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct              _checkAccountsAccess
 * indexAction              _checkCustomerAccess
 * manageAction             _prepareAccountFields
 * addAction                _logout
 * editAction
 * deleteAction
 * loginAction
 * logoutAction
 * registrationAction
 * restorePasswordAction
 * confirmRegistrationAction
 * dashboardAction
 * myAccountAction
 * editAccountAction
 * removeAccountAction
 * activeStatusAction
 * loggedOutAction
 * manageListingAction
 * getCustomersAction
 *
 */

class CustomersController extends CController
{
    private $checkBruteforce;
    private $redirectDelay;
    private $badLogins;
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

        $this->_settings = Bootstrap::init()->getSettings();
        $this->_cSession = A::app()->getSession();

        $this->_view->actionMessage = '';
        $this->_view->errorField = '';

        if(CAuth::isLoggedInAsAdmin()){
            // set meta tags according to active business directory customers
            Website::setMetaTags(array('title'=>A::t('directory', 'Customers Management')));

            $this->_view->tabs = DirectoryComponent::prepareTabs('accounts');
            $this->_view->subTabs = DirectoryComponent::prepareSubTabs('accounts', 'customers');
        }
    }

    /**
     * Controller default action handler
     * @return void
     */
    public function indexAction()
    {
        if(CAuth::isLoggedInAs('customer')){
            $this->redirect('customers/dashboard');    // !Not done
        }else{
            $this->redirect('customers/manage');
        }
    }

    /**
     * Manage action handler
     * @return void
     */
    public function manageAction()
    {
        // set backend mode
        Website::setBackend();
        Website::prepareBackendAction('manage', 'customer', 'customers/manage');
        $this->_prepareAccountFields();

        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');

        $listingsCounts = Listings::model()->count(array('count'=>'*', 'select'=>'customer_id', 'groupBy'=>'customer_id', 'allRows'=>true));
        $customerListings = array();
        foreach($listingsCounts as $listtingCount){
            $customerListings[$listtingCount['customer_id']] = $listtingCount['cnt'];
        }

        $this->_view->listingsCounts = $customerListings;

        if(!empty($alert)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }

        $this->_view->render('customers/manage');
    }
    /**
     * Manage Listing in Customers action handler
     * @param int $customerId
     * @return void
     */
    public function manageListingsAction($customerId)
    {
        // set backend mode
        Website::setBackend();
        Website::prepareBackendAction('manage', 'customer', 'customers/manage');
        $customer = $this->_checkCustomerAccess($customerId);

        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');

        $advertisePlans = Plans::model()->findAll();
        $advertisePlanNames = array();
        foreach($advertisePlans as $advertisePlan){
            $advertisePlanNames[$advertisePlan['id']] = $advertisePlan['name'];
        }

        if(!empty($alert)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }

        $this->_view->advertisePlanNames = $advertisePlanNames;
        $this->_view->customerId = $customerId;
        $this->_view->customerName = $customer->first_name.' '.$customer->last_name;
        $this->_view->dateTimeFormat  = Bootstrap::init()->getSettings()->datetime_format;
        $this->_view->render('customers/managelistings');
    }

    /**
     * Add new action handler
     * @return void
     */
    public function addAction()
    {
        // set backend mode
        Website::setBackend();
        Website::prepareBackendAction('add', 'customer', 'customers/manage');
        $this->_prepareAccountFields();

        $cRequest = A::app()->getRequest();
        if($cRequest->isPostRequest()){
            $this->_view->countryCode = $cRequest->getPost('country_code');
            $this->_view->stateCode = $cRequest->getPost('state');
        }else{
            $this->_view->countryCode = $this->_view->defaultCountryCode;
            $this->_view->stateCode = '';
        }

        // prepare salt
        $this->_view->salt = '';
        if(A::app()->getRequest()->getPost('password') != ''){
            $this->_view->salt = CConfig::get('password.encryptSalt') ? CHash::salt() : '';
        }

        $this->_view->render('customers/add');
    }

    /**
     * Edit business directory customers action handler
     * @param int $id
     * @return void
     */
    public function editAction($id = 0)
    {
        // set backend mode
        Website::setBackend();
        Website::prepareBackendAction('edit', 'customer', 'customers/manage');
        $customer = $this->_checkCustomerAccess($id);
        $this->_prepareAccountFields();

        $cRequest = A::app()->getRequest();
        if($cRequest->isPostRequest()){
            $this->_view->countryCode = $cRequest->getPost('country_code');
            $this->_view->stateCode = $cRequest->getPost('state');
        }else{
            $this->_view->countryCode = $customer->country_code;
            $this->_view->stateCode = $customer->state;
        }

        $this->_view->id = $customer->id;
        // fetch datetime format from settings table
        $this->_view->dateTimeFormat = Bootstrap::init()->getSettings('datetime_format');

        // prepare salt
        $this->_view->salt = '';
        if(A::app()->getRequest()->getPost('password') != ''){
            $this->_view->salt = CConfig::get('password.encryptSalt') ? CHash::salt() : '';
            A::app()->getRequest()->setPost('salt', $this->_view->salt);
        }

        $this->_view->render('customers/edit');
    }

    /**
     * Delete action handler
     * @param int $id
     * @return void
     */
    public function deleteAction($id = 0)
    {
        // set backend mode
        Website::setBackend();
        Website::prepareBackendAction('delete', 'customer', 'customers/manage');
        $customer = $this->_checkCustomerAccess($id);
        $this->_prepareAccountFields();

        $alert = '';
        $alertType = '';

        if($customer->delete()){
            $alert = A::t('directory', 'Customer deleted successfully');
            $alertType = 'success';
        }else{
            if(APPHP_MODE == 'demo'){
                $alert = CDatabase::init()->getErrorMessage();
                $alertType = 'warning';
            }else{
                $alert = A::t('directory', 'Customer deleting error');
                $alertType = 'error';
            }
        }

        if(!empty($alert)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }

        $listingsCounts = Listings::model()->count(array('count'=>'*', 'select'=>'customer_id', 'groupBy'=>'customer_id', 'allRows'=>true));
        $customerListings = array();
        foreach($listingsCounts as $listtingCount){
            $customerListings[$listtingCount['customer_id']] = $listtingCount['cnt'];
        }

        $this->_view->listingsCounts = $customerListings;
        $this->_view->render('customers/manage');
    }

    /**
     * Customer login action handler
     * @param string $type
     * @return void
     */
    public function loginAction($type = '')
    {
        // Redirect logged in customers
        CAuth::handleLoggedIn('customers/dashboard', 'customer');

        // Check if login action is allowed
        if(!ModulesSettings::model()->param('directory', 'customer_allow_login')){
            $this->redirect(Website::getDefaultPage());
        }

        // Social login
        if(!empty($type)){
            $lowType = strtolower($type);

            $config = array();
            $config['returnUrl'] = 'customers/login';
            $config['model'] = 'Customers';

            SocialLogin::config($config);
            SocialLogin::login($lowType);
        }

        // Set frontend mode
        Website::setFrontend();

        $this->_view->allowRememberMe = ModulesSettings::model()->param('directory', 'customer_allow_remember_me');
        $this->_view->allowRegistration = ModulesSettings::model()->param('directory', 'customer_allow_registration');
        $this->_view->allowResetPassword = ModulesSettings::model()->param('directory', 'customer_allow_restore_password');

        //#000
        $this->checkBruteforce = CConfig::get('validation.bruteforce.enable');
        $this->redirectDelay = (int)CConfig::get('validation.bruteforce.redirectDelay', 3);
        $this->badLogins = (int)CConfig::get('validation.bruteforce.badLogins', 5);

        $customer = new Accounts();

        // perform auto-login "remember me"
        if(!CAuth::isLoggedIn()){
            if($this->_view->allowRememberMe){
                parse_str(A::app()->getCookie()->get('customerAuth'));
                if(!empty($usr) && !empty($hash)){
                    $username = CHash::decrypt($usr, CConfig::get('password.hashKey'));
                    $password = $hash;
                    if($customer->login($username, $password, 'customer', true, true)){
                        $this->redirect('customers/dashboard');
                    }
                }
            }
        }

        $cRequest = A::app()->getRequest();
        $this->_view->username = $cRequest->getPost('login_username');
        $this->_view->password = $cRequest->getPost('login_password');
        $this->_view->remember = $cRequest->getPost('remember');
        $alert = A::t('directory', 'Login Message');
        $alertType = '';

        if($cRequest->getPost('act') == 'send'){
            // perform login form validation
            $fields = array();
            $fields['login_username'] = array('title'=>A::t('directory', 'Username'), 'validation'=>array('required'=>true, 'type'=>'any', 'minLength'=>4, 'maxLength'=>25));
            $fields['login_password'] = array('title'=>A::t('directory', 'Password'), 'validation'=>array('required'=>true, 'type'=>'any', 'minLength'=>4, 'maxLength'=>20));
            if($this->_view->allowRememberMe) $fields['remember'] = array('title'=>A::t('directory', 'Remember Me'), 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1)));
            $result = CWidget::create('CFormValidation', array(
                'fields' => $fields
            ));
            if($result['error']){
                $alert = $result['errorMessage'];
                $alertType = 'validation';
                $this->_view->errorField = $result['errorField'];
            }else{
                if($customer->login($this->_view->username, $this->_view->password, 'customer', false, ($this->_view->allowRememberMe && $this->_view->remember))){
                    if($this->_view->allowRememberMe && $this->_view->remember){
                        // username may be decoded
                        $usernameHash = CHash::encrypt($this->_view->username, CConfig::get('password.hashKey'));
                        // password cannot be decoded, so we save ID + username + salt + HTTP_USER_AGENT
                        $httpUserAgent = A::app()->getRequest()->getUserAgent();
                        $passwordHash = CHash::create(CConfig::get('password.encryptAlgorithm'), $customer->id.$customer->username.$customer->getPasswordSalt().$httpUserAgent);
                        A::app()->getCookie()->set('customerAuth', 'usr='.$usernameHash.'&hash='.$passwordHash, (time() + 3600 * 24 * 14));
                    }
                    //#001 clean login attempts counter
                    if($this->checkBruteforce){
                        A::app()->getSession()->remove('customerLoginAttempts');
                        A::app()->getCookie()->remove('customerLoginAttemptsAuth');
                    }
                    $this->redirect('customers/dashboard');
                }else{
                    $alert = $customer->getErrorDescription();
                    $alertType = 'error';
                    $this->_view->errorField = 'username';
                }
            }

            if(!empty($alert)){
                $this->_view->username = $cRequest->getPost('login_username');
                $this->_view->password = $cRequest->getPost('login_password');
                if($this->_view->allowRememberMe) $this->_view->remember = $cRequest->getPost('remember', 'string');
                $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert));

                //#002 increment login attempts counter
                if($this->checkBruteforce && $this->_view->username != '' && $this->_view->password != ''){
                    $logAttempts = A::app()->getSession()->get('customerLoginAttempts', 1);
                    if($logAttempts < $this->badLogins){
                        A::app()->getSession()->set('customerLoginAttempts', $logAttempts+1);
                    }else{
                        A::app()->getCookie()->set('customerLoginAttemptsAuth', md5(uniqid()));
                        sleep($this->redirectDelay);
                        $this->redirect('customers/login');
                    }
                }
            }
        }else{
            //#003 validate login attempts coockie
            if($this->checkBruteforce){
                $logAttempts = A::app()->getSession()->get('customerLoginAttempts', 0);
                $logAttemptsAuthCookie = A::app()->getCookie()->get('customerLoginAttemptsAuth');
                $logAttemptsAuthPost = $cRequest->getPost('customerLoginAttemptsAuth');
                if($logAttempts >= $this->badLogins){
                    if($logAttemptsAuthCookie != '' && $logAttemptsAuthCookie == $logAttemptsAuthPost){
                        A::app()->getSession()->remove('customerLoginAttempts');
                        A::app()->getCookie()->remove('customerLoginAttemptsAuth');
                        $this->redirect('customers/login');
                    }
                }else if(!empty($logAttemptsAuthPost)){
                    // If the lifetime of the session ended, and confirm the button has not been pressed
                    A::app()->getCookie()->remove('customerLoginAttemptsAuth');
                    $this->redirect('customers/login');
                }
            }
            $this->_view->actionMessage = CWidget::create('CMessage', array('info', $alert));
        }

        $this->_view->buttons = SocialLogin::drawButtons(array(
            'facebook'=>'customers/login/type/facebook',
            'twitter'=>'customers/login/type/twitter',
            'google'=>'customers/login/type/google')
        );

        $this->_view->render('customers/login');
    }

    /**
     * Returns customers
     * @return json
     */
    public function getCustomersAction()
    {
        // set backend mode
        Website::setBackend();
        // Block access if this is not AJAX request
        $this->_cRequest = A::app()->getRequest();
        if(!$this->_cRequest->isAjaxRequest()){
            $this->redirect(Website::getDefaultPage());
        }

        $arr = array();
        $params = array();

        $act    = $this->_cRequest->getPost('act');
        $search = $this->_cRequest->getPost('search');
        if($act == 'send'){
            $fullName = explode(' ', $search, 3);
            if(!empty($fullName)){
                if(count($fullName) == 1){
                    $fullName[0] = strip_tags(CString::quote($fullName[0]));
                    $params[':username']   = $fullName[0].'%';
                    $params[':first_name'] = $fullName[0].'%';
                    $params[':last_name']  = $fullName[0].'%';
                    $params[':email']      = '%'.$fullName[0].'%';

                    $condition = 'username LIKE :username OR first_name LIKE :first_name OR last_name LIKE :last_name OR email LIKE :email';
                }else if(count($fullName) == 2){
                    $fullName[0] = strip_tags(CString::quote($fullName[0]));
                    $fullName[1] = strip_tags(CString::quote($fullName[1]));
                    $params[':first_name_1'] = $fullName[1].'%';
                    $params[':last_name_1']  = $fullName[1].'%';
                    $params[':first_name_2'] = $fullName[0].'%';
                    $params[':last_name_2']  = $fullName[1].'%';

                    $condition = '(first_name LIKE :first_name_1 OR last_name LIKE :last_name_1) OR (first_name LIKE :first_name_2 AND last_name LIKE :last_name_2)';
                }

                $condition = '('.$condition.') AND is_active = 1';

                $customer = Customers::model()->findAll(array(
                        'condition' => $condition,
                        'order'=>'first_name,last_name'
                    ),
                    $params
                );
                if(is_array($customer) && !empty($customer)){
                    $arr[] = '{"status": "1"}';
                    foreach($customer as $key => $customer){
                        $arr[] = '{"id": "'.$customer['id'].'", "label": "'.htmlentities($customer['first_name'].' '.$customer['last_name'].' ('.$customer['username'].': '.$customer['email'].')').'"}';
                    }
                }
            }
        }

        if(empty($arr)){
            $arr[] = '';
        }

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');   // Date in the past
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // Always modified
        header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
        header('Pragma: no-cache'); // HTTP/1.0
        header('Content-Type: application/json');

        echo '[';
        echo array($arr) ? implode(',', $arr) : '';
        echo ']';

        exit;
    }

    /**
     * Customer logout action handler
     * @return void
     */
    public function logoutAction()
    {
        if(CAuth::isLoggedInAs('customer')){
            $this->_logout();
            $this->_cSession->startSession();
            $this->_cSession->setFlash('msgLoggedOut', CWidget::create('CMessage', array('info', A::t('directory', 'You have been logged out successfully. We hope to see you again soon.'))));
        }
        $this->redirect('customers/loggedOut');
    }

    /**
     * Customer logout action handler
     * @return void
     */
    public function loggedOutAction()
    {
        // set frontend mode
        Website::setFrontend();
        if($this->_cSession->hasFlash('msgLoggedOut')){
            $this->_view->actionMessage = $this->_cSession->getFlash('msgLoggedOut');
        }
        $this->_view->allowRememberMe = ModulesSettings::model()->param('directory', 'customer_allow_remember_me');
        $this->_view->allowRegistration = ModulesSettings::model()->param('directory', 'customer_allow_registration');
        $this->_view->allowResetPassword = ModulesSettings::model()->param('directory', 'customer_allow_restore_password');

        $this->_view->render('customers/login');
    }


    /**
     * Customer registration action handler
     * @return void
     */
    public function registrationAction()
    {
        // redirect logged in customers
        CAuth::handleLoggedIn('customers/dashboard', 'customer');

        // check if action allowed
        if(!ModulesSettings::model()->param('directory', 'customer_allow_registration')){
            $this->redirect('index/index');
        }

        // set frontend mode
        Website::setFrontend();

        $this->_prepareAccountFields();
        $cRequest = A::app()->getRequest();
        $approvalType = ModulesSettings::model()->param('directory', 'customer_approval_type');
        $arr = array();

        if($cRequest->isPostRequest()){
            if($cRequest->getPost('act') != 'send'){
                $this->redirect(CConfig::get('defaultController').'/');
            }else if(APPHP_MODE == 'demo'){
                $arr[] = '"status": "0"';
            }else{
                // perform registration form validation
                $fields = array();
                if($this->_view->fieldFirstName !== 'no') $fields['first_name'] = array('title'=>A::t('directory', 'First Name'), 'validation'=>array('required'=>($this->_view->fieldFirstName == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>32));
                if($this->_view->fieldLastName !== 'no') $fields['last_name'] = array('title'=>A::t('directory', 'Last Name'), 'validation'=>array('required'=>($this->_view->fieldLastName == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>32));
                if($this->_view->fieldBirthDate !== 'no') $fields['birth_date'] = array('title'=>A::t('directory', 'Birth Date'), 'validation'=>array('required'=>($this->_view->fieldBirthDate == 'allow-required' ? true : false), 'type'=>'date', 'maxLength'=>10, 'minValue'=>'1900-00-00', 'maxValue'=>date('Y-m-d')));
                if($this->_view->fieldWebsite !== 'no') $fields['website'] = array('title'=>A::t('directory', 'Website'), 'validation'=>array('required'=>($this->_view->fieldWebsite == 'allow-required' ? true : false), 'type'=>'url', 'maxLength'=>255));
                if($this->_view->fieldCompany !== 'no') $fields['company'] = array('title'=>A::t('directory', 'Company'), 'validation'=>array('required'=>($this->_view->fieldCompany == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>128));
                if($this->_view->fieldPhone !== 'no') $fields['phone'] = array('title'=>A::t('directory', 'Phone'), 'validation'=>array('required'=>($this->_view->fieldPhone == 'allow-required' ? true : false), 'type'=>'phoneString', 'maxLength'=>32));
                if($this->_view->fieldFax !== 'no') $fields['fax'] = array('title'=>A::t('directory', 'Fax'), 'validation'=>array('required'=>($this->_view->fieldFax == 'allow-required' ? true : false), 'type'=>'phoneString', 'maxLength'=>32));
                if($this->_view->fieldEmail !== 'no') $fields['email'] = array('title'=>A::t('directory', 'Email'), 'validation'=>array('required'=>($this->_view->fieldEmail == 'allow-required' ? true : false), 'type'=>'email', 'maxLength'=>100));
                if($this->_view->fieldAddress !== 'no') $fields['address'] = array('title'=>A::t('directory', 'Address'), 'validation'=>array('required'=>($this->_view->fieldAddress == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>64));
                if($this->_view->fieldAddress2 !== 'no') $fields['address_2'] = array('title'=>A::t('directory', 'Address (line 2)'), 'validation'=>array('required'=>($this->_view->fieldAddress2 == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>64));
                if($this->_view->fieldCity !== 'no') $fields['city'] = array('title'=>A::t('directory', 'City'), 'validation'=>array('required'=>($this->_view->fieldCity == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>64));
                if($this->_view->fieldZipCode !== 'no') $fields['zip_code'] = array('title'=>A::t('directory', 'Zip Code'), 'validation'=>array('required'=>($this->_view->fieldZipCode == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>32));
                if($this->_view->fieldCountry !== 'no') $fields['country_code'] = array('title'=>A::t('directory', 'Country'), 'validation'=>array('required'=>($this->_view->fieldCountry == 'allow-required' ? true : false), 'type'=>'set', 'source'=>array_keys($this->_view->countries)));
                if($this->_view->fieldState !== 'no') $fields['state'] = array('title'=>A::t('directory', 'State/Province'), 'validation'=>array('required'=>($this->_view->fieldState == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>64));
                $fields['username'] = array('title'=>A::t('directory', 'Username'), 'validation'=>array('required'=>true, 'type'=>'login', 'minLength'=>6, 'maxLength'=>25));
                $fields['password'] = array('title'=>A::t('directory', 'Password'), 'validation'=>array('required'=>true, 'type'=>'password', 'minLength'=>6, 'maxLength'=>20));
                if($this->_view->fieldConfirmPassword !== 'no') $fields['confirm_password'] = array('title'=>A::t('directory', 'Confirm Password'), 'validation'=>array('required'=>true, 'type'=>'confirm', 'confirmField'=>'password', 'minLength'=>6, 'maxLength'=>20));
                $captcha = $cRequest->getPost('captcha');

                $result = CWidget::create('CFormValidation', array(
                    'fields' => $fields
                ));
                if($result['error']){
                    $arr[] = '"status": "0"';
                    $arr[] = '"error": "'.$result['errorMessage'].'"';
                }else if($this->_view->fieldCaptcha !== 'no' && $captcha === ''){
                    $arr[] = '"status": "0"';
                    $arr[] = '"error_field": "captcha_validation"';
                    $arr[] = '"error": "'.A::t('directory', 'The field captcha cannot be empty!').'"';
                }else if($this->_view->fieldCaptcha !== 'no' && $captcha != A::app()->getSession()->get('captchaResult')){
                    $arr[] = '"status": "0"';
                    $arr[] = '"error_field": "captcha_validation"';
                    $arr[] = '"error": "'.A::t('directory', 'Sorry, the code you have entered is invalid! Please try again.').'"';
                // these operations is done in model class
                //}else if(Accounts::model()->count('role = :role AND email = :email', array(':role'=>'directory', ':email'=>$cRequest->getPost('email')))){
                //    $arr[] = '"status": "0"';
                //    $arr[] = '"error_field": "email"';
                //    $arr[] = '"error": "'.A::t('directory', 'Customer with such email already exists!').'"';
                //}else if(Accounts::model()->count('role = :role AND username = :username', array(':role'=>'directory', ':username'=>$cRequest->getPost('username')))){
                //    $arr[] = '"status": "0"';
                //    $arr[] = '"error_field": "username"';
                //    $arr[] = '"error": "'.A::t('directory', 'Customer with such username already exists!').'"';
                }else{
                    $username = $cRequest->getPost('username');
                    $password = $cRequest->getPost('password');
                    // password encryption
                    if(CConfig::get('password.encryption')){
                        $encryptAlgorithm = CConfig::get('password.encryptAlgorithm');
                        $hashKey = CConfig::get('password.hashKey');
                        $passwordEncrypted = CHash::create($encryptAlgorithm, $password, $hashKey);
                    }else{
                        $passwordEncrypted = $password;
                    }

                    $customer = new Customers();
                    $customer->group_id = (int)CustomerGroups::model()->findPk('is_default = 1');
                    $customer->first_name = $cRequest->getPost('first_name');
                    $customer->last_name = $cRequest->getPost('last_name');
                    $customer->birth_date = $cRequest->getPost('birth_date');
                    $customer->website = $cRequest->getPost('website');
                    $customer->company = $cRequest->getPost('company');
                    $customer->phone = $cRequest->getPost('phone');
                    $customer->fax = $cRequest->getPost('fax');
                    $customer->address = $cRequest->getPost('address');
                    $customer->address_2 = $cRequest->getPost('address_2');
                    $customer->city = $cRequest->getPost('city');
                    $customer->zip_code = $cRequest->getPost('zip_code');
                    $customer->country_code = $cRequest->getPost('country_code');
                    $customer->state = $cRequest->getPost('state');

                    $accountCreated = false;
                    if($customer->save()){
                        $customer = $customer->refresh();

                        // update accounts table
                        $account = Accounts::model()->findByPk((int)$customer->account_id);
                        if($approvalType == 'by_admin'){
                            $account->registration_code = CHash::getRandomString(20);
                            $account->is_active = 0;
                        }else if($approvalType == 'by_email'){
                            $account->registration_code = CHash::getRandomString(20);
                            $account->is_active = 0;
                        }else{
                            $account->registration_code = '';
                            $account->is_active = 1;
                        }
                        if($account->save()){
                            $accountCreated = true;
                        }
                    }

                    if(!$accountCreated){
                        $arr[] = '"status": "0"';
                        if(APPHP_MODE == 'demo'){
                            $arr[] = '"error": "'.A::t('core', 'This operation is blocked in Demo Mode!').'"';
                        }else{
                            $arr[] = '"error": "'.(($customer->getError() != '') ? $customer->getError() : A::t('directory', 'An error occurred while creating customer account! Please try again later.')).'"';
                            $arr[] = '"error_field": "'.$customer->getErrorField().'"';
                        }
                    }else{
                        $firstName = $customer->first_name;
                        $lastName = $customer->last_name;
                        $customerEmail = $cRequest->getPost('email');
                        $emailResult = true;

                        // send notification to admin about new registration
                        if(ModulesSettings::model()->param('directory', 'customer_new_registration_alert')){
                            $adminLang = '';
                            if($defaultLang = Languages::model()->find('is_default = 1')){
                                $adminLang = $defaultLang->code;
                            }
                            $emailResult = Website::sendEmailByTemplate(
                                $this->_settings->general_email,
                                'directory_account_created_notify_admin',
                                $adminLang,
                                array('{FIRST_NAME}' => $firstName, '{LAST_NAME}' => $lastName, '{CUSTOMER_EMAIL}' => $customerEmail, '{USERNAME}' => $username)
                            );
                        }

                        // send email to customer according to approval type
                        if(!empty($customerEmail)){
                            if($approvalType == 'by_admin'){
                                // approval by admin
                                $emailResult = Website::sendEmailByTemplate(
                                    $customerEmail,
                                    'directory_account_created_admin_approval',
                                    A::app()->getLanguage(),
                                    array('{FIRST_NAME}' => $firstName, '{LAST_NAME}' => $lastName, '{USERNAME}' => $username, '{PASSWORD}' => $password)
                                );
                            }else if($approvalType == 'by_email'){
                                // confirmation by email
                                $emailResult = Website::sendEmailByTemplate(
                                    $customerEmail,
                                    'directory_account_created_email_confirmation',
                                    A::app()->getLanguage(),
                                    array('{FIRST_NAME}' => $firstName, '{LAST_NAME}' => $lastName, '{USERNAME}' => $username, '{PASSWORD}' => $password, '{REGISTRATION_CODE}' => $account->registration_code)
                                );
                            }else{
                                // auto approval
                                $emailResult = Website::sendEmailByTemplate(
                                    $customerEmail,
                                    'directory_account_created_auto_approval',
                                    A::app()->getLanguage(),
                                    array('{FIRST_NAME}' => $firstName, '{LAST_NAME}' => $lastName, '{USERNAME}' => $username, '{PASSWORD}' => $password)
                                );
                            }
                        }
                        if(!$emailResult){
                            $arr[] = '"status": "1"';
                            $arr[] = '"error": "'.A::t('directory', 'Your account has been successfully created, but email not sent! Please try again later.').'"';
                        }else{
                            $arr[] = '"status": "1"';
                        }
                    }
                }
            }

            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');   // date in the past
            header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
            header('Pragma: no-cache'); // HTTP/1.0
            header('Content-Type: application/json');

            echo '{';
            echo implode(',', $arr);
            echo '}';

            exit;
        }else{
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
            $this->_view->messageSuccess = $messageSuccess;
            $this->_view->messageInfo    = $messageInfo;
        }

        $this->_view->render('customers/registration');
    }

    /**
     * Customer restore password action handler
     * @return void
     */
    public function restorePasswordAction()
    {
        // redirect logged in customers
        CAuth::handleLoggedIn('directory/dashboard', 'customer');

        // check if action allowed
        if(!ModulesSettings::model()->param('directory', 'customer_allow_restore_password')){
            $this->redirect('index/index');
        }

        // set frontend mode
        Website::setFrontend();

        $cRequest = A::app()->getRequest();
        if($cRequest->getPost('act') == 'send'){

            $email = $cRequest->getPost('email');
            $alertType = '';
            $alert = '';

            if(empty($email)){
                $alertType = 'validation';
                $alert = A::t('directory', 'The field email cannot be empty!');
            }else if(!empty($email) && !CValidator::isEmail($email)){
                $alertType = 'validation';
                $alert = A::t('directory', 'You must provide a valid email address!');
            }else if(APPHP_MODE == 'demo'){
                $alertType = 'warning';
                $alert = A::t('core', 'This operation is blocked in Demo Mode!');
            }else{
                $account = Accounts::model()->find('role = :role AND email = :email', array(':role'=>'directory', ':email'=>$email));
                if(empty($account)){
                    $alertType = 'error';
                    $alert = A::t('directory', 'Sorry, but we were not able to find a customer with that login information!');
                }else{
                    $username = $account->username;
                    $preferedLang = $account->language_code;
                    // generate new password
                    if(CConfig::get('password.encryption')){
                        $password = CHash::getRandomString(8);
                        $account->password = CHash::create(CConfig::get('password.encryptAlgorithm'), $password, CConfig::get('password.hashKey'));
                        if(!$account->save()){
                            $alertType = 'error';
                            $alert = A::t('directory', 'An error occurred while password recovery process! Please try again later.');
                        }
                    }else{
                        $password = $account->password;
                    }

                    if(!$alert){
                        $result = Website::sendEmailByTemplate(
                            $email,
                            'directory_password_forgotten',
                            $preferedLang,
                            array(
                                '{USERNAME}' => $username,
                                '{PASSWORD}' => $password
                            )
                        );
                        if($result){
                            $alertType = 'success';
                            $alert = A::t('directory', 'Check your e-mail address linked to the account for the confirmation link, including the spam or junk folder.');
                        }else{
                            $alertType = 'error';
                            $alert = A::t('directory', 'An error occurred while password recovery process! Please try again later.');
                        }
                    }
                }
            }

            if(!empty($alert)){
                $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert));
            }
        }

        $this->_view->render('customers/restorePassword');
    }

    /**
     * Customer confirm registration action handler
     * @param string $code
     * @return void
     */
    public function confirmRegistrationAction($code)
    {
        // redirect logged in directory
        CAuth::handleLoggedIn('customers/dashboard', 'customer');

        // set frontend mode
        Website::setFrontend();

        if($customer = Accounts::model()->find('is_active = 0 AND registration_code = :code', array(':code'=>$code))){
            $customer->is_active = 1;
            $customer->registration_code = '';
            if($customer->save()){
                $alertType = 'success';
                $alert = A::t('directory', 'Account registration confirmed');
            }else{
                if(APPHP_MODE == 'demo'){
                    $alertType = 'warning';
                    $alert = CDatabase::init()->getErrorMessage();
                }else{
                    $alertType = 'error';
                    $alert = A::t('directory', 'Account registration error');
                }
            }
        }else{
            $alertType = 'warning';
            $alert = A::t('directory', 'Account registration wrong code');
        }

        if(!empty($alert)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert));
        }
        $this->_view->render('customers/confirmRegistration');
    }

    /**
     * Dashboard action handler
     * @return void
     */
    public function dashboardAction()
    {
        // block access to this controller for not-logged customers
        CAuth::handleLogin('customers/login', 'customer');
        // set meta tags according to active language
        Website::setMetaTags(array('title'=>A::t('directory', 'Dashboard')));
        // set frontend settings
        Website::setFrontend();

        $this->_view->render('customers/dashboard');
    }

    /**
     * Customer edit Account action handler
     * @return void
     */
    public function myAccountAction()
    {
        // block access to this controller for not-logged customers
        CAuth::handleLogin('customers/login', 'customer');
        // set meta tags according to active language
        Website::setMetaTags(array('title'=>A::t('directory', 'My Account')));
        // set frontend settings
        Website::setFrontend();

        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');
        $this->_view->actionMessage = !empty($alertType) ? CWidget::create('CMessage', array($alertType, $alert)) : '';

        $customer = $this->_checkAccountsAccess(A::app()->getSession()->get('loggedId'));
        $this->_prepareAccountFields();
        // fetch datetime format from settings table
        $dateTimeFormat = Bootstrap::init()->getSettings('datetime_format');
        $dateFormat = Bootstrap::init()->getSettings('date_format');

        $this->_view->customer = $customer;
        // prepare some fields
        $this->_view->countryName = $customer->country_code;
        $this->_view->stateName = $customer->state;
        $this->_view->langName = $customer->language_code;
        $this->_view->notifications = ($customer->notifications) ? A::t('directory', 'Yes') : A::t('directory', 'No');
        $this->_view->birthDate = ($customer->birth_date && $customer->birth_date != '0000-00-00') ? date($dateFormat, strtotime($customer->birth_date)) : '';
        $this->_view->createdAt = ($customer->created_at && $customer->created_at != '0000-00-00 00:00:00') ? date($dateTimeFormat, strtotime($customer->created_at)) : A::t('directory', 'Unknown');
        $this->_view->lastVisitedAt = ($customer->last_visited_at && $customer->last_visited_at != '0000-00-00 00:00:00') ? date($dateTimeFormat, strtotime($customer->last_visited_at)) : A::t('directory', 'Unknown');
        if($country = Countries::model()->find('code = :code', array(':code'=>$customer->country_code))){
            $this->_view->countryName = $country->country_name;
        }
        if($state = States::model()->find('country_code = :country_code AND code = :code', array(':country_code'=>$customer->country_code, ':code'=>$customer->state))){
            $this->_view->stateName = $state->state_name;
        }
        if($language = Languages::model()->find('code = :code', array(':code'=>$customer->language_code))){
            $this->_view->langName = $language->name;
        }

        $this->_view->render('customers/myAccount');
    }

    /**
     * Customer edit Account action handler
     * @return void
     */
    public function editAccountAction()
    {
        // block access to this controller for not-logged customers
        CAuth::handleLogin('customers/login', 'customer');
        // set meta tags according to active language
        Website::setMetaTags(array('title'=>A::t('directory', 'Edit Account')));
        // set frontend settings
        Website::setFrontend();

        $states    = array();
        $alert     = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');
        $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert));

        $customer = $this->_checkAccountsAccess(A::app()->getSession()->get('loggedId'));
        $this->_prepareAccountFields();

        $cRequest = A::app()->getRequest();
        if($cRequest->isPostRequest()){
            $countryCode = $cRequest->getPost('country_code');
            $stateCode = $cRequest->getPost('state');
        }else{
            $countryCode = $customer->country_code;
            $stateCode = $customer->state;
        }

        if(!empty($countryCode)){
            // prepare countries
            $states = array(''=>A::t('directory', '- select -'));
            $statesResult = States::model()->findAll(array('condition'=>'is_active = 1 AND '.CConfig::get('db.prefix').'states.country_code = :country_code', 'order'=>'sort_order DESC, state_name ASC'), array(':country_code'=>$countryCode));
            if(is_array($statesResult)){
                foreach($statesResult as $key => $val){
                    $states[$val['code']] = $val['state_name'];
                }
            }
        }

        // prepare salt
        $this->_view->salt = '';
        if(A::app()->getRequest()->getPost('password') != ''){
            $this->_view->salt = CConfig::get('password.encryptSalt') ? CHash::salt() : '';
            A::app()->getRequest()->setPost('salt', $this->_view->salt);
        }

        $this->_view->id = $customer->id;
        $this->_view->countryCode = $countryCode;
        $this->_view->stateCode = $stateCode;
        $this->_view->states = $states;
        // fetch datetime format from settings table
        $this->_view->dateTimeFormat = Bootstrap::init()->getSettings('datetime_format');
        $this->_view->render('customers/editAccount');
    }

    /**
     * Customer remove account action handler
     * @return void
     */
    public function removeAccountAction()
    {
        // block access to this controller for not-logged customers
        CAuth::handleLogin('customers/login', 'customer');
        // set meta tags according to active language
        Website::setMetaTags(array('title'=>A::t('directory', 'Remove Account')));
        // set frontend settings
        Website::setFrontend();

        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');
        $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert));

        $loggedId = A::app()->getSession()->get('loggedId');
        $customer = $this->_checkAccountsAccess($loggedId);
        $alertType = '';
        $alert = '';
        $this->_view->accountRemoved = false;

        $cRequest = A::app()->getRequest();
        if($cRequest->isPostRequest()){
            if($cRequest->getPost('act') != 'send'){
                $this->redirect('customers/myAccount');
            }else if(APPHP_MODE == 'demo'){
                $alertType = 'warning';
                $alert = A::t('core', 'This operation is blocked in Demo Mode!');
            }else{

                // add removing account here
                $removalType = ModulesSettings::model()->param('directory', 'customer_removal_type');
                $this->_view->accountRemoved = true;
                if($removalType == 'logical' || $removalType == 'physical_or_logical'){
                    if(!Accounts::model()->updateByPk($loggedId, array('is_removed'=>1, 'is_active'=>0))){
                        $this->_view->accountRemoved = false;
                    }
                }else if($removalType == 'physical'){
                    if(!Accounts::model()->deleteByPk($loggedId)){
                        $this->_view->accountRemoved = false;
                    }
                }

                if($this->_view->accountRemoved){
                    $alertType = 'success';
                    $alert = A::t('directory', 'Your account has been successfully removed!');

                    $result = Website::sendEmailByTemplate(
                        CAuth::getLoggedEmail(),
                        'directory_account_removed_by_customer',
                        CAuth::getLoggedLang(),
                        array('{USERNAME}' => CAuth::getLoggedName())
                    );

                    $this->_logout();
                }else{
                    $alertType = 'error';
                    $alert = A::t('directory', 'An error occurred while deleting your account! Please try again later.');
                }
            }
        }

        if(!empty($alert)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert));
        }
        $this->_view->render('customers/removeAccount');
    }

    /**
     * Change status customer action handler
     * @param int $id the menu ID
     * @return void
     */
    public function activeStatusAction ($id)
    {
        Website::prepareBackendAction('edit', 'customer', 'customers/manage');

        $customer = Customers::model()->findbyPk($id);
        if(!empty($customer)){
            if(Accounts::model()->updateByPk($customer->account_id, array('is_active'=>($customer->is_active == 1 ? '0' : '1')))){
                A::app()->getSession()->setFlash('alert', A::t('directory', 'Status has been successfully changed!'));
                A::app()->getSession()->setFlash('alertType', 'success');
            }else{
                A::app()->getSession()->setFlash('alert', A::t('directory', 'Status changing error'));
                A::app()->getSession()->setFlash('alertType', 'error');
            }
        }
        $this->redirect('customers/manage');
    }

    /**
     * Check if passed Account ID is valid
     * @param int $id
     * @return Customers
     */
    private function _checkAccountsAccess($id = 0)
    {
        $customers = Customers::model()->find('account_id = :account_id AND is_active = 1', array('i:account_id'=>$id));
        if(!$customers){
            $this->redirect('customers/manage');
        }
        return $customers;
    }

    /**
     * Check if passed Customers ID is valid
     * @param int $id
     * @return Customers
     */
    private function _checkCustomerAccess($id = 0)
    {
        $customer = Customers::model()->findByPk($id);
        if(!$customer){
            $this->redirect('customers/manage');
        }
        return $customer;
    }

    /**
     * Prepares account fields
     * @return void
     */
    private function _prepareAccountFields()
    {
        $this->_view->fieldFirstName        = ModulesSettings::model()->param('directory', 'customer_field_first_name');
        $this->_view->fieldLastName         = ModulesSettings::model()->param('directory', 'customer_field_last_name');
        $this->_view->fieldBirthDate        = ModulesSettings::model()->param('directory', 'customer_field_birth_date');
        $this->_view->fieldWebsite          = ModulesSettings::model()->param('directory', 'customer_field_website');
        $this->_view->fieldCompany          = ModulesSettings::model()->param('directory', 'customer_field_company');

        $this->_view->fieldPhone            = ModulesSettings::model()->param('directory', 'customer_field_phone');
        $this->_view->fieldFax              = ModulesSettings::model()->param('directory', 'customer_field_fax');
        $this->_view->fieldEmail            = ModulesSettings::model()->param('directory', 'customer_field_email');

        $this->_view->fieldAddress          = ModulesSettings::model()->param('directory', 'customer_field_address');
        $this->_view->fieldAddress2         = ModulesSettings::model()->param('directory', 'customer_field_address_2');
        $this->_view->fieldCity             = ModulesSettings::model()->param('directory', 'customer_field_city');
        $this->_view->fieldZipCode          = ModulesSettings::model()->param('directory', 'customer_field_zip_code');
        $this->_view->fieldCountry          = ModulesSettings::model()->param('directory', 'customer_field_country');
        $this->_view->fieldState            = ModulesSettings::model()->param('directory', 'customer_field_state');

        $this->_view->fieldNotifications    = ModulesSettings::model()->param('directory', 'customer_field_notifications');
        $this->_view->fieldUsername         = ModulesSettings::model()->param('directory', 'customer_field_username');
        $this->_view->fieldPassword         = ModulesSettings::model()->param('directory', 'customer_field_password');
        $this->_view->fieldConfirmPassword  = ModulesSettings::model()->param('directory', 'customer_field_confirm_password');
        $this->_view->fieldCaptcha          = ModulesSettings::model()->param('directory', 'customer_field_captcha');

        $this->_view->removalType           = ModulesSettings::model()->param('directory', 'customer_removal_type');
        $this->_view->changePassword        = ModulesSettings::model()->param('directory', 'change_customer_password');

        // prepare groups
        $groups = array();
        $groupsResult = CustomerGroups::model()->findAll();
        $this->_view->defaultGroupId = 0;
        if(is_array($groupsResult)){
            if(count($groupsResult)) $groups = array(''=>'');
            foreach($groupsResult as $key => $val){
                $groups[$val['id']] = $val['name'];
                if($val['is_default']) $this->_view->defaultGroupId = $val['id'];
            }
        }
        $this->_view->groups = $groups;

        // prepare countries
        $countries = array(''=>A::t('directory', '- select -'));
        $countriesResult = Countries::model()->findAll(array('condition'=>'is_active = 1', 'order'=>'sort_order DESC, country_name ASC'));
        $this->_view->defaultCountryCode = '';
        if(is_array($countriesResult)){
            foreach($countriesResult as $key => $val){
                $countries[$val['code']] = $val['country_name'];
                if($val['is_default']) $this->_view->defaultCountryCode = $val['code'];
            }
        }
        $this->_view->countries = $countries;

        // prepare languages
        $langList = array();
        $languagesResult = Languages::model()->findAll('is_active = 1');
        if(is_array($languagesResult)){
            foreach($languagesResult as $lang){
                $langList[$lang['code']] = $lang['name_native'];
            }
        }
        $this->_view->langList = $langList;
    }

    /**
     * Customer logout
     * @return void
     */
    private function _logout()
    {
        A::app()->getSession()->endSession();
        A::app()->getCookie()->remove('customerAuth');
        // clear cache
        if(CConfig::get('cache.enable')) CFile::emptyDirectory('protected/tmp/cache/');
    }
}
