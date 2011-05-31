<?php
	$this->javascriptHelper()->appendFile($this->urlHelper('js','classes','capsulebar.js'));
	$this->javascriptHelper()->appendFile($this->urlHelper('js','classes','article.js'));
	$this->javascriptHelper()->appendFile($this->urlHelper('js','classes','history.js'));
	$this->javascriptHelper()->appendScript('appendPageReadyCallback("Capsulebar.init", ["' . $this->article->id . '"]);');
	$tagSlug = $this->tagSlugHelper($this->tags);
?>
<article id="content" class="grid_12 content article">
	<header class="title clearfix">		
		<div class="heading">
			<h1><?php echo $this->escape($this->article->title) ?></h1>
			<p class="meta">
				<span class="intro"><?php echo $this->translate->_('lastChanged') ?></span> <span class="lastchange"><?php echo date($this->translate->_('timestampFormat'), $this->article->timestamp) ?></span>
				<?php if($tagSlug != '') : ?>
				&nbsp;-&nbsp;
				<span class="tags"><?php echo $this->translate->_('tags') ?>:</span> <?php echo $tagSlug ?>
				<?php endif; ?>
			</p>
		</div>
		
		<?php echo $this->capsulebarHelper($this->article->permalink); ?>
	</header>
	<section>
		<?php echo $this->markDownParser->transform($this->escape($this->article->content)) ?>
	</section>
	<div class="clear"></div>
	
	<?php if ($this->ajax === true): ?>
	<script type="text/javascript">
		Capsulebar.init("<?php echo $this->article->id ?>");
	</script>
	<?php endif; ?>
</article>
