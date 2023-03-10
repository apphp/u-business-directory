<?php
/*
 * NewsSubscribers controller
 *
 * PUBLIC:                  PRIVATE:                    PROTECTED:
 * ---------------          ---------------             ---------------
 * __construct              _checkSubscribeAccess
 * addAction                _prepareTab
 * deleteAction
 * editAction
 * indexAction
 * manageAction
 * subscribeAction
 * unsubscribeAction
 * viewAction
 */
class NewsSubscribersController extends CController
{
    /**
     * Class default constructor
     */
    public function __construct()
    {
        parent::__construct();
        // block access if the module is not installed
        if(!Modules::model()->exists("code = 'news' AND is_installed = 1")){
            if(CAuth::isLoggedInAsAdmin()){
                $this->redirect('modules/index');
            }else{
                $this->redirect('index/index');
            }
        }
        Website::setMetaTags(array('title' => A::t('news', 'Subscribers')));
        $this->_view->actionMessage = '';
        $this->_view->errorField    = '';

        $this->_view->typeFirstName = ModulesSettings::model()->param('news', 'news_subscribers_first_name');
        $this->_view->typeLastName  = ModulesSettings::model()->param('news', 'news_subscribers_last_name');
        $this->_view->typeFullName  = ModulesSettings::model()->param('news', 'news_subscribers_full_name');

        $this->_view->title     = '';
        $this->_view->email     = '';
        $this->_view->firstName = '';
        $this->_view->lastName  = '';
        $this->_view->fullName  = '';
        $this->_view->dateTimeFormat  = Bootstrap::init()->getSettings()->datetime_format;

        // fetch site settings info
        $this->_view->tabs = $this->_prepareTab('subscribers');
    }


    /**
     * Controller default action handler
     * @return void
     */
    public function indexAction()
    {
        if(CAuth::isLoggedInAsAdmin()){
            $action = 'manage';
        }else{
            $action = 'subscribe';
        }
        $this->redirect('newsSubscribers/'.$action);
    }
    
    /**
     * Manage subscription action handler
     * @param string $msg
     * @return void
     */
    public function manageAction()
    {
        Website::prepareBackendAction('manage', 'news', 'newsSubscribers/manage');
        $message = A::app()->getSession()->getFlash('alert');
        
        if($message){
            $this->_view->actionMessage = CWidget::create('CMessage', array('success', $message, array('button'=>true)));
        }
        $this->_view->render('newsSubscribers/manage');
    }
    
    /**
     * Add news action handler
     * @return void
     */
    public function addAction()
    {
        Website::prepareBackendAction('add', 'news', 'newsSubscribers/manage');
        $this->_view->created_at = LocalTime::currentDateTime();
        $this->_view->render('newsSubscribers/add');
    }

    /**
     * Delete subscribe action handler
     * @param int $id the subscribe id
     * @return void
     */
    public function deleteAction($id = 0)
    {
        Website::prepareBackendAction('delete', 'news', 'newsSubscribers/manage');
        $subscribe = $this->_checkSubscribeAccess($id);

        if($subscribe->delete()){
            $msg     = A::t('news', 'Subscriber successfully deleted');
            $msgType = 'success';
        }else{
            if(APPHP_MODE == 'demo'){
                $msg     = CDatabase::init()->getErrorMessage();
                $msgType = 'warning';
            }else{
                $msg     = A::t('news', 'Error remove subscriber');
                $msgType = 'error';
            }
        }
        if(!empty($msg)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($msgType, $msg, array('button' => true)));
        }
        $this->_view->render('newsSubscribers/manage');
    }

    /**
     * Subscribe edit action handler
     * @return void
     */
    public function editAction($id = 0)
    {
        Website::prepareBackendAction('edit', 'news', 'newsSubscribers/manage');
        
        $subscribe = $this->_checkSubscribeAccess($id);
        $this->_view->id = $subscribe->id;

        $this->_view->render('newsSubscribers/edit');
    }

    /*
     * Controller view subscribe
     * @param string $msg
     * @return null
     */
    public function viewAction($msg = '')
    {
        // set frontend mode
        Website::setFrontend();
        if('added' == $msg){
            $action = 'view';
            $this->_view->title = A::t('news', 'You have successfully subscribed');
            $this->_view->actionMessage = CWidget::create('CMessage', array('success', A::t('news', 'You have subscribed')));
        }else{
            $action = 'subscribe';
        }
        $this->_view->render('newsSubscribers/'.$action);
    }

    /*
     * Adds user subscription and send message email
     * @param string $email
     * @return void
     */
    public function subscribeAction($email = '')
    {
        Website::setFrontend();
        $msgType = '';
        $msg = '';
        
        if($email){
            $email = CHash::decrypt(urldecode($email), CConfig::get('password.hashKey'));
            if(CValidator::isEmail($email)){
               $this->_view->email = $email;
            }else{
                $msgType = 'validation';
                $msg = A::t('news', 'Input incorrect parameters');
            }
        }
        
        $cRequest = A::app()->getRequest();
        if('send' == $cRequest->getPost('act') && !$msg){
            $this->_view->firstName = $cRequest->getPost('first_name');
            $this->_view->lastName = $cRequest->getPost('last_name');
            $this->_view->fullName = trim($cRequest->getPost('full_name'));
            $this->_view->email = $cRequest->getPost('email');

            $result = CWidget::create('CFormValidation', array(
                'fields' => array(
                    'email' => array('title' => A::t('news', 'Email'), 'validation' => array('type' => 'email', 'required' => true, 'maxLength' => 128)),
                    'first_name' => array('title' => A::t('news', 'First Name'), 'validation' => array('type' => 'text', 'required' => $this->_view->typeFullName == 'no' && 'allow-required' == $this->_view->typeFirstName ? true : false, 'maxLength' => 32)),
                    'last_name' => array('title' => A::t('news', 'Last Name'), 'validation' => array('type' => 'text', 'required' => $this->_view->typeFullName == 'no' && 'allow-required' == $this->_view->typeLastName ? true : false, 'maxLength' => 32)),
                    'full_name' => array('title' => A::t('news', 'Full Name'), 'validation' => array('type' => 'text', 'required' => 'allow-required' == $this->_view->typeFullName ? true : false, 'maxLength' => 64)),
                    )
                )
            );
            if($result['error']){
                $msgType = 'validation';
                $msg = $result['errorMessage'];
            }else{
                $condition = 'email = :email';
                $params = array('s:email' => $this->_view->email);
                $subscribers = new NewsSubscribers();

                if($subscribers->find($condition, $params)){
                    $msgType = 'validation';
                    $msg = A::t('news', 'Exists Email');
                }else{
                    if('no' != $this->_view->typeFullName && $this->_view->fullName){
                        $posSpace = strpos($this->_view->fullName, ' ');
                        if($posSpace){
                            $this->_view->firstName = substr($this->_view->fullName, 0, $posSpace);
                            $this->_view->lastName = substr($this->_view->fullName, $posSpace+1);
                            if(!CValidator::validateMaxLength($this->_view->firstName, 32)){
                                $msgType = 'validation';
                                $msg = A::t('core', 'The {title} field length may be {max_length} characters maximum! Please re-enter.', array('{title}'=>A::t('news', 'First Name'), '{max_length}'=>'32'));
                            }else if(!CValidator::validateMaxLength($this->_view->lastName, 32)){
                                $msgType = 'validation';
                                $msg = A::t('core', 'The {title} field length may be {max_length} characters maximum! Please re-enter.', array('{title}'=>A::t('news', 'Last Name'), '{max_length}'=>'32'));
                            }
                        }else{
                            $msgType = 'validation';
                            $msg = A::t('core', 'The field {title} cannot be empty! Please re-enter.', array('{title}'=>A::t('news', 'Last Name')));
                        }
                    }else if('no' == $this->_view->typeFullName){
                        $this->_view->fullName = ($this->_view->firstName ? $this->_view->firstName.' ' : '').$this->_view->lastName;
                    }
                    
                    if('' == $msg){
                        $subscribers->first_name = $this->_view->firstName;
                        $subscribers->last_name = $this->_view->lastName;
                        $subscribers->full_name = $this->_view->fullName;
                        $subscribers->email = $this->_view->email;
                        $subscribers->created_at = LocalTime::currentDateTime();

                        if($subscribers->save()){
                            $msgType = 'success';
                            $unsubscribeUrl = A::app()->getRequest()->getBaseUrl().'newsSubscribers/unsubscribe/email/'.urlencode(CHash::encrypt($this->_view->email, CConfig::get('password.hashKey')));
                            $params = array(
                                '{FIRST_NAME}' => $this->_view->firstName,
                                '{LAST_NAME}' => $this->_view->lastName,
                                '{UNSUBSCRIBE_URL}' => $unsubscribeUrl
                            );
                            $result = Website::sendEmailByTemplate($this->_view->email, 'news_subscription', '', $params);
                            
                            $this->_view->firstName = '';
                            $this->_view->lastName = '';
                            $this->_view->fullName = '';
                            $this->_view->email = '';
                            if($result){
                                $msg = A::t('news', 'You have subscribed');
                            }else{
                                $msg = A::t('news', 'You have successfully subscribed');
                            }
                        }else{
                            if(APPHP_MODE == 'demo'){
                                $msg     = CDatabase::init()->getErrorMessage();
                                $msgType = 'warning';
                            }else{
                                $msg     = A::t('news', 'News new record error');
                                $msgType = 'error';
                            }
                        }
                    }
                }
            }
        }
        if($msg){
            $this->_view->actionMessage = CWidget::create('CMessage', array($msgType, $msg));
        }
        $this->_view->render('newsSubscribers/subscribe');
    }

    /*
     * Deletes user subscription and send message email
     * @param string $email
     * @return void
     */
    public function unsubscribeAction($email = 0)
    {
        Website::setFrontend();
        $msg = '';
        $msgType = '';
        
        $cRequest = A::app()->getRequest();
        if('send' == $cRequest->getPost('act')){
            $result = CWidget::create('CFormValidation', array(
                'fields'=>array(
                    'email' => array(
                        'title' => A::t('news', 'Email'),
                        'validation' => array('required' => true, 'type' =>'email', 'maxLength' => 128)
                    ),
                ),
            ));

            $this->_view->email = $cRequest->getPost('email');
            $this->_view->title = A::t('news', 'Unsubscribing from News');
            
            if($result['error']){
                $msgType = 'validation';
                $msg = $result['errorMessage'];
            }else{
                $condition = 'email = :email';
                $params    = array('s:email' => $this->_view->email);

                $subscribe = NewsSubscribers::model()->find($condition, $params);

                if(!$subscribe){
                    $msgType = 'error';
                    $msg     = A::t('news', 'The specified email address not exist in our mailing list');
                }else{
                    
                    if($subscribe->delete()){
                        $msgType = 'success';
                        $msg     = A::t('news', 'You have been successfully unsubscribed');
                        $subscribeUrl = A::app()->getRequest()->getBaseUrl().'newsSubscribers/subscribe/email/'.urlencode(CHash::encrypt($this->_view->email, CConfig::get('password.hashKey')));
                        $params = array(
                            '{FIRST_NAME}'    => $subscribe->first_name,
                            '{LAST_NAME}'     => $subscribe->last_name,
                            '{SUBSCRIBE_URL}' => $subscribeUrl
                            );
                        // Email send
                        if(Website::sendEmailByTemplate($this->_view->email, 'news_unsubscription', '', $params)){
                            $msg .= ' '.A::t('news', 'You will receive a letter by email');
                        }
                        $this->_view->email = '';
                    }else{
                        if(APPHP_MODE == 'demo'){
                            $msgType = 'warning';
                            $msg     = CDatabase::init()->getErrorMessage();
                        }else{
                            $msgType = 'error';
                            $msg     = A::t('news', 'Error removing subscription');
                        }
                    }
                }
            }
        }else{
            $this->_view->title = A::t('news', 'Unsubscribing from News');
            if($email){
                $email = CHash::decrypt(urldecode($email), CConfig::get('password.hashKey'));
                if(CValidator::isEmail($email)){
                    $this->_view->email = $email;
                }else{
                    $msgType = 'validation';
                    $msg     = A::t('news', 'Input incorrect parameters');
                }
            }
        }
        if($msg){
            $this->_view->actionMessage = CWidget::create('CMessage', array($msgType, $msg));
        }
        $this->_view->render('newsSubscribers/unsubscribe');
    }

    /**
     * Check if passed subscription ID is valid
     * @param int $subscriberId
     * @return NewsSubscribers
     */
    private function _checkSubscribeAccess($subscriberId = 0)
    {
        $subscriber = NewsSubscribers::model()->findByPk((int)$subscriberId);
        if(!$subscriber){
            $this->redirect('newsSubscribers/manage');
        }
        return $subscriber;
    }

   /**
     * Prepares subscription module tabs
     * @return string
     */
    private function _prepareTab()
    {
        $result = CWidget::create('CTabs', array(
            'tabsWrapper'      => array('tag' => 'div', 'class' => 'title'),
            'tabsWrapperInner' => array('tag' => 'div', 'class' => 'tabs'),
            'contentWrapper'   => array(),
            'contentMessage'   => '',
            'tabs'=>array(
                A::t('news', 'Settings') => array('href' => 'modules/settings/code/news', 'id' => 'tabSettings', 'content' => '', 'active' => false, 'htmlOptions' => array('class'=>'modules-settings-tab')),
                A::t('news', 'News') => array('href' => 'news/manage', 'id' => 'tabNews', 'content' => '', 'active' => false),
                A::t('news', 'Subscribers') => array('href' => 'newsSubscribers/manage', 'id' => 'tabSubscribe', 'content' => '', 'active' => true),
            ),
            'events' => array(),
            'return' => true,
        ));

        return $result;
    }
}
