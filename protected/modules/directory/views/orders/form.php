<?php
    $this->_pageTitle = A::t('directory', 'Listing Checkout');
    $this->_activeMenu = 'orders/myOrders';
    A::app()->getClientScript()->registerCssFile('templates/default/vendors/basictable/basictable.css');
    A::app()->getClientScript()->registerScriptFile('js/modules/directory/directory.js', 0);
    A::app()->getClientScript()->registerScriptFile('templates/default/vendors/basictable/jquery.basictable.js', 1);

?>
<article id="page-myorders" class="page-listing-checkout type-page status-publish hentry">
    <header class="entry-header">
        <h1 class="entry-title">
            <span><?php echo A::t('directory', 'Listing Checkout'); ?></span>
        </h1>
        <?php
            $breadCrumbLinks = array(array('label'=> A::t('directory', 'Home'), 'url'=>Website::getDefaultPage()));
            $breadCrumbLinks[] = array('label'=> A::t('directory', 'Dashboard'), 'url'=>'customers/dashboard');
            $breadCrumbLinks[] = array('label' => A::t('directory', 'My Orders'), 'url'=>'orders/myOrders');
            $breadCrumbLinks[] = array('label' => A::t('directory', 'Listing Checkout'));
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
        <div class='content'>
<?php
            echo $actionMessage;
?>
            <fieldset>
                <legend><?php echo A::t('directory', 'Payment Info'); ?></legend>
                <div class="row"><label><?php echo '<b>'.A::t('directory', 'Name').'</b>: '.$providerSettings->name; ?></label></div>
                <div class="row"><label><?php echo '<b>'.A::t('directory', 'Description').'</b>: '.$providerSettings->description; ?></label></div>
                <div class="row"><label><?php echo '<b>'.A::t('directory', 'Instructions').'</b>: '.$providerSettings->instructions; ?></label></div>
            </fieldset>
<?php
            if(!empty($customer)){
?>
            <fieldset>
                <legend><?php echo A::t('directory', 'Customer Info'); ?></legend>
<?php
                $arrCustomer = array();
                $arrTitle = array(
                    'name' => A::t('directory', 'Name'),
                    'birthDay' => A::t('directory', 'Birth Date'),
                    'address' => A::t('directory', 'Address'),
                    'website' => A::t('directory', 'Website'),
                    'company' => A::t('directory', 'Company'),
                    'city' => A::t('directory', 'City'),
                    'zipCode' => A::t('directory', 'Zip Code'),
                    'email' => A::t('directory', 'Email')
                );

                $arrCustomer['name'] = ($customer->first_name ? $customer->first_name.' ' : '').($customer->last_name ? $customer->last_name : '');
                $arrCustomer['birthDay'] = $customer->birth_date ? CLocale::date($dateFormat, $customer->birth_date) : '';
                $arrCustomer['address'] = $customer->address ? $customer->address : '';
                $arrCustomer['website'] = $customer->website ? $customer->website : '';
                $arrCustomer['company'] = $customer->company ? $customer->company : '';
                $arrCustomer['city'] = $customer->city ? $customer->city : '';
                $arrCustomer['zipCode'] = $customer->zip_code ? $customer->zip_code : '';
                $arrCustomer['email'] = $customer->email ? $customer->email : '';

                foreach($arrCustomer as $key => $value){
                    if(!empty($value)){
                        echo '<div class="row"><label><b>'.$arrTitle[$key].'</b>: '.$value.'</label></div>';
                    }
                }
?>
            </fieldset>
<?php
            }

            if(!empty($listing)){
?>
            <fieldset class="widget widget_directory">
                <legend><?php echo A::t('directory', 'Listing Info'); ?></legend>
                <div class="featured clearfix with-thumbnail">
                    <div class="thumb-wrap fl">
                        <a href="listings/view/id/<?php echo $listing->id; ?>" target="_blank">
                            <img class="thumb" src="images/modules/directory/listings/thumbs/<?php echo $listing->image_file_thumb ? CHtml::encode($listing->image_file_thumb) : 'no_logo.jpg' ?>" />
                        </a>
                    </div>
                    <h3>
                        <a href="listings/view/id/<?php echo $listing->id?>" target="_blank"><?php echo $listing->business_name; ?></a>
                    </h3>
                <?php if($listing->address){ ?>
                    <p><?php echo A::t('directory', 'Address');?>: <?php echo $listing->address; ?><p>
                <?php } ?>
                <?php if($listing->business_description){ ?>
                    <p><?php echo A::t('directory', 'Description');?>: <?php echo CString::substr($listing->business_description, 150, '', true); ?><p>
                <?php } ?>
                    <p><b><?php echo A::t('directory', 'Price'); ?>:</b> <span class="red"><?php echo $currencySymbol.CNumber::format($order->total_price, $numberFormat, array('decimalPoints'=>2)); ?></span><p>
                </div>
            </fieldset>
<?php
            }
            echo $form;
?>
        </div>
    </div>
</article>
