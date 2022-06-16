DROP TABLE IF EXISTS reports;

CREATE TABLE `reports` (
	`username` TEXT(20) NOT NULL,
	`weekStart` VARCHAR(20) NOT NULL,
	`data` TEXT(65535) NOT NULL
) ENGINE=InnoDB;