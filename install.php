<?php
/**
 * This file is part of the HTML5Wiki Project.
 * 
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://www.github.com/HTML5Wiki/HTML5Wiki/blob/master/LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mweibel@hsr.ch so we can send you a copy immediately.
 *
 * @author Manuel Alabor <malabor@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @category Html5Wiki
 * @package Setup
 */

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
	
	/**
	 * An implementation of InstallationWizard.<br/>
	 * It extends the default wizard with the step specifications and all necessary
	 * methods for the installation process.
	 *
	 * @author Manuel Alabor <malabor@hsr.ch>
	 */
	class Html5Wiki_InstallationWizard extends InstallationWizard {
		
		public function __construct() {
			$steps = array(
				array(
					'name' => 'Welcome'
					,'text' => '<p>Welcome to the HTML5Wiki installation wizard.</p><p>The wizard will guide you through a few steps to setup all necessary stuff like database and basic configuration.<br/>Please click <em>Next</em> when you\'re ready to start.</p>'
					,'callMethodBefore' => 'testWritePermissions'
				)
				,array(
					'name' => 'Database Setup'
					,'text' => '<p>HTML5Wiki needs a MySQL database system to store its data.</p><p>Please specify the servers hostname (mostly <em>localhost</em>), a database, a valid user and its password.</p>'
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
					'name' => 'Branding'
					,'text' => '<p>Please enter a name for your HTML5Wiki installation.</p>'
					,'input' => array(
						'wikiname' => array(
							'type' => 'text'
							,'caption' => 'Name for your wiki'
							,'mandatory' => true
						)
					)
				)
				,array(
					'name' => 'Installation type'
					,'text' => '<p>How is your webserver set up?</p><p>HTML5Wikis bootstrap is located inside the <em>web</em> directory. If you\'re able to point your webserver directly to this location, please select the first option below.</p><p>Many people are not allowed to control their hosted webservers on this level.<br/>If you\'re one of them, select the second option. All files from  <em>web</em> get moved one directory up to allow flawless interaction with HTML5Wiki.</p>'
					,'input' => array(
						'installationtype' => array(
							'type' => 'radio'
							,'caption' => 'Installation type'
							,'mandatory' => true
							,'items' => array(
								'useWeb' => 'Use <em>web/</em>'
								,'useRoot' => 'Don\'t use <em>web/</em>'
							)
						)
					)
				)
				,array(
					'name' => 'Ready to install'
					,'text' => '<p>The installation wizard has now all necessary information available.</p><p>Please click <em>Install</em> to finally set up your HTML5Wiki.<br/>Feel free to use the <em>Back</em> button to review your input before finishing the installation.</p>'
					,'nextCaption' => 'Install >'
				)
				,array(
					'name' => 'Installation done'
					,'text' => ''
					,'nextCaption' => 'Finish'
					,'callMethodBefore' => 'install'
				)
			);
			
			parent::__construct($steps);
		}
		
		
		/**
		 * Tests if the installation wizard has writepermissions for several paths.
		 *
		 * @param $stepData
		 * @return true/false
		 */
		protected function testWritePermissions($stepData) {
			$configWriteable = $this->testIfConfigWriteable();
			$parentWriteable = $this->testIfParentWritable();

			if($configWriteable === false || $parentWriteable === false) {
				$this->addMessage('info', 'No write permissions', 'The installation wizard has recognized that he has no or partially no write permissions.</p><p>You can try to fix this by changing the permissions on your server (<em>chmod 777</em>) and restart the wizard.</p><p>If not, you\'ll have to do some configuration steps by yourself. If you choose this variant, the installation wizard will tell you exactly the steps you have to do.');
			}

			return ($configWriteable && $parentWriteable);
		}

		private function testIfConfigWriteable() {
			return is_writable('../config/');
		}

		private function testIfParentWritable() {
			return is_writeable('../');
		}


		/**
		 * This method tests the databaseconnection.<br/>
		 * If everythings fine, it returns true, otherwise it adds messages to the
		 * wizard and returns false.
		 *
		 * @param $stepData
		 * @return true/false
		 */
		protected function testDatabaseConnection($stepData) {
			$host = $this->getDataValue($stepData,'database_host');
			$dbname = $this->getDataValue($stepData,'database_name');
			$user = $this->getDataValue($stepData,'database_user');
			$password = $this->getDataValue($stepData,'database_password');

			$ok = (($connection = @mysql_connect($host, $user, $password)) !== false);
			if($ok === true) {
				$ok = (@mysql_select_db($dbname, $connection) !== false);

				if($ok === false) {
					$this->addMessage('error', 'Invalid database name', 'Could not access the database "'. $dbname. '". Please make sure this database exists.');
				} else {
					@mysql_close($connection);
					$this->addMessage('info', 'Database connection ready', 'The database connection has successfully been tested.');
				}
			} else {
				$this->addMessage('error','Connection error', 'Could not connect to the host "'. $host. '". Please check host, username and password.');
			}

			return $ok;
		}

		/**
		 * Does the following steps:<br/>
		 *  - Create configuration<br/>
		 *  - Setup the database with the schema and some default articles<br/>
		 *  - Move the files from web/ one level up if necessary<br/>
		 * <br/>
		 * If  any of these steps could be executed, the user gets a report with
		 * detailed instructions what he has to do manually.
		 *
		 * @param $stepData
		 * @return true if everythings fine, false if something went wrong
		 */ 
		protected function install($stepData) {

			/* Config: */
			$config = array(
				'wikiName' => 'HTML5Wiki',
				'databaseAdapter' => 'PDO_MYSQL',
				'database' => array(
					'host'     => 'localhost',
					'dbname'   => 'html5wiki',
					'username' => 'html5wiki',
					'password' => 'mySuperCoolPassword1337'
				),
				'routing' => array(
					'defaultController' => 'wiki',
					'defaultAction'     => 'welcome'
				)
				,'languages' => array('en', 'de')
				,'defaultLanguage' => 'en'
				,'defaultTimezone' => 'Europe/Zurich'
				,'development' => false
			);

		}
		
	}
	
	/**
	 * The abstract InstallationWizard.<br/>
	 * It encapsulates all basic processing logic for running a wizard.
	 *
	 * @author Manuel Alabor <malabor@hsr.ch>
	 */
	abstract class InstallationWizard {
		
		protected $steps = array();
		protected $currentStepIndex = 0;
		private $messages = array();
		
		/**
		 * Creates a new InstallationWizard and initializes it with the steps
		 * from $stepSpecifications.
		 *
		 * @param $stepSpecifications array
		 * */
		public function __construct(array $stepSpecifications) {
			$this->steps = $stepSpecifications;
		}
		
		/**
		 * Starts the installation wizard logic.
		 * 
		 * @param $postData simply a $_POST array
		 */
		public function run(array $postData) {
			$this->deserializeStepData($postData);
			$this->processPostData($postData);
		}

		/**
		 * Returns the current step index.
		 *
		 * @return Current steps index
		 */		
		public function getCurrentStepIndex() {
			return $this->currentStepIndex;
		}
		
		/**
		 * Returns the current step.
		 *
		 * @return Current step specification
		 */
		public function getCurrentStep() {
			return $this->steps[$this->getCurrentStepIndex()];
		}
		
		/**
		 * Returns the total count of steps.
		 *
		 * @return count of steps
		 */
		public function getTotalSteps() {
			return sizeof($this->steps);
		}
		
		
		/**
		 * Takes possible present input data from the POST-request and fills it into
		 * the proper step-data-array.
		 *
		 * @param $postData simply the $_POST array
		 */
		private function processPostData(array $postData) {
			if(isset($postData['step'])) {
				$this->currentStepIndex = $postData['step'];
				$currentStepIndex = $this->currentStepIndex;

				// Read the input-data into the step specifications
				foreach($postData as $key => $value) {
					if(strstr($key, 'input_') !== false) {
						$key = substr($key, 6);
						if(!isset($this->steps[$currentStepIndex]['data'])) {
							$this->steps[$currentStepIndex]['data'] = array();
						}
						$this->steps[$currentStepIndex]['data'][$key] = $value;
					}
				}

				// Determine if the user clicked next or back:
				$wizardDirection = 'next';
				if(isset($postData['back'])) $wizardDirection = 'back';

				// Check input only if next was clicked:
				$inputOk = true;
				if($wizardDirection === 'next') $inputOk = $this->isInputOk();

				// Execute "after" method if needed and update the
				// currentstep_index regarding the button which was clicked.
				if($inputOk === true) {
					$ok = true;
					if($wizardDirection === 'next') {
						if(isset($this->steps[$currentStepIndex]['callMethodAfter'])) {
							$stepsData = array();
							if(isset($this->steps[$currentStepIndex]['data'])) {
								$stepsData = $this->steps[$currentStepIndex]['data'];
							}
							$method = $this->steps[$this->currentStepIndex]['callMethodAfter'];
							$ok = $this->$method($stepsData);
						}
						if($ok === true) $this->currentStepIndex++;
					} else if($wizardDirection === 'back') {
						$this->currentStepIndex--;
					}
					
					$currentStepIndex = $this->currentStepIndex;
				}
			}

			// Execute "before" method if present:
			if(isset($this->steps[$this->currentStepIndex]['callMethodBefore'])) {
				$stepsData = array();
				if(isset($this->steps[$this->currentStepIndex]['data'])) {
					$stepsData = $this->steps[$this->currentStepIndex]['data'];
				}
				$method = $this->steps[$this->currentStepIndex]['callMethodBefore'];
				$this->$method($stepsData);
			}
			
		}

		/**
		 * This function checks if all mandatory inputs for the step $currentstep_index
		 * are filled in.<br/>
		 * If not, a message gets added with #addMessage and false is returned.
		 * Otherwise true gets returned.
		 * 
		 * @return true/false
		 */
		private function isInputOk() {
			$missingFields = array();
			$currentStep = $this->steps[$this->currentStepIndex];
			
			
			if(isset($currentStep['input'])) {
				foreach($currentStep['input'] as $key => $input) {
					if(isset($input['mandatory']) && $input['mandatory'] === true) {
						$dataValid = true;

						if(isset($currentStep['data'][$key])) {
							if(strlen($currentStep['data'][$key]) === 0) {
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
				$this->addMessage('error', 'Missing input', $message);
			}

			return (sizeof($missingFields) === 0);
		}


		/**
		 * Serializes the data of each step in the steps-array into a hidden
		 * input-element.
		 *
		 * @return input-elements with the serialized data
		 * @see deserializeStepData
		 */
		public function serializeStepData() {
			$serialized = '';
			$steps = $this->steps;
			
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
		 * @see serializeStepData
		 */
		private function deserializeStepData(array $postSource) {
			foreach($postSource as $key => $value) {
				if(strstr($key, 'stepdata_') !== false) {
					$stepindex = intval(substr($key, 9));
					$this->steps[$stepindex]['data'] = unserialize(urldecode($value));
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
		public function renderInputs($step) {
			$rendered = '';

			if(isset($step['input'])) {
				$inputs = $step['input'];
				$tabindex = 0;
				foreach($inputs as $key => $input) {
					$tabindex++;
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
									  .  'tabindex="'. $tabindex. '" '
									  .  '/>';
							break;
						case 'radio' :
							$items = $input['items'];
							foreach($items as $itemvalue => $item) {
								if($itemvalue === $value) $selected = ' checked="checked"';
								else $selected = '';
								$rendered .= '<span class="radiocontainer">'
								          .  '<input type="radio" '
										  .  'name="input_'. $key. '" '
								  	  	  .  'id="input_'. $key. '" '
										  .  'value="'. $itemvalue. '" '
										  .  'tabindex="'. $tabindex. '" '
										  .  $selected
										  .  '/> '
										  .  $item
										  .  '</span>';
								$tabindex++;
							}
					}

					$rendered .= '</p>'."\n";
				}
			}

			return $rendered;

		}

		/**
		 * Adds a message to the wizard.
		 *
		 * @param $type [error|info]
		 * @param $title
		 * @param $message
		 */
		public function addMessage($type, $title, $message) {
			$this->messages[] = array(
				'type' => $type
				,'title' => $title
				,'text' => $message
			);
		}
		
		/**
		 * Returns the available messages.
		 *
		 * @return an array with messages
		 */
		public function getMessages() {
			return $this->messages;
		}

		/**
		 * Returns a value out of the array $data. If the specific key is not
		 * present in $data, $default gets returned.
		 * 
		 * @param $data
		 * @param $key
		 * @param $default (optional, default='')
		 * @return entrie from $data or $default
		 */
		function getDataValue($data, $key, $default='') {
			$result = $default;
			if(isset($data[$key])) $result = $data[$key];
			return $result;
		}

	}	

	/* ---------------------------------------------------------------------- */
	
	/* Run the wizard: */
	$wizard = new Html5Wiki_InstallationWizard();
	$wizard->run($_POST);
	
	$currentStepIndex = $wizard->getCurrentStepIndex();
	$currentStep = $wizard->getCurrentStep();
	$messages = $wizard->getMessages();
	
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
		.radiocontainer { display: block; font-size: 80%; margin-bottom: 4px; }
		.radiocontainer input { vertical-align: bottom; }
	</style>
</head> 
<body>
	<div class="container_12">
		<header class="grid_12 header-overall">
			<a href="<?php echo $installscript ?>" class="logo"><span class="hide">HTML5Wiki Installation Wizard</span></a>
			<nav class="main-menu">
				<ol class="menu-items clearfix">
					<li class="item install active">
						<a href="#" class="tab">Installation Wizard: Step <?php echo $currentStepIndex+1 ?> of <?php echo $wizard->getTotalSteps() ?></a>
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
				<input type="hidden" name="step" value="<?php echo $currentStepIndex ?>" />
				<?php echo $wizard->serializeStepData(); ?>
				<header class="title">
					<h2>Step <?php echo $currentStepIndex+1; ?>: <?php echo $currentStep['name'] ?></h2>
					<?php echo $currentStep['text'] ?>
				</header>
				
				<?php echo $wizard->renderInputs($currentStep); ?>
			
				<footer class="bottom-button-bar">
					<?php if($currentStepIndex < $wizard->getTotalSteps()-1) : ?>
					<input type="submit" name="next" value="<?php echo (isset($currentStep['nextCaption']) === true ? $currentStep['nextCaption'] : 'Next >'); ?>" class="large-button caption"/>
					<?php endif; ?>
					<?php if($currentStepIndex > 0) : ?>
					<input type="submit" name="back" value="Back" class="large-button caption" />
					<?php endif; ?>
				</footer>
			</form>
		</section>
		<div class="clear"></div>
	</div>
</body>
</html>