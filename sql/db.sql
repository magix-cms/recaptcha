CREATE TABLE IF NOT EXISTS `mc_recaptcha` (
  `id_recaptcha` smallint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `apiKey` varchar(125) DEFAULT NULL,
  `secret` varchar(125) DEFAULT NULL,
  `version` smallint(2) UNSIGNED NOT NULL DEFAULT '2',
  `published` smallint(1) NOT NULL DEFAULT '0',
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_recaptcha`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;