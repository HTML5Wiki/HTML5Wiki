<?php
	if($this->showCreateNewArticle === TRUE) {
		$this->javascriptHelper()->appendScript('
		appendPageReadyCallback(function() {
			MessageController.addMessage(
				"question"
				,"'. sprintf($this->translate->_('desiredArticleWithPermalinkNotFound'), $this->term). '"
				,{\'buttons\': [{
						\'text\' : \''. $this->translate->_('create'). '\'
						,\'button\' : true
						,\'callback\' : function() {
							window.location = \''. $this->basePath. '/wiki/new/'. $this->term. '\';
						}
					},{
						\'text\' : \''. $this->translate->_('noDontCreate'). '\'
					}]
				}
			);
		});
		');
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

<section id="content" class="grid_12 content searchresults">
	<header class="title">
		<h1><?php printf($this->translate->_('searchResultsFor'), $this->term); ?></h1>
	</header>
	
	<?php if(sizeof($this->results) > 0) : ?>
	<ol class="results">
		<?php foreach($this->results as $result) : ?>
			<li class="result title mediatype-<?php echo strtolower($result['model']->mediaVersionType) ?>">
				<h2>
					<a href="<?php echo $this->urlHelper('wiki', $result['model']->permalink) ?>">
						<?php echo $result['model']->getCommonName() ?>
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
</section>
