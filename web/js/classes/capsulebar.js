/**
 * Capsulebar init click events
 * 
 * @author Michael Weibel <mweibel@hsr.ch>
 */
var Capsulebar = (function() {
	var self = {}
		,articleId = '';

	self.init = function(articleId) {
		this.articleId = articleId;
		this.initializeClickEvents();

		$(window).bind('popstate', this.onPopState.bind(this));
	}
	
	self.initializeClickEvents = function() {
		$("#capsulebar-read").bind('click', this.onClick.bind(this, 'read'));
		$("#capsulebar-edit").bind('click', this.onClick.bind(this, 'edit'));
		$("#capsulebar-history").bind('click', this.onClick.bind(this, 'history'));
	}
	
	self.onClick = function(page, e) {
		var url = e.currentTarget.href;
		this.setContent(page, this.articleId, url);
		this.updateHistory(page, url, this.articleId);
		
		e.preventDefault();
	}
	
	self.updateHistory = function(toPageTitle, toPageUrl) {
		history.pushState({
				'articleId' : this.articleId, 
				'url': toPageUrl
			}
			,toPageTitle
			,toPageUrl
		);
	}
	
	self.onPopState = function(e) {
		var url, articleId, href;
		if (history.state) {
			url = history.state.url;
			articleId = history.state.articleId;
			href = e.currentTarget.location.href;
		} else {
			url = e.currentTarget.location.href;
			articleId = this.articleId;
			href = url;
		}
		this.setContent(this.getPage(url), articleId, href);
		e.preventDefault();
	}
	
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
	}
	
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
	}
	
	return self;
	
}());  // end Capsulebar