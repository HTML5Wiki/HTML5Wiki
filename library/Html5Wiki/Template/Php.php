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
 * Description of Template
 *
 * @author michael
 */
class Html5Wiki_Template_Php extends Html5Wiki_Template_Decorator {

	public function  __get($name) {
		return isset($this->data[$name]) ? $this->data[$name] : null;
	}

    public function render($templateFile) {
		include_once(self::TEMPLATE_PATH . $templateFile);
	}
}
?>
