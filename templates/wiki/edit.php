<?php
	$this->javascriptHelper()->appendFile($this->urlHelper('js','classes','capsulebar.js'));
	$this->javascriptHelper()->appendFile($this->urlHelper('js','classes','article.js'));
	$this->javascriptHelper()->appendFile($this->urlHelper('js','classes','history.js'));
	$this->javascriptHelper()->appendScript('appendPageReadyCallback("Capsulebar.init", ["' . $this->mediaVersionId . '"]);');
	$this->javascriptHelper()->appendScript('appendPageReadyCallback(Article.setupArticleEditorGui);');
	$this->javascriptHelper()->appendScript('appendPageReadyCallback(Article.setupArticleEditorEvents);');
	if(strlen($this->title) == 0) $this->javascriptHelper()->appendScript('appendPageReadyCallback(function() { Article.handleEditArticleTitle(false); });');

	$saveText = $this->translate->_('save');
	
	/* Intermediate version present? */
	if(isset($this->diff)) {
		$saveText = $this->translate->_('overwrite');
		$text = '<p>'
			  . sprintf($this->translate->_('hasIntermediateVersionText'), $this->otherAuthor)
			  . '<div class="compareversions white-paper">'
			  . $this->diffRendererHelper($this->diff, $this->leftVersionTitle, $this->rightVersionTitle)
			  . '</div>';
		
		$this->messageHelper()->appendQuestionMessage($this->translate->_('compareVersions'), $text);
		$this->messageHelper()->addButton($saveText, true, '$(\'#edit-article\').submit();');
	}
	
	/* Errors present? */
	if (isset($this->errors['messages']) && count($this->errors['messages'])) {
        $msg = "<ul>";
        foreach ($this->errors['messages'] as $errorMessage) {
            $msg .= "<li>" . stripslashes($errorMessage) . "</li>";
		}
        $msg .= "</ul>";

		$this->messageHelper()->appendErrorMessage($this->translate->_('wrongInput'), $msg);
	}

?>
<article id="content" class="content editor">
	<form id="edit-article" name="editArticleForm" action="<?php echo $this->urlHelper('wiki','save',$this->permalink) ?>" method="post">
		<input type="hidden" value="<?php echo $this->mediaVersionId; ?>" id="hiddenIdArticle" name="hiddenIdArticle" />
		<input type="hidden" value="<?php echo $this->mediaVersionTimestamp; ?>" id="hiddenTimestampArticle" name="hiddenTimestampArticle" />
		<?php if(isset($this->diff)) : ?><input type="hidden" value="true" id="hiddenOverwrite" name="hiddenOverwrite" /><?php endif; ?>
		
		<header class="grid_12 title clearfix">
			<?php
				$fieldToSet = isset($this->errors['fields']['title']) ? $this->errors['fields']['title'] : false;
				$setErrorClass = $fieldToSet ? ' error' : '';
			?>
			<div id="headingcontainer" style="float:left">
				<h1 class="heading<?php echo $setErrorClass; ?>"><?php echo strlen($this->title) > 0 ? $this->title : $this->permalink; ?></h1>
				<div class="clear"></div>
				<p class="meta">
					<span class="cancel"><?php echo $this->translate->_('clickOnTitleToEdit'); ?></span>
				</p>
			</div>
			<?php echo isset($this->permalink) ? $this->capsulebarHelper()->render($this->permalink) : ''; ?>
		</header>
		<div class="clear messagemarker"></div>

		<div class="grid_12">
			<fieldset name="content" class="group">
				<?php
					$fieldToSet = isset($this->errors['fields']['content']) ? $this->errors['fields']['content'] : false;
					$setErrorClass = $fieldToSet ? ' error' : '';
				?>
				<legend class="groupname<?php echo $setErrorClass; ?>"><?php echo $this->translate->_("articleContentLegend") ?></legend>
				<textarea class="<?php echo $setErrorClass; ?>" id="contentEditor" name="contentEditor"><?php echo $this->escape($this->content); ?></textarea>
			</fieldset>
		</div>
		<div class="clear"></div>
		
		<div class="grid_4">
			<fieldset name="author" class="group">
				<legend class="groupname"><?php echo $this->translate->_('authorInformationLegend') ?></legend>
                <input type="hidden" value="<?php echo isset($this->author->id) ? $this->author->id : 0; ?>" id="hiddenAuthorId" name="hiddenAuthorId" />
				<p>
                    <?php
                        $fieldToSet = isset($this->errors['fields']['authorName']) ? $this->errors['fields']['authorName'] : false;
                        $setErrorClass = $fieldToSet ? ' error' : '';
                    ?>
					<label for="txtAuthor" class="label<?php echo $setErrorClass; ?>"><?php echo $this->translate->_('authorName') ?></label>
					<input type="text" name="txtAuthor" id="txtAuthor" class="textfield<?php echo $setErrorClass; ?>" value="<?php echo isset($this->author->name) ? $this->escape($this->author->name) : ''; ?>" />
				</p>
				<p>
                    <?php
                        $fieldToSet = isset($this->errors['fields']['authorEmail']) ? $this->errors['fields']['authorEmail'] : false;
                        $setErrorClass = $fieldToSet ? ' error' : '';
                    ?>
					<label for="txtAuthorEmail" class="label<?php echo $setErrorClass; ?>"><?php echo $this->translate->_('authorEmail') ?></label>
					<input type="text" name="txtAuthorEmail" id="txtAuthorEmail" class="textfield<?php echo $setErrorClass; ?>" value="<?php echo isset($this->author->email) ? $this->escape($this->author->email) : ''; ?>" />
				</p>
				<p class="hint">
					<?php echo $this->translate->_('authorInformationText') ?>
				</p>
			</fieldset>
		</div>
		<div class="grid_8">
			<fieldset name="tags" class="group">
				<legend class="groupname"><?php echo $this->translate->_('taggingLegend') ?></legend>
                    <p class="clearfix">
                    <?php
                        $fieldToSet = isset($this->errors['fields']['tags']) ? $this->errors['fields']['tags'] : false;
                        $setErrorClass = $fieldToSet ? ' error' : '';
                    ?>
					<label for="txtTags" class="label<?php echo $setErrorClass; ?>"><?php echo $this->translate->_('tag') ?></label>
					<input type="text" name="txtTags" id="txtTags" value="<?php echo $this->tags ?>" class="textfield<?php echo $setErrorClass; ?>" />
				</p>
				<p class="hint">
					<?php echo $this->translate->_('taggingText') ?>
				</p>
			</fieldset>	
			<fieldset name="versionComment" class="group">
                <?php
                        $fieldToSet = isset($this->errors['fields']['versionComment']) ? $this->errors['fields']['versionComment'] : false;
                        $setErrorClass = $fieldToSet ? ' error' : '';
                    ?>
				<legend class="groupname"><?php echo $this->translate->_('versionCommentLegend') ?></legend>
				<p class="clearfix">
					<label for="versionComment" class="label<?php echo $setErrorClass; ?>"><?php echo $this->translate->_('versionCommentText') ?></label>
					<input type="text" name="versionComment" id="versionComment" class="textfield<?php echo $setErrorClass; ?>" value="<?php echo count($this->errors) ? $this->escape($this->versionComment) : ''; ?>" />
				</p>
			</fieldset>
		</div>
		<div class="clear"></div>
		
		<div class="grid_12 bottom-button-bar">
			<input id="article-save" type="submit" value="<?php echo $saveText ?>" class="caption large-button"/>
		</div>
		<div class="clear"></div>
	</form>
	
	<?php if($this->ajax === true) : ?>
    <script type="text/javascript">
		Capsulebar.initializeClickEvents();
		Article.setupArticleEditorGui();
		Article.setupArticleEditorEvents();
		
		<?php if($this->messageHelper()->hasMessages()) : ?>
		MessageController.addMessages(<?php echo json_encode($this->messageHelper()->getMessages()) ?>);
		<?php endif; ?>
	</script>
	<?php endif; ?>

    <?php if (isset($this->errors['messages']) && count($this->errors['messages'])) : ?>
    <script type="text/javascript">		
		<?php if (isset($this->errors['fields']['title']) && $this->errors['fields']['title']): ?>
			Article.handleEditArticleTitle(false);
			$('#txtTitle').addClass('error');
		<?php endif;?>
    </script>
    <?php endif; ?>
</article>