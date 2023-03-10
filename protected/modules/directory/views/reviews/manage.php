<?php
    $this->_activeMenu = 'reviews/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('directory', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('directory', 'Business Directory'), 'url'=>'modules/settings/code/directory'),
        array('label'=>A::t('directory', 'Reviews Management'), 'url'=>'reviews/manage'),
    );
?>

<h1><?php echo A::t('directory', 'Reviews Management'); ?></h1>

<div class="bloc">
    <?php echo $tabs; ?>
    <div class="sub-title">
    <?php
        echo '<a class="sub-tab '.($typeTab == 'pending' ? 'active' : 'previous').'" href="reviews/manage/type/pending'.$getUrl.'">'.A::t('directory', 'Pending').'</a>';
        echo '<a class="sub-tab '.($typeTab == 'approved' ? 'active' : 'previous').'" href="reviews/manage/type/approved'.$getUrl.'">'.A::t('directory', 'Approved').'</a>';
    ?>
    </div>

    <div class="content">
    <?php
        echo $actionMessage;

        CWidget::create('CGridView', array(
            'model'=>'Reviews',
            'actionPath'=>'reviews/manage',
            'condition'=>($typeTab == 'approved' ? CConfig::get('db.prefix').'reviews.is_public = 1' : CConfig::get('db.prefix').'reviews.is_public = 0'),
            'defaultOrder'=>array('id'=>'DESC'),
            'passParameters'=>true,
            'pagination'=>array('enable'=>true, 'pageSize'=>20),
            'sorting'=>true,
            'filters'=>array(
                //'category_id' => array('title'=>A::t('directory', 'Category'), 'type'=>'enum', 'operator'=>'=', 'width'=>'140px', 'emptyOption'=>true, 'source'=>$filterCategories),
                //'region_id' => array('title'=>A::t('directory', 'Location'), 'type'=>'enum', 'operator'=>'=', 'width'=>'120px', 'emptyOption'=>true, 'source'=>$filterLocations),
                //'subregion_id' => array('title'=>A::t('directory', 'Sub-Location'), 'type'=>'enum', 'operator'=>'=', 'width'=>'140px', 'emptyOption'=>true, 'source'=>$filterSubLocations),
                'business_name' => array('title'=>A::t('directory', 'Listing'), 'type'=>'textbox', 'table'=>CConfig::get('db.prefix').'listing_translations', 'operator'=>'%like%', 'width'=>'120px', 'maxLength'=>'50'),
                'customer_name' => array('title'=>A::t('directory', 'Created By'), 'type'=>'textbox', 'operator'=>'like%', 'width'=>'80px', 'maxLength'=>'50'),
                'created_at'  => array('title'=>A::t('directory', 'Date Created'), 'type'=>'datetime', 'operator'=>'like%', 'default'=>'', 'width'=>'80px', 'maxLength'=>'', 'format'=>$dateTimeFormat, 'htmlOptions'=>array()),
                'is_public' => array('title'=>A::t('directory', 'Public'), 'type'=>'enum', 'operator'=>'=', 'width'=>'', 'emptyOption'=>true, 'source'=>array(0=>A::t('directory', 'No'), 1=>A::t('directory', 'Yes'))),
            ),
            'fields'=>array(
                'listing_name' => array('title'=>A::t('directory', 'Listing'), 'type'=>'link', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'linkUrl'=>'listings/view/id/{listing_id}', 'definedValues'=>array(), 'linkText'=>'{listing_name}', 'htmlOptions'=>array('target'=>'_blank')),
                'customer_name' => array('title'=>A::t('directory', 'Created By'), 'type'=>'label', 'align'=>'', 'width'=>'120px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true),
                'customer_email' => array('title'=>A::t('directory', 'Email'), 'type'=>'label', 'align'=>'', 'width'=>'100px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true),
                'created_at' => array('title'=>A::t('directory', 'Date Created'), 'type'=>'datetime', 'align'=>'', 'width'=>'130px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'format'=>$dateTimeFormat),
                'rating_value' => array('title'=>A::t('directory', 'Evaluation'), 'type'=>'evaluation', 'align'=>'', 'width'=>'80px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'minValue'=>1, 'maxValue'=>5, 'tooltip'=>A::t('directory', 'Rate')),
                'is_public' => array('title'=>A::t('directory', 'Published'), 'type'=>'link', 'width'=>'80px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'linkUrl'=>'reviews/changeStatus/id/{id}','definedValues'=>array('0'=>'<span class="badge-red">'.A::t('directory', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('directory', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('directory', 'Click to change status'))),
                'id' => array('title'=>A::t('directory', 'ID'), 'type'=>'label', 'align'=>'', 'width'=>'30px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true),
            ),
            'actions'=>array(
                'preview'       => array(
                    'disabled'      => !Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('directory', 'edit'),
                    'link'          => 'reviews/preview/id/{id}/typeTab/'.CHtml::encode($typeTab), 'imagePath'=>'templates/backend/images/details.png', 'title'=>A::t('directory', 'Preview this record')
                ),
                'delete'=>array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('directory', 'delete'),
                    'link'=>'reviews/delete/id/{id}/typeTab/'.CHtml::encode($typeTab), 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('directory', 'Delete this record'), 'onDeleteAlert'=>true
                )
            ),
            'return'=>false,
        ));

    ?>
    </div>
</div>
