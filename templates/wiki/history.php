<?php
	$basePath = $this->basePath . '/';
	$this->javascriptHelper()->appendFile($basePath . 'js/Capsulebar.js');
	$this->javascriptHelper()->appendScript('Capsulebar.init("' . $this->wikiPage->id . '");');
?>

<article id="content" class="content history">
	<header class="grid_12 title clearfix">
		<h1 class="heading">&Auml;nderungsgeschichte: <?php echo $this->wikiPage->title ?></h1>
		<?php echo $this->capsulebarHelper($this->wikiPage->permalink); ?>
	</header>

	<div class="clear"></div>

	<div class="grid_12">
		<ol class="versionhistory">
			<?php foreach($this->wikiPage->history as $group => $groupedHistoryArticles ) { ?>
			<li class="timespan"><?php echo $group ?></li>
			<ol class="group">
					<?php foreach($groupedHistoryArticles as $historyArticle ) { ?>
					<li class="version">
						<input type="radio" name="left" value="<?php $historyArticle->timestamp; ?>" class="diffselector" />
						<input type="radio" name="right" value="<?php $historyArticle->timestamp; ?>" class="diffselector" />
						<span class="timestamp">
							<span class="time"><?php echo date('H:i', intval($historyArticle->timestamp)); ?></span>, <span class="date"><?php echo date('d.m.Y', intval($historyArticle->timestamp)); ?></span>
						</span>
						<img src="http://www.gravatar.com/avatar/<?php echo md5( trim( strtolower( $historyArticle->getUser()->email ) ) ); ?>?s=16&d=mm" class="avatar" />
						<span class="author"><?php echo $historyArticle->getUser()->toString(); ?></span>
						<span class="comment">&quot;<?php echo $historyArticle->versionComment;?>&quot;</span>
					</li>
					<?php } ?>
			</ol>
		<?php } ?>
	</div>

	<div class="clear"></div>

	<div class="grid_12 bottom-button-bar">
		<a href="index.php#" class="large-button diff-button">
			<span class="caption">Versionen vergleichen</span>
		</a>
	</div>

	<div class="clear"></div>
	<?php if ($this->ajax === true): ?>
	<script type="text/javascript">
		Capsulebar.initializeClickEvents();
	</script>
	<?php endif; ?>
</article>