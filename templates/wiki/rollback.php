<?php
	if (isset($this->errors['messages']) && count($this->errors['messages'])) {
        $msg = '<ul>';
        foreach ($this->errors['messages'] as $errorMessage) {
            $msg .= '<li>' . addslashes($errorMessage) . '</li>';
		}
        $msg .= '</ul>';

		$this->javascriptHelper()->appendScript('appendPageReadyCallback(function() {
		    var options = {
				\'modal\': true,
				\'buttons\' : [{
					\'text\': \'OK\'
					,\'button\': true
				}]
			};
			MessageController.addMessage(\'error\',\''.$msg.'\', options);
		});');
	}

	$toTimestampDate = date($this->translate->_('timestampFormat'), $this->toTimestamp)
?>
<article id="content" class="content compareversions">
	<header class="grid_12 title clearfix">
		<h1 class="heading"><?php echo $this->title ?></h1>
		<?php echo $this->capsulebarHelper($this->permalink); ?>
	</header>
	<div class="clear"></div>

	<div class="grid_12">
		<h2><?php echo $this->translate->_('rollback') ?></h2>
		<p><?php printf($this->translate->_('rollbackToQuestion'), $toTimestampDate, $this->title) ?></p>
	</div>
	<div class="clear"></div>
	
	<div class="grid_12 bottom-button-bar">
		<form action="<?php echo $this->urlHelper('wiki', 'rollback', $this->permalink, '?to=' . $this->toTimestamp); ?>" method="post">
			<fieldset name="author" class="group">
				<legend class="groupname">Autoreninformation</legend>
                <input type="hidden" value="<?php echo isset($this->author->id) ? $this->author->id : 0; ?>" id="hiddenAuthorId" name="hiddenAuthorId" />
				<p>
                    <?php
                        $fieldToSet = isset($this->errors['fields']['txtAuthor']) ? $this->errors['fields']['txtAuthor'] : false;
                        $setErrorClass = $fieldToSet ? ' error' : '';
                    ?>
					<label for="txtAuthor" class="label<?php echo $setErrorClass; ?>">Ihr Name</label>
					<input type="text" name="txtAuthor" id="txtAuthor" class="textfield<?php echo $setErrorClass; ?>" value="<?php echo isset($this->author->name) ? $this->author->name : ''; ?>" />
				</p>
				<p>
                    <?php
                        $fieldToSet = isset($this->errors['fields']['txtAuthorEmail']) ? $this->errors['fields']['txtAuthorEmail'] : false;
                        $setErrorClass = $fieldToSet ? ' error' : '';
                    ?>
					<label for="txtAuthorEmail" class="label<?php echo $setErrorClass; ?>">Ihre E-Mailadresse</label>
					<input type="text" name="txtAuthorEmail" id="txtAuthorEmail" class="textfield<?php echo $setErrorClass; ?>" value="<?php echo isset($this->author->email) ? $this->author->email : ''; ?>" />
				</p>
				<p class="hint">
					Ihr <em>Name</em> sowie Ihre <em>E-Mailadresse</em> werden
					nur zur internen Identifikation resp. Versionskontrolle
					abgelegt.<br/>
					Ihre Daten werden weder weitergegeben noch anderweitig ausgewertet.
				</p>
			</fieldset>

			<fieldset name="versionComment" class="group">
                <?php
                        $fieldToSet = isset($this->errors['fields']['txtVersionComment']) ? $this->errors['fields']['txtVersionComment'] : false;
                        $setErrorClass = $fieldToSet ? ' error' : '';
                    ?>
				<legend class="groupname">Versionskommentar</legend>
				<p class="clearfix">
					<label for="txtVersionComment" class="label<?php echo $setErrorClass; ?>">Kommentar zur Version <em>(optional)</em>:</label>
					<input type="text" name="txtVersionComment" id="txtVersionComment" class="textfield<?php echo $setErrorClass; ?>" value="<?php echo count($this->errors) ? $this->versionComment : ''; ?>" />
				</p>
			</fieldset>
			
			<input id="rollback" name="rollback" type="submit" value="<?php echo $this->translate->_('yesRollback') ?>" class="caption large-button"/>
		</form>
	</div>
	<div class="clear"></div>
</article>