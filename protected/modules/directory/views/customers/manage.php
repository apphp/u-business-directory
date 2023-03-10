<?php
    $this->_activeMenu = 'customers/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('directory', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('directory', 'Business Directory'), 'url'=>'modules/settings/code/directory'),
        array('label'=>A::t('directory', 'Accounts Management'), 'url'=>'customers/manage'),
        array('label'=>A::t('directory', 'Customers')),
    );
?>

<h1><?php echo A::t('directory', 'Accounts Management'); ?></h1>

<div class="bloc">
    <?php echo $tabs; ?>
    <div class="sub-title">
    <?php echo $subTabs; ?>
    </div>

    <div class="content">
    <?php
        echo $actionMessage;

        if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('directory', 'add')){
            echo '<a href="customers/add" class="add-new">'.A::t('directory', 'Add Customer').'</a>';
        }


        $fields = array();
        $filterFields = array();
        $condition = '';

        if(ModulesSettings::model()->param('directory', 'customer_field_last_name') !== 'no'){
            $fields['last_name'] = array('title'=>A::t('directory', 'Last Name'), 'type'=>'label', 'align'=>'', 'width'=>'140px', 'class'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>'');
            $filterFields['last_name'] = array('title'=>A::t('directory', 'Last Name'), 'type'=>'textbox', 'operator'=>'like%', 'width'=>'100px', 'maxLength'=>'32');
        }
        if(ModulesSettings::model()->param('directory', 'customer_field_first_name') !== 'no'){
            $fields['first_name'] = array('title'=>A::t('directory', 'First Name'), 'type'=>'label', 'align'=>'', 'width'=>'90px', 'class'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>'');
        }

        $fields['username'] = array('title'=>A::t('directory', 'Username'), 'type'=>'label', 'align'=>'', 'width'=>'110px', 'class'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>'');
        $filterFields['username'] = array('title'=>A::t('directory', 'Username'), 'type'=>'textbox', 'operator'=>'like%', 'default'=>'', 'width'=>'100px', 'maxLength'=>'25');

        if(ModulesSettings::model()->param('directory', 'customer_field_email') !== 'no'){
            $fields['email'] = array('title'=>A::t('directory', 'Email'), 'type'=>'label', 'align'=>'', 'width'=>'', 'class'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>'');
            $filterFields['email'] = array('title'=>A::t('directory', 'Email'), 'type'=>'textbox', 'operator'=>'like%', 'width'=>'100px', 'maxLength'=>'100');
        }

        $fields['group_name'] = array('title'=>A::t('directory', 'Group'), 'type'=>'label', 'align'=>'', 'width'=>'90px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'definedValues'=>array(''=>'--'), 'format'=>'');
        if(count($groups)) $filterFields['group_id'] = array('title'=>A::t('directory', 'Group'), 'type'=>'enum', 'operator'=>'=', 'width'=>'', 'source'=>$groups);

        $fields['locations_link'] = array('title'=>A::t('directory', 'Listings'), 'type'=>'link', 'class'=>'center', 'headerClass'=>'center', 'width'=>'60px', 'isSortable'=>false, 'linkUrl'=>'customers/manageListings/customerId/{id}', 'linkText'=>A::t('directory', 'Listings'), 'htmlOptions'=>array('class'=>'subgrid-link'), 'prependCode'=>'[ ', 'appendCode'=>' ]');
        $fields['id'] = array('title' => '', 'type'=>'enum', 'table'=>'', 'operator'=>'=', 'default'=>'', 'width'=>'30px', 'source'=>$listingsCounts, 'definedValues'=>array(''=>'<span class="label-zerogray">0</span>'), 'isSortable'=>true, 'class' => 'left', 'prependCode'=>'<span class="label-lightgray">', 'appendCode'=>'</span>');

        $fields['is_active'] = array('title'=>A::t('directory', 'Active'), 'type'=>'link', 'width'=>'60px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'linkUrl'=>'customers/activeStatus/id/{id}', 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-red">'.A::t('directory', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('directory', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('directory', 'Click to change status')));
        $filterFields['is_active'] = array('title'=>A::t('directory', 'Active'), 'type'=>'enum', 'operator'=>'=', 'width'=>'', 'source'=>array(''=>'', '0'=>A::t('directory', 'No'), '1'=>A::t('directory', 'Yes')));


        CWidget::create('CGridView', array(
            'model'=>'Customers',
            'actionPath'=>'customers/manage',
            'condition'=>'',
            'passParameters'=>true,
            'pagination'=>array('enable'=>true, 'pageSize'=>20),
            'sorting'=>true,
            'filters'=>$filterFields,
            'fields'=>$fields,
            'actions'=>array(
                'edit'    => array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('directory', 'edit'),
                    'link'=>'customers/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('directory', 'Edit this record')
                ),
                'delete'=>array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('directory', 'delete'),
                    'link'=>'customers/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('directory', 'Delete this record'), 'onDeleteAlert'=>true
                )
            ),
            'return'=>false,
        ));

    ?>
    </div>
</div>
