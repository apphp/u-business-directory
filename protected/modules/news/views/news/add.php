<!-- register tinymce files -->
<?php A::app()->getClientScript()->registerScriptFile('js/vendors/tinymce/tiny_mce.js'); ?>
<?php A::app()->getClientScript()->registerScriptFile('js/vendors/tinymce/config.js'); ?>
<?php A::app()->getClientScript()->registerCssFile('js/vendors/tinymce/general.css'); ?>

<?php
    $this->_pageTitle = A::t('news', 'News Management').' - '.A::t('news', 'Add News').' | '.CConfig::get('name');
    $this->_activeMenu = 'modules/settings/code/news';
    $this->_breadCrumbs = array(
        array('label'=>A::t('news', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('news', 'News'), 'url'=>'modules/settings/code/news'),
        array('label'=>A::t('news', 'News Management'), 'url'=>'news/manage'),
        array('label'=>A::t('news', 'Add News')),
    );
?>

<h1><?php echo A::t('news', 'News Management'); ?></h1>

<div class="bloc">
	<?php echo $tabs; ?>
		
	<div class="sub-title"><?php echo A::t('news', 'Add News'); ?></div>
    <div class="content">
        <?php
			echo $actionMessage;          
			// open form
			$formName = 'frmNewsAdd';
			echo CHtml::openForm('news/insert', 'post', array('name'=>$formName, 'autoGenerateId'=>true, 'enctype'=>'multipart/form-data'));
        ?>
        <input type="hidden" name="act" value="send">
		
		<div class="left-side" id="left-editpage">
			<span class="required-fields-alert"><?php echo A::t('core','Items marked with an asterisk (*) are required'); ?></span>
			<div class="row">
				<label for="news_header"><?php echo A::t('news', 'News Header'); ?>: <span class="required">*</span></label>
				<input id="news_header" type="text" value="<?php echo CHtml::encode($newsHeader); ?>" name="news_header" class="large" maxlength="255" />
			</div>
			<div class="row">
				<label for="news_text"><?php echo A::t('news', 'News Text'); ?>:</label>
				<div style="float:left">
					<textarea id="news_text" name="news_text" class="full" maxlength="10000"><?php echo $newsText; ?></textarea>
				</div>
				<div style="clear:both;"></div>
			</div>
	    </div>
	
	    <div class="central-part" id="right-editpage">
			<div class="row">
				<label for="is_published" class="small"><?php echo A::t('news', 'Published'); ?>:</label>
				<span id="is_published">
					<input id="is_published_0" value="0" <?php echo ($isPublished == 0 ? 'checked="checked"' : ''); ?> type="radio" name="is_published"> <label for="is_published_0"><?php echo A::t('news', 'No'); ?></label>
					<input id="is_published_1" value="1" <?php echo ($isPublished == 1 ? 'checked="checked"' : ''); ?> type="radio" name="is_published"> <label for="is_published_1"><?php echo A::t('news', 'Yes'); ?></label>
				</span>
			</div>
			<div class="row">
				<label for="language" class="small"><?php echo A::t('news', 'Language'); ?>:</label>
				<select id="language" name="language" disabled="disabled">
				<?php 
					if(is_array($langList)){
						foreach($langList as $lang){
							echo '<option value="'.$lang['code'].'" '.($language == $lang['code'] ? 'selected="selected"' : '').'>'.$lang['name_native'].'</option>';
						}
					}
				?>
				</select>
			</div>
			<div class="row">				
				<label for="intro_image" class="small"><?php echo A::t('news', 'Intro Image'); ?>:</label>
				<input type="file" id="intro_image" name="intro_image" value="" style="margin-top:5px; display: inline-block;"  />
			</div>
			<div class="buttons-wrapper">
				<input type="button" class="button white" onclick="$(location).attr('href','news/manage');" value="<?php echo A::t('news', 'Cancel'); ?>" />
				<input type="submit" value="<?php echo A::t('news', 'Create'); ?>" />
			</div>
		</div>
		
		<div class="clear"></div>	
		
	    <div class="buttons-wrapper">
			<input value="<?php echo A::t('news', 'Create'); ?>" type="submit">
			<input class="button white" onclick="$(location).attr('href','news/manage');" value="<?php echo A::t('news', 'Cancel'); ?>" type="button">
		</div>
		<br>
		<?php echo CHtml::closeForm(); ?>    
    </div>
</div>
<?php

A::app()->getClientScript()->registerCss(
	'news-edit',
	'img.intro-image {width:82px;} a.intro-delete {margin-left:100px; margin-top:10px; display: inline-block;}'
);

A::app()->getClientScript()->registerScript(
	$formName,
	'$(".intro-delete").click(function(){
		$("input[name=act]").val("delete-intro");
		$("form[name='.$formName.']").submit();	
	});'.
	(($errorField != '') ? 'document.forms["'.$formName.'"].'.$errorField.'.focus();' : ''),
	5
);

A::app()->getSession()->set('privilege_category', 'news');
A::app()->getClientScript()->registerScript('setTinyMceEditor', 'setEditor("news_text",'.(($errorField == 'news_text') ? 'true' : 'false').');', 2);

?>
