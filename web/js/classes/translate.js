/**
 * Encapsulates all available translations for javascript
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Web
 * @subpackage Javascript
 */

var Translate = (function() {
	var self = {},
		translations = {};
	
	self.init = function(translations) {
		self.translations = translations;
	};
	
	self._ = function() {
		var key = arguments[0],
			value = self.translations[key];
		for (var i = 1, l = arguments.length; i < l; i++) {
			value = value.replace(/\%s/, arguments[i]);
		}
		return value;
	};
	
	return self;
}());