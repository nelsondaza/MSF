
ALTER TABLE  `msf_consults` ADD  `id_diagnostic` BIGINT( 20 ) UNSIGNED NULL DEFAULT NULL AFTER  `id_referenced_to` ;
ALTER TABLE  `msf_consults` ADD INDEX (  `id_diagnostic` ) ;
ALTER TABLE  `msf_consults` ADD CONSTRAINT  `msf_consults_ibfk_6` FOREIGN KEY (  `id_diagnostic` ) REFERENCES `msf_diagnostics` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT ;

UPDATE  `msf_consults` SET id_referenced_to = NULL WHERE id_referenced_to = 0;

ALTER TABLE  `msf_consults` ADD INDEX (  `id_referenced_to` ) ;
ALTER TABLE  `msf_consults` ADD CONSTRAINT  `msf_consults_ibfk_7` FOREIGN KEY (  `id_referenced_to` ) REFERENCES `msf_references` (
	`id`
) ON DELETE RESTRICT ON UPDATE RESTRICT ;

ALTER TABLE  `msf_consults` ADD INDEX (  `id_closure` ) ;
ALTER TABLE  `msf_consults` ADD CONSTRAINT  `msf_consults_ibfk_8` FOREIGN KEY (  `id_closure` ) REFERENCES `msf_closures` (
	`id`
) ON DELETE RESTRICT ON UPDATE RESTRICT ;

ALTER TABLE  `msf_consults` ADD INDEX (  `id_closure_condition` ) ;
ALTER TABLE  `msf_consults` ADD CONSTRAINT  `msf_consults_ibfk_9` FOREIGN KEY (  `id_closure_condition` ) REFERENCES `msf_closures_conditions` (
	`id`
) ON DELETE RESTRICT ON UPDATE RESTRICT ;


ALTER TABLE  `msf_consults` ADD  `id_creator` BIGINT( 20 ) UNSIGNED NULL DEFAULT NULL AFTER  `id` ,
ADD INDEX (  `id_creator` );

UPDATE  `msf_consults` SET id_creator = 1 WHERE id_creator IS NULL;

ALTER TABLE  `msf_consults` CHANGE  `id_creator`  `id_creator` BIGINT( 20 ) UNSIGNED NOT NULL;


INSERT INTO `msf_references` (`id`, `id_category`, `name`, `active`) VALUES (NULL, '2', '05 Psicólogo MSF', '1');
INSERT INTO `msf_references` (`id`, `id_category`, `name`, `active`) VALUES (NULL, '2', '06 Trabajador Social MSF', '1');
INSERT INTO `msf_risks_categories` (`id`, `name`, `active`) VALUES (NULL, 'Otros', '1');

