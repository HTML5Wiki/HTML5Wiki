<?php
	$toTimestampDate = date($this->translate->_('timestampFormat'), $this->toTimestamp)
?>
<article id="content" class="grid_12 content rollback">
	<header class="title clearfix">		
		<div class="heading">
			<h1><?php printf($this->translate->_('rollbackTo'), $toTimestampDate) ?></h1>
		</div>
	</header>
	<section>
		<p><?php printf($this->translate->_('rollbackToQuestion'), $toTimestampDate, $this->title) ?></p>
		<div class="bottom-button-bar">
			<form action="<?php echo $this->urlHelper('wiki', 'rollback', $this->permalink, '?to=' . $this->toTimestamp); ?>" method="post">
				<input id="rollback" name="rollback" type="submit" value="<?php echo $this->translate->_('yesRollback') ?>" class="caption large-button"/>
				<a href="<?php echo $this->urlHelper('wiki', $this->permalink) ?>" class="link-button"><?php echo $this->translate->_('noDontRollback') ?></a>
			</form>
		</div>
	</section>
</article>
<div class="clear"></div>
