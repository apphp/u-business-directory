<?php
    $this->_activeMenu = 'customers/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('directory', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('directory', 'Business Directory'), 'url'=>'modules/settings/code/directory'),
        array('label'=>A::t('directory', 'Customers Management'), 'url'=>'customers/manage'),
        array('label'=>A::t('directory', 'Groups')),
    );    
?>

<h1><?php echo A::t('directory', 'Customers Management'); ?></h1>

<div class="bloc">
    <?php echo $tabs; ?>
    <div class="sub-title">
    <?php echo $subTabs; ?>
    </div>
    <div class="content">
    <?php 
        echo $actionMessage;  
 
        if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('directory', 'add')){
            echo '<a href="customerGroups/add" class="add-new">'.A::t('directory', 'Add Group').'</a>';
        }

        CWidget::create('CGridView', array(
            'model'=>'CustomerGroups',
            'actionPath'=>'customerGroups/manage',
            'condition'=>'',
            //'defaultOrder'=>array('field_1'=>'DESC'),
            'passParameters'=>true,
            'pagination'=>array('enable'=>true, 'pageSize'=>20),
            'sorting'=>true,
            'fields'=>array(               
               'name'        => array('title'=>A::t('directory', 'Group Name'), 'type'=>'label', 'align'=>'', 'width'=>'210px', 'class'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>''),
               'description' => array('title'=>A::t('directory', 'Description'), 'type'=>'label', 'align'=>'', 'width'=>'', 'class'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>''),                 
               'is_default'  => array('title'=>A::t('directory', 'Default'), 'type'=>'link', 'width'=>'90px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'linkUrl'=>'customerGroups/setDefault/id/{id}', 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-gray">'.A::t('directory', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('directory', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('directory', 'Click to set default'))),
            ),
            'actions'=>array(
                'edit'    => array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('directory', 'edit'),
                    'link'=>'customerGroups/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('directory', 'Edit this record')
                ),
                'delete'=>array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('directory', 'delete'),
                    'link'=>'customerGroups/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('directory', 'Delete this record'), 'onDeleteAlert'=>true
                )
            ),
            'return'=>false,
        ));

    ?>        
    </div>
</div>
