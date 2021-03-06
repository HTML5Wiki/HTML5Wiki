/**
 * This file is part of the HTML5Wiki Project.
 * 
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://www.github.com/HTML5Wiki/HTML5Wiki/blob/master/LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mweibel@hsr.ch so we can send you a copy immediately.
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @author Manuel Alabor <malabor@hsr.ch>
 * @author Alexandre Joly <ajoly@hsr.ch>
 * @author Nicolas Karrer <nkarrer@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @category Html5Wiki
 * @package Web
 * @subpackage Javascript
 */

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
			complete: function(response, textStatus) {
				Article.replaceContent(response, textStatus);
			},
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
				complete: function(response, textStatus) {
					Article.onEditFormLoaded(response, textStatus)
				}
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

			if (typeof title  == "undefined") {
				title = $('article h1.heading').text();
			}

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
					if(response.status != 200){
						Article.replaceContent(response);
					}
				},
				success: function(data, textStatus, response) {
					var url = data;
					window.location.href = url;
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
			complete: function(response, textStatus) {
				Article.onEditFormLoaded(response, textStatus);
			}
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
			complete: function(response, textStatus) {
				Article.replaceContent(response, textStatus);
			}
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
	self.handleEditArticleTitle = function(displayCancelButton) {
		var heading = $('#headingcontainer');
		var title = $('.heading').text();
		var titleEditor = $('<input value="'+title+'" class="textfield" id="txtTitle" placeholder="' + Translate._('title') + '" name="txtTitle" />');
		if(displayCancelButton == undefined) displayCancelButton = true;

		var container = $('<div class="editor-wrapper" />');
		container.append(titleEditor);
		
		if(displayCancelButton) {
			var cancelButton = $('<a href="#" class="button">' + Translate._('restore') + '</a>');
			cancelButton.bind('mouseup',{title:title}, function(event) {
				var heading = $('<div id="headingcontainer" style="float:left"><h1 class="heading">'+event.data.title+'</h1><div class="clear"></div><p class="meta"><span>' + Translate._('clickOnTitleToEdit') + '</span></p></div>');
				$(heading).bind('mouseup', Article.handleEditArticleTitle);
				$('.editor-wrapper').replaceWith(heading);
				return false;
			});
			container.append('<br/><span class="cancel">' + Translate._('restoreQuestion') + '</span>');
			container.append(cancelButton);
		}
		
		heading.replaceWith(container);
		
		return false;
	}
	
	self.setupArticleEditorGui = function() {
		$('.editor #contentEditor').markItUp(html5WikiMarkItUpSettings);
		$('.editor #txtTags').ptags();
	}
	
	self.setupArticleEditorEvents = function() {
		$("#edit-article").submit(Article.save);
		$('.editor h1.heading').bind('mouseup', Article.handleEditArticleTitle);
	}

	return self;
	
}());  // end Article
