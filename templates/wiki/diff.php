<?php
	$leftTimestamp = date($this->translate->_('timestampFormat'), $this->leftTimestamp);
	$rightTimestamp = date($this->translate->_('timestampFormat'), $this->rightTimestamp);
	
	$diffResult = $this->diffRendererHelper($this->diff, $leftTimestamp, $rightTimestamp);
	
	$equal = false;
	if(strlen($diffResult) === 0) {
		$diffResult = '<p>'. $this->translate->_('bothVersionsAreEqual'). '</p>';
		$equal = true;
	}
	
?>

<article id="content" class="content compareversions">
	<header class="grid_12 title clearfix">
		<h1 class="heading"><?php echo $this->title ?></h1>
		<?php echo $this->capsulebarHelper($this->permalink); ?>
	</header>
	<div class="clear messagemarker"></div>
	
	<div class="grid_12">
		<h2><?php echo $this->translate->_('compareVersions') ?></h2>
		<?php echo $diffResult ?>
	</div>
	<div class="clear"></div>
	
	<?php if($equal === false) : ?>
	<div class="grid_12 bottom-button-bar">
		<a href="<?php echo $this->urlHelper('wiki', 'rollback', $this->permalink, '?to=' . $this->leftTimestamp); ?>" class="large-button caption">
			<?php printf($this->translate->_('rollbackTo'), date($this->translate->_('timestampFormat'), $this->leftTimestamp)); ?>
		</a>
	</div>
	<div class="clear"></div>
	<?php endif; ?>
</article>