#$Id: module.sql 5311 2009-01-10 08:11:55Z hami $
# 資料表格式： `score_paper`
#
# 請將您的資料表 CREATE TABLE 語法置於下。
# 若無，則請將本檔 module.sql 刪除。

CREATE TABLE `score_paper` (
  `sp_sn` smallint(5) unsigned NOT NULL auto_increment,
  `file_name` varchar(255) NOT NULL default '',
  `sp_name` varchar(255) NOT NULL default '',
  `descriptive` text NOT NULL,
  `enable` enum('1','2') NOT NULL default '1',
  PRIMARY KEY  (`sp_sn`)
) TYPE=MyISAM;



