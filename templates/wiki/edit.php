<?php
	$basePath = $this->basePath . '/';
	$this->javascriptHelper()->appendFile($basePath . 'js/classes/capsulebar.js');
	$this->javascriptHelper()->appendFile($basePath . 'js/classes/article.js');
	$this->javascriptHelper()->appendFile($basePath . 'js/classes/history.js');
	$this->javascriptHelper()->appendScript('appendPageReadyCallback("Capsulebar.init", ["' . $this->mediaVersionId . '"]);');
	$this->javascriptHelper()->appendScript('appendPageReadyCallback(Article.setupArticleEditorGui);');
	$this->javascriptHelper()->appendScript('appendPageReadyCallback(Article.setupArticleEditorEvents);');
	if(strlen($this->title) == 0) $this->javascriptHelper()->appendScript('appendPageReadyCallback(Article.handleEditArticleTitle);');

	$saveText = $this->translate->_('save');
	$deleteText = $this->translate->_('deleteArticle');
?>
<article id="content" class="content editor">
	<form id="edit-article" name="editArticleForm" action="<?php echo $this->request->getBasePath()?>/wiki/save/<?php echo $this->permalink ?>" method="post">
		<input type="hidden" value="<?php echo $this->mediaVersionId; ?>" id="hiddenIdArticle" name="hiddenIdArticle" />
		<input type="hidden" value="<?php echo $this->mediaVersionTimestamp; ?>" id="hiddenTimestampArticle" name="hiddenTimestampArticle" />
		<header class="grid_12 title clearfix">
			<?php
				$fieldToSet = isset($this->errors['fields']['title']) ? $this->errors['fields']['title'] : false;
				$setErrorClass = $fieldToSet ? ' error' : '';
			?>
			<h1 class="heading<?php echo $setErrorClass; ?>"><?php echo strlen($this->title) > 0 ? $this->title : $this->permalink; ?></h1>
			<?php echo isset($this->permalink) ? $this->capsulebarHelper($this->permalink) : ''; ?>
		</header>
		<div class="clear"></div>

		<?php if (isset($this->diff)) : ?>
		<?php
			$saveText = $this->translate->_('overwrite');
			$deleteText = $this->translate->_('rejectChanges');
		?>
		<input type="hidden" value="true" id="hiddenOverwrite" name="hiddenOverwrite" />
		<div class="grid_12 compareversions">
			<fieldset name="author" class="group">
				<legend class="groupname"><?php echo $this->translate->_('compareVersions') ?></legend>
				<p class="hint">
					<?php echo $this->translate->_('hasIntermediateVersionText'); ?>
				</p>
				<?php echo $this->diffRendererHelper($this->diff, $this->leftTimestamp, $this->rightTimestamp) ?>
				<input id="article-save" type="submit" value="<?php echo $saveText ?>" class="caption large-button"/>
				<a href="#" class="link-button delete-button"><?php echo $deleteText ?></a>
			</fieldset>
		</div>
		<div class="clear"></div>
		<?php endif; ?>

		<div class="grid_12">
			<fieldset name="content" class="group">
				<?php
					$fieldToSet = isset($this->errors['fields']['content']) ? $this->errors['fields']['content'] : false;
					$setErrorClass = $fieldToSet ? ' error' : '';
				?>
				<legend class="groupname<?php echo $setErrorClass; ?>"><?php echo $this->translate->_("articleContentLegend") ?></legend>
				<textarea class="<?php echo $setErrorClass; ?>" id="contentEditor" name="contentEditor"><?php echo $this->content; ?></textarea>
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
					<input type="text" name="txtAuthor" id="txtAuthor" class="textfield<?php echo $setErrorClass; ?>" value="<?php echo isset($this->author->name) ? $this->author->name : ''; ?>" />
				</p>
				<p>
                    <?php
                        $fieldToSet = isset($this->errors['fields']['authorEmail']) ? $this->errors['fields']['authorEmail'] : false;
                        $setErrorClass = $fieldToSet ? ' error' : '';
                    ?>
					<label for="txtAuthorEmail" class="label<?php echo $setErrorClass; ?>"><?php echo $this->translate->_('authorEmail') ?></label>
					<input type="text" name="txtAuthorEmail" id="txtAuthorEmail" class="textfield<?php echo $setErrorClass; ?>" value="<?php echo isset($this->author->email) ? $this->author->email : ''; ?>" />
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
					<input type="text" name="versionComment" id="versionComment" class="textfield<?php echo $setErrorClass; ?>" value="<?php echo count($this->errors) ? $this->versionComment : ''; ?>" />
				</p>
			</fieldset>
		</div>
		<div class="clear"></div>
		
		<div class="grid_12 bottom-button-bar">
			<input id="article-save" type="submit" value="<?php echo $saveText ?>" class="caption large-button"/>
			<a href="#" class="link-button delete-button"><?php echo $deleteText ?></a>
		</div>
		<div class="clear"></div>
	</form>
	
	<?php if($this->ajax === true) : ?>
    <script type="text/javascript">
		Capsulebar.initializeClickEvents();
		Article.setupArticleEditorGui();
		Article.setupArticleEditorEvents();
	</script>
	<?php endif; ?>

    <?php if (isset($this->errors['messages']) && count($this->errors['messages'])) : ?>
    <script type="text/javascript">
        <?php
            $msg = "<ul>";
            foreach ($this->errors['messages'] as $errorMessage):
                $msg .= "<li>" . addslashes($errorMessage) . "</li>";
            endforeach;
            $msg .= "</ul>";
        ?>
        var options = {
			'modal': true,
			'buttons' : [{
				'text': 'OK'
				,'button': true
			}]
		};
		MessageController.addMessage('question','<?php echo $msg; ?>', options);
    </script>
    <?php endif; ?>
</article>