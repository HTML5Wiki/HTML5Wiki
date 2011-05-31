<?php
/**
 * Error Controller<br/>
 * Used by set_exception_handler & set_error_handler<br/>
 *
 * This class recreates a lot of the encapsulated logic of abstract controller
 * and Html5Wiki_Routing_Router.<br/>
 * A definitive candidate for refactoring ;)
 *
 * @author Manuel Alabor <malabor@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Application
 */
class Application_ErrorController {
	
	/**
	 * Handles an exception.
	 *
	 * @param $exception
	 * @see web/index.php
	 */
	public static function handleException($exception) {
		if($exception instanceof Html5Wiki_Exception_404) {
			self::handle404($exception);
		} else {
			self::handleGeneric($exception);
		}
	}
	
	/**
	 * Handles an error.
	 *
	 * @param $code
	 * @param $text
	 * @param $file
	 * @param $line
	 * @see web/index.php
	 */
	public static function handleError($code, $text, $file, $line) {
		$data = array('text' => $text);
		if(self::isDebugActive()) {
			$data['code'] = $code;
			$data['file'] = $file;
			$data['line'] = $line;
		}
		
		self::render($data, 'error.php');
	}
	
	/**
	 * Handles a Html5Wiki_Exception_404 exception with a redirect to the 
	 * homepage of HTML5Wiki.
	 *
	 * @param Html5Wiki_Exception_404 $exception
	 */
	private static function handle404(Html5Wiki_Exception_404 $exception) {
		header('Location: '. self::buildUrl(array()), 302);
	}
	
	/**
	 * Handles a generic exception by showing its message.<br/>
	 * If the GET-parameter "debug" is true, full stacktrace is displayed.
	 * 
	 * @param $exception
	 */
	private static function handleGeneric($exception) {
		$data = array();
		if(self::isDebugActive()) {
			$data['exception'] = $exception;
		}
		
		self::render($data, 'exception.php');
	}
	
	/**
	 * Creates the templates, assigns all data and renders the templates.
	 *
	 * @param array $data
	 * @param $templateFile
	 */
	private static function render(array $data, $templateFile) {
		$response = new Html5Wiki_Routing_Response();
		$language = self::getLanguage();
		$translate = self::getTranslations($language);
		
		$layoutTemplate = new Html5Wiki_Template_Php($response);
		$layoutTemplate->setTemplateFile('error/error-layout.php');
		$layoutTemplate->setTranslate($translate);
		$template = new Html5Wiki_Template_Php($response, $layoutTemplate);
		$template->setTemplateFile('error/'. $templateFile);
		$template->setTranslate($translate);
		
		$layoutTemplate->assign('title', 'Error');
		$template->assign('errorInfo', $data);
		
		$template->render();
		$response->render();
	}
	
	/**
	 * Get the current users browser language or gets the default language from
	 * config if something is wrong.
	 *
	 * @return language locale
	 */
	private static function getLanguage() {
		$request = new Html5Wiki_Routing_Request();
		$request->parse();
		$config = self::getConfig();
		
		$language = Html5Wiki_Routing_Request::parseHttpAcceptLanguage($config['languages']);
		$language = ($language !== null) ? $language : $config['defaultLanguage'];
		
		return $language;
	}
	
	/**
	 * Loads the config.php and returns the config-array
	 *
	 * @return config
	 */
	private static function getConfig() {
		require 'config/config.php';
		return $config;
	}
	
	/**
	 * Loads a translation file and returns its array.
	 *
	 * @return array with translations
	 */
	private static function getTranslations($language) {
		$translate = new Zend_Translate(
				array(
					'adapter' => 'array',
					'content' => '../languages/' . $language . '.php',
					'locale'  => $language
				)
		);
		return $translate;
	}
	
	/**
	 * Pass as many url parts as you want (without any slashes or anything!) and
	 * this method will create you a valid URL with basepath and everything.
	 *
	 * @param 0-n url parts
	 * @return valid URL with basepath plus all url parts from the parameterlist.
	 */
	private static function buildUrl(array $parts) {
		$request = new Html5Wiki_Routing_Request();
		$request->parse();
		
		$basePath = $request->getBasePath();
		$target = implode('/', $parts);
		$url = $basePath. '/'. $target;
		
		return $url;
	}
	
	/**
	 * Looks into the GET parameters for a parameter with the name debug.<br/>
	 * If present and the value is 1, the function returns true.
	 *
	 * @return true/false regarding the GET-parameter
	 */
	private static function isDebugActive() {
		$debug = false;
		if(isset($_GET['debug'])) {
			if($_GET['debug'] === '1') $debug = true;
		}
		
		return $debug;
	}
}
?>
