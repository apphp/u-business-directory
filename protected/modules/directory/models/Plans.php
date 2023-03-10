<?php
/**
 * Template of Plans model
 *
 * PUBLIC:                 PROTECTED                  PRIVATE
 * ---------------         ---------------            ---------------
 * __construct             _relations
 *                         _afterSave
 *                         _afterDelete
 *
 * STATIC:
 * ---------------------------------------------------------------
 * model
 *
 */
class Plans extends CActiveRecord
{

    /** @var string */
    protected $_table = 'advertise_plans';
    /** @var string */
    protected $_tableTranslation = 'advertise_plan_translations';

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
     * This method is invoked after saving a record successfully
     * @param string $id
     * @return void
     */
    protected function _afterSave($id = 0)
    {
        $this->_isError = false;

        // if this group is default - remove default flag in all other languages
        if($this->is_default){

            if(!$this->_db->update($this->_table, array('is_default'=>0), 'id != :id', array(':id'=>$id))){
                $this->_isError = true;
            }
        }
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
        if(false === $this->_db->delete($this->_tableTranslation, 'advertise_plan_id = :advertise_plan_id', array(':advertise_plan_id'=>$pk))){
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
                'advertise_plan_id',
                'condition'=>"`language_code` = '".A::app()->getLanguage()."'",
                'joinType'=>self::LEFT_OUTER_JOIN,
                'fields'=>array('name'=>'name', 'description'=>'description')
            ),
        );
    }
}
