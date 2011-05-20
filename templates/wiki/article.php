<?php
	$basePath = $this->basePath . '/';
	$this->javascriptHelper()->appendFile($basePath . 'js/Capsulebar.js');
	$this->javascriptHelper()->appendScript('Capsulebar.init("' . $this->wikiPage->id . '");');
	
	$tagSlug = getTagSlug($this->tags);
	
	
	function getTagSlug($tags) {
		$tagSlug = '';
		
		for($i = 0, $l = sizeof($tags); $i < $l; $i++) {
			$tag = $tags[$i];
			$tagSlug .= '<span class="tag">'. $tag. '</span>';
			if($i < $l-1) $tagSlug .= ', ';
		}
		
		return $tagSlug;
	}
?>
<article id="content" class="grid_12 content article">
	<header class="title clearfix">		
		<?php if($tagSlug != '') : ?>
		<div class="heading">
			<h1><?php echo $this->wikiPage->title ?></h1>
			<p class="tags"><span class="intro"><?php echo $this->translate->_('tags') ?>:</span> <?php echo $tagSlug ?></p>
		</div>
		<?php else : ?>
		<h1 class="heading"><?php echo $this->wikiPage->title ?></h1>
		<?php endif; ?>
		
		<?php echo $this->capsulebarHelper($this->wikiPage->permalink); ?>
	</header>
	<section>
		<?php echo $this->markDownParser->transform($this->wikiPage->content) ?>
	</section>
	<div class="clear"></div>
	
	<?php if ($this->ajax === true): ?>
	<script type="text/javascript">
		Capsulebar.initializeClickEvents();
	</script>
	<?php endif; ?>
</article>
