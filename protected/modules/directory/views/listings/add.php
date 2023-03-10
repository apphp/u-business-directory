<?php
    $this->_activeMenu = 'listings/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('directory', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('directory', 'Business Directory'), 'url'=>'modules/settings/code/directory'),
        array('label'=>A::t('directory', 'Listings Management'), 'url'=>'listings/manage'),
        array('label'=>A::t('directory', 'Add Listing')),
    );

    A::app()->getClientScript()->registerScriptFile('js/modules/directory/directory.js');
?>

<h1><?php echo A::t('directory', 'Listings Management'); ?></h1>

<div class="bloc">
    <?php
        echo $tabs;
    ?>
    <div class="sub-title">
    <?php
        echo '<a class="sub-tab '.($typeTab != 'approved' && $typeTab != 'expired' && $typeTab != 'pending' ? 'active' : 'previous').'" href="listings/manage">'.A::t('directory', 'All').'</a>';
        echo '<a class="sub-tab '.($typeTab == 'pending' ? 'active' : 'previous').'" href="listings/manage/type/pending">'.A::t('directory', 'Pending').'</a>';
        echo '<a class="sub-tab '.($typeTab == 'approved' ? 'active' : 'previous').'" href="listings/manage/type/approved">'.A::t('directory', 'Approved').'</a>';
        echo '<a class="sub-tab '.($typeTab == 'expired' ? 'active' : 'previous').'" href="listings/manage/type/expired">'.A::t('directory', 'Expired').'</a>';
        echo A::t('directory', 'Add Listing');
    ?>
    </div>
    <?php
        $onchange = "addListingChangeSubRegions(this.value,'')";

        $fields = array();
        $fields['separatorContact'] = array();
        $fields['separatorContact']['separatorInfo']  = array('legend'=>A::t('directory', 'Contact Information'));
        $fields['separatorContact']['business_email'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Email'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'email', 'maxLength'=>75), 'htmlOptions'=>array('class'=>'middle', 'maxLength'=>75));
        $fields['separatorContact']['business_phone'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Phone'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'numeric', 'maxLength'=>32), 'htmlOptions'=>array('maxLength'=>32));
        $fields['separatorContact']['business_fax']   = array('type'=>'textbox', 'title'=>A::t('directory', 'Fax'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'numeric', 'maxLength'=>32), 'htmlOptions'=>array('maxLength'=>32));


        $fields['separatorPersonal'] = array();
        $fields['separatorPersonal']['separatorInfo'] = array('legend'=>A::t('directory', 'Personal Information'));
        $fields['separatorPersonal']['website_url']   = array('type'=>'textbox', 'title'=>A::t('directory', 'Website'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'url', 'maxLength'=>75), 'htmlOptions'=>array('class'=>'middle', 'maxLength'=>75));
        $fields['separatorPersonal']['video_url']     = array('type'=>'textbox', 'title'=>A::t('directory', 'Video Link'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'url', 'maxLength'=>75), 'htmlOptions'=>array('class'=>'middle', 'maxLength'=>75));


        $fields['separatorAddress'] = array();
        $fields['separatorAddress']['separatorInfo']    = array('legend'=>A::t('directory', 'Address Information'));
        $fields['separatorAddress']['region_id']        = array('type'=>'select', 'title'=>A::t('directory', 'Location'), 'tooltip'=>'', 'default'=>$region, 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($regionNames)), 'data'=>$regionNames, 'viewType'=>'dropdownlist', 'htmlOptions'=>array('onchange'=>$onchange, 'id'=>'listing-region-add'));
        $fields['separatorAddress']['subregion_id']     = array('type'=>'select', 'title'=>A::t('directory', 'Sub-Location'), 'default'=>$subregion, 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($subRegionNames)), 'data'=>$subRegionNames, 'viewType'=>'dropdownlist','htmlOptions'=>array('id'=>'listing-subregion-add'));
        $fields['separatorAddress']['business_address'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Address'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>255), 'htmlOptions'=>array('class'=>'middle', 'maxLength'=>255), 'appendCode'=>' [ <a href="javascript:void(0)" onclick="javascript:listings_FindCoordinates(\'frmListingAdd\')">'.A::t('directory', 'Find Longitude/Latitude').'</a> ]');
        $fields['separatorAddress']['region_longitude'] = array('type'=>'textbox', 'title'=>A::t('directory', 'Location Longitude'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'float', 'minValue'=>'', 'maxValue'=>'', 'maxLength'=>20), 'htmlOptions'=>array('class'=>'normal', 'maxLength'=>20));
        $fields['separatorAddress']['region_latitude']  = array('type'=>'textbox', 'title'=>A::t('directory', 'Location Latitude'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'float', 'maxLength'=>20), 'htmlOptions'=>array('class'=>'normal', 'maxLength'=>20));

        $fields['separatorConfiguration'] = array();
        $fields['separatorConfiguration']['separatorInfo']      = array('legend'=>A::t('directory', 'Configuration Information'));
        //$fields['separatorConfiguration']['customer_id']        = array('type'=>'select', 'title'=>A::t('directory', 'Customer'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($customerFullNames)), 'data'=>$customerFullNames, 'emptyOption'=>true, 'viewType'=>'dropdownlist', 'htmlOptions'=>array('class'=>'chosen-select-filter'));
        $fields['separatorConfiguration']['customer_id']        = array('type'=>'textbox', 'title'=>A::t('directory', 'Customer'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($customerFullNames)), 'autocomplete'=>array('enable'=>true, 'ajaxHandler'=>'customers/getCustomers', 'minLength'=>'1', 'default'=>'', 'params'=>''), 'htmlOptions'=>array());
        $fields['separatorConfiguration']['keywords']           = array('type'=>'textarea', 'title'=>A::t('directory', 'Keywords'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>1024), 'htmlOptions'=>array('maxLength'=>1024));
        $fields['separatorConfiguration']['advertise_plan_id']  = array('type'=>'select', 'title'=>A::t('directory', 'Advertise Plan'), 'default'=>$advertisePlanDefault, 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($advertisePlanNames)), 'data'=>$advertisePlanNames, 'emptyOption'=>false, 'viewType'=>'dropdownlist', 'htmlOptions'=>array());
        $fields['separatorConfiguration']['is_approved']        = array('type'=>'select', 'title'=>A::t('directory', 'Approved'), 'default'=>3, 'validation'=>array('type'=>'set', 'source'=>array(0, 1, 2)), 'data'=>array(0=>A::t('directory', 'Pending'), 1=>A::t('directory', 'Approved'), 2=>A::t('directory', 'Canceled')),'htmlOptions'=>array());
        $fields['separatorConfiguration']['access_level']       = array('type'=>'select', 'title'=>A::t('directory', 'Access Level'), 'default'=>0, 'validation'=>array('type'=>'set', 'source'=>array(0, 1)), 'data'=>array(0 => A::t('directory', 'Public'), 1 => A::t('directory', 'Registered')),'htmlOptions'=>array());
        $fields['separatorConfiguration']['is_featured']        = array('type'=>'checkbox', 'title'=>A::t('directory', 'Featured'), 'default'=>0, 'validation'=>array('type'=>'set', 'source'=>array(0, 1)));
        $fields['separatorConfiguration']['is_published']       = array('type'=>'checkbox', 'title'=>A::t('directory', 'Published'), 'default'=>1, 'validation'=>array('type'=>'set', 'source'=>array(0, 1)), 'data'=>array(0 => A::t('directory', 'Public'), 1 => A::t('directory', 'Registered')));
        $fields['separatorConfiguration']['sort_order']         = array('type'=>'textbox', 'title'=>A::t('directory', 'Sort Order'), 'default'=>0, 'tooltip'=>'', 'validation'=>array('required'=>false, 'maxLength'=>1, 'type'=>'numeric'), 'htmlOptions'=>array('maxLength'=>1, 'class'=>'small'));

        $fields['separatorImages'] = array();
        $fields['separatorImages']['separatorInfo']     = array('legend'=>A::t('directory', 'Images'));
        $fields['separatorImages']['image_file'] = array(
            'type'              => 'imageUpload',
            'title'             => A::t('directory', 'Image'),
            'default'=>'',
            'validation'        => array('required'=>false, 'type'=>'image', 'targetPath'=>'images/modules/directory/listings/', 'maxSize'=>'2M', 'mimeType'=>'image/jpeg, image/png, image/gif', 'fileName'=>CHash::getRandomString(15), 'fileNameCase'=>'lower', 'maxWidth'=>'1024px', 'maxHeight'=>'1024px'),
            'imageOptions'      => array('showImage'=>false, 'showImageName'=>true, 'imageClass'=>'icon-listing'),
            'thumbnailOptions'  => array('create'=>true, 'field'=>'image_file_thumb', 'width'=>'150px', 'directory'=>'thumbs/'),
        );
        $fields['separatorImages']['image_1'] = array(
            'type'              => 'imageUpload',
            'title'             => A::t('directory', 'Image 2'),
            'default'=>'',
            'validation'        => array('required'=>false, 'type'=>'image', 'targetPath'=>'images/modules/directory/listings/', 'maxSize'=>'2M', 'mimeType'=>'image/jpeg, image/png, image/gif', 'fileName'=>CHash::getRandomString(15), 'fileNameCase'=>'lower', 'fileNameCase'=>'lower', 'maxWidth'=>'1024px', 'maxHeight'=>'1024px'),
            'imageOptions'      => array('showImage'=>false, 'showImageName'=>true, 'imageClass'=>'icon-listing'),
            'thumbnailOptions'  => array('create'=>true, 'field'=>'image_1_thumb', 'height'=>'50px', 'directory'=>'thumbs/'),
        );
        $fields['separatorImages']['image_2'] = array(
            'type'              => 'imageUpload',
            'title'             => A::t('directory', 'Image 3'),
            'default'=>'',
            'validation'        => array('required'=>false, 'type'=>'image', 'targetPath'=>'images/modules/directory/listings/', 'maxSize'=>'2M', 'mimeType'=>'image/jpeg, image/png, image/gif', 'fileName'=>CHash::getRandomString(15), 'fileNameCase'=>'lower', 'fileNameCase'=>'lower', 'maxWidth'=>'1024px', 'maxHeight'=>'1024px'),
            'imageOptions'      => array('showImage'=>false, 'showImageName'=>true, 'imageClass'=>'icon-listing'),
            'thumbnailOptions'  => array('create'=>true, 'field'=>'image_2_thumb', 'height'=>'50px', 'directory'=>'thumbs/'),
        );
        $fields['separatorImages']['image_3'] = array(
            'type'              => 'imageUpload',
            'title'             => A::t('directory', 'Image 4'),
            'default'=>'',
            'validation'        => array('required'=>false, 'type'=>'image', 'targetPath'=>'images/modules/directory/listings/', 'maxSize'=>'2M', 'mimeType'=>'image/jpeg, image/png, image/gif', 'fileName'=>CHash::getRandomString(15), 'fileNameCase'=>'lower', 'fileNameCase'=>'lower', 'maxWidth'=>'1024px', 'maxHeight'=>'1024px'),
            'imageOptions'      => array('showImage'=>false, 'showImageName'=>true, 'imageClass'=>'icon-listing'),
            'thumbnailOptions'  => array('create'=>true, 'field'=>'image_3_thumb', 'height'=>'50px', 'directory'=>'thumbs/'),
        );

        $translationFields = array('business_name' => array('type'=>'textbox', 'title'=>A::t('directory', 'Name'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>125), 'htmlOptions'=>array('class' => 'middle', 'maxLength'=>125)));
        $translationFields['business_description'] = array('type'=>'textarea', 'title'=>A::t('directory', 'Description'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>1024), 'htmlOptions'=>array('maxLength'=>1024));
    ?>

    <div class="content">
        <?php
            echo $actionMessage;

            CWidget::create('CDataForm', array(
                'model'             => 'Listings',
                'operationType'     => 'add',
                'action'            => 'listings/add',
                'successUrl'        => 'listings/manage/typeTab/'.CHtml::encode($typeTab),
                'cancelUrl'         => 'listings/manage/typeTab/'.CHtml::encode($typeTab),
                'passParameters'    => false,
                'method'            => 'post',
                'htmlOptions'       => array(
                    'id'                => 'frmListingAdd',
                    'name'              => 'frmListingAdd',
                    'enctype'           => 'multipart/form-data',
                    'autoGenerateId'    => true
                ),
                'requiredFieldsAlert'   => true,
                'fields'                => $fields,
                'translationInfo'       => array('relation'=>array('id', 'listing_id'), 'languages'=>Languages::model()->findAll('is_active = 1')),
                'translationFields'     => $translationFields,
                'buttons'           => array(
                   'submit' => array('type'=>'submit', 'value'=>A::t('directory', 'Create'), 'htmlOptions'=>array('name'=>'')),
                   'cancel' => array('type'=>'button', 'value'=>A::t('directory', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
                ),
                'messagesSource'    => 'core',
                'alerts'            => array('type'=>'flash'),
                'return'            => false,
            ));
        ?>

    </div>
</div>

<?php
    A::app()->getClientScript()->registerScript(
        'addListingChangeSubRegions',
        'document.addListingChangeSubRegions = function (region,subregion){
            var ajax = null;
            ajax = regions_ChangeSubRegions("frmListingAdd",region,subregion);
            if(ajax == null){
                $("#listing-subregion-add").chosen("destroy");
                $("#listing-subregion-add").chosen({disable_search_threshold: 10});
            }else{
                ajax.done(function (){
                    $("#listing-subregion-add").chosen("destroy");
                    $("#listing-subregion-add").chosen({disable_search_threshold: 10});
                });
            }
        }
        '
    );
