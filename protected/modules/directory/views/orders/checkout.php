<?php
    $this->_pageTitle = A::t('directory', 'Listing Checkout');
    $this->_activeMenu = 'orders/myOrders';
    A::app()->getClientScript()->registerCssFile('templates/default/vendors/basictable/basictable.css');
    A::app()->getClientScript()->registerScriptFile('js/modules/directory/directory.js', 0);
    A::app()->getClientScript()->registerScriptFile('templates/default/vendors/basictable/jquery.basictable.js', 1);

?>
<article id="page-orders-pay" class="page-my page-orders-pay type-page status-publish hentry">
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
        <div class="sub-title">
        </div>
        <div class='content'>
        <?php
            echo $actionMessage;
        ?>
            <table class="table-pay">
                <thead>
                    <tr>
                        <!--th><?php echo A::t('directory', 'Select'); ?></th-->
                        <th style="padding-left:10px;text-align:left;"><?php echo A::t('directory', 'Listing'); ?></th>
                        <th style="padding-left:10px;text-align:left;"><?php echo A::t('directory', 'Advertise Plan'); ?></th>
                        <th style="padding-left:10px;text-align:left;"><?php echo A::t('directory', 'Duration'); ?></th>
                        <th style="padding-right:10px;text-align:right;"><?php echo A::t('directory', 'Price'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td date-th="<?php echo A::te('directory', 'Listing'); ?>"><span class="bt-content"><a target="_blank" href="listings/view/id/<?php echo $listing['id']; ?>"><?php echo $listing['business_name']; ?></a></span></td>
                        <td date-th="<?php echo A::te('directory', 'Advertise Plan'); ?>"><span class="bt-content"><?php echo $adverticePlanList[$listing['advertise_plan_id']]['name']; ?></span></td>
                        <td date-th="<?php echo A::te('directory', 'Duration'); ?>"><span class="bt-content"><?php echo $adverticePlanList[$listing['advertise_plan_id']]['duration']; ?></span></td>
                        <td date-th="<?php echo A::te('directory', 'Price'); ?>" style="text-align:right;"><span class="bt-content"><?php echo $currencySymbol.CNumber::format($adverticePlanList[$listing['advertise_plan_id']]['price'], $numberFormat, array('decimalPoints'=>2)); ?></span></td>
                    </tr>
                </tbody>
            </table>
            <div id="ordersTotalPrice">
                <div class="title"><?php echo A::t('directory', 'Total Price'); ?></div>
                <div class="price"><?php echo $currencySymbol.CNumber::format($totalPrice, $numberFormat, array('decimalPoints'=>2)); ?></div>
            </div>
            <?php echo CHtml::openForm('orders/formPay/listingId/'.$listing['id'], 'post', array('id'=>'orderPayListings'));
                echo CHtml::hiddenField('act', 'send', array());
                echo CHtml::hiddenField('listings', $listing['id'], array());
            ?>
                <fieldset>
                <legend><?php echo A::t('directory', 'Payment Method'); ?>:</legend>
                    <select name="type">
                        <option value=""><?php echo A::t('directory', '- select -'); ?></option>
                        <?php
                            if(is_array($providers)){
                                foreach($providers as $key => $provider){
                                    echo '<option value="'.$provider['code'].'">'.$provider['name'].'</option>';	
                                }
                            }
                        ?>
                    </select>
                </fieldset>
            <?php
                echo CHtml::submitButton(A::t('directory', 'Go To Payment'), array('class'=>'button'));
                echo CHtml::closeForm();
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
