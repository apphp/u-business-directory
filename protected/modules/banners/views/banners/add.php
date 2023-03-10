<?php
    Website::setMetaTags(array('title'=>A::t('banners', 'Add Banner')));

    $this->_activeMenu = 'modules/settings/code/banners';
    $this->_breadCrumbs = array(
        array('label'=>A::t('banners', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('banners', 'Banners'), 'url'=>'modules/settings/code/banners'),
        array('label'=>A::t('banners', 'Banners Management'), 'url'=>'banners/manage'),
        array('label'=>A::t('banners', 'Add Banner')),
    );
?>

<h1><?php echo A::t('banners', 'Banners Management'); ?></h1>

<div class="bloc">
    <?php echo $tabs; ?>

    <div class="sub-title"><?php echo A::t('banners', 'Add Banner'); ?></div>
    <div class="content">
    <?php
        echo CWidget::create('CDataForm', array(
            'model'=>'Banners',
            'operationType'=>'add',
            'action'=>'banners/add/',
            'successUrl'=>'banners/manage/msg/added',
            'cancelUrl'=>'banners/manage/',
            'passParameters'=>false,
            'method'=>'post',
            'htmlOptions'=>array(
                'name'=>'frmBannerAdd',
                'enctype'=>'multipart/form-data',
                'autoGenerateId'=>true
            ),
            'requiredFieldsAlert'=>true,
            'fields'=>array(
                'image_file' => array(
                    'type'          => 'imageupload',
                    'title'         => A::t('banners', 'Image'),
                    'validation'    => array('required'=>true, 'type'=>'image', 'maxSize'=>'900k', 'targetPath'=>'images/modules/banners/', 'mimeType'=>'image/jpeg, image/gif, image/png', 'fileName'=>'a'.CHash::getRandomString(10)),
                    'imageOptions'  => array('showImage'=>false),
                    'thumbnailOptions' => array('create'=>true, 'field'=>'image_file_thumb', 'width'=>'120', 'height'=>'90'),
                    'deleteOptions' => array('showLink'=>false),
                    'fileOptions'   => array('showAlways'=>false, 'class'=>'file', 'size'=>'25')
                ),
                'link_url'   => array('type'=>'textbox', 'title'=>A::t('banners', 'Link'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'link'), 'htmlOptions'=>array('maxlength'=>'255')),
                'sort_order' => array('type'=>'textbox', 'title'=>A::t('banners', 'Sort Order'), 'tooltip'=>'', 'default'=>'0', 'validation'=>array('required'=>true, 'type'=>'numeric'), 'htmlOptions'=>array('maxlength'=>'4', 'class'=>'small')),
                'is_active'  => array('type'=>'checkbox', 'title'=>A::t('banners', 'Active'), 'tooltip'=>'', 'default'=>'1', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array()),
            ),
            'translationInfo' => array('relation'=>array('id', 'banner_id'), 'languages'=>Languages::model()->findAll('is_active = 1')),
            'translationFields' => array(
                'banner_text' => array('type'=>'textarea', 'title'=>A::t('banners', 'Description'), 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>1024), 'htmlOptions'=>array('maxLength'=>'1024')),
            ),
            'buttons'=>array(
               'submit'=>array('type'=>'submit', 'value'=>A::t('banners', 'Create'), 'htmlOptions'=>array('name'=>'')),
               'cancel'=>array('type'=>'button', 'value'=>A::t('banners', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
            'messagesSource'=>'core',
            'showAllErrors'=>false,
            'return'=>true,
        ));
    ?>
    </div>
</div>
