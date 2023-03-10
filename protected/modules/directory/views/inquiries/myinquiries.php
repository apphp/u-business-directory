<?php
    $this->_pageTitle = A::t('directory', 'Inquiries');
    $this->_activeMenu = 'inquiries/myInquiries';
    A::app()->getClientScript()->registerCssFile('templates/default/vendors/basictable/basictable.css');
    A::app()->getClientScript()->registerScriptFile('js/modules/directory/directory.js', 0);
    A::app()->getClientScript()->registerScriptFile('templates/default/vendors/basictable/jquery.basictable.js', 1);
?>
<article id="page-myinquiries" class="page-my page-myinquiries type-page status-publish hentry">
    <header class="entry-header">
        <h1 class="entry-title">
            <span><?php echo A::t('directory', 'Inquiries'); ?></span>
        </h1>
        <?php
            $breadCrumbLinks = array(array('label'=> A::t('directory', 'Home'), 'url'=>Website::getDefaultPage()));
            $breadCrumbLinks[] = array('label'=> A::t('directory', 'Dashboard'), 'url'=>'customers/dashboard');
            $breadCrumbLinks[] = array('label' => A::t('directory', 'Inquiries'));
            CWidget::create('CBreadCrumbs', array(
                'links' => $breadCrumbLinks,
                'wrapperClass' => 'category-breadcrumb clearfix',
                'linkWrapperTag' => 'span',
                'separator' => '&nbsp;/&nbsp;',
                'return' => false
            ));
        ?>
    </header>
    <div class="block-body">
        <div class="sub-title">
        <?php
            echo '<a class="sub-tab '.($typeTab == 'all' ? 'active' : 'previous').'" href="inquiries/myInquiries/type/all">'.A::t('directory', 'All').'</a>';
            echo '<a class="sub-tab '.($typeTab == 'active' ? 'active' : 'previous').'" href="inquiries/myInquiries/type/active">'.A::t('directory', 'Active').'</a>';
            echo '<a class="sub-tab '.($typeTab == 'archived' ? 'active' : 'previous').'" href="inquiries/myInquiries/type/archived">'.A::t('directory', 'Archived').'</a>';
        ?>
        </div>
        <div class='content'>
        <?php
            echo $actionMessage;

            $actions = array();

            CWidget::create('CGridView', array(
                'model'=>'Inquiries',
                'actionPath'=>'inquiries/myInquiries/'.$typeTab,
                'condition'=>'customer_id = "'.(int)$customerId.'"'.(!empty($condition) ? ' AND ('.$condition.')' : ''),
                'defaultOrder'=>array('id'=>'DESC'),
                'passParameters'=>true,
                'pagination'=>array('enable'=>true, 'pageSize'=>20),
                'sorting'=>true,
                'fields'=>array(
                    'listing_name' => array('title'=>A::t('directory', 'Listing'), 'type'=>'link', 'width'=>'', 'headerClass'=>'', 'class'=>'', 'isSortable'=>true, 'linkUrl'=>'listings/view/id/{listing_id}', 'linkText'=>'{listing_name}', 'htmlOptions'=>array('target'=>'_blank')),
                    'name' => array('title'=>A::t('directory', 'Name'), 'type'=>'label', 'align'=>'', 'width'=>'140px', 'class'=>'', 'headerClass'=>'', 'isSortable'=>true, 'definedValues'=>array()),
                    'email' => array('title'=>A::t('directory', 'Email'), 'type'=>'link', 'align'=>'', 'width'=>'150px', 'class'=>'', 'headerClass'=>'', 'isSortable'=>true, 'linkUrl'=>'mailto:{email}', 'linkText'=>'{email}'),
                    'date_created' => array('title'=>A::t('directory', 'Date Created'), 'type'=>'datetime', 'align'=>'', 'width'=>'125px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'format'=>$dateTimeFormat),
                    'is_active' => array('title'=>A::t('directory', 'Active'), 'type'=>'link', 'width'=>'60px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>$typeTab == 'all' ? true : false, 'linkUrl'=>'inquiries/changeStatus/id/{id}/type/'.$typeTab, 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-gray">'.A::t('directory', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('directory', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('directory', 'Click to change status')))
                ),
                'actions'=>array(
                    'preview'       => array(
                        'link'          => 'inquiries/customerPreview/id/{id}/type/'.$typeTab, 'imagePath'=>'templates/backend/images/details.png', 'title'=>A::t('directory', 'Preview this record')
                    )
                ),
                'return'=>false,
            ));
        ?>
        </div>
    </div>
</article>
<?php
    A::app()->getClientScript()->registerScript(
        'responsiveTable',
        'jQuery("table").basictable({
            noResize: true
        });'
    );
