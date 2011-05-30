<section class="grid_12 content error">
	<header class="title">
		<h1><?php echo $this->translate->_('internalError') ?></h1>
	</header>
	<p><?php printf($this->translate->_('internalErrorText'), $this->urlHelper()) ?></p>
	<?php if(isset($this->errorInfo)) : ?>
	<h2><?php echo $this->translate->_('additionalErrorInfo') ?></h2>
	<p><?php echo nl2br($this->errorInfo['exception']); ?></p>
	<?php endif; ?>
</section>
