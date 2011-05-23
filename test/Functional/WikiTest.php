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
	
	const AUTHOR = 'seleniumtestUser';
	const AUTHOR_EMAIL = 'seleniumtest@foobar.com';
	
	const TEST_TITLE = 'Selenium lorem test';
	const TEST_CONTENT = 'Lorem ipsum dolor.';
	const TEST_TAG_INSERT = 'selenium test,seleniumtestuser,selenium';
	const TEST_VERSION_COMMENT = 'selenium test';
	
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
		
		$this->setBrowserUrl(TEST_HOST);
		$this->wikiTestPage .= self::$time;
		$this->wikiTestUrl = TEST_HOST . '/wiki/' . $this->wikiTestPage;
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
		$this->assertElementPresent('css=#article-create');
		$this->click('css=#article-create');
		$this->waitForAjax();
		
		$this->captureEntirePageScreenshot('/tmp/selenium-test-create-page.png');
		
		$this->assertEditorPresent();

		$this->assertElementContainsText('css=.heading', $this->wikiTestPage);
		
		$this->assertElementValueEquals('css=#txtAuthor', self::AUTHOR);
		$this->assertElementValueEquals('css=#txtAuthorEmail', self::AUTHOR_EMAIL);
	}
	
	public function testEditPage() {
		$this->open($this->wikiTestUrl);
		
		$this->captureEntirePageScreenshot('/tmp/selenium-test-edit-page1.png');
		
		$this->assertElementPresent('css=#capsulebar-edit');
		$this->click('css=#capsulebar-edit');
		$this->waitForAjax();
		
		$this->captureEntirePageScreenshot('/tmp/selenium-test-edit-page2.png');
		
		$this->assertEditorPresent();
		
		$this->clickAt('css=.editor h1.heading');
		
		$this->captureEntirePageScreenshot('/tmp/selenium-test-edit-page3.png');
		
		$this->assertElementPresent('css=.editor-wrapper #txtTitle');
		$this->assertElementPresent('css=.editor-wrapper .cancel');
		$this->assertElementPresent('css=.editor-wrapper .button');
		
		$this->type('css=.editor-wrapper #txtTitle', self::TEST_TITLE);
		
		$this->captureEntirePageScreenshot('/tmp/selenium-test-edit-page4.png');
		
		$this->type('css=#contentEditor', self::TEST_CONTENT);
		
		$this->fireEvent('css=#txtTags__ptags', 'focus');
		$this->type('css=#txtTags__ptags', self::TEST_TAG_INSERT);
		$this->fireEvent('css=#txtTags__ptags', 'blur');
		
		$this->type('css=#versionComment', self::TEST_VERSION_COMMENT);
		
		// fill in author & email again, as selenium creates a new FF profile without cookies on every start.
		$this->type('css=#txtAuthor', self::AUTHOR);
		$this->type('css=#txtAuthorEmail', self::AUTHOR_EMAIL);
		
		$this->captureEntirePageScreenshot('/tmp/selenium-test-edit-page5.png');
		
		$this->click('css=.save-button');
		$this->waitForAjax();
		
		$this->captureEntirePageScreenshot('/tmp/selenium-test-edit-page6.png');
		
		$this->assertReadArticlePresent();		
		$this->assertReadArticleValues();
	}
	
	private function assertReadArticlePresent() {
		$this->assertElementPresent('css=.heading h1');
		$this->assertElementPresent('css=.heading .meta .intro');
		$this->assertElementPresent('css=.heading .meta .lastchange');
		$this->assertElementPresent('css=.heading .meta .tags');
		$this->assertElementPresent('css=.heading .meta .tag');
		$this->assertElementPresent('css=section');
	}
	
	private function assertReadArticleValues() {
		$this->assertElementContainsText('css=.heading h1', self::TEST_TITLE);
		$this->assertElementPresent('link=selenium test');
		$this->assertElementPresent('link=seleniumtestuser');
		$this->assertElementPresent('link=selenium');
		$this->assertTextPresent(self::TEST_TITLE);
	}
	
	private function assertEditorPresent() {
		$this->assertElementPresent('css=.heading');
		$this->assertElementPresent('css=#edit-article');
		$this->assertElementPresent('css=#contentEditor');
		$this->assertElementPresent('css=#txtTags__ptags');
		$this->assertElementPresent('css=#versionComment');
		$this->assertElementPresent('css=#txtAuthor');
		$this->assertElementPresent('css=#txtAuthorEmail');
	}
}
