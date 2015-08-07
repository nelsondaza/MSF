
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

