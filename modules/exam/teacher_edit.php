<?php

                                                                                                                             
// $Id: teacher_edit.php 6807 2012-06-22 08:08:30Z smallduh $

if (!$isload)
{
include "config.php";
//session_start();
if ($session_tea_img != "1")
{
 $exename = $PHP_SELF;                                 
 include "checkid.php";
 exit;
}

include "header.php";
}
if ($sel =="delete")
  {
        echo "<form action=\"$PHP_SELF\" method=\"post\">\n"; 
        echo "確定刪除 <font color=red>".stripslashes ($stud_name)."</font> ？<br>";
        echo "<input type=\"hidden\" name=\"stud_id\" value=\"$stud_id\">\n";
        echo "<input type=\"submit\" name=\"key\" value=\"確定刪除\" >\n";
        echo "&nbsp;&nbsp;<input type=button  value= \"回上頁\" onclick=\"history.back()\">";
        echo "</form>";
        include "footer.php";
	exit;
  }  
  if ($key =="確定刪除")
  {
        $sql_update = " delete from stud_base ";
	$sql_update .= " where stud_id='$stud_id' ";
	$result = mysql_query ($sql_update,$conID)  or die ($sql_update);  
	include "stud_base.php";	
	exit;
  }
  if ($key =="修改")
  {
	$sql_update = "update stud_base set stud_id='$stud_id',stud_name='$stud_name',stud_pass='$stud_pass',tea_school='$tea_school',tea_img='$tea_img' ";
	$sql_update .= " where stud_id='$stud_id' ";
	$result = mysql_query ($sql_update,$conID)  or die ($sql_update);  
	//echo $sql_update;
	include "stud_base.php";
	exit;
  }
  $sql_select = "select stud_id,stud_name,stud_pass,tea_school,tea_img from stud_base";
  $sql_select .= " where stud_id='$stud_id' ";
  $result = mysql_query ($sql_select,$conID);  
 
while ($row = mysql_fetch_array($result)) {

	$stud_id = $row["stud_id"];
	$stud_name = $row["stud_name"];
	$stud_pass = $row["stud_pass"];
	$tea_school = $row["tea_school"];
        if ($row["tea_img"]=='1') 
	    $tea_img = " checked ";
	   else 
	    $tea_img = " ";
		

};
?>
修改人員資料
<form method="post" >
<table>
<tr>
	<td>教師代號<br>
		<?php echo $stud_id ?>
	</td>
</tr>
<tr>
	<td>教師姓名<br>
		<input type="text" size="20" maxlength="20" name="stud_name" value="<?php echo $stud_name ?>">
	</td>
</tr>



<tr>
	<td>密碼<br>
		<input type="text" size="6" maxlength="6" name="stud_pass" value="<?php echo $stud_pass ?>">
	</td>
</tr>



<tr>
	<td>學校<br>
		<input type="text" size="20" maxlength="20" name="tea_school" value="<?php echo $tea_school ?>">
	</td>
</tr>
<tr>
	<td>管理者<br>
		是 <input type="checkbox" name="tea_img" value="1" <? echo $tea_img ?> > 
	</td>
</tr>
<tr>
	<td>
	<input type="hidden" name=stud_id value="<? echo $stud_id; ?>">
	<input type="submit" name=key value="修改">
	&nbsp;&nbsp;<input type="button"  value= "回上頁" onclick="history.back()">
	</td>
</tr>

</table>


<? include "footer.php"; ?>
