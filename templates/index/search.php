<?php
	if($this->showCreateNewArticle === true) {
		$title = $this->translate->_('noArticleWithPermalink');
		$text = sprintf($this->translate->_('desiredArticleWithPermalinkNotFound'), $this->term);
		$action = 'window.location = \''. $this->urlHelper('wiki','new',$this->term). '\';';
		$this->messageHelper()->appendQuestionMessage($title, $text);
		$this->messageHelper()->addButton($this->translate->_('create'), true, $action);
		$this->messageHelper()->addButton($this->translate->_('noDontCreate'), false);
	}
	
	function translateMatchOrigins($matchOrigins, $translate) {
		$translated = array();
		
		foreach($matchOrigins as $matchOrigin) {
			$translated[] = $translate->_($matchOrigin);
		}
		sort($translated);
		
		return $translated;
	}
?>

<section id="content" class="content searchresults">
	<header class="title grid_12">
		<h1><?php printf($this->translate->_('searchResultsFor'), $this->term); ?></h1>
	</header>
	<div class="clear messagemarker"></div>
	
	<div class="grid_12">
		<?php if(sizeof($this->results) > 0) : ?>
		<ol class="results">
			<?php foreach($this->results as $result) : ?>
				<li class="result title mediatype-<?php echo strtolower($result['model']->mediaVersionType) ?>">
					<h2>
						<a href="<?php echo $this->urlHelper('wiki', $result['model']->permalink) ?>">
							<?php echo $this->escape($result['model']->getCommonName()) ?>
						</a>
					</h2>
					<p class="meta">
						<span class="intro"><?php echo $this->translate->_('matchedOn') ?></span>:
						<?php echo implode(', ', translateMatchOrigins($result['matchOrigins'], $this->translate)) ?>
						&nbsp;-&nbsp;
						<span class="intro"><?php echo $this->translate->_('lastChanged') ?></span>: <span class="lastchange"><?php echo date($this->translate->_('timestampFormat'), $result['model']->timestamp) ?></span>
					</p>
				</li>
			<?php endforeach; ?>
		</ol>
		<?php else : ?>
		<h2><?php echo $this->translate->_('noSearchResultsTitle') ?></h2>
		<p><?php printf($this->translate->_('noSearchResultsText'), $this->term) ?></p>
		<?php endif; ?>
	</div>
	<div class="clear"></div>
	
</section>
