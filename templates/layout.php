<?php
	$jsHelper = $this->javascriptHelper();
	
	/* Productive Javascripts: */
	$jsHelper->appendFile('https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js', false, true);
	$jsHelper->appendFile($this->urlHelper('js','html5wiki.js'), false, true);
	
	/* Development Javascripts: */
	$jsHelper->appendFile($this->urlHelper('js','libs','jquery.min.js'), true, false, 0);
	$jsHelper->appendFile($this->urlHelper('js','libs','jquery.markitup.js'));
	$jsHelper->appendFile($this->urlHelper('js','libs','markitup','html5wiki-set.js'));
	$jsHelper->appendFile($this->urlHelper('js','libs','jquery.ptags.min.js'));
	$jsHelper->appendFile($this->urlHelper('js','classes','translate.js'));	
	$jsHelper->appendFile($this->urlHelper('js','core.js'));
	$jsHelper->appendFile($this->urlHelper('js','classes','menu.js'));
	$jsHelper->appendFile($this->urlHelper('js','classes','messagecontroller.js'));
	$jsHelper->appendFile($this->urlHelper('js','classes','searchboxcontroller.js'));
	$jsHelper->appendFile($this->urlHelper('js','classes','html5wiki.js'));

	/* "Plain" Javascripts: */
	$jsHelper->appendScript('Translate.init('. json_encode($this->translate->getAdapter()->getMessages()). ');');
	$jsHelper->appendScript('appendPageReadyCallback("Html5Wiki.init", ["'. $this->urlHelper() .'"]);');
	$jsHelper->appendScript('appendPageReadyCallback(function() {
		SearchBoxController.initWithSearchBox(
			$("#searchBox")
			,"' . $this->urlHelper('index', 'search') . '"
		);
	});');
	
	if($this->messageHelper()->hasMessages()) {
		$jsHelper->appendScript('appendPageReadyCallback(function() {
			MessageController.addMessages('. json_encode($this->messageHelper()->getMessages()). ');
		});');
	}
	
	$frontController = Html5Wiki_Controller_Front::getInstance();
	$config = $frontController->getConfig();
	$router = $frontController->getRouter();
	
	$displayCurrentPageInMenu = $router->getController() === 'wiki' && 
			$router->getAction() !== $config->routing->defaultAction && 
			$router->getAction() !== 'new' &&
			($router->getAction() !== 'read' || $this->permalink !== $config->routing->defaultAction);
?>
<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="utf-8" />
	<title><?php echo $this->title ?> | <?php echo $config->wikiName ?></title>
	<meta name="description" content="HTML5Wiki"/>
 	<meta name="author" content="HTML5Wiki"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="shortcut icon" href="<?php echo $this->urlHelper('images','favicon.ico') ?>" type="image/x-icon" />
	<link rel="icon" href="<?php echo $this->urlHelper('images','favicon.ico') ?>" type="image/ico" />
	<link rel="stylesheet" href="<?php echo $this->urlHelper('css','html5wiki.css') ?>" />
</head> 
<body>
	<div class="container_12">
		<header class="grid_12 header-overall">
			<a href="<?php echo $this->urlHelper() ?>" class="logo"><span class="hide"><?php echo $config->wikiName ?></span></a>
			<nav class="main-menu">
				<ol class="menu-items clearfix">
					<li class="item home<?php echo !$displayCurrentPageInMenu && $router->getController() === 'wiki' && $router->getAction() !== 'new' ? ' active' : '' ?>">
						<a href="<?php echo $this->urlHelper() ?>" class="tab"><?php echo $this->translate->_("homepage") ?></a>
					</li>
					<li class="item updates<?php echo $router->getController() == 'index' && $router->getAction() == 'history' ? ' active' : '' ?>">
						<a href="<?php echo $this->urlHelper('index', 'history') ?>" class="tab"><?php echo $this->translate->_("recentChanges") ?></a>
					</li>
					<li class="item new-article<?php echo $router->getController() == 'wiki' && $router->getAction() == 'new' ? ' active' : '' ?>">
						<a href="<?php echo $this->urlHelper('wiki', 'new') ?>" class="tab"><?php echo $this->translate->_('newArticle') ?></a>
					</li>
					<?php if ($displayCurrentPageInMenu): ?>
					<li class="item article active">
						<a href="<?php echo $router->getRequest()->getUri() ?>" class="tab"><?php echo $this->title ?></a>
					</li>
					<?php endif; ?>
					<li class="item search"><input id="searchBox" placeholder="<?php echo $this->translate->_("search") ?>" class="searchterm" accesskey="s" /></li>
				</ol>
			</nav>
		</header>
		<div class="clear"></div>
		
		<?php echo $this->decoratedContent ?>
	</div>
	<?php echo $this->javascriptHelper() ?>
</body>
</html>