/**
 * Capsulebar init click events
 * 
 * @author Michael Weibel <mweibel@hsr.ch>
 */
var Capsulebar = (function() {
	var self = {}
		,articleId = '';

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
	
	self.onPopState = function(e) {
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