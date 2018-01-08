CREATE TABLE IF NOT EXISTS `smslib_send` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date_heure_sms` datetime NOT NULL,
  `sender` varchar(15) COLLATE utf8_bin NOT NULL,
  `receiver` varchar(15) COLLATE utf8_bin NOT NULL,
  `domaine` varchar(30) COLLATE utf8_bin NOT NULL,
  `domaine_id` varchar(30) COLLATE utf8_bin NOT NULL,
  `contenu` varchar(1024) COLLATE utf8_bin NOT NULL,
  `flag` tinyint(2) NOT NULL,
  `dlr_url` varchar(1024) COLLATE utf8_bin NOT NULL,
  `send_repeat` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `smslib_sent` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date_heure_envoi` datetime NOT NULL,
  `date_heure_sms` datetime NOT NULL,
  `sender` varchar(15) COLLATE utf8_bin NOT NULL,
  `receiver` varchar(15) COLLATE utf8_bin NOT NULL,
  `domaine` varchar(30) COLLATE utf8_bin NOT NULL,
  `domaine_id` varchar(30) COLLATE utf8_bin NOT NULL,
  `contenu` varchar(1024) COLLATE utf8_bin NOT NULL,
  `flag` tinyint(2) NOT NULL,
  `dlr_url` varchar(1024) COLLATE utf8_bin NOT NULL,
  `dlr_state` tinyint(2) NOT NULL,
  `send_repeat` tinyint(1) NOT NULL,
  `send_repeat_count` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
