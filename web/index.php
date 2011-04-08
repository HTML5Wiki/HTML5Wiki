<?php
/**
 * HTML5Wiki bootstrap file
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Web
 */
ob_start();

error_reporting(E_ALL | E_STRICT);

$basePath = realpath(dirname(__FILE__) . '/../');
$libraryPath = $basePath . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR;
$applicationPath = $basePath . DIRECTORY_SEPARATOR . 'Application' . DIRECTORY_SEPARATOR;
$configPath	= $basePath . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR;

// include library
$includePath = get_include_path() . PATH_SEPARATOR . $basePath . PATH_SEPARATOR . $libraryPath;
ini_set('include_path', $includePath);
// include config
$includePath = get_include_path() . PATH_SEPARATOR . $basePath . PATH_SEPARATOR . $configPath;
ini_set('include_path', $includePath);

require $libraryPath . 'Zend/Loader/Autoloader.php';

$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace(array('Html5Wiki_', 'Application_'));

require $configPath . 'config.php';
$config = new Zend_Config($config);

$adapter = Zend_Db::factory($config->databaseAdapter, $config->database);
Zend_Db_Table::setDefaultAdapter($adapter);

$frontController = new Html5Wiki_Controller_Front($config, $basePath, $libraryPath, $applicationPath);
$frontController->dispatch();

ob_end_flush();