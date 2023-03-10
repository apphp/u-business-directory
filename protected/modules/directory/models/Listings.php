<?php
/**
 * Template of Listings model
 *
 * PUBLIC:                 PROTECTED                  PRIVATE
 * ---------------         ---------------            ---------------
 * __construct             _relations
 *                         _beforeSave
 *                         _afterDelete
 *
 * STATIC:
 * ---------------------------------------------------------------
 * model
 *
 */
class Listings extends CActiveRecord
{

    /** @var string */
    protected $_table = 'listings';
    /** @var string */
    protected $_tableTranslation = 'listing_translations';
    /** @var string */
    protected $_tableListingsCategories = 'listings_categories';
    /** @var string */
    protected $_tableAdvertisePlans = 'advertise_plans';
    /** @var string */
    protected $_tableOrders = 'orders';
    /* @var string (listings(default)|listingsCategories|orders|plans) */
    private $_typeRelations = 'listings';

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
     * This method is invoked before saving a record
     * @param string $id
     * @return bool
     */
    protected function _beforeSave($id = 0)
    {
        $advertisePlan = Plans::model()->findByPk($this->advertise_plan_id);
        // If the listing approve a administrator, set finish publishing
        if(CAuth::isLoggedInAsAdmin()){
            $finishPublishing = $this->finish_publishing;
            // Determine the listing is approved (example: change status from pending in approved)
            if($this->is_approved == 1 && (empty($finishPublishing) || strpos($finishPublishing, '0000-00-00') !== false)){
                $datePublished = $this->date_published && $this->date_published != '0000-00-00 00:00:00' ? $this->date_published : date('Y-m-d H:i:s');
                $finish = date('Y-m-d H:i:s');
                if(!empty($advertisePlan)){
                    // Change status of approved
                    $duration = $advertisePlan->duration;
                    if(!empty($duration) && $duration != '-1'){
                        $finishTime = strtotime($datePublished) + $duration * 24 * 60 * 60;
                        $finish = date('Y-m-d H:i:s', $finishTime);
                    }else if($duration == -1){
                        $finish = '0000-00-00 00:00:00';
                    }
                }
                $this->finish_publishing = $finish;
                $this->date_published = $datePublished;
                A::app()->getRequest()->setPost('finish_publishing', $finish);
            }
        }
        // If new Listing
        if(empty($advertisePlan)){
            $this->_error = true;
            $this->_errorMessage = A::t('directory', 'Selected non-existent Advertise Plan');
            return false;
        }

        // Check the number of words in a field the keywords
        $countKeywords = $advertisePlan->keywords_count;
        if(!CAuth::isLoggedInAsAdmin() && $this->keywords){
            $matches = array();
            $pattern = '~(\w+)~';
            preg_match_all($pattern, $this->keywords , $matches);
            if(count($matches[1]) > $countKeywords){
                $this->_error = true;
                $this->_errorMessage = A::t('directory', 'The {field} field length may be {number} words maximum! Please re-enter.', array('{field}'=>A::t('directory', 'Keywords'), '{number}'=>$countKeywords));
                return false;
            }
        }
        return true;
    }

    /**
     * This method is invoked after deleting a record successfully
     * @param string $pk
     * @return void
     */
    protected function _afterDelete($pk = '')
    {
        $this->_isError = false;
        // delete country names from translation table
        if(false === $this->_db->delete($this->_tableTranslation, 'listing_id = :listing_id', array(':listing_id'=>$pk))){
            $this->_isError = true;
        }
    }

    /**
     * Defines relations between different tables in database and current $_table
     * @return array
     */
    protected function _relations()
    {
        $result = array(
            'id' => array(
                self::HAS_MANY,
                $this->_tableTranslation,
                'listing_id',
                'condition'=>"`language_code` = '".A::app()->getLanguage()."'",
                'joinType'=>self::LEFT_OUTER_JOIN,
                'fields'=>array('business_name', 'business_description')
            ),
        );

        if($this->_typeRelations == 'listingsCategories'){
            $result[] = array(
                self::HAS_MANY,
                $this->_tableListingsCategories,
                'listing_id',
                'parent_key' => 'id',
                'condition' => '',
                'joinType' => self::INNER_JOIN,
                'fields' => array('category_id')
            );
        }else if($this->_typeRelations == 'orders' || $this->_typeRelations == 'plans'){
            if($this->_typeRelations == 'orders'){
                $result[] = array(
                    self::HAS_MANY,
                    $this->_tableOrders,
                    'listing_id',
                    'parent_key' => 'id',
                    'condition' => '',
                    'joinType' => self::LEFT_OUTER_JOIN,
                    'fields' => array('status'=>'order_plan_status')
                );
            }
            $result[] = array(
                self::HAS_MANY,
                $this->_tableAdvertisePlans,
                'id',
                'parent_key' => 'advertise_plan_id',
                'condition' => '',
                'joinType' => self::INNER_JOIN,
                'fields' => array('price', 'categories_count')
            );
        }

        return $result;
    }

    /**
     * Used to define custom fields
     * This method should be overridden
     * Usage: 'CONCAT(last_name, " ", first_name)'=>'fullname'
     *        '(SELECT COUNT(*) FROM '.CConfig::get('db.prefix').$this->_tableTranslation.')'=>'records_count'
     */
    protected function _customFields()
    {
        return array(
            "IF(
                ".CConfig::get('db.prefix').$this->_table.".finish_publishing != '0000-00-00 00:00:00' AND ".CConfig::get('db.prefix').$this->_table.".finish_publishing != '' AND ".CConfig::get('db.prefix').$this->_table.".finish_publishing < '".LocalTime::currentDateTime()."', 1, 0
            )" => 'status_expired'
        );
    }

    /**
     * Performs search in listings
     * @param string $keywords
     * @param mixed $itemsCount
     * @return array array('0'=>array(listings), '1'=>total)
     */
    public function search($keywords = '', $itemsCount = 10)
    {
        $result = array();

        if($keywords !== ''){
            $limit = !empty($itemsCount) ? '0, '.(int)$itemsCount : '';
            $accessLevel = CAuth::isLoggedIn() ? 1 : 0;
            $condition = CConfig::get('db.prefix').$this->_table.'.is_published = 1 AND access_level <= '.$accessLevel.' AND `is_approved` = 2 AND '.
                CConfig::get('db.prefix').$this->_tableTranslation.'.business_name LIKE :keywords OR '.
                CConfig::get('db.prefix').$this->_tableTranslation.'.business_description LIKE :keywords';

            // Count total items in result
            $total = $this->count(array('condition'=>$condition), array(':keywords'=>'%'.$keywords.'%'));

            // Prepare listings result
            $listings = $this->findAll(array('condition'=>$condition, 'limit' => $limit), array(':keywords'=>'%'.$keywords.'%'));
            foreach($listings as $key => $val){
                $result[0][] = array(
                    'date'          => $val['date_published'],
                    'title'         => $val['business_name'],
                    'intro_image'   => (!empty($val['image_file_thumb']) ? '<img class="image-file-thumb listing-icon" src="images/modules/directory/listings/thumbs/'.CHtml::encode($val['image_file_thumb']).'" alt="listing icon" />' : ''),
                    'content'       => $val['business_description'],
                    'link'          => 'listings/view/id/'.$val['id']
                );
            }
            $result[1] = $total;
        }
        return $result;
    }

    /**
     * Set variable $this->_typeRelations
     * @param string $type
     * @return void
     */
    public function setTypeRelations($type)
    {
        if(is_string($type)){
            $this->_typeRelations = $type;
        }
    }

    /**
     * Reset variable $this->_typeRelations
     * @return void
     */
    public function resetTypeRelations()
    {
        $this->_typeRelations = '';
    }

    /**
     * Get variable $this->_typeRelations
     * @return string
     */
    public function getTypeRelations()
    {
        return $this->_typeRelations;
    }

}
