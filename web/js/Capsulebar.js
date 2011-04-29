/**
 * Capsulebar init click events
 * 
 * @author Michael Weibel <mweibel@hsr.ch>
 */
var Capsulebar = (function() {
	return {
		init: function(articleId, articleTimestamp) {
			$("#capsulebar-read").click(function(e) {
				Article.loadArticle(articleId, articleTimestamp);
				e.preventDefault();
			});
			$("#capsulebar-edit").click(function(e) {
				Article.loadEditForm(articleId, articleTimestamp);
				e.preventDefault();
			});
			$("#capsulebar-history").click(function(e) {
				Article.loadHistory(articleId, articleTimestamp);
				e.preventDefault();
			});
		}
	};
}());