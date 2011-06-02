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
 * Class for adding new menu items with javascript
 */
var Menu = (function() {
	var self = {};
	
	self.addOrReplaceArticleTab = function(url, title) {
		var articleTab = $('.menu-items .article a');
		if (articleTab.length) {
			articleTab.attr('href', url);
			articleTab.text(title);
		} else {
			articleTab = $('<li class="item article active">'
				+ '<a href="' + url + '" class="tab">' + title + '</a>');
			$('.menu-items').append(articleTab);
		}
		articleTab.addClass('active');
	};
	
	return self;
}());