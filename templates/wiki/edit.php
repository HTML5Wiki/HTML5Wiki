<article class="content editor">
	<header class="grid_12 title clearfix">
		<h1 class="heading">Der erste grosse Artikel im Prototyp</h1>
		<ol class="capsulebar">
			<li class="item first read"><a href="#" class="capsule"><span class="caption">Lesen</span></a></li>
			<li class="item edit active"><a href="#" class="capsule"><span class="caption">Bearbeiten</span></a></li>
			<li class="item last history"><a href="#" class="capsule"><span class="caption">&Auml;nderungsgeschichte</span></a></li>
		</ol>
	</header>
<<<<<<< HEAD
	<div class="clear"></div>

	<form action="#" method="post" class>
		<div class="grid_12">
			<fieldset name="content" class="group">
				<legend class="groupname">Artikelinhalt</legend>					
				<textarea id="contentEditor"></textarea>
			</fieldset>
		</div>
		<div class="clear"></div>
		
		<div class="grid_4">
			<fieldset name="author" class="group">
				<legend class="groupname">Autoreninformation</legend>
				<p>
					<label for="txtAuthor" class="label">Ihr Name</label>
					<input type="text" name="txtAuthor" id="txtAuthor" class="textfield" />
				</p>
				<p>
					<label for="txtAuthorEmail" class="label">Ihre E-Mailadresse</label>
					<input type="text" name="txtAuthorEmail" id="txtAuthorEmail" class="textfield" />
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
					<input type="text" name="txtTags" id="txtTags" value="ein,paar,tags,zum,testen" class="textfield" />
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
	</form>
=======
	
	<textarea id="contenteditor"><?=$this->content?></textarea>
	
	<script type="text/javascript" >
		$(document).ready(function() {
			$("#contenteditor").markItUp(html5WikiMarkItUpSettings);
		});
	</script>
>>>>>>> c82a540c80bebc6247cd000d5d6f15d38bffb0b9
	
</article>