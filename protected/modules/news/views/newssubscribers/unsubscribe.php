<?php A::app()->getClientScript()->registerCssFile('css/modules/news/news.css'); ?>
<h1 class="title"><?php echo A::t('news', 'Unsubscribe to News') ?></h1>
<div class="subscribe-form-content">
    <?php echo $actionMessage; ?>
    <p id='messageInfo'><?php echo A::t('news', 'Are you sure you want to unsubscribe') ?></p>
    <?php
        // open form
        $formName = 'frmNewsUnsubscribe';
        echo CHtml::openForm('newsSubscribers/unsubscribe', 'post', array('id' => 'subscribe-form', 'name'=>$formName, 'autoGenerateId'=>true, 'enctype'=>'multipart/form-data', 'class' => 'unsubscribe'));
    ?>
    <input type="hidden" name="act" value="send" />
    <div class="row">
        <label for="news_unsubscribe_email"><?php echo A::t('news', 'Email'); ?>: </label>
        <input id="news_unsubscribe_email" type="text" maxLength="128" value="<?php echo CHtml::encode($email); ?>" name="email" class="large" />
    </div>
    <div class="clear"></div>
    <div class="buttons-wrapper">
        <input value="<?php echo A::t('news', 'Yes'); ?>" type="submit" />
    </div>
    <?php echo CHtml::closeForm(); ?>
</div>
