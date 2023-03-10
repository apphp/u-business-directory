<?php
    $this->_activeMenu = 'categories/manage';
    $breadCrumbs = array(
        array('label'=>A::t('directory', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('directory', 'Business Directory'), 'url'=>'modules/settings/code/directory'),
        array('label'=>A::t('directory', 'Locations Management'), 'url'=>'customer/manage'),
    );
    
    if(!empty($regionId)){
        $breadCrumbs[] = array('label'=>$regionName, 'url'=>'regions/manage/parentId/'.$regionId);
    }
    
    $breadCrumbs[] = array('label'=>$subRegionName, 'url'=>'regions/manageListings/locationId/'.$regionId);
    $this->_breadCrumbs = $breadCrumbs;
?>

<h1><?php echo A::t('directory', 'Locations Management'); ?></h1>

<div class="bloc">
    <?php
        echo $tabs;
    ?>
    <div class="sub-title">
    <?php
        if(!empty($regionId)){
            echo '<a class="sub-tab previous" href="regions/manage/parentId/'.CHtml::encode($regionId).'">'.$regionName.'</a>» ';
            echo '<a class="sub-tab active" href="regions/manageListings/locationId/'.CHtml::encode($subRegionId).'">'.$subRegionName.'</a>';
        }else{
            echo '<a class="sub-tab active" href="regions/manageListings/locationId/'.CHtml::encode($subRegionId).'">'.$subRegionName.'</a>';
        }
    ?>
    </div>
    <div class="content">
    <?php
        echo $actionMessage;
        
        CWidget::create('CGridView', array(
            'model'=>'Listings',
            'actionPath'=>'regions/manageListings/locationId/'.$subRegionId,
            'condition'=>'subregion_id = "'.(int)$subRegionId.'"',
            'passParameters'=>true,
            'pagination'=>array('enable'=>true, 'pageSize'=>20),
            'sorting'=>true,
            
            'fields'=>array(
                'image_file_thumb'     => array('title'=>'Image', 'type'=>'image', 'align'=>'', 'width'=>'40px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>false, 'imagePath'=>'images/modules/directory/listings/thumbs/', 'defaultImage'=>'no_image.png', 'imageHeight'=>'25px', 'alt'=>''),
                'business_name'        => array('title'=>'Name', 'type'=>'label', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>''),
                'advertise_plan_id'    => array('title'=>'Advertise Plan', 'type'=>'label', 'align'=>'', 'width'=>'105px', 'class'=>'center', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>$advertisePlanNames),
                'access_level'         => array('title'=>A::t('directory', 'Access'), 'type'=>'enum', 'class'=>'center', 'headerClass'=>'center', 'source'=>array('0'=>A::t('directory', 'Public'), '1'=>A::t('directory', 'Registered')), 'width'=>'65px'),
                'is_approved'          => array('title'=>A::t('directory', 'Approved'), 'type'=>'enum', 'width'=>'80px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'source'=>array('0'=>A::t('directory', 'Pending'), '1'=>A::t('directory', 'Paid'), '2'=>A::t('directory', 'Approved')), 'htmlOptions'=>array()),
                'is_published'         => array('title'=>A::t('directory', 'Published'), 'type'=>'enum', 'width'=>'80px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'source'=>array('0'=>'<span class="badge-red">'.A::t('directory', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('directory', 'Yes').'</span>'), 'htmlOptions'=>array()),
                'finish_publishing'    => array('title'=>A::t('directory', 'Finish Published'), 'type'=>'datetime', 'align'=>'', 'width'=>'135px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>$dateTimeFormat),
                'sort_order'           => array('title'=>A::t('directory', 'Sort Order'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'100px'),
            ),
            'actions'=>array(),
            'return'=>false,
        ));

    ?>        
    </div>
</div>
