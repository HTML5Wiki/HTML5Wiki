/**
 * Class for adding new menu items with javascript
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Web
 * @subpackage Javascript
 */

var Menu = (function() {
	var self = {};
	
	self.addOrReplaceArticleTab = function(url, title) {
		var articleTab = $('.menu-items .article');
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