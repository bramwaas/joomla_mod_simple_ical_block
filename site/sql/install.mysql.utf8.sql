--
-- This file will contain Table structure for `"__simpleicalblock`
-- v 0.0.0 copied from wsaonepage and added `transientblob` the only specific field for this module.
--
DROP TABLE IF EXISTS `#__simpleicalblock`;


CREATE TABLE IF NOT EXISTS `#__simpleicalblock` (
  	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  	`transient_id` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
	`transient_blob` MEDIUMBLOB NULL COMMENT 'PHP serialized data object',
  	`transient_expires` bigint(20) COMMENT 'PHP unix timestamp', 
/
	PRIMARY KEY (`id`),
	UNIQUE KEY `idx_transient_id` (`transient_id`)
	

)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8mb4
	DEFAULT COLLATE=utf8mb4_unicode_ci;
