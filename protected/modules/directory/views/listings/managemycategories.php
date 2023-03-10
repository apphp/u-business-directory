<?php
    $this->_pageTitle = A::t('directory', 'My Listings');
    $this->_activeMenu = 'listings/myListings';
    A::app()->getClientScript()->registerCssFile('templates/default/vendors/basictable/basictable.css');
    //A::app()->getClientScript()->registerScriptFile('js/modules/directory/directory.js', 0);
    A::app()->getClientScript()->registerScriptFile('templates/default/vendors/basictable/jquery.basictable.js', 1);
?>

<article id="page-mylistings" class="page-my page-mylistings type-page status-publish hentry">
    <header class="entry-header">
        <h1 class="entry-title">
            <span><?php echo A::t('directory', 'My Listings'); ?></span>
        </h1>
        <?php
            $breadCrumbLinks = array(array('label'=> A::t('directory', 'Home'), 'url'=>Website::getDefaultPage()));
            $breadCrumbLinks[] = array('label'=> A::t('directory', 'Dashboard'), 'url'=>'customers/dashboard');
            $breadCrumbLinks[] = array('label' => A::t('directory', 'My Listings'), 'url'=>'listings/myListings');
            $breadCrumbLinks[] = array('label' => $listingName);
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
            $listingTab = '» <a class="sub-tab active" href="javascript:void(0);">'.A::t('directory', 'Listing').': '.$listingName.'</a>';

            echo '<a class="sub-tab '.($typeTab == 'all' ? 'active' : 'previous').'" href="listings/myListings">'.A::t('directory', 'All').'</a>'.($typeTab == 'all' ? $listingTab : '');
            echo '<a class="sub-tab '.($typeTab == 'pending' ? 'active' : 'previous').'" href="listings/myListings/type/pending">'.A::t('directory', 'Pending').'</a>'.($typeTab == 'pending' ? $listingTab : '');
            echo '<a class="sub-tab '.($typeTab == 'approved' ? 'active' : 'previous').'" href="listings/myListings/type/approved">'.A::t('directory', 'Approved').'</a>'.($typeTab == 'approved' ? $listingTab : '');
            echo '<a class="sub-tab '.($typeTab == 'expired' ? 'active' : 'previous').'" href="listings/myListings/type/expired">'.A::t('directory', 'Expired').'</a>'.($typeTab == 'expired' ? $listingTab : '');
            echo '<a class="sub-tab '.($typeTab == 'canceled' ? 'active' : 'previous').'" href="listings/myListings/type/canceled">'.A::t('directory', 'Canceled').'</a>'.($typeTab == 'canceled' ? $listingTab : '');
        ?>
        </div>
        <div class='content'>
        <?php
            echo $actionMessage;

            if($displayButtonAdd){
                echo CHtml::tag('a', array('class'=>'button add-listing', 'href'=>'listings/addMyCategory/listingId/'.$listingId.'/typeTab/'.$typeTab), A::t('directory', 'Add Category'));
            }

            ListingsCategories::model()->setTypeRelations('categories');

            CWidget::create('CGridView', array(
                'model'       => 'ListingsCategories',
                'actionPath'  => 'listings/manageMyCategories/listingId/'.$listingId,
                'condition'   => CConfig::get('db.prefix')."listings_categories.listing_id = '".$listingId."'",
                'defaultOrder'=>array(CConfig::get('db.prefix').'listings_categories.id'=>'DESC'),
                'passParameters'=>true,
                'pagination'=>array('enable'=>true, 'pageSize'=>20),
                'sorting'=>true,
                'filters'=>array(
                    //'name'           => array('title'=>A::t('directory', 'Name'), 'type'=>'textbox', 'operator'=>'%like%', 'width'=>'140px', 'maxLength'=>''),
                    //'description'    => array('title'=>A::t('directory', 'Description'), 'type'=>'textbox', 'operator'=>'%like%', 'width'=>'200px', 'maxLength'=>''),
                ),
                'fields'=>array(
                    'icon'              => array('title'=>A::t('directory', 'Icon'), 'type'=>'image', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'imagePath'=>'images/modules/directory/categories/', 'defaultImage'=>'no_image.png', 'width'=>'40px', 'imageHeight'=>'20px'),
                    'icon_map'          => array('title'=>A::t('directory', 'Map Icon'), 'type'=>'image', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'imagePath'=>'images/modules/directory/categories/mapicons/', 'defaultImage'=>'no_image.png', 'width'=>'80px', 'imageHeight'=>'25px'),
                    'name'              => array('title'=>A::t('directory', 'Name'), 'type'=>'label', 'class'=>'center', 'width'=>'140px', 'headerClass'=>'center'),
                    'description'       => array('title'=>A::t('directory', 'Description'), 'type'=>'label', 'width'=>'', 'class'=>'center', 'headerClass'=>'center', 'maxLength'=>'150'),
                ),
                'actions'=>array(
                    'delete'=>array(
                        'link'=>'listings/deleteMyCategory/id/{id}/typeTab/'.$typeTab, 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('directory', 'Delete this record'), 'onDeleteAlert'=>true
                    )
                ),
                'return'=>false,
            ));

        ?>
        </div>
    </div>
</article>
