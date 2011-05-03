<?php
	$basePath = $this->basePath . '/';
	$this->javascriptHelper()->appendFile($basePath . 'js/Capsulebar.js');
	$this->javascriptHelper()->appendScript('Capsulebar.init("' . $this->wikiPage->id . '");');
	$this->javascriptHelper()->appendScript('$("#edit-article").submit(Article.save.bind());');
?>
<article id="content" class="content editor">
	<form id="edit-article" name="editArticleForm" action="<?php echo $this->request->getBasePath()?>/wiki/save/<?php echo $this->wikiPage->permalink ?>" method="post">
		<input type="hidden" value="<?php echo $this->wikiPage->id; ?>" id="hiddenIdArticle" name="hiddenIdArticle" />
        <input type="hidden" value="<?php echo $this->wikiPage->timestamp; ?>" id="hiddenTimestampArticle" name="hiddenTimestampArticle" />
        <header class="grid_12 title clearfix">
			<h1 class="heading"><?php echo $this->title; ?></h1>
			<?php echo $this->capsulebarHelper($this->wikiPage->permalink); ?>
		</header>
		<div class="clear"></div>

		<div class="grid_12">
			<fieldset name="content" class="group">
				<legend class="groupname"><?php echo $this->translate->_("articleContentLegend") ?></legend>					
				<textarea id="contentEditor" name="contentEditor"><?php echo $this->content; ?></textarea>
			</fieldset>
		</div>
		<div class="clear"></div>
		
		<div class="grid_4">
			<fieldset name="author" class="group">
				<legend class="groupname">Autoreninformation</legend>
                <input type="hidden" value="<?php echo isset($this->author->id) ? $this->author->id : 0; ?>" id="hiddenAuthorId" name="hiddenAuthorId" />
				<p>
					<label for="txtAuthor" class="label">Ihr Name</label>
					<input type="text" name="txtAuthor" id="txtAuthor" class="textfield" value="<?php echo isset($this->author->name) ? $this->author->name : ''; ?>" />
				</p>
				<p>
					<label for="txtAuthorEmail" class="label">Ihre E-Mailadresse</label>
					<input type="text" name="txtAuthorEmail" id="txtAuthorEmail" class="textfield" value="<?php echo isset($this->author->email) ? $this->author->email : ''; ?>" />
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
					<label for="txtTags" class="label">Tag</label>
					<input type="text" name="txtTags" id="txtTags" value="<?php echo $this->tag; ?>" class="textfield" />
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
		$('#edit-article').submit(Article.save.bind());
	</script>
	<?php endif; ?>
</article>