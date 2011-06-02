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
 * @package Test
 */

/*
 * Set error reporting to the level to which HTML5Wiki code must comply.
 */
error_reporting(E_ALL | E_STRICT);

/*
 * Determine the root, library, and tests directories of the framework
 * distribution.
 */
$baseDir    = realpath(dirname(dirname(__FILE__)));
$libraryDir = "$baseDir/library";
$testDir    = "$baseDir/test";
$unitTestDir  = "$testDir/Unit";

/*
 * Prepend the Zend Framework library/ and tests/ directories to the
 * include_path. This allows the tests to run out of the box and helps prevent
 * loading other copies of the framework code and tests that would supersede
 * this copy.
 */
$path = array(
    $libraryDir,
    $testDir,
	$unitTestDir,
    get_include_path()
);
set_include_path(implode(PATH_SEPARATOR, $path));

/**
 * Get configuration; If no config is defined, get the sample config
 */
if (is_readable($testDir . DIRECTORY_SEPARATOR . 'TestConfiguration.php')) {
    require_once $testDir . DIRECTORY_SEPARATOR . 'TestConfiguration.php';
} else {
    require_once $testDir . DIRECTORY_SEPARATOR . 'TestConfiguration.php.sample';
}

require $libraryDir . '/Zend/Loader/Autoloader.php';

$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace(array('Html5Wiki_', 'Application_', 'Markdown_', 'PhpDiff_'));

date_default_timezone_set(DEFAULT_TIMEZONE);

/*
 * Unset global variables that are no longer needed.
 */
unset($baseDir, $libraryDir, $testDir, $unitTestDir, $path);
