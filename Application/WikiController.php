<?php

/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright Html5Wiki 2011
 * @package Html5Wiki
 * @subpackage Application
 */

/**
 * Description of ApiController
 *
 * @author michael
 */
class Application_WikiController extends Html5Wiki_Controller_AbstractController {

	public function foobarAction() {
		$this->template->assign('foo', 'bar');
	}
}

?>