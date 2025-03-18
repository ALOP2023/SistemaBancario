CREATE TABLE `depositos` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`documento` BIGINT(20) NOT NULL,
	`saldo` DOUBLE NOT NULL,
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `FK_depositos_usuarios` (`documento`) USING BTREE,
	CONSTRAINT `FK_depositos_usuarios` FOREIGN KEY (`documento`) REFERENCES `usuarios` (`documento`) ON UPDATE NO ACTION ON DELETE NO ACTION
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=9
;
