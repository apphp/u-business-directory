<?php
/**
 * Pages model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------				---------------
 * __construct             	_relations
 * model (static)			_afterSave
 * setTranslationsArray    	_afterDelete
 *                         	
 */

class Pages extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'cms_pages';
    /** @var string */
    protected $_tableTranslation = 'cms_page_translations';
    protected $_columnsTranslation = array('tag_title', 'tag_keywords', 'tag_description', 'page_header', 'page_text');
    
    private $_translationsArray;
    /** @var bool */
    private $_isError = false;
    
    /**
	 * Class default constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

	/**
	 * Returns the static model of the specified AR class
	 */
   	public static function model()
   	{
		return parent::model(__CLASS__);
   	}
    	
	/**
	 * Defines relations between different tables in database and current $_table
	 */
	protected function _relations()
	{
		return array(
			'id' => array(
				self::HAS_MANY,
				$this->_tableTranslation,
				'page_id',
				'condition'=>'language_code = \''.A::app()->getLanguage().'\'',
				'joinType'=>self::LEFT_OUTER_JOIN,
				'fields'=>array(
                    'tag_title'      =>'tag_title',
                    'tag_keywords'   =>'tag_keywords',
                    'tag_description'=>'tag_description',
                    'page_header'    =>'page_header',
                    'page_text'      =>'page_text'
                )
			),
		);
	}
	
	/**
	 * This method is invoked after saving a record successfully
	 * @param string $pk
	 */
	protected function _afterSave($id = 0)
	{
		$this->_isError = false;
			
		// if this page is home page - remove this flag from all other pages
		if($this->is_homepage){
        	if(!$this->_db->update($this->_table, array('is_homepage'=>0), 'id != :id', array(':id'=>$id))){
        		$this->_isError = true;
        	}
		}
		
		// insert/update data in translation table
		if(is_array($this->_translationsArray)){
			if($this->isNewRecord()){
				foreach($this->_translationsArray as $lang => $translations){
					$insertFields['page_id'] = $id;
					$insertFields['language_code'] = $lang;
					foreach($this->_columnsTranslation as $columnTransKey){
						$insertFields[$columnTransKey] = $translations[$columnTransKey];
					}
					if(!$this->_db->insert($this->_tableTranslation, $insertFields)){
						$this->_isError = true;
					}
				}
			}else{
				foreach($this->_translationsArray as $lang => $translations){
					$updateFields = array();
					foreach($this->_columnsTranslation as $columnTransKey){
						$updateFields[$columnTransKey] = $translations[$columnTransKey];
					}
					if(!$this->_db->update($this->_tableTranslation, $updateFields, 'page_id = :page_id AND language_code = :language_code', array(':page_id'=>$id, ':language_code'=>$lang))){
						$this->_isError = true;
					}
				}
			}
		}
	}
	
	/**
	 * This method is invoked after deleting a record successfully
	 * @param string $id
	 */
	protected function _afterDelete($id = 0)
	{
		$this->_isError = false;
		// delete page names from translation table
		if(false === $this->_db->delete($this->_tableTranslation, 'page_id="'.$id.'"')){
			$this->_isError = true;
		}
	}
	
	public function setTranslationsArray($inputArray)
	{
		$this->_translationsArray = $inputArray;
	}
	
	public function selectTranslations()
	{
		return $this->getTranslations(array('key'=>'page_id', 'value'=>$this->id, 'fields'=>$this->_columnsTranslation));
	}
	
	/**
	 * Returns boolean that indicates if the last operation was successfull
	 * @return boolean
	 */
	public function getError()
	{
		return $this->_isError;
	}
	
}
