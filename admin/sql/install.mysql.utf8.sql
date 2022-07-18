DROP TABLE IF EXISTS reports;

CREATE TABLE `reports` (
        `pk` SERIAL NOT NULL AUTO_INCREMENT,
	`id` SERIAL NOT NULL,
	`weekStart` VARCHAR(20) NOT NULL,
	`data` JSON NOT NULL,
	PRIMARY KEY (`pk`)
) ENGINE=InnoDB;
