CREATE TABLE IF NOT EXISTS `auction` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `owner` int(11) unsigned NOT NULL,
  `start` int(11) unsigned NOT NULL,
  `stop` int(11) unsigned NOT NULL,
  `updated` int(11) unsigned NOT NULL,
  `price` float unsigned NOT NULL,
  `bid` float NOT NULL,
  `bidcount` smallint(3) unsigned NOT NULL DEFAULT '0',
  `bidhistory` text NOT NULL,
  `header` varchar(200) NOT NULL,
  `body` text NOT NULL,
  `protection` smallint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
