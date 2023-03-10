<?php
/**
 * Home controller
 *
 * PUBLIC:                  PRIVATE:
 * ---------------          ---------------
 * __construct              _checkSiteInfoAccess
 * siteinfoAction           _prepareTab
 * socialAction
 * socialAddAction
 * socialEditAction
 * socialDeleteAction
 * socialPublishedStatusAction
 *
 */

class SocialController extends CController
{
    /**
     * Class default constructor
     */
    public function __construct()
    {
        parent::__construct();

        // Set meta tags according to active language
        Website::setMetaTags(array('title'=>A::t('app', 'Social Settings')));
        // Set backend mode
        Website::setBackend();
        $this->_view->actionMessage = '';
        $this->_view->errorField = '';
    }

    /**
     * Controller default action handler
     */
    public function indexAction()
    {
        $this->redirect('social/social');
    }

    /**
     * Social networks settings action handler
     * @return void
     */
    public function socialAction()
    {
        CAuth::handleLogin(Website::getDefaultPage());

        // Block access if admin has no active privileges to access site settings
        if(!Admins::hasPrivilege('social_settings', 'view')){
            $this->redirect('backend/index');
        }

        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');

        if(!empty($alertType)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }

        $this->_view->tabs = $this->_prepareTab('social');
        $this->_view->render('social/social');
    }

    /**
     * Add new action handler
     * @param string $typeTab the type sub-tab ('approved', 'pending', 'expired' and 'all')
     * @return void
     */
    public function socialAddAction()
    {
        CAuth::handleLogin(Website::getDefaultPage());

        // Block access if admin has no active privileges to access site settings
        if(!Admins::hasPrivilege('social_settings', 'edit')){
            $this->redirect('social/social');
        }

        $this->_view->tabs = $this->_prepareTab('social');
        $this->_view->render('social/socialadd');
    }

    /**
     * Edit action handler
     * @param int $id
     * @param string $typeTab
     * @param string $delete the type image delete ('image')
     * @return void
     */
    public function socialEditAction($id = 0, $delete = '')
    {
        CAuth::handleLogin(Website::getDefaultPage());

        // Block access if admin has no active privileges to access site settings
        if(!Admins::hasPrivilege('social_settings', 'edit')){
            $this->redirect('social/social');
        }

        $socialNetwork = SocialNetworks::model()->findByPk($id);

        if(empty($socialNetwork)){
            $alert = A::t('app', 'Input incorrect parameters');
            $alertType = 'error';
            A::app()->getSession()->setFlash('alert', $alert);
            A::app()->getSession()->setFlash('alertType', $alertType);
            $this->redirect('social/social');
        }

        // Delete the icon file
        if($delete == 'image'){
            $icon = $socialNetwork->icon;
            $socialNetwork->icon = '';

            $imagePath = 'images/social_settings/social_networks/'.$icon;

                // Save the changes in admins table
                if($socialNetwork->save()){
                    // Delete the files
                    if(!empty($imagePath) && CFile::deleteFile($imagePath)){
                        $alert = A::t('app', 'Image successfully deleted');
                        $alertType = 'success';
                    }else{
                        $alert = A::t('app', 'Image Delete Warning');
                        $alertType = 'warning';
                    }
                }else{
                    $alert = A::t('app', 'Delete Error Message');
                    $alertType = 'error';
                }

            if(!empty($alert)){
                $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
            }
        }

        $this->_view->id = $id;
        $this->_view->tabs = $this->_prepareTab('social');
        $this->_view->render('social/socialedit');
    }

    /**
     * Delete social network action handler
     * @return void
     */
    public function socialDeleteAction($id)
    {
        CAuth::handleLogin(Website::getDefaultPage());

        // Block access if admin has no active privileges to access site settings
        if(!Admins::hasPrivilege('social_settings', 'delete')){
            $this->redirect('backend/index');
        }

        $socialNetwork = SocialNetworks::model()->findByPk($id);

        if(!empty($socialNetwork)){
            $icon = $socialNetwork->icon;
            $imagePath = 'images/social_settings/social_networks/'.$icon;
            if($socialNetwork->delete())
            {
                if($icon ? CFile::deleteFile($imagePath) : true){
                    $alert = A::t('app', 'Icon successfully deleted');
                    $alertType = 'success';
                }else{
                    $alert = A::t('app', 'Edit Social Network');
                    $alertType = 'warning';
                }
            }else{
                if(APPHP_MODE == 'demo'){
                    $alert = CDatabase::init()->getErrorMessage();
                    $alertType = 'warning';
                }else{
                    $alert = A::t('app', 'Social Network deleting error');
                    $alertType = 'error';
                }
            }
            $alert = A::app()->getSession()->setFlash('alert', $alert);
            $alertType = A::app()->getSession()->setFlash('alertType', $alertType);
        }

        $this->redirect('social/social');
    }

    /**
     * Social Login action handler
     * @param string $typeTab
     * @return void
     */
    public function socialLoginAction()
    {
        CAuth::handleLogin(Website::getDefaultPage());

        // Block access if admin has no active privileges to access site settings
        if(!Admins::hasPrivilege('social_settings', 'view')){
            $this->redirect('backend/index');
        }

        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');

        if(!empty($alertType)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }

        $this->_view->tabs = $this->_prepareTab('sociallogin');
        $this->_view->render('social/sociallogin');
    }

    /**
     * Social Login Edit action handler
     * @param int $id
     * @return void
     */
    public function socialLoginEditAction($id = 0, $image = '')
    {
        CAuth::handleLogin(Website::getDefaultPage());

        // Block access if admin has no active privileges to access site settings
        if(!Admins::hasPrivilege('social_settings', 'edit')){
            $this->redirect('social/socialLogin');
        }
        $socialLogin = $this->_checkSocialLoginAccess($id);

        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');

        if($image == 'delete'){
            $iconPath = 'images/social_settings/social_login/'.$socialLogin->button_image;
            $socialLogin->button_image = '';

            // Save the changes in admins table
            if($socialLogin->save()){
                // Delete the files. If deleteThumb == true, then delete image thumb file
                if(CFile::deleteFile($iconPath)){
                    $alert = A::t('app', 'Icon successfully deleted');
                    $alertType = 'success';
                }else{
                    $alert = A::t('app', 'Image Delete Warning');
                    $alertType = 'warning';
                }
            }else{
                if(APPHP_MODE == 'demo'){
                    $alert = A::t('app', 'This operation is blocked in Demo Mode!');
                    $alertType = 'warning';
                }else{
                    $alert = A::t('app', 'Image Delete Error');
                    $alertType = 'error';
                }
            }

        }

        if(!empty($alertType)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }

        $this->_view->tabs = $this->_prepareTab('sociallogin');
        $this->_view->id = $socialLogin->id;
        $this->_view->render('social/socialloginedit');
    }

    /**
     * Change status social network action handler
     * @param int $id the menu ID
     * @return void
     */
    public function socialLoginStatusAction($id = 0)
    {
        CAuth::handleLogin(Website::getDefaultPage());

        // Block access if admin has no active privileges to access site settings
        if(!Admins::hasPrivilege('social_settings', 'delete')){
            $this->redirect('social/socialLogin');
        }
        $socialLogin = SocialNetworksLogin::model()->findbyPk((int)$id);

        if(!empty($socialLogin)){
            if($socialLogin->application_id && SocialNetworksLogin::model()->updateByPk($socialLogin->id, array('is_active'=>($socialLogin->is_active == 1 ? '0' : '1')))){
                A::app()->getSession()->setFlash('alert', A::t('app', 'Status has been successfully changed!'));
                A::app()->getSession()->setFlash('alertType', 'success');
            }else{
                if($socialLogin->application_id){
                    A::app()->getSession()->setFlash('alert', A::t('app', 'Status changing error'));
                }else{
                    A::app()->getSession()->setFlash('alert', A::t('app', 'Change of status is not possible due to the fact that the Application ID is empty'));
                }
                A::app()->getSession()->setFlash('alertType', 'error');
            }
        }
        $this->redirect('social/socialLogin');
    }


    /**
     * Change status social network action handler
     * @param int $id the menu ID
     * @return void
     */
    public function socialPublishedStatusAction($id)
    {
        CAuth::handleLogin(Website::getDefaultPage());

        // Block access if admin has no active privileges to access site settings
        if(!Admins::hasPrivilege('social_settings', 'delete')){
            $this->redirect('social/social');
        }
        $socialNetwork = SocialNetworks::model()->findbyPk((int)$id);

        if(!empty($socialNetwork)){
            if(SocialNetworks::model()->updateByPk($socialNetwork->id, array('is_published'=>($socialNetwork->is_published == 1 ? '0' : '1')))){
                A::app()->getSession()->setFlash('alert', A::t('app', 'Status has been successfully changed!'));
                A::app()->getSession()->setFlash('alertType', 'success');
            }else{
                A::app()->getSession()->setFlash('alert', A::t('app', 'Status changing error'));
                A::app()->getSession()->setFlash('alertType', 'error');
            }
        }
        $this->redirect('social/social');
    }


    /**
     * Change status social network action handler
     * @param int $id the menu ID
     * @return void
     */
    public function oauthAction($type = '')
    {
        $cSession = A::app()->getSession();
        $config = $cSession->get('socialLogin');
        $opauthInfo = $cSession->get('opauth');
        $returnUrl = isset($config['returnUrl']) ? $config['returnUrl'] : Website::getDefaultPage();
        $method = isset($config['method']) && !empty($config['method']) ? $config['method'] : 'registrationSocial';
        $model = isset($config['model']) ? $config['model'] : 'Accounts';

        if(!CAuth::isLoggedIn() && !empty($type)){
            $socialLogin = SocialNetworksLogin::model()->find('type = :type AND is_active = 1', array(':type'=>$type));
            if(!empty($socialLogin)){
                $configOauth = array();
                $configOauth['path'] = A::app()->getRequest()->getBasePath().'social/oauth/type/';
                $configOauth['callback_url'] = A::app()->getRequest()->getBaseUrl().'social/oauth';
                $strategies = COauth::getStrategies();
                foreach($strategies as $typeSocial => $options){
                    if(strtolower($typeSocial) == $type){
                        $strategy[$typeSocial] = $options;
                        // The first key is the Application ID (Facebook => app_id, Google => client_id and etc.)
                        reset($options);
                        $keyId = key($options);
                        // The second key is the Application Secret (Facebook => app_secret, Google => client_secret and etc.)
                        next($options);
                        $keySecret = key($options);
                        // Replace key and secret from the value of the DB other settings take defaul
                        $strategy[$typeSocial][$keyId] = $socialLogin->application_id;
                        $strategy[$typeSocial][$keySecret] = $socialLogin->application_secret;

                        $configOauth['Strategy'] = $strategy;
                    }
                }

                if(!empty($configOauth['Strategy'])){
                    COauth::config($configOauth);
                    COauth::login($type);
                }
            }
        }else if(!empty($opauthInfo)){
            $this->_view->allowRememberMe = 0;

            if(isset($opauthInfo['error'])){
                $alertType = 'error';
                $alert = $opauthInfo['error']['message'];
            }else if(isset($opauthInfo['auth']) && is_array($opauthInfo['auth'])){
                $provider = strtolower($opauthInfo['auth']['provider']);
                $uid = isset($opauthInfo['auth']['uid']) ? $opauthInfo['auth']['uid'] : '';
                $authInfo = isset($opauthInfo['auth']['info']) ? $opauthInfo['auth']['info'] : array();
                if($provider == 'google'){
                    $username = $uid.'_gl';
                    $firstName = isset($authInfo['first_name']) ? $authInfo['first_name'] : '';
                    $lastName = isset($authInfo['last_name']) ? $authInfo['last_name'] : '';
                    $email = isset($authInfo['email']) ? $authInfo['email'] : '';
                    $image = isset($authInfo['image']) ? $authInfo['image'] : '';
                }else if($provider == 'facebook'){
                    $username = $uid.'_fb';
                    $explName = isset($authInfo['name']) ? explode(' ', $authInfo['name']) : array();
                    $firstName = isset($authInfo['first_name']) ? $authInfo['first_name'] : (isset($explName[0]) ? $explName[0] : '');
                    $lastName = isset($authInfo['last_name']) ? $authInfo['last_name'] : (isset($explName[1]) ? $explName[1] : '');
                    $email = isset($authInfo['email']) ? $authInfo['email'] : $uid.'-fb@facebook.com';
                    $image = isset($authInfo['image']) ? $authInfo['image'] : '';
                }else if($provider == 'twitter'){
                    $username = $uid.'_tw';
                    $explName = isset($authInfo['name']) ? explode(' ', $authInfo['name']) : array();
                    $firstName = isset($authInfo['first_name']) ? $authInfo['first_name'] : (isset($explName[0]) ? $explName[0] : '');
                    $lastName = isset($authInfo['last_name']) ? $authInfo['last_name'] : (isset($explName[1]) ? $explName[1] : '');
                    $email = isset($authInfo['email']) ? $authInfo['email'] : $uid.'-tw@twitter.com';
                    $image = isset($authInfo['image']) ? $authInfo['image'] : '';
                }

                if(empty($uid) && !empty($email)){
                    $alertType = 'error';
                    $alert = A::t('app', 'Input incorrect parameters');
                }else{
                    if(APPHP_MODE == 'demo'){
                        $account = Accounts::model()->find(array('orderBy'=>CConfig::get('db.prefix').'accounts.id ASC'));
                    }else{
                        $account = Accounts::model()->find(CConfig::get('db.prefix').'accounts.username = :username OR '.CConfig::get('db.prefix').'accounts.email = :email', array(':username'=>$username, ':email'=>$email));
                    }
                    if(!empty($account)){
                        // Login
                        if($account->is_active == 1 && $account->is_removed == 0){
                            $action = 'dashboard';
                            // Customers already exists
                            $cSession->set('loggedIn', true);
                            $cSession->set('loggedId', $account->id);
                            $cSession->set('loggedName', $account->username);
                            $cSession->set('loggedEmail', $account->email);
                            $cSession->set('loggedLastVisit', $account->last_visited_at);
                            if($account->iscolumnExists('avatar')) $cSession->set('loggedAvatar', $account->avatar);
                            $cSession->set('loggedLanguage', ($account->language_code ? $account->language_code : Languages::model()->getDefaultLanguage()));
                            $cSession->set('loggedRole', $account->role);

                            // Set current language
                            if($accountLang = Languages::model()->find('code = :code AND is_active = 1', array(':code'=>$account->language_code))){
                                $params = array(
                                    'locale' => $accountLang->lc_time_name,
                                    'direction' => $accountLang->direction
                                );
                                A::app()->setLanguage($account->language_code, $params);
                            }

                            // Update last visited and token expires dates in account record
                            $account->last_visited_at = LocalTime::currentDateTime();
                            $account->last_visited_ip = A::app()->getRequest()->getUserHostAddress();
                            $account->token_expires_at = '';
                            $account->save();
                        }else{
                            $alert = A::t('app', 'Your account has been disabled!');
                            $alertType = 'error';
                        }
                    }else{
                        // Registration
                        $arrayInfo = array(
                            'username' => $username,
                            'email' => $email,
                            'firstName' => $firstName,
                            'lastName' => $lastName,
                            'allInfo' => $authInfo
                        );


                        if(class_exists($model) && is_subclass_of($model, 'CActiveRecord') && is_callable(array($model, $method))){
                            $newUser = new $model();
                            $newUser->$method($arrayInfo);
                            if(!$newUser->save()){
                                if(APPHP_MODE == 'demo'){
                                    $alert = A::t('app', 'This operation is blocked in Demo Mode!');
                                    $alertType = 'warning';
                                }else{
                                    $alert = (($newUser->getError() != '') ? $newUser->getError() : A::t('app', 'An error occurred while creating customer account! Please try again later.'));
                                    $alertType = 'error';
                                }
                            }else{
                                $account = Accounts::model()->findByPk($newUser->account_id);
                                // Customers already exists
                                $session = A::app()->getSession();
                                $session->set('loggedIn', true);
                                $session->set('loggedId', $newUser->account_id);
                                $session->set('loggedName', $account->username);
                                $session->set('loggedEmail', $account->email);
                                $session->set('loggedLastVisit', $account->last_visited_at);
                                $session->set('loggedAvatar', '');
                                $session->set('loggedLanguage', (Languages::model()->getDefaultLanguage()));
                                $session->set('loggedRole', $account->role);

                                // Set current language
                                if($accountLang = Languages::model()->find('code = :code AND is_active = 1', array(':code'=>Languages::model()->getDefaultLanguage()))){
                                    $params = array(
                                        'locale' => $accountLang->lc_time_name,
                                        'direction' => $accountLang->direction
                                    );
                                    A::app()->setLanguage($account->language_code, $params);
                                }

                                // Update last visited and token expires dates in account record
                                $account->last_visited_at = LocalTime::currentDateTime();
                                $account->last_visited_ip = A::app()->getRequest()->getUserHostAddress();
                                $account->token_expires_at = '';
                                $account->save();

                                $alert = A::t('app', 'Your account has been successfully created.');
                                $alertType = 'success';
                            }
                        }else{
                            CDebug::addMessage('error', 'The method '.CHtml::encode($model).'::'.CHtml::encode($method).'can not be called');

                            $alert = A::t('app', 'Input incorrect parameters');
                            $alertType = 'error';
                        }
                    }
                }
            }

            if(!empty($alertType)){
                $cSession->setFlash('alertType', $alertType);
                $cSession->setFlash('alert', $alert);
            }
        }

        $cSession->remove('socialLogin');
        $cSession->remove('opauth');

        $this->redirect($returnUrl);
    }



    /**
     * Check if passed record ID is valid
     * @param int $id
     * @return SiteInfo
     */
    private function _checkSocialLoginAccess($id = 0)
    {
        $model = SocialNetworksLogin::model()->findByPk($id);
        if(!$model){
            $this->redirect('social/socialLogin');
        }
        return $model;
    }

    /**
     * Prepare settings tabs
     * @param string $activeTab general|visual|local|email|templates|server|site|cron
     */
    private function _prepareTab($activeTab = 'general')
    {
        return CWidget::create('CTabs', array(
            'tabsWrapper'=>array('tag'=>'div', 'class'=>'title'),
            'tabsWrapperInner'=>array('tag'=>'div', 'class'=>'tabs'),
            'contentWrapper'=>array(),
            'contentMessage'=>'',
            'tabs'=>array(
                A::t('app', 'Social Networks')  =>array('href'=>'social/social', 'id'=>'tab1', 'content'=>'', 'active'=>($activeTab == 'social' ? true : false)),
                A::t('app', 'Social Login')   =>array('href'=>'social/socialLogin', 'id'=>'tab2', 'content'=>'', 'active'=>($activeTab == 'sociallogin' ? true : false)),
            ),
            'return'=>true,
        ));
    }
}
