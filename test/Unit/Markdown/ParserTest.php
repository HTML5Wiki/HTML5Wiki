<?php
/**
 * Markdown parser test - 3rd party library without tests.
 *
 * @author		Michael Weibel <mweibel@hsr.ch>
 * @copyright	(c) HTML5Wiki Team 2011
 * @package		Test
 * @subpackage  Markdown
 */

require 'Markdown/Parser.php';

class Test_Unit_Markdown_ParserTest extends PHPUnit_Framework_TestCase {
	/**
	 * Markdown parser instance
	 * @var Markdown_Parser
	 */
	private $parser = null;

	public function setUp() {
		$this->parser = new Markdown_Parser();
	}

	public function testHeadingsSetext() {
		$text = <<<EOF
Test Heading Two
================

Test Heading Three
------------------
EOF;
		$expected = <<<EOF
<h2>Test Heading Two</h2>

<h3>Test Heading Three</h3>

EOF;
		$this->transformAndTest($text, $expected);
	}

	public function testHeadingsAtx() {
		$text = <<<EOF
# Test Heading Two

## Test Heading Three

### Test Heading Four
EOF;
		$expected = <<<EOF
<h2>Test Heading Two</h2>

<h3>Test Heading Three</h3>

<h4>Test Heading Four</h4>

EOF;
		$this->transformAndTest($text, $expected);
	}

	public function testHardBreaks() {
		$text = <<<EOF
Test Hardbreak with two whitespaces before newline.  
This should be hardbreaked.
EOF;
		$expected = <<<EOF
<p>Test Hardbreak with two whitespaces before newline.<br />
This should be hardbreaked.</p>

EOF;
		$this->transformAndTest($text, $expected);
	}

	public function testBlockquote() {
		$text = <<<EOF
> This is a blockquote.
>
> This is the second paragraph in the blockquote.
>
> #This is a h2 in a blockquote
EOF;
		$expected = <<<EOF
<blockquote>
 <p>This is a blockquote.</p>
 
 <p>This is the second paragraph in the blockquote.</p>
 
 <h2>This is a h2 in a blockquote</h2>
</blockquote>

EOF;
		$this->transformAndTest($text, $expected);
	}

	public function testEmphasized() {
		$text = <<<EOF
Some of these words *are emphasized*.
Some of these words _are emphasized also_.

Use two asterisks for **strong emphasis**.
Or, if you prefer, __use two underscores instead__.
EOF;
		$expected = <<<EOF
<p>Some of these words <em>are emphasized</em>.
Some of these words <em>are emphasized also</em>.</p>

<p>Use two asterisks for <strong>strong emphasis</strong>.
Or, if you prefer, <strong>use two underscores instead</strong>.</p>

EOF;
		$this->transformAndTest($text, $expected);
	}

	public function testItalicsAndBold() {
		$text = <<<EOF
Some of these words ***are strong and italics***.
Some of these words ___are strong and italics also___.
EOF;
		$expected = <<<EOF
<p>Some of these words <strong><em>are strong and italics</em></strong>.
Some of these words <strong><em>are strong and italics also</em></strong>.</p>

EOF;
		$this->transformAndTest($text, $expected);
	}

	public function testUnorderedLists() {
		$expected = <<<EOF
<ul>
<li>Candy.</li>
<li>Gum.</li>
<li>Booze.</li>
</ul>

EOF;

		$text = <<<EOF
* Candy.
* Gum.
* Booze.
EOF;
		$this->transformAndTest($text, $expected);

		$text = <<<EOF
+ Candy.
+ Gum.
+ Booze.
EOF;
		$this->transformAndTest($text, $expected);

		$text = <<<EOF
- Candy.
- Gum.
- Booze.
EOF;
		$this->transformAndTest($text, $expected);
	}

	public function testOrderedList() {
		$text = <<<EOF
1. Red
2. Green
3. Blue
EOF;
		$expected = <<<EOF
<ol>
<li>Red</li>
<li>Green</li>
<li>Blue</li>
</ol>

EOF;
		$this->transformAndTest($text, $expected);
	}

	public function testMultipleParagraphsInList() {
		$text = <<<EOF
* A list item.
	
  With multiple paragraphs.
  
* Another item in the list.

EOF;
		$expected = <<<EOF
<ul>
<li><p>A list item.</p>

<p>With multiple paragraphs.</p></li>
<li><p>Another item in the list.</p></li>
</ul>

EOF;
		$this->transformAndTest($text, $expected);
	}

	public function testLink() {
		$text = <<<EOF
This is an [example link](http://example.com/).
EOF;
		$expected = <<<EOF
<p>This is an <a href="http://example.com/">example link</a>.</p>

EOF;
		$this->transformAndTest($text, $expected);
	}

	public function testLinkWithTitle() {
		$text = <<<EOF
This is an [example link](http://example.com/ "With a Title").
EOF;
		$expected = <<<EOF
<p>This is an <a href="http://example.com/" title="With a Title">example link</a>.</p>

EOF;
		$this->transformAndTest($text, $expected);
	}

	public function testAutoLink() {
		$text = <<<EOF
This is an <http://example.com/>.
EOF;
		$expected = <<<EOF
<p>This is an <a href="http://example.com/">http://example.com/</a>.</p>

EOF;
		$this->transformAndTest($text, $expected);
	}

	public function testEmailAutoLink() {
		$text = <<<EOF
This is a email link <mailto:foo@example.com>.
EOF;
		$expected = <<<EOF
<p>This is a email link <a href="&#x6d;&#97;&#105;&#x6c;&#116;&#111;&#x3a;&#x66;&#111;&#x6f;&#x40;&#101;&#120;&#x61;&#109;&#112;&#x6c;&#x65;&#46;&#x63;&#x6f;&#109;">&#x66;&#111;&#x6f;&#x40;&#101;&#120;&#x61;&#109;&#112;&#x6c;&#x65;&#46;&#x63;&#x6f;&#109;</a>.</p>

EOF;
		$this->transformAndTest($text, $expected);
	}

	public function testNumberedReferenceStyleLinks() {
		$text = <<<EOF
I get 10 times more traffic from [Google][1] than from
[Yahoo][2] or [MSN][3].

[1]: http://google.com/        "Google"
[2]: http://search.yahoo.com/  "Yahoo Search"
[3]: http://search.msn.com/    "MSN Search"
EOF;
		$expected = <<<EOF
<p>I get 10 times more traffic from <a href="http://google.com/" title="Google">Google</a> than from
<a href="http://search.yahoo.com/" title="Yahoo Search">Yahoo</a> or <a href="http://search.msn.com/" title="MSN Search">MSN</a>.</p>

EOF;
		$this->transformAndTest($text, $expected);
	}

	public function testNamedReferenceStyleLinks() {
		$text = <<<EOF
I start my morning with a cup of coffee and
[The New York Times][NY Times].

[ny times]: http://www.nytimes.com/
EOF;
		$expected = <<<EOF
<p>I start my morning with a cup of coffee and
<a href="http://www.nytimes.com/">The New York Times</a>.</p>

EOF;
		$this->transformAndTest($text, $expected);
	}

	public function testImages() {
		$expected = <<<EOF
<p><img src="/path/to/img.jpg" alt="alt text" title="Title" /></p>

EOF;
		$text = <<<EOF
![alt text](/path/to/img.jpg "Title")
EOF;
		$this->transformAndTest($text, $expected);
	}

	public function testReferenceStyleImages() {
		$expected = <<<EOF
<p><img src="/path/to/img.jpg" alt="alt text" title="Title" /></p>

EOF;
		$text = <<<EOF
![alt text][id]

[id]: /path/to/img.jpg "Title"
EOF;
		$this->transformAndTest($text, $expected);
	}

	public function testCodeInSpan() {
		$expected = <<<EOF
<p>I strongly recommend against using any <code>&lt;blink&gt;</code> tags.</p>

<p>I wish SmartyPants used named entities like <code>&amp;mdash;</code>
instead of decimal-encoded entites like <code>&amp;#8212;</code>.</p>

EOF;
		$text = <<<EOF
I strongly recommend against using any `<blink>` tags.

I wish SmartyPants used named entities like `&mdash;`
instead of decimal-encoded entites like `&#8212;`.
EOF;
		$this->transformAndTest($text, $expected);
	}

	public function testCodeBlock() {
		$expected = <<<EOF
<p>If you want your page to validate under XHTML 1.0 Strict,
you've got to put paragraph tags in your blockquotes:</p>

<pre><code>&lt;blockquote&gt;
    &lt;p&gt;For example.&lt;/p&gt;
&lt;/blockquote&gt;
</code></pre>

EOF;
		$text = <<<EOF
If you want your page to validate under XHTML 1.0 Strict,
you've got to put paragraph tags in your blockquotes:

	<blockquote>
		<p>For example.</p>
	</blockquote>
EOF;
		$this->transformAndTest($text, $expected);
	}

	private function transformAndTest($text, $expected) {
		$parsed = $this->parser->transform($text);
		$this->assertEquals($expected, $parsed);
	}

	public function tearDown() {
		$this->parser = null;
	}
}
?>
