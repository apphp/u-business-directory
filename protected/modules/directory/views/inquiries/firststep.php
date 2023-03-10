<?php
    $this->_pageTitle = A::t('directory', 'Send Inquiry');
    A::app()->getClientScript()->registerScriptFile('js/modules/directory/directory.js', 0);
?>
<article id="page-edit-listing" class="page-edit-listing type-page status-publish hentry">
<?php if(!$showWidget){ ?>
    <header class="entry-header">
        <h1 class="entry-title">
            <span><?php echo A::t('directory', 'Send Inquiry'); ?></span>
        </h1>
        <?php
            $breadCrumbLinks = array(array('label'=> A::t('directory', 'Home'), 'url'=>Website::getDefaultPage()));
            $breadCrumbLinks[] = array('label' => A::t('directory', 'Send Inquiry'));
            CWidget::create('CBreadCrumbs', array(
                'links' => $breadCrumbLinks,
                'wrapperClass' => 'category-breadcrumb clearfix',
                'linkWrapperTag' => 'span',
                'separator' => '&nbsp;/&nbsp;',
                'return' => false
            ));
        ?>
    </header>
<?php } ?>
    <div class="block-body">
    <?php echo DirectoryComponent::inquiriesBlock(); ?>
    <?php
        $onchange = "regions_ChangeSubRegions('inquiryFirstStep',this.value,'')";

        $fields = array(
            'act'=>array('type'=>'hidden', 'value'=>'send'),
            'separatorCategory'=>array(
                'separatorInfo'=>array('legend'=>A::t('directory', 'General Information')),
                'category_id'=>array('disabled'=>(!empty($specificListing) ? true : false), 'type'=>'select', 'title'=>A::t('directory', 'Category'), 'tooltip'=>'', 'mandatoryStar'=>false, 'value'=>$defaultCategory, 'data'=>$allCategories, 'emptyOption'=>true, 'emptyValue'=>'--', 'viewType'=>'dropdownlist', 'multiple'=>false, 'htmlOptions'=>array('class'=>'chosen-select-filter', 'id'=>'inquiries-first-step-category-id')),
                'description'=>array('type'=>'textarea', 'title'=>A::t('directory', 'Describe here what you need'), 'tooltip'=>'', 'mandatoryStar'=>true, 'value'=>$defaultDescription, 'htmlOptions'=>array('maxLength'=>'1024')),
            ),
            'separatorName' =>array(
                'separatorInfo' => array('legend'=>A::t('directory', 'Contact Information')),
                'name'=>array('type'=>'textbox', 'title'=>A::t('directory', 'Name'), 'tooltip'=>'', 'mandatoryStar'=>true, 'value'=>$defaultName, 'htmlOptions'=>array('maxLength'=>'50')),
                'email'=>array('type'=>'textbox', 'title'=>A::t('directory', 'Email'), 'tooltip'=>'', 'mandatoryStar'=>true, 'value'=>$defaultEmail, 'htmlOptions'=>array('maxLength'=>'70')),
                'phone'=>array('type'=>'textbox', 'title'=>A::t('directory', 'Phone'), 'tooltip'=>'', 'mandatoryStar'=>true, 'value'=>$defaultPhone, 'htmlOptions'=>array('maxLength'=>'20')),
                'region_id'=>array('disabled'=>(!empty($specificListing) ? true : false), 'type'=>'select', 'title'=>A::t('directory', 'Location'), 'tooltip'=>'', 'mandatoryStar'=>false, 'value'=>$defaultRegion, 'data'=>$allLocations, 'emptyOption'=>true, 'emptyValue'=>'--', 'htmlOptions'=>array('onchange'=>$onchange, 'id'=>'inquiries-first-step-region')),
                'subregion_id'=>array('disabled'=>(!empty($specificListing) ? true : false), 'type'=>'select', 'title'=>A::t('directory', 'Sub-Location'), 'tooltip'=>'', 'mandatoryStar'=>false, 'value'=>$defaultSubRegion, 'data'=>$subLocations, 'emptyOption'=>true, 'emptyValue'=>'--', 'htmlOptions'=>array('id'=>'inquiries-first-step-subregion')),
                'availability'=>array('type'=>'select', 'title'=>A::t('directory', 'I am available'), 'tooltip'=>'', 'mandatoryStar'=>true, 'value'=>$defaultAvailability, 'data'=>$availabilityList, 'emptyOption'=>false, 'htmlOptions'=>array()),
                'contacted'=>array('type'=>'select', 'title'=>A::t('directory', 'I prefer to be contacted'), 'tooltip'=>'', 'mandatoryStar'=>true, 'value'=>$defaultContacted, 'data'=>$preferredContactsList, 'htmlOptions'=>array()),
            )
        );

        $listingsFields = array('separatorInfo' => array('legend'=>A::t('directory', 'Search Result')));

        if(!empty($listings) && is_array($listings)){
            $i = 0;

            $fields['button'] = array(
                'type'=>'label',
                'title'=>'',
                'value'=>'',
                'appendCode'=>'<input class="button" value="'.A::t('directory', 'Send Inquiries').'" name="ap1" type="submit" />',
                'htmlOptions'=>array('style'=>'display:none;')
            );
            foreach($listings as $listing){
                $listingsFields['listing'.(++$i)] = array(
                    'type'=>'checkbox',
                    'title'=>'',
                    'value'=>$listing['listing_id'],
                    'checked'=>true,
                    'appendCode'=>'<div class="inquiries-listing">
                        <div class="listing-image">
                            <a href="listings/view/id/'.$listing['listing_id'].'" target="_blank">
                                <img src="images/modules/directory/listings/thumbs/'.(!empty($listing['image_file_thumb']) ? CHtml::encode($listing['image_file_thumb']) : 'no_logo.jpg').'" />
                            </a>
                        </div>
                        <div class="listings-description-block">
                            <h3 class="listing-name">
                                <a href="listings/view/id/'.$listing['listing_id'].'" target="_blank">'.$listing['business_name'].'</a>
                            </h3>
                            <div class="listing-description">'.CString::substr($listing['business_description'], 350, false, true).'</div>
                        </div>
                    </div>
                    <label for="listing'.$i.'" class="title-send">'.A::t('directory', 'Send Email').'</label>',
                    'htmlOptions'=>array('class'=>'listing-checkbox', 'id'=>'listing'.$i)
                );
            }
        }else{
            $listingsFields['listing0'] = array(
                'type'=>'label',
                'title'=>'',
                'value'=>'',
                'appendCode'=>'<h3 class="listing-empty">'.A::t('directory', 'Sorry, but your search did not found any listing').'</h3>',
                'htmlOptions'=>array('style'=>'display:none;')
            );
        }

        $fields['separatorListings'] = $listingsFields;

        $button = array();
        if(!$showNotFoundMessage){
            $button['submit'] = array('type'=>'submit', 'value'=>A::t('directory', 'Send Inquiries'), 'htmlOptions'=>array('class'=>'button'));
        }

        echo $actionMessage;

        echo CWidget::create('CFormView', array(
             'action'=>'inquiries/firstStep'.(!empty($specificListing) ? '/listingId/'.$specificListing->listing_id : '').(!empty($showWidget) ? '/widget/1' : ''),
             'method'=>'post',
             'htmlOptions'=>array(
                 'name'=>'inquiry-form-first-step',
                 'id'=>'inquiryFirstStep',
                 'data-send'=>A::te('directory', 'Send Inquiries'),
                 //'enctype'=>'multipart/form-data',
                 'autoGenerateId'=>true
             ),
             'requiredFieldsAlert'=>false,
             'fieldSets'=>array('type'=>'frameset'/*|tabs|tabsList*/, 'firstTabActive'=>true),
             'fieldWrapper'=>array('tag'=>'div', 'class'=>'row'),
             'fields'=>$fields,
             'buttons'=>$button,
             'buttonsPosition'=>'bottom',
             'events'=>array(
                 'focus'=>array('field'=>$errorField)
             ),
             'return'=>true,
         ));
        ?>
    </div>
</article>

<?php
    $jQueryCode = '
        jQuery(document).ready(function(){
        var $ = jQuery;
        var globalDebug = 1;
        $("#inquiries-first-step-category-id").change(function(e){
            var el = e.target;
            var selectCategory = $(el).find("option:selected").val();
            var selectLocation = $("#inquiries-first-step-region option:selected").val();
            var selectSubLocation = $("#inquiries-first-step-subregion option:selected").val();

            inquiries_getBlockLisitngsHtml("inquiryFirstStep", selectCategory, selectLocation, selectSubLocation);
        });

        $("#inquiries-first-step-region").change(function(e){
            var el = e.target;
            var selectCategory = $("#inquiries-first-step-category-id option:selected").val();
            var selectLocation = $(el).find("option:selected").val();
            var selectSubLocation = $("#inquiries-first-step-subregion option:selected").val();

            inquiries_getBlockLisitngsHtml("inquiryFirstStep", selectCategory, selectLocation, selectSubLocation);
        });

        $("#inquiries-first-step-subregion").change(function(e){
            var el = e.target;
            var selectCategory = $("#inquiries-first-step-category-id option:selected").val();
            var selectLocation = $("#inquiries-first-step-region option:selected").val();
            var selectSubLocation = $(el).find("option:selected").val();

            inquiries_getBlockLisitngsHtml("inquiryFirstStep", selectCategory, selectLocation, selectSubLocation);
        });
    });';

    if(!$showWidget){
        A::app()->getClientScript()->registerScript(
            'loadListings',
            $jQueryCode
        );
    }else{
        echo '<script type="text/javascript">'.$jQueryCode.'</script>';
    }
