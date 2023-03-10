<?php
    $this->_activeMenu = 'listings/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('directory', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('directory', 'Business Directory'), 'url'=>'modules/settings/code/directory'),
        array('label'=>A::t('directory', 'Listings Management'), 'url'=>'listings/manage'),
        array('label'=>$listingName)
    );
?>

<h1><?php echo A::t('directory', 'Listings Management'); ?></h1>

<div class="bloc">
    <?php echo $tabs; ?>
    <div class="sub-title">
    <?php
        echo '<a class="sub-tab previous" href="listings/manage/type/all">'.A::t('directory', 'All').'</a>';
        if($typeTab == 'all'){
            echo '» <a class="sub-tab active" href="listings/manageCategories/listingId/'.CHtml::encode($listingId).'/type/all">'.A::t('directory', 'Listing').': '.$listingName.'</a>';
        }
        echo '<a class="sub-tab previous" href="listings/manage/type/pending">'.A::t('directory', 'Pending').'</a>';
        if($typeTab == 'pending'){
            echo '» <a class="sub-tab active" href="listings/manageCategories/listingId/'.CHtml::encode($listingId).'/type/pending">'.A::t('directory', 'Listing').': '.$listingName.'</a>';
        }
        echo '<a class="sub-tab previous" href="listings/manage/type/approved">'.A::t('directory', 'Approved').'</a>';
        if($typeTab == 'approved'){
            echo '» <a class="sub-tab active" href="listings/manageCategories/listingId/'.CHtml::encode($listingId).'/type/approved">'.A::t('directory', 'Listing').': '.$listingName.'</a>';
        }
        echo '<a class="sub-tab previous" href="listings/manage/type/expired">'.A::t('directory', 'Expired').'</a>';
        if($typeTab == 'expired'){
            echo '» <a class="sub-tab active" href="listings/manageCategories/listingId/'.CHtml::encode($listingId).'/type/expired">'.A::t('directory', 'Listing').': '.$listingName.'</a>';
        }
        echo '<a class="sub-tab previous" href="listings/manage/type/canceled">'.A::t('directory', 'Canceled').'</a>';
        if($typeTab == 'canceled'){
            echo '» <a class="sub-tab active" href="listings/manageCategories/listingId/'.CHtml::encode($listingId).'/type/canceled">'.A::t('directory', 'Listing').': '.$listingName.'</a>';
        }
    ?>
    </div>

    <div class="content">
    <?php
        echo $actionMessage;

        if($displayButtonAdd && Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('directory', 'add')){
            echo '<a href="listings/addCategory/listingId/'.$listingId.'/type/'.$typeTab.'" class="add-new">'.A::t('directory', 'Add Category').'</a>';
        }

        ListingsCategories::model()->setTypeRelations('categories');

        CWidget::create('CGridView', array(
            'model'       => 'ListingsCategories',
            'actionPath'  => 'listings/manageCategories/listingId/'.$listingId,
            'condition'   => '`'.CConfig::get('db.prefix').'listings_categories`.`listing_id` = "'.$listingId.'"',
            //'defaultOrder'=>array('name'=>'DESC'),
            'passParameters'=>true,
            'pagination'=>array('enable'=>true, 'pageSize'=>20),
            'sorting'=>true,
            'filters'=>array(
                'name'           => array('title'=>A::t('directory', 'Name'), 'type'=>'textbox', 'operator'=>'%like%', 'width'=>'140px', 'maxLength'=>''),
                'description'    => array('title'=>A::t('directory', 'Description'), 'type'=>'textbox', 'operator'=>'%like%', 'width'=>'200px', 'maxLength'=>''),
            ),
            'fields'=>array(
                'name'              => array('title'=>A::t('directory', 'Name'), 'type'=>'label', 'class'=>'left', 'width'=>'140px', 'headerClass'=>'left'),
                'description'       => array('title'=>A::t('directory', 'Description'), 'type'=>'label', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'maxLength'=>'150'),
                'icon'              => array('title'=>A::t('directory', 'Icon'), 'type'=>'image', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'imagePath'=>'images/modules/directory/categories/', 'defaultImage'=>'no_image.png', 'width'=>'40px', 'imageHeight'=>'20px'),
                'icon_map'          => array('title'=>A::t('directory', 'Map Icon'), 'type'=>'image', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'imagePath'=>'images/modules/directory/categories/mapicons/', 'defaultImage'=>'no_image.png', 'width'=>'80px', 'imageHeight'=>'25px'),
            ),
            'actions'=>array(
                'delete'=>array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('directory', 'delete'),
                    'link'=>'listings/deleteCategory/id/{id}/type/'.$typeTab, 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('directory', 'Delete this record'), 'onDeleteAlert'=>true
                )
            ),
            'return'=>false,
        ));

    ?>
    </div>
</div>
