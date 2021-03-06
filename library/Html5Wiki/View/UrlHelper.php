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
 * @author Manuel Alabor <malabor@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @category Html5Wiki
 * @package Library
 * @subpackage View
 */

/**
 * Url Helper
 */
class Html5Wiki_View_UrlHelper extends Html5Wiki_View_Helper {
	/**
	 * returns a correct url. Args get imploded with /
	 * @param array $params
	 * @return string
	 */
	public function urlHelper($params) {
		$router = Html5Wiki_Controller_Front::getInstance()->getRouter();
		return $router->buildURL($params);
	}
}

?>
