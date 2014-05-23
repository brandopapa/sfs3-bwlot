# $Id: module.sql 5311 2009-01-10 08:11:55Z hami $

CREATE TABLE `copy_log` (
  `cp_sn` int(10) unsigned NOT NULL auto_increment,
  `sn` int(10) unsigned NOT NULL default '0',
  `tbl_name` varchar(255) NOT NULL default '',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `record` varchar(255) NOT NULL default '',
  `year` tinyint(3) unsigned NOT NULL default '0',
  `semester` enum('1','2') NOT NULL default '1',
  PRIMARY KEY  (`cp_sn`)
) TYPE=MyISAM;
