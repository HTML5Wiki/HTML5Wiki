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
 * @package Bootstrap
 */

/**
 * HTML5Wiki bootstrap file
 */

/**
 * Catch all output
 */
ob_start();

/**
 * Set display errors to on
 * 
 * @todo Depends on the dev/production mode! Maybe remove it entirely, 
 *		 users should set that via the php.ini
 */
ini_set('display_errors', true);
error_reporting(E_ALL | E_STRICT);

/**
 * Setup paths
 */
$systemBasePath = realpath(dirname(__FILE__));
if(file_exists('library/') === false && is_dir('library/') === false) {
	$systemBasePath .= '/..';
}
$libraryPath = $systemBasePath . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR;
$applicationPath = $systemBasePath . DIRECTORY_SEPARATOR . 'Application' . DIRECTORY_SEPARATOR;
$configPath	= $systemBasePath . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR;

// include library
$includePath = get_include_path() . PATH_SEPARATOR . $systemBasePath . PATH_SEPARATOR . $libraryPath;
ini_set('include_path', $includePath);
// include config
$includePath = get_include_path() . PATH_SEPARATOR . $systemBasePath . PATH_SEPARATOR . $configPath;
ini_set('include_path', $includePath);

/**
 * Zend Autoloader automatically includes required files without explicit specifying of them. 
 */
require $libraryPath . 'Zend/Loader/Autoloader.php';

$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace(array('Html5Wiki_', 'Application_', 'Markdown_', 'PhpDiff_'));

/**
 * All errors and exceptions (except FATAL errors) are handled by the error controller.
 */
set_exception_handler(array('Application_ErrorController','handleException'));
set_error_handler(array('Application_ErrorController','handleError'));

/**
 * Configuration..
 */
require $configPath . 'config.php';
$config = new Zend_Config($config);

/**
 * DB Connection..
 */
$adapter = Zend_Db::factory($config->databaseAdapter, $config->database);
Zend_Db_Table::setDefaultAdapter($adapter);

/**
 * Set default timezone according to the configuration
 */
date_default_timezone_set($config->defaultTimezone);

/**
 * Setup front controller and run it.
 */
$frontController = new Html5Wiki_Controller_Front($config, $systemBasePath, $libraryPath, $applicationPath);
$frontController->run();
echo $frontController->render();

/**
 * Echo the buffered output
 */
ob_end_flush();