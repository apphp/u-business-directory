<?php 
    $this->_pageTitle = A::t('directory', 'Remove Account');
    $this->_activeMenu = 'customers/myAccount';
?>
<article id="page-removeaccount" class="page-removeaccount page-customers type-page status-publish hentry">
	<header class="entry-header">
		<h1 class="entry-title">
			<span><?php echo A::t('directory', 'Remove Account'); ?></span>
		</h1>
		<?php
			$breadCrumbLinks = array(array('label'=> A::t('directory', 'Home'), 'url'=>Website::getDefaultPage()));
			$breadCrumbLinks[] = array('label' => A::t('directory', 'My Account'), 'url'=>'customers/myAccount');
			$breadCrumbLinks[] = array('label' => A::t('directory', 'Remove Account'));
			CWidget::create('CBreadCrumbs', array(
				'links' => $breadCrumbLinks,
				'wrapperClass' => 'category-breadcrumb clearfix',
				'linkWrapperTag' => 'span',
				'separator' => '&nbsp;/&nbsp;',
				'return' => false
			));
		?>
	</header>
	<div class="block-body">
	<?php
		if($accountRemoved){
			echo $actionMessage;
			echo '<script type="text/javascript">setTimeout(function(){window.location.href = "customers/logout";}, 5000);</script>';        
		}else{
			echo CHtml::openForm('customers/removeAccount', 'post', array('name'=>'remove-account-form', 'id'=>'remove-account-form')) ; 
			echo CHtml::hiddenField('act', 'send');    
			echo CHtml::tag('p', array(), A::t('directory', 'Account removal notice'));
			echo CHtml::openTag('div', array('class'=>'row row-button'));
			echo CHtml::tag('button', array('type'=>'submit', 'class'=>'button'), A::t('directory', 'Remove'));
			echo CHtml::link(A::t('directory', 'Cancel'), 'customers/myAccount', array('class'=>'button white'));
			echo CHtml::closeTag('div');
			echo CHtml::closeForm();
		}    
	?>
	</div>
</article>
