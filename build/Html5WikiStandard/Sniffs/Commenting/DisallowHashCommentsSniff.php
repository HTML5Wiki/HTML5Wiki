<?php
/**
 * This sniff prohibits the use of Perl style hash comments.
 *
 * Source:
 * {@link http://pear.php.net/manual/en/package.php.php-codesniffer.coding-standard-tutorial.php
 *  PHP CodeSniffer Coding Standard Tutorial}.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    PHP_CodeSniffer Team
 * @author    Michael Weibel <mweibel@hsr.ch>
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * This sniff prohibits the use of Perl style hash comments.
 *
 * An example of a hash comment is:
 *
 * <code>
 *  # This is a hash comment, which is prohibited.
 *  $hello = 'hello';
 * </code>
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    PHP_CodeSniffer Team
 * @author    Michael Weibel <mweibel@hsr.ch>
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class Html5WikiStandard_Sniffs_Commenting_DisallowHashCommentsSniff implements PHP_CodeSniffer_Sniff
{
	/**
	 * Returns the token types that this sniff is interested in.
	 *
	 * @return array(int)
	 */
	public function register()
	{
		return array(T_COMMENT);

	}


	/**
	 * Processes the tokens that this sniff is interested in.
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile The file where the token was found.
	 * @param int                  $stackPtr  The position in the stack where
	 *                                        the token was found.
	 *
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();
		if ($tokens[$stackPtr]['content']{0} === '#') {
			$error = 'Hash comments are prohibited; found %s';
			$data  = array(trim($tokens[$stackPtr]['content']));
			$phpcsFile->addError($error, $stackPtr, 'Found', $data);
		}

	}


}
