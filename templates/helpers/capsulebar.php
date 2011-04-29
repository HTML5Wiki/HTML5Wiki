<ol class="capsulebar">
	<li class="item first<?php echo (($this->activePage !== 'edit' && $this->activePage !== 'history') ? ' active' : ''); ?> read">
		<a href="<?php echo $this->urlHelper('wiki/' . $this->permalink) ?>" class="capsule" id="capsulebar-read">
			<span class="caption"><?php echo $this->translate->_('read') ?></span>
		</a>
	</li>
	<li class="item edit<?php echo ($this->activePage === 'edit' ? ' active' : '') ?>">
		<a href="<?php echo $this->urlHelper('wiki/edit/' . $this->permalink) ?>" class="capsule" id="capsulebar-edit">
			<span class="caption"><?php echo $this->translate->_('edit') ?></span>
		</a>
	</li>
	<li class="item last<?php echo ($this->activePage === 'history' ? ' active' : '') ?> history">
		<a href="<?php echo $this->urlHelper('wiki/history/' . $this->permalink) ?>" class="capsule" id="capsulebar-history">
			<span class="caption"><?php echo $this->translate->_('history') ?></span>
		</a>
	</li>
</ol>