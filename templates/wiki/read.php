<?php
	$this->javascriptHelper()->appendFile($this->urlHelper('js','classes','capsulebar.js'));
	$this->javascriptHelper()->appendFile($this->urlHelper('js','classes','article.js'));
	$this->javascriptHelper()->appendFile($this->urlHelper('js','classes','history.js'));
	$this->javascriptHelper()->appendScript('appendPageReadyCallback("Capsulebar.init", ["' . $this->article->id . '"]);');
	$tagSlug = $this->tagSlugHelper($this->tags);
?>
<article id="content" class="content article">
	<header class="grid_12 title clearfix">
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
	<div class="clear messagemarker"></div>
	
	<section class="grid_12">
		<?php echo $this->markDownParser->transform($this->escape($this->article->content)) ?>
	</section>
	<div class="clear"></div>
</article>

<?php if ($this->ajax === true): ?>
<script type="text/javascript">
	Capsulebar.init("<?php echo $this->article->id ?>");
	
	<?php if($this->messageHelper()->hasMessages()) : ?>
	MessageController.addMessages(<?php echo json_encode($this->messageHelper()->getMessages()) ?>);
	<?php endif; ?>
</script>
<?php endif; ?>
