<?php
    $this->_activeMenu = 'regions/manage';
    $breadCrumbs = array(
        array('label'=>A::t('directory', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('directory', 'Business Directory'), 'url'=>'modules/settings/code/directory'),
        array('label'=>A::t('directory', 'Locations Management'), 'url'=>'regions/manage')
	);
	if($parentId){
		$breadCrumbs[] = array('label'=>$parentName, 'url'=>'regions/manage/parentId/'.$parentId);
		$breadCrumbs[] = array('label'=>A::t('directory', 'Edit Location'));
	}else{
		$breadCrumbs[] = array('label'=>A::t('directory', 'Edit Sub-Location'));
	}
	$this->_breadCrumbs = $breadCrumbs;
?>

<h1><?php echo A::t('directory', 'Locations Management'); ?></h1>

<div class="bloc">
	<?php
		echo $tabs;	
		if($parentId){
			echo '<div class="sub-title">';
			echo '<a class="sub-tab active" href="regions/manage/parentId/'.(int)$parentId.'">'.$parentName.'</a>'.' '.A::t('directory', 'Edit Sub-Location');
			echo '</div>';
		}else{
			echo '<div class="sub-title">'.A::t('directory', 'Edit Location').'</div>';
		}
	?>
    <div class="content">
        <?php
			echo $actionMessage;
			
			$fields = array();
			if($parentId){
				$fields['parent_id'] = array('type'=>'select', 'title' => A::t('directory', 'Location'), 'tooltip'=>'', 'default'=>$parentId, 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array($parentId)), 'data'=>array($parentId=>$parentName), 'htmlOptions'=>array('readonly'=>'readonly'));
			}
			$fields['is_active'] = array('type'=>'checkbox', 'title'=>A::t('directory', 'Active'), 'default'=>true, 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array());
			$fields['sort_order'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Sort Order'), 'default'=>0, 'tooltip'=>'', 'validation'=>array('required'=>true, 'maxLength'=>'4', 'type'=>'numeric'), 'htmlOptions'=>array('maxLength'=>'4', 'class'=>'small'));
				
			CWidget::create('CDataForm', array(
				'model'			=>'Regions',
				'primaryKey'	=>$id,
				'operationType'	=>'edit',
				'action'		=>'regions/edit/id/'.$id,
				'successUrl'	=>'regions/manage/parentId/'.$parentId,
				'cancelUrl'		=>'regions/manage/parentId/'.$parentId,
				'passParameters'=>true,
				'method'		=>'post',
				'htmlOptions'=>array(
					'name'	 =>'frmRegionEdit',
					'id'	 =>'frmRegionEdit',
					'enctype'=>'multipart/form-data',
					'autoGenerateId'=>true
				),
				'requiredFieldsAlert'=>true,
				'fields'			 =>$fields,
				'translationInfo'	 =>array('relation'=>array('id', 'region_id'), 'languages'=>Languages::model()->findAll('is_active = 1')),
				'translationFields'	 =>array(
					'name' => array('type'=>'textbox', 'title'=>A::t('directory', 'Name'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>70), 'htmlOptions'=>array('maxLength'=>70)),
				),
				'buttons' => array(
					'submitUpdateClose' =>array('type'=>'submit', 'value'=>A::t('directory', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose')),
					'submitUpdate' 		=>array('type'=>'submit', 'value'=>A::t('directory', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
					'cancel' 			=>array('type'=>'button', 'value'=>A::t('directory', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
				),
				'messagesSource'=>'core',
			    'alerts'		=>array('type'=>'flash'),
				'return'		=>false,
			));
	    ?>
    </div>
</div>