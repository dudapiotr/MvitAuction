CREATE TABLE IF NOT EXISTS `auction` (
  `A_Id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `A_Owner` int(11) unsigned NOT NULL,
  `A_CategoryId` int(5) unsigned NOT NULL,
  `A_CurrencyId` int(5) unsigned NOT NULL,
  `A_Created` int(11) unsigned NOT NULL,
  `A_EndTime` int(11) unsigned NOT NULL,
  `A_Updated` int(11) unsigned NOT NULL,
  `A_Price` int(10) unsigned NOT NULL,
  `A_Buyout` float unsigned NOT NULL,
  `A_Slug` varchar(200) NOT NULL,
  `A_Header` varchar(200) NOT NULL,
  `A_Body` text NOT NULL,
  `A_Protection` smallint(1) unsigned NOT NULL,
  PRIMARY KEY (`A_Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `auction_bid` (
  `AB_Id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `AB_Auction` int(11) unsigned NOT NULL,
  `AB_User` int(11) unsigned NOT NULL,
  `AB_Bid` float unsigned NOT NULL,
  `AB_Time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`AB_Id`),
  KEY `AB_Action` (`AB_Auction`),
  KEY `AB_User` (`AB_User`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `auction_category` (
  `AC_Id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `AC_Parent` int(5) unsigned NOT NULL DEFAULT '0',
  `AC_Name` varchar(200) NOT NULL,
  `AC_Slug` varchar(200) NOT NULL,
  `AC_Visible` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`AC_Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `auction_currency` (
  `ACU_Id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `ACU_Name` varchar(255) NOT NULL,
  `ACU_Before` varchar(5) NOT NULL,
  `ACU_After` varchar(5) NOT NULL,
  PRIMARY KEY (`ACU_Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;