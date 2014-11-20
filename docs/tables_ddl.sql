CREATE TABLE `usuario` (
  `usuario_id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_nick` varchar(45) NOT NULL,
  `usuario_nome` varchar(45) NOT NULL,
  `usuario_email` varchar(45) NOT NULL,
  `usuario_telefone` varchar(45) DEFAULT NULL,
  `usuario_facebook` varchar(45) DEFAULT NULL,
  `usuario_twitter` varchar(45) DEFAULT NULL,
  `usuario_senha` varchar(45) NOT NULL,
  `usuario_foto` varchar(1000) DEFAULT NULL,
  `usuario_admin` int(11) NOT NULL DEFAULT '0',
  `status_id` int(11) NOT NULL,
  `usuario_token` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`usuario_id`),
  KEY `fk_Usuario_Status1_idx` (`status_id`),
  CONSTRAINT `fk_Usuario_Status1` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;



CREATE TABLE `status` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_codigo` varchar(45) NOT NULL,
  `status_nome` varchar(45) NOT NULL,
  `status_descricao` text,
  `status_visivel` char(1) NOT NULL,
  `status_tipo_id` int(11) NOT NULL,
  PRIMARY KEY (`status_id`),
  KEY `fk_Status_StatusTipo1_idx` (`status_tipo_id`),
  CONSTRAINT `fk_Status_StatusTipo1` FOREIGN KEY (`status_tipo_id`) REFERENCES `status_tipo` (`status_tipo_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
