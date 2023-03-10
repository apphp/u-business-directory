<?php
    $this->_pageTitle   = A::t('news', 'Subscribers') . ' | ' . CConfig::get('name');
    $this->_activeMenu  = 'modules/settings/code/news';
    $this->_breadCrumbs = array(
        array('label' => A::t('news', 'Modules'), 'url'=>'modules/'),
        array('label' => A::t('news', 'News'),    'url'=>'modules/settings/code/news'),
        array('label' => A::t('news', 'Subscribers')),
    );    
?>
<h1><?php echo A::t('news', 'Subscribers'); ?></h1>
<div class="bloc">
    <?php echo $tabs; ?>
    <div class="content">
    <?php 
        echo $actionMessage;  
        if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('news', 'add')){
            echo '<a href="newsSubscribers/add" class="add-new">'.A::t('news', 'Add Subscriber').'</a>';
        }
        $filters = array();
        if('no' != $typeFirstName) $filters['first_name'] = array('title' => A::t('news', 'First Name'), 'type' => 'textbox', 'operator' => 'like%', 'width' => '120px', 'maxLength' => '32');
        if('no' != $typeLastName) $filters['last_name'] = array('title' => A::t('news', 'Last Name'), 'type' => 'textbox', 'operator' => 'like%', 'width' => '120px', 'maxLength' => '32');
        if('no' != $typeFullName) $filters['full_name'] = array('title' => A::t('news', 'Full Name'), 'type' => 'textbox', 'operator' => '%like%', 'width' => '120px', 'maxLength' => '64');
        $filters['email'] = array('title' => A::t('news', 'Email'), 'type' => 'textbox', 'operator' => '%like%', 'width' => '120px', 'maxLength' => '128');
        $filters['created_at'] = array('title'=>A::t('news', 'Date'), 'type'=>'datetime', 'operator'=>'like%', 'width'=>'80px', 'maxLength'=>'10');
        
        $fields = array();
        if('no' != $typeFirstName) $fields['first_name'] = array('title' => A::t('news', 'First Name'), 'type' => 'label', 'align' => 'left', 'width' => '120px', 'class' => 'left', 'headerClass' => 'left', 'isSortable' => true, 'defaultValues' => array());
        if('no' != $typeLastName) $fields['last_name'] = array('title' => A::t('news', 'Last Name'), 'type' => 'label', 'align' => 'left', 'width' => '', 'class' => 'left', 'headerClass' => 'left', 'isSorting' => true, 'defaultValues' => array());
        if('no' != $typeFullName) $fields['full_name'] = array('title' => A::t('news', 'Full Name'), 'type' => 'label', 'align' => 'left', 'width' => '', 'class' => 'left', 'headerClass' => 'left', 'isSorting' => true, 'defaultValues' => array());
        $fields['email'] = array('title' => A::t('news', 'Email'), 'type' => 'label', 'align' => 'left', 'width' => '200px', 'class' => 'left', 'headerClass' => 'left', 'isSortable' => true, 'defaultValues' => array());
        $fields['created_at'] = array('title' => A::t('news', 'Date Created'), 'type' => 'label', 'align' => 'left', 'width' => '200px', 'class' => 'left', 'headerClass' => 'left', 'isSorting' => true, 'defaultValues' => array(), 'format' => $dateTimeFormat);

        $fields['id'] = array('title' => A::t('news', 'ID'), 'type' => 'label', 'align' => 'center', 'width' => '60px', 'class' => 'center', 'headerClass' => 'center', 'isSorting' => true, 'defaultValues' => array());

        CWidget::create('CGridView', array(
            'model'          => 'NewsSubscribers',
            'actionPath'     => 'newsSubscribers/manage',
            'condition'      => '',
            'defaultOrder'   => array('id'=>'DESC'),
            'passParameters' => true,
            'pagination'     => array('enable'=>true, 'pageSize' => 20),
            'sorting'        => true,
            'filters'        => $filters, 
            'fields'         => $fields,
            'actions'        => array(
                'edit' => array('disabled' => !Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('news', 'edit'), 'link'      => 'newsSubscribers/edit/id/{id}', 'imagePath' => 'templates/backend/images/edit.png', 'title' => A::t('news', 'Edit this record')),
                'delete' => array('disabled' => !Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('news', 'delete'), 'link' => 'newsSubscribers/delete/id/{id}', 'imagePath' => 'templates/backend/images/delete.png', 'title' => A::t('news', 'Delete this record'), 'onDeleteAlert' => true)),
            'return' => false,
        ));

    ?>        
    </div>
</div>
