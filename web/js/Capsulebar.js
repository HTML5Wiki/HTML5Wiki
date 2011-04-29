/**
 * Capsulebar init click events
 * 
 * @author Michael Weibel <mweibel@hsr.ch>
 */
var Capsulebar = (function() {
	var articleId, articleTimestamp;
	return {
		init: function(articleId, articleTimestamp) {
			this.articleId = articleId;
			this.articleTimestamp = articleTimestamp;
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
			var url = this.setContent(page, e);
			this.updateHistory(page, url, this.articleId, this.articleTimestamp);
			
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
					'articleTimestamp': this.articleTimestamp, 
					'url': toPageUrl
				}, 
				toPageTitle, toPageUrl);
		},
		
		onPopState: function(e) {
			this.setContent(this.getPage(history.state.url), history.state.articleId, history.state.articleTimestamp);
			
			e.preventDefault();
		},
		
		setContent: function(page, e) {
			var url;
			switch (page) {
				case 'history':
					url = Article.loadHistory(e, this.articleId, this.articleTimestamp);
					break;
				case 'edit':
					url = Article.loadEditForm(e, this.articleId, this.articleTimestamp);
					break;
				default:
					url = Article.loadArticle(e, this.articleId, this.articleTimestamp);
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