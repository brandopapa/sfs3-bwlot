<?
// $Id: chc_del.php 6746 2012-04-17 04:49:03Z infodaes $

require "config.php";
sfs_check();
if ($_GET[act]=='del' && $_GET[cyear]!='') {
	$year=$_GET[cyear];
//	$SQL="TRUNCATE TABLE stud_compile ";
	$SQL="delete from  stud_compile where LEFT(new_class,1)='$year' ";
	$rs=$CONN->Execute($SQL) or die($SQL); 
	header("Location:continue.php");
}
head("S形編班");

$SQL="select LEFT(new_class,1) as year, count(compile_sn) as tol from stud_compile group by  LEFT(new_class,1) ";
$rs=$CONN->Execute($SQL) or die($SQL); 


echo "<H2><CENTER>清除編班資料</CENTER></H2><TABLE width='50%'  border='0' align='center' cellpadding='1' cellspacing='1' bgcolor='#9EBCDD' style='font-size:14pt'>
<TR bgcolor='#E1ECFF' align='center' style='font-size:12pt'><TD>己編班的新年級</TD><TD>己編班人數</TD><TD>刪除</TD></TR>";
while ($ro=$rs->FetchNextObject(false)) {
  $tea[$ro->year]=get_object_vars($ro);
  echo "<TR bgcolor='white' align='center' style='font-size:16pt'><TD>".$ro->year."</TD><TD>".$ro->tol."</TD><TD><B  onclick=\"if( window.confirm('確定刪除？')) {location.href='chc_del.php?act=del&cyear=".$ro->year."';}\"  style='font-weight: 300;color:red;font-size:12pt'>
[ｘ刪除]</B></TD></TR>";
  }
echo "</TABLE>";
?>
