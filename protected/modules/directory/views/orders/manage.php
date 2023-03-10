<?php
    $this->_activeMenu = 'orders/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('directory', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('directory', 'Business Directory'), 'url'=>'modules/settings/code/directory'),
        array('label'=>A::t('directory', 'Orders Management'), 'url'=>'orders/manage'),
    );
?>

<h1><?php echo A::t('directory', 'Orders Management'); ?></h1>

<div class="bloc">
    <?php echo $tabs; ?>

    <div class="content">
    <?php
        echo $actionMessage;

        CWidget::create('CGridView', array(
            'model'=>'Orders',
            'actionPath'=>'orders/manage',
            'condition'=>CConfig::get('db.prefix').'orders.status > 0',
            //'defaultOrder'=>array('field_1'=>'DESC'),
            'passParameters'=>true,
            'pagination'=>array('enable'=>true, 'pageSize'=>20),
            'sorting'=>true,
            'filters'=>array(
                'order_number' => array('title'=>A::t('directory', 'Order number'), 'type'=>'textbox', 'operator'=>'=', 'width'=>'100px', 'maxLength'=>''),
                'created_date' => array('title'=>A::t('directory', 'Date'), 'type'=>'datetime', 'operator'=>'like%', 'width'=>'80px', 'maxLength'=>'', 'format'=>''),
//                'customer_id'  => array('title'=>A::t('directory', 'Customer'), 'type'=>'enum', 'operator'=>'=', 'width'=>'80px', 'source'=>array('0'=>A::t('directory', 'Preparing'), '1'=>'Pending', '2'=>'Paid', '3'=>'Completed', '4'=>'Refunded')),
                'status'       => array('title'=>A::t('directory', 'Status'), 'type'=>'enum', 'operator'=>'=', 'width'=>'100px', 'emptyOption'=>true, 'emptyValue'=>'--', 'source'=>$allStatus),
            ),
            'fields'=>array(
                'order_number' => array('title'=>A::t('directory', 'Order'), 'type'=>'label', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true),
                'created_date' => array('title'=>A::t('directory', 'Date'), 'type'=>'datetime', 'align'=>'', 'width'=>'130px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'format'=>$dateTimeFormat),
                'first_name' => array('title'=>A::t('directory', 'Customer'), 'type'=>'concat', 'align'=>'', 'width'=>'90px', 'class'=>'left', 'headerClass'=>'left', 'concatFields'=>array('first_name', 'last_name'), 'isSortable'=>true,  'concatSeparator'=>' '),
//                'currency' => array('title'=>A::t('directory', 'Country'), 'type'=>'image', 'align'=>'', 'width'=>'', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false),
                'advertise_plan_id' => array('title'=>A::t('directory', 'Plan'), 'type'=>'enum', 'align'=>'', 'width'=>'60px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'source'=>$adverticePlans),
                'listing_name' => array('title'=>A::t('directory', 'Listing'), 'type'=>'link', 'align'=>'', 'width'=>'130px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'linkUrl'=>'listings/view/id/{listing_id}', 'linkText'=>'{listing_name}', 'prependCode'=>'', 'appendCode'=>'', 'htmlOptions'=>array('target'=>'_blank')),
                'total_price'=> array('title'=>A::t('directory', 'Price'), 'type'=>'label', 'align'=>'', 'width'=>'60px', 'class'=>'right', 'headerClass'=>'right', 'isSortable'=>true, 'prependCode'=>$currencySymbol.' '),
                'status' => array('title'=>A::t('directory', 'Status'), 'type'=>'enum', 'align'=>'', 'width'=>'80px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'source'=>array(1=>'<span class="label-gray">'.A::t('directory', 'Pending').'</span>', 2=>'<span class="label-green">'.A::t('directory', 'Completed').'</span>', 3=>'<span class="label-red">'.A::t('directory', 'Payment Error').'</span>', 4=>'<span class="label-red">'.A::t('directory', 'Refunded').'</span>', 5=>'<span class="label-red">'.A::t('directory', 'Canceled').'</span>')),
            ),
            'actions'=>array(
                'preview'       => array(
                    'disabled'      => !Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('directory', 'edit'),
                    'link'          => 'orders/preview/id/{id}', 'imagePath'=>'templates/backend/images/details.png', 'title'=>A::t('directory', 'Preview this record')
                ),
                'edit'       => array(
                    'disabled'      => !Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('directory', 'edit'),
                    'link'          => 'orders/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('directory', 'Edit this record')
                ),
                'delete'=>array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('directory', 'delete'),
                    'link'=>'orders/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('directory', 'Delete this record'), 'onDeleteAlert'=>true
                )
            ),
            'return'=>false,
        ));

    ?>
    </div>
</div>
