<?php /* Smarty version 2.6.26, created on 2015-05-20 17:26:13
         compiled from /home/sfs6/dev3.sfs3.bwsh.kindn.es/templates/new/header.tpl */ ?>
<?php 
//$Id: header.tpl 5959 2010-06-14 23:40:50Z hami $
// 額外的 javascript 加入
global $injectJavascript;
$injectJavascript = ($this->_smarty_vars['capture']['injectJavascript'])?$this->_smarty_vars['capture']['injectJavascript']: '';
//表頭
head($this->get_template_vars("module_name"));
 ?>