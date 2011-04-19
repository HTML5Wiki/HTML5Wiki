<h1 class="heading">Artikel <?php echo $this->permalink ?> existiert nicht!</h1>

<div class="editor">
		<a class="save-button" href="<?php echo $this->request->getBasePath()?>/wiki/create/<?php echo $this->permalink ?>">
			<span class="caption">Artikel erstellen</span>
		</a>
</div>