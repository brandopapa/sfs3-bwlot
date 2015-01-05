<?php
                                                                                                                             
// $Id: booksay.php 5310 2009-01-10 07:57:56Z hami $

include "book_config.php";
include "header.php";
$sel = $_REQUEST['sel'];
$query = "select bs_con from book_say where bs_id=$sel";
$res=$CONN->Execute($query);
echo $res->fields[0];
include "footer.php";
?>
