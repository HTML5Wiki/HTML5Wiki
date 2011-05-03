<article class="grid_12 content article">
	<header>
		<h1><?php echo $this->translate->_('recentChanges') ?></h1>
	</header>
	<section>
		<ol class="latest-articles">
			<?php foreach($this->latestArticles as $article): ?>
			<li>
				<a href="<?php echo $this->urlHelper('wiki', $article->permalink) ?>"><?php echo $article->title ?></a>
				<time><?php echo date('d.m.Y H:i', $article->mediaVersionTimestamp) ?></time>
			</li>
			<?php endforeach; ?>
		</ol>
	</section>
</article>
