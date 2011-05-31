<?php
	$this->capsulebarHelper()->addItem(
		'delete'
		,$this->translate->_('delete')
		,'delete'
		,$this->urlHelper('wiki', 'delete', $this->permalink)
	);
?>
<article id="content" class="content delete">
	<header class="grid_12 title clearfix">
		<h1 class="heading"><?php echo $this->title ?></h1>
		<?php echo $this->capsulebarHelper()->render($this->permalink); ?>
	</header>
	<div class="clear messagemarker"></div>

	<div class="grid_12">
		<h2><?php echo $this->translate->_('delete') ?></h2>
		<p><?php printf($this->translate->_('deleteQuestion'), $this->title) ?></p>
	</div>
	<div class="clear"></div>
	
	<div class="grid_12 bottom-button-bar">
		<form action="<?php echo $this->urlHelper('wiki', 'delete', $this->permalink); ?>" method="post">
			<input id="delete" name="delete" type="submit" value="<?php echo $this->translate->_('yesDelete') ?>" class="caption large-button"/>
		</form>
	</div>
	<div class="clear"></div>
</article>