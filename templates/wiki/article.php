<?php
	$basePath = $this->basePath . '/';
	$this->javascriptHelper()->appendFile($basePath . 'js/Capsulebar.js');
	$this->javascriptHelper()->appendScript('Capsulebar.init("' . $this->wikiPage->id . '", "' . $this->wikiPage->timestamp . '");');
?>
<article id="content" class="grid_12 content article">
	<header class="title clearfix">
		<h1 class="heading"><?php echo $this->wikiPage->title ?></h1>
		<?php echo $this->capsulebarHelper($this->wikiPage->permalink); ?>
	</header>
	<section>
		<?php echo $this->markDownParser->transform($this->wikiPage->content) ?>
	</section>
</article>
<div class="clear"></div>
