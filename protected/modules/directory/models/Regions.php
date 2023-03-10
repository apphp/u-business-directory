<?php
/**
 * Template of [MODEL_NAME] model
 *
 * PUBLIC:                 PROTECTED                  PRIVATE
 * ---------------         ---------------            ---------------
 * __construct             _relations
 *                         _customFields
 *                         _afterDelete
 *
 * STATIC:
 * ---------------------------------------------------------------
 * model
 *
 */
class Regions extends CActiveRecord
{

    /** @var string */
    protected $_table = 'regions';
    /** @var string */
    protected $_tableTranslation = 'region_translations';

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
     * This method is invoked after deleting a record successfully
     * @param string $pk
     * @return void
     */
    protected function _afterDelete($pk = '')
    {
        $this->_isError = false;
        // delete country names from translation table
        if(false === $this->_db->delete($this->_tableTranslation, 'region_id = :region_id', array(':region_id'=>$pk))){
            $this->_isError = true;
        }
    }

    /**
     * Defines relations between different tables in database and current $_table
     * @return array
     */
    protected function _relations()
    {
        return array(
            'id' => array(
                self::HAS_MANY,
                $this->_tableTranslation,
                'region_id',
                'condition'=>"language_code = '".A::app()->getLanguage()."'",
                'joinType'=>self::LEFT_OUTER_JOIN,
                'fields'=>array('name', 'region_id')
            ),
        );
    }

    /*
     * Used to define custom fields
     * This method should be overridden
     * @return array
     */
    protected function _customFields()
    {
        return array('`'.CConfig::get('db.prefix').'regions`.id'=>'id_to_listings');
    }
}
