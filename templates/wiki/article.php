<article id="content" class="grid_12 content article">
	<header class="title clearfix">
		<h1 class="heading"><?php echo $this->wikiPage->title ?></h1>
		<ol class="capsulebar">
			<li class="item first active read"><a href="#" class="capsule"><span class="caption">Lesen</span></a></li>
			<li class="item edit"><a href="#" onclick="Article.loadEditForm( <?php echo $this->wikiPage->id; ?>, <?php echo $this->wikiPage->timestamp ?> );" class="capsule"><span class="caption">Bearbeiten</span></a></li>
			<li class="item last history"><a href="#" class="capsule"><span class="caption">&Auml;nderungsgeschichte</span></a></li>
		</ol>
	</header>
	<section>
		<?php echo $this->markDownParser->transform($this->wikiPage->content) ?>
	</section>
</article>
<div class="clear"></div>
