<?php
	$capsulebarJs = '
	$("#capsulebar-edit").click(function(e) {
		Article.loadEditForm("' . $this->wikiPage->id . '", "' . $this->wikiPage->timestamp . '");
		e.preventDefault();
	});

	$("#capsulebar-history").click(function(e) {
		Article.loadArticleHistory("' . $this->wikiPage->id . '");
		e.preventDefault();
	});';

	$this->javascriptHelper()->appendScript($capsulebarJs);
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
