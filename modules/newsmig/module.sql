# $Id: module.sql 5311 2009-01-10 08:11:55Z hami $
#
# 資料表格式： 'newsmig
#

CREATE TABLE `newsmig` (
  `news_sno` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(60) default NULL,
  `posterid` varchar(10) default NULL,
  `news` text,
  `postdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `newslink` varchar(70) default NULL,
  PRIMARY KEY  (`news_sno`)
) TYPE=MyISAM AUTO_INCREMENT=0 ;
