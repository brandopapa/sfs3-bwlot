<?php

// $Id: file.php 5310 2009-01-10 07:57:56Z hami $

include "config.php";
$str="SELECT main_data, type FROM file_db WHERE FSN={$_GET['FSN']}";
$recordSet=$CONN->Execute($str);
if ($recordSet) {
	$image = $recordSet->FetchRow();
	header("Content-Type: $image[type]");
	echo stripslashes(Base64_Decode($image[main_data])); 
}
?> 
