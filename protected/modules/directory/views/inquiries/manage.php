<?php
    $this->_activeMenu = 'inquiries/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('directory', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('directory', 'Business Directory'), 'url'=>'modules/settings/code/directory'),
        array('label'=>A::t('directory', 'Inquiries Management'), 'url'=>'inquiries/manage'),
    );
?>

<h1><?php echo A::t('directory', 'Inquiries Management'); ?></h1>

<div class="bloc">
    <?php echo $tabs; ?>

    <div class="content">
    <?php
        echo $actionMessage;

        CWidget::create('CGridView', array(
            'model'=>'Inquiries',
            'actionPath'=>'inquiries/manage',
            'condition'=>'',
            //'defaultOrder'=>array('field_1'=>'DESC'),
            'passParameters'=>true,
            'pagination'=>array('enable'=>true, 'pageSize'=>20),
            'sorting'=>true,
            'filters'=>array(
                'name' => array('title'=>A::t('directory', 'Name'), 'type'=>'textbox', 'operator'=>'%like%', 'width'=>'180px', 'maxLength'=>'50'),
                'category_id' => array('title'=>A::t('directory', 'Category'), 'type'=>'enum', 'operator'=>'=', 'width'=>'140px', 'emptyOption'=>true, 'source'=>$filterCategories),
                'region_id' => array('title'=>A::t('directory', 'Location'), 'type'=>'enum', 'operator'=>'=', 'width'=>'120px', 'emptyOption'=>true, 'source'=>$filterLocations),
            ),
            'fields'=>array(
                'name' => array('title'=>A::t('directory', 'Name'), 'type'=>'label', 'align'=>'', 'width'=>'140px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array()),
                'email' => array('title'=>A::t('directory', 'Email'), 'type'=>'link', 'align'=>'', 'width'=>'150px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'linkUrl'=>'mailto:{email}', 'linkText'=>'{email}'),
                'listing_name' => array('title'=>A::t('directory', 'Listing'), 'type'=>'link', 'align'=>'', 'width'=>'100px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'linkUrl'=>'listings/view/id/{listing_id}', 'linkText'=>'{listing_name}', 'htmlOptions'=>array('target'=>'_blank')),
                'category_id' => array('title'=>A::t('directory', 'Category'), 'type'=>'enum', 'align'=>'', 'width'=>'100px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'source'=>$categories),
                'region_id' => array('title'=>A::t('directory', 'Location'), 'type'=>'enum', 'align'=>'', 'width'=>'110px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'source'=>$locations),
                'subregion_id' => array('title'=>A::t('directory', 'Sub-Location'), 'type'=>'enum', 'align'=>'', 'width'=>'110px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'source'=>$locations),
                'date_created' => array('title'=>A::t('directory', 'Date Created'), 'type'=>'datetime', 'align'=>'', 'width'=>'125px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'format'=>$dateTimeFormat),
                'inquiry_type' => array('title'=>A::t('directory', 'Type'), 'type'=>'enum', 'class'=>'center', 'width'=>'80px', 'headerClass'=>'center', 'isSortable'=>true, 'source'=>array(0=>A::t('directory', 'Standard'), 1=>A::t('directory','Direct'))),
//                'is_active' => array('title'=>A::t('directory', 'Active'), 'type'=>'label', 'width'=>'60px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'definedValues'=>array('0'=>'<span class="badge-red" style="width:auto">'.A::t('directory', 'No').'</span>', '1'=>'<span class="badge-green" style="width:auto">'.A::t('directory', 'Yes').'</span>'), 'htmlOptions'=>array()),
                'is_active' => array('title'=>A::t('directory', 'Active'), 'type'=>'link', 'width'=>'60px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'linkUrl'=>'inquiries/changeStatus/id/{id}', 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-gray">'.A::t('directory', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('directory', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('directory', 'Click to change status')))
            ),
            'actions'=>array(
                'preview'       => array(
                    'disabled'      => !Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('directory', 'edit'),
                    'link'          => 'inquiries/preview/id/{id}', 'imagePath'=>'templates/backend/images/details.png', 'title'=>A::t('directory', 'Preview this record')
                ),
                'delete'=>array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('directory', 'delete'),
                    'link'=>'inquiries/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('directory', 'Delete this record'), 'onDeleteAlert'=>true
                )
            ),
            'return'=>false,
        ));

    ?>
    </div>
</div>
