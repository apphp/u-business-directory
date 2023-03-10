<?php
    $this->_pageTitle = A::t('directory', 'Create Listing');
    $this->_activeMenu = 'listings/myListings';
    A::app()->getClientScript()->registerScriptFile('js/modules/directory/directory.js', 0);
?>
<article id="page-add-listing" class="page-add-listing type-page status-publish hentry">
    <header class="entry-header">
        <h1 class="entry-title">
            <span><?php echo A::t('directory', 'Create Listing'); ?></span>
        </h1>
        <?php
            $breadCrumbLinks = array(array('label'=> A::t('directory', 'Home'), 'url'=>Website::getDefaultPage()));
            $breadCrumbLinks[] = array('label'=> A::t('directory', 'Dashboard'), 'url'=>'customers/dashboard');
            $breadCrumbLinks[] = array('label' => A::t('directory', 'My Listings'), 'url'=>'listings/myListings');
            $breadCrumbLinks[] = array('label' => A::t('directory', 'Create Listing'));
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
        echo CHtml::openForm('listings/addListing#add-listing-advertise-plan','post',array('id'=>'add-listing-advertise-plan', 'name'=>'add-listing-advertise-plan'));
        echo CHtml::openTag('h3',array('class'=>'widget-title'));
        echo CHtml::tag('span',array(), A::t('directory', 'Select Advertising Plan'));
        echo CHtml::closeTag('h3');
        echo CHtml::openTag('div', array('class'=>'row'));
        echo CHtml::tag('label', array(), A::t('directory', 'Advertise Plan'));
        echo CHtml::dropDownList('advertise_plan_id', $defaultPlan, $allPlans, array('onchange'=>'this.form.submit()'));
        echo CHtml::closeTag('div');
        echo CHtml::closeForm();

        $fields = array();
        $fields['advertise_plan_id'] = array('type'=>'hidden', 'default'=>$defaultPlan);

        $onchange = "regions_ChangeSubRegions('add-listing-form',this.value,'')";
        $fields['separatorAddress'] = array();
        $fields['separatorAddress']['separatorInfo'] = array('legend'=>A::t('directory', 'Address Information'));
        $fields['separatorAddress']['region_id']     = array('type'=>'select', 'title'=>A::t('directory', 'Location'), 'default'=>0, 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($allRegions)), 'data'=>$allRegions, 'emptyOption'=>false, 'viewType'=>'dropdownlist', 'htmlOptions'=>array('onchange'=>$onchange, 'id'=>'add-listing-region'));
        $fields['separatorAddress']['subregion_id']  = array('type'=>'select', 'title'=>A::t('directory', 'Sub-Location'), 'default'=>0, 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($allSubRegions)), 'data'=>$allSubRegions, 'emptyOption'=>false, 'viewType'=>'dropdownlist', 'htmlOptions'=>array('id'=>'add-listing-subreion'));
        if($fieldAddress) $fields['separatorAddress']['business_address'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Address'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>255), 'appendCode'=>'<a href="javascript:void(0)" onclick="javascript:listings_FindCoordinates(\'add-listing-form\')">'.A::t('directory', 'Find Longitude/Latitude').'</a>');
        if($fieldMap) $fields['separatorAddress']['region_longitude'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Location Longitude'), 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'float', 'minValue'=>'-180', 'maxValue'=>'180', 'maxLength'=>20), 'htmlOptions'=>array('maxLength'=>20, 'id'=>'add-listing-longitude'));
        if($fieldMap) $fields['separatorAddress']['region_latitude'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Location Latitude'), 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'float', 'minValue'=>'-90', 'maxValue'=>'90', 'maxLength'=>20), 'htmlOptions'=>array('maxLength'=>20, 'id'=>'add-listing-latitude'));

        $fields['separatorContact'] = array();
        $fields['separatorContact']['separatorInfo'] = array('legend'=>A::t('directory', 'Contact Information'));
        if($fieldEmail) $fields['separatorContact']['business_email'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Email'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'email', 'maxLength'=>75), 'htmlOptions'=>array('maxLength'=>75, 'id'=>'add-listing-email'));
        if($fieldPhone) $fields['separatorContact']['business_phone'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Phone'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'numeric', 'maxLength'=>32), 'htmlOptions'=>array('maxLength'=>32, 'id'=>'add-listing-phone'));
        if($fieldFax) $fields['separatorContact']['business_fax'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Fax'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'numeric', 'maxLength'=>32), 'htmlOptions'=>array('maxLength'=>32, 'id'=>'add-listing-fax'));
        if(count($fields['separatorContact']) == 1) unset($fields['separatorContact']);

        $fields['separatorPersonal'] = array();
        $fields['separatorPersonal']['separatorInfo'] = array('legend'=>A::t('directory', 'Personal Information'));
        if($fieldWebsite) $fields['separatorPersonal']['website_url'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Website'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'url', 'maxLength'=>75), 'htmlOptions'=>array('maxLength'=>75, 'id'=>'add-listing-website'));
        if($fieldVideo) $fields['separatorPersonal']['video_url'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Video Link'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'url', 'maxLength'=>75), 'htmlOptions'=>array('maxLength'=>75, 'id'=>'add-listing-video'));
        if(count($fields['separatorPersonal']) == 1) unset($fields['separatorPersonal']);

        $fields['separatorImages'] = array();
        $fields['separatorImages']['separatorInfo'] = array('legend'=>A::t('directory', 'Images'));
        if($fieldLogo) $fields['separatorImages']['image_file'] = array(
            'type'              => 'imageUpload',
            'title'             => A::t('directory', 'Image'),
            'default'=>'',
            'validation'        => array('required'=>false, 'type'=>'image', 'targetPath'=>'images/modules/directory/listings/', 'maxSize'=>'2M', 'mimeType'=>'image/jpeg, image/png, image/gif', 'fileName'=>CHash::getRandomString(15), 'fileNameCase'=>'lower', 'maxWidth'=>'1024px', 'maxHeight'=>'1024px'),
            'imageOptions'      => array('showImage'=>false, 'showImageName'=>true, 'imageClass'=>'image-logo'),
            'thumbnailOptions'  => array('create'=>true, 'field'=>'image_file_thumb', 'width'=>'150px', 'directory'=>'thumbs/'),
        );

        if($fieldImagesCount >= 1) $fields['separatorImages']['image_1'] = array(
            'type'              => 'imageUpload',
            'title'             => A::t('directory', 'Image 2'),
            'default'=>'',
            'validation'        => array('required'=>false, 'type'=>'image', 'targetPath'=>'images/modules/directory/listings/', 'maxSize'=>'2M', 'mimeType'=>'image/jpeg, image/png, image/gif', 'fileName'=>CHash::getRandomString(15), 'fileNameCase'=>'lower', 'fileNameCase'=>'lower', 'maxWidth'=>'1024px', 'maxHeight'=>'1024px'),
            'imageOptions'      => array('showImage'=>false, 'showImageName'=>true, 'imageClass'=>'image-1'),
            'thumbnailOptions'  => array('create'=>true, 'field'=>'image_1_thumb', 'height'=>'50px', 'directory'=>'thumbs/'),
        );
        if($fieldImagesCount >= 2) $fields['separatorImages']['image_2'] = array(
            'type'              => 'imageUpload',
            'title'             => A::t('directory', 'Image 3'),
            'default'=>'',
            'validation'        => array('required'=>false, 'type'=>'image', 'targetPath'=>'images/modules/directory/listings/', 'maxSize'=>'2M', 'mimeType'=>'image/jpeg, image/png, image/gif', 'fileName'=>CHash::getRandomString(15), 'fileNameCase'=>'lower', 'fileNameCase'=>'lower', 'maxWidth'=>'1024px', 'maxHeight'=>'1024px'),
            'imageOptions'      => array('showImage'=>false, 'showImageName'=>true, 'imageClass'=>'image-2'),
            'thumbnailOptions'  => array('create'=>true, 'field'=>'image_2_thumb', 'height'=>'50px', 'directory'=>'thumbs/'),
        );
        if($fieldImagesCount >= 3) $fields['separatorImages']['image_3'] = array(
            'type'              => 'imageUpload',
            'title'             => A::t('directory', 'Image 4'),
            'default'=>'',
            'validation'        => array('required'=>false, 'type'=>'image', 'targetPath'=>'images/modules/directory/listings/', 'maxSize'=>'2M', 'mimeType'=>'image/jpeg, image/png, image/gif', 'fileName'=>CHash::getRandomString(15), 'fileNameCase'=>'lower', 'fileNameCase'=>'lower', 'maxWidth'=>'1024px', 'maxHeight'=>'1024px'),
            'imageOptions'      => array('showImage'=>false, 'showImageName'=>true, 'imageClass'=>'image-3'),
            'thumbnailOptions'  => array('create'=>true, 'field'=>'image_3_thumb', 'height'=>'50px', 'directory'=>'thumbs/'),
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

        $translationFields = array('business_name' => array('type'=>'textbox', 'title'=>A::t('directory', 'Name'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>125), 'htmlOptions'=>array('class' => 'middle', 'maxLength'=>125, 'id'=>'add-listing-name')));
        $translationFields['business_description'] = array('type'=>'textarea', 'title'=>A::t('directory', 'Description'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>1024), 'htmlOptions'=>array('maxLength'=>1024, 'style'=>($fieldDescription ? '' : 'display:none;'), 'id'=>'add-listing-description'));

        echo $actionMessage;

        CWidget::create('CDataForm', array(
            'model'             => 'Listings',
            'operationType'     => 'add',
            'action'            => 'listings/addListing',
            'successUrl'        => !empty($price) ? 'listings/myListings/'.($typeTab ? '/type/'.$typeTab : '') : 'listings/order/price/'.$price,
            'cancelUrl'         => 'listings/myListings'.($typeTab ? '/type/'.$typeTab : ''),
            'passParameters'    => false,
            'method'            => 'post',
            'htmlOptions'       => array(
                'id'                => 'add-listing-form',
                'name'              => 'add-listing-form',
                'enctype'           => 'multipart/form-data',
                'class'             => 'listing-form',
            ),
            'requiredFieldsAlert'   => false,
            'fields'                => $fields,
            'translationInfo'       => array('relation'=>array('id', 'listing_id'), 'languages'=>Languages::model()->findAll('is_active = 1')),
            'translationFields'     => $translationFields,
            'buttons'           => array(
               'submit' => array('type'=>'submit', 'value'=>A::t('directory', 'Create'), 'htmlOptions'=>array('name'=>'', 'class'=>'button')),
               'cancel' => array('type'=>'button', 'value'=>A::t('directory', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
            'messagesSource'    => 'core',
            'showAllErrors'     => false,
            'alerts'            => array('type'=>'flash'),
            'return'            => false,
        ));
        ?>
    </div>
</article>
