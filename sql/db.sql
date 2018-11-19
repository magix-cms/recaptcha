CREATE TABLE IF NOT EXISTS `mc_recaptcha` (
  `id_recaptcha` smallint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `apiKey` varchar(125) DEFAULT NULL,
  `secret` varchar(125) DEFAULT NULL,
  `published` smallint(1) NOT NULL DEFAULT '0',
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_recaptcha`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `mc_admin_access` (`id_role`, `id_module`, `view`, `append`, `edit`, `del`, `action`)
  SELECT 1, m.id_module, 1, 1, 1, 1, 1 FROM mc_module as m WHERE name = 'recaptcha';