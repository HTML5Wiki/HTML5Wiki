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
 * @package Web
 * @subpackage Javascript
 */

/**
 * Encapsulates all available translations for javascript in a Zend_Translate way.
 * 
 * @see Translate#_
 */
var Translate = (function() {
	var self = {},
		translations = {};
	
	self.init = function(phpTranslations) {
		translations = phpTranslations;
	};
	
	/**
	 * Translate a given key (first argument). 
	 * If more than one argument is supplied, placeholders get 
	 * replaced by the specified arguments.
	 */
	self._ = function() {
		var key = arguments[0],
			value = translations[key];
		for (var i = 1, l = arguments.length; i < l; i++) {
			value = value.replace(/\%s/, arguments[i]);
		}
		return value;
	};
	
	return self;
}());