<?php
	$basePath = $this->basePath . '/';
	$this->javascriptHelper()->appendFile($basePath . 'js/Capsulebar.js');
	$this->javascriptHelper()->appendScript('appendPageReadyCallback(function(){Capsulebar.init("' . $this->wikiPage->id . '");});');
?>

<article id="content" class="content history">
	<header class="grid_12 title clearfix">
		<h1 class="heading"><?php echo $this->wikiPage->title ?></h1>
		<?php echo $this->capsulebarHelper($this->wikiPage->permalink); ?>
	</header>

	<div class="clear"></div>

	<form action="<?php echo $this->urlHelper('wiki', 'diff', $this->wikiPage->permalink) ?>" method="get">
	<div class="grid_12">
		<ol class="versionhistory">
			<?php foreach($this->versions as $versionGroup => $versions) : ?>
			<ol class="timespan <?php echo $versionGroup; ?>">
				<?php foreach($versions as $version) : ?>
				<?php $user = $version->getUser(); ?>
				<li class="version">
					<input type="radio" name="left" value="<?php echo $version->timestamp; ?>" class="diffselector" />
					<input type="radio" name="right" value="<?php echo $version->timestamp; ?>" class="diffselector" />
					<span class="timestamp">
						<span class="time"><?php echo date('H:i', intval($version->timestamp)); ?></span>,
						<span class="date"><?php echo date('d.m.Y', intval($version->timestamp)); ?></span>
					</span>
					<img src="http://www.gravatar.com/avatar/<?php echo md5($user->email); ?>?s=16&d=mm" class="avatar" />
					<span class="author"><?php echo $user->name; ?></span>
					<span class="comment">&quot;<?php echo $version->versionComment; ?>&quot;</span>
				</li>
				<?php endforeach; ?>
			</ol>
			<?php endforeach; ?>
		</ol>
	</div>
	<div class="clear"></div>

	<div class="grid_12 bottom-button-bar">
		<input id="article-history" type="submit" value="Versionen Vergleichen" class="caption large-button diff-button"/>
	</div>
	</form>
	
	<div class="clear"></div>
	<?php if ($this->ajax === true): ?>
	<script type="text/javascript">
		Capsulebar.initializeClickEvents();
	</script>
	<?php endif; ?>
</article>