$(document).ready(function() {
	SearchBoxController.initWithSearchBox($('#header-searchbox'));
	
	// templates/wiki/edit.php-Stuff
	$('.editor #contentEditor').markItUp(html5WikiMarkItUpSettings);
	$('.editor h1.heading').bind('mouseup', handleEditArticleTitle);
	$('.editor #txtTags').ptags();
});

/**
 * Changes the h1 of the Article-Editor into a textfield for editing the title
 * of an article.
 * A button with the possibility to cancel the title-editor is appended.
 *
 * @author Manuel Alabor
 * @access public
 */
function handleEditArticleTitle() {
	var heading = $(this);
	var title = heading.text();
	var titleEditor = $('<input value="'+title+'" class="txtTitle" id="txtTitle" name="txtTitle" />');
	
	var cancelButton = $('<a href="#" class="button">Wiederherstellen</a>');
	cancelButton.bind('mouseup',{title:title}, function(event) {
		var heading = $('<h1 class="heading">'+event.data.title+'</h1>');
		$(heading).bind('mouseup', handleEditArticleTitle);
		$('.editor-wrapper').replaceWith(heading);
		return false;
	});
	
	
	var container = $('<div class="editor-wrapper" />');
	container.append(titleEditor);
	container.append('<br/><span class="cancel">M&ouml;chten Sie den Titel wiederherstellen?</span> ');
	container.append(cancelButton);
	heading.replaceWith(container);
	
	return false;			
}