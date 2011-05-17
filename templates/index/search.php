<section id="content" class="grid_12 content searchresults">
	<header class="title clearfix">
		<h1 class="heading"><?php echo $this->translate->_('search') ?></h1>
	</header>
	<ol class="results">
		<?php foreach($this->results as $result) : ?>
			<li class="result mediatype-<?php echo strtolower($result->mediaVersionType) ?>">
				<h2 class="name">
					<a href="<?php echo $this->urlHelper('wiki', $result->permalink) ?>">
						<?php echo $result->getCommonName() ?>
					</a>
				</h2>
			</li>
		<?php endforeach; ?>
	</ol>
</section>
