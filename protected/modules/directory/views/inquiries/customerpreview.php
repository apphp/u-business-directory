<?php
    $this->_pageTitle = A::t('directory', 'Preview Inquiry');
    $this->_activeMenu = 'inquiries/myInquiries';
?>

<article id="page-preview-inquiries" class="page-preview-inquiries type-page hentry">
    <header class="entry-header">
        <h1 class="entry-title">
            <span><?php echo A::t('directory', 'Preview Inquiry'); ?></span>
        </h1>
        <?php
            $breadCrumbLinks = array(array('label'=> A::t('directory', 'Home'), 'url'=>Website::getDefaultPage()));
            $breadCrumbLinks[] = array('label'=> A::t('directory', 'Dashboard'), 'url'=>'customers/dashboard');
            $breadCrumbLinks[] = array('label' => A::t('directory', 'Inquiries'), 'url'=>'inquiries/myInquiries');
            $breadCrumbLinks[] = array('label' => A::t('directory', 'Preview Inquiry'));
            CWidget::create('CBreadCrumbs', array(
                'links' => $breadCrumbLinks,
                'wrapperClass' => 'category-breadcrumb clearfix',
                'linkWrapperTag' => 'span',
                'separator' => '&nbsp;/&nbsp;',
                'return' => false
            ));
        ?>
    </header>
    <div class="block-body">
    <?php
        // open form
        $formName = 'frmInquiryPreview';
        echo CHtml::openForm('reviews/manage', 'post', array('name'=>$formName, 'id'=>$formName, 'autoGenerateId'=>true));
    ?>
        <input type="hidden" name="act" value="send">

        <div class="left-side" id="left-editpost">

            <div class="row">
                <label for="name"><?php echo A::t('directory', 'Name'); ?>:</label>
                <label id="<?php echo $formName; ?>_name" readonly type="label" name="name" class="large"><?php echo $name; ?></label>
            </div>
            <div class="row">
                <label for="inquiry_type"><?php echo A::t('directory', 'Inquiry Type'); ?>:</label>
                <label id="<?php echo $formName; ?>_inquiry_type" readonly type="label" name="inquiry_type" class="large"><?php echo $inquiryType; ?></label>
            </div>
            <div class="row">
                <label for="listing_name"><?php echo A::t('directory', 'Listing'); ?>:</label>
                <label id="<?php echo $formName; ?>_listing_name" readonly type="label" name="listing_name" class="large"><a href="listings/view/id/<?php echo (int)$listingId; ?>" target="_blank"><?php echo $listingName; ?></a></label>
                <div style="clear:both;"></div>
            </div>
            <div class="row">
                <label for="email"><?php echo A::t('directory', 'Email'); ?>:</label>
                <label id="<?php echo $formName; ?>_email" readonly type="label" name="email" class="large"><?php echo $email; ?></label>
                <div style="clear:both;"></div>
            </div>
            <div class="row">
                <label for="phone"><?php echo A::t('directory', 'Phone'); ?>:</label>
                <label id="<?php echo $formName; ?>_phone" readonly type="label" name="phone" class="large"><?php echo $phone; ?></label>
                <div style="clear:both;"></div>
            </div>
            <div class="row">
                <label for="region"><?php echo A::t('directory', 'Location'); ?>:</label>
                <label id="<?php echo $formName; ?>_region" readonly type="label" name="region" class="large"><?php echo $region; ?></label>
                <div style="clear:both;"></div>
            </div>
            <div class="row">
                <label for="subregion"><?php echo A::t('directory', 'Sub-Location'); ?>:</label>
                <label id="<?php echo $formName; ?>_subregion" readonly type="label" name="subregion" class="large"><?php echo $subregion; ?></label>
                <div style="clear:both;"></div>
            </div>
            <div class="row">
                <label for="availability"><?php echo A::t('directory', 'Availability'); ?>:</label>
                <label id="<?php echo $formName; ?>_availability" readonly type="label" name="availability" class="large"><?php echo $availability; ?></label>
                <div style="clear:both;"></div>
            </div>
            <div class="row">
                <label for="preferred_contact"><?php echo A::t('directory', 'Preferred Contact'); ?>:</label>
                <label id="<?php echo $formName; ?>_preferred_contact" readonly type="label" name="preferred_contact" class="large"><?php echo $preferredContacts; ?></label>
                <div style="clear:both;"></div>
            </div>
            <div class="row">
                <label for="description"><?php echo A::t('directory', 'Description'); ?>:</label>
                <label id="<?php echo $formName; ?>_description" readonly type="label" name="description" class="large"><?php echo $description; ?></label>
                <div style="clear:both;"></div>
            </div>
            <div class="row">
                <label for="date_created"><?php echo A::t('directory', 'Date Created'); ?>:</label>
                <label id="<?php echo $formName; ?>_date_created" readonly type="label" name="date_created" class="large"><?php echo $dateCreated; ?></label>
                <div style="clear:both;"></div>
            </div>
            <div class="row">
                <label for="active"><?php echo A::t('directory', 'Active'); ?>:</label>
				<label id="<?php echo $formName; ?>_active" readonly type="label" name="active" class="large"><a class="tooltip-link" href="inquiries/changeStatus/id/<?php echo $id.(!empty($type) ? '/type/'.$type : ''); ?>" title="<?php echo A::t('directory', 'Click to change status'); ?>"><?php echo $active; ?></a></label>
                <div style="clear:both;"></div>
            </div>
        </div>

        <div class="clear"></div>

        <div class="buttons-wrapper">
            <input class="button white" onclick="javascript: void(0);" value="<?php echo A::t('directory', 'Cancel'); ?>" type="button">
        </div>

        <?php echo CHtml::closeForm(); ?>
    </div>
</article>

<?php
    A::app()->getClientScript()->registerScript(
        'previewReviews',
        'jQuery(document).ready(function(){
            jQuery("#'.$formName.' div.buttons-wrapper input.button").click(function(){
                jQuery(location).attr("href", "inquiries/myInquiries'.(!empty($type) ? '/type/'.$type : '').'");
            });
        });'
    );
