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

/* ---------------------------------------------------------------------- */

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
	
	const PROPERTY_WIKINAME = 'wikiname';
	const PROPERTY_DATABASE_HOST = 'database_host';
	const PROPERTY_DATABASE_NAME = 'database_name';
	const PROPERTY_DATABASE_USER = 'database_user';
	const PROPERTY_DATABASE_PASSWORD = 'database_password';
	const PROPERTY_INSTALLATION_TYPE = 'installationtype';
	const INSTALLATION_TYPE_WEB = 'useWeb';
	const INSTALLATION_TYPE_ROOT = 'useRoot';
	const FILE_CONFIG = 'config/config.php';
	const FILE_DATABASE_SCHEMA = 'data/sql/html5wiki_schema.sql';
	const FOLDER_WEB = 'web/';
	const FOLDER_ROOT = '';
	

	
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
					self::PROPERTY_DATABASE_HOST => array(
						'type' => 'text'
						,'caption' => 'Database host'
						,'placeholder' => 'localhost'
						,'mandatory' => true
					)
					,self::PROPERTY_DATABASE_NAME => array(
						'type' => 'text'
						,'caption' => 'Database name'
						,'mandatory' => true
					)
					,self::PROPERTY_DATABASE_USER => array(
						'type' => 'text'
						,'caption' => 'Database user'
						,'mandatory' => true
					)
					,self::PROPERTY_DATABASE_PASSWORD => array(
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
					self::PROPERTY_WIKINAME => array(
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
					self::PROPERTY_INSTALLATION_TYPE => array(
						'type' => 'radio'
						,'caption' => 'Installation type'
						,'mandatory' => true
						,'items' => array(
							self::INSTALLATION_TYPE_WEB => 'Use <em>web/</em>'
							,self::INSTALLATION_TYPE_ROOT => 'Don\'t use <em>web/</em>'
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
				,'text' => '<p>The installation steps were executed. If there was any problem, please read the displayed messages above precisely.<br/>They will include information how you can fullify the installation by yourself.</p><p>When everything is done, click <a href="wiki/">here</a> to open your fresh installed HTML5Wiki.</p>'
				,'nextCaption' => 'Finish'
				,'callMethodBefore' => 'install'
				,'allowBack' => false
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
		return is_writable(self::FILE_CONFIG);
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
				$this->addMessage('info', 'Database connection verified', 'The database connection has successfully been tested.');
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
		$configOk = $this->setupConfig(self::FILE_CONFIG);
		$databaseOk = $this->setupDatabase(self::FILE_DATABASE_SCHEMA);
		$installationTypeOk = $this->setupInstallationtype();
		$htaccessOk = $this->setupHtaccessFile();
		
		if($configOk === true && $databaseOk === true && $installationTypeOk === true && $htaccessOk === true) {
			$this->addMessage('info','Installation successfull','Congratulations! All installation steps are successfully completed.');
		}
	}
	
	/**
	 * Builds a configuration array with the given parameters and writes it to
	 * its target file in the config folder.
	 *
	 * @param $targetFile
	 * @return true/false regarding success
	 */
	private function setupConfig($targetFile) {
		$configOk = true;
		$wikiname = $this->wizardData[self::PROPERTY_WIKINAME];
		$database_host = $this->wizardData[self::PROPERTY_DATABASE_HOST];
		$database_name = $this->wizardData[self::PROPERTY_DATABASE_NAME];
		$database_user = $this->wizardData[self::PROPERTY_DATABASE_USER];
		$database_password = $this->wizardData[self::PROPERTY_DATABASE_PASSWORD];
		
		/* Create config string: */
		$config = '$config = array('. "\n"
				. '\'wikiName\' => \''. $wikiname. '\','. "\n"
				. '\'databaseAdapter\' => \'PDO_MYSQL\','. "\n"
				. '\'database\' => array('. "\n"
				. '	\'host\'     => \''. $database_host. '\','. "\n"
				. '	\'dbname\'   => \''. $database_name. '\','. "\n"
				. '	\'username\' => \''. $database_user. '\','. "\n"
				. '	\'password\' => \''. $database_password. '\''. "\n"
				. '),'. "\n"
				. '\'routing\' => array('. "\n"
				. '	\'defaultController\' => \'wiki\','. "\n"
				. '	\'defaultAction\'     => \'welcome\''. "\n"
				. ')'. "\n"
				. ',\'languages\' => array(\'en\', \'de\')'. "\n"
				. ',\'defaultLanguage\' => \'en\''. "\n"
				. ',\'defaultTimezone\' => \'Europe/Zurich\''. "\n"
				. ',\'development\' => false'. "\n"
			. ');'. "\n";
		
		/* Try writing the file: */
		$configOk = $this->writeFile(self::FILE_CONFIG, $config);
		
		if($configOk === false) {
			$this->addMessage('error','Could not create configuration file', 'Please create the file <em>config/config.php</em> by yourself and copy paste the following configuration data into it:</p><p class="white-paper">'. nl2br($config));
		}
		
		return $configOk;
	}
	
	/**
	 * Tries to setup the database with the give schema.
	 *
	 * @param $schemaFile
	 * @return true/false regarding success
	 */
	private function setupDatabase($schemaFile) {
		$databaseOk = true;
		$sql_schema = '';
		$database_host = $this->wizardData[self::PROPERTY_DATABASE_HOST];
		$database_name = $this->wizardData[self::PROPERTY_DATABASE_NAME];
		$database_user = $this->wizardData[self::PROPERTY_DATABASE_USER];
		$database_password = $this->wizardData[self::PROPERTY_DATABASE_PASSWORD];
		
		/* Try to read schema: */
		if(is_readable($schemaFile)) {
			if(($sql_schema = file_get_contents($schemaFile)) === false) {
				$databaseOk = false;
			}
		} else {
			$databaseOk = false;
		}
		
		/* Run the sql schema on the database: */
		if(strlen($sql_schema) > 0) {
			$connection = mysql_connect($database_host, $database_user, $database_password);
			if($connection !== false) {
				if(mysql_select_db($database_name, $connection) !== false) {
					// The schema needs to be split up to single statements since
					// mysql_query can only run one statement at once.
					$statements = explode(';', $sql_schema);
					foreach($statements as $statement) {
						$statement = trim($statement);
						if(strlen($statement) > 0) {
							if(mysql_query($statement, $connection) === false) {
								$databaseOk = false;
								break;
							}
						}
					}
					
					mysql_close($connection);
				} else {
					mysql_close($connection);
					$databaseOk = false;
				}
			} else {
				$databaseOk = false;
			}
		}
		
		if($databaseOk === false) {
			$this->addMessage('error','Database not set up', 'The database was not set up correctly.<br/>Please use the schema file <em>data/sql/html5wiki_schema.sql</em> and try setting up the database by yourself.');
		}
		
		return $databaseOk;
	}
	
	/**
	 * If the user wanted to, this setup method moves all files from /web/ one
	 * directory up (except of the install.php of course ;) )
	 *
	 * @return true/false regarding success
	 */
	private function setupInstallationtype() {
		$installationtypeOk = true;
		
		if($this->wizardData[self::PROPERTY_INSTALLATION_TYPE] === self::INSTALLATION_TYPE_ROOT) {
			$installationtypeOk = $this->copyDirectory(
				self::FOLDER_WEB
				,self::FOLDER_WEB
				,self::FOLDER_ROOT. 'copytest/'
				,array('web/install.php')
				,true
				,array('web/','web/install.php')
				);
		}
		
		if($installationtypeOk === false) {
			$this->addMessage('error','Files not moved', 'You choosed the installation type which runs the HTML5Wiki boostrap outside of the <em>web/</em> directory.<p><p>The wizard was not able to move all files located in <em>web/</em>. Please move the contained files by yourself one directory up and delete <em>web/</em> afterwards.');
		}
		
		return $installationtypeOk;
	}
	
	/**
	 * Copies a complete directory to the in $destinationBasePath specified
	 * target directory.<br/>
	 * Uses recursion to resolve all subdirectories.
	 *
	 * @param $directoryToCopy To current directory which should be copied
	 * @param $sourceBasePath The source base directory
	 * @param $destinationBasePath The target base directory
	 * @param $excludeFromCopy Exclude these files/directory from copy
	 * @param $move Delete source files/directory after successful copy
	 * @param $excludeFromDelete Exclude these files/directories from delete
	 * @return true/false regarding success
	 */
	private function copyDirectory($directoryToCopy, $sourceBasePath, $destinationBasePath, $excludeFromCopy = array(), $move = false, array $excludeFromDelete = array()) {
		$success = true;
		$files = scandir($directoryToCopy);
		$destinationPath = str_replace($sourceBasePath, $destinationBasePath, $directoryToCopy);
		if(mkdir($destinationPath) === false) $success = false;
		
		if($success === true) {
			foreach ($files as $file) {
				if (in_array($file, array(".",".."))) continue;

				if(is_dir($directoryToCopy.$file) && !in_array($directoryToCopy, $excludeFromCopy)) {
					// Copy subdirectory:
					$success = $this->copyDirectory($directoryToCopy.$file.'/', $sourceBasePath, $destinationBasePath, $move, $excludeFromDelete);
				} else {
					// Copy file:
					if(!in_array($directoryToCopy.$file, $excludeFromCopy)) {
						if (!copy($directoryToCopy.$file, $destinationPath.$file)) {
							$success = false;
						} else {
							if($move === true) {
								if(!in_array($directoryToCopy.$file, $excludeFromDelete)) {
									unlink($directoryToCopy.$file);
								}
							}
						}
					}
				}
			}
		}
		
		if($success === true && $move === true) {
			if(!in_array($directoryToCopy, $excludeFromDelete)) {
				if(rmdir($directoryToCopy) === false) $success = false;
			}
		}
		
		return $success;
	}
	
	/**
	 * This creates the htaccess-file in the correct position.
	 *
	 * @return true/false regarding success
	 */
	private function setupHtaccessFile() {
		$htaccessOk = true;
		$targetFile = '';
		
		/* Where to write? */
		$installationtype = $this->wizardData[self::PROPERTY_INSTALLATION_TYPE];
		if($installationtype === self::INSTALLATION_TYPE_WEB) $targetFile = 'web/.htaccess';
		else if($installationtype === self::INSTALLATION_TYPE_ROOT) $targetFile = '.htaccess';
		
		/* Create contents: */
		$htaccess = '# This file was generated by the HTML5Wiki Installation Wizard. Do not modify.'."\n\n"
				  . 'RewriteEngine On'."\n"
				  . 'RewriteCond %{REQUEST_FILENAME} !-f'."\n"
				  . 'RewriteCond %{REQUEST_FILENAME} !-d'."\n"
				  . 'RewriteCond %{REQUEST_FILENAME} !-l'."\n"
				  . 'RewriteRule !^(css/.*|images/.*|js/.*) index.php [L]'."\n";

		
		/* Try to write the file: */
		$htaccessOk = $this->writeFile($targetFile, $htaccess);
		
		
		if($htaccessOk === false) {
			$this->addMessage('error','Could not create .htaccess file', 'Please create the file <em>web/.htaccess</em> by yourself and copy paste the following content into it:</p><p class="white-paper">'. nl2br($htaccess));
		}
		
		return $htaccessOk;
	}
	
	/**
	 * Tries to write $content into the file $targetFile.
	 *
	 * @param $targetFile
	 * @param $content
	 * @return true/false regarding success
	 */
	private function writeFile($targetFile, $content) {
		$ok = true;
		
		if(is_writeable($targetFile)) {
			$fh = fopen($targetFile, 'w');
			if($fh) {
				if(fwrite($fh, $content)) fclose($fh);
				else $ok = false;
			} else {
				$ok = false;
			}
		} else {
			$ok = false;
		}
		
		return $ok;
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
	protected $wizardData = array();
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
		$this->deserializeWizardData($postData);
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
					$this->wizardData[$key] = $value;
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
						$method = $this->steps[$this->currentStepIndex]['callMethodAfter'];
						$ok = $this->$method($this->wizardData);
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
			$method = $this->steps[$this->currentStepIndex]['callMethodBefore'];
			$this->$method($this->wizardData);
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

					if(isset($this->wizardData[$key])) {
						if(strlen($this->wizardData[$key]) === 0) {
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
	 * @see deserializeSWizardData
	 */
	public function serializeWizardData() {
		$serialized = '';
		
		if(sizeof($this->wizardData) > 0) {
			$serialized .= '<input type="hidden" '
			            .  'name="wizardData" '
						.  'value="'. urlencode(serialize($this->wizardData)). '" '
						.  ' />'."\n";
		}

		return $serialized."\n";
	}

	/**
	 * After a POST-Request, this method deserializes the steps-data back into
	 * the wizardData-array
	 *
	 * @param $postSource simply the $_POST-variable
	 * @see serializeWizardData
	 */
	private function deserializeWizardData(array $postSource) {
		if(isset($postSource['wizardData'])) {
			$this->wizardData = unserialize(urldecode($postSource['wizardData']));
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
				if(isset($this->wizardData[$key])) $value = $this->wizardData[$key];
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
	
	/**
	 * If true, the next button is allowed to be displayed.
	 *
	 * @return true/false
	 */
	public function isNextAllowed() {
		$allowed = false;

		if($this->getCurrentStepIndex() < $this->getTotalSteps()-1) {
			$allowed = true;
		}
		
		return $allowed;
	}
	
	/**
	 * If true, the back button is allowed to be displayed.
	 *
	 * @return true/false
	 */
	public function isBackAllowed() {
		$allowed = true;
		$currentStep = $this->getCurrentStep();
		
		if($this->getCurrentStepIndex() > 0) {
			if(isset($currentStep['allowBack']) && $currentStep['allowBack'] === false) {
				$allowed = false;
			}
		} else {
			$allowed = false;
		}
		
		return $allowed;
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
	<link rel="shortcut icon" href="wimages/favicon.ico" type="image/x-icon" />
	<link rel="icon" href="wimages/favicon.ico" type="image/ico" />
	<style type="text/css">
		html,body,div,span,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,abbr,address,cite,code,del,dfn,em,img,ins,kbd,q,samp,small,strong,sub,sup,var,b,i,dl,dt,dd,ol,ul,li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td,article,aside,canvas,details,figcaption,figure,footer,header,hgroup,menu,nav,section,summary,time,mark,audio,video{margin:0;padding:0;border:0;outline:0;font-size:100%;vertical-align:baseline;background:transparent;}
		body{line-height:1;}
		article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section{display:block;}
		ul,ol{list-style:none;}
		blockquote,q{quotes:none;}
		blockquote:before,blockquote:after,
		q:before,q:after{content:'';content:none;}
		a{margin:0;padding:0;font-size:100%;vertical-align:baseline;background:transparent;}
		ins{background-color:#ff9;color:#000;text-decoration:none;}
		mark{background-color:#ff9;color:#000;font-style:italic;font-weight:bold;}
		del{text-decoration:line-through;}
		abbr[title],dfn[title]{border-bottom:1px dotted;cursor:help;}
		table{border-collapse:collapse;border-spacing:0;}
		hr{display:block;height:1px;border:0;border-top:1px solid #cccccc;margin:1em 0;padding:0;}
		input,select{vertical-align:middle;}
		.container_12,.container_16{margin-left:4%;margin-right:4%;width:92%;}
		.grid_1,.grid_2,.grid_3,.grid_4,.grid_5,.grid_6,.grid_7,.grid_8,.grid_9,.grid_10,.grid_11,.grid_12,.grid_13,.grid_14,.grid_15,.grid_16{display:inline;float:left;margin-left:1%;margin-right:1%;}
		.container_12 .grid_3,.container_16 .grid_4{width:23%;}
		.container_12 .grid_6,.container_16 .grid_8{width:48%;}
		.container_12 .grid_9,.container_16 .grid_12{width:73%;}
		.container_12 .grid_12,.container_16 .grid_16{width:98%;}
		.alpha{margin-left:0;}
		.omega{margin-right:0;}
		.container_12 .grid_1{width:6.333%;}
		.container_12 .grid_2{width:14.666%;}
		.container_12 .grid_4{width:31.333%;}
		.container_12 .grid_5{width:39.666%;}
		.container_12 .grid_7{width:56.333%;}
		.container_12 .grid_8{width:64.666%;}
		.container_12 .grid_10{width:81.333%;}
		.container_12 .grid_11{width:89.666%;}
		.container_16 .grid_1{width:4.25%;}
		.container_16 .grid_2{width:10.5%;}
		.container_16 .grid_3{width:16.75%;}
		.container_16 .grid_5{width:29.25%;}
		.container_16 .grid_6{width:35.5%;}
		.container_16 .grid_7{width:41.75%;}
		.container_16 .grid_9{width:54.25%;}
		.container_16 .grid_10{width:60.5%;}
		.container_16 .grid_11{width:66.75%;}
		.container_16 .grid_13{width:79.25%;}
		.container_16 .grid_14{width:85.5%;}
		.container_16 .grid_15{width:91.75%;}
		.container_12 .prefix_3,.container_16 .prefix_4{padding-left:25%;}
		.container_12 .prefix_6,.container_16 .prefix_8{padding-left:50%;}
		.container_12 .prefix_9,.container_16 .prefix_12{padding-left:75%;}
		.container_12 .prefix_1{padding-left:8.333%;}
		.container_12 .prefix_2{padding-left:16.666%;}
		.container_12 .prefix_4{padding-left:33.333%;}
		.container_12 .prefix_5{padding-left:41.666%;}
		.container_12 .prefix_7{padding-left:58.333%;}
		.container_12 .prefix_8{padding-left:66.666%;}
		.container_12 .prefix_10{padding-left:83.333%;}
		.container_12 .prefix_11{padding-left:91.666%;}
		.container_16 .prefix_1{padding-left:6.25%;}
		.container_16 .prefix_2{padding-left:12.5%;}
		.container_16 .prefix_3{padding-left:18.75%;}
		.container_16 .prefix_5{padding-left:31.25%;}
		.container_16 .prefix_6{padding-left:37.5%;}
		.container_16 .prefix_7{padding-left:43.75%;}
		.container_16 .prefix_9{padding-left:56.25%;}
		.container_16 .prefix_10{padding-left:62.5%;}
		.container_16 .prefix_11{padding-left:68.75%;}
		.container_16 .prefix_13{padding-left:81.25%;}
		.container_16 .prefix_14{padding-left:87.5%;}
		.container_16 .prefix_15{padding-left:93.75%;}
		.container_12 .suffix_3,.container_16 .suffix_4{padding-right:25%;}
		.container_12 .suffix_6,.container_16 .suffix_8{padding-right:50%;}
		.container_12 .suffix_9,.container_16 .suffix_12{padding-right:75%;}
		.container_12 .suffix_1{padding-right:8.333%;}
		.container_12 .suffix_2{padding-right:16.666%;}
		.container_12 .suffix_4{padding-right:33.333%;}
		.container_12 .suffix_5{padding-right:41.666%;}
		.container_12 .suffix_7{padding-right:58.333%;}
		.container_12 .suffix_8{padding-right:66.666%;}
		.container_12 .suffix_10{padding-right:83.333%;}
		.container_12 .suffix_11{padding-right:91.666%;}
		.container_16 .suffix_1{padding-right:6.25%;}
		.container_16 .suffix_2{padding-right:16.5%;}
		.container_16 .suffix_3{padding-right:18.75%;}
		.container_16 .suffix_5{padding-right:31.25%;}
		.container_16 .suffix_6{padding-right:37.5%;}
		.container_16 .suffix_7{padding-right:43.75%;}
		.container_16 .suffix_9{padding-right:56.25%;}
		.container_16 .suffix_10{padding-right:62.5%;}
		.container_16 .suffix_11{padding-right:68.75%;}
		.container_16 .suffix_13{padding-right:81.25%;}
		.container_16 .suffix_14{padding-right:87.5%;}
		.container_16 .suffix_15{padding-right:93.75%;}
		html body * span.clear,html body * div.clear,html body * li.clear,html body * dd.clear{background:none repeat scroll 0 0 transparent;border:0 none;clear:both;display:block;float:none;font-size:0;height:0;list-style:none outside none;margin:0;overflow:hidden;padding:0;visibility:hidden;width:0;}
		.clearfix:after{clear:both;content:".";display:block;height:0;visibility:hidden;}
		.clearfix{display:inline-block;}
		* html .clearfix{height:1%;}
		.clearfix{display:block;}
		.hide{display:none;}
		.header-overall{font-family:"Trebuchet MS",Tahoma,Arial,sans-serif;font-size:85%;position:relative;height:60px;padding-top:8px;}.header-overall .logo{position:absolute;background:url('images/headerLogo.png') no-repeat;width:153px;height:60px;}
		.header-overall .main-menu{margin-left:181px;margin-top:28px;}.header-overall .main-menu .menu-items .item{float:left;list-style:none;margin-right:3px;}
		.header-overall .main-menu .menu-items .tab{color:black;text-decoration:none;background:5px 5px no-repeat rgba(255, 255, 255, 0.6);padding:6px 8px 4px 24px;-moz-border-radius:5px 5px 0 0;-webkit-border-radius:5px 5px 0 0;border-radius:5px 5px 0 0;-moz-border-radius:5px 5px 0 0;-webkit-border-radius:5px 5px 0 0;border-radius:5px 5px 0 0;}.header-overall .main-menu .menu-items .tab:hover{background-color:rgba(255, 255, 255, 0.8);}
		.header-overall .main-menu .menu-items .active .tab{background-color:#fff;}.header-overall .main-menu .menu-items .active .tab:hover{background-color:#fff;}
		.header-overall .main-menu .menu-items .home .tab{background-image:url('images/icons16/house.png');}
		.header-overall .main-menu .menu-items .updates .tab{background-image:url('images/icons16/clockHistoryFrame.png');}
		.header-overall .main-menu .menu-items .article .tab{background-image:url('images/icons16/page.png');}
		.header-overall .main-menu .menu-items .new-article .tab{background-image:url('images/icons16/page_add.png');}
		.header-overall .main-menu .menu-items .search{float:right;margin:-10px 0 0 0;}.header-overall .main-menu .menu-items .search .searchterm{width:150px;font-family:"Trebuchet MS",Tahoma,Arial,sans-serif;font-size:85%;padding:5px 3px 3px 22px;background:url('images/icons16/magnifier.png') no-repeat 3px 2px #ffffff;border:1px solid #ea7253;}
		.header-overall .searchbox-results{margin:-1px 0 0 0;position:absolute;width:175px;background:#fff;border:1px solid #ea7253;border-top:1px solid #fbe2db;}.header-overall .searchbox-results .result-item{list-style:none;padding:4px;font-size:85%;}.header-overall .searchbox-results .result-item a{text-decoration:none;color:#000;}
		.header-overall .searchbox-results .result-item .title{font-weight:bold;padding:2px 0 5px 20px;background:no-repeat 0 0;}
		.header-overall .searchbox-results .result-item .matchorigins{padding-left:20px;font-style:italic;color:#a9a9a9;}
		.header-overall .searchbox-results .selected{background-color:#f5bdae;}
		.header-overall .searchbox-results .mediatype-article .title{background-image:url('images/icons16/page.png');}
		body{background:url('images/headerBackground.png') repeat-x;}
		.content{margin-top:10px;margin-bottom:30px;}.content h1{font:bold 200% "Trebuchet MS",Tahoma,Arial,sans-serif;color:#000000;}
		.content h2,.content h3,.content h4,.content h5{font-family:"Trebuchet MS",Tahoma,Arial,sans-serif;}.content h2 a,.content h3 a,.content h4 a,.content h5 a{text-decoration:none;color:#000000;}.content h2 a:hover,.content h3 a:hover,.content h4 a:hover,.content h5 a:hover{background-color:#fdf4f2;}
		.content h2{font-size:150%;color:#000000;margin:30px 0 5px 0;}
		.content h3{font-size:110%;color:#000000;margin:10px 0 0 0;}
		.content h2:first-child{margin-top:0;}
		.content .button{font-family:"Trebuchet MS",Tahoma,Arial,sans-serif;text-decoration:none;outline:none;cursor:pointer;border:1px solid #bdbdbd;font-size:85%;padding:2px 4px;color:#7b7b7b;background-color:#f0f0f0;border-color:#bdbdbd;background-image:-moz-linear-gradient(center bottom, #e1e1e1 20%, #f0f0f0 45%);-moz-border-radius:4px;-webkit-border-radius:4px;border-radius:4px;}.content .button:hover{color:#a9a9a9;background-color:#fbfbfb;background-image:-moz-linear-gradient(center bottom, #e9e9e9 20%, #fdfdfd 45%);-webkit-box-shadow:0 0 5px rgba(255, 255, 255, 0.5);-moz-box-shadow:0 0 5px rgba(255, 255, 255, 0.5);box-shadow:0 0 5px rgba(255, 255, 255, 0.5);}
		.content .button:active{-webkit-box-shadow:0 0 5px rgba(255, 255, 255, 0.95);-moz-box-shadow:0 0 5px rgba(255, 255, 255, 0.95);box-shadow:0 0 5px rgba(255, 255, 255, 0.95);text-shadow:0px 1px 1px rgba(0, 0, 0, 0.19999999999999996);background-color:#fbfbfb;background-image:-moz-linear-gradient(center top, #e9e9e9 20%, #fdfdfd 45%);}
		.content .title{margin-bottom:20px;}.content .title .heading{float:left;}
		.content .bottom-button-bar{margin-top:20px;}.content .bottom-button-bar .large-button{font-family:"Trebuchet MS",Tahoma,Arial,sans-serif;text-decoration:none;outline:none;cursor:pointer;border:1px solid #b6b6b6;font-size:120%;padding:2px 4px;color:#737373;background-color:#e9e9e9;border-color:#b6b6b6;background-image:-moz-linear-gradient(center bottom, #d9d9d9 20%, #e9e9e9 45%);padding:5px 8px 3px 6px;-moz-border-radius:4px;-webkit-border-radius:4px;border-radius:4px;}.content .bottom-button-bar .large-button:hover{color:#a1a1a1;background-color:#f3f3f3;background-image:-moz-linear-gradient(center bottom, #e1e1e1 20%, #f5f5f5 45%);-webkit-box-shadow:0 0 5px rgba(255, 255, 255, 0.5);-moz-box-shadow:0 0 5px rgba(255, 255, 255, 0.5);box-shadow:0 0 5px rgba(255, 255, 255, 0.5);}
		.content .bottom-button-bar .large-button:active{-webkit-box-shadow:0 0 5px rgba(255, 255, 255, 0.95);-moz-box-shadow:0 0 5px rgba(255, 255, 255, 0.95);box-shadow:0 0 5px rgba(255, 255, 255, 0.95);text-shadow:0px 1px 1px rgba(0, 0, 0, 0.19999999999999996);background-color:#f3f3f3;background-image:-moz-linear-gradient(center top, #e1e1e1 20%, #f5f5f5 45%);}
		.content .bottom-button-bar .large-button .caption{padding-left:19px;padding-top:1px;background-repeat:no-repeat;}
		.content .bottom-button-bar .large-button .caption{background-position:0 center;padding-left:22px;}
		.content .bottom-button-bar .link-button{color:#a9a9a9;font:85% "Trebuchet MS",Tahoma,Arial,sans-serif;}.content .bottom-button-bar .link-button:hover{color:#9c9c9c;background-color:#f0f0f0;}
		.content .box{font:85% "Trebuchet MS",Tahoma,Arial,sans-serif;padding:6px 5px 6px 29px;background:no-repeat 7px 10px;border:1px solid;-moz-border-radius:5px;-webkit-border-radius:5px;border-radius:5px;margin-bottom:10px;}.content .box.info{background-image:url('images/icons16/information.png');background-color:#7ab7e6;border-color:#2888d2;}
		.content .box.error{background-image:url('images/icons16/exclamation.png');background-color:#e63d1f;border-color:#8f2310;}
		.content .box.question{color:black;background-image:url('images/icons16/question.png');background-color:#ffe292;border-color:#ffc72c;}
		.content .box p{font-family:"Trebuchet MS",Tahoma,Arial,sans-serif !important;}
		.content .box .white-paper{background-color:#fff;padding:2px;margin:10px 0;border:1px solid #cccccc;}
		.content .box .options{padding:7px 0 4px 0;}.content .box .options .option{color:#696969;font-size:85%;outline:none;}.content .box .options .option:hover{color:#767676;background-color:rgba(245, 245, 245, 0.7);}
		.content .box .options .button{font-family:"Trebuchet MS",Tahoma,Arial,sans-serif;text-decoration:none;outline:none;cursor:pointer;border:1px solid #bdbdbd;font-size:85%;padding:2px 4px;color:#7b7b7b;background-color:#f0f0f0;border-color:#bdbdbd;background-image:-moz-linear-gradient(center bottom, #e1e1e1 20%, #f0f0f0 45%);-moz-border-radius:4px;-webkit-border-radius:4px;border-radius:4px;margin-right:7px;}.content .box .options .button:hover{color:#a9a9a9;background-color:#fbfbfb;background-image:-moz-linear-gradient(center bottom, #e9e9e9 20%, #fdfdfd 45%);-webkit-box-shadow:0 0 5px rgba(255, 255, 255, 0.5);-moz-box-shadow:0 0 5px rgba(255, 255, 255, 0.5);box-shadow:0 0 5px rgba(255, 255, 255, 0.5);}
		.content .box .options .button:active{-webkit-box-shadow:0 0 5px rgba(255, 255, 255, 0.95);-moz-box-shadow:0 0 5px rgba(255, 255, 255, 0.95);box-shadow:0 0 5px rgba(255, 255, 255, 0.95);text-shadow:0px 1px 1px rgba(0, 0, 0, 0.19999999999999996);background-color:#fbfbfb;background-image:-moz-linear-gradient(center top, #e9e9e9 20%, #fdfdfd 45%);}
		.editor .title .heading{cursor:pointer;}.editor .title .heading:hover{background-color:#fdf4f2;}
		.editor .title .editor-wrapper{float:left;}.editor .title .editor-wrapper .textfield{font:200% "Trebuchet MS",Tahoma,Arial,sans-serif;font-weight:bold;width:100%;margin-bottom:6px;}
		.editor .title .editor-wrapper .cancel{font:85% "Trebuchet MS",Tahoma,Arial,sans-serif;color:#a9a9a9;margin-right:3px;}
		form{font-family:"Trebuchet MS",Tahoma,Arial,sans-serif;}form .group{-moz-border-radius:5px;-webkit-border-radius:5px;border-radius:5px;border:1px solid #a9a9a9;padding:5px 10px;margin-bottom:10px;background-color:#f8f8f8;}
		form .label{font-size:75%;display:block;margin-bottom:3px;}
		.header-overall .menu-items .install .tab { background-image: url('wimages/icons16/wizard.png'); }
		input[type=text] { font-size: 110%; margin-bottom: 8px; width: 300px; }
		label { display: block; font-size: 85%; margin-bottom: 2px; }
		.editor p { margin-bottom: 6px; }
		.content .box h3 { margin-top: 3px;}
		.content .box .white-paper { font-family: "Courier New", Courier, monospace !important; }
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
				<?php echo $wizard->serializeWizardData(); ?>
				<header class="title">
					<h2>Step <?php echo $currentStepIndex+1; ?>: <?php echo $currentStep['name'] ?></h2>
					<?php echo $currentStep['text'] ?>
				</header>
				
				<?php echo $wizard->renderInputs($currentStep); ?>
			
				<footer class="bottom-button-bar">
					<?php if($wizard->isNextAllowed()) : ?>
					<input type="submit" name="next" value="<?php echo (isset($currentStep['nextCaption']) === true ? $currentStep['nextCaption'] : 'Next >'); ?>" class="large-button caption"/>
					<?php endif; ?>
					<?php if($wizard->isBackAllowed()) : ?>
					<input type="submit" name="back" value="Back" class="large-button caption" />
					<?php endif; ?>
				</footer>
			</form>
		</section>
		<div class="clear"></div>
	</div>
</body>
</html>