CREATE TABLE `cuentas` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`documento` BIGINT(20) NOT NULL DEFAULT '0',
	`nro_cuenta` BIGINT(20) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `FK_cuentas_usuarios` (`documento`) USING BTREE,
	CONSTRAINT `FK_cuentas_usuarios` FOREIGN KEY (`documento`) REFERENCES `usuarios` (`documento`) ON UPDATE NO ACTION ON DELETE NO ACTION
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=5
;
