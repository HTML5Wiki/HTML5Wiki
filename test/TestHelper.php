<?php
/**
 * Copied from Zend Framework, adapted for Html5Wiki by Michael Weibel <mweibel@hsr.ch>
 * 
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: TestHelper.php 23775 2011-03-01 17:25:24Z ralph $
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


/*
 * Unset global variables that are no longer needed.
 */
unset($baseDir, $libraryDir, $testDir, $unitTestDir, $path);
