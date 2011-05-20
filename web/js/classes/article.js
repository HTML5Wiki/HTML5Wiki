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
			var tags      = Article.collectMediaTags();

			var mediaData = {
                hiddenIdArticle: idArticle,
                hiddenTimestampArticle: timestampArticle,
                txtTitle: title,
                contentEditor: content,
				versionComment: versionComment,
				txtAuthor: name,
				txtAuthorEmail: email,
				hiddenAuthorId: id,
				tags: tags.join(',')
			};
			$.ajax({
				type: 'POST',
				url: form.attr('action'), 
				data: mediaData,
				complete: function(response) {
					Article.replaceContent(response);
					var url = window.location.href.replace('edit', 'read');
					history.pushState({articleId: idArticle, 'url': url}, 'read', url);
				}
			});
		}
	}

	/**
	 * Setup the article editor GUI with ptags and markitup.
	 *
	 * @author Manuel Alabor <malabor@hsr.ch>
	 */
	self.setupArticleEditorGui = function() {
		$("#edit-article").submit(Article.save.bind());
		
		$('.editor #contentEditor').markItUp(html5WikiMarkItUpSettings);
		$('.editor h1.heading').bind('mouseup', Article.handleEditArticleTitle);
		$('.editor #txtTags').ptags();
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
		
		this.bindEditorEvents();
	}
	
	self.bindEditorEvents = function() {
		// templates/wiki/edit.php-Stuff
		$('.editor #contentEditor').markItUp(html5WikiMarkItUpSettings);
		$('.editor h1.heading').bind('mouseup', Article.handleEditArticleTitle);
		$('.editor #txtTags').ptags();
		$('.editor #txtTags').bind('change', Article.collectMediaTags);
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
     * @author Manuel Alabor
     * @access public
     */
	self.handleEditArticleTitle = function() {
		var heading = $(this);
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
	
	self.collectMediaTags = function() {
		var tags = [];
		
		// @todo Not necessary like this! Tags are backed in the original
		// input field
		
		$('.ui-ptags-tag-text').each(function(element, test, test2) {
			tags.push($.trim(test.innerHTML));
		});

		return tags;
	}

	return self;
	
}());  // end Article