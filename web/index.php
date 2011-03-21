<?php
/**
 * HTML5Wiki bootstrap file
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright Html5Wiki 2011
 * @package Html5Wiki
 * @subpackage Web
 */
ob_start();

error_reporting(E_ALL | E_STRICT);

$frontController = new Html5Wiki_Controller_FrontController();
$frontController->dispatch();

ob_end_flush();