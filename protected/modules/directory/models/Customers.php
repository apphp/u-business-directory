<?php
/**
 * Template of Customers model
 *
 * PUBLIC:                 PROTECTED                  PRIVATE
 * ---------------         ---------------            ---------------
 * __construct             _relations
 * getError                _beforeSave
 * getErrorField           _afterSave
 *                         _afterDelete
 *
 * STATIC:
 * ---------------------------------------------------------------
 * model
 *
 */
class Customers extends CActiveRecord
{

    /** @var string */
    protected $_table = 'customers';
    /** @var string */
    protected $_tableGroups = 'customer_groups';
    /** @var string */
    protected $_role = 'customer';
    /** @var string */
    protected $_tableAccounts = 'accounts';
    /** @var bool */
    private $_sendApprovalEmail = false;
    /** @var bool */
    private $_sendActivationEmail = false;
    /** @var bool */
    private $_sendPasswordChangedEmail = false;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns the static model of the specified AR class
     * @return Customers
     */
    public static function model()
    {
        return parent::model(__CLASS__);
    }

    /**
     * Defines relations between different tables in database and current $_table
     * @return array
     */
    protected function _relations()
    {
        return array(
            'account_id' => array(
                self::HAS_ONE,
                $this->_tableAccounts,
                'id',
                'condition'=>"role = '".$this->_role."'",
                'joinType'=>self::INNER_JOIN,
                'fields'=>array(
                    'role'=>'role',
                    'email'=>'email',
                    'language_code'=>'language_code',
                    'username'=>'username',
                    'created_at'=>'created_at',
                    'created_ip'=>'created_ip',
                    'last_visited_at'=>'last_visited_at',
                    'last_visited_ip'=>'last_visited_ip',
                    'notifications'=>'notifications',
                    'notifications_changed_at'=>'notifications_changed_at',
                    'is_active'=>'is_active',
                    'is_removed'=>'is_removed',
                    'comments'=>'comments'
                )
            ),
            'group_id' => array(
                self::HAS_ONE,
                $this->_tableGroups,
                'id',
                'condition'=>'',
                'joinType'=>self::LEFT_OUTER_JOIN,
                'fields'=>array(
                    'name'=>'group_name',
                    'description'=>'group_description'
                )
            ),
        );
    }

    /**
     * This method is invoked before saving a record
     * @param string $id
     * @return bool
     */
    protected function _beforeSave($id = 0)
    {
        $cRequest      = A::app()->getRequest();
        $firstName     = $cRequest->getPost('first_name');
        $lastName      = $cRequest->getPost('last_name');
        $username      = $cRequest->getPost('username');
        $password      = $cRequest->getPost('password');
        $salt          = $cRequest->getPost('salt');
        $email         = $cRequest->getPost('email');
        $languageCode  = $cRequest->getPost('language_code', 'alpha', A::app()->getLanguage());
        $notifications = (int)$cRequest->getPost('notifications', 'int');
        if($notifications !== 0 && $notifications !== 1) $notifications = 0;
        $isActive       = (int)$cRequest->getPost('is_active', 'int', 1);
        $isRemoved      = (int)$cRequest->getPost('is_removed', 'int');
        $comments       = $cRequest->getPost('comments');
        $ipAddress      = $cRequest->getUserHostAddress();
        $approvalType   = ModulesSettings::model()->param('directory', 'customer_approval_type');
        $changePassword = ModulesSettings::model()->param('directory', 'change_customer_password');

        $firstName = empty($firstName) && $this->isColumnExists('first_name') ? $this->first_name : $firstName;
        $lastName  = empty($lastName) && $this->isColumnExists('last_name') ? $this->last_name : $lastName;
        $username  = empty($username) && $this->isColumnExists('username') ? $this->username : $username;
        $email     = empty($email) && $this->isColumnExists('email') ? $this->email : $email;

        if($id > 0){
            $account = Accounts::model()->findByPk((int)$this->account_id);
            $salt = $account->salt;
        }else{
            $salt = !empty($salt) ? $salt : CHash::getRandomString(33);
        }

        if(CConfig::get('password.encryption')){
            $encryptAlgorithm = CConfig::get('password.encryptAlgorithm');
            $encryptSalt = $salt;
            if(!empty($password)){
                $password = CHash::create($encryptAlgorithm, $password, $encryptSalt);
            }
        }

        // check if customer with the same email already exists
        $customerExists = $this->_db->select('SELECT * FROM '.CConfig::get('db.prefix').'accounts WHERE role = :role AND email = :email AND id != :id', array(':role'=>$this->_role, ':email'=>$email, ':id'=>$this->account_id));
        if(!empty($email) && $customerExists){
            $this->_error = true;
            $this->_errorMessage = A::t('directory', 'Customer with such email already exists!');
            $this->_errorField = 'email';
            return false;
        }

        if($id > 0){
            // UPDATE CUSTOMER
            // update accounts table
            if(CAuth::isLoggedInAsAdmin()){
                $account->comments = $comments;
                $account->is_active = $isActive;
                $account->is_removed = $isRemoved;
                // logical deleting
                if($isRemoved == 1){
                    $account->is_active = 0;
                }

                // approval by admin (previously created by customer)
                if($approvalType == 'by_admin' && $account->registration_code != '' && $isActive){
                    $account->registration_code = '';
                    $this->_sendApprovalEmail = true;
                }

                // password changed by admin
                if($changePassword && $account->password != $password && !empty($password) && $isActive){
                    $this->_sendPasswordChangedEmail = true;
                }
            }

            if(!empty($password)) $account->password = $password;
            if(!empty($salt)) $account->salt = $salt;
            $account->email = $email;
            $account->language_code = $languageCode;
            if($account->notifications != $notifications){
                $account->notifications = $notifications;
                $account->notifications_changed_at = LocalTime::currentDateTime();
            }

            if($account->save()){
                // update existing customer
                if($this->birth_date == '') $this->birth_date = '0000-00-00';
                if($this->group_id == '') $this->group_id = '0';
                return true;
            }
            return false;
        }else{
            // NEW ACCOUNT
            // check if customer with the same username already exists
            if($this->_db->select('SELECT * FROM '.CConfig::get('db.prefix').'accounts WHERE role = :role AND username = :username', array(':role'=>$this->_role, ':username'=>$username))){
                $this->_error = true;
                $this->_errorMessage = A::t('directory', 'Customer with such username already exists!');
                $this->_errorField = 'username';
                return false;
            }

            // insert new customer
            if($accountId = $this->_db->insert($this->_tableAccounts, array(
                'role'=>$this->_role,
                'username'=>$username,
                'password'=>$password,
                'salt'=>$salt,
                'email'=>$email,
                'language_code'=>$languageCode,
                'created_at'=>LocalTime::currentDateTime(),
                'created_ip'=>$ipAddress,
                'notifications'=>$notifications,
                'registration_code'=>'',
                'is_active'=>$isActive,
                'comments'=>$comments
            ))){
                $this->account_id = $accountId;
                if($this->birth_date == '') $this->birth_date = '0000-00-00';
                if($this->group_id == '') $this->group_id = '0';

                // account activated by admin (previously created by admin)
                if(CAuth::isLoggedInAsAdmin() && $isActive){
                    $this->_sendActivationEmail = true;
                }
                return true;
            }
            return false;
        }
    }

    /**
     * This method is invoked after saving a record successfully
     * @param string $pk
     * @return void
     * You may override this method
     */
    protected function _afterSave($pk = '')
    {
        $cRequest = A::app()->getRequest();
        $email = $cRequest->getPost('email');
        $firstName = $cRequest->getPost('first_name');
        $lastName = $cRequest->getPost('last_name');
        $username = $cRequest->getPost('username', '', $this->username);
        $password = $cRequest->getPost('password');
        $languageCode = $cRequest->getPost('language_code');
        $isActive = (int)$cRequest->getPost('is_active', 'int');

        // send email to customer on creating new account by admininstrator (if customer is active)
        if($this->_sendActivationEmail){
            $result = Website::sendEmailByTemplate(
                $email,
                'directory_new_account_created_by_admin',
                $languageCode,
                array(
                    '{FIRST_NAME}' => $firstName,
                    '{LAST_NAME}' => $lastName,
                    '{USERNAME}' => $username,
                    '{PASSWORD}' => $password,
                )
            );
        }

        // send email to customer on admin changes customer password
        if($this->_sendPasswordChangedEmail){
            $result = Website::sendEmailByTemplate(
                $email,
                'directory_password_changed_by_admin',
                $languageCode,
                array(
                    '{FIRST_NAME}' => $firstName,
                    '{LAST_NAME}' => $lastName,
                    '{USERNAME}' => $username,
                    '{PASSWORD}' => $password,
                )
            );
        }

        // send email to customer on admin approval
        if($this->_sendApprovalEmail){
            $result = Website::sendEmailByTemplate(
                $email,
                'directory_account_approved_by_admin',
                $languageCode,
                array(
                    '{FIRST_NAME}' => $firstName,
                    '{LAST_NAME}' => $lastName
                )
            );
        }
    }

    /**
     * This method is invoked after deleting a record successfully
     * @param string $pk
     * @return void
     */
    protected function _afterDelete($pk = '')
    {
        // Prepare Selects
        $sqlDeleteReview = 'DELETE FROM '.CConfig::get('db.prefix').'reviews WHERE '.CConfig::get('db.prefix').'reviews.listing_id IN (SELECT id FROM '.CConfig::get('db.prefix').'listings WHERE customer_id = '.(int)$pk.')';
        $sqlDeleteListingsCategories = 'DELETE FROM '.CConfig::get('db.prefix').'listings_categories WHERE '.CConfig::get('db.prefix').'listings_categories.listing_id IN (SELECT id FROM '.CConfig::get('db.prefix').'listings WHERE customer_id = '.(int)$pk.')';
        $sqlDeleteListings = 'DELETE FROM '.CConfig::get('db.prefix').'listings WHERE '.CConfig::get('db.prefix').'listings.customer_id = '.(int)$pk;

        // delete record from accounts table
        if(false === $this->_db->delete($this->_tableAccounts, 'id = '.(int)$this->account_id)){
            $this->_error = true;
        }
        // Delete review
        if(!CDatabase::init()->customQuery($sqlDeleteReview)){
            $this->error = true;
        }
        // Delete records in table listings_categories
        if(CDatabase::init()->customQuery($sqlDeleteListingsCategories)){
            $this->error = true;
        }
        // Delete listings
        if(CDatabase::init()->customQuery($sqlDeleteListings)){
            $this->error = true;
        }

        if($this->error = true){
            $this->_errorMessage = A::t('directory', 'An error occurred while deleting customer account! Please try again later.');
        }
    }

    /*
     * Social Registration
     * @param array $params
     * @return void
     */
    public function registrationSocial($params = array())
    {
        $this->username     = isset($params['username']) ? $params['username'] : '';
        $this->email        = isset($params['email']) ? $params['email'] : '';
        $this->first_name   = isset($params['firstName']) ? $params['firstName'] : '';
        $this->last_name    = isset($params['lastName']) ? $params['lastName'] : '';
    }

    /**
     * Returns error description
     * @return boolean
     */
    public function getError()
    {
        return $this->_errorMessage;
    }

    /**
     * Returns error field
     * @return boolean
     */
    public function getErrorField()
    {
        return $this->_errorField;
    }

}
