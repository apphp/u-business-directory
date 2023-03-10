<?php
/**
 * CGridView widget helper class file
 *
 * @project ApPHP Framework
 * @author ApPHP <info@apphp.com>
 * @link http://www.apphpframework.com/
 * @copyright Copyright (c) 2012 - 2015 ApPHP Framework
 * @license http://www.apphpframework.com/license/
 *
 * PUBLIC (static):			PROTECTED:					PRIVATE (static):		
 * ----------               ----------                  ----------
 * init													_additionalParams
 * 														_customParams
 * 														_getFieldParam
 */	  

class CGridView extends CWidgs
{

	/** @const string */
    const NL = "\n";
    /** @var string */
    private static $_rowCount = 0;
    /** @var string */
    private static $_pickerCount = 0;
    /** @var string */
    private static $_autocompleteCount = 0;

    /**
     * Draws grid view
     * @param array $params
     *
     * Notes:
     *   - to disable any field, including filtering or button use: 'disabled'=>true
     *   - insert code (for all fields): 'prependCode=>'', 'appendCode'=>''
     *   - 'data'=>'' - attribute for type 'label', allows to show data from PHP variables
     *   - 'case'=>'normal' - attribute for type 'label', allows to convert value to 'upper', 'lower' or 'camel' cases
     *   - 'maxLength'=>'X' - attribute for type 'label', specifies to show maximum X characters of the string
     *   - 'aggregate'=>array('function'=>'sum|avg') - allow to run aggregate function on specific column
     *   - 'sourceField'=>'' - used to show data from another field
     *   - 'callback'=>array('class'=>'className', 'function'=>'functionName', 'params'=>$functionParams)
     *      callback of closure function that is called when item created (available for labels only), $record - all current record
     *      <  5.3.0 function functionName($record, $params){ return record['field_name']; }
     *      >= 5.3.0 $functionName = function($record, $params){ return record['field_name']; }
     *      Ex.: function callbackFunction($record, $params){...}
     *   - select classes: 'class'=>'chosen-select-filter' or 'class'=>'chosen-select'
     *   
     *   *** SORTING:
     *   - 'sortType'=>'string|numeric' - defines soritng type ('string' is default)
     *   - 'sortBy'=>'' - defines field to perform sorting by
     *   
     *   *** FILTERING:
     *   - to perform search by few fields define them comma separated: 'field1,field2' => array(...)
     *   - for filters attribute 'table' is empty by default. Remember: to add CConfig::get('db.prefix') in 'table'=>CConfig::get('db.prefix').'table'
     *   - 'compareType'=>'string|numeric|binary' - attribute for filtering fields
     *   
     * Usage:
     *  echo CWidget::create('CGridView', array(
     *    'model'				=> 'ModelName',
     *    'actionPath'			=> 'controller/action',
     *    'condition'			=> CConfig::get('db.prefix').'countries.id <= 30',
     *    'groupBy'				=> '',
     *    'defaultOrder'		=> array('field_1'=>'DESC', 'field_2'=>'ASC' [,...]),
     *    'passParameters'		=> false,
     *    [DEPRECATED from v0.7.1] 'customParameters'	=> array('param_1'=>'integer', 'param_1'=>'string' [,...]), 
	 *    'pagination'			=> array('enable'=>true, 'pageSize'=>20),
	 *    'sorting'				=> true,
	 *    'linkType' 			=> 0,
	 *    'options'	=> array(
	 *    	 'filterDiv' 	=> array('class'=>''),
	 *    	 'gridWrapper'	=> array('tag'=>'div', 'class'=>''),
	 *    	 'gridTable' 	=> array('class'=>''),
	 *    ),
     *    'filters'	=> array(
     *    	 'field_1' 	=> array('title'=>'Field 1', 'type'=>'textbox', 'table'=>'', 'operator'=>'=', 'default'=>'', 'width'=>'', 'maxLength'=>'', 'htmlOptions'=>array()),
     *    	 'field_2' 	=> array('title'=>'Field 2', 'type'=>'textbox', 'table'=>'', 'operator'=>'=', 'default'=>'', 'width'=>'', 'maxLength'=>'', 'autocomplete'=>array('enable'=>true, 'ajaxHandler'=>'path/to/handler/file', 'minLength'=>3, 'returnId'=>true), 'htmlOptions'=>array()),
     *    	 'field_3' 	=> array('title'=>'Field 3', 'type'=>'enum', 'table'=>'', 'operator'=>'=', 'default'=>'', 'width'=>'', 'source'=>array('0'=>'No', '1'=>'Yes'), 'emptyOption'=>true, 'emptyValue'=>'', 'htmlOptions'=>array('class'=>'chosen-select-filter')),
     *    	 'field_4' 	=> array('title'=>'Field 4', 'type'=>'datetime', 'table'=>'', 'operator'=>'=', 'default'=>'', 'width'=>'80px', 'maxLength'=>'', 'format'=>'', 'htmlOptions'=>array()),
     *    ),
	 *    'fields'	=> array(
	 *       'field_1' => array('title'=>'Field 1', 'type'=>'index', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>false),
	 *       'field_2' => array('title'=>'Field 2', 'type'=>'concat', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'concatFields'=>array('first_name', 'last_name'), 'concatSeparator'=>', ',),
	 *       'field_3' => array('title'=>'Field 3', 'type'=>'decimal', 'align'=>'', 'width'=>'', 'class'=>'right', 'headerClass'=>'right', 'isSortable'=>true, 'format'=>'american|european', 'decimalPoints'=>''),
	 *       'field_4' => array('title'=>'Field 4', 'type'=>'datetime', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>''),
	 *       'field_5' => array('title'=>'Field 5', 'type'=>'enum', 'align'=>'', 'width'=>'', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'source'=>array('0'=>'No', '1'=>'Yes')),
	 *       'field_6' => array('title'=>'Field 6', 'type'=>'image', 'align'=>'', 'width'=>'', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'imagePath'=>'images/flags/', 'defaultImage'=>'', 'imageWidth'=>'16px', 'imageHeight'=>'16px', 'alt'=>''),
	 *       'field_7' => array('title'=>'Field 7', 'type'=>'label', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'stripTags'=>false, 'case'=>'', 'maxLength'=>'', 'callback'=>array('function'=>$functionName, 'params'=>$functionParams)),
	 *       'field_8' => array('title'=>'Field 8', 'type'=>'link', 'align'=>'', 'width'=>'', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'linkUrl'=>'path/to/param/{field_name}', 'linkText'=>'', 'definedValues'=>array(), 'htmlOptions'=>array()),
	 *       'field_9' => array('title'=>'Field 9', 'type'=>'evaluation', 'align'=>'', 'width'=>'', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'minValue'=>1, 'maxValue'=>5, 'tooltip'=>A::t('app', 'Value'), 'counts'=>array('fieldName'=>'', 'title'=>A::t('app', 'Evaluations')), 'definedValues'=>array(), 'htmlOptions'=>array()),
	 *       'field_10' => array('title'=>'Field 10', 'type'=>'template', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>false, 'sortBy'=>'', 'html'=>'{category_id}', 'fields'=>array('category_id'=>array('default'=>'', 'prefix'=>'', 'postfix'=>'', 'source'=>array()))),
	 *    ),
	 *    'actions'	=> array(
     *    	 'edit'    => array('link'=>'locations/edit/id/{id}/page/{page}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>'Edit this record'),
     *    	 'delete'  => array('link'=>'locations/delete/id/{id}/page/{page}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>'Delete this record', 'onDeleteAlert'=>true),
     *    ),
	 *    'return'=>true,
     *  ));
    */
    public static function init($params = array())
    {
		parent::init($params);
		
        $output 	   		= '';
        $model 		   		= self::params('model', '');
		$actionPath    		= self::params('actionPath', '');
		$condition 	   		= self::params('condition', '');
		$groupBy			= self::params('groupBy', '');
		$defaultOrder  		= self::params('defaultOrder', '');
		$passParameters 	= (bool)self::params('passParameters', false);
		$customParameters 	= self::params('customParameters', false);
        $return 	   		= (bool)self::params('return', true);
		$fields 	   		= self::params('fields', array());
		$filters	   		= self::params('filters', array());
		$actions 	   		= self::params('actions', array());
		$sortingEnabled 	= (bool)self::params('sorting', false);
		$filterDiv			= self::params('options.filterDiv', array());
		$filterItemDiv		= self::params('options.filterItemDiv', array());
		$gridWrapper		= self::params('options.gridWrapper', array());
		$gridTable			= self::params('options.gridTable', array());        
        $linkType 			= (int)self::params('linkType', 0);	/* Link type: 0 - standard, 1 - SEO */
		$pagination 		= (bool)self::params('pagination.enable', '');
		$pageSize 			= abs((int)self::params('pagination.pageSize', '10'));
		$activeActions 		= is_array($actions) ? count($actions) : 0;
		$onDeleteRecord 	= false;
		
		$baseUrl = A::app()->getRequest()->getBaseUrl();		

		// Remove disabled actions
		if(is_array($actions)){
			foreach($actions as $key => $val){
				if((bool)self::keyAt('disabled', $val) === true){
					unset($actions[$key]);
				}
				if((bool)self::keyAt('onDeleteAlert', $val) === true){
					$onDeleteRecord = true;
				}
			}
			$activeActions = count($actions);
		}
		
		// Prepare sorting variables
		// ---------------------------------------		
		$sortBy = '';
		$sortDir = '';
		$sortUrl = '';
		$orderClause = '';
		if($sortingEnabled){
			$sortBy = A::app()->getRequest()->getQuery('sort_by', 'dbfield', '');		
			$sortDir = (strtolower(A::app()->getRequest()->getQuery('sort_dir', 'alpha', '')) == 'desc') ? 'desc' : 'asc';		
		}
		
		if($sortingEnabled && !empty($sortBy)){
			$sortType = self::_getFieldParam($fields, $sortBy, 'sortType', array('string', 'numeric'));
			$orderClause = $sortBy.($sortType == 'numeric' ? ' + 0 ' : '').' '.$sortDir;
		}else if(is_array($defaultOrder)){
			foreach($defaultOrder as $oField => $oFieldType){
				$sortType = self::_getFieldParam($fields, $oField, 'sortType', array('string', 'numeric'));
				$orderClause .= (!empty($orderClause) ? ', ' : '').$oField.($sortType == 'numeric' ? ' + 0 ' : '').' '.$oFieldType;
			}					
		}
		
		// Prepare filter variables
		// ---------------------------------------
		$whereClause = (!empty($condition)) ? $condition : '';
		$filterUrl = '';		
		if(is_array($filters) && !empty($filters)){
			// Remove disabled fields
			foreach($filters as $key => $val){
				if((bool)self::keyAt('disabled', $val) === true) unset($filters[$key]);
			}

			$output .= CHtml::openTag('div', array('class'=>(!empty($filterDiv['class']) ? $filterDiv['class'] : 'filtering-wrapper'))).self::NL;
			$output .= CHtml::openForm($actionPath, 'get', array('id'=>'frmFilter'.$model)).self::NL;
			foreach($filters as $fKey => $fValue){
				$title 			= isset($fValue['title']) ? $fValue['title'] : '';				
				$type 			= isset($fValue['type']) ? $fValue['type'] : '';
                $table 			= isset($fValue['table']) ? $fValue['table'] : '';
				$width 			= (isset($fValue['width']) && CValidator::isHtmlSize($fValue['width'])) ? 'width:'.$fValue['width'].';' : '';
				$maxLength 		= isset($fValue['maxLength']) && !empty($fValue['maxLength']) ? (int)$fValue['maxLength'] : '255';
				$fieldOperator 	= isset($fValue['operator']) ? $fValue['operator'] : '';
                $fieldDefaultValue = isset($fValue['default']) ? $fValue['default'] : '';
				$compareType 	= isset($fValue['compareType']) ? $fValue['compareType'] : '';
				$autocomplete 	= isset($fValue['autocomplete']) ? $fValue['autocomplete'] : array();
				$htmlOptions 	= isset($fValue['htmlOptions']) ? $fValue['htmlOptions'] : array();
				
                if(A::app()->getRequest()->getQuery('but_filter')){                    
                    $fieldValue = CHtml::decode(A::app()->getRequest()->getQuery($fKey));
                }else{
                    $fieldValue = $fieldDefaultValue;
                }
				
				if(!empty($filterItemDiv)){
					$output .= CHtml::openTag('div', array('class'=>(!empty($filterItemDiv['class']) ? $filterItemDiv['class'] : ''))).self::NL;
				}
				$output .= !empty($title) ? $title.': ' : '';
				switch($type){

					case 'enum':
						$source = isset($fValue['source']) ? $fValue['source'] : array();
						$emptyOption = isset($fValue['emptyOption']) ? (bool)$fValue['emptyOption'] : false;
						$emptyValue = isset($fValue['emptyValue']) ? $fValue['emptyValue'] : '';
						$sourceCount = count($source);
						if($sourceCount >= 1 || !empty($emptyOption)){
							if($emptyOption){
								$source = array(''=>$emptyValue) + $source;
							}
							$htmlOptions['style'] = $width;
							$output .= (count($source)) ? CHtml::dropDownList($fKey, $fieldValue, $source, $htmlOptions) : 'no';	
						}else{
							$output .= A::t('core', 'none');
						}
						$output .= '&nbsp;'.self::NL;
						break;

					case 'datetime':
						$format = isset($fieldInfo['format']) ? $fieldInfo['format'] : 'yy-mm-dd';
						$htmlOptions['maxlength'] = $maxLength;
						$htmlOptions['style'] = $width;
						$output .= CHtml::textField($fKey, $fieldValue, $htmlOptions);
						A::app()->getClientScript()->registerCssFile('js/vendors/jquery/jquery-ui.min.css');
						// UI:
						//		dateFormat: dd/mm/yy | d M, y | mm/dd/yy  | yy-mm-dd 
						// Bootstrap:
						// 		dateFormat: dd/mm/yyyy | d M, y | mm/dd/yyyy  | yyyy-mm-dd
						//		autoclose: true,
						A::app()->getClientScript()->registerScript(
							'datepicker_'.self::$_pickerCount++,
							'jQuery("#'.$fKey.'").datepicker({
								showOn: "button",
								buttonImage: "js/vendors/jquery/images/calendar.png",
								buttonImageOnly: true,
								showWeek: false,
								firstDay: 1,
								autoclose: true,
								format: "'.($format == 'yy-mm-dd' ? 'yyyy-mm-dd' : $format).'",
								dateFormat: "'.$format.'",
								changeMonth: true,
								changeYear: true,
								appendText : ""
							});'
						);
						break;

					case 'textbox':
					default:						
						$autocompleteEnabled = self::keyAt('enable', $autocomplete);
						$autocompleteAjaxHandler = self::keyAt('ajaxHandler', $autocomplete, '');
						$autocompleteMinLength = self::keyAt('minLength', $autocomplete, 1);
						$autocompleteReturnId = self::keyAt('returnId', $autocomplete, true);

						if($autocompleteEnabled){
							$cRequest = A::app()->getRequest();
							
							A::app()->getClientScript()->registerCssFile('js/vendors/jquery/jquery-ui.min.css');
							// Already included in backend default.php
							if(A::app()->view->getTemplate() != 'backend'){
								A::app()->getClientScript()->registerScriptFile('js/vendors/jquery/jquery-ui.min.js', 2);	
							}							
							
							$fKeySearch = $autocompleteReturnId ? $fKey.'_result' : $fKey;
							A::app()->getClientScript()->registerScript(
								'autocomplete_'.self::$_autocompleteCount++,
								'jQuery("#'.$fKeySearch.'").autocomplete({
									source: function(request, response){
										$.ajax({
											url: "'.CHtml::encode($autocompleteAjaxHandler).'",
											global: false,
											type: "POST",
											data: ({
												'.$cRequest->getCsrfTokenKey().': "'.$cRequest->getCsrfTokenValue().'",
												act: "send",
												search : jQuery("#'.$fKeySearch.'").val()
											}),
											dataType: "json",
											async: true,
											error: function(html){
												'.((APPHP_MODE == 'debug') ? 'alert("AJAX: cannot connect to the server or server response error! Please try again later.");' : '').'
											},
											success: function(data){
												if(data.length == 0){
													response({label: "'.A::te('core', 'No matches found').'"});
												}else{
													response($.map(data, function(item){
														if(item.label !== undefined){
															return {id: '.($autocompleteReturnId ? 'item.id' : 'item.label').', label: item.label}	
														}else{
															// Empty search value if nothing found
															jQuery("#'.$fKey.'").val("");
														}
													}));
												}
											}
										});
									},
									minLength: '.(int)$autocompleteMinLength.',
									select: function(event, ui) {
										jQuery("#'.$fKey.'").val(ui.item.id);
										if(typeof(ui.item.id) == "undefined"){
											jQuery("#'.$fKeySearch.'").val("");
											return false;
										}
									}
								});',
								4
							);

							if($autocompleteReturnId){
								// Draw hidden field for real field
								$output .= CHtml::hiddenField($fKey, CHtml::encode($fieldValue), $htmlOptions).self::NL;								
							}
							// Draw textbox
							$htmlOptions['style'] = $width;
							$htmlOptions['maxlength'] = $maxLength;
							$fieldValueSearch = $cRequest->getQuery($fKeySearch);
							$output .= CHtml::textField($fKeySearch, CHtml::encode($fieldValueSearch), $htmlOptions).self::NL;
						}else{
							// Draw textbox
							$htmlOptions['style'] = $width;
							$htmlOptions['maxlength'] = $maxLength;
							$output .= CHtml::textField($fKey, CHtml::encode($fieldValue), $htmlOptions).self::NL;
						}						
						break;
				}
				if(!empty($filterItemDiv)){
					$output .= CHtml::closeTag('div').self::NL;
				}
				
				if($fieldValue !== ''){
                    $filterUrl .= (!empty($filterUrl) ? '&' : '').$fKey.'='.$fieldValue;

					// Check if there is an autocomplete key that must be added to filter string
					$autocompleteValue = A::app()->getRequest()->getQuery($fKey.'_result');
					if($autocompleteValue != ''){
						$filterUrl .= (!empty($filterUrl) ? '&' : '').$fKey.'_result='.$autocompleteValue;
					}

					$escapedFieldValue = strip_tags(CString::quote($fieldValue));
					$quote = ($compareType == 'numeric' || $compareType == 'binary') ? '' : "'";
					$binary = ($compareType == 'binary') ? 'BINARY ' : '';
					$whereClauseMiddle = '';
                    
                    $fKeyParts = explode(',', $fKey);
					$fKeyPartsCount = count($fKeyParts);
                    foreach($fKeyParts as $key => $val){
						if(!empty($table)) $val = $table.'.'.$val;
                        if($fKeyPartsCount == 1){
                            $whereClauseMiddle .= !empty($whereClause) ? ' AND ' : '';
                        }else{
                            $whereClauseMiddle .= !empty($whereClauseMiddle) ? ' OR ' : '';                            
                        }
                        $whereClauseMiddle .= $binary.$val.' ';                        
                        switch($fieldOperator){
                            case 'like':
                                $whereClauseMiddle .= "like ".$quote.$escapedFieldValue.$quote; break;
                            case 'not like':
                                $whereClauseMiddle .= "not like ".$quote.$escapedFieldValue.$quote; break;
                            case '%like':
                                $whereClauseMiddle .= "like '%".$escapedFieldValue."'"; break;
                            case 'like%':
                                $whereClauseMiddle .= "like '".$escapedFieldValue."%'"; break;
                            case '%like%':
                                $whereClauseMiddle .= "like '%".$escapedFieldValue."%'"; break;
                            case '!=':
                            case '<>':	
                                $whereClauseMiddle .= "!= ".$quote.$escapedFieldValue.$quote; break;
                            case '>':	
                                $whereClauseMiddle .= "> ".$quote.$escapedFieldValue.$quote; break;
                            case '>=':	
                                $whereClauseMiddle .= ">= ".$quote.$escapedFieldValue.$quote; break;
                            case '<':	
                                $whereClauseMiddle .= "< ".$quote.$escapedFieldValue.$quote; break;
                            case '<=':	
                                $whereClauseMiddle .= "<= ".$quote.$escapedFieldValue.$quote; break;
                            case '=':
                            default:
                                $whereClauseMiddle .= "= ".$quote.$escapedFieldValue.$quote; break;
                        }
                    }
                    if($fKeyPartsCount > 1){
                        $whereClause .= (!empty($whereClause) ? ' AND' : '').' ('.$whereClauseMiddle.')';
                    }else{
                        $whereClause .= $whereClauseMiddle;
                    }                    
				} 
			}
			
			$output .= CHtml::openTag('div', array('class'=>'buttons-wrapper')).self::NL;
			if(A::app()->getRequest()->getQuery('but_filter')){
				$filterUrl .= (!empty($filterUrl) ? '&' : '').'but_filter=true';
				$output .= CHtml::button(A::t('core', 'Cancel'), array('name'=>'', 'class'=>'button white', 'onclick'=>'jQuery(location).attr(\'href\',\''.$baseUrl.$actionPath.'\');')).self::NL;
			}
			
			$output .= CHtml::submitButton(A::t('core', 'Filter'), array('name'=>'but_filter')).self::NL;
			$output .= CHtml::closeTag('div').self::NL;
			$output .= CHtml::closeForm().self::NL;
			$output .= CHtml::closeTag('div').self::NL;
			$filterUrl = CHtml::encode($filterUrl);
		}

		// Prepare pagination variables
		// ---------------------------------------
		$totalRecords = $totalPageRecords = 0;
		$currentPage = '';
        $objModel = call_user_func_array($model.'::model', array());    
		if(!$objModel){
            CDebug::addMessage('errors', 'missing-model', A::t('core', 'Unable to find class "{class}".', array('{class}'=>$model)), 'session');                        
            return '';
        }
		if($pagination){			
			$currentPage = A::app()->getRequest()->getQuery('page', 'integer', 1);
			$totalRecords = $objModel->count(array(
				'condition'=>$whereClause,
				'group'=>$groupBy
			));
			if($currentPage){				
				$records = $objModel->findAll(array(
					'condition'=>$whereClause,
					'limit'=>(($currentPage - 1) * $pageSize).', '.$pageSize,
					'order'=>$orderClause,
					'group'=>$groupBy
				));
				$totalPageRecords = is_array($records) ? count($records) : 0;
			}
			if(!$totalPageRecords || !$currentPage){
				if(A::app()->getRequest()->getQuery('but_filter')){
					$output .= CWidget::create('CMessage', array('warning', A::t('core', 'No records found or incorrect parameters passed')));
				}else{
					$output .= CWidget::create('CMessage', array('warning', A::t('core', 'No records found')));					
				}
			}
		}else{
			$records = $objModel->findAll(array(
				'condition'=>$whereClause,
				'order'=>$orderClause,
				'group'=>$groupBy
			));
			$totalPageRecords = is_array($records) ? count($records) : 0;
			if(!$totalPageRecords){
				$output .= CWidget::create('CMessage', array('error', A::t('core', 'No records found')));
			}
		}

		// Draw table
		// ---------------------------------------
        if($totalPageRecords > 0){			
			// Remove disabled fields
			foreach($fields as $key => $val){
				if((bool)self::keyAt('disabled', $val) === true) unset($fields[$key]);
			}
			
			// ----------------------------------------------
			// Draw TABLE wrapper
			// ----------------------------------------------
			if(!empty($gridWrapper['tag'])){
				$output .= CHtml::openTag($gridWrapper['tag'], array('class'=>(!empty($gridWrapper['class']) ? $gridWrapper['class'] : null))).self::NL;
			}			
			// ----------------------------------------------
			// Draw <THEAD> rows 
			// ----------------------------------------------
            $output .= CHtml::openTag('table', array('class'=>(!empty($gridTable['class']) ? $gridTable['class'] : null))).self::NL;
            $output .= CHtml::openTag('thead').self::NL;
            $output .= CHtml::openTag('tr', array('id'=>'tr'.$model.'_'.self::$_rowCount++)).self::NL;
				foreach($fields as $key => $val){
					
					// Check if we want to use another field as a "source field"
					$sourceField = self::keyAt('sourceField', $val, '');
					if(!empty($sourceField)){
						$key = $sourceField;
					}
					
					$type 			= self::keyAt('type', $val, '');
					$title 			= self::keyAt('title', $val, '');
					$headerClass 	= self::keyAt('headerClass', $val, '');
					$isSortable 	= (bool)self::keyAt('isSortable', $val, true);
					$sortField 		= self::keyAt('sortBy', $val, '');
					$widthAttr 		= self::keyAt('width', $val);
					$alignAttr 		= self::keyAt('align', $val);
					
					// Prepare style attributes
					$width = (!empty($widthAttr) && CValidator::isHtmlSize($widthAttr)) ? 'width:'.$widthAttr.';' : '';
					$align = (!empty($alignAttr) && CValidator::isAlignment($alignAttr)) ? 'text-align:'.$alignAttr.';' : '';					
					$style = $width.$align;
					
					if($sortingEnabled && $isSortable && ($type != 'template' || ($type == 'template' && !empty($sortField)))){
						$sortByField = !empty($sortField) ? $sortField : $key;
						$colSortDir = (($sortBy != $sortByField) ? 'asc' : (($sortDir == 'asc') ? 'desc' : 'asc'));
						$sortImg = ($sortBy == $sortByField) ? ' '. CHtml::tag('span', array('class'=>'sort-arrow'), (($colSortDir == 'asc') ? '&#9660;' : '&#9650;')) : '';
						if($sortBy == $sortByField) $sortUrl = 'sort_by='.$sortByField.'&sort_dir='.$sortDir;
						
						$separateSymbol = preg_match('/\?/', $actionPath) ? '&' : '?';						
						$linkUrl = $actionPath.$separateSymbol.'sort_by='.$sortByField.'&sort_dir='.$colSortDir;						
						$linkUrl .= !empty($currentPage) ? '&page='.$currentPage : '';						
						$linkUrl .= !empty($filterUrl) ? '&'.$filterUrl : '';						
						$linkUrl .= self::_customParams($customParameters, $linkType, '&');
						$title = CHtml::link($title.$sortImg, $linkUrl);
					}
					$output .= CHtml::tag('th', array('class'=>$headerClass, 'style'=>$style), $title).self::NL;
				}
				if($activeActions > 0){
					$output .= CHtml::tag('th', array('class'=>'actions'), A::t('core', 'Actions')).self::NL;
				}
                $output .= CHtml::closeTag('tr').self::NL;;
            $output .= CHtml::closeTag('thead').self::NL;
			
			// ----------------------------------------------
			// Draw <TBODY> rows 
			// ----------------------------------------------
			$aggregateRow = array();
			$output .= CHtml::openTag('tbody').self::NL;
			for($i = 0; $i < $totalPageRecords; $i++){
				$output .= CHtml::openTag('tr', array('id'=>'tr'.$model.'_'.self::$_rowCount++)).self::NL;
				$id = (isset($records[$i]['id'])) ? $records[$i]['id'] : '';
				
				// ----------------------------------------------
				// Display columns in each row
				// ----------------------------------------------
				foreach($fields as $key => $val){					

					// Check if we want to use another field as a "source field"
					$sourceField = self::keyAt('sourceField', $val, '');
					if(!empty($sourceField)){
						$key = $sourceField;
					}
					
					$align				= self::keyAt('align', $val, '');
					$style 				= (!empty($align) && CValidator::isAlignment($align)) ? 'text-align:'.$align.';' : '';
					$class 				= self::keyAt('class', $val, '');
					$type 				= self::keyAt('type', $val, '');
					$title 				= self::keyAt('title', $val, '');
					$format 			= self::keyAt('format', $val, '');
					$definedValues 		= self::keyAt('definedValues', $val, '');
                    $htmlOptions 		= (array)self::keyAt('htmlOptions', $val, array());
					$prependCode 		= self::keyAt('prependCode', $val, '');
					$appendCode 		= self::keyAt('appendCode', $val, '');
					$fieldValue 		= (isset($records[$i][$key])) ? $records[$i][$key] : ''; /* $key */
					$fieldValueOriginal = $fieldValue;
					$aggregateFunction 	= self::keyAt('aggregate.function', $val, '');
					$aggregateFunction 	= in_array(strtolower($aggregateFunction), array('sum', 'avg')) ? strtolower($aggregateFunction) : '';
					
					$fieldOutput = '';
					switch($type){
						case 'concat':
							$concatFields = self::keyAt('concatFields', $val, '');
							$concatSeparator = self::keyAt('concatSeparator', $val, '');
							$concatResult = '';
							if(is_array($concatFields)){
								foreach($concatFields as $cfKey){
									if(!empty($concatResult)) $concatResult .= $concatSeparator;
									$concatResult .= $records[$i][$cfKey];
								}
							}
							$fieldOutput .= $concatResult;
							break;
						
                        case 'decimal':
							$decimalPoints = (int)self::keyAt('decimalPoints', $val, '');
                            if($format === 'european'){
								// 1,222.33 => '1.222,33'
								$fieldValue = str_replace(',', '', $fieldValue);
								$fieldValue = number_format((float)$fieldValue, $decimalPoints, ',', '.');
                            }else{
								$fieldValue = number_format((float)$fieldValue, $decimalPoints);
                            }
                            $fieldOutput .= $fieldValue;
                            break;
						
                        case 'datetime':
							if(is_array($definedValues) && isset($definedValues[$fieldValue])){
								$fieldValue = $definedValues[$fieldValue];
                            }else if($format != ''){
                                $fieldValue = date($format, strtotime($fieldValue));
                            }
							$fieldOutput .= $fieldValue;
                            break;
						
                        case 'enum':
							$source = self::keyAt('source', $val, '');
							$outputValue = isset($source[$fieldValue]) ? $source[$fieldValue] : '';	
							if(is_array($definedValues) && isset($definedValues[$outputValue])){
								$fieldOutput .= $definedValues[$outputValue];
                            }else{
                                $fieldOutput .= $outputValue;
                            }
                            break;
						
						case 'index':
                            $fieldOutput .= ($i+1).'.';
                            break;
						
						case 'image':
							$imagePath = self::keyAt('imagePath', $val, '');
							$defaultImage = self::keyAt('defaultImage', $val, '');
							$alt = self::keyAt('alt', $val, '');
							$imageWidth = self::keyAt('imageWidth', $val, '');
							$imageHeight = self::keyAt('imageHeight', $val, '');
							
							$htmlOptions = array();
							if(!empty($imageWidth) && CValidator::isHtmlSize($imageWidth)) $htmlOptions['width'] = $imageWidth;
							if(!empty($imageHeight) && CValidator::isHtmlSize($imageHeight)) $htmlOptions['height'] = $imageHeight;							
							if((!$fieldValue || !file_exists($imagePath.$fieldValue)) && !empty($defaultImage)) $fieldValue = $defaultImage;
							$fieldOutput .= CHtml::image($imagePath.$fieldValue, $alt, $htmlOptions).self::NL;
							break;
						
						case 'link':
                            $linkUrl = self::keyAt('linkUrl', $val, '#');
							$linkText = self::keyAt('linkText', $val, '');
							
							// Replace for linkURL
                            if(preg_match_all('/{(.*?)}/i', $linkUrl, $matches)){
                                if(isset($matches[1]) && is_array($matches[1])){
                                    foreach($matches[1] as $kKey => $kVal){
                                        $kValValue = (isset($records[$i][$kVal])) ? $records[$i][$kVal] : ''; 
                                        $linkUrl = str_ireplace('{'.$kVal.'}', $kValValue, $linkUrl);
                                    }                                
                                }
                            }
							
							// Replace for linkText
                            $fieldValue = (isset($records[$i][$key])) ? $records[$i][$key] : ''; /* $key */							
							if(is_array($definedValues) && isset($definedValues[$fieldValue])){
								$linkText = $definedValues[$fieldValue];
                            }else{
								if(preg_match_all('/{(.*?)}/i', $linkText, $matches)){
									if(isset($matches[1]) && is_array($matches[1])){
										foreach($matches[1] as $kKey => $kVal){
											$kValValue = (isset($records[$i][$kVal])) ? $records[$i][$kVal] : ''; 
											$linkText = str_ireplace('{'.$kVal.'}', $kValValue, $linkText);
										}                                
									}
								}								
							}
							
							$fieldOutput .= CHtml::link($linkText, $linkUrl, $htmlOptions);
							break;
						
						case 'evaluation':
							
							$minValue = self::keyAt('minValue', $val, 1);
							$maxValue = self::keyAt('maxValue', $val, 5);
							
							$size = max(0, (min($maxValue, $fieldValue))) * 16;
							$tooltip = self::keyAt('tooltip', $val, '');
							$titleText = CHtml::encode($tooltip).': '.$fieldValue;
							
							$countsField = self::keyAt('counts.fieldName', $val, '');
							$countsTitle = self::keyAt('counts.title', $val, '');
							if(!empty($countsField)){
								$countsValue = !empty($countsField) && isset($records[$i][$countsField]) ? $records[$i][$countsField] : '';
								$titleText .= ' / '.$countsTitle.': '.$countsValue;
							}
							
							$fieldOutput .= CHtml::tag('label', array('class'=>'stars tooltip-link', 'title'=>$titleText), '<label style="width:'.$size.'px;"></label>');
							break;

						case 'template':
							$htmlCode = self::keyAt('html', $val, '');
							$templateFields = self::keyAt('fields', $val, array());
							$templateSources = self::keyAt('sources', $val, array());
							
							if(is_array($templateFields)){
								foreach($templateFields as $tfKey => $tfValue){									
									if(is_array($tfValue)){
										$default = isset($tfValue['default']) ? $tfValue['default'] : '';
										$prefix = isset($tfValue['prefix']) ? $tfValue['prefix'] : '';
										$postfix = isset($tfValue['postfix']) ? $tfValue['postfix'] : '';
										$source = isset($tfValue['source']) ? $tfValue['source'] : array();
										
										$templateFieldValue = isset($records[$i][$tfKey]) ? $records[$i][$tfKey] : '';
										// Check if there is an array with predefined values
										if(!empty($source) && is_array($source)){
											$templateFieldValue = isset($source[$templateFieldValue]) ? $source[$templateFieldValue] : '';
										}
										if(empty($templateFieldValue)) $templateFieldValue = $default;
										if(!empty($templateFieldValue)) $templateFieldValue = $prefix.$templateFieldValue.$postfix;
										
										$htmlCode = str_ireplace('{'.$tfKey.'}', $templateFieldValue, $htmlCode);
									}else{
										$templateFieldValue = isset($records[$i][$tfValue]) ? $records[$i][$tfValue] : '';
										// Check if there is an array with predefined values
										if(isset($templateSources[$tfValue])){
											$templateFieldValue = !empty($templateSources[$tfValue][$templateFieldValue]) ? $templateSources[$tfValue][$templateFieldValue] : '';
										}
										$htmlCode = str_ireplace('{'.$tfValue.'}', $templateFieldValue, $htmlCode);
									}
								}
							}
							
							$fieldOutput .= $htmlCode;							
							break;
						
						case 'label':
						default:
							// Call of closure function on item creating event
							$callbackClass = self::keyAt('callback.class', $val);
							$callbackFunction = self::keyAt('callback.function', $val);
							$callbackParams = self::keyAt('callback.params', $val, array());
                            if(!empty($callbackFunction)){
                                if(!empty($callbackClass) && class_exists($callbackClass)){
                                    // Calling a method class
                                    $callbackObject = new $callbackClass();
                                    if(method_exists($callbackObject, $callbackFunction) && is_callable(array($callbackObject, $callbackFunction))){
                                        // For PHP_VERSION >= 5.3.0 you may use
                                        // $fieldValue = $callbackObject::$callbackFunction($records[$i], $callbackParams);
                                        $fieldValue = call_user_func(array($callbackObject, $callbackFunction), $records[$i], $callbackParams);
                                    }
                                }else if(is_callable($callbackFunction)){
                                    // Calling a function
                                    // For PHP_VERSION >= 5.3.0 you may use
                                    // $fieldValue = $callbackFunction($records[$i], $callbackParams);
                                    $fieldValue = call_user_func($callbackFunction, $records[$i], $callbackParams);
                                }
							}

							$dataValue = self::keyAt('data', $val, '');
							if(!empty($dataValue)) $fieldValue = $dataValue;
							if(is_array($definedValues) && isset($definedValues[$fieldValue])){
								$fieldValue = $definedValues[$fieldValue];
                            }else if($format != '' && $format != 'american' && $format != 'european'){
                                $fieldValue = date($format, strtotime($fieldValue));
                            }else{
								$stripTags = (bool)self::keyAt('stripTags', $val, false);
								if($stripTags){
									$fieldValue = strip_tags($fieldValue);
								}
								$case = self::keyAt('case', $val, '');
								if($case == 'upper'){
									$fieldValue = strtoupper($fieldValue);
								}else if($case == 'lower'){
									$fieldValue = strtolower($fieldValue);
								}else if($case == 'camel'){
									$fieldValue = ucwords($fieldValue);
								}
								
								$maxLength = (int)self::keyAt('maxLength', $val, '');
								if(!empty($maxLength)){
									$fieldValue = CHtml::encode($fieldValue);
									$fieldValue = CHtml::tag('span', array('title'=>$fieldValue), CString::substr($fieldValue, $maxLength, '', true));
								}								
							}
							$fieldOutput .= $fieldValue;
							break;
					}
					
					// Calculate aggregate data
					if(!empty($aggregateFunction)){
						switch($aggregateFunction){
							case 'avg':
								if(!isset($aggregateRow[$key])){
									$aggregateRow[$key] = array('function'=>A::t('core', 'AVG'), 'result'=>$fieldValueOriginal, 'sum'=>$fieldValueOriginal, 'count'=>1);	
								}else{
									$aggregateRow[$key]['sum'] += $fieldValueOriginal;
									$aggregateRow[$key]['count']++;
									$aggregateRow[$key]['result'] = $aggregateRow[$key]['sum'] / $aggregateRow[$key]['count'];
								}								
								break;								
							case 'sum':
							default:
								if(!isset($aggregateRow[$key])){
									$aggregateRow[$key] = array('function'=>A::t('core', 'SUM'), 'result'=>$fieldValueOriginal);	
								}else{
									$aggregateRow[$key]['result'] += $fieldValueOriginal;
								}								
								break;
						}						
					}
					
					$output .= CHtml::openTag('td', array('class'=>$class, 'style'=>$style));                    
					$output .= $prependCode;
					$output .= $fieldOutput;
					$output .= $appendCode;
					$output .= CHtml::closeTag('td').self::NL;
				}
				
				if($activeActions > 0){
					$output .= CHtml::openTag('td', array('class'=>'actions')).self::NL;
					foreach($actions as $aKey => $aVal){
						$htmlOptions = (array)self::keyAt('htmlOptions', $aVal);						
						$htmlOptions['class'] = (isset($htmlOptions['class']) ? $htmlOptions['class'].' ' : '').'tooltip-link gridview-delete-link';						
						$htmlOptions['data-id'] = $id;
						if(isset($aVal['title'])){
							$htmlOptions['title'] = $aVal['title'];
						}
						if((bool)self::keyAt('onDeleteAlert', $aVal) === true){
							$htmlOptions['onclick'] = 'return onDeleteRecord(this);';
						}
						$imagePath = self::keyAt('imagePath', $aVal);
						$linkUrl = str_ireplace('{id}', $id, self::keyAt('link', $aVal, '#'));
						// Add additional parameters if allowed
						if($linkUrl != '#'){
							$separateSymbol = preg_match('/\?/', $linkUrl) ? '&' : '?';
							$linkUrl .= self::_additionalParams($passParameters, $linkType, $separateSymbol);
							$separateSymbol = preg_match('/\?/', $linkUrl) ? '&' : '?';
							$linkUrl .= self::_customParams($customParameters, $linkType, $separateSymbol);
						}						
						
						$linkLabel = (!empty($imagePath) ? '<img src="'.$imagePath.'" alt="'.$aKey.'" />' : $aKey);
						
						$output .= CHtml::link($linkLabel, $linkUrl, $htmlOptions).self::NL;
					}
					$output .= CHtml::closeTag('td').self::NL;
				}
				$output .= CHtml::closeTag('tr').self::NL;
			}
            $output .= CHtml::closeTag('tbody').self::NL;
                
			// ----------------------------------------------
			// Draw <TFOOT> aggregate row
			// ----------------------------------------------
			if(!empty($aggregateRow) && is_array($aggregateRow)){
				$output .= CHtml::openTag('tfoot').self::NL;
				$output .= CHtml::openTag('tr', array('id'=>'tr'.$model.'_'.self::$_rowCount++)).self::NL;
					foreach($fields as $key => $val){
						$title = '';
						$headerClass = self::keyAt('headerClass', $val, '');
						$prependCode = self::keyAt('prependCode', $val, '');
						$appendCode = self::keyAt('appendCode', $val, '');
						
						$format = self::keyAt('format', $val, '');
						$decimalPoints = (int)self::keyAt('decimalPoints', $val, 0);
						$widthAttr = self::keyAt('width', $val);
						$alignAttr = self::keyAt('align', $val);
						
						// Prepare style attributes
						$width = (!empty($widthAttr) && CValidator::isHtmlSize($widthAttr)) ? 'width:'.$widthAttr.';' : '';
						$align = (!empty($alignAttr) && CValidator::isAlignment($alignAttr)) ? 'text-align:'.$alignAttr.';' : '';
						$style = $width.$align;
						
						if(isset($aggregateRow[$key])){
							$aggregateValue = $aggregateRow[$key]['result'];
                            if($format === 'european'){
                                $aggregateValue = str_replace('.', '#', $aggregateValue);
                                $aggregateValue = str_replace(',', '.', $aggregateValue);
                                $aggregateValue = str_replace('#', ',', $aggregateValue);
                            }else{
								$aggregateValue = number_format((float)$aggregateValue, $decimalPoints);
                            }

							$title .= $aggregateRow[$key]['function'].': '.$prependCode.$aggregateValue.$appendCode;
						}
						
						$output .= CHtml::tag('td', array('class'=>$headerClass, 'style'=>$style), $title).self::NL;
					}
					if($activeActions > 0){
						$output .= CHtml::tag('td', array(), '').self::NL;
					}
					$output .= CHtml::closeTag('tr').self::NL;;
				$output .= CHtml::closeTag('tfoot').self::NL;
			}			
			
			$output .= CHtml::closeTag('table').self::NL;
			// ----------------------------------------------
			// Draw TABLE wrapper
			// ----------------------------------------------
			if(!empty($gridWrapper['tag'])){
				$output .= CHtml::closeTag($gridWrapper['tag']).self::NL;
			}			
			
			// Register script if onDeleteAlert is true
			if($onDeleteRecord){
				A::app()->getClientScript()->registerScript(
					'delete-record',
					'function onDeleteRecord(el){return confirm("ID: " + jQuery(el).data("id") + "\n'.A::t('core', 'Are you sure you want to delete this record?').'");}',
					2
				);				
			}

			// Draw pagination
			if($pagination){			
				$paginationUrl = $actionPath;
				$paginationUrl .= !empty($sortUrl) ? '?'.$sortUrl : '';
				$paginationUrl .= !empty($filterUrl) ? (empty($sortUrl) ? '?' : '&').$filterUrl : '';				
				$output .= CWidget::create('CPagination', array(
					'actionPath'   => $paginationUrl,
					'currentPage'  => $currentPage,
					'pageSize'     => $pageSize,
					'totalRecords' => $totalRecords,
					'linkType' => 0,
					'paginationType' => 'fullNumbers'
				));
			}
        }
		
		if($return) return $output;
        else echo $output;
	}

    /**
	 * Prepare additional parameters that will be passed
	 * @param bool $allow
	 * @param int $linkType
	 * @param char $separateSymbol
	 * @return string
     */
	private static function _additionalParams($allow = false, $linkType = 0, $separateSymbol = '&')
    {
		$output = '';
		
		if($allow){
			$page = A::app()->getRequest()->getQuery('page', 'integer', 1);
			$sortBy = A::app()->getRequest()->getQuery('sort_by', 'string');
			$sortDir = A::app()->getRequest()->getQuery('sort_dir', 'string');				
			
			if($sortBy){
				$output .= ($linkType == 1) ? '/sort_by/'.$sortBy : (!empty($output) ? '&' : $separateSymbol).'sort_by='.$sortBy;	
			}
			if($sortDir){
				$output .= ($linkType == 1) ? '/sort_dir/'.$sortDir : (!empty($output) ? '&' : $separateSymbol).'sort_dir='.$sortDir;	
			}
			if($page){
				$output .= ($linkType == 1) ? '/page/'.$page : (!empty($output) ? '&' : $separateSymbol).'page='.$page;	
			}
		}
		
		return $output;
	}
 
    /**
	 * Prepare custom parameters that will be passed
	 * @param array $params
	 * @param int $linkType
	 * @param string $separateSymbol
	 * @return string
     */
	private static function _customParams($params, $linkType = 0, $separateSymbol = '&')
    {
		$output = '';

		if(is_array($params)){
			foreach($params as $key => $val){
				$output .= ($linkType == 1) ? '/'.$key.'/'.$val : (!empty($output) ? '&' : $separateSymbol).$key.'='.$val;
			}
		}
		
		return $output;
	}
	
	/**
	 * Returns field attribute
	 * @param array $fields
	 * @param string $field
	 * @param string $attr
	 * @param array $allowedValues
	 * @return mixed
	 */
	private static function _getFieldParam($fields = array(), $field = '', $attr = '', $allowedValues = array())
	{
		$paramValue = '';
		
		if(!empty($fields) && is_array($fields)){
			if(!empty($allowedValues) && is_array($allowedValues)){
				$paramValue = isset($fields[$field][$attr]) && in_array($fields[$field][$attr], $allowedValues) ? $fields[$field][$attr] : '';				
			}else{
				$paramValue = isset($fields[$field][$attr]) ? $fields[$field][$attr] : '';
			}
		}

		return $paramValue;
	}
}
