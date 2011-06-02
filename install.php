<?php
	$steps = array(
		array(
			'name' => 'Welcome'
		)
		,array(
			'name' => 'Basic Setup'
		)
		,array(
			'name' => 'Database Setup'
		)
	);
	$currentstep_index = 0;
	$currentstep = $steps[$currentstep_index];
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Installation Wizard | HTML5Wiki</title>
	<meta name="description" content="HTML5Wiki"/>
 	<meta name="author" content="HTML5Wiki"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="shortcut icon" href="web/images/favicon.ico" type="image/x-icon" />
	<link rel="icon" href="web/images/favicon.ico" type="image/ico" />
	<link rel="stylesheet" href="web/css/html5wiki.css" />
	
	<style type="text/css">
		.header-overall .menu-items .install .tab { background-image: url('web/images/icons16/wizard.png'); }
	</style>
</head> 
<body>
	<div class="container_12">
		<header class="grid_12 header-overall">
			<a href="<?php echo $PHP_SELF ?>" class="logo"><span class="hide">HTML5Wiki Installation Wizard</span></a>
			<nav class="main-menu">
				<ol class="menu-items clearfix">
					<li class="item install active">
						<a href="#" class="tab">Install Wizard</a>
					</li>
				</ol>
			</nav>
		</header>
		<div class="clear"></div>
		
		<section class="content">
			<header class="title">
				<h2>Step <?php echo $currentstep_index+1; ?>: <?php echo $currentstep['name'] ?></h2>
			</header>
			
		</section>
	</div>
</body>
</html>