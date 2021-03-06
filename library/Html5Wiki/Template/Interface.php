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
 * @subpackage Template
 */

/**
 * Template interface
 */
interface Html5Wiki_Template_Interface {
	/**
	 * Contructor. 
	 * 
	 * @param Html5Wiki_Routing_Response $response
	 * @param Html5Wiki_Template_Interface $decoratedTemplate
	 */
	public function __construct(Html5Wiki_Routing_Response $response, Html5Wiki_Template_Interface $decoratedTemplate = null);
	
	/**
	 * Render this template
	 */
    public function render();
}
?>
