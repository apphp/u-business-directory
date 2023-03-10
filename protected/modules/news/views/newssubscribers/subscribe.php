<?php A::app()->getClientScript()->registerCssFile('css/modules/news/news.css'); ?>
<h1 class="title"><?php echo A::t('news', 'Subscribe to news') ?></h1>
<div class="subscribe-form-content">
    <?php echo $actionMessage; ?>
    <p id='messageInfo'><?php echo A::t('news', 'View Message Subscribe') ?></p>
    <?php
        if('no' == $typeFullName && 'no' != $typeFirstName) $fields['first_name'] = array('title' => A::t('news', 'First Name'), 'type' => 'textbox', 'tooltip' => '', 'mandatoryStar' => 'allow-required' == $typeFirstName ? true : false, 'value' => $firstName, 'htmlOptions' => array('maxLength' => '32', 'placeholder' => '', 'id' => 'news_subscribers_first_name'));
        if('no' == $typeFullName && 'no' != $typeLastName) $fields['last_name'] = array('title' => A::t('news', 'Last Name'), 'type' => 'textbox', 'tooltip' => '', 'mandatoryStar' => 'allow-required' == $typeLastName ? true : false, 'value' => $lastName, 'htmlOptions' => array('maxLength' => '32', 'placeholder' => '', 'id' => 'news_subscribers_last_name'));
        if('no' != $typeFullName) $fields['full_name'] = array('title' => A::t('news', 'Full Name'), 'type' => 'textbox', 'tooltip' => '', 'mandatoryStar' => 'allow-required' == $typeFullName ? true : false, 'value' => $fullName, 'htmlOptions' => array('maxLength' => '64', 'placeholder' => '', 'id' => 'news_subscribers_last_name'));
        $fields['email'] = array('title' => A::t('news', 'Email'), 'type' => 'textbox', 'tooltip' => '', 'mandatoryStar' => true, 'value' => $email, 'htmlOptions' => array('maxLength' => '128', 'placeholder' => '', 'id' => 'news_subscribers_email'));
        $fields['act'] = array('type' => 'hidden', 'value' => 'send');
        $formName = 'frmNewsSubscribe';
        CWidget::create('CFormView', array(
            'action'    => 'newsSubscribers/subscribe',
            'method'    => 'post',
            'htmlOptions'   => array(
                'id'   => 'subscribe-form',
                'name' => $formName,
                'autoGenerateId' => true
            ),
            'requiredFieldsAlert' => false,
            'fields'    => $fields,
            'buttons'   => array(
                'submit' => array('type' => 'submit', 'value' => A::t('news', 'Subscribe'), 'htmlOptions' => array('name' => ''))
            ),
            'buttonsPosition' => 'bottom',
            'events' => array(
                'focus' => array('field' => $errorField)
            ),
            'return' => false
        ));
        ?>
</div>
