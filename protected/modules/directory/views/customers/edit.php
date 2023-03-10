<?php
    $this->_activeMenu = 'customers/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('directory', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('directory', 'Business Directory'), 'url'=>'modules/settings/code/directory'),
        array('label'=>A::t('directory', 'Accounts Management'), 'url'=>'customers/manage'),
        array('label'=>A::t('directory', 'Edit Customer')),
    );
	
	// register module javascript
    A::app()->getClientScript()->registerScriptFile('js/modules/directory/directory.js');
?>

<h1><?php echo A::t('directory', 'Accounts Management'); ?></h1>

<div class="bloc">
	<?php echo $tabs; ?>
	<div class="sub-title"><?php echo $subTabs.' '.A::t('directory', 'Edit Customer'); ?></div>
    <div class="content">
        <?php
        $fields = array();        
        
        $fields['separatorPersonal'] = array();
        $fields['separatorPersonal']['separatorInfo'] = array('legend'=>A::t('directory', 'Personal Information'));
        if($fieldFirstName !== 'no') $fields['separatorPersonal']['first_name'] = array('type'=>'textbox', 'title'=>A::t('directory', 'First Name'), 'default'=>'', 'validation'=>array('required'=>($fieldFirstName == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>32), 'htmlOptions'=>array('maxlength'=>'32'));
        if($fieldLastName !== 'no') $fields['separatorPersonal']['last_name'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Last Name'), 'default'=>'', 'validation'=>array('required'=>($fieldLastName == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>32), 'htmlOptions'=>array('maxlength'=>'32'));
        if($fieldBirthDate !== 'no') $fields['separatorPersonal']['birth_date'] = array('type'=>'datetime', 'title'=>A::t('directory', 'Birth Date'), 'defaultEditMode'=>'0000-00-00', 'validation'=>array('required'=>($fieldBirthDate == 'allow-required' ? true : false), 'type'=>'date', 'maxLength'=>10, 'minValue'=>'1900-00-00', 'maxValue'=>date('Y-m-d')), 'htmlOptions'=>array('maxlength'=>'10', 'style'=>'width:100px'), 'definedValues'=>array('0000-00-00'=>''));
        if($fieldWebsite !== 'no') $fields['separatorPersonal']['website'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Website'), 'default'=>'', 'validation'=>array('required'=>($fieldWebsite == 'allow-required' ? true : false), 'type'=>'url', 'maxLength'=>255, 'unique'=>false), 'htmlOptions'=>array('maxlength'=>'255', 'autocomplete'=>'off'));
        if($fieldCompany !== 'no') $fields['separatorPersonal']['company'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Company'), 'default'=>'', 'validation'=>array('required'=>($fieldCompany == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>128, 'unique'=>false), 'htmlOptions'=>array('maxlength'=>'128', 'autocomplete'=>'off'));
        if(count($fields['separatorPersonal']) == 1) unset($fields['separatorPersonal']);
        
        $fields['separatorContact'] = array();
        $fields['separatorContact']['separatorInfo'] = array('legend'=>A::t('directory', 'Contact Information'));
        if($fieldPhone !== 'no') $fields['separatorContact']['phone'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Phone'), 'default'=>'', 'validation'=>array('required'=>($fieldPhone == 'allow-required' ? true : false), 'type'=>'phoneString', 'maxLength'=>32, 'unique'=>false), 'htmlOptions'=>array('maxlength'=>'32', 'autocomplete'=>'off'));
        if($fieldFax !== 'no') $fields['separatorContact']['fax'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Fax'), 'default'=>'', 'validation'=>array('required'=>($fieldFax == 'allow-required' ? true : false), 'type'=>'phoneString', 'maxLength'=>32, 'unique'=>false), 'htmlOptions'=>array('maxlength'=>'32', 'autocomplete'=>'off'));
        if(count($fields['separatorContact']) == 1) unset($fields['separatorContact']);
        
        $fields['separatorAddress'] = array();
        $fields['separatorAddress']['separatorInfo'] = array('legend'=>A::t('directory', 'Address Information'));
        if($fieldAddress !== 'no') $fields['separatorAddress']['address'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Address'), 'default'=>'', 'validation'=>array('required'=>($fieldAddress == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>64, 'unique'=>false), 'htmlOptions'=>array('maxlength'=>'64', 'autocomplete'=>'off'));
        if($fieldAddress2 !== 'no') $fields['separatorAddress']['address_2'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Address (line 2)'), 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>64, 'unique'=>false), 'htmlOptions'=>array('maxlength'=>'64', 'autocomplete'=>'off'));
        if($fieldCity !== 'no') $fields['separatorAddress']['city'] = array('type'=>'textbox', 'title'=>A::t('directory', 'City'), 'default'=>'', 'validation'=>array('required'=>($fieldCity == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>64, 'unique'=>false), 'htmlOptions'=>array('maxlength'=>'64', 'autocomplete'=>'off'));
        if($fieldZipCode !== 'no') $fields['separatorAddress']['zip_code'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Zip Code'), 'default'=>'', 'validation'=>array('required'=>($fieldZipCode == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>32, 'unique'=>false), 'htmlOptions'=>array('maxlength'=>'32', 'autocomplete'=>'off', 'class'=>'small'));
        if($fieldCountry !== 'no'){
            $onchange = ($fieldState !== 'no') ? "customers_ChangeCountry('frmCustomerEdit',this.value,'')" : '';
            $fields['separatorAddress']['country_code'] = array('type'=>'select', 'title'=>A::t('directory', 'Country'), 'tooltip'=>'', 'default'=>$defaultCountryCode, 'validation'=>array('required'=>($fieldCountry == 'allow-required' ? true : false), 'type'=>'set', 'source'=>array_keys($countries)), 'data'=>$countries, 'htmlOptions'=>array('onchange'=>$onchange));
        }
        if($fieldState !== 'no') $fields['separatorAddress']['state'] = array('type'=>'textbox', 'title'=>A::t('directory', 'State/Province'), 'default'=>'', 'validation'=>array('required'=>($fieldState == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>64, 'unique'=>false), 'htmlOptions'=>array('maxlength'=>'64', 'autocomplete'=>'off'));
        if(count($fields['separatorAddress']) == 1) unset($fields['separatorAddress']);
        
        $fields['separatorAccount'] = array();
        $fields['separatorAccount']['separatorInfo'] = array('legend'=>A::t('directory', 'Account Information'));
        if($fieldEmail !== 'no') $fields['separatorAccount']['email'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Email'), 'default'=>'', 'validation'=>array('required'=>($fieldEmail == 'allow-required' ? true : false), 'type'=>'email', 'maxLength'=>100, 'unique'=>true), 'htmlOptions'=>array('maxlength'=>'100', 'autocomplete'=>'off', 'class'=>'middle'));
        if(count($groups)) $fields['separatorAccount']['group_id'] = array('type'=>'select', 'title'=>A::t('directory', 'Group'), 'tooltip'=>'', 'default'=>$defaultGroupId, 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($groups)), 'data'=>$groups, 'htmlOptions'=>array());
        $fields['separatorAccount']['username']	= array('type'=>'label', 'title'=>A::t('directory', 'Username'), 'default'=>'', 'tooltip'=>'', 'definedValues'=>array(), 'htmlOptions'=>array(), 'format'=>'', 'stripTags'=>false);
        if($changePassword) $fields['separatorAccount']['password']	= array('type'=>'password', 'title'=>A::t('directory', 'Password'), 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'password', 'minLength'=>6, 'maxlength'=>20), 'encryption'=>array('enabled'=>CConfig::get('password.encryption'), 'encryptAlgorithm'=>CConfig::get('password.encryptAlgorithm'), 'encryptSalt'=>$salt), 'htmlOptions'=>array('maxlength'=>'20', 'placeholder'=>'&#9679;&#9679;&#9679;&#9679;&#9679;'));
        $fields['separatorAccount']['language_code'] = array('type'=>'select', 'title'=>A::t('directory', 'Preferred Language'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($langList)), 'data'=>$langList);
        $fields['separatorAccount']['is_active'] = array('type'=>'checkbox', 'title'=>A::t('directory', 'Active'), 'default'=>'1', 'validation'=>array('type'=>'set', 'source'=>array(0,1)));
        if($removalType == 'logical' || $removalType == 'physical_and_logical') $fields['separatorAccount']['is_removed'] = array('type'=>'checkbox', 'title'=>A::t('directory', 'Removed'), 'default'=>'0', 'validation'=>array('type'=>'set', 'source'=>array(0,1)));
        
        $fields['separatorOther'] = array();
        $fields['separatorOther']['separatorInfo'] = array('legend'=>A::t('directory', 'Other'));
        $fields['separatorOther']['created_at'] = array('type'=>'label', 'title'=>A::t('directory', 'Created at'), 'default'=>'', 'tooltip'=>'', 'definedValues'=>array('0000-00-00 00:00:00'=>A::t('directory', 'Never')), 'htmlOptions'=>array(), 'format'=>$dateTimeFormat, 'stripTags'=>false);
        $fields['separatorOther']['created_ip'] = array('type'=>'label', 'title'=>A::t('directory', 'Created from IP'), 'default'=>'', 'tooltip'=>'', 'definedValues'=>array('000.000.000.000'=>A::t('directory', 'Unknown')), 'htmlOptions'=>array(), 'format'=>'', 'stripTags'=>false);        
        $fields['separatorOther']['last_visited_at'] = array('type'=>'label', 'title'=>A::t('directory', 'Last visit at'), 'default'=>'', 'tooltip'=>'', 'definedValues'=>array('0000-00-00 00:00:00'=>A::t('directory', 'Never')), 'htmlOptions'=>array(), 'format'=>$dateTimeFormat, 'stripTags'=>false);
        $fields['separatorOther']['last_visited_ip'] = array('type'=>'label', 'title'=>A::t('directory', 'Last visit from IP'), 'default'=>'', 'tooltip'=>'', 'definedValues'=>array('000.000.000.000'=>A::t('directory', 'Unknown')), 'htmlOptions'=>array(), 'format'=>'', 'stripTags'=>false);        
        if($fieldNotifications !== 'no') $fields['separatorOther']['notifications'] = array('type'=>'checkbox', 'title'=>A::t('directory', 'Notifications'), 'default'=>'0', 'validation'=>array('type'=>'set', 'source'=>array(0,1)));
        if($fieldNotifications !== 'no') $fields['separatorOther']['notifications_changed_at'] = array('type'=>'label', 'title'=>A::t('directory', 'Notifications changed at'), 'default'=>'', 'tooltip'=>'', 'definedValues'=>array('0000-00-00 00:00:00'=>A::t('directory', 'Never')), 'htmlOptions'=>array(), 'format'=>$dateTimeFormat, 'stripTags'=>false);
        $fields['separatorOther']['comments'] = array('type'=>'textarea', 'title'=>A::t('directory', 'Comments'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>2048), 'htmlOptions'=>array('maxLength'=>'2048'));
        
        echo CWidget::create('CDataForm', array(
            'model'=>'Customers',
            'primaryKey'=>$id,        
            'operationType'=>'edit',    
            'action'=>'customers/edit/id/'.$id,
            'successUrl'=>'customers/manage',
            'cancelUrl'=>'customers/manage',
            'method'=>'post',
            'htmlOptions'=>array(
                'id'=>'frmCustomerEdit',                
                'name'=>'frmCustomerEdit',                
                'autoGenerateId'=>true
            ),
            'requiredFieldsAlert'=>true,
            'fields'=>$fields,
            'buttons'=>array(
                'submitUpdateClose'=>array('type'=>'submit', 'value'=>A::t('directory', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose')),
                'submitUpdate'=>array('type'=>'submit', 'value'=>A::t('directory', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
                'cancel'=>array('type'=>'button', 'value'=>A::t('directory', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
            'buttonsPosition'=>'both',
            'alerts'=>array('type'=>'flash'),
            'messagesSource'=>'core',
            'return'=>true,
        ));       
    ?>
    </div>
</div>
<?php
    if($fieldCountry !== 'no' && $fieldState !== 'no'){
        A::app()->getClientScript()->registerScript(
            'customersChangeCountry',
            '$(document).ready(function(){
                customers_ChangeCountry(
                    "frmCustomerEdit","'.$countryCode.'","'.$stateCode.'"
                );
            });',
            1
        );
    }    
?>