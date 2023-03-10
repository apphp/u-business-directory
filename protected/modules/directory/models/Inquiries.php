<?php
/**
 * Template of Inquiries model
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
class Inquiries extends CActiveRecord
{

    /** @var string */
    protected $_table = 'inquiries';
    /** @var string */
    protected $_tableListings = 'listings';
    /** @var string */
    protected $_tableCategories = 'category_translations';
    /** @var string */
    protected $_tableRegion = 'region_translations';
    /** @var string */
    protected $_tableListingTranslation = 'listing_translations';
    /* @var string (default|categories) */
    private $_typeRelations = 'default';

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
        $result = array(
            0 => array(
                self::HAS_MANY,
                $this->_tableListingTranslation,
                'listing_id',
                'parent_key'=>'listing_id',
                'condition'=>CConfig::get('db.prefix').$this->_tableListingTranslation.".language_code = '".A::app()->getLanguage()."'",
                'joinType'=>self::LEFT_OUTER_JOIN,
                'fields'=>array('business_name'=>'listing_name')
            ),
            1 => array(
                self::HAS_MANY,
                $this->_tableListings,
                'id',
                'parent_key'=>'listing_id',
                'condition'=>'',
                'joinType'=>self::LEFT_OUTER_JOIN,
                'fields'=>array('customer_id'),
            )
        );
        if($this->_typeRelations == 'categories'){
            $result[] = array(
                self::HAS_MANY,
                $this->_tableCategories,
                'category_id',
                'parent_key'=>'category_id',
                'condition'=>CConfig::get('db.prefix').$this->_tableCategories.".language_code = '".A::app()->getLanguage()."'",
                'joinType'=>self::LEFT_OUTER_JOIN,
                'fields'=>array('name'=>'category_name')
            );

            $result[] = array(
                self::HAS_MANY,
                $this->_tableRegion,
                'region_id',
                'parent_key'=>'region_id',
                'condition'=>CConfig::get('db.prefix').$this->_tableRegion.".language_code = '".A::app()->getLanguage()."'",
                'joinType'=>self::LEFT_OUTER_JOIN,
                'fields'=>array('name'=>'region_name')
            );
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
