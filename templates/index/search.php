<article id="content" class="grid_12 content article">
	<header class="title clearfix">
		<h1 class="heading"><?php echo $this->translate->_('search') ?></h1>
	</header>
	<section>
		<?php foreach ($this->result as $row): ?>
		<div class="searchresult">
			<h1><?php echo str_replace($this->term, '<em>' . $this->term . '</em>', $row->title) ?></h1>
			<div class="content">
				<?php echo $this->markDownParser->transform(str_replace($this->term, '<em>' . $this->term . '</em>', $row->content)) ?>
			</div>
		</div>
		<?endforeach; ?>
	</section>
	<div class="clear"></div>
</article>
