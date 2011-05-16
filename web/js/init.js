$(document).ready(function() {
	// templates/wiki/edit.php-Stuff
	$('.editor #contentEditor').markItUp(html5WikiMarkItUpSettings);
	$('.editor h1.heading').bind('mouseup', Article.handleEditArticleTitle);
	$('.editor #txtTags').ptags();
});