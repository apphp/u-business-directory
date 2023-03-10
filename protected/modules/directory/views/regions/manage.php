<?php
    $this->_activeMenu = 'regions/manage';

    $breadCrumbs = array(
        array('label'=>A::t('directory', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('directory', 'Business Directory'), 'url'=>'modules/settings/code/directory'),
        array('label'=>A::t('directory', 'Locations Management'), 'url'=>'regions/manage')
    );
    if($parentId){
        $breadCrumbs[] = array('label'=>$parentName);
    }
    $this->_breadCrumbs = $breadCrumbs;
?>

<h1><?php echo A::t('directory', 'Locations Management'); ?></h1>

<div class="bloc">
    <?php
        echo $tabs;
        if($parentId){
            echo '<div class="sub-title">';
            echo '<a class="sub-tab active" href="regions/manage/parentId/'.(int)$parentId.'">'.$parentName.'</a>';
            echo '</div>';
        }
    ?>
    <div class="content">
    <?php
        echo $actionMessage;

        if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('directory', 'add')){
            echo '<a href="regions/add/parentId/'.$parentId.'" class="add-new">'.($parentId ? A::t('directory', 'Add Sub-Location') : A::t('directory', 'Add Location')).'</a>';
        }

        $fields = array(
            'name'              => array('title'=>A::t('directory', 'Name'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left'),
            'listing_link'      => array('title'=>A::t('directory', 'Listings'), 'type'=>'link', 'class'=>'center', 'headerClass'=>'center', 'width'=>'60px', 'isSortable'=>false, 'linkUrl'=>'listings/manage?region_id={id}&but_filter=Filter', 'linkText'=>A::t('directory', 'Listings'), 'htmlOptions'=>array('class'=>'subgrid-link'), 'prependCode'=>'[ ', 'appendCode'=>' ]'),
            'id_to_listings'    => array('title' => '', 'type'=>'enum', 'table'=>'', 'operator'=>'=', 'default'=>'', 'width'=>'25px', 'source'=>$listingsCounts, 'definedValues'=>array(''=>'<span class="label-zerogray">0</span>'), 'isSortable'=>true, 'class' => 'left', 'prependCode'=>'<span class="label-lightgray">', 'appendCode'=>'</span>'),
        );
        if(0 == $parentId){
            $fields['sub_region_link'] = array('title'=>A::t('directory', 'Sub-Locations'), 'type'=>'link', 'class'=>'center', 'headerClass'=>'center', 'width'=>'100px', 'isSortable'=>false, 'linkUrl'=>'regions/manage/parentId/{id}', 'linkText'=>A::t('directory', 'Sub-Locations'), 'htmlOptions'=>array('class'=>'subgrid-link'), 'prependCode'=>'[ ', 'appendCode'=>' ]');
            $fields['region_id'] = array('title' => '', 'type'=>'enum', 'table'=>'', 'operator'=>'=', 'default'=>'', 'width'=>'25px', 'source'=>$subRegionCounts, 'definedValues'=>array(''=>'<span class="label-zerogray">0</span>'), 'isSortable'=>true, 'class' => 'left', 'prependCode'=>'<span class="label-lightgray">', 'appendCode'=>'</span>');
        }
        $fields['sort_order']   = array('title'=>A::t('directory', 'Sort Order'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'80px');
        $fields['is_active']    = array('title'=>A::t('directory', 'Active'), 'type'=>'link', 'width'=>'60px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'linkUrl'=>'regions/activeStatus/id/{id}', 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-red">'.A::t('directory', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('directory', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('directory', 'Click to change status')));


        CWidget::create('CGridView', array(
            'model'         =>'Regions',
            'actionPath'    =>'regions/manage/parentId/'.$parentId,
            'defaultOrder'  =>array('sort_order'=>'DESC', 'name'=>'ASC'),
            'condition'     =>'parent_id = "'.$parentId.'"',
            'passParameters'=>true,
            'pagination'    =>array('enable'=>true, 'pageSize'=>20),
            'sorting'       =>true,
            'filters'       =>array(
                'name'           => array('title'=>A::t('directory', 'Name'), 'type'=>'textbox', 'operator'=>'%like%', 'width'=>'140px', 'maxLength'=>70),
            ),
            'fields'=>$fields,
            'actions'=>array(
                'edit'    => array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('directory', 'edit'),
                    'link'=>'regions/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('directory', 'Edit this record')
                ),
                'delete'=>array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('directory', 'delete'),
                    'link'=>'regions/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('directory', 'Delete this record'), 'onDeleteAlert'=>true
                )
            ),
            'return'=>false,
        ));
    ?>
    </div>
</div>
