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
 * Diff rendering Helper
 * @uses PhpDiff_Diff_Renderer_Html_Html5Wiki_SideBySide
 */
class Html5Wiki_View_DiffRendererHelper extends Html5Wiki_View_Helper {

	/**
	 * Renders a diff using our own renderer.
	 * @param array $args
	 * @return string 
	 */
	public function diffRendererHelper($args) {
		$renderer = new PhpDiff_Diff_Renderer_Html_Html5Wiki_SideBySide();
		$renderer->setLeftTimestamp($args[1]);
		$renderer->setRightTimestamp($args[2]);
		
		return $args[0]->render($renderer);
	}
	
}
?>
