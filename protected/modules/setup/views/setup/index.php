<?php
	A::app()->view->setMetaTags('title', A::t('setup', 'General'));
	
    $this->_activeMenu = $this->_controller.'/'.$this->_action;
?>

<h1><?php echo A::t('setup', 'General'); ?></h1>
<p><?php echo A::t('setup', 'Select your preferred language and click Next to get the Setup Wizard started.'); ?></p>

<?php echo $actionMessage; ?>
<br>
<?php
    echo CWidget::create('CFormView', array(
        'action'=>'setup/index',
        'method'=>'post',
        'htmlOptions'=>array(
            'name'=>'frmSetup',
        ),
        'fields'=>$formFields,
        'buttons'=>array(
            'submit'=>array('type'=>'submit', 'value'=>A::t('setup', 'Next'), 'htmlOptions'=>array('name'=>''))
        ),
        'return'=>true,
    ));     
?>
<br>
