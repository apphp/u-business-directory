<?php
    $this->_pageTitle = A::t('directory', 'My Listings');
    $this->_activeMenu = 'listings/myListings';
    A::app()->getClientScript()->registerCssFile('templates/default/vendors/basictable/basictable.css');
    //A::app()->getClientScript()->registerScriptFile('js/modules/directory/directory.js', 0);
    A::app()->getClientScript()->registerScriptFile('templates/default/vendors/basictable/jquery.basictable.js', 1);
?>

<article id="page-mylistings" class="page-my page-add-listing type-page status-publish hentry">
    <header class="entry-header">
        <h1 class="entry-title">
            <span><?php echo A::t('directory', 'My Listings'); ?></span>
        </h1>
        <?php
            $breadCrumbLinks = array(array('label'=> A::t('directory', 'Home'), 'url'=>Website::getDefaultPage()));
            $breadCrumbLinks[] = array('label'=> A::t('directory', 'Dashboard'), 'url'=>'customers/dashboard');
            $breadCrumbLinks[] = array('label' => A::t('directory', 'My Listings'), 'url'=>'listings/myListings');
            $breadCrumbLinks[] = array('label' => $listingName, 'url'=>'listings/manageMyCategories/listingId/'.$listingId.'/typeTab/'.$typeTab);
            $breadCrumbLinks[] = array('label' => A::t('directory', 'Add Category'));
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
            $listingTab = '» <a class="sub-tab active" href="listings/manageMyCategories/listingId/'.CHtml::encode($listingId).'">'.A::t('directory', 'Listing').': '.$listingName.'</a>';

            echo '<a class="sub-tab '.($typeTab == 'all' ? 'active' : 'previous').'" href="listings/myListings">'.A::t('directory', 'All').'</a>'.($typeTab == 'all' ? $listingTab : '');
            echo '<a class="sub-tab '.($typeTab == 'pending' ? 'active' : 'previous').'" href="listings/myListings/type/pending">'.A::t('directory', 'Pending').'</a>'.($typeTab == 'pending' ? $listingTab : '');
            echo '<a class="sub-tab '.($typeTab == 'approved' ? 'active' : 'previous').'" href="listings/myListings/type/approved">'.A::t('directory', 'Approved').'</a>'.($typeTab == 'approved' ? $listingTab : '');
            echo '<a class="sub-tab '.($typeTab == 'expired' ? 'active' : 'previous').'" href="listings/myListings/type/expired">'.A::t('directory', 'Expired').'</a>'.($typeTab == 'expired' ? $listingTab : '');
            echo '<a class="sub-tab '.($typeTab == 'canceled' ? 'active' : 'previous').'" href="listings/myListings/type/canceled">'.A::t('directory', 'Canceled').'</a>'.($typeTab == 'canceled' ? $listingTab : '');
        ?>
    </div>

    <div class="content">
        <?php
            //echo $actionMessage;

            CWidget::create('CDataForm', array(
                'model'             => 'ListingsCategories',
                'operationType'     => 'add',
                'action'            => 'listings/addMyCategory/listingId/'.$listingId.'/typeTab/'.$typeTab,
                'successUrl'        => 'listings/manageMyCategories/listingId/'.$listingId.'/typeTab/'.$typeTab,
                'cancelUrl'         => 'listings/manageMyCategories/listingId/'.$listingId.'/typeTab/'.$typeTab,
                'passParameters'    => false,
                'method'            => 'post',
                'htmlOptions'       => array(
                    'id'                => 'frmListingCategoryAdd',
                    'name'              => 'frmListingCategoryAdd',
                    'enctype'           => 'multipart/form-data',
                    'autoGenerateId'    => true
                ),
                'requiredFieldsAlert'   => false,
                'fields'                => array(
                    'separatorGeneralFields' => array(
                        'separatorInfo' => array('legend'=>A::t('directory', 'Add Category')),
                        'listing_id'    => array('type'=>'hidden', 'default'=>$listingId, 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array($listingId))),
                        'category_id'   => array('type'=>'select', 'title' => A::t('directory', 'Parent Category'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'unique'=>true, 'uniqueCondition'=>CConfig::get('db.prefix').'listings_categories.listing_id = '.(int)$listingId, 'type'=>'set', 'source'=>array_keys($allCategories)), 'data'=>$allCategories, 'htmlOptions'=>array('options' => $optionsAtributs)),
                    )
                ),
                'buttons'           => array(
                   'submit' => array('type'=>'submit', 'value'=>A::t('directory', 'Add'), 'htmlOptions'=>array('name'=>'', 'class'=>'button')),
                   'cancel' => array('type'=>'button', 'value'=>A::t('directory', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
                ),
                'messagesSource'    => 'core',
                'alerts'            => array('type'=>'flash'),
                'return'            => false,
            ));
        ?>

    </div>
</div>
