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
							window.location = \''. $this->basePath. '/wiki/create/'. $this->term. '\';
						}
					},{
						\'text\' : \''. $this->translate->_('noDontCreate'). '\'
					}]
				}
			);
		});
		');
	}

	function createMatchOriginsSlug($matchOrigins) {
		$slug = '';
		for($i = 0, $l = sizeof($matchOrigins); $i < $l; $i++) {
			$slug .= $matchOrigins[$i];
			if($i < $l-1) $slug .= ', ';
		}
		
		return $slug;
	}
?>

<section id="content" class="grid_12 content searchresults">
	<header class="title">
		<h1><?php printf($this->translate->_('searchResultsFor'), $this->term); ?></h1>
	</header>
	
	<ol class="results">
		<?php foreach($this->results as $result) : ?>
			<li class="result mediatype-<?php echo strtolower($result['model']->mediaVersionType) ?>">
				<h2 class="name">
					<a href="<?php echo $this->urlHelper('wiki', $result['model']->permalink) ?>">
						<?php echo $result['model']->getCommonName() ?>
					</a>
				</h2>
				<p>Gefunden in: <?php echo createMatchOriginsSlug($result['matchOrigins']) ?></p>
			</li>
		<?php endforeach; ?>
	</ol>
</section>
