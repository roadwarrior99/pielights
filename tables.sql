CREATE TABLE `pieservice`.`error_log` ( `id` INT NOT NULL , `timestamp` TIMESTAMP NOT NULL , `error_message` TEXT NOT NULL , `json` JSON NULL , PRIMARY KEY (`id`) USING BTREE) ENGINE = InnoDB;

create table `pieservice`.`request` (`id` int not null, `timestamp` timestamp NOT NULL,`request` JSON NULL, `voiceQuery` text,`response` JSON NULL);

create table `pieservice`.`light` (`id` int not null, `name` text, `ip` text)

