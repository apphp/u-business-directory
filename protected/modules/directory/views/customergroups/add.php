<?php
    $this->_activeMenu = 'customers/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('directory', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('directory', 'Business Directory'), 'url'=>'modules/settings/code/directory'),
        array('label'=>A::t('directory', 'Customers Management'), 'url'=>'customers/manage'),
        array('label'=>A::t('directory', 'Add Group')),
    );
?>

<h1><?php echo A::t('directory', 'Customers Management'); ?></h1>

<div class="bloc">
	<?php echo $tabs; ?>
	<div class="sub-title"><?php echo $subTabs.' '.A::t('directory', 'Add Group'); ?></div>
    <div class="content">
        <?php
			echo $actionMessage;
			CWidget::create('CDataForm', array(
            'model'=>'CustomerGroups',
            'operationType'=>'add',
            'action'=>'customerGroups/add/',
            'successUrl'=>'customerGroups/manage',
            'cancelUrl'=>'customerGroups/manage',
            'method'=>'post',
            'htmlOptions'=>array(
                'id'=>'frmCustomerGroupsAdd',
                'name'=>'frmCustomerGroupsAdd',                
                'autoGenerateId'=>true
            ),
            'requiredFieldsAlert'=>true,
            'fields'=>array(
                'name'        => array('type'=>'textbox', 'title'=>A::t('directory', 'Group Name'), 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>50), 'htmlOptions'=>array('maxlength'=>'50')),
                'description' => array('type'=>'textarea', 'title'=>A::t('directory', 'Description'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>255), 'htmlOptions'=>array('maxLength'=>'255')),
				'is_default'  => array('type'=>'checkbox', 'title'=>A::t('directory', 'Default'), 'validation'=>array('type'=>'set', 'source'=>array(0,1))),
            ),
            'buttons'=>array(
                'submit' => array('type'=>'submit', 'value'=>A::t('directory', 'Create'), 'htmlOptions'=>array('name'=>'')),
                'cancel' => array('type'=>'button', 'value'=>A::t('directory', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
            'buttonsPosition'=>'bottom',
            'alerts'=>array('type'=>'flash'),
            'messagesSource'=>'core',
            'return'=>false,
        ));
	    ?>
  
    </div>
</div>