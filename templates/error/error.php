<section class="grid_12 content error">
	<header class="title">
		<h1><?php echo $this->translate->_('systemError') ?></h1>
	</header>
	<p><?php printf($this->translate->_('systemErrorText'), $this->urlHelper()) ?></p>
	<?php if(isset($this->errorInfo)) : ?>
	<h2><?php echo $this->translate->_('additionalErrorInfo') ?></h2>
	<ul>
		<li><?php echo $this->errorInfo['text'] ?></li>
		<li><?php echo $this->errorInfo['code'] ?></li>
		<li><?php echo $this->errorInfo['file'] ?>:<?php echo $this->errorInfo['line'] ?></li>
	</ul>
	<?php endif; ?>
</section>
