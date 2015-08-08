
ALTER TABLE  `msf_consults` ADD  `id_creator` BIGINT( 20 ) UNSIGNED NULL DEFAULT NULL AFTER  `id` ,
ADD INDEX (  `id_creator` ) ;

UPDATE  `msf_consults` SET id_creator = 1 WHERE id_creator IS NULL

ALTER TABLE  `msf_consults` CHANGE  `id_creator`  `id_creator` BIGINT( 20 ) UNSIGNED NOT NULL ;

