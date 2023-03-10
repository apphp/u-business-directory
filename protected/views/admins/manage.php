<?php
	Website::setMetaTags(array('title'=>A::t('app', 'Admins Management')));
	
    $this->_activeMenu = 'admins/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Accounts'), 'url'=>'admins/'),
		array('label'=>A::t('app', 'Admins')),
    );	
?>

<h1><?php echo A::t('app', 'Admins Management'); ?></h1>

<div class="bloc">
    <div class="title"><?php echo A::t('app', 'Admins')?></div>
    <div class="content">
		<?php echo $actionMessage; ?>
    	<a href="admins/add" class="add-new"><?php echo A::t('app', 'Add New'); ?></a>
        <?php
            echo CWidget::create('CGridView', array(
                'model'				=> 'Admins',
                'actionPath'		=> 'admins/manage',
                'defaultOrder'		=> array('username'=>'ASC'),
				'passParameters'	=> true,
            	'condition'			=> 'role IN ('.$rolesListStr.')',
            	'pagination'		=> array('enable'=>true, 'pageSize'=>20),
                'sorting'			=> true,
                'filters'			=> array(
                    'first_name'    	=> array('title'=>A::t('app', 'First Name'), 'type'=>'textbox', 'operator'=>'like%', 'width'=>'100px', 'maxLength'=>'32'),
                    'last_name'     	=> array('title'=>A::t('app', 'Last Name'), 'type'=>'textbox', 'operator'=>'like%', 'width'=>'100px', 'maxLength'=>'32'),
                    'is_active'     	=> array('title'=>A::t('app', 'Active'), 'type'=>'enum', 'operator'=>'=', 'width'=>'', 'source'=>array('0'=>A::t('app', 'No'), '1'=>A::t('app', 'Yes')), 'emptyOption'=>true),
                    'role'     			=> array('title'=>A::t('app', 'Account Type'), 'type'=>'enum', 'operator'=>'=', 'width'=>'', 'source'=>$rolesList, 'emptyOption'=>true),
                ),
                'fields'			=> array(
                    'avatar'        	=> array('title'=>'', 'type'=>'image', 'align'=>'', 'width'=>'40px', 'class'=>'left', 'headerClass'=>'', 'isSortable'=>false, 'imagePath'=>'templates/backend/images/accounts/', 'defaultImage'=>'no_image.png', 'imageWidth'=>'26px', 'imageHeight'=>'', 'alt'=>''),
                    'fullname'      	=> array('title'=>A::t('app', 'Full Name'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left', 'width'=>'150px'),
                	'username'      	=> array('title'=>A::t('app', 'Username'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left', 'width'=>'120px'),
                    'email'     		=> array('title'=>A::t('app', 'Email'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left'),
                    'role'    			=> array('title'=>A::t('app', 'Account Type'), 'type'=>'enum', 'class'=>'center', 'headerClass'=>'center', 'source'=>$rolesList, 'width'=>'110px'),
                    'is_active'     	=> array('title'=>A::t('app', 'Active'), 'type'=>'enum', 'class'=>'center', 'headerClass'=>'center', 'source'=>array('0'=>'<span class="badge-red">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('app', 'Yes').'</span>'), 'width'=>'110px'),
                    'id'            	=> array('title'=>A::t('app', 'ID'), 'type'=>'label', 'align'=>'center', 'width'=>'20px', 'class'=>'center', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>''), 
                    //'last_visited_at'=> array('title'=>A::t('app', 'Last Visit'), 'type'=>'datetime', 'class'=>'center', 'headerClass'=>'center', 'definedValues'=>array('0000-00-00 00:00:00'=>A::t('app', 'Never')), 'width'=>'100px', 'format'=>$dateTimeFormat),
                ),
                'actions'			=> array(
                    'edit'   			=> array('link'=>'admins/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('app', 'Edit this record')),
                    'delete' 			=> array('link'=>'admins/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('app', 'Delete this record'), 'onDeleteAlert'=>true),
                ),
            ));
        ?>        
    </div>
</div>