<font color=blue><b>文章分類</b></font>：<select name="bk_id" size="1">
<?php
	$query = "select bk_id,bk_order,board_name,position from jboard_kind order by bk_order,bk_id ";
	$result= $CONN->Execute($query) or die ($query);
	while( $row = $result->fetchRow()){
		$P=($row['position']>0)?"".str_repeat("|--",$row['position']):"";
		if (board_checkid($row["bk_id"]) or checkid($_SERVER[SCRIPT_FILENAME],1)) {
			if ($row["bk_id"] == $bk_id  ){
			echo sprintf(" <option style='color:%s;font-weight:bold;font-size:13pt' value=\"%s\" selected>[%05d] %s%s(%s)</option>",$position_color[$row['position']],$row["bk_id"],$row['bk_order'],$P,$row["board_name"],$row["bk_id"]);
		  } else {
		  	if ($row['position']==0) {
					echo sprintf(" <option style='color:%s;font-weight:bold;font-size:13pt' value=\"%s\">[%05d] %s%s(%s)</option>",$position_color[$row['position']],$row["bk_id"],$row['bk_order'],$P,$row["board_name"],$row["bk_id"]);
	  		} else {
					echo sprintf(" <option style='color:%s' value=\"%s\">[%05d] %s%s(%s)</option>",$position_color[$row['position']],$row["bk_id"],$row['bk_order'],$P,$row["board_name"],$row["bk_id"]);
		  	}
			}
		}	
		
		/*
		$P=($row['position']>0)?str_repeat("--",$row['position']):"";
		if (board_checkid($row["bk_id"]) or checkid($_SERVER[SCRIPT_FILENAME],1)) {
			if ($row["bk_id"] == $bk_id  ){
				echo sprintf(" <option style='color:%s' value='%s' selected>%s%s</option>",$position_color[$row['position']],$row["bk_id"],$P,$row["board_name"]);
			}	else {
				echo sprintf(" <option style='color:%s' value='%s' >%s%s</option>",$position_color[$row['position']],$row["bk_id"],$P,$row["board_name"]);
	  	}
		}
		*/
	} // end while
	echo "</select>";
	
?>
<table border='2' cellpadding='3' cellspacing='0' style='border-collapse: collapse; font-size=12px;' bgcolor="#FFFFEE" bordercolor='#111111' width="100%">
<tr>
	<td align="right" valign="top" width="60">發佈日期</td>
	<td>
		<input type="text" size="12" maxlength="12" name="b_open_date" value="<?php echo $b_open_date;?>">
	  期限：<select name="b_days">

<?php
reset($days);
	while (list ($key, $val) = each ($days)){
		if ($b_days == $key )
			echo "<option value=\"$key\" selected >$val";
		else
			echo "<option value=\"$key\" >$val";
	}
?>
</select></td>
</tr>

<tr>
	<td align="right" valign="top">其他設定</td>
	<td>
		<input type="checkbox"  name="b_is_intranet" value="1" <?php if ($b_is_intranet=='1') echo "checked"; ?> > 本訊息只對本校公布		
		<input type="checkbox"  name="b_is_marquee" value="1" <?php if ($b_is_marquee=='1') echo "checked"; ?>> 將本公告置於跑馬燈
		<?php
			if ($enable_is_sign == '1') {
		?>		
		<input type="checkbox"  name="b_is_sign" value="1" <?php if ($b_is_sign=='1') echo "checked"; ?> > 本校教職員須簽收公告
		<?php
			}
		?>
	</td>
</tr>

<tr>
	<td align="right" valign="top">文章標題</td>
	<td ><input type="text" size="80" maxlength="100" name="b_sub" value="<?php echo $b_sub ?>"></td>
</tr>


<tr>
<td align="right" valign="top" nowrap="true">文章內容</td>
		<td colspan="3"><textarea name="b_con" id="b_con" class="ckeditor" cols="80" rows="15"><?php echo $b_con ?></textarea></td>
</tr>
<tr>
	<td align="right" valign="top">相關網址</td>
	<td ><input type="text" name="b_url" size=70 value="<?php echo $b_url ?>"></td>
</tr>
<tr>
	<td vAlign="top" align="right">附件<br/>
      <a href="javascript:addElementToForm('fileFields','file','resourceFile','')" class='b1'>增加附件</a>
	  
	</td>
	<td >
	<div class="field" id="fileFields">
		<input type="file" id="resourceFile_1" name="resourceFile_1" />(單一檔案大小限制:<?php echo $Max_upload;?>MB , 過大的檔案將自動捨棄)
		<br />
		 <div id="marker" style="clear:none;"></div>
	</div>
	<?php
		if ($fArr = board_getFileArray($b_id)){
			echo "<ul>";
			foreach ($fArr as $id => $fName){
				$Org=($fName['org_filename']!="")?"(原檔名：".$fName['org_filename'].")":"";
				echo "<li id='f_$id'><input type='button' value='刪除' class='b1' onClick=\"del_file('f_$id','$id')\"> ".$fName['new_filename'].$Org."</li>";
			}
			echo "</ul>";
		}
	?>
		<input type='hidden' name='file_name'>
		</td>
</tr>
	<?php
	if ($b_signs<>''){
	?>
	<tr>
		<td colspan=2 align=center><input type='checkbox' name='del_sign' value='1'> 須重新回簽公告 </td>
	</tr>
	<?php
	}
	?>
</table>
