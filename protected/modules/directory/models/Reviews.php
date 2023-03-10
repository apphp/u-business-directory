<?php
/**
 * Template of InquiriesReplies model
 *
 * PUBLIC:                 PROTECTED                  PRIVATE
 * ---------------         ---------------            ---------------
 * __construct
 * getTypeRelations
 * setTypeRelations
 * resetTypeRelations
 *
 * STATIC:
 * ---------------------------------------------------------------
 * model
 *
 */
class Reviews extends CActiveRecord
{

    /** @var string */
    protected $_table = 'reviews';
    /** @var string */
    protected $_tableListings = 'listings';
    /** @var string */
    protected $_tableListingsTranslation = 'listing_translations';
    /* @var string (listings|none)*/
    private $_typeRelations = '';

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
        if($this->_typeRelations == 'none'){
            $output = array();
        }else if($this->_typeRelations == 'listings'){
            $output = array(
                0 => array(
                    self::HAS_MANY,
                    $this->_tableListings,
                    'id',
                    'parent_key' => 'listing_id',
                    'joinType'=>self::LEFT_OUTER_JOIN,
                    'fields'=>array('is_approved')
                ),
                1 => array(
                    self::HAS_MANY,
                    $this->_tableListingsTranslation,
                    'listing_id',
                    'parent_key' => 'listing_id',
                    'condition'=>"`language_code` = '".A::app()->getLanguage()."'",
                    'joinType'=>self::LEFT_OUTER_JOIN,
                    'fields'=>array('business_name'=>'listing_name')
                ),
            );
        }else{
            $output = array(
                'listing_id' => array(
                    self::HAS_MANY,
                    $this->_tableListingsTranslation,
                    'listing_id',
                    'condition'=>"`language_code` = '".A::app()->getLanguage()."'",
                    'joinType'=>self::LEFT_OUTER_JOIN,
                    'fields'=>array('business_name'=>'listing_name')
                )
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
        if($this->_typeRelations == 'listings'){
            return array(
                "IF(
                    ".CConfig::get('db.prefix').$this->_tableListings.".finish_publishing != '0000-00-00 00:00:00' AND ".CConfig::get('db.prefix').$this->_tableListings.".finish_publishing != '' AND ".CConfig::get('db.prefix').$this->_tableListings.".finish_publishing < '".LocalTime::currentDateTime()."', 1, 0
                )" => 'status_expired'
            );
        }else{
            return array();
        }
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
