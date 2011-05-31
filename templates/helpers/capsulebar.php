<?php
	$i = -1;  // item counter
	
	$activeKey = 'read';
	foreach($this->items as $key => $item) {
		$keys = explode(',',$key);
		if(in_array($this->activePage, $keys)) {
			$activeKey = $key;
			break;
		}
	}
?>
<ol class="capsulebar">
	<?php foreach($this->items as $key => $item) : ?>
	<?php $i++; ?>
	<li class="item <?php echo $item['class']; ?><?php if($key === $activeKey) echo ' active'; ?><?php if($i == 0) echo ' first' ?><?php if($i == sizeof($this->items)-1) echo ' last' ?>">
		<a href="<?php echo $item['url']; ?>" class="capsule" accesskey="<?php echo $i+1; ?>" id="capsulebar-<?php echo $key ?>">
			<span class="caption"><?php echo $item['text']; ?></span>
		</a>
	</li>
	<?php endforeach; ?>
</ol>
