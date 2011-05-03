<?php
	$basePath = $this->basePath . '/';
	$jsHelper = $this->javascriptHelper();
	$jsHelper->appendFile($basePath . 'js/jquery.min.js');
	$jsHelper->appendFile($basePath . 'js/jquery.markitup.js');
	$jsHelper->appendFile($basePath . 'js/markitup/html5wiki-set.js');
	$jsHelper->appendFile($basePath . 'js/jquery.ptags.min.js');
	$jsHelper->appendFile($basePath . 'js/init.js');
	$jsHelper->appendFile($basePath . 'js/messagecontroller.js');
	$jsHelper->appendFile($basePath . 'js/searchboxcontroller.js');
	$jsHelper->appendFile($basePath . 'js/Article.js');
	$jsHelper->appendFile($basePath . 'js/html5wiki.js');

	$jsHelper->appendScript('Html5Wiki.init("'. $basePath .'");');
	
	$frontController = Html5Wiki_Controller_Front::getInstance();
	$config = $frontController->getConfig();
	$router = $frontController->getRouter();
?>
<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="utf-8" />
	<title><?php echo $this->title ?> | HTML5Wiki</title>
	<meta name="description" content=""/>
 	<meta name="author" content="HTML5Wiki"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="shortcut icon" href="<?php echo $basePath ?>images/favicon.ico" type="image/x-icon" />
	<link rel="icon" href="<?php echo $basePath ?>images/favicon.ico" type="image/ico" />
	<link rel="stylesheet" href="<?php echo $basePath ?>css/html5wiki.css" />
	<link rel="stylesheet" href="<?php echo $basePath ?>css/editor.css" />
</head> 
<body>
	<div class="container_12">
		<header class="grid_12 header-overall">
			<a href="<?php echo $basePath ?>" class="logo"><span class="hide">HTML5Wiki</span></a>
			<nav class="main-menu">
				<ol class="menu-items clearfix">
					<li class="item home<?php echo $router->getController() == $config->routing->defaultController && $router->getAction() == $config->routing->defaultAction ? ' active' : '' ?>">
						<a href="<?php echo $basePath ?>" class="tab"><?php echo $this->translate->_("homepage") ?></a>
					</li>
					<li class="item updates<?php echo $router->getController() == 'index' && $router->getAction() == 'history' ? ' active' : '' ?>">
						<a href="<?php echo $this->urlHelper('index', 'history') ?>" class="tab"><?php echo $this->translate->_("recentChanges") ?></a>
					</li>
					<?php if ($router->getController() === 'wiki' && $router->getAction() !== $config->routing->defaultAction): ?>
					<li class="item article active">
						<a href="<?php echo $this->urlHelper($router->getRequest()->getUri()) ?>" class="tab"><?php echo $this->title ?></a>
					</li>
					<?php endif; ?>
					<li class="item search"><input placeholder="<?php echo $this->translate->_("search") ?>" class="searchterm" accesskey="s" /></li>
				</ol>
			</nav>
		</header>
		<div class="clear"></div>
		
		<?php echo $this->decoratedContent ?>
	</div>
	<?php echo $this->javascriptHelper() ?>
</body>
</html>