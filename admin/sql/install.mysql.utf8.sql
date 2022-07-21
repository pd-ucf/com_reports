DROP TABLE IF EXISTS reports;

CREATE TABLE `reports` (
        `pk` SERIAL PRIMARY KEY NOT NULL,
	`id` INT NOT NULL,
	`weekStart` VARCHAR(20) NOT NULL,
	`data` JSON NOT NULL
) ENGINE=InnoDB;
