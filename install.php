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
	
	/* ---------------------------------------------------------------------- */
	
	/* Definition of the Steps: */
	$steps = array(
		array(
			'name' => 'Welcome'
			,'text' => '<p>Welcome to the HTML5Wiki installation wizard.</p><p>The wizard will guide you through a few steps to setup all necessary stuff like database and basic configuration.<br/>Please click <em>Next</em> when you\'re ready to start.</p>'
			,'data' => array()
		)
		,array(
			'name' => 'Database Setup'
			,'text' => '<p>HTML5Wiki needs a MySQL database system to store its data.</p><p>Please specify the servers hostname (mostly <em>localhost</em>), a database, a valid user and its password.</p>'
			,'data' => array()
			,'input' => array(
				'database_host' => array(
					'type' => 'text'
					,'caption' => 'Database host'
					,'placeholder' => 'localhost'
					,'mandatory' => true
				)
				,'database_name' => array(
					'type' => 'text'
					,'caption' => 'Database name'
					,'mandatory' => true
				)
				,'database_user' => array(
					'type' => 'text'
					,'caption' => 'Database user'
					,'mandatory' => true
				)
				,'database_password' => array(
					'type' => 'text'
					,'caption' => 'Database password'
					,'placeholder' => 'optional'
				)
			)
			,'callMethodAfter' => 'testDatabaseConnection'
		)
		,array(
			'name' => 'Wiki'
			,'text' => '<p>Please enter a name for your HTML5Wiki installation.</p>'
			,'input' => array(
				'wikiname' => array(
					'type' => 'text'
					,'caption' => 'Name for your wiki'
					,'mandatory' => true
				)
			)
			,'data' => array()
		)
	);
	$currentstep_index = 0;
	$messages = array();
	
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
			$currentstep_index = $postData['step'];
			
			// Read the input-data into the step specifications
			foreach($postData as $key => $value) {
				if(strstr($key, 'input_') !== false) {
					$key = substr($key, 6);
					$steps[$currentstep_index]['data'][$key] = $value;
				}
			}
			
			// Determine if the user clicked next or back:
			$wizardDirection = 'next';
			if(isset($postData['back'])) $wizardDirection = 'back';
			
			// Check input only if next was clicked:
			$inputOk = true;
			if($wizardDirection === 'next') $inputOk = isInputOk($steps, $currentstep_index);
			
			// Execute method if needed and update the currentstep_index regarding
			// the button which was clicked.
			if($inputOk === true) {
				$ok = true;
				if($wizardDirection === 'next') {
					if(isset($steps[$currentstep_index]['callMethodAfter'])) {
						$stepsData = $steps[$currentstep_index]['data'];
						$ok = $steps[$currentstep_index]['callMethodAfter']($stepsData);
					}
					if($ok === true) $currentstep_index++;
				} else if($wizardDirection === 'back') {
					$currentstep_index--;
				}
			}
		}
		
		return $currentstep_index;
	}
	
	/**
	 * This function checks if all mandatory inputs for the step $currentstep_index
	 * are filled in.<br/>
	 * If not, a message gets added with #addMessage and false is returned.
	 * Otherwise true gets returned.
	 * 
	 * @param $steps
	 * @param $currentstep_index
	 * @return true/false
	 */
	function isInputOk(array $steps, $currentstep_index) {
		$missingFields = array();
		if(isset($steps[$currentstep_index]['input'])) {
			foreach($steps[$currentstep_index]['input'] as $key => $input) {
				if(isset($input['mandatory']) && $input['mandatory'] === true) {
					$dataValid = true;
					
					if(isset($steps[$currentstep_index]['data'][$key])) {
						if(strlen($steps[$currentstep_index]['data'][$key]) === 0) {
							$dataValid = false;
						}
					} else {
						$dataValid = false;
					}
					
					if($dataValid === false) $missingFields[] = $input['caption'];
				}
			}
		}
		
		// Add message if needed:
		if(sizeof($missingFields) > 0) {
			$message = 'Please fill in the following field(s) you missed:<br/>';
			foreach($missingFields as $field) {
				$message .= '&nbsp;-&nbsp;'. $field. '<br/>';
			}
			addMessage('error', 'Missing input', $message);
		}
		
		return (sizeof($missingFields) === 0);
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
				$placeholder = '';
				if(isset($step['data'][$key])) $value = $step['data'][$key];
				if(isset($input['placeholder'])) $placeholder = $input['placeholder'];
				
				$rendered .= '<p>'
						  .  '<label for="input_'. $key. '">'
						  .  $input['caption']
						  .  '</label>';
				
				switch($input['type']) {
					case 'text' :
						$rendered .= '<input type="text" '
								  .  'name="input_'. $key. '" '
							  	  .  'id="input_'. $key. '" '
								  .  'value="'. $value. '" '
								  .  'placeholder="'. $placeholder. '" '
								  .  '/>';
						break;
				}
				
				$rendered .= '</p>'."\n";
			}
		}
		
		return $rendered;
	}
	
	/* ---------------------------------------------------------------------- */
	
	function addMessage($type, $title, $message) {
		global $messages;  // ugly, but only a bit ;)
		
		$messages[] = array(
			'type' => $type
			,'title' => $title
			,'text' => $message
		);
	}
	
	/* ---------------------------------------------------------------------- */
	/**
	 * This method tests the databaseconnection.<br/>
	 * If everythings fine, it returns true, otherwise it adds messages to the
	 * wizard and returns false.
	 *
	 * @param $stepData
	 * @return true/false
	 */
	function testDatabaseConnection($stepData) {
		$host = getDataValue($stepData,'database_host');
		$dbname = getDataValue($stepData,'database_name');
		$user = getDataValue($stepData,'database_user');
		$password = getDataValue($stepData,'database_password');
		
		$ok = (($connection = @mysql_connect($host, $user, $password)) !== false);
		if($ok === true) {
			$ok = (mysql_select_db($dbname, $connection) !== false);
			
			if($ok === false) {
				addMessage('error', 'Invalid database name', 'Could not access the database "'. $dbname. '". Please make sure this database exists.');
			}
		} else {
			addMessage('error','Connection error', 'Could not connect to the host "'. $host. '". Please check host, username and password.');
		}
		
		return $ok;
	}
	
	function getDataValue($data, $key, $default='') {
		$result = $default;
		if(isset($data[$key])) $result = $data[$key];
		return $result;
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
		input[type=text] { font-size: 110%; margin-bottom: 8px; width: 300px; }
		label { display: block; font-size: 85%; margin-bottom: 2px; }
		.editor p { margin-bottom: 6px; }
		.box h3 { margin-top: 3px;}
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
		
		<section class="content editor grid_12">
			<?php if(sizeof($messages) > 0) : ?>
			<?php foreach($messages as $message) : ?>
			<div class="box <?php echo $message['type'] ?>">
				<h3><?php echo $message['title'] ?></h3>
				<p><?php echo $message['text'] ?></p>
			</div>
			<?php endforeach; ?>
			<?php endif; ?>
			
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
		<div class="clear"></div>
	</div>
</body>
</html>