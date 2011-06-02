<?php
	$this->capsulebarHelper()->addItem(
		'rollback'
		,$this->translate->_('rollback')
		,'rollback'
		,$this->urlHelper('wiki', 'rollback', $this->permalink, '?to='. $this->toTimestamp)
	);

	/* Errors present? */
	if (isset($this->errors['messages']) && count($this->errors['messages'])) {
	    $msg = "<ul>";
	    foreach ($this->errors['messages'] as $errorMessage) {
	        $msg .= "<li>" . stripslashes($errorMessage) . "</li>";
		}
	    $msg .= "</ul>";

		$this->messageHelper()->appendErrorMessage($this->translate->_('wrongInput'), $msg);
	}

	$toTimestampDate = date($this->translate->_('timestampFormat'), $this->toTimestamp)
?>
<article id="content" class="content rollback">
	<header class="grid_12 title clearfix">
		<h1 class="heading"><?php echo $this->title ?></h1>
		<?php echo $this->capsulebarHelper()->render($this->permalink); ?>
	</header>
	<div class="clear messagemarker"></div>

	<div class="grid_12">
		<h2><?php echo $this->translate->_('rollback') ?></h2>
		<p><?php printf($this->translate->_('rollbackToQuestion'), $toTimestampDate, $this->title) ?></p>
	</div>
	<div class="clear"></div>
	
	<div class="grid_12 bottom-button-bar">
		<form action="<?php echo $this->urlHelper('wiki', 'rollback', $this->permalink, '?to=' . $this->toTimestamp); ?>" method="post">
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

			<fieldset name="versionComment" class="group">
                <?php
                        $fieldToSet = isset($this->errors['fields']['txtVersionComment']) ? $this->errors['fields']['txtVersionComment'] : false;
                        $setErrorClass = $fieldToSet ? ' error' : '';
                    ?>
				<legend class="groupname"><?php echo $this->translate->_('versionCommentLegend') ?></legend>
				<p class="clearfix">
					<label for="txtVersionComment" class="label<?php echo $setErrorClass; ?>"><?php echo $this->translate->_('versionCommentText') ?></label>
					<input type="text" name="txtVersionComment" id="txtVersionComment" class="textfield<?php echo $setErrorClass; ?>" value="<?php echo count($this->errors) ? $this->versionComment : ''; ?>" />
				</p>
			</fieldset>
			
			<input id="rollback" name="rollback" type="submit" value="<?php echo $this->translate->_('yesRollback') ?>" class="caption large-button"/>
		</form>
	</div>
	<div class="clear"></div>
</article>