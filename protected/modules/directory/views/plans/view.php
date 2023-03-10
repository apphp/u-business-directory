<?php
    $this->_pageTitle = A::t('directory', 'Advertise Plans Description');
    A::app()->getClientScript()->registerCssFile('templates/default/vendors/basictable/basictable.css');
    A::app()->getClientScript()->registerScriptFile('js/modules/directory/directory.js', 0);
    A::app()->getClientScript()->registerScriptFile('templates/default/vendors/basictable/jquery.basictable.js', 1);
?>

<article id="advertise-plans" class="advertise-plans type-description hentry">
    <header class="entry-header">
        <h1 class="entry-title">
            <span><?php echo A::t('directory', 'Advertise Plans Description'); ?></span>
        </h1>
        <?php
        CWidget::create('CBreadCrumbs', array(
            'links' => array(
                array('label'=>A::t('directory', 'Home'), 'url'=>Website::getDefaultPage()),
                array('label'=>A::t('directory', 'Advertise Plans Description'))
            ),
            'wrapperClass' => 'category-breadcrumb clearfix',
            'linkWrapperTag' => 'span',
            'separator' => '&nbsp;/&nbsp;',
            'return' => false
        ));
        ?>
    </header>

    <?php
            if(is_array($advertisePlans) && !empty($advertisePlans)){
        ?>
            <div class="entry-content clearfix">
                <table class='advertise-plan-item'>
                    <tr>
                        <th class="advertise-plan-duration advertise-plan-title"></th>
                    <?php
                        foreach($advertisePlans as $plan){
                    ?>
                        <th class="advertise-plan-duration advertise-plan-value"><?php echo $plan['name']; ?></th>
                    <?php
                        }
                    ?>
                    </tr>
                    <tr>
                        <td class="advertise-plan-duration advertise-plan-title">
                            <?php echo A::t('directory', 'Duration'); ?>
                        </td>
                    <?php
                        foreach($advertisePlans as $plan){
                    ?>
                        <td class="advertise-plan-duration advertise-plan-value"<?php echo ($plan['duration'] == '-1' ? (' title="'.A::te('directory', 'Unlimited').'"') : ''); ?>>
                            <?php echo $plan['duration'] == -1 ? '&#8734;' : (isset($durations[$plan['duration']]) ? $durations[$plan['duration']] : ($plan['duration'].' '.A::t('directory', 'Days'))); ?>
                        </td>
                    <?php
                        }
                    ?>
                    </tr>
                    <tr>
                        <td class="advertise-plan-email advertise-plan-title">
                            <?php echo A::t('directory', 'Display Email'); ?>
                        </td>
                    <?php
                        foreach($advertisePlans as $plan){
                    ?>
                        <td class="advertise-plan-email advertise-plan-value">
                            <?php echo !empty($plan['email']) ? $yes : $no ?>
                        </td>
                    <?php
                        }
                    ?>
                    </tr>
                    <tr>
                        <td class="advertise-plan-phone advertise-plan-title">
                            <?php echo A::t('directory', 'Display Phone'); ?>
                        </td>
                    <?php
                        foreach($advertisePlans as $plan){
                    ?>
                        <td class="advertise-plan-phone advertise-plan-value">
                            <?php echo !empty($plan['phone']) ? $yes : $no ?>
                        </td>
                    <?php
                        }
                    ?>
                    </tr>
                    <tr>
                        <td class="advertise-plan-fax advertise-plan-title">
                            <?php echo A::t('directory', 'Display Fax'); ?>
                        </td>
                    <?php
                        foreach($advertisePlans as $plan){
                    ?>
                        <td class="advertise-plan-fax advertise-plan-value">
                            <?php echo !empty($plan['fax']) ? $yes : $no ?>
                        </td>
                    <?php
                        }
                    ?>
                    </tr>
                    <tr>
                        <td class="advertise-plan-website advertise-plan-title">
                            <?php echo A::t('directory', 'Display Website'); ?>
                        </td>
                    <?php
                        foreach($advertisePlans as $plan){
                    ?>
                        <td class="advertise-plan-website advertise-plan-value">
                            <?php echo !empty($plan['website']) ? $yes : $no ?>
                        </td>
                    <?php
                        }
                    ?>
                    </tr>
                    <tr>
                        <td class="advertise-plan-<?php echo CHtml::encode($plan['id']); ?> advertise-plan-video advertise-plan-title">
                            <?php echo A::t('directory', 'Display Video Link'); ?>
                        </td>
                    <?php
                        foreach($advertisePlans as $plan){
                    ?>
                        <td class="advertise-plan-video advertise-plan-value">
                            <?php echo !empty($plan['video_link']) ? $yes : $no ?>
                        </td>
                    <?php
                        }
                    ?>
                    </tr>
                    <tr>
                        <td class="advertise-plan-<?php echo CHtml::encode($plan['id']); ?> advertise-plan-address advertise-plan-title">
                            <?php echo A::t('directory', 'Display Address'); ?>
                        </td>
                    <?php
                        foreach($advertisePlans as $plan){
                    ?>
                        <td class="advertise-plan-address advertise-plan-value">
                            <?php echo !empty($plan['address']) ? $yes : $no ?>
                        </td>
                    <?php
                        }
                    ?>
                    </tr>
                    <tr>
                        <td class="advertise-plan-<?php echo CHtml::encode($plan['id']); ?> advertise-plan-map advertise-plan-title">
                            <?php echo A::t('directory', 'Display Map'); ?>
                        </td>
                    <?php
                        foreach($advertisePlans as $plan){
                    ?>
                        <td class="advertise-plan-map advertise-plan-value">
                            <?php echo !empty($plan['map']) ? $yes : $no ?>
                        </td>
                    <?php
                        }
                    ?>
                    </tr>
                    <tr>
                        <td class="advertise-plan-<?php echo CHtml::encode($plan['id']); ?> advertise-plan-logo advertise-plan-title">
                            <?php echo A::t('directory', 'Display Logo'); ?>
                        </td>
                    <?php
                        foreach($advertisePlans as $plan){
                    ?>
                        <td class="advertise-plan-logo advertise-plan-value">
                            <?php echo !empty($plan['logo']) ? $yes : $no ?>
                        </td>
                    <?php
                        }
                    ?>
                    </tr>
                    <tr>
                        <td class="advertise-plan-<?php echo CHtml::encode($plan['id']); ?> advertise-plan-image-count advertise-plan-title">
                            <?php echo A::t('directory', 'The Number of Thumbnails'); ?>
                        </td>
                    <?php
                        foreach($advertisePlans as $plan){
                    ?>
                        <td class="advertise-plan-image-count advertise-plan-value">
                            <?php echo $plan['images_count'] ?>
                        </td>
                    <?php
                        }
                    ?>
                    </tr>
                    <tr>
                        <td class="advertise-plan-<?php echo CHtml::encode($plan['id']); ?> advertise-plan-description advertise-plan-title">
                            <?php echo A::t('directory', 'Display Description'); ?>
                        </td>
                    <?php
                        foreach($advertisePlans as $plan){
                    ?>
                        <td class="advertise-plan-description advertise-plan-value">
                            <?php echo !empty($plan['business_description']) ? $yes : $no ?>
                        </td>
                    <?php
                        }
                    ?>
                    </tr>
                    <tr>
                        <td class="advertise-plan-<?php echo CHtml::encode($plan['id']); ?> advertise-plan-keywords-count advertise-plan-title">
                            <?php echo A::t('directory', 'Keywords Count'); ?>
                        </td>
                    <?php
                        foreach($advertisePlans as $plan){
                    ?>
                        <td class="advertise-plan-keywords-count advertise-plan-value">
                            <?php echo $plan['keywords_count'] ?>
                        </td>
                    <?php
                        }
                    ?>
                    </tr>
                    <tr>
                        <td class="advertise-plan-<?php echo CHtml::encode($plan['id']); ?> advertise-plan-inquiries-count advertise-plan-title">
                            <?php echo A::t('directory', 'Inquiries Count'); ?>
                        </td>
                    <?php
                        foreach($advertisePlans as $plan){
                    ?>
                        <td class="advertise-plan-inquiries-count advertise-plan-value" <?php echo $plan['inquiries_count'] == '-1' ? (' title="'.A::te('directory', 'Unlimited').'"') : ''; ?>>
                            <?php echo $plan['inquiries_count'] == '-1' ? '&#8734;' : $plan['inquiries_count']; ?>
                        </td>
                    <?php
                        }
                    ?>
                    </tr>
                    <tr>
                        <td class="advertise-plan-<?php echo CHtml::encode($plan['id']); ?> advertise-plan-categories-count advertise-plan-title">
                            <?php echo A::t('directory', 'Categories Count'); ?>
                        </td>
                    <?php
                        foreach($advertisePlans as $plan){
                    ?>
                        <td class="advertise-plan-categories-count advertise-plan-value">
                            <?php echo $plan['categories_count'] ?>
                        </td>
                    <?php
                        }
                    ?>
                    </tr>
                    <tr>
                        <td class="advertise-plan-<?php echo CHtml::encode($plan['id']); ?> advertise-plan-inquiry-button advertise-plan-title">
                            <?php echo A::t('directory', 'Display Inquiries Button'); ?>
                        </td>
                    <?php
                        foreach($advertisePlans as $plan){
                    ?>
                        <td class="advertise-plan-inquiry-button advertise-plan-value">
                            <?php echo !empty($plan['inquiry_button']) ? $yes : $no ?>
                        </td>
                    <?php
                        }
                    ?>
                    </tr>
                    <tr>
                        <td class="advertise-plan-<?php echo CHtml::encode($plan['id']); ?> advertise-plan-ratin-button advertise-plan-title">
                            <?php echo A::t('directory', 'Display Rating Button'); ?>
                        </td>
                    <?php
                        foreach($advertisePlans as $plan){
                    ?>
                        <td class="advertise-plan-ratin-button advertise-plan-value">
                            <?php echo !empty($plan['rating_button']) ? $yes : $no ?>
                        </td>
                    <?php
                        }
                    ?>
                    </tr>
                    <tr>
                        <td class="advertise-plan-<?php echo CHtml::encode($plan['id']); ?> advertise-plan-open-review advertise-plan-title">
                            <?php echo A::t('directory', 'Open Review'); ?>
                        </td>
                    <?php
                        foreach($advertisePlans as $plan){
                    ?>
                        <td class="advertise-plan-fax advertise-plan-value">
                            <?php echo !empty($plan['open_comments']) ? $yes : $no ?>
                        </td>
                    <?php
                        }
                    ?>
                    </tr>
                    <tr>
                        <td class="advertise-plan-<?php echo CHtml::encode($plan['id']); ?> advertise-plan-default advertise-plan-title">
                            <?php echo A::t('directory', 'Default'); ?>
                        </td>
                    <?php
                        foreach($advertisePlans as $plan){
                    ?>
                        <td class="advertise-plan-fax advertise-plan-value">
                            <?php echo !empty($plan['default']) ? $yes : $no ?>
                        </td>
                    <?php
                        }
                    ?>
                    </tr>
                </table>
            </div>
        <?php
            }
    ?>
</article>
<?php
    A::app()->getClientScript()->registerScript(
        'responsiveTable',
        'jQuery("table").basictable({
            noResize: true
        });'
    );
