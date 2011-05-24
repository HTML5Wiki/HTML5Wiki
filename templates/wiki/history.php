<?php
	$basePath = $this->basePath . '/';
	$this->javascriptHelper()->appendFile($basePath . 'js/classes/history.js');
	$this->javascriptHelper()->appendFile($basePath . 'js/classes/article.js');
	$this->javascriptHelper()->appendFile($basePath . 'js/classes/capsulebar.js');
	$this->javascriptHelper()->appendScript('appendPageReadyCallback("Capsulebar.init", ["' . $this->article->id . '"]);');
	$this->javascriptHelper()->appendScript('appendPageReadyCallback(History.init);');

	$numberOfVersions = countVersions($this->versions);
	$overallVersionCounter = -1;
	
	function isVersionChecked($index, $side, $numberOfVersions) {
		$checked = '';
		$checkedTemplate = 'checked="checked" ';
		
		if($numberOfVersions > 1) {
			if($side == 'left' && $index == 1) $checked = $checkedTemplate;
			else if($side == 'right' && $index == 0) $checked = $checkedTemplate;
		} else {
			$checked = $checkedTemplate;
		}
		
		return $checked;
	}
	
	function countVersions($versionGroups) {
		$total = 0;
		foreach($versionGroups as $group) {
			$total += sizeof($group);
		}
		return $total;
	}
?>

<article id="content" class="content history">
	<header class="grid_12 title clearfix">
		<h1 class="heading"><?php echo $this->article->title ?></h1>
		<?php echo $this->capsulebarHelper($this->article->permalink); ?>
	</header>
	<div class="clear"></div>

	<?php if($numberOfVersions > 1) : ?>
	<form action="<?php echo $this->urlHelper('wiki', 'diff', $this->article->permalink) ?>" method="get">
	<?php endif;?>
	<div class="grid_12">
		<ol class="versionhistory">
			<?php foreach($this->versions as $versionGroup => $versions) : ?>
			<ol class="timespan <?php echo $versionGroup; ?>">
				<?php foreach($versions as $version) : ?>
				<?php
					$overallVersionCounter++;
					$user = $version->getUser();
				?>
				<li class="version">
					<?php if($numberOfVersions > 1) : ?>
					<input type="radio" name="left" value="<?php echo $version->timestamp; ?>" class="diffselector" <?php echo isVersionChecked($overallVersionCounter,'left',$numberOfVersions) ?>/>
					<input type="radio" name="right" value="<?php echo $version->timestamp; ?>" class="diffselector" <?php echo isVersionChecked($overallVersionCounter,'right',$numberOfVersions) ?>/>
					<?php endif; ?>
					<span class="timestamp">
						<span class="time"><?php echo date($this->translate->_('timeFormat'), intval($version->timestamp)); ?></span>,
						<span class="date"><?php echo date($this->translate->_('dateFormat'), intval($version->timestamp)); ?></span>
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

	<?php if($numberOfVersions > 1) : ?>
	<div class="grid_12 bottom-button-bar">
		<input id="article-history" type="submit" value="<?php echo $this->translate->_('compareVersions') ?>" class="caption large-button diff-button"/>
	</div>
	</form>
	<?php endif; ?>
	
	<div class="clear"></div>
	<?php if ($this->ajax === true): ?>
	<script type="text/javascript">
		History.init();
		Capsulebar.initializeClickEvents();
	</script>
	<?php endif; ?>
</article>