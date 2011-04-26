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
 * Url Helper
 */
class Html5Wiki_View_UrlHelper extends Html5Wiki_View_Helper {
	public function urlHelper($path) {
		$request = Html5Wiki_Controller_Front::getInstance()->getRouter()->getRequest();
		return $request->getBasePath() . '/' . $path;
	}
}

?>
