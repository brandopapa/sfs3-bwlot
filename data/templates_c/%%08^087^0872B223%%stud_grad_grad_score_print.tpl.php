<?php /* Smarty version 2.6.26, created on 2015-05-20 17:34:06
         compiled from stud_grad_grad_score_print.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'stud_grad_grad_score_print.tpl', 12, false),)), $this); ?>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=big5">
<title><?php if ($_POST['years'] == 5): ?>五學期<?php else: ?>畢業<?php endif; ?>成績表</title>
</head>

<body>
<?php $_from = $this->_tpl_vars['student_sn']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['ss'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['ss']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['site_num'] => $this->_tpl_vars['sn']):
        $this->_foreach['ss']['iteration']++;
?>
<?php if ($this->_foreach['ss']['iteration'] % 4 == 1): ?>
<table border="0" cellspacing="0" cellpadding="0" width="610" <?php if (( $this->_foreach['ss']['iteration']+4 ) < $this->_tpl_vars['stud_num']): ?>style="page-break-after: always"<?php endif; ?>>
<tr align="right">
<td colspan="<?php echo $this->_tpl_vars['ss_num']+5; ?>
"><b><?php echo $this->_tpl_vars['class_base'][$this->_tpl_vars['seme_class']]; ?>
 <?php if ($_POST['years'] == 5): ?>五學期<?php else: ?>畢業<?php endif; ?>成績表　　　　　 <font size="1" style="font-size: 10pt">列印日期：<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp) : smarty_modifier_date_format($_tmp)); ?>
</font></b></td>
</tr>
<tr align="center">
<td style="border-style:solid; border-width:1.5pt 0.75pt 1.5pt 1.5pt;"><font size="1" style="font-size: 8pt">座號</font></td>
<td style="border-style:solid; border-width:1.5pt 0.75pt 1.5pt 0pt;"><font size="1" style="font-size: 8pt">學號</font></td>
<td style="border-style:solid; border-width:1.5pt 0.75pt 1.5pt 0pt;"><font size="1" style="font-size: 8pt">姓名</font></td>
<td style="border-style:solid; border-width:1.5pt 0.75pt 1.5pt 0pt;"><font size="1" style="font-size: 8pt">學習領域</font></td>
<?php $_from = $this->_tpl_vars['show_year']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['j'] => $this->_tpl_vars['i']):
?>
<td style="border-style:solid; border-width:1.5pt 0.75pt 1.5pt 0pt;"><font size="1" style="font-size: 8pt"><?php echo $this->_tpl_vars['i']; ?>
<?php if ($this->_tpl_vars['jos'] != 0): ?>學年度<br>第<?php endif; ?><?php if ($this->_tpl_vars['jos'] != 0): ?><?php echo $this->_tpl_vars['show_seme'][$this->_tpl_vars['j']]; ?>
學期<?php else: ?><?php if ($this->_tpl_vars['show_seme'][$this->_tpl_vars['j']] == 1): ?>上<?php else: ?>下<?php endif; ?><?php endif; ?></font></td>
<?php endforeach; endif; unset($_from); ?>
<td style="border-style:solid; border-width:1.5pt 0.75pt 1.5pt 0.75pt;"><font size="1" style="font-size: 8pt">各領域<br>平均</font></td>
<td style="border-style:solid; border-width:1.5pt 1.5pt 1.5pt 0.75pt;"><font size="1" style="font-size: 8pt">總平均</font></td>
</tr>
<?php endif; ?>
<?php $_from = $this->_tpl_vars['ss_link']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['ss_link'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['ss_link']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['sl']):
        $this->_foreach['ss_link']['iteration']++;
?>
<tr align="center">
<?php if ($this->_foreach['ss_link']['iteration'] == 1): ?>
<td <?php if ($this->_tpl_vars['ss_num'] > 1): ?>rowspan="<?php echo $this->_tpl_vars['ss_num']+1; ?>
"<?php endif; ?> style="border-style:solid; border-width:0pt 0.75pt 1.5pt 1.5pt;"><?php echo $this->_tpl_vars['site_num']; ?>
</td>
<td <?php if ($this->_tpl_vars['ss_num'] > 1): ?>rowspan="<?php echo $this->_tpl_vars['ss_num']+1; ?>
"<?php endif; ?> style="border-style:solid; border-width:0pt 0.75pt 1.5pt 0pt;"><?php echo $this->_tpl_vars['stud_id'][$this->_tpl_vars['site_num']]; ?>
</td>
<td <?php if ($this->_tpl_vars['ss_num'] > 1): ?>rowspan="<?php echo $this->_tpl_vars['ss_num']+1; ?>
"<?php endif; ?> style="border-style:solid; border-width:0pt 0.75pt 1.5pt 0pt;"><?php echo $this->_tpl_vars['stud_name'][$this->_tpl_vars['site_num']]; ?>
</td>
<?php endif; ?>
<td align="left" style="border-style:solid; border-width:0pt 0.75pt 0.75pt 0pt;"><font size="1" style="font-size: 8pt">&nbsp;<?php echo $this->_tpl_vars['link_ss'][$this->_tpl_vars['sl']]; ?>
</font></td>
<?php $_from = $this->_tpl_vars['semes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sj'] => $this->_tpl_vars['si']):
?>
<td style="border-style:solid; border-width:0pt 0.75pt 0.75pt 0pt;"><?php if ($this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']][$this->_tpl_vars['sl']][$this->_tpl_vars['si']]['score'] == ""): ?>---<?php else: ?><?php echo $this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']][$this->_tpl_vars['sl']][$this->_tpl_vars['si']]['score']; ?>
<?php endif; ?></td>
<?php endforeach; endif; unset($_from); ?>
<?php if ($this->_tpl_vars['sl'] != 'local' && $this->_tpl_vars['sl'] != 'english'): ?>
<td <?php if ($this->_tpl_vars['sl'] == 'chinese'): ?>rowspan="3"<?php endif; ?> style="border-style:solid; border-width:0pt 0.75pt 0.75pt 0.75pt;"><?php if ($this->_tpl_vars['sl'] == 'chinese'): ?><?php echo $this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']]['language']['avg']['score']; ?>
<?php else: ?><?php if ($this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']][$this->_tpl_vars['sl']]['avg']['score'] == ""): ?>---<?php else: ?><?php echo $this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']][$this->_tpl_vars['sl']]['avg']['score']; ?>
<?php endif; ?><?php endif; ?></td>
<?php endif; ?>
<?php if ($this->_tpl_vars['sl'] == 'chinese'): ?><td rowspan="<?php echo $this->_tpl_vars['ss_num']; ?>
" style="border-style:solid; border-width:0pt 1.5pt 0.75pt 0.75pt;"><?php echo $this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']]['avg']['score']; ?>
<br>(<?php echo $this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']]['avg']['str']; ?>
)</td><?php endif; ?>
<?php if ($this->_tpl_vars['ss_num'] == 1 && ( $this->_tpl_vars['sl'] == 'basic' || $this->_tpl_vars['sl'] == 'live' || $this->_tpl_vars['sl'] == 'mylife' )): ?><td style="border-style:solid; border-width:0pt 1.5pt 0.75pt 0.75pt;"><?php echo $this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']]['avg']['score']; ?>
<br>(<?php echo $this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']]['avg']['str']; ?>
)</td><?php endif; ?>
</tr>
<?php endforeach; endif; unset($_from); ?>
<?php if ($this->_tpl_vars['ss_num'] > 1): ?>
<tr align="center">
<td align="left" style="border-style:solid; border-width:0pt 0.75pt 1.5pt 0pt;"><font size="1" style="font-size: 8pt">&nbsp;平常生活表現</font></td>
<?php $_from = $this->_tpl_vars['semes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sj'] => $this->_tpl_vars['si']):
?>
<td style="border-style:solid; border-width:0pt 0.75pt 1.5pt 0pt;"><?php if ($this->_tpl_vars['fin_nor_score'][$this->_tpl_vars['sn']][$this->_tpl_vars['si']]['score'] == ""): ?>---<?php else: ?><?php echo $this->_tpl_vars['fin_nor_score'][$this->_tpl_vars['sn']][$this->_tpl_vars['si']]['score']; ?>
<?php endif; ?></td>
<?php endforeach; endif; unset($_from); ?>
<td style="border-style:solid; border-width:0pt 0.75pt 1.5pt 0.75pt;"><?php if ($this->_tpl_vars['fin_nor_score'][$this->_tpl_vars['sn']]['avg']['score'] == ""): ?>---<?php else: ?><?php echo $this->_tpl_vars['fin_nor_score'][$this->_tpl_vars['sn']]['avg']['score']; ?>
<?php endif; ?></td>
<td style="border-style:solid; border-width:0pt 1.5pt 1.5pt 0.75pt;">---</td>
</tr>
<?php endif; ?>
<?php if ($this->_foreach['ss']['iteration'] % 4 == 0 || $this->_foreach['ss_link']['iteration'] == $this->_tpl_vars['stud_num']): ?>
</table>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</body>
</html>