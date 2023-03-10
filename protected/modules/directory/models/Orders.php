<?php
/**
 * Template of [MODEL_NAME] model
 *
 * PUBLIC:                 PROTECTED                  PRIVATE
 * ---------------         ---------------            ---------------
 * __construct             _relations
 *
 * STATIC:
 * ---------------------------------------------------------------
 * model
 *
 */
class Orders extends CActiveRecord
{

    /** @var string */
    protected $_table = 'orders';
    /** @var string */
    protected $_tableAccounts = 'accounts';
    /** @var string */
    protected $_tableCustomers = 'customers';
    /** @var string */
    protected $_tableListings = 'listing_translations';


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns the static model of the specified AR class
     * @return void
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
            'customer_id' => array(
                self::HAS_ONE,
                $this->_tableCustomers,
                'id',
                'condition'=>'',
                'joinType'=>self::INNER_JOIN,
                'fields'=>array('first_name', 'last_name')
            ),
            'listing_id' => array(
                self::HAS_ONE,
                $this->_tableListings,
                'listing_id',
                'condition'=>'',
                'joinType'=>self::INNER_JOIN,
                'fields'=>array('business_name'=>'listing_name')
            ),
//          'group_id' => array(
//              self::HAS_ONE,
//              $this->_tableGroups,
//              'id',
//              'condition'=>'',
//              'joinType'=>self::LEFT_OUTER_JOIN,
//              'fields'=>array(
//                    'name'=>'group_name',
//                    'description'=>'group_description'
//                )
//          ),
        );
    }

    /**
     * This method is invoked before saving a record
     * @param string $id
     */
    protected function _beforeSave($id = 0)
    {
        if($id == 0){
            // New record
        }else{
            // Edit record
            // If status Complited (Paid)
            if($this->status == 2){
                $listing = Listings::model()->findByPk($this->listing_id);
                if(!empty($listing)){
                    $listingsApproval = ModulesSettings::model()->param('directory', 'listing_approval');
                    if($listingsApproval == 'automatic'){
                        $adverticePlan = Plans::model()->findByPk($listing->advertise_plan_id);
                        if(!empty($adverticePlan)){
                            // Change status of Approved
                            $listing->is_approved = 1;
                            $listing->date_published = date('Y-m-d H:i:s');
                            $duration = $adverticePlan->duration;
                            if(!empty($duration) && $duration != '-1'){
                                $finishTime = time() + $duration*24*60*60;
                                $finishPublished = date('Y-m-d H:i:s', $finishTime);
                            }else if($duration == -1){
                                $finishPublished = '0000-00-00 00:00:00';
                            }else{
                                $finishPublished = date('Y-m-d H:i:s', time());
                            }
                            $listing->finish_publishing = $finishPublished;
                            $listing->save();
                        }
                    }
                }
            }
        }

        return true;
    }

    /*
     * Used to define custom fields
     * This method should be overridden
     * @return array
     */
    protected function _customFields()
    {
        return array('CONCAT('.CConfig::get('db.prefix').$this->_tableCustomers.'.first_name, " ", '.CConfig::get('db.prefix').$this->_tableCustomers.'.last_name)'=>'full_name');
    }

    public function paymentHandler($type, $orderInfo = array())
    {
        if(($type == 'completed' && empty($orderInfo)) || !is_array($orderInfo)){
            $this->_errorMessage = A::t('directory', 'Input incorrect parameters');
            return false;
        }

        $return = true;
        $orderNumber = !empty($orderInfo['order_number']) ? $orderInfo['order_number'] : '';

        // Status Pending
        if($type == 'pending'){
            // Get Order Number
            if(empty($orderNumber)){
                $accountId = CAuth::getLoggedId();
                $customer = Customers::model()->find('account_id = :account_id', array(':account_id'=>$accountId));
                if(empty($customer)){
                    $this->_errorMessage = A::t('directory', 'You are not logged in to your account');
                    return false;
                }
                $order = $this->find('customer_id = :customer_id AND status = 0', array(':customer_id'=>$customer->id));
            }else{
                $order = $this->find('order_number = :order_number', array(':order_number'=>$orderNumber));
            }

            if(empty($order)){
                $this->_errorMessage = A::t('directory', 'Order cannot be found in the database');
                $return = false;
            }else{
                $order->status = 1;
            }
        }else if($type == 'completed' && $orderNumber){
            // Status Completed
            $order = $this->find('order_number = :order_number', array(':order_number'=>$orderNumber));
            if(empty($order)){
                $this->_errorMessage = A::t('directory', 'Order cannot be found in the database');
                $return = false;
            }else{
                $order->status = 2;
            }
        }else if($type == 'rejected' && $orderNumber){
            // Status Rejected
            $order = $this->find('order_number = :order_number', array(':order_number'=>$orderNumber));
            if(empty($order)){
                $this->_errorMessage = A::t('directory', 'Order cannot be found in the database');
                $return = false;
            }else{
                $order->status = 3;
                $listing = Listings::model()->findByPk($order->listing_id);
                if(!empty($listing)){
                    // Change status of Canceled
                    $listing->is_approved = 2;
                    $listing->save();
                }
            }
        }else{
            $this->_errorMessage = A::t('directory', 'Input incorrect parameters');
            $return = false;
        }

        if($return){
            // Fill in additional fields
            $order->status_changed = date('Y-m-d H:i:s');

            if(!empty($orderInfo)){
                foreach($orderInfo as $fieldName => $fieldValue){
                    if($this->isColumnExists($fieldName)){
                        $order->$fieldName = $fieldValue;
                    }
                }
            }

            if($order->status == '1' || $order->status == '2'){
                // Find the user to know send or not send a message of email
                $listing = Listings::model()->findByPk($order->listing_id);
                if(!empty($listing)){
                    $customer = Customers::model()->findByPk($listing->customer_id);
                    if(!empty($customer) && $customer->notifications){
                        // Send email
                        $providersList = array();
                        $paymentProviders = PaymentProviders::model()->findAll('is_active = 1');
                        if(!empty($paymentProviders) && is_array($paymentProviders)){
                            foreach($paymentProviders as $onePayment){
                                $providersList[$onePayment['id']] = $onePayment['name'];
                            }
                        }

                        $currencyName = '';
                        $currency = Currencies::model()->findByPk($order->currency);
                        if($currency){
                            $currencyName = $currency->name.' ('.$currency->code.')';
                        }

                        $statusList = array(
                            '0'=>A::t('directory', 'Preparing', array(), null, $customer->language_code),
                            '1'=>A::t('directory', 'Pending', array(), null, $customer->language_code),
                            '2'=>A::t('directory', 'Completed', array(), null, $customer->language_code),
                            '3'=>A::t('directory', 'Refunded', array(), null, $customer->language_code)
                        );
                        $status = $statusList[$order->status];
                        $paymentType = $providersList[$order->payment_id];
                        $dateTimeFormat = Bootstrap::init()->getSettings()->datetime_format;

                        $order->email_sent = Website::sendEmailByTemplate(
                            $customer->email,
                            'directory_success_order',
                            $customer->language_code,
                            array(
                                '{FIRST_NAME}'   => $customer->first_name,
                                '{LAST_NAME}'    => $customer->last_name,
                                '{ORDER_NUMBER}' => $order->order_number,
                                '{STATUS}'       => $status,
                                '{DATE_CREATED}' => date($dateTimeFormat, strtotime($order->created_date)),
                                '{DATE_PAYMENT}' => $order->payment_date == '0000-00-00 00:00:00' ? A::t('directory', 'Not paid yet', array(), null, $customer->language_code) : date($dateTimeFormat, strtotime($order->payment_date)),
                                '{PAYMENT_TYPE}' => $paymentType,
                                '{CURRENCY}'     => $currencyName,
                                '{LINK_LISTING}' => A::app()->getRequest()->getBaseUrl().'listings/view/id/'.$order->listing_id,
                                '{LISTING_NAME}' => $listing->business_name,
                                '{PRICE}'        => $order->total_price
                            )
                        );
                    }
                }
            }

            $order->save();
            return true;
        }else{
            return false;
        }
    }
}
