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
 * @package Library
 * @subpackage Controller
 */

/**
 * Controller factory creates a new controller according to the informations from the router.
 */
class Html5Wiki_Controller_Factory {

	const APPLICATION_NAMESPACE = 'Application_';

	/**
	 *
	 * @param string $applicationPath
	 * @param Html5Wiki_Routing_Interface_Router $router
	 * @return AbstractController
	 */
	public static function factory($applicationPath, Html5Wiki_Routing_Interface_Router $router, Html5Wiki_Routing_Response $response) {
		if (!is_string($applicationPath)) {
			throw new Html5Wiki_Exception_InvalidArgument(
						'Invalid argument supplied for ' . __CLASS__ . '::' . __FUNCTION__ . ' (Argument applicationPath).'
						. ' String required but ' . gettype($applicationPath) . ' supplied.');
		}

		$fileHandle = opendir($applicationPath);

		while (false !== ($fileName = readdir($fileHandle))) {
			if ($fileName != "." && $fileName != ".." && strpos($fileName, ".php") !== false) {
				if (stripos($fileName, $router->getController()) === 0) {
					$controller = self::APPLICATION_NAMESPACE . substr($fileName, 0, -4);
					include_once $applicationPath . $fileName;
					
					return new $controller($response);
				}
			}
		}
		throw new Html5Wiki_Exception_404('Could not found a controller for the current path.');
	}
}
?>
