<?php
    $this->_activeMenu = 'categories/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('directory', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('directory', 'Business Directory'), 'url'=>'modules/settings/code/directory'),
        array('label'=>A::t('directory', 'Categories Management'), 'url'=>'categories/manage'),
        array('label'=>$categoryName),
    );

    $disabled = (!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('directory', 'edit')) ? true : false;

    function getActions($record, $params)
    {
        $output = '';
        $disabled = $params['disabled'] ? $params['disabled'] : true;

        if($disabled){
            $output = '<a href="listings/edit/id/'.CHtml::encode($record['listing_id']).'" title="'.A::t('directory', 'Edit this record').'"><img src="templates/backend/images/edit.png" alt="edit" /></a>';
        }
        return $output;
    }
?>

<h1><?php echo A::t('directory', 'Categories Management'); ?></h1>

<div class="bloc">
    <?php echo $tabs; ?>
    <div class="sub-title">
        <a class="sub-tab active" href="categories/manage/parentId/<?php echo CHtml::encode($categoryParentId) ?>"><?php echo $categoryName; ?></a>
    </div>

    <div class="content">
    <?php
        echo $actionMessage;

        // Echo all fields to listings
        ListingsCategories::model()->setTypeRelations('listingsFull');

        CWidget::create('CGridView', array(
            'model'=>'ListingsCategories',
            'actionPath'=>'categories/manageListings/categoryId/'.$categoryId,
            'condition'=>'category_id = "'.(int)$categoryId.'"',
            'passParameters'=>true,
            'pagination'=>array('enable'=>true, 'pageSize'=>20),
            'sorting'=>true,
            'filters'=>array(
                'business_name' => array('title'=>A::t('directory', 'Name'), 'type'=>'textbox', 'operator'=>'%like%', 'width'=>'120px', 'maxLength'=>125),
                'advertise_plan_id' => array('title'=>A::t('directory', 'Advertise Plans'), 'type'=>'enum', 'operator'=>'=', 'width'=>'', 'source'=>$advertisePlanNames, 'emptyOption'=>true),
//                'customer_id'  => array('title'=>A::t('directory', 'Customer'), 'type'=>'enum', 'operator'=>'=', 'width'=>'65px'),
                'region_id'    => array('title'=>A::t('directory', 'Location'), 'type'=>'enum', 'operator'=>'=', 'source'=>$regionNames, 'width'=>'', 'emptyOption'=>true),
//                'subregion_id' => array('title'=>A::t('directory', 'Sub-Location'), 'type'=>'enum', 'class'=>'center', 'headerClass'=>'center', 'source'=>$allSubLocations, 'width'=>'65px'),
                'is_published' => array('title'=>A::t('directory', 'Published'), 'type'=>'enum', 'operator'=>'=', 'width'=>'', 'source'=>array('0'=>A::t('directory', 'No'), '1'=>A::t('directory', 'Yes')), 'emptyOption'=>true),
                'is_featured' => array('title'=>A::t('directory', 'Featured'), 'type'=>'enum', 'operator'=>'=', 'width'=>'', 'source'=>array('0'=>A::t('directory', 'No'), '1'=>A::t('directory', 'Yes')), 'emptyOption'=>true),
            ),
            'fields'=>array(
                'image_file_thumb'     => array('title'=>A::t('directory', 'Image'), 'type'=>'image', 'align'=>'', 'width'=>'40px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>false, 'imagePath'=>'images/modules/directory/listings/thumbs/', 'defaultImage'=>'no_image.png', 'imageHeight'=>'25px', 'alt'=>''),
                'business_name'        => array('title'=>A::t('directory', 'Name'), 'type'=>'label', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>''),
                'region_id'            => array('title'=>A::t('directory', 'Location'), 'type'=>'enum', 'align'=>'', 'width'=>'90px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'source'=>$regionNames),
                'advertise_plan_id'    => array('title'=>A::t('directory', 'Advertise Plan'), 'type'=>'label', 'align'=>'', 'width'=>'105px', 'class'=>'center', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>$advertisePlanNames),
                'access_level'         => array('title'=>A::t('directory', 'Access'), 'type'=>'enum', 'class'=>'center', 'headerClass'=>'center', 'width'=>'65px', 'source'=>array(0=>A::t('directory', 'Public'), 1=>A::t('directory', 'Registered'))),
                'finish_publishing'    => array('title'=>A::t('directory', 'Finish Published'), 'type'=>'datetime', 'align'=>'', 'width'=>'135px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>$dateTimeFormat),
                'sort_order'           => array('title'=>A::t('directory', 'Sort Order'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'100px'),
                'is_approved'          => array('title'=>A::t('directory', 'Approved'), 'type'=>'enum', 'width'=>'80px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'source'=>array('0'=>'<span class="label-gray">'.A::t('directory', 'Pending').'</span>', '1'=>'<span class="label-blue">'.A::t('directory', 'Paid').'</span>', '2'=>'<span class="label-green">'.A::t('directory', 'Approved').'</span>'), 'htmlOptions'=>array()),
                'is_published'         => array('title'=>A::t('directory', 'Published'), 'type'=>'enum', 'width'=>'80px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'source'=>array('0'=>'<span class="badge-red">'.A::t('directory', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('directory', 'Yes').'</span>'), 'htmlOptions'=>array()),
                'status_expired'       => array('title'=>A::t('directory', 'Expired'), 'type'=>'enum', 'align'=>'', 'width'=>'80px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'source'=>array('0'=>'--', '1'=>'<span class="badge-red" style="width:auto">'.A::t('directory', 'Expired')), 'fields'=>array()),
                'actions'              => array('title'=>A::t('core', 'Actions'), 'type'=>'label', 'class'=>'actions', 'headerClass'=>'center', 'width'=>'60px', 'isSortable'=>false, 'callback'=>array('function'=>'getActions', 'params'=>array('disabled'=>$disabled))),
            ),
            'actions'=>array(),
            'return'=>false,
        ));

    ?>
    </div>
</div>
