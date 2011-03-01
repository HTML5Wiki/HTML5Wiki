<?php
/**
 * Disallow space indenting - use tabs instead
 *
 * Source: Squiz Coding Standard: DisallowTabIndentSniff
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    HTML5Wiki
 * @subpackage PHP_CodeSniffer
 * @author     Greg Sherwood <gsherwood@squiz.net>
 * @author     Marc McIntyre <mmcintyre@squiz.net>
 * @author     Michael Weibel <mweibel@hsr.ch>
 * @copyright  2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license    http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @link       http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Disallow space indenting - use tabs instead
 *
 * Throws errors if spaces are used for indentation.
 *
 * @category   PHP
 * @package    HTML5Wiki
 * @subpackage PHP_CodeSniffer
 * @author     Greg Sherwood <gsherwood@squiz.net>
 * @author     Marc McIntyre <mmcintyre@squiz.net>
 * @author     Michael Weibel <mweibel@hsr.ch>
 * @copyright  2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license    http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @link       http://pear.php.net/package/PHP_CodeSniffer
 */
class Html5WikiStandard_Sniffs_WhiteSpace_DisallowSpaceIndentSniff implements PHP_CodeSniffer_Sniff
{
	/**
	 * A list of tokenizers this sniff supports.
	 *
	 * @var array
	 */
	public $supportedTokenizers = array('PHP', 'JS', 'CSS');


	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register()
	{
		return array(T_WHITESPACE);

	}


	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile All the tokens found in the document.
	 * @param int                  $stackPtr  The position of the current token in
	 *                                        the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		// Make sure this is whitespace used for indentation.
		$line = $tokens[$stackPtr]['line'];
		if ($stackPtr > 0 && $tokens[($stackPtr - 1)]['line'] === $line) {
			return;
		}

		if (strpos($tokens[$stackPtr]['content'], " ") !== false) {
			$error = 'Tabs must be used to indent lines; spaces are not allowed';
			$phpcsFile->addError($error, $stackPtr, 'SpacesUsed');
		}

	}


}
