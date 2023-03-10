<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Add Social Network')));

    $this->_activeMenu = 'social/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Social Settings'), 'url'=>'social/social'),
        array('label'=>A::t('app', 'Social Networks Management'), 'url'=>'social/social'),
        array('label'=>A::t('app', 'Add Social Network'))
    );

    $spinnersCount = 0;
?>

<h1><?php echo A::t('app', 'Add Social Network'); ?></h1>

<div class="bloc">
    <?php echo $tabs; ?>

    <div class="content">
    <?php
        echo $actionMessage;

        echo CWidget::create('CDataForm', array(
            'model'             => 'SocialNetworks',
            'operationType'     => 'add',
            'action'            => 'social/socialAdd',
            'successUrl'        => 'social/social',
            'cancelUrl'         => 'social/social',
            'passParameters'    => false,
            'method'            => 'post',
            'htmlOptions'       => array(
                'id'                => 'frmSocialAdd',
                'name'              => 'frmSocialAdd',
                'enctype'           => 'multipart/form-data',
                'autoGenerateId'    => true
            ),
            'requiredFieldsAlert'   => true,
            'fields'                => array(
                'icon' => array(
                    'type'              => 'imageUpload',
                    'title'             => A::t('app', 'Icon'),
                    'default'=>'',
                    'validation'        => array('required'=>false, 'type'=>'image', 'targetPath'=>'images/social_settings/social_networks/', 'maxSize'=>'990k', 'mimeType'=>'image/jpeg, image/png, image/gif', 'fileName'=>'sn_'.CHash::getRandomString(10), 'maxWidth'=>'64px', 'maxHeight'=>'64px'),
                    'imageOptions'      => array('showImage'=>false, 'showImageName'=>true, 'imageClass'=>'icon-listing'),
                    'thumbnailOptions'  => array('create'=>false),
                    'fileOptions'       => array('showAlways'=>false, 'class'=>'file', 'size'=>'25', 'filePath'=>'images/social_settings/social_networks/')
                ),
                'name'       => array('type'=>'textbox', 'title'=>A::t('app', 'Name'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>50), 'htmlOptions'=>array('class'=>'middle', 'maxLength'=>50)),
                'link'       => array('type'=>'textbox', 'title'=>A::t('app', 'Link'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'url', 'maxLength'=>255), 'htmlOptions'=>array('class'=>'middle', 'maxLength'=>255)),
                'sort_order' => array('type'=>'textbox', 'title'=>A::t('app', 'Sort Order'), 'default'=>0, 'tooltip'=>'', 'validation'=>array('required'=>false, 'maxLength'=>1, 'type'=>'numeric'), 'htmlOptions'=>array('maxLength'=>1, 'class'=>'small')),
                'is_published' => array('type'=>'checkbox', 'title'=>A::t('app', 'Public'), 'default'=>1, 'validation'=>array('type'=>'set', 'source'=>array(0, 1)), 'data'=>array(0 => A::t('app', 'No'), 1 => A::t('app', 'Yes'))),
                'site_id'    => array('type'=>'data', 'default'=>'1'),
            ),
            'translationInfo'       => array(),
            'translationFields'     => array(),
            'buttons'           => array(
               'submit' => array('type'=>'submit', 'value'=>A::t('app', 'Create'), 'htmlOptions'=>array('name'=>'')),
               'cancel' => array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
            'messagesSource'    => 'core',
            'alerts'            => array('type'=>'flash'),
            'return'            => true,
        ));
    ?>
    </div>
</div>