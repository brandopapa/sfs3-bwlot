<?php

include "config.php";
sfs_check();

//秀出網頁
head("使用說明");

//橫向選單標籤
$linkstr="item_id=$item_id";
echo print_menu($MENU_P,$linkstr);

$help_doc="<br><br>
<li>教育部十二年國民基本教育資訊網：<a href='http://12basic.edu.tw/' target='_BLANK'>http://12basic.edu.tw/</a></li>
		<li>屏東區高中職免試入學作業要點：<a href='http://12basic.tchcvs.tc.edu.tw/File/Levelimg_35/11.pdf' target='_BLANK'>http://12basic.tchcvs.tc.edu.tw/File/Levelimg_35/11.pdf</a></li>
		<li>屏東區103免試入學超額比序資料匯出模組操作說明：<a href='./103_ptc_preview.pdf' target='_BLANK'><img src='./images/on.png' border=0>預覽版</a> <a href='./103_ptc_1.0.pdf' target='_BLANK'><img src='./images/on.png' border=0>1.0版</a></li>
";

echo $help_doc;
foot();
?>