<?php
    $this->_activeMenu = 'modules/settings/code/directory';
    $breadCrumbs = array(
        array('label'=>A::t('directory', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('directory', 'Business Directory'), 'url'=>'modules/settings/code/directory'),
        array('label'=>A::t('directory', 'Categories Management'), 'url'=>'categories/manage'),
    );
    $lastCategoryName = A::t('directory', 'None');
    foreach($parentCategories as $parentCategory){
        $breadCrumbs[] = array('label'=>$parentCategory['name'], 'url'=>'categories/manage/parentId/'.$parentCategory['id']);
        $lastCategoryName = $parentCategory['name'];
    }
    $breadCrumbs[] =  array('label'=>A::t('directory', 'Edit Category'));
    $this->_breadCrumbs = $breadCrumbs;
?>

<h1><?php echo A::t('directory', 'Categories Management'); ?></h1>

<div class="bloc">
    <?php echo $tabs; ?>
    <div class="sub-title"><?php echo $subTabs.' '.A::t('directory', 'Edit Category'); ?></div>
    <div class="content">
        <?php
            echo $actionMessage;

            CWidget::create('CDataForm', array(
                'model'=>'Categories',
                'primaryKey'=>$id,
                'operationType'=>'edit',
                'action'=>'categories/edit/id/'.$id,
                'successUrl'=>'categories/manage/parentId/'.$parentId,
                'cancelUrl'=>'categories/manage/parentId/'.$parentId,
                'passParameters'=>false,
                'method'=>'post',
                'htmlOptions'=>array(
                    'id'=>'frmCategoryEdit',
                    'name'=>'frmCategoryEdit',
                    'enctype'=>'multipart/form-data',
                    'autoGenerateId'=>true
                ),
                'requiredFieldsAlert'=>true,
                'fields'=>array(
                    'parent_id' => array('type'=>'select', 'title' => A::t('directory', 'Parent Category'), 'tooltip'=>'', 'default'=>$parentId, 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array($parentId)), 'data'=>array($parentId => $lastCategoryName), 'htmlOptions'=>array('readonly' => 'readonly')),
                    'icon'  => array(
                        'type'=>'imageUpload',
                        'title'=>A::t('directory', 'Icon'),
                        'default'=>'',
                        'validation'=>array('type'=>'image', 'targetPath'=>'images/modules/directory/categories/', 'maxSize'=>'50k', 'maxWidth'=>'60px', 'maxHeight'=>'100px', 'mimeType'=>'image/jpeg, image/png, image/gif', 'fileName'=>CHash::getRandomString(15), 'fileNameCase'=>'lower'),
                        'imageOptions'   => array('showImage'=>true, 'showImageName'=>true, 'showImageSize'=>true, 'imageClass'=>'icon-category'),
                        'thumbnailOptions' => array('create'=>false),
                        'deleteOptions'  => array('showLink'=>true, 'linkUrl'=>'categories/edit/id/'.$id.'/deliteIcon/icon', 'linkText'=>'Delete'),
                        'fileOptions'=> array('showAlways'=>false, 'class'=>'file', 'size'=>'25', 'filePath'=>'images/modules/directory/categories/'),
                    ),
                    'icon_map'  => array(
                        'type'=>'imageUpload',
                        'title'=>A::t('directory', 'Map Icon'),
                        'default'=>'',
                        'validation'=>array('type'=>'image', 'targetPath'=>'images/modules/directory/categories/mapicons/', 'maxSize'=>'10k', 'maxWidth'=>'60px', 'maxHeight'=>'100px', 'mimeType'=>'image/jpeg, image/png, image/gif', 'fileName'=>CHash::getRandomString(15), 'fileNameCase'=>'lower'),
                        'imageOptions'   => array('showImage'=>true, 'showImageName'=>true, 'showImageSize'=>true, 'imageClass'=>'icon-map-category'),
                        'thumbnailOptions' => array('create'=>false),
                        'deleteOptions'  => array('showLink'=>true, 'linkUrl'=>'categories/edit/id/'.$id.'/deliteIcon/iconmap', 'linkText'=>'Delete'),
                        'fileOptions'=> array('showAlways'=>false, 'class'=>'file', 'size'=>'25', 'filePath'=>'images/modules/directory/categories/mapicons/'),
                    ),
                    'sort_order' => array('type'=>'textbox', 'title'=>A::t('directory', 'Sort Order'), 'default'=>0, 'tooltip'=>'', 'validation'=>array('required'=>true, 'maxLength'=>6, 'type'=>'numeric'), 'htmlOptions'=>array('maxLength'=>6, 'class'=>'small')),
                ),
                'translationInfo' => array('relation'=>array('id', 'category_id'), 'languages'=>Languages::model()->findAll('is_active = 1')),
                'translationFields' => array(
                    'name' => array('type'=>'textbox', 'title'=>A::t('directory', 'Name'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>50), 'htmlOptions'=>array('maxLength'=>50)),
                    'description' => array('type'=>'textarea', 'title'=>A::t('directory', 'Description'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>512), 'htmlOptions'=>array('maxLength'=>512)),
                ),
                'buttons'=>array(
                    'submitUpdateClose' => array('type'=>'submit', 'value'=>A::t('directory', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose')),
                    'submitUpdate' => array('type'=>'submit', 'value'=>A::t('directory', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
                    'cancel' => array('type'=>'button', 'value'=>A::t('directory', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
                ),
                'messagesSource'=>'core',
                'alerts'=>array('type'=>'flash'),
                'return'=>false,
            ));
        ?>
    </div>
</div>
