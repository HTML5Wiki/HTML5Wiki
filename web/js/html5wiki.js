$(document).ready(function() {
	SearchBoxController.initWithSearchBox($('#header-searchbox'));
	
	// templates/wiki/edit.php-Stuff
	$('.editor #contentEditor').markItUp(html5WikiMarkItUpSettings);
	$('.editor h1.heading').bind('mouseup', Article.handleEditArticleTitle);
	$('.editor #txtTags').ptags();
});

Html5Wiki = {

	baseURL: '',

	init: function(baseURL) {
		this.baseURL = baseURL;
	},

	getUrl: function(path) {
		return window.location.protocol + '//' + window.location.host + Html5Wiki.baseURL + path;
	}

};