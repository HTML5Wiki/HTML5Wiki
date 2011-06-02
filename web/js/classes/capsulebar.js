/**
 * This file is part of the HTML5Wiki Project.
 * 
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
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
 * Capsulebar handles click events and navigation transition events
 */
var Capsulebar = (function() {
	var self = {}
		,articleId = '',
		loaded = false;

	self.init = function(articleId) {
		self.articleId = articleId;
		self.initializeClickEvents();

		$(window).bind('popstate', self.onPopState);
	};
	
	self.initializeClickEvents = function() {
		$("#capsulebar-read").bind('click', function(e) {
			self.onClick('read', e);
		});
		$("#capsulebar-edit").bind('click', function(e) {
			self.onClick('edit', e);
		});
		$("#capsulebar-history").bind('click', function(e) {
			self.onClick('history', e);
		});
	};
	
	self.onClick = function(page, e) {
		var url = e.currentTarget.href;
		self.setContent(page, self.articleId, url);
		self.updateHistory(page, url);
		
		e.preventDefault();
	};
	
	self.updateHistory = function(toPageTitle, toPageUrl) {
		try {
			history.pushState({
					'articleId' : self.articleId, 
					'url': toPageUrl
				}
				,toPageTitle
				,toPageUrl
			);
		} catch(e) {
			//html5 history not supported
		}
	};
	
	/**
	 * onPopState is called when navigation traversal is done. 
	 * This happens on following events:
	 *   - page load
	 *   - back button
	 *   - forward button
	 * 
	 * To prevent a not wanted reload on page load, the first loaded check is done.
	 * 
	 * @link http://stackoverflow.com/questions/5257819/onpopstate-handler-ajax-back-button
	 */
	self.onPopState = function(e) {
		if (!loaded) {
			loaded = true;
			return;
		}
		try {
			var url, articleId, href;
			if (history.state) {
				url = history.state.url;
				articleId = history.state.articleId;
				href = e.currentTarget.location.href;
			} else {
				url = e.currentTarget.location.href;
				articleId = self.articleId;
				href = url;
			}
			self.setContent(self.getPage(url), articleId, href);
			e.preventDefault();
		} catch(e) {
			//html5 history not supported
		}
	};
	
	self.setContent = function(page, articleId, url) {
		var url;
		switch (page) {
			case 'history':
				url = Article.loadHistory(url, articleId);
				break;
			case 'edit':
				url = Article.loadEditForm(url, articleId);
				break;
			default:
				url = Article.loadArticle(url, articleId);
		}
		return url;
	};
	
	self.getPage = function(url) {
		var page;
		if (url.indexOf('edit') !== -1) {
			page = 'edit';
		} else if (url.indexOf('history') !== -1) {
			page = 'history';
		} else {
			page = 'read';
		}
		return page;
	};
	
	self.setActive = function(active) {
		var url = window.location.href.replace(/(edit|read|history|save|new)/, active);
		self.updateHistory(active, url);
	};
	
	return self;
	
}());  // end Capsulebar