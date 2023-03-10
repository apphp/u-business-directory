<?php
    Website::setMetaTags(array('title'=>A::t('directory', 'Site Info Management')));

    $this->_activeMenu = 'siteInfoFrontend/siteinfo';
    $this->_breadCrumbs = array(
        array('label'=>A::t('directory', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('directory', 'Business Directory'), 'url'=>'modules/settings/code/directory'),
        array('label'=>A::t('directory', 'Site Info Management')),
    );

    $spinnersCount = 0;
?>

<h1><?php echo A::t('directory', 'Site Info Management'); ?></h1>

<div class="bloc">
    <?php echo $tabs; ?>

    <div class="content">
    <?php
        echo $actionMessage;

        echo CWidget::create('CDataForm', array(
            'model'=>'SiteInfoFrontend',
            'primaryKey'=>$id,
            'operationType'=>'edit',
            'action'=>'siteInfoFrontend/siteinfo',
            'successUrl'=>'siteInfoFrontend/siteinfo',
            'cancelUrl'=>'siteInfoFrontend/siteinfo',
            'passParameters'=>false,
            'method'=>'post',
            'htmlOptions'=>array(
                'id'     =>'frmSiteInfoEdit',
                'name'   =>'frmSiteInfoEdit',
                'enctype'=>'multipart/form-data',
                'autoGenerateId'=>true
            ),
            'requiredFieldsAlert'=>true,
            'fields'=>array(
                'logo' => array(
                    'type'              => 'imageUpload',
                    'title'             => A::t('directory', 'Logo'),
                    'tooltip'           => '',
                    'default'           => 'no_logo.png',
                    'validation'        => array('required'=>false, 'type'=>'image', 'targetPath'=>'images/modules/directory/siteinfo/', 'maxSize'=>'1M', 'maxWidth'=>'450px', 'maxHeight'=>'100px', 'mimeType'=>'image/jpeg, image/png, image/gif', 'fileName'=>'logo_'.CHash::getRandomString(5), 'filePrefix'=>'', 'filePostfix'=>'', 'htmlOptions'=>array()),
                    'imageOptions'      => array('showImage'=>true, 'showImageName'=>true, 'showImageSize'=>true, 'imageClass'=>''),
                    'thumbnailOptions'  => array('create'=>false, 'directory'=>'', 'field'=>'', 'postfix'=>'_thumb', 'width'=>'', 'height'=>''),
                    'deleteOptions'     => array('showLink'=>true, 'linkUrl'=>'siteInfoFrontend/siteinfo/logo/delete', 'linkText'=>A::t('directory', 'Delete')),
                    'fileOptions'       => array('showAlways'=>false, 'class'=>'file', 'size'=>'25', 'filePath'=>'images/modules/directory/siteinfo/')
                ),
                'favicon' => array(
                    'type'              => 'imageUpload',
                    'title'             => A::t('directory', 'Favicon'),
                    'tooltip'           => '',
                    'default'           => '',
                    'validation'        => array('required'=>false, 'type'=>'image', 'targetPath'=>'images/modules/directory/siteinfo/', 'maxSize'=>'100k', 'maxWidth'=>'32px', 'maxHeight'=>'32px', 'mimeType'=>'image/jpeg, image/png, image/gif', 'fileName'=>'favicon_'.CHash::getRandomString(5), 'filePrefix'=>'', 'filePostfix'=>'', 'htmlOptions'=>array()),
                    'imageOptions'      => array('showImage'=>true, 'showImageName'=>true, 'showImageSize'=>true, 'imageClass'=>''),
                    'thumbnailOptions'  => array('create'=>false, 'directory'=>'', 'field'=>'', 'postfix'=>'_thumb', 'width'=>'', 'height'=>''),
                    'deleteOptions'     => array('showLink'=>true, 'linkUrl'=>'siteInfoFrontend/siteinfo/favicon/delete', 'linkText'=>A::t('directory', 'Delete')),
                    'fileOptions'       => array('showAlways'=>false, 'class'=>'file', 'size'=>'25', 'filePath'=>'images/modules/directory/siteinfo/')
                ),
                'email'   => array('type'=>'textbox', 'title'=>A::t('directory', 'Email'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>70), 'htmlOptions'=>array('maxLength'=>70)),
                'email_visible' => array('type'=>'checkbox', 'title'=>A::t('directory', 'Email visible'), 'tooltip'=>A::t('directory', 'Off - show on Frontend, No - hide from Frontend'), 'default'=>'1', 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'viewType'=>'custom'),
                'phone'   => array('type'=>'textbox', 'title'=>A::t('directory', 'Phone'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'phone', 'maxLength'=>30), 'htmlOptions'=>array('maxLength'=>30)),
                'phone_visible' => array('type'=>'checkbox', 'title'=>A::t('directory', 'Phone visible'), 'tooltip'=>A::t('directory', 'Off - show on Frontend, No - hide from Frontend'), 'default'=>'1', 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'viewType'=>'custom'),
                'custom_text_name' => array('type'=>'textbox', 'title'=>A::t('directory', 'Custom Text (Name)'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>50), 'htmlOptions'=>array('maxLength'=>50)),
                'custom_text_description' => array('type'=>'textarea', 'title'=>A::t('directory', 'Custom Text (Description)'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>2048), 'htmlOptions'=>array('maxLength'=>2048))
            ),
            'translationInfo'       => array('relation'=>array('id', 'listing_id'), 'languages'=>Languages::model()->findAll('is_active = 1')),
            'translationFields' => array(),
            'buttons'=>array(
                'submitUpdate' => array('type'=>'submit', 'value'=>A::t('directory', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
                'cancel' => array('type'=>'button', 'value'=>A::t('directory', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
            'messagesSource'=>'core',
            //'alerts'=>array('type'=>'flash'),
            'return'=>true,
        ));
    ?>
    </div>
</div>
