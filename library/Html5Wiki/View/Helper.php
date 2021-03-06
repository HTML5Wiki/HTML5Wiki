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
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @category Html5Wiki
 * @package Library
 * @subpackage View
 */

/**
 * Helper abstract class for view helpers
 */
abstract class Html5Wiki_View_Helper {
	/**
	 * Template object in which the helper gets called.
	 * @var string
	 */
	protected $template;
	
	/**
	 * Constructor
	 * @param Html5Wiki_Template_Interface $template 
	 */
    public function __construct(Html5Wiki_Template_Interface $template) {
		$this->template = $template;
	}

	/**
	 * Every call to an object inheriting this helper
	 * @param string $name
	 * @param string $arguments
	 * @return mixed
	 */
	public function __call($name, $arguments) {
		if (!method_exists($this, $name)) {
			return $this;
		}
		return $this->$name($arguments);
	}

	/**
	 * Return string on echo. 
	 * @return string
	 */
	public function toString() {
		return '';
	}
	
	/**
	 * Magic method calls this#toString
	 * @return string
	 */
	public function __toString() {
		return $this->toString();
	}
}
?>
