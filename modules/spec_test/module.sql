# $Id: module.sql 5311 2009-01-10 08:11:55Z hami $
#
# 資料表格式： `test_manage`
#

CREATE TABLE test_manage (
  id int(10) unsigned NOT NULL auto_increment,
  year smallint(5) unsigned NOT NULL default '0',
  semester enum('1','2') NOT NULL default '1',
  c_year tinyint(2) unsigned NOT NULL default '0',
  title text NOT NULL,
  subject_str text NOT NULL,
  ratio_str text NOT NULL,
  compare_id int(10) unsigned NOT NULL default '0',
  PRIMARY KEY (id),
  KEY serial (id)
) TYPE=MyISAM;
