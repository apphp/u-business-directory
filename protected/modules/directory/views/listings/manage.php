<?php
    $this->_activeMenu = 'listings/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('directory', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('directory', 'Business Directory'), 'url'=>'modules/settings/code/directory'),
        array('label'=>A::t('directory', 'Listings Management'), 'url'=>'listings/manage'),
    );

    function getIsApproved($record, $params)
    {
        $id = $record['id'];
        $isApproved = $record['is_approved'];
        $advertisePlanId = $record['advertise_plan_id'];
        $allAdvertisePlans = $params['allAdvertisePlans'];
        $typeTab = (!empty($params['typeTab']) ? '/typeTab/'.CHtml::encode($params['typeTab']) : '');

        // 0 - Pending, 1 - Approved, 2 - Canceled
        if($isApproved == 0){
            $output = '<a href="listings/approvedStatus/id/'.CHtml::encode($id).$typeTab.'" class="tooltip-link" title="'.A::t('directory', 'Click to Approve').'" data-id="'.CHtml::encode($id).'" onclick="return onApprovalRecord(this);"><span class="label-gray">'.A::t('directory', 'Pending').'</span></a>';
        }else if($isApproved == 1){
            $output = '<span class="label-green">'.A::t('directory', 'Approved').'</span>';
        }else{
            $output = '<span class="label-red">'.A::t('directory', 'Canceled').'</span>';
        }

        return $output;
    }
?>

<h1><?php echo A::t('directory', 'Listings Management'); ?></h1>

<div class="bloc">
    <?php echo $tabs; ?>
    <div class="sub-title">
    <?php
        echo '<a class="sub-tab '.($typeTab == 'all' ? 'active' : 'previous').'" href="listings/manage/'.$getUrl.'">'.A::t('directory', 'All').'</a>';
        echo '<a class="sub-tab '.($typeTab == 'pending' ? 'active' : 'previous').'" href="listings/manage/type/pending'.$getUrl.'">'.A::t('directory', 'Pending').'</a>';
        echo '<a class="sub-tab '.($typeTab == 'approved' ? 'active' : 'previous').'" href="listings/manage/type/approved'.$getUrl.'">'.A::t('directory', 'Approved').'</a>';
        echo '<a class="sub-tab '.($typeTab == 'expired' ? 'active' : 'previous').'" href="listings/manage/type/expired'.$getUrl.'">'.A::t('directory', 'Expired').'</a>';
        echo '<a class="sub-tab '.($typeTab == 'canceled' ? 'active' : 'previous').'" href="listings/manage/type/canceled'.$getUrl.'">'.A::t('directory', 'Canceled').'</a>';

    ?>
    </div>
    <div class="content">
    <?php

        echo $actionMessage;
        $fields = array();

        $fields['image_file_thumb']     = array('title'=>A::t('directory', 'Image'), 'type'=>'image', 'align'=>'', 'width'=>'40px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>false, 'imagePath'=>'images/modules/directory/listings/thumbs/', 'defaultImage'=>'no_image.png', 'imageHeight'=>'25px', 'alt'=>'');
        $fields['business_name']        = array('title'=>A::t('directory', 'Name'), 'type'=>'link', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'linkUrl'=>'listings/view/id/{id}', 'definedValues'=>array(), 'linkText'=>'{business_name}', 'htmlOptions'=>array('target'=>'_blank'));
        $fields['region_id']            = array('title'=>A::t('directory', 'Location'), 'type'=>'enum', 'align'=>'', 'width'=>'90px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'source'=>$regionNames);
        $fields['advertise_plan_id']    = array('title'=>A::t('directory', 'Advertise Plan'), 'type'=>'label', 'align'=>'', 'width'=>'105px', 'class'=>'center', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>$advertisePlanNames);
        $fields['access_level']         = array('title'=>A::t('directory', 'Access'), 'type'=>'enum', 'class'=>'center', 'headerClass'=>'center', 'width'=>'65px', 'source'=>array(0=>A::t('directory', 'Public'), 1=>A::t('directory', 'Registered')));
        $fields['finish_publishing']    = array('title'=>A::t('directory', 'Finish Published'), 'type'=>'datetime', 'align'=>'', 'width'=>'140px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'definedValues'=>array('0000-00-00 00:00:00'=>'- '.A::t('directory', 'Unknown').' -'), 'format'=>$dateTimeFormat);
        $fields['category_id']          = array('title'=>A::t('directory', 'Categories'), 'type'=>'link', 'class'=>'center', 'headerClass'=>'center', 'width'=>'75px', 'isSortable'=>false, 'linkUrl'=>'listings/manageCategories/listingId/{id}/type/'.$typeTab, 'linkText'=>A::t('directory', 'Categories'), 'htmlOptions'=>array('class'=>'subgrid-link'), 'prependCode'=>'[ ', 'appendCode'=>' ]');
        $fields['category_count']       = array('title' => '', 'type'=>'enum', 'sourceField'=>'id', 'table'=>'', 'default'=>'', 'width'=>'20px', 'source'=>$categoryCounts, 'definedValues'=>array(''=>'<span class="label-zerogray">0</span>'), 'isSortable'=>true, 'class' => 'left', 'prependCode'=>'<span class="label-lightgray">', 'appendCode'=>'</span>');
        $fields['sort_order']           = array('title'=>A::t('directory', 'Sort Order'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'80px');

        if($typeTab != 'expired'){
            $fields['is_approved']      = array('title'=>A::t('directory', 'Approved'), 'type'=>'label', 'width'=>'80px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'callback'=>array('function'=>'getIsApproved', 'params'=>array('allAdvertisePlans'=>$allAdvertisePlans, 'typeTab'=>$typeTab)), 'htmlOptions'=>array('onclick'=>'return onApprovalRecord(this);', 'data-id'=>'{id}'));
        }

        $fields['is_published']     = array('disabled'=>($typeTab == 'expired' ? true : false), 'title'=>A::t('directory', 'Published'), 'type'=>'link', 'width'=>'80px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'linkUrl'=>'listings/publishedStatus/id/{id}/type/'.$typeTab, 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-red">'.A::t('directory', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('directory', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('directory', 'Click to change status')));
        $fields['status_expired']   = array('disabled'=>($typeTab == 'approved' || $typeTab == 'pending' ? true : false), 'title'=>A::t('directory', 'Expired'), 'type'=>'enum', 'align'=>'', 'width'=>'70px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>($typeTab == 'all' ? true : false), 'source'=>array('0'=>'--', '1'=>'<span class="badge-red" style="width:auto">'.A::t('directory', 'Expired')), 'fields'=>array());
        $fields['id']               = array('title'=>A::t('directory', 'ID'), 'type'=>'label', 'align'=>'', 'width'=>'30px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true);


        if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('directory', 'add')){
            echo '<a href="listings/add/typeTab/'.CHtml::encode($typeTab).'" class="add-new">'.A::t('directory', 'Add Listing').'</a>';
        }

        CWidget::create('CGridView', array(
            'model'=>'Listings',
            'actionPath'=>'listings/manage/type/'.$typeTab,
            'condition'=>$condition,
            'passParameters'=>true,
            'defaultOrder'=>array('sort_order'=>'DESC', 'id'=>'DESC'),
            'pagination'=>array('enable'=>true, 'pageSize'=>20),
            'sorting'=>true,
            'filters'=>array(
                'business_name' => array('title'=>A::t('directory', 'Name'), 'type'=>'textbox', 'operator'=>'%like%', 'width'=>'120px', 'maxLength'=>125),
                'advertise_plan_id' => array('title'=>A::t('directory', 'Advertise Plans'), 'type'=>'enum', 'operator'=>'=', 'width'=>'', 'source'=>$advertisePlanNames, 'emptyOption'=>true, 'emptyValue'=>'--'),
                'customer_id'  => array('title'=>A::t('directory', 'Customer'), 'type'=>'textbox', 'operator'=>'=', 'width'=>'65px', 'autocomplete'=>array('enable'=>true, 'ajaxHandler'=>'customers/getCustomers', 'minLength'=>'2')),
                'region_id'    => array('title'=>A::t('directory', 'Location'), 'type'=>'enum', 'operator'=>'=', 'source'=>$regionNames, 'width'=>''),
                'is_published' => array('title'=>A::t('directory', 'Published'), 'type'=>'enum', 'operator'=>'=', 'width'=>'', 'source'=>array('0'=>A::t('directory', 'No'), '1'=>A::t('directory', 'Yes')), 'emptyOption'=>true, 'emptyValue'=>'--'),
                'is_featured' => array('title'=>A::t('directory', 'Featured'), 'type'=>'enum', 'operator'=>'=', 'width'=>'', 'source'=>array('0'=>A::t('directory', 'No'), '1'=>A::t('directory', 'Yes')), 'emptyOption'=>true, 'emptyValue'=>'--'),
            ),
            'fields'=>$fields,
            'actions'=>array(
                'edit'    => array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('directory', 'edit'),
                    'link'=>'listings/edit/id/{id}/typeTab/'.CHtml::encode($typeTab), 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('directory', 'Edit this record')
                ),
                'delete'=>array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('directory', 'delete'),
                    'link'=>'listings/delete/id/{id}/typeTab/'.CHtml::encode($typeTab), 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('directory', 'Delete this record'), 'onDeleteAlert'=>true
                )
            ),
            'return'=>false,
        ));

    ?>
    </div>
</div>
<?php
A::app()->getClientScript()->registerScript(
    'listingManagment',
    'function onApprovalRecord(el){return confirm("ID: " + jQuery(el).data("id") + "\n'.A::t('directory', 'Do you really want to approve the listing?').'");}',
    2
);
