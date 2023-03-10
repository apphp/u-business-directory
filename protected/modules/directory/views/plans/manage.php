<?php
    $this->_activeMenu = 'plans/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('directory', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('directory', 'Business Directory'), 'url'=>'modules/settings/code/directory'),
        array('label'=>A::t('directory', 'Advertise Plans Management'), 'url'=>'plans/manage'),
    );
?>

<h1><?php echo A::t('directory', 'Advertise Plans Management'); ?></h1>

<div class="bloc">
    <?php echo $tabs; ?>

    <div class="content">
    <?php
        echo $actionMessage;

        if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('directory', 'add')){
            echo '<a href="plans/add" class="add-new">'.A::t('directory', 'Add Advertise Plan').'</a>';
        }

        CWidget::create('CGridView', array(
            'model'=>'Plans',
            'actionPath'=>'plans/manage',
            'condition'=>'',
            //'defaultOrder'=>array('field_1'=>'DESC'),
            'passParameters'=>true,
            'pagination'=>array('enable'=>true, 'pageSize'=>20),
            'sorting'=>true,
            'filters'=>array(),
            'fields'=>array(
                'name' => array('title'=>'Name', 'type'=>'', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true),
                'categories_count' => array('title'=>A::t('directory', 'Categories Count'), 'type'=>'', 'align'=>'', 'width'=>'125px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true),
                'duration' => array('title'=>A::t('directory', 'Duration'), 'type'=>'', 'align'=>'', 'width'=>'90px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'definedValues'=>$durations),
                'price' => array('title'=>A::t('directory', 'Price'), 'type'=>'', 'align'=>'', 'width'=>'50px', 'class'=>'right', 'headerClass'=>'right', 'isSortable'=>true, 'prependCode'=>$currencySymbol),
                'is_default' => array('title'=>A::t('directory', 'Default'), 'type'=>'link', 'width'=>'70px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'linkUrl'=>'plans/setDefault/id/{id}', 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-gray">'.A::t('directory', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('directory', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('directory', 'Click to set default')))
            ),
            'actions'=>array(
                'edit'    => array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('directory', 'edit'),
                    'link'=>'plans/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('directory', 'Edit this record')
                ),
                'delete'=>array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('directory', 'delete'),
                    'link'=>'plans/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('directory', 'Delete this record'), 'onDeleteAlert'=>true
                )
            ),
            'return'=>false,
        ));

    ?>
    </div>
</div>
