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
	
	const TEST_TITLE_EDIT = 'Edit selenium test';
	const TEST_CONTENT_EDIT = 'Edit lorem ipsum dolor.';
	const TEST_TAG_EDIT = 'edit selenium test';
	const TEST_VERSION_COMMENT_EDIT = 'selenium edit test';
	
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

	public function testShowSearchWhenNoPagePresent() {
		$this->open($this->wikiTestUrl);
		
		$this->captureEntirePageScreenshot('/tmp/selenium-testShowSearchWhenNoPagePresent.png');
		
		$this->assertElementContainsText('css=header.title', sprintf($this->getLanguageKey('searchResultsFor'), $this->wikiTestPage));
		$this->assertElementContainsText('css=section h2', $this->getLanguageKey('noSearchResultsTitle'));
		
		$this->assertMessageBoxPresent();
	}

	public function testOpenCreatePage() {
		$this->openCreatePage();
		
		$this->captureEntirePageScreenshot('/tmp/selenium-testOpenCreatePage.png');
		
		$this->assertEditorPresent();
		$this->assertEditTitleBoxPresent();

		$this->assertElementValueEquals('css=#txtTitle', $this->wikiTestPage);
	}
	
	public function testCreatePageDoNotEnterAnythingFails() {
		$this->openCreatePage();
		
		$this->type('css=#txtTitle', '');
		$this->type('css=#contentEditor', '');
		$this->type('css=#txtAuthor', '');
		$this->type('css=#txtAuthorEmail', '');
		$this->type('css=#txtTags__ptags', '');
		$this->type('css=#versionComment', '');
		
		$this->click('css=#article-save');
		//$this->waitForPageToLoad('50000');
		$this->captureEntirePageScreenshot('/tmp/selenium-testCreatePageDoNotEnterAnythingFails.png');
		
		$this->assertElementPresent('css=#txtTitle.error');
		$this->assertElementPresent('css=#contentEditor.error');
		$this->assertElementPresent('css=#txtAuthor.error');
		$this->assertElementPresent('css=#txtAuthorEmail.error');
		$this->assertElementPresent('css=#txtTags__ptags.error');
	}
	
	public function testSuccessCreatePage() {
		$this->openCreatePage();
		
		$this->type('css=#txtTitle', self::TEST_TITLE);
		$this->type('css=#contentEditor', self::TEST_CONTENT);
		$this->type('css=#txtAuthor', self::AUTHOR);
		$this->type('css=#txtAuthorEmail', self::AUTHOR_EMAIL);
		$this->insertTagsIntoPtagsField();
		$this->type('css=#versionComment', self::TEST_VERSION_COMMENT);
		
		$this->click('css=#article-save');
		$this->waitForPageToLoad('50000');
		$this->captureEntirePageScreenshot('/tmp/selenium-testSuccessCreatePage.png');
		
		$this->assertReadArticlePresent();
		$this->assertReadArticleValues();
	}
	
	public function testEditPage() {
		$this->open($this->wikiTestUrl);
		
		$this->captureEntirePageScreenshot('/tmp/selenium-testEditPage1.png');
		
		$this->assertElementPresent('css=#capsulebar-edit');
		$this->click('css=#capsulebar-edit');
		$this->waitForPageToLoad('50000');
		
		$this->captureEntirePageScreenshot('/tmp/selenium-testEditPage2.png');
		
		$this->assertEditorPresent();
		
		$this->clickAt('css=.editor h1.heading');
		
		$this->captureEntirePageScreenshot('/tmp/selenium-testEditPage3.png');
		
		$this->assertEditTitleBoxPresent();
		
		$this->type('css=.editor-wrapper #txtTitle', self::TEST_TITLE_EDIT);
		
		$this->captureEntirePageScreenshot('/tmp/selenium-testEditPage4.png');
		
		$this->type('css=#contentEditor', self::TEST_CONTENT_EDIT);
		
		$this->type('css=#txtTags', self::TEST_TAG_EDIT);
		
		$this->type('css=#versionComment', self::TEST_VERSION_COMMENT_EDIT);
		
		// fill in author & email again, as selenium creates a new FF profile without cookies on every start.
		$this->type('css=#txtAuthor', self::AUTHOR);
		$this->type('css=#txtAuthorEmail', self::AUTHOR_EMAIL);
		
		$this->captureEntirePageScreenshot('/tmp/selenium-testEditPage5.png');
		
		$this->click('css=#article-save');
		$this->waitForPageToLoad('50000');
		
		$this->captureEntirePageScreenshot('/tmp/selenium-testEditPage6.png');
		
		$this->assertReadArticlePresent();		
		$this->assertElementContainsText('css=.heading h1', self::TEST_TITLE_EDIT);
		$this->assertElementPresent('link=' . self::TEST_TAG_EDIT);
		$this->assertTextPresent(self::TEST_CONTENT_EDIT);
	}
	
	private function insertTagsIntoPtagsField() {
		$this->fireEvent('css=#txtTags__ptags', 'focus');
		$this->type('css=#txtTags__ptags', self::TEST_TAG_INSERT);
		$this->fireEvent('css=#txtTags__ptags', 'blur');
	}
	
	private function openCreatePage() {
		$this->open($this->wikiTestUrl);
		
		$this->click('css=.messagebox .options .button');
		$this->waitForPageToLoad('50000');
		
		$this->captureEntirePageScreenshot('/tmp/selenium-open-create-page.png');
	}
	
	private function assertEditTitleBoxPresent() {
		$this->assertElementPresent('css=.editor-wrapper #txtTitle');
		$this->assertElementPresent('css=.editor-wrapper .cancel');
		$this->assertElementPresent('css=.editor-wrapper .button');
	}
	
	private function assertMessageboxPresent() {
		$this->assertElementPresent('css=.messagebox');
		$this->assertElementPresent('css=.options .button');
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
		$this->assertElementPresent('css=#edit-article');
		$this->assertElementPresent('css=#contentEditor');
		$this->assertElementPresent('css=#txtTags');
		$this->assertElementPresent('css=#versionComment');
		$this->assertElementPresent('css=#txtAuthor');
		$this->assertElementPresent('css=#txtAuthorEmail');
	}
}
