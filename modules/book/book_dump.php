<?php

// $Id: book_new.php 5471 2009-05-06 12:30:50Z infodaes $


// --系統設定檔
include "book_config.php";

if (!checkid($_SERVER[SCRIPT_FILENAME],1)){
	include "header.php";
	echo "<h3>非管理者勿進入</h3>";
	include "footer.php";
	exit;
}

$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

if ($_POST['act'] == '匯出 xls') {
	require_once "../../include/sfs_case_excel.php";
	$rowtext=array("書號","總類","分類","書名","作者","出版社","出版日期","裝訂","定價","ISBN");

	$bookch1_id = $_POST['bookch1_id'];

	$x=new sfs_xls();
	$x->setUTF8();
	$x->setBorderStyle(1);
	$x->addSheet('圖書資料表');
	$x->setRowText($rowtext);
	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	$query = "SELECT a.*,b.bookch1_name,b.bookch2_name FROM book a, bookch1 b WHERE
	   a.bookch1_id=b.bookch1_id AND a.bookch1_id='$bookch1_id'  ORDER BY bookch1_id, book_id ";
	$res = $CONN->Execute($query) or die($query);
	$arr = array();
	while ($row = $res->fetchRow()) {
		$arr[] = array($row['book_id'], $row['bookch1_name'],$row['bookch2_name'],$row['book_name'],
		$row['book_author'], $row['book_maker'], $row['book_myear'], $row['book_bind'],
		$row['book_price'], $row['ISBN']);
	}
	$x->items=$arr;
	$x->writeSheet();
	$x->process();
	exit;
}

include "header.php";
?>
<form method="post" action="">

<?php
$query = "SELECT *  FROM bookch1 ORDER BY bookch1_id";
$res = $CONN->Execute($query) or die($query);
?>
<select name="bookch1_id" >
<?php while ($row = $res->fetchRow()): ?>
<?php if ($row['bookch1_id'] == $_POST['bookch1_id']):?>
<option value="<?php echo $row['bookch1_id']?>" selected><?php echo $row['bookch1_name']?></option>
<?php else:?>
<option value="<?php echo $row['bookch1_id']?>" ><?php echo $row['bookch1_name']?></option>
<?php endif?>
<?php endwhile;?>
</select>
<input type="submit" name="act"
	value="匯出 xls" />
</form>
<?php
include "footer.php";
