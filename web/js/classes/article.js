/**
 * Encapsulates methods for working with the article editor.
 *
 * @see templates/wiki/edit.php
 */
var Article = (function() {
	var self = {};
	
	self.loadArticle = function(url, idArticle) {
		$.ajax({
			type: 'get',
			'url':  url,
			complete: this.replaceContent.bind(this),
			data: 'idArticle=' + idArticle
		});
		return url;
	}

	self.create = function() {
		var form	= $('#create-article');
		if( form ) {
			var name    = $('#txtAuthor').val();
			var email   = $('#txtAuthorEmail').val();
            var id      = $('#hiddenAuthorId').val();

			var mediaData = {
				txtAuthor: name,
				txtAuthorEmail: email,
				hiddenAuthorId: id	
			};

			$.ajax({
				type: 'POST',
				url: form.attr('action'), 
				data: mediaData,
				complete: Article.onEditFormLoaded.bind(this)
			});
		}
	}
	
	self.save = function(e) {
		e.preventDefault();
		var form	= $(e.currentTarget);
		if( form ) {
            var idArticle        = $('#hiddenIdArticle').val();
            var timestampArticle = $('#hiddenTimestampArticle').val();
            var title     = $('#txtTitle').val();
            var content   = $('#contentEditor').val();
			var versionComment = $('#versionComment').val();
			var name      = $('#txtAuthor').val();
			var email     = $('#txtAuthorEmail').val();
            var id        = $('#hiddenAuthorId').val();
			var tags      = $('#txtTags').val();
			var overwrite = $('#hiddenOverwrite').val();

			var mediaData = {
                hiddenIdArticle: idArticle,
                hiddenTimestampArticle: timestampArticle,
                txtTitle: title,
                contentEditor: content,
				versionComment: versionComment,
				txtAuthor: name,
				txtAuthorEmail: email,
				hiddenAuthorId: id,
				tags: tags,
				hiddenOverwrite: overwrite
			};
			$.ajax({
				type: 'POST',
				url: form.attr('action'), 
				data: mediaData,
				complete: function(response) {
					Article.replaceContent(response);
				},
				success: function(response) {
					console.log('f');
					var url = window.location.href.replace(/(edit|new)/, 'read');
					history.pushState({articleId: idArticle, 'url': url}, 'read', url);
					try {
						console.log('b');
						Menu.addOrReplaceArticleTab(url, title);
					} catch(e) {
						console.log(e);
					}
				}
			});
		}
	}
	
	self.replaceContent = function(response, textStatus) {
		$('#content').replaceWith(response.responseText);
	}
	
	self.loadEditForm = function(url, idArticle) {
		$.ajax({
            type:   'get',
            url:    url,
            data:   'idArticle=' + idArticle,
			complete: Article.onEditFormLoaded.bind(this)
        });
		return url;
    }

	self.onEditFormLoaded = function(response, textStatus) {
		this.replaceContent(response);
	}
	
	self.loadHistory = function(url, idArticle) {
		$.ajax({
			type:   'get',
            url:    url,
            data:   'idArticle=' + idArticle,
			complete: Article.replaceContent.bind(this)
        });
		
		return url;
	}
	
	/**
     * Changes the h1 of the Article-Editor into a textfield for editing the title
     * of an article.
     * A button with the possibility to cancel the title-editor is appended.
     *
     * @access public
     */
	self.handleEditArticleTitle = function() {
		var heading = $('.heading');
		var title = heading.text();
		var titleEditor = $('<input value="'+title+'" class="textfield" id="txtTitle" name="txtTitle" />');

		var cancelButton = $('<a href="#" class="button">Wiederherstellen</a>');
		cancelButton.bind('mouseup',{title:title}, function(event) {
			var heading = $('<h1 class="heading">'+event.data.title+'</h1>');
			$(heading).bind('mouseup', Article.handleEditArticleTitle);
			$('.editor-wrapper').replaceWith(heading);
			return false;
		});

		var container = $('<div class="editor-wrapper" />');
		container.append(titleEditor);
		container.append('<br/><span class="cancel">M&ouml;chten Sie den urspr&uuml;nglichen Titel wiederherstellen?</span>');
		container.append(cancelButton);
		heading.replaceWith(container);

		return false;
	}
	
	self.setupArticleEditorGui = function() {
		$('.editor #contentEditor').markItUp(html5WikiMarkItUpSettings);
		$('.editor #txtTags').ptags();
	}
	
	self.setupArticleEditorEvents = function() {
		$("#edit-article").submit(Article.save.bind());
		$('.editor h1.heading').bind('mouseup', Article.handleEditArticleTitle);
	}

	return self;
	
}());  // end Article
