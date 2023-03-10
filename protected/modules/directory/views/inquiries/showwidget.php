<!doctype html>
<!--[if IE 8]><html class="no-js oldie ie8 ie" lang="en-US"><![endif]-->
<html class="no-js" lang="en-US">
<head>
    <meta charset="UTF-8" />
    <meta name="keywords" content="<?php echo CHtml::encode($this->_pageKeywords); ?>" />
    <meta name="description" content="<?php echo CHtml::encode($this->_pageDescription); ?>" />
    <meta name="generator" content="<?php echo CConfig::get('name').' v'.CConfig::get('version'); ?>" />
    <!-- don't move it -->
    <base href="<?php echo A::app()->getRequest()->getBaseUrl(); ?>" />
    <?php echo CHtml::scriptFile('js/modules/directory/directory.js'); ?>
    <title><?php echo A::te('directory', 'Send Inquiry'); ?></title>
    <?php echo CHtml::cssFile('templates/default/css/style.css'); ?>
    <?php echo CHtml::cssFile('templates/default/css/custom.css'); ?>
    <?php echo CHtml::cssFile('templates/default/css/jquery-ui-1.10.1.custom.min.css'); ?>

    <?php echo CHtml::scriptFile('templates/default/js/jquery.js'); ?>

    <!-- template files -->
    <?php echo CHtml::scriptFile('templates/default/js/main.js'); ?>
    <style>
        body {
            min-width: 100%;
        }
        body > article {
            margin: 10px;
        }
        #inquiryThreeStep {
            float: none;
        }
    </style>
</head>
<body id="frontend" class="frontend">
    <?php echo $content; ?>
</body>
</html>
