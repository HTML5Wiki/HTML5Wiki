<?php
    $basePath = $this->basePath . '/';
	$this->javascriptHelper()->appendFile($basePath . 'js/classes/capsulebar.js');
	$this->javascriptHelper()->appendFile($basePath . 'js/classes/article.js');
	$this->javascriptHelper()->appendScript('$("#create-article").submit(Article.create.bind());');
?>

<div id="content" class="content">
	<h1 class="heading">
		Artikel <?php echo $this->permalink ?> existiert noch nicht!
	</h1>
	
	<p>Wollen Sie diesen Artikel erstellen?</p>

	<div class="grid_4">
		<form id="create-article" name="createArticleForm" method="post" action="<?php echo $this->request->getBasePath()?>/wiki/create/<?php echo $this->permalink ?>">
				
			<fieldset name="author" class="group">
				<legend class="groupname">Autoreninformation</legend>
				<input type="hidden" value="<?php echo isset($this->author->id) ? $this->author->id : ''; ?>" id="hiddenAuthorId" name="hiddenAuthorId" />
				<p>
					<label for="txtAuthor" class="label">Ihr Name*</label>
					<input type="text" name="txtAuthor" id="txtAuthor" class="textfield" required value="<?php echo isset($this->author->name) ? $this->author->name : ''; ?>" />
				</p>
				<p>
					<label for="txtAuthorEmail" class="label">Ihre E-Mailadresse*</label>
					<input type="email" name="txtAuthorEmail" id="txtAuthorEmail" class="textfield" required value="<?php echo isset($this->author->email) ? $this->author->email : ''; ?>" />
				</p>
				<p class="hint">
					Ihr <em>Name</em> sowie Ihre <em>E-Mailadresse</em> werden
					nur zur internen Identifikation resp. Versionskontrolle
					abgelegt.<br/>
					Ihre Daten werden weder weitergegeben noch anderweitig ausgewertet.
				</p>
			</fieldset>
			<div class="bottom-button-bar">
				<input id="article-create" type="submit" value="Artikel erstellen" class="caption large-button save-button"/>
			</div>
		</form>
	</div>
</div>
