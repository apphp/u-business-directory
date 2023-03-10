<?php 
    $this->_pageTitle = A::t('directory', 'Customer Registration');
?>
<article id="page-conirmregistration" class="page-conirmregistration page-customers type-page status-publish hentry">
	<header class="entry-header">
		<h1 class="entry-title">
			<span><?php echo A::t('directory', 'Customer Registration'); ?></span>
		</h1>
		<?php
			$breadCrumbLinks = array(array('label'=> A::t('directory', 'Home'), 'url'=>Website::getDefaultPage()));
			$breadCrumbLinks[] = array('label'=>A::t('directory', 'Customer Registration'));
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
		<h2><?php echo A::t('directory', 'Customer Registration').', '.CAuth::getLoggedName(); ?></h2>
		<p><?php echo $actionMessage; ?></p>
	</div>
</article>
