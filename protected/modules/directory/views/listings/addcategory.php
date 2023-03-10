<?php
    $this->_activeMenu = 'listings/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('directory', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('directory', 'Business Directory'), 'url'=>'modules/settings/code/directory'),
        array('label'=>A::t('directory', 'Listings Management'), 'url'=>'listings/manage'),
        array('label'=>$listingName, 'url'=>'listings/managecategories/listingId/'.$listingId),
        array('label'=>A::t('directory', 'Add Listing')),
    );
?>

<h1><?php echo A::t('directory', 'Listings Management'); ?></h1>

<div class="bloc">
    <?php echo $tabs; ?>

    <div class="sub-title">
    <?php
        echo '<a class="sub-tab previous" href="listings/manage/type/all">'.A::t('directory', 'All').'</a>';
        if($typeTab == 'all'){
            echo '» <a class="sub-tab active" href="listings/manageCategories/listingId/'.CHtml::encode($listingId).'/type/all">'.A::t('directory', 'Listing').': '.$listingName.'</a>';
        }
        echo '<a class="sub-tab previous" href="listings/manage/type/pending">'.A::t('directory', 'Pending').'</a>';
        if($typeTab == 'pending'){
            echo '» <a class="sub-tab active" href="listings/manageCategories/listingId/'.CHtml::encode($listingId).'/type/pending">'.A::t('directory', 'Listing').': '.$listingName.'</a>';
        }
        echo '<a class="sub-tab previous" href="listings/manage/type/approved">'.A::t('directory', 'Approved').'</a>';
        if($typeTab == 'approved'){
            echo '» <a class="sub-tab active" href="listings/manageCategories/listingId/'.CHtml::encode($listingId).'/type/approved">'.A::t('directory', 'Listing').': '.$listingName.'</a>';
        }
        echo '<a class="sub-tab previous" href="listings/manage/type/expired">'.A::t('directory', 'Expired').'</a>';
        if($typeTab == 'expired'){
            echo '» <a class="sub-tab active" href="listings/manageCategories/listingId/'.CHtml::encode($listingId).'/type/expired">'.A::t('directory', 'Listing').': '.$listingName.'</a>';
        }
        echo '<a class="sub-tab previous" href="listings/manage/type/canceled">'.A::t('directory', 'Canceled').'</a>';
        if($typeTab == 'canceled'){
            echo '» <a class="sub-tab active" href="listings/manageCategories/listingId/'.CHtml::encode($listingId).'/type/canceled">'.A::t('directory', 'Listing').': '.$listingName.'</a>';
        }
    ?>
        <?php echo A::t('directory', 'Add Category'); ?>
    </div>

    <div class="content">
        <?php
            echo $actionMessage;

            CWidget::create('CDataForm', array(
                'model'             => 'ListingsCategories',
                'operationType'     => 'add',
                'action'            => 'listings/addCategory/listingId/'.$listingId.'/type/'.$typeTab,
                'successUrl'        => 'listings/manageCategories/listingId/'.$listingId.'/type/'.$typeTab,
                'cancelUrl'         => 'listings/manageCategories/listingId/'.$listingId.'/type/'.$typeTab,
                'passParameters'    => false,
                'method'            => 'post',
                'htmlOptions'       => array(
                    'id'                => 'frmListingCategoryAdd',
                    'name'              => 'frmListingCategoryAdd',
                    'enctype'           => 'multipart/form-data',
                    'autoGenerateId'    => true
                ),
                'requiredFieldsAlert'   =>true,
                'fields'                =>array(
                    'listing_id'    => array('type'=>'hidden', 'default'=>$listingId, 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array($listingId))),
                    'category_id'   => array('type'=>'select', 'title' => A::t('directory', 'Parent Category'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($allCategories)), 'data'=>$allCategories, 'htmlOptions'=>array('options' => $optionsAtributs)),
                ),
                'buttons'           => array(
                   'submit' => array('type'=>'submit', 'value'=>A::t('directory', 'Add'), 'htmlOptions'=>array('name'=>'')),
                   'cancel' => array('type'=>'button', 'value'=>A::t('directory', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
                ),
                'messagesSource'    => 'core',
                'alerts'            => array('type'=>'flash'),
                'return'            => false,
            ));
        ?>

    </div>
</div>
