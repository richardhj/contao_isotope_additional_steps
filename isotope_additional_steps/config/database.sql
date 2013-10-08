-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************


--
-- Table `tl_iso_additional_steps`
--

CREATE TABLE `tl_iso_additional_steps` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `label` varchar(255) NOT NULL default '',
  `position` smallint(2) unsigned NOT NULL default '0',
  `iso_checkout_method` varchar(10) NOT NULL default '',
  `countries` blob NULL,
  `insert_article` smallint(5) unsigned NOT NULL default '0',
  `enabled` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
