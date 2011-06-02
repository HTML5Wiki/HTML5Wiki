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
 * @author Manuel Alabor <malabor@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @category Html5Wiki
 * @package Library
 * @subpackage View
 */

/**
 * Encapsulates main Html5Wiki javscript functions.
 */
var Html5Wiki = (function() {
	var self = {}
		,baseURL = '';
	
	self.init = function(baseURL) {
		self.baseURL = baseURL;
	}
	
	self.getUrl = function(path) {
		return window.location.protocol + '//' + window.location.host + Html5Wiki.baseURL + path;
	}
	
	return self;
	
}());  // end Html5Wiki