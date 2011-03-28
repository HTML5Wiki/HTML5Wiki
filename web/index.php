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

$basePath = realpath(dirname(__FILE__) . '/../');
$libraryPath = $basePath . '/library/';
$applicationPath = $basePath . '/application/';

ini_set('include_path', get_include_path() . ':' . $basePath . ':' . $libraryPath);

require '../library/Zend/Loader/Autoloader.php';

$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace(array('Html5Wiki_', 'Application_'));

$frontController = new Html5Wiki_Controller_FrontController($basePath, $libraryPath, $applicationPath);
$frontController->dispatch();

ob_end_flush();