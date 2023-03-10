<?php
    $this->_activeMenu = 'categories/manage';
    $breadCrumbs = array(
        array('label'=>A::t('directory', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('directory', 'Business Directory'), 'url'=>'modules/settings/code/directory'),
        array('label'=>A::t('directory', 'Categories Management'), 'url'=>'categories/manage'),
    );
    for($i = 0; $i < count($parentCategories); $i++){
        $tmpArray = array('label'=>$parentCategories[$i]['name']);
        if($i < (count($parentCategories) - 1)){
            $tmpArray['url'] = 'categories/manage/parentId/'.$parentCategories[$i]['id'];
        }
        $breadCrumbs[] = $tmpArray;
    }
    $this->_breadCrumbs = $breadCrumbs;
?>

<h1><?php echo A::t('directory', 'Categories Management'); ?></h1>

<div class="bloc">
    <?php
        echo $tabs;
        if($subTabs){
            echo '<div class="sub-title">'.$subTabs.'</div>';
        }

        $fields = array(
                'name'              => array('title'=>A::t('directory', 'Name'), 'type'=>'label', 'class'=>'left', 'width'=>'140px', 'headerClass'=>'left'),
                'description'       => array('title'=>A::t('directory', 'Description'), 'type'=>'label', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'maxLength'=>'150'),
                'icon'              => array('title'=>A::t('directory', 'Icon'), 'type'=>'image', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'imagePath'=>'images/modules/directory/categories/', 'defaultImage'=>'no_image.png', 'width'=>'35px', 'imageHeight'=>'20px'),
                'icon_map'          => array('title'=>A::t('directory', 'Map Icon'), 'type'=>'image', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'imagePath'=>'images/modules/directory/categories/mapicons/', 'defaultImage'=>'no_image.png', 'width'=>'60px', 'imageHeight'=>'28px'),
                'locations_link'    => array('title'=>A::t('directory', 'Listings'), 'type'=>'link', 'class'=>'center', 'headerClass'=>'center', 'width'=>'60px', 'isSortable'=>false, 'linkUrl'=>'categories/manageListings/categoryId/{id}', 'linkText'=>A::t('directory', 'Listings'), 'htmlOptions'=>array('class'=>'subgrid-link'), 'prependCode'=>'[ ', 'appendCode'=>' ]'),
                'id_to_listings'    => array('title'=>'', 'type'=>'enum', 'table'=>'', 'operator'=>'=', 'default'=>'', 'width'=>'30px', 'source'=>$listingsCounts, 'definedValues'=>array(''=>'<span class="label-zerogray">0</span>'), 'isSortable'=>true, 'class' => 'left', 'prependCode'=>'<span class="label-lightgray">', 'appendCode'=>'</span>')
            );
        if($parentCategoryCount < 2){
            $fields['sub_categories_link']  = array('title'=>A::t('directory', 'Sub-Categories'), 'type'=>'link', 'class'=>'center', 'headerClass'=>'center', 'width'=>'100px', 'isSortable'=>false, 'linkUrl'=>'categories/manage/parentId/{id}', 'linkText'=>A::t('directory', 'Sub-Categories'), 'htmlOptions'=>array('class'=>'subgrid-link'), 'prependCode'=>'[ ', 'appendCode'=>' ]');
            $fields['id']                   = array('title' => '', 'type'=>'enum', 'table'=>'', 'operator'=>'=', 'default'=>'', 'width'=>'30px', 'source'=>$subCategoriesCounts, 'definedValues'=>array(''=>'<span class="label-zerogray">0</span>'), 'isSortable'=>true, 'class' => 'left', 'prependCode'=>'<span class="label-lightgray">', 'appendCode'=>'</span>');
        }
        $fields['sort_order']       = array('title'=>A::t('directory', 'Sort Order'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'90px');

    ?>

    <div class="content">
    <?php
        echo $actionMessage;

        if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('directory', 'add')){
            echo '<a href="categories/add/parentId/'.$parentId.'" class="add-new">'.A::t('directory', 'Add Category').'</a>';
        }

        CWidget::create('CGridView', array(
            'model'=>'Categories',
            'actionPath'=>'categories/manage/parentId/'.$parentId,
            'condition'=>'parent_id="'.$parentId.'"',
            'defaultOrder'=> array('sort_order'=>'DESC', 'name'=>'ASC'),
            'passParameters'=>true,
            'pagination'=>array('enable'=>true, 'pageSize'=>20),
            'sorting'=>true,
            'filters'=>array(
                'name'           => array('title'=>A::t('directory', 'Name'), 'type'=>'textbox', 'operator'=>'%like%', 'width'=>'140px', 'maxLength'=>50),
                'description'    => array('title'=>A::t('directory', 'Description'), 'type'=>'textbox', 'operator'=>'%like%', 'width'=>'250px', 'maxLength'=>512),
            ),
            'fields'=>$fields,
            'actions'=>array(
                'edit'    => array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('directory', 'edit'),
                    'link'=>'categories/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('directory', 'Edit this record')
                ),
                'delete'=>array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('directory', 'delete'),
                    'link'=>'categories/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('directory', 'Delete this record'), 'onDeleteAlert'=>true
                )
            ),
            'return'=>false,
        ));
    ?>
    </div>
</div>
