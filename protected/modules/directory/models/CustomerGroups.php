<?php
/**
 * Template of CustomerGroups model
 *
 * PUBLIC:                 PROTECTED                  PRIVATE
 * ---------------         ---------------            ---------------
 * __construct             _afterSave
 *
 * STATIC:
 * ---------------------------------------------------------------
 * model
 *
 */
class CustomerGroups extends CActiveRecord
{

    /** @var string */
    protected $_table = 'customer_groups';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns the static model of the specified AR class
     * @return CustomerGroups
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
}
