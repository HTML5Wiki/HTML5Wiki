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
	private $template;
	private static $javascriptFiles = array();
	private static $javascriptScripts = array();
	
    public function __construct(Html5Wiki_Template_Interface $template) {
		$this->template = $template;
	}

	public function appendFile($file) {
		if (!in_array($file, self::$javascriptFiles)) {
			self::$javascriptFiles[] = $file;
		}
	}

	public function appendScript($script) {
		self::$javascriptScripts[] = $script;
	}

	public function toString() {
		self::$javascriptFiles = array_reverse(self::$javascriptFiles);
		self::$javascriptScripts = array_reverse(self::$javascriptScripts);
		$string = '';
		foreach (self::$javascriptFiles as $file) {
			$string .= $this->fileString($file);
		}
		foreach (self::$javascriptScripts as $script) {
			$string .= $this->scriptString($script);
		}
		return $string;
	}

	private function fileString($file) {
		return '<script type="text/javascript" src="' . $file .'"></script>' . "\n";
	}

	private function scriptString($script) {
		return '<script type="text/javascript">' . $script .'</script>' . "\n";
	}
}
?>