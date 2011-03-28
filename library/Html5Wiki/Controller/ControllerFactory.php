<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright Html5Wiki 2011
 * @package Html5Wiki
 * @subpackage Application
 */

/**
 * Description of ControllerFactory
 *
 * @author michael
 */
class Html5Wiki_Controller_ControllerFactory {

	const APPLICATION_NAMESPACE = 'Application_';

	/**
	 *
	 * @todo move basepath in config or somewhere else
	 * @param string $basePath
	 * @param Html5Wiki_Routing_Interface_Router $router
	 * @return AbstractController
	 */
	public static function factory($applicationPath, Html5Wiki_Routing_Interface_Router $router) {
		if (!is_string($applicationPath)) {
			throw new Html5Wiki_Exception_InvalidArgument(
						'Invalid argument supplied for ' . __CLASS__ . '::' . __FUNCTION__ . ' (Argument basePath).'
						. ' String required but ' . gettype($applicationPath) . ' supplied.');
		}

		$fileHandle = opendir($applicationPath);

		while (false !== ($fileName = readdir($fileHandle))) {
			if ($fileName != "." && $fileName != ".." && strpos($fileName, ".php") !== false) {

				if (stripos($fileName, $router->getController()) === 0) {
					$controller = self::APPLICATION_NAMESPACE . substr($fileName, 0, -4);
					return new $controller;
				}
			}
		}
		throw new Html5Wiki_Exception_404('Could not found a controller for the current path.');
	}
}
?>
