<?php
    $this->_pageTitle = A::t('directory', 'My Orders');
    $this->_activeMenu = 'orders/myOrders';
    A::app()->getClientScript()->registerCssFile('templates/default/vendors/basictable/basictable.css');
    A::app()->getClientScript()->registerScriptFile('js/modules/directory/directory.js', 0);
    A::app()->getClientScript()->registerScriptFile('templates/default/vendors/basictable/jquery.basictable.js', 1);

?>
<article id="page-myorders" class="page-my page-myorders type-page status-publish hentry">
    <header class="entry-header">
        <h1 class="entry-title">
            <span><?php echo A::t('directory', 'My Orders'); ?></span>
        </h1>
        <?php
            $breadCrumbLinks = array(array('label'=> A::t('directory', 'Home'), 'url'=>Website::getDefaultPage()));
            $breadCrumbLinks[] = array('label' => A::t('directory', 'My Orders'));
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
        <!--div class="sub-title">
        <?php
            echo '<a class="sub-tab '.($typeTab == 'all' ? 'active' : 'previous').'" href="orders/myOrders">'.A::t('directory', 'All').'</a>';
            echo '<a class="sub-tab '.($typeTab == 'pending' ? 'active' : 'previous').'" href="orders/myOrders/type/pending">'.A::t('directory', 'Pending').'</a>';
            echo '<a class="sub-tab '.($typeTab == 'approved' ? 'active' : 'previous').'" href="orders/myOrders/type/approved">'.A::t('directory', 'Approved').'</a>';
            echo '<a class="sub-tab '.($typeTab == 'expired' ? 'active' : 'previous').'" href="orders/myOrders/type/expired">'.A::t('directory', 'Expired').'</a>';
        ?>
        </div-->
        <div class='content'>
        <?php
            echo $actionMessage;

            $actions = array();

            CWidget::create('CGridView', array(
                'model'=>'Orders',
                'actionPath'=>'orders/myOrders/type/'.$typeTab,
                'condition'=>CConfig::get('db.prefix').'orders.status > 0 AND '.CConfig::get('db.prefix').'orders.customer_id = "'.(int)$customerId.'"'.(!empty($condition) ? ' AND ('.$condition.')' : ''),
                'defaultOrder'=>array('id'=>'DESC'),
                'passParameters'=>true,
                'pagination'=>array('enable'=>true, 'pageSize'=>20),
                'sorting'=>true,
                'fields'=>array(
                    'order_number' => array('title'=>A::t('directory', 'Order'), 'type'=>'label', 'align'=>'', 'width'=>'', 'class'=>'', 'headerClass'=>'left', 'isSortable'=>true),
                    'created_date' => array('title'=>A::t('directory', 'Date'), 'type'=>'datetime', 'align'=>'', 'width'=>'130px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'format'=>$dateTimeFormat),
                    'first_name' => array('title'=>A::t('directory', 'Customer'), 'type'=>'concat', 'align'=>'', 'width'=>'80px', 'class'=>'center', 'headerClass'=>'center', 'concatFields'=>array('first_name', 'last_name'), 'isSortable'=>true,  'concatSeparator'=>' '),
    //                'currency' => array('title'=>A::t('directory', 'Country'), 'type'=>'image', 'align'=>'', 'width'=>'', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false),
                    'advertise_plan_id' => array('title'=>A::t('directory', 'Plan'), 'type'=>'enum', 'align'=>'', 'width'=>'60px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'source'=>$adverticePlans),
                    'listing_name' => array('title'=>A::t('directory', 'Listing'), 'type'=>'link', 'align'=>'', 'width'=>'', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'linkUrl'=>'listings/view/id/{listing_id}', 'linkText'=>'{listing_name}', 'prependCode'=>'', 'appendCode'=>'', 'htmlOptions'=>array('target'=>'_blank')),
                    'total_price'=> array('title'=>A::t('directory', 'Price'), 'type'=>'label', 'align'=>'', 'width'=>'60px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true),
                    'status' => array('title'=>A::t('directory', 'Status'), 'type'=>'enum', 'align'=>'', 'width'=>'80px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'source'=>array(1=>'<span class="label-gray">'.A::t('directory', 'Pending').'</span>', 2=>'<span class="label-green">'.A::t('directory', 'Completed').'</span>', 3=>'<span class="label-red">'.A::t('directory', 'Refunded').'</span>')),
                ),
                'actions'=>$actions,
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
