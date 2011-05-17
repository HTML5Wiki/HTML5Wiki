<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright Html5Wiki 2011
 * @package Html5Wiki
 * @subpackage Library
 */
require_once 'PHPUnit/Autoload.php';
require_once 'SeleniumTestCase.php';

/**
 * Wiki Test case
 */
class Test_Functional_WikiTest extends Test_Functional_SeleniumTestCase {
	
	const MAIN_URL = 'http://vs01.openflex.net';
	
	const AUTHOR = 'seleniumtest';
	const AUTHOR_EMAIL = 'seleniumtest@foobar.com';
	
	private $wikiTestUrl = '';
	private $wikiTestPage = 'seleniumtest';
	
	private static $time = 0;
	
	/**
	 * Ugly as hell to override the constructor, but not possible another way round..
	 * 
	 * @param string $name
	 * @param array $data
	 * @param string $dataName
	 * @param array $browser 
	 */
	public function __construct($name = NULL, array $data = array(), $dataName = '', array $browser = array()) {
		parent::__construct($name, $data, $dataName, $browser);
		self::$time = time();
	}

	public function setUp() {
		parent::setUp();
		
		$this->setBrowserUrl(self::MAIN_URL);
		$this->wikiTestPage .= self::$time;
		$this->wikiTestUrl = self::MAIN_URL . '/wiki/' . $this->wikiTestPage;
	}

	public function testShowCreateForm() {
		$this->open($this->wikiTestUrl);
		$this->assertElementContainsText('css=.heading', 'Artikel ' . $this->wikiTestPage . ' existiert noch nicht!');
		$this->assertElementPresent('css=#create-article');
		$this->assertElementPresent('css=#txtAuthor');
		$this->assertElementPresent('css=#txtAuthorEmail');
		$this->assertElementPresent('css=.save-button');
	}
	
	public function testCreatePage() {
		$this->open($this->wikiTestUrl);
		$this->type('css=#txtAuthor', self::AUTHOR);
		$this->type('css=#txtAuthorEmail', self::AUTHOR_EMAIL);
		$this->assertElementPresent('css=.save-button');
		$this->click('css=.save-button');
		
		$this->assertElementPresent('css=.heading');
		$this->assertElementPresent('css=#edit-article');
		$this->assertElementPresent('css=#contentEditor');
		$this->assertElementPresent('css=#txtTags__ptags');
		$this->assertElementPresent('css=#versionComment');
		$this->assertElementPresent('css=#txtAuthor');
		$this->assertElementPresent('css=#txtAuthorEmail');

		$this->assertElementContainsText('css=.heading', $this->wikiTestPage);
		$this->assertElementValueEquals('css=#txtAuthor', self::AUTHOR);
		$this->assertElementValueEquals('css=#txtAuthorEmail', self::AUTHOR_EMAIL);
	}
}
