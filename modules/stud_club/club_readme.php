<?php
// $Id: reward_one.php 6735 2012-04-06 08:14:54Z smallduh $

//取得設定檔
include_once "config.php";

sfs_check();


//秀出網頁
head("社團活動 - 模組說明");

$tool_bar=&make_menu($school_menu_p);

//列出選單
echo $tool_bar;

?>
<table border="0" witdh="800">
	<tr>
		<td>
社團模組操作說明：
		</td>
	</tr>
	<tr>
		<td>
			一、緣起：學校欲舉辦社團活動，希望能有一個可以讓學生自由選課，並加以編班及管理成績的輔助工具。
	  </td>
  </tr>
	<tr>
		<td>
		二、程式適用情境：本程式的設計，係依據下列的情境加以設計，若您學校安排社團活動的模式與下列敘述不符，不敢保證能適用。
	  </td>
  </tr>
	<tr>
		<td>
1.學校每週安排固定社團活動時間，例如：本校是利用每週四的班會聯課活動時間。<br>
2.每位學生每學期只能選一個社團參加，而且同年級每個學生都必須參加。<br>
註：系統允許一個學生可參加多個社團，但第二個社團以上必須手動指定。<br>
3.社團的安排是以班為單位，每位導師都必須開一個社團，當社團活動時間時，學生到他參加的社團班級去活動。<br>
4.一年級只能參加一年級的社團，二年級只能參加二年級的社團，依此類推。<br>
註：系統允許開設誇年級的社團，但不建議開放讓學生自由選課，因編班過程是依年級逐一編班，先編班的年級會優先編入這個社團。<br>
5.學期初，學生自己上網選擇自己想參加的社團。選擇的社團依志願順序排列，在選修時間結束後，再將全校學生
　依志願序排出每個學生最終可參加的社團。<br>			
	  </td>
  </tr>
	<tr>
		<td>
			三、使用流程
	  </td>
  </tr>
  <tr>
  	<td>
    <?php
    readme();
    ?>
  	</td>
  </tr>

</table>



