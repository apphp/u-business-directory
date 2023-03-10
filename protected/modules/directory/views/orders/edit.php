<?php
    $this->_activeMenu = 'orders/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('directory', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('directory', 'Business Directory'), 'url'=>'modules/settings/code/directory'),
        array('label'=>A::t('directory', 'Orders Management'), 'url'=>'orders/manage'),
        array('label'=>A::t('directory', 'Edit Order')),
    );

//    A::app()->getClientScript()->registerScriptFile('js/modules/directory/directory.js');

    $statusColor = array('0'=>'gray', '1'=>'yellow', '2'=>'blue', '3'=>'green', '4'=>'red');
?>

<h1><?php echo A::t('directory', 'Orders Management'); ?></h1>

<div class="bloc">
    <?php
        echo $tabs;
    ?>
    <div class="sub-title">
    <?php
        echo A::t('directory', 'Edit Order');
    ?>
    </div>

    <div class="content">
<?php
        echo $actionMessage;
        // open form
        $formName = 'frmOrderPreview';

        echo CWidget::create('CDataForm', array(
                'model'=>'Orders',
                'primaryKey'=>$id,
                'operationType'=>'edit',
                'action'=>'orders/edit/id/'.$id,
                'successUrl'=>'orders/manage',
                'cancelUrl'=>'orders/manage',
                'passParameters'=>false,
                'method'=>'post',
                'htmlOptions'=>array(
                    'id'     =>$formName,
                    'name'   =>$formName,
                    'enctype'=>'multipart/form-data',
                    'autoGenerateId'=>true
                ),
                'requiredFieldsAlert'=>true,
                'fields'=>array(
                    'listing_name' => array('type'=>'label', 'title'=>A::t('directory', 'Listing'), 'tooltip'=>'', 'default'=>'', 'validation'=>array(), 'htmlOptions'=>array()),
                    'full_name' => array('type'=>'label', 'title'=>A::t('directory', 'Created By'), 'tooltip'=>'', 'default'=>'', 'validation'=>array(), 'htmlOptions'=>array()),
                    'order_number' => array('type'=>'label', 'title'=>A::t('directory', 'Order'), 'tooltip'=>'', 'default'=>'', 'validation'=>array(), 'htmlOptions'=>array()),
                    'payment_id' => array('type'=>'label', 'title'=>A::t('directory', 'Payment Type'), 'tooltip'=>'', 'default'=>'', 'definedValues'=>$allPaymentTypes, 'htmlOptions'=>array()),
                    'payment_method' => array('type'=>'label', 'title'=>A::t('directory', 'Payment Method'), 'tooltip'=>'', 'default'=>'', 'definedValues'=>$allPaymentMethods, 'htmlOptions'=>array()),
                    'description' => array('type'=>'label', 'title'=>A::t('directory', 'Description'), 'tooltip'=>'', 'default'=>'', 'validation'=>array(), 'htmlOptions'=>array()),
                    'status' => array('type'=>'select', 'title'=>A::t('directory', 'Status'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($allStatus)), 'data'=>$allStatus, 'htmlOptions'=>array()),
                    'date_created' => array('type'=>'label', 'title'=>A::t('directory', 'Date Created'), 'tooltip'=>'', 'default'=>'', 'validation'=>array(), 'definedValues'=>array(''=>A::t('directory', 'Unknown'), '0000-00-00 00:00:00'=>A::t('directory', 'Unknown')), 'htmlOptions'=>array(), 'format'=>$dateTimeFormat),
                    'payment_date' => array('type'=>'label', 'title'=>A::t('directory', 'Payment Date'), 'tooltip'=>'', 'default'=>'', 'validation'=>array(), 'definedValues'=>array(''=>A::t('directory', 'Unknown'), '0000-00-00 00:00:00'=>A::t('directory', 'Unknown')), 'htmlOptions'=>array(), 'format'=>$dateTimeFormat),
                    'status_changed' => array('type'=>'label', 'title'=>A::t('directory', 'Status Changed'), 'tooltip'=>'', 'default'=>'', 'validation'=>array(), 'definedValues'=>array(''=>A::t('directory', 'Unknown'), '0000-00-00 00:00:00'=>A::t('directory', 'Unknown')), 'htmlOptions'=>array(), 'format'=>$dateTimeFormat),
                    'total_price' => array('type'=>'label', 'title'=>A::t('directory', 'Price'), 'tooltip'=>'', 'default'=>'', 'validation'=>array(), 'definedValues'=>array(), 'htmlOptions'=>array()),
                ),
                'translationInfo'       => array('relation'=>array('id', 'listing_id'), 'languages'=>Languages::model()->findAll('is_active = 1')),
                'translationFields' => array(),
                'buttons'=>array(
                    'submitUpdateClose' => array('type'=>'submit', 'value'=>A::t('directory', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose')),
                    'submitUpdate' => array('type'=>'submit', 'value'=>A::t('directory', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
                    'cancel' => array('type'=>'button', 'value'=>A::t('directory', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
                ),
                'messagesSource'=>'core',
                'alerts'=>array('type'=>'flash'),
                'return'=>true,
            ));
?>
    </div>
</div>
