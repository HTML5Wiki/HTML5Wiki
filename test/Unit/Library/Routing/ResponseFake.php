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
 * @package Test
 * @subpackage Unit
 */

/**
 * Response fake for overriding the render methods
 */
class Test_Unit_Library_Routing_ReponseFake extends Html5Wiki_Routing_Response {
	
	public $renderedData = '';
	public $renderedHeader = '';
	public $renderedCookie = '';
	
	/**
	 * Puts data into the renderedData field.
	 * 
	 * @override
	 * 
	 * @param string $data 
	 */
	public function renderData($data) {
		$this->renderedData .= $data;
	}
	
	/**
	 * Puts headers in to the renderedHeader field.
	 * 
	 * @override
	 * 
	 * @param array $header
	 */
	public function renderHeader($header) {
		$this->renderedHeader .= implode(",", $header) . "\n";
	}
	
	/**
	 * Puts cookies in to the renderedCookie field.
	 * 
	 * @override
	 * 
	 * @param array $cookie 
	 */
	public function renderCookie($cookie) {
		$this->renderedCookie .= implode(",", $cookie) . "\n";
	}
}
?>
