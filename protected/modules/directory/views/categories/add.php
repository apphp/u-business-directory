<?php
    $this->_activeMenu = 'categories/manage';
    $breadCrumbs = array(
        array('label'=>A::t('directory', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('directory', 'Business Directory'), 'url'=>'modules/settings/code/directory'),
        array('label'=>A::t('directory', 'Categories Management'), 'url'=>'categories/manage'),
    );
    foreach($parentCategories as $parentCategory){
        $breadCrumbs[] = array('label'=>$parentCategory['name'], 'url'=>'categories/manage/parentId/'.$parentCategory['id']);
    }
    // if $parentName empty assigning 'None'
    $parentName || $parentName = A::t('directory', 'None');
    $breadCrumbs[] =  array('label'=>A::t('directory', 'Add Category'));
    $this->_breadCrumbs = $breadCrumbs;
?>

<h1><?php echo A::t('directory', 'Categories Management'); ?></h1>

<div class="bloc">
    <?php
        echo $tabs;

        $fields = array(
            'parent_id' => array('type'=>($parentId ? 'select' : 'hidden'), 'title' => A::t('directory', 'Parent Category'), 'tooltip'=>'', 'default'=>$parentId, 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array($parentId)), 'data'=>array($parentId=>$parentName), 'htmlOptions'=>array()),
            'icon' => array(
                'type'              => 'imageUpload',
                'title'             => A::t('directory', 'Icon'),
                'default'=>'',
                'validation'        => array('required'=>false, 'type'=>'image', 'targetPath'=>'images/modules/directory/categories/', 'maxSize'=>'50k', 'maxWidth'=>'35px', 'maxHeight'=>'35px', 'mimeType'=>'image/jpeg, image/png, image/gif', 'fileName'=>CHash::getRandomString(15), 'fileNameCase'=>'lower'),
                'imageOptions'      => array('showImage'=>false, 'showImageName'=>true, 'imageClass'=>'icon-category'),
                'thumbnailOptions'  => array('create'=>false),
            ),
            'icon_map' => array(
                'type'              => 'imageUpload',
                'title'             => A::t('directory', 'Map Icon'),
                'default'=>'',
                'validation'        => array('required'=>false, 'type'=>'image', 'targetPath'=>'images/modules/directory/categories/mapicons/', 'maxSize'=>'10k',  'maxWidth'=>'60px', 'maxHeight'=>'100px', 'mimeType'=>'image/jpeg, image/png, image/gif', 'fileName'=>CHash::getRandomString(15), 'fileNameCase'=>'lower'),
                'imageOptions'      => array('showImage'=>false, 'showImageName'=>true, 'imageClass'=>'icon-map-category'),
                'thumbnailOptions'  => array('create'=>false)
            ),
            'sort_order' => array('type'=>'textbox', 'title'=>A::t('directory', 'Sort Order'), 'default'=>0, 'tooltip'=>'', 'validation'=>array('required'=>true, 'maxLength'=>6, 'type'=>'numeric'), 'htmlOptions'=>array('maxLength'=>6, 'class'=>'small'))
        );
    ?>

    <div class="sub-title"><?php echo $subTabs.' '.A::t('directory', 'Add Category'); ?></div>
    <div class="content">
        <?php
            echo $actionMessage;

            CWidget::create('CDataForm', array(
                'model'         =>'Categories',
                'operationType' =>'add',
                'resetBeforeStart' => true,
                'action'        =>'categories/add/parentId/'.$parentId,
                'successUrl'    =>'categories/manage/parentId/'.$parentId,
                'cancelUrl'     =>'categories/manage/parentId/'.$parentId,
                'passParameters'=>false,
                'method'        =>'post',
                'htmlOptions'   =>array(
                    'name'          =>'frmCategoryAdd',
                    'id'            =>'frmCategoryAdd',
                    'enctype'       =>'multipart/form-data',
                    'autoGenerateId'=>true
                ),
                'requiredFieldsAlert'=>true,
                'fields'        =>$fields,
                'translationInfo'   => array('relation'=>array('id', 'category_id'), 'languages'=>Languages::model()->findAll('is_active = 1')),
                'translationFields' => array(
                    'name'       => array('type'=>'textbox', 'title'=>A::t('directory', 'Name'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>50), 'htmlOptions'=>array('maxLength'=>50)),
                    'description'=> array('type'=>'textarea', 'title'=>A::t('directory', 'Description'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>512), 'htmlOptions'=>array('maxLength'=>512)),
                ),
                'buttons'=>array(
                   'submit' => array('type'=>'submit', 'value'=>A::t('directory', 'Create'), 'htmlOptions'=>array('name'=>'')),
                   'cancel' => array('type'=>'button', 'value'=>A::t('directory', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
                ),
                'messagesSource'=>'core',
                'alerts'=>array('type'=>'flash'),
                'return'=>false,
            ));
        ?>
    </div>
</div>
