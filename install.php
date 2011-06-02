<?php
	/**
	 * HTML5Wiki Install Wizard
	 *
	 * @author Manuel Alabor <malabor@hsr.ch>
	 * @copyright (c) HTML5Wiki Team 2011
	 */
	ini_set('display_errors', true);
	error_reporting(E_ALL | E_STRICT);
	$installscript = "install.php";
	
	/* Definition of the Steps: */
	$steps = array(
		array(
			'name' => 'Welcome'
			,'text' => '<p>Welcome to the HTML5Wiki installation wizard.</p><p>The wizard will guide you through a few steps to setup all necessary stuff like database and basic configuration.<br/>Please click <em>Next</em> when you\'re ready to start.</p>'
			,'data' => array()
		)
		,array(
			'name' => 'Basic Setup'
			,'text' => ''
			,'input' => array(
				'wikiname' => array(
					'type' => 'text'
					,'caption' => 'Name of your wiki'
				)
			)
			,'data' => array()
		)
		,array(
			'name' => 'Database Setup'
			,'text' => ''
			,'data' => array()
		)
	);
	$currentstep_index = 0;
	
	/* ---------------------------------------------------------------------- */
	/* Run the wizards logic: */
	// Get data from other steps:
	deserializeStepData($_POST, $steps);
	
	// Process POST-data from inputs of the last step:
	$currentstep_index = processPostData($_POST, $steps);
	$currentstep = $steps[$currentstep_index];
	
	/* ---------------------------------------------------------------------- */
	
	/**
	 * Takes possible present input data from the POST-request and fills it into
	 * the proper step-data-array.
	 *
	 * @param $postData simply the $_POST array
	 * @param &$steps reference to the steps array
	 * @return the index of the current step in the wizard
	 */
	function processPostData(array $postData, array &$steps) {
		$currentstep_index = 0;
		
		if(isset($postData['step'])) {
			$stepindex = $postData['step'];
			
			// Read the input-data into the step specifications
			foreach($postData as $key => $value) {
				if(strstr($key, 'input_') !== false) {
					$key = substr($key, 6);
					$steps[$stepindex]['data'][$key] = $value;
				}
			}
			
			// Was back or next clicked?
			if(isset($_POST['next'])) {
				$currentstep_index = $stepindex + 1;
			} else if(isset($_POST['back'])) {
				$currentstep_index = $stepindex - 1;
			}
		}
		
		return $currentstep_index;
	}
	
	/**
	 * Serializes the data of each step in the steps-array into a hidden
	 * input-element.
	 *
	 * @param $steps the steps array
	 * @return input-elements with the serialized data
	 * @see deserializeStepData
	 */
	function serializeStepData(array $steps) {
		$serialized = '';
		
		foreach($steps as $index => $step) {
			$serializedStep = '';
			if(isset($step['data'])) $serializedStep = serialize($step['data']);
			if(strlen($serializedStep) > 0) {
				$serialized .= '<input type="hidden" '
				            .  'name="stepdata_'. $index. '" '
							.  'value="'. urlencode($serializedStep). '" '
							.  ' />'."\n";
			}
		}
		
		return $serialized."\n";
	}
	
	/**
	 * After a POST-Request, this method deserializes the steps-data back into
	 * the steps-array.
	 *
	 * @param $postSource simply the $_POST-variable
	 * @param &$steps a reference to the $steps array
	 * @see serializeStepData
	 */
	function deserializeStepData(array $postSource, array &$steps) {
		foreach($postSource as $key => $value) {
			if(strstr($key, 'stepdata_') !== false) {
				$stepindex = intval(substr($key, 9));
				$steps[$stepindex]['data'] = unserialize(urldecode($value));
			}
		}
	}
	
	/**
	 * Takes the input-specifications of a step and renders the proper input-elements
	 * for them.
	 *
	 * @param $step step-specifications
	 * @return input-elements
	 */
	function renderInputs($step) {
		$rendered = '';
		
		if(isset($step['input'])) {
			$inputs = $step['input'];
			
			foreach($inputs as $key => $input) {
				$value = '';
				if(isset($step['data'][$key])) $value = $step['data'][$key];
				
				switch($input['type']) {
					case 'text' :
						$rendered .= '<input type="text" '
								  .  'name="input_'. $key. '" '
							  	  .  'id="input_'. $key. '" '
								  .  'value="'. $value. '" '
								  .  'placeholder="'. $input['caption']. '" '
								  .  '/>';
						break;
				}
			}
		}
		
		return $rendered;
	}
	
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
			<a href="<?php echo $installscript ?>" class="logo"><span class="hide">HTML5Wiki Installation Wizard</span></a>
			<nav class="main-menu">
				<ol class="menu-items clearfix">
					<li class="item install active">
						<a href="#" class="tab">Install Wizard</a>
					</li>
				</ol>
			</nav>
		</header>
		<div class="clear"></div>
		
		<section class="content article">
			<form action="<?php echo $installscript ?>" method="post">
				<input type="hidden" name="step" value="<?php echo $currentstep_index ?>" />
				<?php echo serializeStepData($steps); ?>
				<header class="title">
					<h2>Step <?php echo $currentstep_index+1; ?>: <?php echo $currentstep['name'] ?></h2>
					<?php echo $currentstep['text'] ?>
				</header>
				
				<?php echo renderInputs($currentstep); ?>
			
				<footer class="bottom-button-bar">
					<?php if($currentstep_index > 0) : ?>
					<input type="submit" name="back" value="Back" class="large-button caption"/>
					<?php endif; ?>
					<?php if($currentstep_index < sizeof($steps)-1) : ?>
					<input type="submit" name="next" value="Next" class="large-button caption"/>
					<?php endif; ?>
				</footer>
			</form>
		</section>
	</div>
</body>
</html>