<?php
    $this->_pageTitle = A::t('directory', 'Edit Listing');
    A::app()->getClientScript()->registerScriptFile('js/modules/directory/directory.js', 0);
?>
<article id="page-edit-listing" class="page-edit-listing type-page status-publish hentry">
    <header class="entry-header">
        <h1 class="entry-title">
            <span><?php echo A::t('directory', 'Edit Listing'); ?></span>
        </h1>
        <?php
            $breadCrumbLinks = array(array('label'=> A::t('directory', 'Home'), 'url'=>Website::getDefaultPage()));
            $breadCrumbLinks[] = array('label'=> A::t('directory', 'Dashboard'), 'url'=>'customers/dashboard');
            $breadCrumbLinks[] = array('label'=> A::t('directory', 'My Listings'), 'url'=>'listings/myListings');
            $breadCrumbLinks[] = array('label' => A::t('directory', 'Edit Listing'));
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

        $fields = array();
        $fields['separatorPlan'] = array(
            'separatorInfo' => array('legend'=>A::t('directory', 'Advertise Plan')),
            'advertise_plan_id' => array('title'=>'', 'type'=>'label', 'definedValues'=>array($defaultPlan=>$defaultPlanName), 'htmlOptions'=>array('class'=>'name-plan'))
        );

        $onchange = "regions_ChangeSubRegions('edit-listing-form',this.value,'')";
        $fields['separatorAddress'] = array();
        $fields['separatorAddress']['separatorInfo'] = array('legend'=>A::t('directory', 'Address Information'));
        $fields['separatorAddress']['region_id']     = array('type'=>'select', 'title'=>A::t('directory', 'Location'), 'default'=>0, 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($allRegions)), 'data'=>$allRegions, 'emptyOption'=>false, 'viewType'=>'dropdownlist', 'htmlOptions'=>array('onchange'=>$onchange, 'id'=>'edit-listing-region'));
        $fields['separatorAddress']['subregion_id']  = array('type'=>'select', 'title'=>A::t('directory', 'Sub-Location'), 'default'=>0, 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($allSubRegions)), 'data'=>$allSubRegions, 'emptyOption'=>false, 'viewType'=>'dropdownlist', 'htmlOptions'=>array('id'=>'edit-listing-subreion'));
        if($fieldAddress) $fields['separatorAddress']['business_address'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Address'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>255), 'appendCode'=>'<a href="javascript:void(0)" onclick="javascript:listings_FindCoordinates(\'edit-listing-form\')">'.A::t('directory', 'Find Longitude/Latitude').'</a>');
        if($fieldMap) $fields['separatorAddress']['region_longitude'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Location Longitude'), 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'float', 'minValue'=>'-180', 'maxValue'=>'180', 'maxLength'=>20), 'htmlOptions'=>array('maxLength'=>20, 'id'=>'edit-listing-longitude'));
        if($fieldMap) $fields['separatorAddress']['region_latitude'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Location Latitude'), 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'float', 'minValue'=>'-90', 'maxValue'=>'90', 'maxLength'=>20), 'htmlOptions'=>array('maxLength'=>20, 'id'=>'edit-listing-latitude'));

        $fields['separatorContact'] = array();
        $fields['separatorContact']['separatorInfo'] = array('legend'=>A::t('directory', 'Contact Information'));
        if($fieldEmail) $fields['separatorContact']['business_email'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Email'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'email', 'maxLength'=>75), 'htmlOptions'=>array('maxLength'=>75, 'id'=>'edit-listing-email'));
        if($fieldPhone) $fields['separatorContact']['business_phone'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Phone'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'phone', 'maxLength'=>32), 'htmlOptions'=>array('maxLength'=>32, 'id'=>'edit-listing-phone'));
        if($fieldFax) $fields['separatorContact']['business_fax'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Fax'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'phone', 'maxLength'=>32), 'htmlOptions'=>array('maxLength'=>32, 'id'=>'edit-listing-fax'));
        if(count($fields['separatorContact']) == 1) unset($fields['separatorContact']);

        $fields['separatorPersonal'] = array();
        $fields['separatorPersonal']['separatorInfo'] = array('legend'=>A::t('directory', 'Personal Information'));
        if($fieldWebsite) $fields['separatorPersonal']['website_url'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Website'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'url', 'maxLength'=>75), 'htmlOptions'=>array('maxLength'=>75, 'id'=>'edit-listing-website'));
        if($fieldVideo) $fields['separatorPersonal']['video_url'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Video Link'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'url', 'maxLength'=>75), 'htmlOptions'=>array('maxLength'=>75, 'id'=>'edit-listing-video'));
        if(count($fields['separatorPersonal']) == 1) unset($fields['separatorPersonal']);

        $fields['separatorImages'] = array();
        $fields['separatorImages']['separatorInfo'] = array('legend'=>A::t('directory', 'Images'));
        if($fieldLogo) $fields['separatorImages']['image_file'] = array(
            'type'              => 'imageUpload',
            'title'             => A::t('directory', 'Image'),
            'default'=>'',
            'validation'        => array('required'=>false, 'type'=>'image', 'targetPath'=>'images/modules/directory/listings/', 'maxSize'=>'2M', 'mimeType'=>'image/jpeg, image/png, image/gif', 'fileName'=>CHash::getRandomString(15), 'fileNameCase'=>'lower', 'maxWidth'=>'1024px', 'maxHeight'=>'1024px'),
            'thumbnailOptions'  => array('create'=>true, 'field'=>'image_file_thumb', 'width'=>'150px', 'directory'=>'thumbs/'),
            'imageOptions'      => array('showImage'=>true, 'showImageName'=>true, 'showImageSize'=>true, 'imageClass'=>'icon-listing'),
            'deleteOptions'     => array('showLink'=>true, 'linkUrl'=>'listings/editListing/id/'.$id.'/typeTab/'.$typeTab.'/deliteImage/image', 'linkText'=>'Delete'),
            'fileOptions'       => array('showAlways'=>false, 'class'=>'file', 'size'=>'28', 'filePath'=>'images/modules/directory/listings/'),
        );

        if($fieldImagesCount >= 1) $fields['separatorImages']['image_1'] = array(
            'type'              => 'imageUpload',
            'title'             => A::t('directory', 'Image 2'),
            'default'=>'',
            'validation'        => array('required'=>false, 'type'=>'image', 'targetPath'=>'images/modules/directory/listings/', 'maxSize'=>'2M', 'mimeType'=>'image/jpeg, image/png, image/gif', 'fileName'=>CHash::getRandomString(15), 'fileNameCase'=>'lower', 'fileNameCase'=>'lower', 'maxWidth'=>'1024px', 'maxHeight'=>'1024px'),
            'thumbnailOptions'  => array('create'=>true, 'field'=>'image_1_thumb', 'height'=>'50px', 'directory'=>'thumbs/'),
            'imageOptions'      => array('showImage'=>true, 'showImageName'=>true, 'showImageSize'=>true, 'imageClass'=>'icon-listing'),
            'deleteOptions'     => array('showLink'=>true, 'linkUrl'=>'listings/editListing/id/'.$id.'/typeTab/'.$typeTab.'/deliteImage/image2', 'linkText'=>'Delete'),
            'fileOptions'       => array('showAlways'=>false, 'class'=>'file', 'size'=>'28', 'filePath'=>'images/modules/directory/listings/'),
        );
        if($fieldImagesCount >= 2) $fields['separatorImages']['image_2'] = array(
            'type'              => 'imageUpload',
            'title'             => A::t('directory', 'Image 3'),
            'default'=>'',
            'validation'        => array('required'=>false, 'type'=>'image', 'targetPath'=>'images/modules/directory/listings/', 'maxSize'=>'2M', 'mimeType'=>'image/jpeg, image/png, image/gif', 'fileName'=>CHash::getRandomString(15), 'fileNameCase'=>'lower', 'fileNameCase'=>'lower', 'maxWidth'=>'1024px', 'maxHeight'=>'1024px'),
            'thumbnailOptions'  => array('create'=>true, 'field'=>'image_2_thumb', 'height'=>'50px', 'directory'=>'thumbs/'),
            'imageOptions'      => array('showImage'=>true, 'showImageName'=>true, 'showImageSize'=>true, 'imageClass'=>'icon-listing'),
            'deleteOptions'     => array('showLink'=>true, 'linkUrl'=>'listings/editListing/id/'.$id.'/typeTab/'.$typeTab.'/deliteImage/image3', 'linkText'=>'Delete'),
            'fileOptions'       => array('showAlways'=>false, 'class'=>'file', 'size'=>'28', 'filePath'=>'images/modules/directory/listings/'),
        );
        if($fieldImagesCount >= 3) $fields['separatorImages']['image_3'] = array(
            'type'              => 'imageUpload',
            'title'             => A::t('directory', 'Image 4'),
            'default'=>'',
            'validation'        => array('required'=>false, 'type'=>'image', 'targetPath'=>'images/modules/directory/listings/', 'maxSize'=>'2M', 'mimeType'=>'image/jpeg, image/png, image/gif', 'fileName'=>CHash::getRandomString(15), 'fileNameCase'=>'lower', 'fileNameCase'=>'lower', 'maxWidth'=>'1024px', 'maxHeight'=>'1024px'),
            'thumbnailOptions'  => array('create'=>true, 'field'=>'image_3_thumb', 'height'=>'50px', 'directory'=>'thumbs/'),
            'imageOptions'      => array('showImage'=>true, 'showImageName'=>true, 'showImageSize'=>true, 'imageClass'=>'icon-listing'),
            'deleteOptions'     => array('showLink'=>true, 'linkUrl'=>'listings/editListing/id/'.$id.'/typeTab/'.$typeTab.'/deliteImage/image4', 'linkText'=>'Delete'),
            'fileOptions'       => array('showAlways'=>false, 'class'=>'file', 'size'=>'28', 'filePath'=>'images/modules/directory/listings/'),
        );
        if(count($fields['separatorImages']) == 1) unset($fields['separatorImages']);

        $fields['separatorConfiguration'] = array();
        $fields['separatorConfiguration']['separatorInfo']      = array('legend'=>A::t('directory', 'Other Settings'));
        $fields['separatorConfiguration']['is_published']       = array('type'=>'checkbox', 'title'=>A::t('directory', 'Published'), 'default'=>1, 'validation'=>array('type'=>'set', 'source'=>array(0, 1)));
        if($fieldKeywordsCount > 0) $fields['separatorConfiguration']['keywords'] = array('type'=>'textarea', 'title'=>A::t('directory', 'Keywords'), 'default'=>'', 'validation'=>array('type'=>'text', 'required'=>false, 'maxLength'=>1024), 'htmlOptions'=>array('maxLength'=>1024));
        $fields['separatorConfiguration']['customer_id']        = array('type'=>'data', 'default'=>$customerId);
        $fields['separatorConfiguration']['is_approved']        = array('type'=>'data', 'default'=>$approvel);
        $fields['separatorConfiguration']['finish_publishing']  = array('type'=>'data', 'default'=>$finishPublished);
        $fields['separatorConfiguration']['date_published']     = array('type'=>'data', 'default'=>$datePublished);

        $translationFields = array('business_name' => array('type'=>'textbox', 'title'=>A::t('directory', 'Name'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>125), 'htmlOptions'=>array('class' => 'middle', 'maxLength'=>125, 'id'=>'edit-listing-name')));
        $translationFields['business_description'] = array('type'=>'textarea', 'title'=>A::t('directory', 'Description'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>1024), 'htmlOptions'=>array('maxLength'=>1024, 'style'=>($fieldDescription ? '' : 'display:none;'), 'id'=>'edit-listing-description'));

        echo $actionMessage;

        CWidget::create('CDataForm', array(
            'model'             => 'Listings',
            'operationType'     => 'edit',
            'primaryKey'        => $id,
            'action'            => 'listings/editListing/id/'.$id.($typeTab ? '/typeTab/'.$typeTab : ''),
            'successUrl'        => 'listings/myListings'.($typeTab ? '/typeTab/'.$typeTab : ''),
            'cancelUrl'         => 'listings/myListings'.($typeTab ? '/typeTab/'.$typeTab : ''),
            'passParameters'    => false,
            'method'            => 'post',
            'htmlOptions'       => array(
                'id'                => 'edit-listing-form',
                'name'              => 'edit-listing-form',
                'enctype'           => 'multipart/form-data',
                'class'             => 'listing-form',
                'autoGenerateId'    => true
            ),
            'requiredFieldsAlert'   => false,
            'fields'                => $fields,
            'translationInfo'       => array('relation'=>array('id', 'listing_id'), 'languages'=>Languages::model()->findAll('is_active = 1')),
            'translationFields'     => $translationFields,
            'buttons'           => array(
               'submitUpdateClose' => array('type'=>'submit', 'value'=>A::t('directory', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose', 'class'=>'button')),
               'submitUpdate'      => array('type'=>'submit', 'value'=>A::t('directory', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate', 'class'=>'button')),
               'cancel'            => array('type'=>'button', 'value'=>A::t('directory', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
            'messagesSource'    => 'core',
            'showAllErrors'     => false,
            'alerts'            => array('type'=>'flash'),
            'return'            => false,
        ));
        ?>
    </div>
</article>
