# $Id: module.sql 5311 2009-01-10 08:11:55Z hami $
CREATE TABLE stud_compile (
  compile_sn int(10) unsigned NOT NULL auto_increment,
  student_sn int(10) unsigned NOT NULL default '0',
  sort int unsigned NOT NULL default '0',
  old_class varchar(11) NOT NULL default '',
  new_class varchar(11) NOT NULL default '',
  site_num tinyint(3) unsigned NOT NULL default '0',
  sex tinyint(1) unsigned NOT NULL default '0',
  stud_birthday date NOT NULL default '0000-00-00',
  update_time datetime NOT NULL default '0000-00-00 00:00:00',
  bs varchar(11) NOT NULL default '',
  PRIMARY KEY  (compile_sn)
) TYPE=MyISAM;
