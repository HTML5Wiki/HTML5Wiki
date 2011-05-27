<?php
	$leftTimestamp = date($this->translate->_('timestampFormat'), $this->leftTimestamp);
	$rightTimestamp = date($this->translate->_('timestampFormat'), $this->rightTimestamp);
?>

<article id="content" class="content compareversions">
	<header class="grid_12 title clearfix">
		<h1 class="heading"><?php echo $this->title ?></h1>
		<?php echo $this->capsulebarHelper($this->permalink); ?>
	</header>
	<div class="clear"></div>
	
	<div class="grid_12">
		<?php echo $this->diffRendererHelper($this->diff, $leftTimestamp, $rightTimestamp) ?>
	</div>
	<div class="clear"></div>
	
	<div class="grid_12 bottom-button-bar">
		<a href="<?php echo $this->urlHelper('wiki', 'rollback', $this->permalink, '?to=' . $this->leftTimestamp); ?>" class="large-button caption">
			<?php printf($this->translate->_('rollbackTo'), date($this->translate->_('timestampFormat'), $this->leftTimestamp)); ?>
		</a>
	</div>
	<div class="clear"></div>
			
</article>