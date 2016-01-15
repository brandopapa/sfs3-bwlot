<?php /* Smarty version 2.6.26, created on 2016-01-15 15:29:30
         compiled from /home/sfs6/dev3.sfs3.bwsh.kindn.es/templates/new/menu.tpl */ ?>
<table cellspacing=1 cellpadding=3><tr>
<?php $_from = $this->_tpl_vars['SFS_MENU']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
<?php if ($this->_tpl_vars['CURR_SCRIPT'] == $this->_tpl_vars['key']): ?><td class='tab' bgcolor='#FFF158'>&nbsp;<a href="<?php echo $this->_tpl_vars['key']; ?>
<?php if ($this->_tpl_vars['SFS_MENU_LINK']): ?>?<?php echo $this->_tpl_vars['SFS_MENU_LINK']; ?>
<?php endif; ?>"><?php echo $this->_tpl_vars['item']; ?>
</a>&nbsp;</td>
<?php else: ?><td class='tab' bgcolor='#EFEFEF'>&nbsp;<a href="<?php echo $this->_tpl_vars['key']; ?>
<?php if ($this->_tpl_vars['SFS_MENU_LINK']): ?>?<?php echo $this->_tpl_vars['SFS_MENU_LINK']; ?>
<?php endif; ?>"><?php echo $this->_tpl_vars['item']; ?>
</a>&nbsp;</td><?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</tr></table>