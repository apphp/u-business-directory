<?php
    $this->_pageTitle = A::t('directory', 'My Listings');
    $this->_activeMenu = 'listings/myListings';
    A::app()->getClientScript()->registerCssFile('templates/default/vendors/basictable/basictable.css');
    A::app()->getClientScript()->registerScriptFile('js/modules/directory/directory.js', 0);
    A::app()->getClientScript()->registerScriptFile('templates/default/vendors/basictable/jquery.basictable.js', 1);


    function getBusinessName($record, $params)
    {
        $id = $record['id'];
        $name = $record['business_name'];
        $isApproved = $record['is_approved'];

        if($isApproved == 2){
            $result = '<a href="listings/view/id/'.CHtml::encode($id).'" target="_blank">'.$name.'</a>';
        }else{
            $result = $name;
        }

        return $result;

    }

    function getIsApproved($record, $params)
    {
        $id = $record['id'];
        $isApproved = $record['is_approved'];
        $advertisePlanId = $record['advertise_plan_id'];
        $allAdvertisePlans = $params['allAdvertisePlans'];

        // 0 - Pending, 1 - Approved, 2 - Canceled
        if($isApproved == 0){
            //$output = '<a href="orders/checkout/listingId/'.CHtml::encode($id).'" class="tooltip-link" title="'.A::t('directory', 'Click to pay listing').'"><span class="label-gray">'.A::t('directory', 'Preparing').'</span></a>';
            $output = '<span class="label-gray">'.A::t('directory', 'Pending').'</span>';
        }else if($isApproved == 1){
            $output = '<span class="label-green">'.A::t('directory', 'Approved').'</span>';
        }else{
            $output = '<span class="label-red">'.A::t('directory', 'Canceled').'</span>';
        }

        return $output;
    }

    function getStatus($record, $params)
    {
        $id = $record['id'];
        $isApproved = $record['is_approved'];
        $price = $record['price'];
        $orderPlanStatus = $record['order_plan_status'];

        // 0 - Pending, 1 - Approved, 2 - Canceled
        if($price == 0){
            $output = '--';
        }else{
            if(empty($orderPlanStatus)){
                if($isApproved == 0){
                    // Pay Now
                    $output = '<a href="orders/checkout/listingId/'.CHtml::encode($id).'" class="tooltip-link" title="'.A::t('directory', 'Click to pay listing').'"><span class="label-yellow">'.A::t('directory', 'Pay Now').'</span></a>';
                }else if($isApproved == 1){
                    $output = '<span class="label-green">'.A::t('directory', 'Completed').'</span>';
                }else{
                    $output = '<span class="label-red">'.A::t('directory', 'Canceled').'</span>';
                }
            }else if($orderPlanStatus == 1){
                // Pending Payment
                $output = '<span class="label-gray">'.A::t('directory', 'Pending Payment').'</span>';
            }else if($orderPlanStatus == 2){
                // Paid
                $output = '<span class="label-blue">'.A::t('directory', 'Paid').'</span>';
            }else if($orderPlanStatus == 3){
                // Payment Error
                $output = '<span class="label-red">'.A::t('directory', 'Payment Error').'</span>';
            }else if($orderPlanStatus == 4){
                // Canceled
                $output = '<span class="label-red">'.A::t('directory', 'Canceled').'</span>';
            }else if($orderPlanStatus == 5){
                // Refunded
                $output = '<span class="label-red">'.A::t('directory', 'Refunded').'</span>';
            }else{
                // Unknown
                $output = '<span class="label-red">- '.A::t('directory', 'Unknown').' -</span>';
            }
        }

        return $output;
    }

    function getIsPublished($record, $params)
    {
        $id = $record['id'];
        $isPublished = $record['is_published'];
        $isExists = $record['status_expired'];
        $isApproved = $record['is_approved'];
        $typeTab = $params['typeTab'];

        // 0 - Pending, 1 - Approved, 2 - Canceled
        if($isApproved != 1){
            if($isPublished == 1){
                $output = '<span class="label-green">'.A::t('directory', 'Yes').'</span>';
            }else{
                $output = '<span class="label-red">'.A::t('directory', 'No').'</span>';
            }
            $output = '--';
        }else{
            if($isPublished == 1){
                $output = '<a href="listings/publishedStatus/id/'.CHtml::encode($id).'/typeTab/'.CHtml::encode($typeTab).'" class="tooltip-link" title="'.A::t('directory', 'Click to change status').'"><span class="label-green">'.A::t('directory', 'Yes').'</span></a>';
            }else{
                $output = '<a href="listings/publishedStatus/id/'.CHtml::encode($id).'/typeTab/'.CHtml::encode($typeTab).'" class="tooltip-link" title="'.A::t('directory', 'Click to change status').'"><span class="label-red">'.A::t('directory', 'No').'</span></a>';
            }
        }

        return $output;
    }

?>
<article id="page-mylistings" class="page-my page-mylistings type-page status-publish hentry">
    <header class="entry-header">
        <h1 class="entry-title">
            <span><?php echo A::t('directory', 'My Listings'); ?></span>
        </h1>
        <?php
            $breadCrumbLinks = array(array('label'=> A::t('directory', 'Home'), 'url'=>Website::getDefaultPage()));
            $breadCrumbLinks[] = array('label'=> A::t('directory', 'Dashboard'), 'url'=>'customers/dashboard');
            $breadCrumbLinks[] = array('label' => A::t('directory', 'My Listings'));
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
            echo '<a class="sub-tab '.($typeTab == 'all' ? 'active' : 'previous').'" href="listings/myListings">'.A::t('directory', 'All').'</a>';
            echo '<a class="sub-tab '.($typeTab == 'pending' ? 'active' : 'previous').'" href="listings/myListings/type/pending">'.A::t('directory', 'Pending').'</a>';
            echo '<a class="sub-tab '.($typeTab == 'approved' ? 'active' : 'previous').'" href="listings/myListings/type/approved">'.A::t('directory', 'Approved').'</a>';
            echo '<a class="sub-tab '.($typeTab == 'expired' ? 'active' : 'previous').'" href="listings/myListings/type/expired">'.A::t('directory', 'Expired').'</a>';
            echo '<a class="sub-tab '.($typeTab == 'canceled' ? 'active' : 'previous').'" href="listings/myListings/type/canceled">'.A::t('directory', 'Canceled').'</a>';
        ?>
        </div>
        <div class='content'>
        <?php
            echo $actionMessage;

            echo CHtml::tag('a', array('class'=>'button add-listing', 'href'=>'listings/addListing'.($typeTab ? '/type/'.$typeTab : '')), A::t('directory', 'Add Listing'));

            if($typeTab == 'pending' || $typeTab == 'preparing'){
                $actions = array(
                    'edit'    => array(
                        'link'=>'listings/editListing/id/{id}/typeTab/'.$typeTab, 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('directory', 'Edit this record')
                    ),
                    'delete'=>array(
                        'link'=>'listings/deleteListing/id/{id}/typeTab/'.$typeTab, 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('directory', 'Delete this record'), 'onDeleteAlert'=>true
                    )
                );
            }else if($typeTab == 'approved'){
                $actions = array(
                    'edit'    => array(
                        'link'=>'listings/editListing/id/{id}/typeTab/'.$typeTab, 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('directory', 'Edit this record')
                    )
                );
            }else{
                $actions = array();
            }

            Listings::model()->setTypeRelations('orders');
            CWidget::create('CGridView', array(
                'model'=>'Listings',
                'actionPath'=>'listings/myListings/type/'.$typeTab,
                'condition'=>CConfig::get('db.prefix').'listings.customer_id = "'.(int)$customerId.'"'.(!empty($condition) ? ' AND ('.$condition.')' : ''),
                'defaultOrder'=>array('id'=>'DESC'),
                'passParameters'=>true,
                'pagination'=>array('enable'=>true, 'pageSize'=>20),
                'sorting'=>true,
                'fields'=>array(
                    'image_file_thumb'=>array('title'=>A::t('directory', 'Image'), 'type'=>'image', 'width'=>'50px', 'defaultImage'=>'no_image.png', 'class'=>'center', 'isSortable'=>false, 'imagePath'=>'images/modules/directory/listings/thumbs/', 'imageWidth'=>'20px', 'imageHeight'=>'20px', 'alt'=>''),
                    'business_name'=>array('title'=>A::t('directory', 'Name'), 'type'=>'link', 'headerClass'=>'center', 'width'=>'', 'isSortable'=>true, 'linkUrl'=>'listings/view/id/{id}', 'linkText'=>'{business_name}', 'htmlOptions'=>array('target'=>'_blank')),
                    'finish_publishing'=>array('disabled'=>($typeTab == 'approved' ? false : true), 'title'=>A::t('directory', 'Expired At'), 'type'=>'datetime', 'width'=>'140px', 'default'=>'', 'class'=>'center', 'format'=>$dateTimeFormat, 'definedValues'=>array('0000-00-00 00:00:00'=>'- '.A::t('directory', 'Undefined').' -')),
                    'advertise_plan_id'=>array('title'=>A::t('directory', 'Advertise Plan'), 'type'=>'enum', 'width'=>'100px', 'default'=>'', 'class'=>'center', 'source'=>$advertisePlanNames),
                    'is_approved'=>array('disabled'=>($typeTab == 'all' ? false : true), 'title'=>A::t('directory', 'Approved'), 'type'=>'label', 'width'=>'90px', 'default'=>'', 'class'=>'center', 'callback'=>array('function'=>'getIsApproved', 'params'=>array('allAdvertisePlans'=>$allAdvertisePlans))),
                    'order_plan_status'=>array('disabled'=>($typeTab == 'expired' ? true : false), 'title'=>A::t('directory', 'Payment Status'), 'type'=>'label', 'width'=>'100px', 'isSortable'=>false, 'default'=>'', 'class'=>'center', 'callback'=>array('function'=>'getStatus', 'params'=>array('allAdvertisePlans'=>$allAdvertisePlans))),
                    //'is_approved'=>array('disabled'=>($typeTab == 'expired' ? true : false), 'title'=>A::t('directory', 'Approved'), 'type'=>'enum', 'width'=>'90px', 'default'=>'', 'class'=>'center', 'source'=>array('0'=>'<span class="badge-gray" style="width:auto">'.A::t('directory', 'Pending').'</span>', '1'=>'<span class="badge-blue" style="width:auto">'.A::t('directory', 'Paid').'</span>', '2'=>'<span class="badge-green" style="width:auto">'.A::t('directory', 'Approved').'</span>')),
                    'category_id' => array('disabled'=>(in_array($typeTab, array('all', 'pending', 'approved')) ? false : true), 'title'=>A::t('directory', 'Categories'), 'type'=>'link', 'class'=>'center', 'headerClass'=>'center', 'width'=>'90px', 'isSortable'=>false, 'linkUrl'=>'listings/manageMyCategories/listingId/{id}/typeTab/'.$typeTab, 'linkText'=>A::t('directory', 'Categories'), 'htmlOptions'=>array('class'=>'subgrid-link'), 'prependCode'=>'[ ', 'appendCode'=>' ]'),
                    'category_count' => array('disabled'=>(in_array($typeTab, array('all', 'pending', 'approved')) ? false : true), 'title' => '', 'type'=>'enum', 'sourceField'=>'id', 'table'=>'', 'default'=>'', 'width'=>'20px', 'source'=>$categoryCounts, 'definedValues'=>array(''=>'<span class="label-zerogray">0</span>'), 'isSortable'=>true, 'class' => 'center', 'prependCode'=>'<span class="label-lightgray">', 'appendCode'=>'</span>'),
                    'is_published'=>array('disabled'=>($typeTab == 'expired' || $typeTab == 'pending' || $typeTab == 'canceled' ? true : false), 'title'=>A::t('directory', 'Published'), 'type'=>'label', 'width'=>'70px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'callback'=>array('function'=>'getIsPublished', 'params'=>array('typeTab'=>$typeTab)), 'htmlOptions'=>array()),
                    'status_expired'=>array('disabled'=>($typeTab == 'all' ? false : true), 'title'=>A::t('directory', 'Expired'), 'type'=>'enum', 'width'=>'90px', 'class'=>'center', 'source'=>array('0'=>'--', '1'=>'<span class="label-red">'.A::t('directory', 'Expired').'</span>')),
                ),
                'actions'=>$actions,
                'return'=>false,
            ));
            Listings::model()->resetTypeRelations();
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
