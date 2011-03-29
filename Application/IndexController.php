<?php
/**
 * Index Controller for the home page
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Application
 */
class Application_IndexController extends Html5Wiki_Controller_Abstract {
    public function indexAction() {
		$this->template->assign('helloWorld', 'Hello from html5wiki');
	}
}
?>
