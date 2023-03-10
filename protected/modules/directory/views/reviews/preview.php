<?php
    $this->_activeMenu = 'review/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('directory', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('directory', 'Business Directory'), 'url'=>'modules/settings/code/directory'),
        array('label'=>A::t('directory', 'Reviews Management'), 'url'=>'reviews/manage'),
        array('label'=>A::t('directory', 'Preview Review')),
    );

//    A::app()->getClientScript()->registerScriptFile('js/modules/directory/directory.js');
?>

<h1><?php echo A::t('directory', 'Reviews Management'); ?></h1>

<div class="bloc">
    <?php
        echo $tabs;
    ?>
    <div class="sub-title">
    <?php
        echo '<a class="sub-tab '.($typeTab == 'pending' ? 'active' : 'previous').'" href="reviews/manage/type/pending">'.A::t('directory', 'Pending').'</a>';
        echo '<a class="sub-tab '.($typeTab == 'approved' ? 'active' : 'previous').'" href="reviews/manage/type/approved">'.A::t('directory', 'Approved').'</a>';
        echo A::t('directory', 'Preview Review');
    ?>
    </div>

    <div class="content">
    <?php
        echo $actionMessage;
        // open form
        $formName = 'frmReviewPreview';
        echo CHtml::openForm('reviews/manage/typeTab/'.$typeTab, 'post', array('name'=>$formName, 'id'=>$formName, 'autoGenerateId'=>true));
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
                <label for="customer_email"><?php echo A::t('directory', 'Email'); ?>:</label>
                <label id="<?php echo $formName; ?>_customer_email" readonly type="label" name="customer_email" class="large"><?php echo $customerEmail; ?></label>
                <div style="clear:both;"></div>
            </div>
            <div class="row">
                <label for="message"><?php echo A::t('directory', 'Message'); ?>:</label>
                <label id="<?php echo $formName; ?>_message" readonly type="label" class="large" name="message"><?php echo CHtml::encode($message); ?></label>
                <div style="clear:both;"></div>
            </div>
            <div class="row">
                <label for="rating_price"><?php echo A::t('directory', 'Price'); ?>:</label>
                <label id="<?php echo $formName; ?>_rating_price" readonly type="label" name="rating_price" class="stars tooltip-link" title="<?php echo A::t('directory', 'Rate').': '.$ratingPrice; ?>"><label style="width:<?php echo 16*$ratingPrice; ?>px;"></label></label>
                <div style="clear:both;"></div>
            </div>
            <div class="row">
                <label for="rating_location"><?php echo A::t('directory', 'Location'); ?>:</label>
                <label id="<?php echo $formName; ?>_rating_location" readonly type="label" name="rating_location" class="stars tooltip-link" title="<?php echo A::t('directory', 'Rate').': '.$ratingLocation; ?>"><label style="width:<?php echo 16*$ratingLocation; ?>px;"></label></label>
                <div style="clear:both;"></div>
            </div>
            <div class="row">
                <label for="rating_services"><?php echo A::t('directory', 'Services'); ?>:</label>
                <label id="<?php echo $formName; ?>_rating_services" readonly type="label" name="rating_services" class="stars tooltip-link" title="<?php echo A::t('directory', 'Rate').': '.$ratingServices; ?>"><label style="width:<?php echo 16*$ratingServices; ?>px;"></label></label>
                <div style="clear:both;"></div>
            </div>
            <div class="row">
                <label for="rating_staff"><?php echo A::t('directory', 'Staff'); ?>:</label>
                <label id="<?php echo $formName; ?>_rating_staff" readonly type="label" name="rating_staff" class="stars tooltip-link" title="<?php echo A::t('directory', 'Rate').': '.$ratingStaff; ?>"><label style="width:<?php echo 16*$ratingStaff; ?>px;"></label></label>
                <div style="clear:both;"></div>
            </div>
            <div class="row">
                <label for="is_public"><?php echo A::t('directory', 'Published'); ?>:</label>
                <label id="<?php echo $formName; ?>_is_public" readonly type="label" name="is_public" class="small"><span class="label-<?php echo $isPublic == 1 ? 'green' : 'red'; ?>"><?php echo $isPublic == 1 ? A::t('directory', 'Yes') : A::t('directory', 'No'); ?></span></label>
                <div style="clear:both;"></div>
            </div>
            <div class="row">
                <label for="date_created"><?php echo A::t('directory', 'Date Created'); ?>:</label>
                <label id="<?php echo $formName; ?>_date_created" readonly type="label" name="date_created" class="large"><?php echo $dateCreated; ?></label>
                <div style="clear:both;"></div>
            </div>
        </div>

        <div class="clear"></div>

        <div class="buttons-wrapper">
            <input class="button white" onclick="$(location).attr('href', 'reviews/manage');" value="<?php echo A::t('directory', 'Cancel'); ?>" type="button">
        </div>

        <?php echo CHtml::closeForm(); ?>
    </div>
</div>

<?php
    A::app()->getClientScript()->registerScript(
        'previewReviews',
        'jQuery(document).ready(function(){
            jQuery("#frmReviewPreview div.button-wrapper input.button").click(function(){
                jQuery(location).attr("href", "reviews/manage");
            });
        });'
    );
