<div class="content">
	<h2 class="heading">
		Artikel <?php echo $this->permalink ?> existiert nicht!
	</h2>
	
	<?php if( $this->user == null ) { ?>
		<form>
			<div class="grid_4">
				<fieldset name="author" class="group">
					<legend class="groupname">Autoreninformation</legend>
					<p>
						<label for="txtAuthor" class="label">Ihr Name</label>
						<input type="text" name="txtAuthor" id="txtAuthor" class="textfield" value="<?php echo $this->author; ?>" />
					</p>
					<p>
						<label for="txtAuthorEmail" class="label">Ihre E-Mailadresse</label>
						<input type="text" name="txtAuthorEmail" id="txtAuthorEmail" class="textfield" value="<?php echo $this->authorEmail; ?>" />
					</p>
					<p class="hint">
						Ihr <em>Name</em> sowie Ihre <em>E-Mailadresse</em> werden
						nur zur internen Identifikation resp. Versionskontrolle
						abgelegt.<br/>
						Ihre Daten werden weder weitergegeben noch anderweitig ausgewertet.
					</p>
				</fieldset>
			</div>
		</form>
	<?php } ?>
	
	<div class="grid_12 bottom-button-bar">
			<a class="large-button save-button" href="<?php echo $this->request->getBasePath()?>/wiki/create/<?php echo $this->permalink ?>">
				<span class="caption">Artikel erstellen</span>
			</a>
	</div>
</div>