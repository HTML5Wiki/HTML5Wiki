<?php
	$i = -1;  // item counter
?>
<ol class="capsulebar">
	<?php foreach($this->items as $key => $item) : ?>
	<?php $i++; ?>
	<li class="item <?php echo $item['class']; ?><?php if($item['active'] === true) echo ' active'; ?><?php if($i == 0) echo ' first' ?><?php if($i == sizeof($this->items)-1) echo ' last' ?>">
		<a href="<?php echo $item['url']; ?>" class="capsule" accesskey="<?php echo $i+1; ?>" id="capsulebar-<?php echo $key ?>">
			<span class="caption"><?php echo $item['text']; ?></span>
		</a>
	</li>
	<?php endforeach; ?>
</ol>
