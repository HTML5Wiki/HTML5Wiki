<?php
	$basePath = $this->basePath . '/';

	$jsHelper = $this->javascriptHelper();
	$jsHelper->appendFile($basePath . 'js/messagecontroller.js');
	$jsHelper->appendFile($basePath . 'js/searchboxcontroller.js');
	$jsHelper->appendFile($basePath . 'js/html5wiki.js');
	$jsHelper->appendFile($basePath . 'js/Article.js');
	$jsHelper->appendFile($basePath . 'js/jquery.markitup.js');
	$jsHelper->appendFile($basePath . 'js/jquery.ptags.min.js');
	$jsHelper->appendFile($basePath . 'js/markitup/html5wiki-set.js');
	$jsHelper->appendFile('http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js?ver=1.4.2');
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
					<li class="item home active"><a href="<?php echo $basePath ?>" class="tab">Startseite</a></li>
					<li class="item updates"><a href="#" class="tab">Neuste Änderungen</a></li>
					<li class="item search"><input placeholder="Suchen" class="searchterm" accesskey="s" /></li>
				</ol>
			</nav>
		</header>
		<div class="clear"></div>
		
		<?php echo $this->decoratedContent ?>
	</div>
	<?php echo $this->javascriptHelper()->toString() ?>
</body>
</html>