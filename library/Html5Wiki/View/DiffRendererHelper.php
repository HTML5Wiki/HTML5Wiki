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
 * Diff rendering Helper
 */
class Html5Wiki_View_DiffRendererHelper extends Html5Wiki_View_Helper {

	public function diffRendererHelper($args) {
		$renderer = new PhpDiff_Diff_Renderer_Html_Html5Wiki_SideBySide();
		$renderer->setLeftTimestamp($args[1]);
		$renderer->setRightTimestamp($args[2]);
		
		return $args[0]->render($renderer);
	}
	
}
?>
