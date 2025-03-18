CREATE TABLE `claves_dinamicas` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`documento` BIGINT(20) NOT NULL,
	`clave` VARCHAR(6) NOT NULL COLLATE 'latin1_swedish_ci',
	`fecha_creacion` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
	`fecha_modificacion` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
	PRIMARY KEY (`id`) USING BTREE,
	UNIQUE INDEX `documento` (`documento`) USING BTREE
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1082
;
