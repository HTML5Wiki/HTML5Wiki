<article id="content" class="content">
	<header>
		<header class="grid_12 title clearfix">
			<h1 class="heading">Diff</h1>
		</header>
	</header>
	<div class="grid_12">
		<pre><?php echo FineDiff_FineDiff::renderDiffToHTMLFromOpcodes($this->leftVersion->content, $this->opcodes); ?></pre>
	</div>
</article>