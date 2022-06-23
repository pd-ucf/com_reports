DROP TABLE IF EXISTS reports;

CREATE TABLE `reports` (
	`id` SERIAL NOT NULL,
	`weekStart` VARCHAR(20) NOT NULL,
	`data` JSON NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB;