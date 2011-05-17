<article id="content" class="content compareversions">
	<header>
		<header class="grid_12 title clearfix">
			<h1 class="heading"><?php echo $this->translate->_('compareVersions') ?></h1>
		</header>
	</header>
	<div class="grid_12">
		<?php print_r( $this->diffRendererHelper($this->diff)) ?>
	</div>
</article>