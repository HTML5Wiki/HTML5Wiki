/**
 * Capsulebar init click events
 * 
 * @author Michael Weibel <mweibel@hsr.ch>
 */
var Capsulebar = (function() {
	var articleId;
	return {
		init: function(articleId) {
			this.articleId = articleId;
			this.initializeHistory();
			this.initializeClickEvents();
			
			$(window).bind('popstate', this.onPopState.bind(this));
		},
		
		initializeClickEvents: function() {
			$("#capsulebar-read").bind('click', this.onClick.bind(this, 'read'));
			$("#capsulebar-edit").bind('click', this.onClick.bind(this, 'edit'));
			$("#capsulebar-history").bind('click', this.onClick.bind(this, 'history'));
		},
		
		onClick: function(page, e) {
			var url = e.currentTarget.href;
			this.setContent(page, this.articleId, url);
			this.updateHistory(page, url, this.articleId);
			
			e.preventDefault();
		},
		
		initializeHistory: function() {
			var url = window.location.pathname;
			
			this.updateHistory(this.getPage(url), url);
		},
		
		updateHistory: function(toPageTitle, toPageUrl) {
			history.pushState(
				{
					'articleId' : this.articleId, 
					'url': toPageUrl
				}, 
				toPageTitle, toPageUrl);
		},
		
		onPopState: function(e) {
			this.setContent(this.getPage(history.state.url), history.state.articleId, e.currentTarget.location.href);
			
			e.preventDefault();
		},
		
		setContent: function(page, articleId, url) {
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
		},
		
		getPage: function(url) {
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
	};
}());