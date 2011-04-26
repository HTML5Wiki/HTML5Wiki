<article class="content history">
	<header class="grid_12 title clearfix">
		<h1 class="heading">&Auml;nderungsgeschichte Wiki</h1>
		<ol class="capsulebar">
			<li class="item first read"><a href="index.php#" class="capsule"><span class="caption">Lesen</span></a></li>
			<li class="item edit"><a href="index.php#" class="capsule"><span class="caption">Bearbeiten</span></a></li>
			<li class="item last history active"><a href="index.php#" class="capsule"><span class="caption">&Auml;nderungsgeschichte</span></a></li>
		</ol>
	</header>

	<div class="clear"></div>

	<div class="grid_12">
		<ol class="versionhistory">
			<li class="timespan">Heute</li>
			<ol class="group">
				<?php foreach($this->wikiPages as $wikiPage) { ?>

					<li class="version">
						<input type="radio" name="left" value="[timestamp]" class="diffselector" />
						<input type="radio" name="right" value="[timestamp]" class="diffselector" />
						<span class="timestamp">
							<span class="time"><?php echo date('H:i', intval($wikiPage->timestamp)); ?></span>, <span class="date"><?php echo date('d.m.Y', intval($wikiPage->timestamp)); ?></span>
						</span>
						<img src="http://www.gravatar.com/avatar/c36915ec92a666d930c1e91e2c3ba6a4?s=16&d=mm" class="avatar" />
						<span class="author">Manuel Alabor</span>
						<span class="comment">&quot;This was only a short change&quot;</span>
					</li>
				<?php } ?>
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