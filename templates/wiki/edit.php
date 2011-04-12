<article class="content article">
	<header class="title clearfix">
		<h1 class="heading"><input type="text" name="title" value="<?=$this->title?>" /></h1>
		<div id="permlink"><strong>Permalink: </strong><?=$this->permalink?></div>
		<ol class="capsulebar">
			<li class="item first read"><a href="#" class="capsule"><span class="caption">Lesen</span></a></li>
			<li class="item edit active"><a href="#" class="capsule"><span class="caption">Bearbeiten</span></a></li>
			<li class="item last history"><a href="#" class="capsule"><span class="caption">&Auml;nderungsgeschichte</span></a></li>
		</ol>
	</header>
	
	<textarea id="contenteditor"><?=$this->content?></textarea>
	
	<script type="text/javascript" >
	   $(document).ready(function() {
	      $("#contenteditor").markItUp(html5WikiMarkItUpSettings);
	   });
	</script>
	
</article>