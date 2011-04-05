<?php
	$basePath = $this->basePath . '/';
?>
<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="utf-8" />
	<title><?php echo $this->title ?> | HTML5Wiki</title>
	<meta name="description" content="">
 	<meta name="author" content="HTML5Wiki">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="<?php echo $basePath ?>images/favicon.ico" type="image/x-icon" />
	<link rel="icon" href="<?php echo $basePath ?>images/favicon.ico" type="image/ico" />
	<link rel="stylesheet" href="<?php echo $basePath ?>css/html5wiki.css" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js?ver=1.4.2"></script> 
	<script src="<?php echo $basePath ?>js/searchboxcontroller.js"></script>
	<script src="<?php echo $basePath ?>js/html5wiki.js"></script>
</head> 
<body>
	<header class="header-overall">
		<a href="#" class="logo"><span class="hide">HTML5Wiki</span></a>
		<nav class="main-menu">
			<ol class="menu-items clearfix">
				<li class="item home active"><a href="#" class="tab">Startseite</a></li>
				<li class="item updates"><a href="#" class="tab">Neuste Änderungen</a></li>
				<li class="item search"><input placeholder="Suchen" class="searchterm" accesskey="s" /></li>
			</ol>
		</nav>
	</header>

	<?php echo $this->decoratedContent ?>
	
</body>
</html>