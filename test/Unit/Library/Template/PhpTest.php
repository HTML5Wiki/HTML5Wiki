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
 * @subpackage Unit
 */

/**
 * Php Template test
 */
class Test_Unit_Library_Template_PhpTest extends PHPUnit_Framework_TestCase {
	
	private $templatePath = '';
	private $response = null;
	
	public function setUp() {
		$this->response = new Test_Unit_Library_Routing_ReponseFake();
		$this->templatePath = dirname(__FILE__) . '/templates/';
	}
	
	/**
	 * @expectedException Html5Wiki_Exception_Template
	 */
	public function testNoRendering() {
		$phpTemplate = new Html5Wiki_Template_Php($this->response);
		$phpTemplate->render();
	}
	
	public function testSimpleRendering() {
		$phpTemplate = new Html5Wiki_Template_Php($this->response);
		$phpTemplate->setTemplatePath($this->templatePath);
		$phpTemplate->setTemplateFile('simpleRendering.php');
		$phpTemplate->assign('test', 'ok');
		
		$phpTemplate->render();
		$this->response->render();
		
		$this->assertEquals('ok', $this->response->renderedData);
	}
	
	public function testLayoutRendering() {
		$layout = new Html5Wiki_Template_Php($this->response);
		$layout->setTemplatePath($this->templatePath);
		$layout->setTemplateFile('layout.php');
		
		$tpl = new Html5Wiki_Template_Php($this->response, $layout);
		$tpl->setTemplatePath($this->templatePath);
		$tpl->setTemplateFile('simpleRendering.php');
		
		$layout->assign('test', 'layout');
		$tpl->assign('test', 'decorated');
		
		$tpl->render();
		$this->response->render();
		
		$this->assertEquals('layout<div>decorated</div>' . "\n", $this->response->renderedData);
	}
	
	public function testSetNoLayoutRendering() {
		$layout = new Html5Wiki_Template_Php($this->response);
		$layout->setTemplatePath($this->templatePath);
		$layout->setTemplateFile('layout.php');
		
		$tpl = new Html5Wiki_Template_Php($this->response, $layout);
		$tpl->setTemplatePath($this->templatePath);
		$tpl->setTemplateFile('simpleRendering.php');
		
		$layout->assign('test', 'layout');
		$tpl->assign('test', 'decorated');
		$tpl->setNoLayout();
		
		$tpl->render();
		$this->response->render();
		
		$this->assertEquals('decorated', $this->response->renderedData);
	}
	
	public function testAHelper() {
		$this->setUpFrontController();
		$tpl = new Html5Wiki_Template_Php($this->response);
		$tpl->setTemplatePath($this->templatePath);
		$tpl->setTemplateFile('testJavascriptHelper.php');
		
		$tpl->render();
		$this->response->render();
		
		$this->assertEquals('<script type="text/javascript" src="foo2.js"></script>'
				. "\n" . '<script type="text/javascript" src="foo.js"></script>'
				. "\n" . '<script type="text/javascript">document.write("test");' . "\n</script>", 
				$this->response->renderedData);
	}
	
	private function setUpFrontController() {
		$config = new Zend_Config(array(
			'routing' => array(
				'defaultController' => 'index',
				'defaultAction'     => 'index'
			),
			'development' => true
		));
		
		$systemBasePath = realpath(dirname(__CLASS__) . '/../');
		$applicationPath = $systemBasePath . DIRECTORY_SEPARATOR . 'test/Unit/Library/Controller/FactoryTest/CorrectControllers' . DIRECTORY_SEPARATOR;
		$libraryPath     = $systemBasePath . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR;
		
		$request = new Test_Unit_Library_Routing_RequestStub();
		$response = new Test_Unit_Library_Routing_ReponseFake();
		$router = new Html5Wiki_Routing_Router($config, $response, $request);

		new Html5Wiki_Controller_Front($config, $systemBasePath, $libraryPath, $applicationPath, $router, $request);
		
	}
	
	public function testIsset() {
		$tpl = new Html5Wiki_Template_Php($this->response);
		$tpl->setTemplatePath($this->templatePath);
		$tpl->setTemplateFile('testIsset.php');
		
		$tpl->render();
		$this->response->render();
		
		$this->assertEquals('correct', $this->response->renderedData);
	}
	
	public function testTranslation() {
		$tpl = new Html5Wiki_Template_Php($this->response);
		$tpl->setTemplatePath($this->templatePath);
		$tpl->setTemplateFile('testTranslate.php');
		
		$translate = new Zend_Translate(array(
			'adapter' => 'array',
			'content' => array('thisShouldBeReplaced' => 'test translation succeeded!'),
			'locale'  => 'en'
		));
		
		$tpl->setTranslate($translate);
		
		$tpl->render();
		$this->response->render();
		
		$this->assertEquals($tpl->getTranslate(), $translate);
		$this->assertEquals('test translation succeeded!', $this->response->renderedData);
	}
}
?>
