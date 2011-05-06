<?php
/**
 * Dummy index test controller
 */
require_once 'Html5Wiki/Template/Interface.php';
require_once 'Html5Wiki/Template/Decorator.php';
require_once 'Html5Wiki/Template/Php.php';
require_once 'Html5Wiki/Controller/Abstract.php';

class Application_WikiController extends Html5Wiki_Controller_Abstract {
    public function indexAction() {}

	public function setTemplate($template) {}

	public function render() {}
}
?>
