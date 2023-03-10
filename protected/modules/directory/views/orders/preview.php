<?php
    $this->_activeMenu = 'orders/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('directory', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('directory', 'Business Directory'), 'url'=>'modules/settings/code/directory'),
        array('label'=>A::t('directory', 'Orders Management'), 'url'=>'orders/manage'),
        array('label'=>A::t('directory', 'Preview Order')),
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
        echo A::t('directory', 'Preview Order');
    ?>
    </div>

    <div class="content">
    <?php
        echo $actionMessage;
        // open form
        $formName = 'frmOrderPreview';
        echo CHtml::openForm('orders/manage', 'post', array('name'=>$formName, 'id'=>$formName, 'autoGenerateId'=>true));
    ?>
        <input type="hidden" name="act" value="send">

        <div class="left-side" id="left-editpost">

            <div class="row">
                <label for="listing_name"><?php echo A::t('directory', 'Listing'); ?>:</label>
                <label id="<?php echo $formName; ?>_listing_name" readonly type="label" name="listing_name" class="large">
                    <a target="_blank" href="listings/view/id/<?php echo CHtml::encode($listingId); ?>"><?php echo $listingName; ?></a>
                </label>
            </div>
            <div class="row">
                <label for="customer_name"><?php echo A::t('directory', 'Created By'); ?>:</label>
                <label id="<?php echo $formName; ?>_customer_name" readonly type="label" name="customer_name" class="large"><?php echo $customerName; ?></label>
            </div>
            <div class="row">
                <label for="order_number"><?php echo A::t('directory', 'Order'); ?>:</label>
                <label id="<?php echo $formName; ?>_order_number" readonly type="label" name="order_number" class="large"><?php echo $orderNumber; ?></label>
            </div>
            <div class="row">
                <label for="payment_type"><?php echo A::t('directory', 'Payment Type'); ?>:</label>
                <label id="<?php echo $formName; ?>_payment_type" readonly type="label" name="payment_type"><?php echo $orderPaymentType; ?></label>
                <div style="clear:both;"></div>
            </div>
            <div class="row">
                <label for="payment_method"><?php echo A::t('directory', 'Payment Method'); ?>:</label>
                <label id="<?php echo $formName; ?>_payment_method" readonly type="label" name="payment_method"><?php echo $orderPaymentMethod; ?></label>
                <div style="clear:both;"></div>
            </div>
            <div class="row">
                <label for="description"><?php echo A::t('directory', 'Description'); ?>:</label>
                <label id="<?php echo $formName; ?>_description" readonly type="label" name="description"><?php echo $orderDescription; ?></label>
                <div style="clear:both;"></div>
            </div>
            <div class="row">
                <label for="status"><?php echo A::t('directory', 'Status'); ?>:</label>
                <label id="<?php echo $formName; ?>_status" readonly type="label" name="_status" class="small"><span class="label-<?php echo isset($statusColor[$orderStatusId]) ? $statusColor[$orderStatusId] : 'gray'; ?>"><?php echo $orderStatus; ?></span></label>
                <div style="clear:both;"></div>
            </div>
            <div class="row">
                <label for="date_created"><?php echo A::t('directory', 'Date Created'); ?>:</label>
                <label id="<?php echo $formName; ?>_date_created" readonly type="label" name="date_created" class="large"><?php echo ($orderDateCreated != '' && $orderDateCreated != '0000-00-00 00:00:00' ? CLocale::date($dateTimeFormat, $orderDateCreated) : A::t('directory', 'Unknown')); ?></label>
                <div style="clear:both;"></div>
            </div>
            <div class="row">
                <label for="payment_date"><?php echo A::t('directory', 'Payment Date'); ?>:</label>
                <label id="<?php echo $formName; ?>_payment_date" readonly type="label" name="payment_date" class="large"><?php echo ($orderDatePayment != '' && $orderDatePayment != '0000-00-00 00:00:00' ? CLocale::date($dateTimeFormat, $orderDatePayment) : A::t('directory', 'Unknown')); ?></label>
                <div style="clear:both;"></div>
            </div>
            <div class="row">
                <label for="status_changed"><?php echo A::t('directory', 'Status Changed'); ?>:</label>
                <label id="<?php echo $formName; ?>_status_changed" readonly type="label" name="status_changed" class="large"><?php echo ($orderStatusChanged != '' && $orderStatusChanged != '0000-00-00 00:00:00' ? CLocale::date($dateTimeFormat, $orderStatusChanged) : A::t('directory', 'Unknown')); ?></label>
                <div style="clear:both;"></div>
            </div>
            <div class="row">
                <label for="price"><?php echo A::t('directory', 'Price'); ?>:</label>
                <label id="<?php echo $formName; ?>_price" readonly type="label" name="price" class="large"><?php echo $currencySymbol.' '.$orderPrice; ?></label>
                <div style="clear:both;"></div>
            </div>
        </div>

        <div class="clear"></div>

        <div class="buttons-wrapper">
            <input class="button white" onclick="javascript:void(0)" value="<?php echo A::t('directory', 'Cancel'); ?>" type="button">
        </div>

        <?php echo CHtml::closeForm(); ?>
    </div>
</div>

<?php
    A::app()->getClientScript()->registerScript(
        'previewReviews',
        'jQuery(document).ready(function(){
            jQuery("#'.CHtml::encode($formName).' div.buttons-wrapper input.button").click(function(){
                jQuery(location).attr("href", "orders/manage");
            });
        });',
        1
    );
