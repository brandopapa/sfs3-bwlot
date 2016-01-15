<?php /* Smarty version 2.6.26, created on 2016-01-15 15:29:29
         compiled from fitness_input.tpl */ ?>
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

<script language="JavaScript">
function openwindow(t){
	window.open ("quick_input.php?t="+t+"&class_num=<?php echo $this->_tpl_vars['class_num']; ?>
&c_curr_seme=<?php echo $_POST['year_seme']; ?>
","成績處理","toolbar=no,location=no,directories=no,status=no,scrollbars=yes,resizable=no,copyhistory=no,width=600,height=420");
}
</script>

<table bgcolor="#DFDFDF" cellspacing="1" cellpadding="4">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>
" method="post">
<input type="hidden" name="act" value="">
<tr>
<td bgcolor="#FFFFFF" valign="top">
<p><?php echo $this->_tpl_vars['seme_menu']; ?>
 <?php echo $this->_tpl_vars['class_menu']; ?>
 <font size="3" color="blue">按下項目名稱即可輸入成績</font> <?php if ($this->_tpl_vars['admin']): ?><input type='submit' value='抓取本學期全校學生身高體重資料' name='copy_wh' onclick='return confirm("學生人數多的話可能會耗時很久，確定要這樣做嗎？")'><?php else: ?><input type='submit' value='抓取本學期身高體重資料' name='copy_wh'><?php endif; ?></p>
<table bgcolor="#9ebcdd" cellspacing="1" cellpadding="4" width="100%">
<tr bgcolor="#c4d9ff">
<td align="center">座號</td>
<td align="center">姓名</td>
<td align="center">學號</td>
<td align="center"><a onclick="openwindow('0');"><img src="./images/wedit.png" border="0" title="資料輸入">身高</a><br>(cm)</td>
<td align="center"><a onclick="openwindow('1');"><img src="./images/wedit.png" border="0" title="資料輸入">體重</a><br>(kg)</td>
<td align="center"><a onclick="openwindow('2');"><img src="./images/wedit.png" border="0" title="資料輸入">坐姿前彎</a><br>(cm)</td>
<td align="center"><a onclick="openwindow('4');"><img src="./images/wedit.png" border="0" title="資料輸入">立定跳遠</a><br>(cm)</td>
<td align="center"><a onclick="openwindow('3');"><img src="./images/wedit.png" border="0" title="資料輸入">仰臥起坐</a><br>(次)</td>
<td align="center"><a onclick="openwindow('5');"><img src="./images/wedit.png" border="0" title="資料輸入">心肺適能</a><br>(秒)</td>
<td align="center"><a onclick="openwindow('6');"><img src="./images/wedit.png" border="0" title="資料輸入">檢測單位</a></td>
<td align="center"><a onclick="openwindow('7');"><img src="./images/wedit.png" border="0" title="資料輸入">檢測年月</a><br>( 年-月 )</td>
</tr>
<?php $_from = $this->_tpl_vars['rowdata']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['d']):
?>
<?php $this->assign('sn', $this->_tpl_vars['d']['student_sn']); ?>
<tr bgcolor="white">
<td class="small"><?php echo $this->_tpl_vars['d']['seme_num']; ?>
</td>
<td class="small"><font color="<?php if ($this->_tpl_vars['d']['stud_sex'] == 1): ?>blue<?php elseif ($this->_tpl_vars['d']['stud_sex'] == 2): ?>red<?php else: ?>black<?php endif; ?>"><?php echo $this->_tpl_vars['d']['stud_name']; ?>
</font></td>
<td style="text-align:right;"><?php echo $this->_tpl_vars['d']['stud_id']; ?>
</td>
<td style="text-align:right;"><?php echo $this->_tpl_vars['fd'][$this->_tpl_vars['sn']]['tall']; ?>
</td>
<td style="text-align:right;"><?php echo $this->_tpl_vars['fd'][$this->_tpl_vars['sn']]['weigh']; ?>
</td>
<td style="text-align:right;"><?php echo $this->_tpl_vars['fd'][$this->_tpl_vars['sn']]['test1']; ?>
</td>
<td style="text-align:right;"><?php echo $this->_tpl_vars['fd'][$this->_tpl_vars['sn']]['test3']; ?>
</td>
<td style="text-align:right;"><?php echo $this->_tpl_vars['fd'][$this->_tpl_vars['sn']]['test2']; ?>
</td>
<td style="text-align:right;"><?php echo $this->_tpl_vars['fd'][$this->_tpl_vars['sn']]['test4']; ?>
</td>
<td style="text-align:left;"><?php echo $this->_tpl_vars['fd'][$this->_tpl_vars['sn']]['organization']; ?>
</td>
<td style="text-align:center;"><?php echo $this->_tpl_vars['fd'][$this->_tpl_vars['sn']]['test_y']; ?>
-<?php echo $this->_tpl_vars['fd'][$this->_tpl_vars['sn']]['test_m']; ?>
</td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
</td></tr></table>
<?php if ($this->_tpl_vars['admin']): ?>
<table border="2" cellpadding="3" cellspacing="0" style="border-collapse: collapse; font-size=9px;" bordercolor="#119911" width="100%">
		<tr><td align="center" bgcolor="#ccffff">測驗結果批次匯入</td></tr>
		<tr><td><font size=2>
			<li>本功能可匯入選定學期就學學生的體適能測驗紀錄，<a href='./xls_sample.xls'><img src='./images/pen.png' border=0 height=11>格式下載</a>。</li>
			<li>匯入的資料採教育部資料格式，欄位須為固定的順序：測驗日期、學校類別、年級、班級名稱、學號、性別、身分證字號、生日、身高、體重、坐姿體前彎、立定跳遠、仰臥起坐、心肺適能、<font color='red'>檢測單位</font>。</li>
			<li>匯入時免登載的欄位：學校類別、年級、性別、身分證字號、生日；<font color='red'>必有的欄位：班級名稱、學號；班級名稱請用序列代號表示，如六年甲班請填601、九年二班請填902。</font></li>
			<li>匯入後，程式會將資料內有效學生指定學期原有的紀錄刪除，再依據您貼上的資料重新記錄，請謹慎使用。</li>
			<li>複製貼上的資料無須包含欄位名稱或說明，僅需貼上學生紀錄列即可！</li>
			</font></td></tr>
		<tr><td>
		<textarea name="content" style="border-width:1px; color:blue; background:#ffeeee; font-size:11px;" cols=120 rows=5></textarea></td></tr>
		<tr><td align="center" bgcolor="#ccffff"><input type="submit" name="go" value="匯入"></td></tr>
		</table><font color="red"><?php echo $this->_tpl_vars['msg']; ?>
</font>
<?php endif; ?>
</form>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['SFS_TEMPLATE'])."/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>