Article = {

	loadArticle: function(idArticle, timestampArticle) {
		$.ajax({
			type: 'POST',
			url: '',
			complete: this.replaceContent.bind(this),
			data: 'ajax=true&idArticle=' + idArticle + '&timestampArticle=' + timestampArticle
		})
	},

	create: function() {
		var form	= $('#create-article');
		if( form ) {
			var name    = $('#txtAuthor').val();
			var email   = $('#txtAuthorEmail').val();
            var id      = $('#hiddenAuthorId').val();

			var media_data = {
				txtAuthor: name,
				txtAuthorEmail: email,
				hiddenAuthorId: id,
				ajax: true		
			};

			$.ajax({
				type: 'POST',
				url: form.attr('action'), 
				data: media_data,
				success: function(msg) {
					$('#content').html(msg);
				}
			});
		}
	},

	replaceContent: function(response) {
		$('#content').replaceWith(response.responseText);
	},

	/**
	 * 
	 * @param idArticle
	 * @param timestampArticle
	 */
    loadEditForm: function(idArticle, timestampArticle) {
		$.ajax({
            type:   'POST',
            url:    'edit',
            data:   'ajax=true&idArticle=' + idArticle + '&timestampArticle=' + timestampArticle,
			complete: this.onEditFormLoaded.bind(this)
        });
    },

	/**
	 * 
	 * @param response
	 */
	onEditFormLoaded: function(response) {
		this.replaceContent(response);
		
		// templates/wiki/edit.php-Stuff
		$('.editor #contentEditor').markItUp(html5WikiMarkItUpSettings);
		$('.editor h1.heading').bind('mouseup', this.handleEditArticleTitle);
		$('.editor #txtTags').ptags();
	},

	/**
     * Changes the h1 of the Article-Editor into a textfield for editing the title
     * of an article.
     * A button with the possibility to cancel the title-editor is appended.
     *
     * @author Manuel Alabor
     * @access public
     */
	handleEditArticleTitle: function() {
		console.log($(this));
		var heading = $(this);
		var title = heading.text();
		var titleEditor = $('<input value="'+title+'" class="txtTitle" id="txtTitle" name="txtTitle" />');

		var cancelButton = $('<a href="#" class="button">Wiederherstellen</a>');
		cancelButton.bind('mouseup',{title:title}, function(event) {
			var heading = $('<h1 class="heading">'+event.data.title+'</h1>');
			$(heading).bind('mouseup', Article.handleEditArticleTitle);
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
};