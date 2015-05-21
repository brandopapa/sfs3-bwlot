<?php /* Smarty version 2.6.26, created on 2015-05-20 17:26:13
         compiled from stud_grad_grad_score.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['SFS_TEMPLATE'])."/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['SFS_TEMPLATE'])."/menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<table border="0" cellspacing="1" cellpadding="2" width="100%" bgcolor="#cccccc">
<tr><td bgcolor='#FFFFFF'>
<form name="menu_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
">
<table width="100%">
<tr>
<td><?php echo $this->_tpl_vars['year_seme_menu']; ?>
 <?php echo $this->_tpl_vars['class_year_menu']; ?>
 <?php if ($_POST['year_seme']): ?><?php echo $this->_tpl_vars['class_name_menu']; ?>
 <?php echo $this->_tpl_vars['score_area_menu']; ?>
 <select name="years" size="1" style="background-color:#FFFFFF;font-size:13px" onchange="this.form.submit()";><?php if ($this->_tpl_vars['jos'] == 6): ?><option value="5" <?php if ($_POST['years'] == 5): ?>selected<?php endif; ?>>五學期</option><option value="6" <?php if ($_POST['years'] == 6): ?>selected<?php endif; ?>>六學期</option><?php else: ?><option value="11" <?php if ($_POST['years'] == 11): ?>selected<?php endif; ?>>十一學期</option><option value="12" <?php if ($_POST['years'] == 12): ?>selected<?php endif; ?>>十二學期</option><?php endif; ?></select><?php endif; ?><?php if ($_POST['me']): ?> 
<input type="checkbox" name="show_rank" <?php echo $this->_tpl_vars['show_rank']; ?>
 font-size:13px onclick="this.form.submit()">顯示加權依據及名次 

<input type="submit" name="friendly_print" value="友善列印"><?php endif; ?><?php if ($_POST['years'] == 6 || $_POST['years'] == 12): ?> <input type="submit" name="grad_score" value="寫入畢業成績"><?php endif; ?></td>
</tr>
<?php if ($_POST['me']): ?>
<tr><td>
<table border="0" cellspacing="1" cellpadding="4" width="100%" bgcolor="#cccccc" class="main_body">
<tr bgcolor="#E1ECFF" align="center">
<td>座號</td>
<td>學號</td>
<td>姓名</td>
<td>學習領域</td>
<?php $_from = $this->_tpl_vars['show_year']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['j'] => $this->_tpl_vars['i']):
?>
<td><?php echo $this->_tpl_vars['i']; ?>
<?php if ($this->_tpl_vars['jos'] != 0): ?>學年度<br>第<?php endif; ?><?php if ($this->_tpl_vars['jos'] != 0): ?><?php echo $this->_tpl_vars['show_seme'][$this->_tpl_vars['j']]; ?>
學期<?php else: ?><?php if ($this->_tpl_vars['show_seme'][$this->_tpl_vars['j']] == 1): ?>上<?php else: ?>下<?php endif; ?><?php endif; ?></td>
<?php endforeach; endif; unset($_from); ?>
<td>各學習領域<br>平均</td>
<td>總平均</td>
</tr>
<?php $_from = $this->_tpl_vars['student_sn']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['ss'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['ss']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['site_num'] => $this->_tpl_vars['sn']):
        $this->_foreach['ss']['iteration']++;
?>
<?php $_from = $this->_tpl_vars['ss_link']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['ss_link'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['ss_link']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['sl']):
        $this->_foreach['ss_link']['iteration']++;
?>
<tr bgcolor="#ddddff" align="center">
<?php if ($this->_foreach['ss_link']['iteration'] == 1): ?>
<td <?php if ($this->_tpl_vars['ss_num'] > 1): ?>rowspan="<?php echo $this->_tpl_vars['ss_num']+1; ?>
"<?php endif; ?>><?php echo $this->_tpl_vars['site_num']; ?>
</td>
<td <?php if ($this->_tpl_vars['ss_num'] > 1): ?>rowspan="<?php echo $this->_tpl_vars['ss_num']+1; ?>
"<?php endif; ?>><?php echo $this->_tpl_vars['stud_id'][$this->_tpl_vars['site_num']]; ?>
</td>
<td <?php if ($this->_tpl_vars['ss_num'] > 1): ?>rowspan="<?php echo $this->_tpl_vars['ss_num']+1; ?>
"<?php endif; ?>><?php echo $this->_tpl_vars['stud_name'][$this->_tpl_vars['site_num']]; ?>
</td>
<?php endif; ?>
<td align="left"><?php echo $this->_tpl_vars['link_ss'][$this->_tpl_vars['sl']]; ?>
</td>
<?php $_from = $this->_tpl_vars['semes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sj'] => $this->_tpl_vars['si']):
?>
<td><?php if ($this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']][$this->_tpl_vars['sl']][$this->_tpl_vars['si']]['score'] < 60): ?><font color="red"><?php endif; ?><?php echo $this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']][$this->_tpl_vars['sl']][$this->_tpl_vars['si']]['score']; ?>
<?php if ($this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']][$this->_tpl_vars['sl']][$this->_tpl_vars['si']]['score'] > 0): ?><?php endif; ?><?php if ($this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']][$this->_tpl_vars['sl']][$this->_tpl_vars['si']]['score'] < 60): ?></font><?php endif; ?></td>
<?php endforeach; endif; unset($_from); ?>
<?php if ($this->_tpl_vars['sl'] != 'local' && $this->_tpl_vars['sl'] != 'english'): ?>
<td <?php if ($this->_tpl_vars['sl'] == 'chinese'): ?>rowspan="3"<?php endif; ?>><?php if ($this->_tpl_vars['sl'] == 'chinese'): ?><?php if ($this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']]['language']['avg']['score'] < 60): ?><font color="red"><?php endif; ?><?php echo $this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']]['language']['avg']['score']; ?>
<?php if ($this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']]['language']['avg']['score'] > 0): ?><?php endif; ?><?php if ($this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']]['language']['avg']['score'] < 60): ?></font><?php endif; ?><?php else: ?><?php if ($this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']][$this->_tpl_vars['sl']]['avg']['score'] < 60): ?><font color="red"><?php endif; ?><?php echo $this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']][$this->_tpl_vars['sl']]['avg']['score']; ?>
<?php if ($this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']][$this->_tpl_vars['sl']]['avg']['score'] < 60): ?></font><?php endif; ?><?php endif; ?></td>
<?php if ($this->_tpl_vars['sl'] == 'chinese'): ?><td rowspan="<?php echo $this->_tpl_vars['ss_num']; ?>
"><?php if ($this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']]['avg']['score'] < 60): ?><font color="red"><?php endif; ?><?php echo $this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']]['avg']['score']; ?>
<br>(<?php echo $this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']]['avg']['str']; ?>
)<?php if ($this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']]['avg']['score'] < 60): ?></font><?php endif; ?><br><BR><BR>(<?php echo $this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']]['total']['score']; ?>
)</td><?php endif; ?>
<?php if ($this->_tpl_vars['ss_num'] == 1 && ( $this->_tpl_vars['sl'] == 'basic' || $this->_tpl_vars['sl'] == 'live' || $this->_tpl_vars['sl'] == 'mylife' )): ?><td><?php if ($this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']]['avg']['score'] < 60): ?><font color="red"><?php endif; ?><?php echo $this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']]['avg']['score']; ?>
<br>(<?php echo $this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']]['avg']['str']; ?>
)<?php if ($this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']]['avg']['score'] < 60): ?></font><?php endif; ?><br><BR><BR>(<?php echo $this->_tpl_vars['fin_score'][$this->_tpl_vars['sn']]['total']['score']; ?>
)</td><?php endif; ?>
<?php endif; ?>
</tr>
<?php endforeach; endif; unset($_from); ?>
<?php if ($this->_tpl_vars['ss_num'] > 1): ?>
<tr bgcolor="#ddddff" align="center">
<td align="left">平常生活表現</td>
<?php $_from = $this->_tpl_vars['semes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sj'] => $this->_tpl_vars['si']):
?>
<td><?php if ($this->_tpl_vars['fin_nor_score'][$this->_tpl_vars['sn']][$this->_tpl_vars['si']]['score'] < 60): ?><font color="red"><?php endif; ?><?php echo $this->_tpl_vars['fin_nor_score'][$this->_tpl_vars['sn']][$this->_tpl_vars['si']]['score']; ?>
<?php if ($this->_tpl_vars['fin_nor_score'][$this->_tpl_vars['sn']][$this->_tpl_vars['si']]['score'] < 60): ?></font><?php endif; ?></td>
<?php endforeach; endif; unset($_from); ?>
<td><?php if ($this->_tpl_vars['fin_nor_score'][$this->_tpl_vars['sn']]['avg']['score'] < 60): ?><font color="red"><?php endif; ?><?php echo $this->_tpl_vars['fin_nor_score'][$this->_tpl_vars['sn']]['avg']['score']; ?>
<?php if ($this->_tpl_vars['fin_nor_score'][$this->_tpl_vars['sn']]['avg']['score'] < 60): ?></font><?php endif; ?></td>
<td>---</td>
</tr>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</table>
</td></tr>
<?php else: ?>
<tr><td><form name="check_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
">請先檢查學期成績是否有多餘資料，以確保成績計算正確。<input type="submit" name="check" value="先檢查成績"></form></td></tr>
<?php endif; ?>
</tr>
</table>
</td></tr>
</table>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['SFS_TEMPLATE'])."/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>