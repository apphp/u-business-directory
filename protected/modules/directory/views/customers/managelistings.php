<?php
    $this->_activeMenu = 'categories/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('directory', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('directory', 'Business Directory'), 'url'=>'modules/settings/code/directory'),
        array('label'=>A::t('directory', 'Accounts Management'), 'url'=>'customer/manage'),
        array('label'=>$customerName),
    );
?>

<h1><?php echo A::t('directory', 'Accounts Management'); ?></h1>

<div class="bloc">
    <?php echo $tabs; ?>
    <div class="sub-title">
        <a class="sub-tab previous" href="customers/manage"><?php echo A::t('directory', 'Customers')?></a>»
        <a class="sub-tab active" href="customers/manageListings/customerId/<?php echo CHtml::encode($customerId) ?>"><?php echo $customerName; ?></a>
        <a class="sub-tab previous" href="customerGroups/manage"><?php echo A::t('directory', 'Groups')?></a>
    </div>

    <div class="content">
    <?php
        echo $actionMessage;
        ListingsCategories::model()->setTypeRelations('listingsFull');
        CWidget::create('CGridView', array(
            'model'=>'Listings',
            'actionPath'=>'customers/manageListings/customerId/'.$customerId,
            'condition'=>'customer_id = "'.(int)$customerId.'"',
            'passParameters'=>true,
            'pagination'=>array('enable'=>true, 'pageSize'=>20),
            'sorting'=>true,

            'fields'=>array(
                'image_file_thumb'     => array('title'=>'Image', 'type'=>'image', 'align'=>'', 'width'=>'40px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>false, 'imagePath'=>'images/modules/directory/listings/thumbs/', 'defaultImage'=>'no_image.png', 'imageHeight'=>'25px', 'alt'=>''),
                'business_name'        => array('title'=>'Name', 'type'=>'label', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>''),
                'advertise_plan_id'    => array('title'=>'Advertise Plan', 'type'=>'label', 'align'=>'', 'width'=>'105px', 'class'=>'center', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>$advertisePlanNames),
                'finish_publishing'    => array('title'=>A::t('directory', 'Finish Published'), 'type'=>'datetime', 'align'=>'', 'width'=>'135px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>$dateTimeFormat),
                'access_level'         => array('title'=>A::t('directory', 'Access'), 'type'=>'enum', 'class'=>'center', 'headerClass'=>'center', 'source'=>array('0'=>A::t('directory', 'Public'), '1'=>A::t('directory', 'Registered')), 'width'=>'65px'),
                'sort_order'           => array('title'=>A::t('directory', 'Sort Order'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'100px'),
                'is_approved'          => array('title'=>A::t('directory', 'Approved'), 'type'=>'enum', 'width'=>'80px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'source'=>array('0'=>'<span class="label-gray">'.A::t('directory', 'Pending').'</span>', '1'=>'<span class="label-blue">'.A::t('directory', 'Paid').'</span>', '2'=>'<span class="label-green">'.A::t('directory', 'Approved').'</span>'), 'htmlOptions'=>array()),
                'is_published'         => array('title'=>A::t('directory', 'Published'), 'type'=>'enum', 'width'=>'80px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'source'=>array('0'=>'<span class="badge-red">'.A::t('directory', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('directory', 'Yes').'</span>'), 'htmlOptions'=>array()),
            ),
            'actions'=>array(
                'edit'    => array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('directory', 'edit'),
                    'link'=>'listings/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('directory', 'Edit this record')
                ),
            ),
            'return'=>false,
        ));

    ?>
    </div>
</div>
