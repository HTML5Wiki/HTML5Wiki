<?php
	/* Initialize array with common capsulebar items: */
	$i = -1;  // item counter
	$items = array(
		'read' => array(
			'url' => $this->urlHelper('wiki/' . $this->permalink)
			,'name' => $this->translate->_('read')
			,'cssClass' => 'read'
			,'active' => true
		)
		,'edit' => array(
			'url' => $this->urlHelper('wiki/edit/' . $this->permalink)
			,'name' => $this->translate->_('edit')
			,'cssClass' => 'edit'
			,'active' => false
		)
		,'history' => array(
			'url' => $this->urlHelper('wiki/history/' . $this->permalink)
			,'name' => $this->translate->_('history')
			,'cssClass' => 'history'
			,'active' => false
		)
	);
	
	/* Look for the current item: */
	foreach($items as $key => $item) {
		if($key === $this->activePage) {
			$items[$key]['active'] = true;
			if($key !== 'read') $items['read']['active'] = false;
			break;
		}
	}


?>
<ol class="capsulebar">
	<?php foreach($items as $item) : ?>
	<?php $i++; ?>
	<li class="item <?php echo $item['cssClass']; ?><? if($item['active'] === true) echo ' active'; ?><? if($i == 0) echo ' first' ?><? if($i == sizeof($items)-1) echo ' last' ?>">
		<a href="<?php echo $item['url']; ?>" class="capsule" id="capsulebar-read">
			<span class="caption"><?php echo $item['name']; ?></span>
		</a>
	</li>
	<?php endforeach; ?>
</ol>