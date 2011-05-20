<?php
	$basePath = $this->request->getBasePath();

	$this->javascriptHelper()->appendFile($basePath . '/js/classes/article.js');
	$this->javascriptHelper()->appendFile($basePath . '/js/classes/capsulebar.js');
	$this->javascriptHelper()->appendScript('appendPageReadyCallback("Capsulebar.init", ["' . $this->wikiPage->id . '"]);');
	$tagSlug = getTagSlug($this->tags, $this);
	
	function getTagSlug($tags, $template) {
		$tagSlug = '';
		$tagTemplate = '<a href="'
					 . $template->request->getBasePath()
					 . '/index/search?term=%s&mediaType=tag" title="'
					 . $template->translate->_('searchForOtherObjectsWithTag')
					 . '" class="tag">%s</a>';
		
		for($i = 0, $l = sizeof($tags); $i < $l; $i++) {
			$tag = $tags[$i];
			$tagSlug .= sprintf($tagTemplate, $tag, $tag, $tag);
			if($i < $l-1) $tagSlug .= ', ';
		}
		
		return $tagSlug;
	}
?>
<article id="content" class="grid_12 content article">
	<header class="title clearfix">		
		<div class="heading">
			<h1><?php echo $this->wikiPage->title ?></h1>
			<p class="meta">
				<span class="intro">Zuletzt ge&auml;ndert</span>: <span class="lastchange"><?php echo date('d.m.Y H:m', $this->wikiPage->timestamp) ?></span>
				<?php if($tagSlug != '') : ?>
				&nbsp;-&nbsp;
				<span class="intro"><?php echo $this->translate->_('tags') ?>:</span> <?php echo $tagSlug ?>
				<?php endif; ?>
			</p>
		</div>
		
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
