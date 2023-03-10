<!-- register tinymce files -->
<?php A::app()->getClientScript()->registerScriptFile('js/vendors/tinymce/tiny_mce.js'); ?>
<?php A::app()->getClientScript()->registerScriptFile('js/vendors/tinymce/config.js'); ?>
<?php A::app()->getClientScript()->registerCssFile('js/vendors/tinymce/general.css'); ?>

<?php
    $this->_pageTitle = A::t('cms', 'Pages Management').' - '.A::t('cms', 'Edit Page').' | '.CConfig::get('name');
    $this->_activeMenu = 'pages/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('cms', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('cms', 'CMS'), 'url'=>'modules/settings/code/cms'),
        array('label'=>A::t('cms', 'Pages'), 'url'=>'pages/manage'),
		array('label'=>A::t('cms', 'Edit Page')),
    );    

	$rPage = A::app()->getRequest()->getQuery('page', 'integer', 1);
	$rSortBy = A::app()->getRequest()->getQuery('sort_by', 'string');
	$rSortDir = A::app()->getRequest()->getQuery('sort_dir', 'string');				
	$additionalParams  = ($rSortBy) ? '/sort_by/'.$rSortBy : '';
	$additionalParams .= ($rSortDir) ? '/sort_dir/'.$rSortDir : '';
	$additionalParams .= ($rPage) ? '/page/'.$rPage : '';
?>

<h1><?php echo A::t('cms', 'Pages Management')?></h1>

<div class="bloc">
	<?php echo $tabs; ?>
	
	<div class="sub-title"><?php echo A::t('cms', 'Edit Page'); ?></div>
    <div class="content">        
        <?php
			echo $actionMessage;  
        	// open form
			$formName = 'frmPageEdit';
			echo CHtml::openForm('pages/update'.$additionalParams, 'post', array('name'=>$formName, 'autoGenerateId'=>true));
        ?>
        <input type="hidden" name="act" value="send">
        <input type="hidden" name="id" value="<?php echo $page->id; ?>">
        		
		<div class="left-side" id="left-editpage">
			<span class="required-fields-alert"><?php echo A::t('core','Items marked with an asterisk (*) are required'); ?></span>
			<div class="row">
				<label for="page_header"><?php echo A::t('cms', 'Page Header'); ?>: <span class="required">*</span></label>
				<input id="page_header" type="text" value="<?php echo CHtml::encode($pageHeader); ?>" name="page_header" class="large" maxlength="255">
			</div>
			<div class="row">
				<label for="page_text"><?php echo A::t('cms', 'Page Text'); ?>:</label>
				<div style="float:left">
					<textarea id="page_text" name="page_text" class="full" maxlength="10000"><?php echo $pageText; ?></textarea>
				</div>
				<div style="clear:both;"></div>
			</div>
			<fieldset>
				<legend style="cursor:pointer;" onclick="javascript:$('#meta-content').toggle('fast');"><?php echo A::t('cms', 'META Tags'); ?></legend>
				<div id="meta-content" style="display:none;">
				<div class="row">
					<label class="meta-tag" for="tag_title"><?php echo CHtml::encode(A::t('cms', 'Tag TITLE')); ?>:</label>
					<textarea maxlength="255" class="small-wide" id="tag_title" name="tag_title"><?php echo $tagTitle; ?></textarea>
				</div>
				<div class="row">
					<label class="meta-tag" for="tag_keywords"><?php echo CHtml::encode(A::t('cms', 'Meta Tag KEYWORDS')); ?>:</label>
					<textarea maxlength="255" class="middle-wide" id="tag_keywords" name="tag_keywords"><?php echo $tagKeywords; ?></textarea>
				</div>
				<div class="row">
					<label class="meta-tag" for="tag_description"><?php echo CHtml::encode(A::t('cms', 'Meta Tag DESCRIPTION')); ?>:</label>
					<textarea maxlength="255" class="middle-wide" id="tag_description" name="tag_description"><?php echo $tagDescription; ?></textarea>
				</div>
				</div>
			</fieldset>
	    </div>
	
	    <div class="central-part" id="right-editpage">
			<div class="row">
				<label for="publish_status" class="small"><?php echo A::t('cms', 'Published'); ?>:</label>
				<span id="publish_status">
					<input id="publish_status_0" value="0" <?php echo ($page->publish_status == 0 ? 'checked="checked"' : ''); ?> type="radio" name="publish_status"> <label for="publish_status_0"><?php echo A::t('cms', 'No'); ?></label>
					<input id="publish_status_1" value="1" <?php echo ($page->publish_status == 1 ? 'checked="checked"' : ''); ?> type="radio" name="publish_status"> <label for="publish_status_1"><?php echo A::t('cms', 'Yes'); ?></label>
				</span>
			</div>
			<div class="row">
				<label for="language" class="small"><?php echo A::t('cms', 'Language'); ?>:</label>
				<select id="language" name="language" onchange="onLanguageChange();">
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
				<label for="is_homepage" class="small"><?php echo A::t('cms', 'Home Page'); ?>:</label>
				<?php
                    if($page->is_homepage){
                        echo '<label>'.A::t('cms', 'Yes').'</label>';
                    }else{
                        echo CHtml::checkBox('is_homepage', false, array('uncheckValue'=>0));    
                    }					
				?>
			</div>
			<div class="row">
				<label for="sort_order" class="small"><?php echo A::t('cms', 'Sort Order'); ?>:</label>
				<input id="sort_order" type="text" value="<?php echo $page->sort_order; ?>" name="sort_order" class="small" maxlength="4 ">
			</div>
			<div class="row">
				<label class="small"><?php echo A::t('cms', 'Shortcodes'); ?>:</label>
				<?php
					echo '<label class="shortcodes">';
					foreach($shortCodes as $key => $val){
						echo '<span class="tooltip-icon tooltip-link" title="'.$val['description'].'"></span> '.$val['value'].'<br>';
					}
					echo '</label>';
				?>
			</div>			

			<div class="buttons-wrapper">
				<input type="button" class="button white" onclick="$(location).attr('href','pages/manage<?php echo $additionalParams; ?>');" value="<?php echo A::t('cms', 'Cancel'); ?>">
				<input type="submit" name="btnUpdate" value="<?php echo A::t('cms', 'Update'); ?>">
			</div>
		</div>
		
		<div class="clear"></div>	
		
	    <div class="buttons-wrapper">
            <input type="submit" name="btnUpdateClose" value="<?php echo A::t('app', 'Update & Close'); ?>">
			<input type="submit" name="btnUpdate" value="<?php echo A::t('app', 'Update'); ?>">
			<input type="button" class="button white" onclick="$(location).attr('href','pages/manage<?php echo $additionalParams; ?>');" value="<?php echo A::t('cms', 'Cancel'); ?>">
		</div>
  
		<?php echo CHtml::closeForm(); ?>    
    </div>
</div>
<?php

if($errorField != ''){
	A::app()->getClientScript()->registerScript($formName, 'document.forms["'.$formName.'"].'.$errorField.'.focus();', 2);
}

A::app()->getClientScript()->registerScript(
	'editPageTop',
	'function onLanguageChange(){
		$("form[name='.$formName.']").attr("action","pages/edit");
		$("form[name='.$formName.']").submit();
	};',
	0
);
A::app()->getSession()->set('privilege_category', 'pages');
A::app()->getClientScript()->registerScript('setTinyMceEditor', 'setEditor("page_text",'.(($errorField == 'page_text') ? 'true' : 'false').');', 2);
?>