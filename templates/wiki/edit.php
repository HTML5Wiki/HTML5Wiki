<?php
	$basePath = $this->basePath . '/';
	$this->javascriptHelper()->appendFile($basePath . 'js/classes/capsulebar.js');
	$this->javascriptHelper()->appendFile($basePath . 'js/classes/article.js');
	$this->javascriptHelper()->appendScript('appendPageReadyCallback("Capsulebar.init", ["' . $this->wikiPage->id . '"]);');
	$this->javascriptHelper()->appendScript('appendPageReadyCallback(Article.setupArticleEditorGui);');
?>
<article id="content" class="content editor">
	<form id="edit-article" name="editArticleForm" action="<?php echo $this->request->getBasePath()?>/wiki/save/<?php echo $this->wikiPage->permalink ?>" method="post">
		<input type="hidden" value="<?php echo $this->wikiPage->id; ?>" id="hiddenIdArticle" name="hiddenIdArticle" />
        <input type="hidden" value="<?php echo $this->wikiPage->timestamp; ?>" id="hiddenTimestampArticle" name="hiddenTimestampArticle" />
        <header class="grid_12 title clearfix">
            <?php
                $fieldToSet = isset($this->error['fields']['title']) ? $this->error['fields']['title'] : false;
                $setErrorClass = $fieldToSet ? ' error' : '';
            ?>
			<h1 class="heading<?php echo $setErrorClass; ?>"><?php echo strlen($this->title) > 0 ? $this->title : $this->translate->_('noTitle'); ?></h1>
			<?php echo $this->capsulebarHelper($this->wikiPage->permalink); ?>
		</header>
		<div class="clear"></div>

		<div class="grid_12">
			<fieldset name="content" class="group">
                <?php
                    $fieldToSet = isset($this->error['fields']['content']) ? $this->error['fields']['content'] : false;
                    $setErrorClass = $fieldToSet ? ' error' : '';
                ?>
				<legend class="groupname<?php echo $setErrorClass; ?>"><?php echo $this->translate->_("articleContentLegend") ?></legend>
				<textarea class="<?php echo $setErrorClass; ?>" id="contentEditor" name="contentEditor"><?php echo $this->content; ?></textarea>
			</fieldset>
		</div>
		<div class="clear"></div>
		
		<div class="grid_4">
			<fieldset name="author" class="group">
				<legend class="groupname">Autoreninformation</legend>
                <input type="hidden" value="<?php echo isset($this->author->id) ? $this->author->id : 0; ?>" id="hiddenAuthorId" name="hiddenAuthorId" />
				<p>
                    <?php
                        $fieldToSet = isset($this->error['fields']['author']) ? $this->error['fields']['author'] : false;
                        $setErrorClass = $fieldToSet ? ' error' : '';
                    ?>
					<label for="txtAuthor" class="label<?php echo $setErrorClass; ?>">Ihr Name</label>
					<input type="text" name="txtAuthor" id="txtAuthor" class="textfield<?php echo $setErrorClass; ?>" value="<?php echo isset($this->author->name) ? $this->author->name : ''; ?>" />
				</p>
				<p>
                    <?php
                        $fieldToSet = isset($this->error['fields']['authorEmail']) ? $this->error['fields']['authorEmail'] : false;
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
		</div>
		<div class="grid_8">
			<fieldset name="tags" class="group">
				<legend class="groupname">Tagging</legend>
                    <p class="clearfix">
                    <?php
                        $fieldToSet = isset($this->error['fields']['tags']) ? $this->error['fields']['tags'] : false;
                        $setErrorClass = $fieldToSet ? ' error' : '';
                    ?>
					<label for="txtTags" class="label<?php echo $setErrorClass; ?>">Tag</label>
					<input type="text" name="txtTags" id="txtTags" value="<?php echo implode(",", $this->tags); ?>" class="textfield<?php echo $setErrorClass; ?>" />
				</p>
				<p class="hint">
					Ein Artikel kann mit verschiedenen Tags versehen werden,
					welche sp&auml;ter das Auffinden &uuml;ber die Suche
					erleichtern k&ouml;nnen.<br/>
					<em>Tipp:</em> Geben Sie mehrere Tags auf einmal getrennt
					durch ein Komma ein und bestätigen Sie mit der
					<em>Eingabetaste</em>.
				</p>
			</fieldset>	
			<fieldset name="versionComment" class="group">
                <?php
                        $fieldToSet = isset($this->error['fields']['versionComment']) ? $this->error['fields']['versionComment'] : false;
                        $setErrorClass = $fieldToSet ? ' error' : '';
                    ?>
				<legend class="groupname">Versionskommentar</legend>
				<p class="clearfix">
					<label for="versionComment" class="label<?php echo $setErrorClass; ?>">Kommentar zur Version <em>(optional)</em>:</label>
					<input type="text" name="versionComment" id="versionComment" class="textfield<?php echo $setErrorClass; ?>" value="<?php echo $this->versionComment; ?>" />
				</p>
			</fieldset>
		</div>
		<div class="clear"></div>
		
		<div class="grid_12 bottom-button-bar">
			<input id="article-save" type="submit" value="Speichern" class="caption large-button save-button"/>
			<a href="#" class="link-button cancel-button">&Auml;nderungen verwerfen</a>
		</div>
		<div class="clear"></div>
	</form>
	<?php if ($this->ajax === true): ?>
	<script type="text/javascript">
		Capsulebar.initializeClickEvents();
		Article.bindEditorEvents();
		$("#edit-article").submit(Article.save.bind());
	</script>
	<?php endif; ?>

    <?php
    if (isset($this->error['messages'])):
        if (count($this->error['messages'])):
    ?>
    <script type="text/javascript">
        <?php
            $msg = "<ul>";
            foreach ($this->error['messages'] as $errorMessage) {
                $msg .= "<li>" . addslashes($errorMessage) . "</li>";
            }
            $msg .= "</ul>";
        ?>
        var options = [
				{
					text : 'Ok',
					button : true
				}
			];
			MessageController.addMessage('question','<?php echo $msg; ?>', options);
    </script>
    <?php
        endif;
    endif;
    ?>

</article>