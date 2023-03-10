<?php
    $this->_pageTitle = A::t('directory', 'Restore Password');

    A::app()->getClientScript()->registerScriptFile('js/modules/directory/directory.js');
?>
<article id="page-restorepassword" class="page-restorepassword page-customers type-page status-publish hentry">
    <header class="entry-header">
        <h1 class="entry-title">
            <span><?php echo A::t('directory', 'Restore Password'); ?></span>
        </h1>
        <?php
            $breadCrumbLinks = array(array('label'=> A::t('directory', 'Home'), 'url'=>Website::getDefaultPage()));
            $breadCrumbLinks[] = array('label' => A::t('directory', 'Restore Password'));
            CWidget::create('CBreadCrumbs', array(
                'links' => $breadCrumbLinks,
                'wrapperClass' => 'category-breadcrumb clearfix',
                'linkWrapperTag' => 'span',
                'separator' => '&nbsp;/&nbsp;',
                'return' => false
            ));
        ?>
    </header>
    <?php
        echo CHtml::openForm('customers/restorePassword', 'post', array('name'=>'restore-form', 'id'=>'restore-form')) ;
        echo CHtml::hiddenField('act', 'send');

        echo CHtml::tag('p', array(), A::t('directory', 'Password recovery instructions'));
        echo $actionMessage;

        echo '<div class="row">';
        echo CHtml::tag('label', array(), A::t('directory', 'Email').': ');
        echo CHtml::textField('email', '', array('maxlength'=>'100', 'autocomplete'=>'off'));
        echo CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'emailErrorEmpty'), A::t('directory', 'The field email cannot be empty!'));
        echo CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'emailErrorValid'), A::t('directory', 'You must provide a valid email address!'));
        echo '</div>';

        echo '<div class="row row-button">';
        echo CHtml::tag('button', array('type'=>'button', 'class'=>'button', 'data-sending'=>A::t('directory', 'Sending...'), 'onclick'=>'javascript:customers_RestorePasswordForm(this)'), A::t('directory', 'Get New Password'));
        echo '</div>';

        echo CHtml::closeForm();
    ?>
</article>
