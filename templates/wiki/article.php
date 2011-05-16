<?php
	$basePath = $this->basePath . '/';
	$this->javascriptHelper()->appendFile($basePath . 'js/Capsulebar.js');
	$this->javascriptHelper()->appendScript('Capsulebar.init("' . $this->wikiPage->id . '");');
?>
<article id="content" class="grid_12 content article">
	<header class="title clearfix">
		<h1 class="heading"><?php echo $this->wikiPage->title ?></h1>
		<?php echo $this->capsulebarHelper($this->wikiPage->permalink); ?>
	</header>
	<section>
		<?php echo $this->markDownParser->transform($this->wikiPage->content) ?>
	</section>
	<div class="clear"></div>
	<section>
		<h2>Tags</h2>
		<?php foreach ($this->tags as $tag): ?>
		<div class="ui-state-default ui-corner-all ui-ptags-tag">
			<div class="ui-ptags-tag-text"><?php echo $tag ?></div>
		</div>
		<?php endforeach; ?>
	</section>
	<?php if ($this->ajax === true): ?>
	<script type="text/javascript">
		Capsulebar.initializeClickEvents();
	</script>
	<?php endif; ?>
</article>
