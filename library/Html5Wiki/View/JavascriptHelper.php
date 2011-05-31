<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright Html5Wiki 2011
 * @package Html5Wiki
 * @subpackage Library
 */

/**
 * Javascript Helper
 */
class Html5Wiki_View_JavascriptHelper extends Html5Wiki_View_Helper {
	
	private static $productiveJSFiles = array();
	private static $developmentJSFiles = array();	
	private static $plainScripts = array();
	
	/**
	 * Adds a javascript file to be appended to the output.<br/>
	 * Use the $useIn*-parameters to control, in which environments the script
	 * should be or should not be appended.
	 *
	 * @param $file
	 * @param $useInDevelopment
	 * @param $useInProduction
	 */
	public function appendFile($file, $useInDevelopment=true, $useInProduction=false) {
		if($useInDevelopment === true) {
			if (!in_array($file, self::$developmentJSFiles)) {
				self::$developmentJSFiles[] = $file;
			}
		}
		if($useInProduction === true) {
			if (!in_array($file, self::$productiveJSFiles)) {
				self::$productiveJSFiles[] = $file;
			}
		}
	}

	/**
	 * Add a javascript (really the script, not a file) to the output.
	 *
	 * @param $script
	 */
	public function appendScript($script) {
		self::$plainScripts[] = $script;
	}

	/**
	 * Creates valid HTML source from the files and scripts.
	 *
	 * @return string
	 */
	public function toString() {
		$string = '';
		$string = $this->getJSFiles();
		$string .= $this->getPlainScripts();
		
		return $string;
	}
	
	/**
	 * Creates <script>-Tags for all javascript files present in the arrays.<br/>
	 * If this current environment is productive, $productiveJSFiles is used,
	 * otherwise $developmentJSFiles.
	 *
	 * @return string
	 */
	private function getJSFiles() {
		$isProductive = Html5Wiki_Controller_Front::getInstance()->isProductive();
		$files = self::$productiveJSFiles;
		$string = '';
		
		if($isProductive === false) {
			$files = self::$developmentJSFiles;
		}
		
		foreach ($files as $file) {
			$string .= $this->fileString($file);
		}
		
		return $string;
	}
	
	/**
	 * Creates <script>-Tags with the javascripts present in $plainScripts.
	 *
	 * @return string
	 */
	private function getPlainScripts() {
		$string = '';
		
		if(sizeof(self::$plainScripts) > 0) {			
			$string .= '<script type="text/javascript">';
			foreach (self::$plainScripts as $script) {
				$string .= $this->scriptString($script);
			}
			$string .= '</script>';
		}
		
		return $string;
	}

	private function fileString($file) {
		return '<script type="text/javascript" src="' . $file .'"></script>' . "\n";
	}

	private function scriptString($script) {
		return $script . "\n";
	}
	
	
}
?>
