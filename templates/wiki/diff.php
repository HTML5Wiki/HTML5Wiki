<?php
	$leftTimestamp = date($this->translate->_('timestampFormat'), $this->leftTimestamp);
	$rightTimestamp = date($this->translate->_('timestampFormat'), $this->rightTimestamp);
?>

<article id="content" class="content compareversions">
	<header>
		<header class="grid_12 title clearfix">
			<h1 class="heading"><?php echo $this->translate->_('compareVersions') ?></h1>
		</header>
	</header>
	<div class="grid_12">
		<?php echo $this->diffRendererHelper($this->diff, $leftTimestamp, $rightTimestamp) ?>
		<a href="<?php echo $this->urlHelper('wiki', 'rollback', $this->permalink, '?to=' . $this->leftTimestamp); ?>" class="rollback">
			<?php printf($this->translate->_('rollbackTo'), date($this->translate->_('timestampFormat'), $this->leftTimestamp)); ?>
		</a>
	</div>
</article>