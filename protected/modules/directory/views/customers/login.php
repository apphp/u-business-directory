<?php
    $this->_pageTitle = A::t('directory', 'Customer Login');
    A::app()->getClientScript()->registerScriptFile('js/modules/directory/directory.js');
?>

<article id="page-login" class="page-login page-customers type-page status-publish hentry">
    <header class="entry-header">
        <h1 class="entry-title">
            <span><?php echo A::t('directory', 'Customer Login'); ?></span>
        </h1>
        <?php
            $breadCrumbLinks = array(array('label'=> A::t('directory', 'Home'), 'url'=>Website::getDefaultPage()));
            $breadCrumbLinks[] = array('label' => A::t('directory', 'Customer Login'));
            CWidget::create('CBreadCrumbs', array(
                'links' => $breadCrumbLinks,
                'wrapperClass' => 'category-breadcrumb clearfix',
                'linkWrapperTag' => 'span',
                'separator' => '&nbsp;/&nbsp;',
                'return' => false
            ));
        ?>
    </header>
    <div id="login-form">
    <?php
        //#004 Add poupup form for validation
        if(A::app()->getCookie()->get('customerLoginAttemptsAuth') != ''){
            echo CWidget::create('CMessage', array('info', A::t('directory', 'Please confirm you are a human by clicking the button below!')));
            echo CWidget::create('CFormView', array(
                'action'=>'customers/login',
                'method'=>'post',
                'htmlOptions'=>array(
                    'name'=>'frmLogin',
                    'id'=>'frmLogin'
                ),
                'fields'=>array(
                    'customerLoginAttemptsAuth' =>array('type'=>'hidden', 'value'=>A::app()->getCookie()->get('customerLoginAttemptsAuth')),
                ),
                'buttons'=>array(
                    'submit'=>array('type'=>'submit', 'value'=>A::t('directory', 'Confirm'), 'htmlOptions'=>array('class'=>'button')),
                ),
                'return'=>true,
            ));
        }else{
            echo $actionMessage;

            echo CHtml::openForm('customers/login', 'post', array('onsubmit'=>'return customers_LoginForm(this)')) ;
            echo CHtml::hiddenField('act', 'send');

            echo '<div class="row">';
            echo '<label>'.A::t('directory', 'Username').':</label>';
            echo '<input id="login_username" type="text" name="login_username" value="" maxlength="25" data-required="true" autocomplete="off" />';
            echo CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'usernameErrorEmpty'), A::t('directory', 'Username field cannot be empty!'));
            echo '</div>';

            echo '<div class="row">';
            echo '<label>'.A::t('directory', 'Password').':</label>';
            echo '<input id="login_password" type="password" name="login_password" value="" maxlength="20" data-required="true" autocomplete="off" />';
            echo CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'passwordErrorEmpty'), A::t('directory', 'Password field cannot be empty!'));
            echo '</div>';

            echo '<div class="row">';
            if($allowRememberMe){
                echo '<input id="remember" type="checkbox" name="remember" /> <label for="remember" class="remember">'.A::t('directory', 'Remember Me').'</label><br/>';
            }
            echo '</div>';

            echo '<input type="submit" value="'.A::t('directory', 'Login').'" class="button" />';
            if(0 && !empty($buttons)){
                echo '<div class="row">';
                echo $buttons;
                echo '</div>';
            }

            echo '<div class="row">';
            if(isset($allowRegistration)){
                echo '<a href=customers/registration>'.A::t('directory', 'Create account').'</a><br/>';
            }
            if(isset($allowResetPassword)){
                echo '<a href="customers/restorePassword">'.A::t('directory', 'Forgot your password?').'</a>';
            }
            echo '</div>';

            echo CHtml::closeForm();
        }
     ?>
    </div>
</article>
