<?php
/**
 * Template of ListingsCategories model
 *
 * PUBLIC:                 PROTECTED                  PRIVATE
 * ---------------         ---------------            ---------------
 * __construct             _relations
 * getTypeRelations
 * setTypeRelations
 * resetTypeRelations
 *
 * STATIC:
 * ---------------------------------------------------------------
 * model
 *
 */
class ListingsCategories extends CActiveRecord
{

    /** @var string */
    protected $_table = 'listings_categories';
    /** @var string */
    protected $_tableListings = 'listings';
    /** @var string */
    protected $_tableListingsTranslation = 'listing_translations';
    /** @var string */
    protected $_tableCategories = 'categories';
    /** @var string */
    protected $_tableCategoriesTranslation = 'category_translations';
    /* @var string or array (listings|listingsFull|categories|none)*/
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
     * Defines relations between different tables in database and current $_table
     * @return array
     */
    protected function _relations()
    {
        $output = array();

        if($this->_typeRelations == 'categories'){
            $output[] = array(
                self::HAS_MANY,
                $this->_tableCategories,
                'id',
                'parent_key' => 'category_id',
                'joinType'=>self::LEFT_OUTER_JOIN,
                'fields'=>array('icon', 'icon_map')
            );
            $output[] = array(
                self::HAS_MANY,
                $this->_tableCategoriesTranslation,
                'category_id',
                'parent_key' => 'category_id',
                'condition'=>"`language_code` = '".A::app()->getLanguage()."'",
                'joinType'=>self::LEFT_OUTER_JOIN,
                'fields'=>array('name', 'description')
            );
        }

        if($this->_typeRelations == 'listings' || $this->_typeRelations == 'listingsFull'){
            $fieldsListing = array('customer_id', 'image_file', 'image_file_thumb', 'business_address', 'date_published', 'finish_publishing', 'region_longitude', 'region_latitude', 'is_featured');
            if($this->_typeRelations == 'listingsFull'){
                $fieldsListing = array_merge($fieldsListing, array('region_id', 'subregion_id', 'image_1', 'image_1_thumb', 'image_2', 'image_2_thumb', 'image_3', 'image_3_thumb', 'website_url', 'video_url', 'keywords', 'business_email', 'business_phone', 'business_fax', 'sort_order', 'access_level', 'is_published', 'is_approved', 'advertise_plan_id'));
            }
            $output[] = array(
                self::HAS_MANY,
                $this->_tableListings,
                'id',
                'parent_key' => 'listing_id',
                'joinType'=>self::LEFT_OUTER_JOIN,
                'fields'=>$fieldsListing
            );
            $output[] = array(
                self::HAS_MANY,
                $this->_tableListingsTranslation,
                'listing_id',
                'parent_key' => 'listing_id',
                'condition'=>"`language_code` = '".A::app()->getLanguage()."'",
                'joinType'=>self::LEFT_OUTER_JOIN,
                'fields'=>array('business_name', 'business_description')
            );
        }

        return $output;
    }

    /**
     * Used to define custom fields
     * This method should be overridden
     * Usage: 'CONCAT(last_name, " ", first_name)'=>'fullname'
     *        '(SELECT COUNT(*) FROM '.CConfig::get('db.prefix').$this->_tableTranslation.')'=>'records_count'
     */
    protected function _customFields()
    {
        $return = array();
        if('listingsFull' == $this->_typeRelations){
            $return = array(
                "IF(
                    ".CConfig::get('db.prefix').$this->_tableListings.".finish_publishing != '0000-00-00 00:00:00' AND ".CConfig::get('db.prefix').$this->_tableListings.".finish_publishing != '' AND ".CConfig::get('db.prefix').$this->_tableListings.".finish_publishing < '".LocalTime::currentDateTime()."', 1, 0
                )" => 'status_expired'
            );
        }

        return $return;
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
