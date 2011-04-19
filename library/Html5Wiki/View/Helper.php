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
 * Helper interface for view helpers
 */
abstract class Html5Wiki_View_Helper {
    abstract public function __construct(Html5Wiki_Template_Interface $template);

	public function __call($name, $arguments) {
		if (!method_exists($this, $name)) {
			return $this;
		}
		return $this->$name($arguments);
	}

	abstract public function toString();
}
?>
