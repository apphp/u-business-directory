<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
	<meta name="keywords" content="<?php echo CHtml::encode($this->_pageKeywords); ?>" />
	<meta name="description" content="<?php echo CHtml::encode($this->_pageDescription); ?>" />
    <meta name="author" content="ApPHP Company - Advanced Power of PHP">
    <meta name="generator" content="ApPHP MVC Framework - Setup Wizard">
    <title><?php echo CHtml::encode($this->_pageTitle); ?></title>

    <base href="<?php echo A::app()->getRequest()->getBaseUrl(); ?>" />
    <link rel="shortcut icon" href="templates/setup/images/favicon.ico" />     
    <?php echo CHtml::cssFile("templates/setup/css/main.css"); ?>
	<?php echo CHtml::scriptFile('http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js'); ?>
</head>
<body>  
<div id="container">
    <header>        
        <nav>
            <?php echo A::t('setup', 'Setup Wizard'); ?><br>
            <small><?php echo A::t('setup', 'This wizard will guide you through the installation process'); ?></small>
        </nav>
    </header>
    <section>
        <aside>
            <div>
                <b><?php echo $this->_programName; ?></b><br>
                <?php echo A::t('setup', 'version'); ?>: <?php echo $this->_programVersion; ?>
            </div>            
    
            <?php
                CWidget::create('CMenu', array(
                    'type'=>'vertical',					
                    'items'=>array(
                        array('label'=>'1. '.A::t('setup', 'General'), 'url'=>'setup/index', 'readonly'=>true),
                        array('label'=>'2. '.A::t('setup', 'Check Requirements'), 'url'=>'setup/requirements', 'readonly'=>true),
                        array('label'=>'3. '.A::t('setup', 'Database Settings'), 'url'=>'setup/database', 'readonly'=>true),
                        array('label'=>'4. '.A::t('setup', 'Administrator Account'), 'url'=>'setup/administrator', 'readonly'=>true),
                        array('label'=>'5. '.A::t('setup', 'Ready to Install'), 'url'=>'setup/ready', 'readonly'=>true),
                        array('label'=>'6. '.A::t('setup', 'Completed'), 'url'=>'setup/completed', 'readonly'=>true),
                    ),
                    'selected'=>$this->_activeMenu,
                    'return'=>false
                ));
            ?>
        </aside>
        <article>
            <?php echo A::app()->view->getContent(); ?>
        </article>
    </section>    
    <footer>
        <p class="copyright"><?php echo A::t('setup', 'Copyright'); ?> &copy; <?php echo date('Y'); ?> <?php echo $this->_programName; ?></p>
        <p class="powered"><?php echo A::powered(); ?></p>
    </footer>
</div>    
</body>
</html>