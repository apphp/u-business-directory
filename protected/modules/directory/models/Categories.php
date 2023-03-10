<?php
/**
 * Template of Categories model
 *
 * PUBLIC:                 PROTECTED                  PRIVATE
 * ---------------         ---------------            ---------------
 * __construct             _relations
 *                         _afterDelete
 *
 * STATIC:
 * ---------------------------------------------------------------
 * model
 *
 */
class Categories extends CActiveRecord
{

    /** @var string */
    protected $_table = 'categories';
    /** @var string */
    protected $_tableTranslation = 'category_translations';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns the static model of the specified AR class
     * @return Categories
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
        if(false === $this->_db->delete($this->_tableTranslation, 'category_id = :category_id', array(':category_id'=>$pk))){
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
                'category_id',
                'condition'=>"language_code = '".A::app()->getLanguage()."'",
                'joinType'=>self::LEFT_OUTER_JOIN,
                'fields'=>array('name'=>'name', 'description'=> 'description')
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
        return array('`'.CConfig::get('db.prefix').'categories`.id'=>'id_to_listings');
    }

    /**
     * Performs search in categories
     * @param string $keywords
     * @param mixed $itemsCount
     * @return array array('0'=>array(categories), '1'=>total)
     */
    public function search($keywords = '', $itemsCount = 10)
    {
        $result = array();

        if($keywords !== ''){
            $limit = !empty($itemsCount) ? '0, '.(int)$itemsCount : '';
            $accessLevel = CAuth::isLoggedIn() ? 1 : 0;
            $condition = CConfig::get('db.prefix').$this->_tableTranslation.'.name LIKE :keywords OR '.
                CConfig::get('db.prefix').$this->_tableTranslation.'.description LIKE :keywords';

            // Count total items in result
            $total = $this->count(array('condition'=>$condition), array(':keywords'=>'%'.$keywords.'%'));

            // Prepare categories result
            $categories = $this->findAll(array('condition'=>$condition, 'limit' => $limit), array(':keywords'=>'%'.$keywords.'%'));
            foreach($categories as $key => $val){
                $result[0][] = array(
                    'title'         => $val['name'],
                    'intro_image'   => (!empty($val['icon']) ? '<img class="icon-thumb category-icon" src="images/modules/directory/categories/'.(!empty($val['icon']) ? CHtml::encode($val['icon']) : 'no_image.png').'" alt="category icon" />' : ''),
                    'content'       => $val['description'],
                    'link'          => 'categories/view/id/'.$val['id']
                );
            }
            $result[1] = $total;
        }
        return $result;
    }
}
