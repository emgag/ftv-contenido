DROP TABLE IF EXISTS `!PREFIX!_art`;

CREATE TABLE `!PREFIX!_art` (
  `idart` int(11) NOT NULL auto_increment,
  `idclient` int(11) NOT NULL default '0',
  PRIMARY KEY  (`idart`),
  KEY `idclient` (`idclient`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;

INSERT INTO `!PREFIX!_art` (`idart`, `idclient`) VALUES
(1, 1),
(2, 1),
(3, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(12, 1),
(13, 1),
(15, 1),
(16, 1),
(17, 1),
(19, 1),
(20, 1),
(21, 1),
(24, 1),
(29, 1),
(31, 1),
(32, 1),
(33, 1),
(43, 1),
(45, 1),
(46, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(67, 1),
(68, 1),
(69, 1),
(70, 1),
(74, 1),
(75, 1),
(76, 1),
(77, 1),
(78, 1),
(82, 1),
(83, 1),
(84, 1),
(85, 1),
(86, 1),
(87, 1);