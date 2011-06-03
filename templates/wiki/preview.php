<article id="content" class="grid_12 content article">
	<header class="title clearfix">		
		<div class="heading">
			<h1><?php echo $this->translate->_('preview') ?></h1>
		</div>
	</header>
	<section>
		<?php echo $this->markDownParser->transform($this->escape($this->content)) ?>
	</section>
	<div class="clear"></div>
</article>
