<?php /* Smarty version 2.6.0, created on 2011-05-01 21:35:45
         compiled from classleft.tpl */ ?>
<?php if (count($_from = (array)$this->_tpl_vars['classleftindex'])):
    foreach ($_from as $this->_tpl_vars['subpackage'] => $this->_tpl_vars['files']):
?>
  <ul>
	<?php if ($this->_tpl_vars['subpackage'] != ""): ?><li><?php echo $this->_tpl_vars['subpackage'];  endif; ?>
	<?php if (isset($this->_sections['files'])) unset($this->_sections['files']);
$this->_sections['files']['name'] = 'files';
$this->_sections['files']['loop'] = is_array($_loop=$this->_tpl_vars['files']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['files']['show'] = true;
$this->_sections['files']['max'] = $this->_sections['files']['loop'];
$this->_sections['files']['step'] = 1;
$this->_sections['files']['start'] = $this->_sections['files']['step'] > 0 ? 0 : $this->_sections['files']['loop']-1;
if ($this->_sections['files']['show']) {
    $this->_sections['files']['total'] = $this->_sections['files']['loop'];
    if ($this->_sections['files']['total'] == 0)
        $this->_sections['files']['show'] = false;
} else
    $this->_sections['files']['total'] = 0;
if ($this->_sections['files']['show']):

            for ($this->_sections['files']['index'] = $this->_sections['files']['start'], $this->_sections['files']['iteration'] = 1;
                 $this->_sections['files']['iteration'] <= $this->_sections['files']['total'];
                 $this->_sections['files']['index'] += $this->_sections['files']['step'], $this->_sections['files']['iteration']++):
$this->_sections['files']['rownum'] = $this->_sections['files']['iteration'];
$this->_sections['files']['index_prev'] = $this->_sections['files']['index'] - $this->_sections['files']['step'];
$this->_sections['files']['index_next'] = $this->_sections['files']['index'] + $this->_sections['files']['step'];
$this->_sections['files']['first']      = ($this->_sections['files']['iteration'] == 1);
$this->_sections['files']['last']       = ($this->_sections['files']['iteration'] == $this->_sections['files']['total']);
?>
    <?php if ($this->_tpl_vars['subpackage'] != ""): ?><ul><?php endif; ?>
		<?php if ($this->_tpl_vars['files'][$this->_sections['files']['index']]['link'] != ''): ?><li><a href="<?php echo $this->_tpl_vars['files'][$this->_sections['files']['index']]['link']; ?>
"><?php endif;  echo $this->_tpl_vars['files'][$this->_sections['files']['index']]['title'];  if ($this->_tpl_vars['files'][$this->_sections['files']['index']]['link'] != ''): ?></a></li><?php endif; ?>
    <?php if ($this->_tpl_vars['subpackage'] != ""): ?></ul></li><?php endif; ?>
	<?php endfor; endif; ?>
  </ul>
<?php endforeach; unset($_from); endif; ?>