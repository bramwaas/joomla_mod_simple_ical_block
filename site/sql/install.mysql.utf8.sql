--
-- This file will contain Table structure for `"__simpleicalblock`
-- v 0.0.0 copied from wsaonepage and added `transientblob` the only specific field for this module.
--
DROP TABLE IF EXISTS `#__simpleicalblock`;


CREATE TABLE IF NOT EXISTS `#__simpleicalblock` (
  	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  	`transient_id` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
	`transient_blob` MEDIUMBLOB NULL,
  	`transient_expires` datetime, 
/*  	`asset_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
  	`title` varchar(255) NOT NULL DEFAULT '',
  	`alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
	`description` VARCHAR(255) NOT NULL DEFAULT '',
	`created` datetime NOT NULL,
	`created_by` int(10) unsigned NOT NULL DEFAULT 0,
	`created_by_alias` varchar(255) NOT NULL DEFAULT '',
  	`modified` datetime NOT NULL,
  	`modified_by` int(10) unsigned NOT NULL DEFAULT 0,
  	`checked_out` int(10) unsigned,
  	`checked_out_time` datetime,
  	`publish_up` datetime, 
  	`publish_down` datetime, 
  	`version` int(10) unsigned NOT NULL DEFAULT 1 COMMENT 'Number of revisions',
  	`ordering` int(11) NOT NULL DEFAULT 0,
  	`metakey` text NOT NULL DEFAULT '',
  	`metadesc` text NOT NULL DEFAULT '',
  	`access` int(10) unsigned NOT NULL DEFAULT 0,
  	`hits` int(10) unsigned NOT NULL DEFAULT 0,
  	`metadata` text NOT NULL DEFAULT '',
	`language`  CHAR(7)  NOT NULL DEFAULT '*',
	`published` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'State of the record in some other components state',
	`params`   VARCHAR(1024) NOT NULL DEFAULT '' COMMENT 'params for this component in some other components attribs',
	`image`   VARCHAR(1024) NOT NULL DEFAULT '',
*/	
	PRIMARY KEY (`id`),
	UNIQUE KEY `idx_transient_id` (`transient_id`)
	
/*  	UNIQUE KEY `idx_alias` (`alias`),
  	KEY `idx_access` (`access`),
  	KEY `idx_checkout` (`checked_out`),
  	KEY `idx_createdby` (`created_by`),
  	KEY `idx_language` (`language`),
  	KEY `idx_published` (`published`) */
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8mb4
	DEFAULT COLLATE=utf8mb4_unicode_ci;
