<?php
	$basePath = $this->basePath . '/';
	$this->javascriptHelper()->appendFile($basePath . 'js/Capsulebar.js');
	$this->javascriptHelper()->appendScript('Capsulebar.init("' . $this->wikiPage->id . '", "' . $this->wikiPage->timestamp . '");');
?>

<article id="content" class="content history">
	<header class="grid_12 title clearfix">
		<h1 class="heading">&Auml;nderungsgeschichte <?php echo $this->wikiPage->title ?></h1>
		<?php echo $this->capsulebarHelper($this->wikiPage->permalink); ?>
	</header>

	<div class="clear"></div>

	<div class="grid_12">
		<ol class="versionhistory">
			<li class="timespan">Heute</li>
			<ol class="group">
					<li class="version">
						<input type="radio" name="left" value="[timestamp]" class="diffselector" />
						<input type="radio" name="right" value="[timestamp]" class="diffselector" />
						<span class="timestamp">
							<span class="time"><?php echo date('H:i', intval($this->wikiPage->timestamp)); ?></span>, <span class="date"><?php echo date('d.m.Y', intval($this->wikiPage->timestamp)); ?></span>
						</span>
						<img src="http://www.gravatar.com/avatar/c36915ec92a666d930c1e91e2c3ba6a4?s=16&d=mm" class="avatar" />
						<span class="author">Manuel Alabor</span>
						<span class="comment">&quot;This was only a short change&quot;</span>
					</li>
			</ol>
	</div>

	<div class="clear"></div>

	<div class="grid_12 bottom-button-bar">
		<a href="index.php#" class="large-button diff-button">
			<span class="caption">Versionen vergleichen</span>
		</a>
	</div>

	<div class="clear"></div>

</article>