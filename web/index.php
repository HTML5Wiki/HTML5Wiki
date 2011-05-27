<?php
/**
 * HTML5Wiki bootstrap file
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Web
 */
ob_start();

ini_set('display_errors', true);
error_reporting(E_ALL | E_STRICT);

$systemBasePath = realpath(dirname(__FILE__) . '/../');
$libraryPath = $systemBasePath . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR;
$applicationPath = $systemBasePath . DIRECTORY_SEPARATOR . 'Application' . DIRECTORY_SEPARATOR;
$configPath	= $systemBasePath . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR;

// include library
$includePath = get_include_path() . PATH_SEPARATOR . $systemBasePath . PATH_SEPARATOR . $libraryPath;
ini_set('include_path', $includePath);
// include config
$includePath = get_include_path() . PATH_SEPARATOR . $systemBasePath . PATH_SEPARATOR . $configPath;
ini_set('include_path', $includePath);

require $libraryPath . 'Zend/Loader/Autoloader.php';

$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace(array('Html5Wiki_', 'Application_', 'Markdown_', 'PhpDiff_'));

require $configPath . 'config.php';
$config = new Zend_Config($config);

$adapter = Zend_Db::factory($config->databaseAdapter, $config->database);
Zend_Db_Table::setDefaultAdapter($adapter);

date_default_timezone_set($config->defaultTimezone);

$frontController = new Html5Wiki_Controller_Front($config, $systemBasePath, $libraryPath, $applicationPath);
$frontController->run();

ob_end_flush();