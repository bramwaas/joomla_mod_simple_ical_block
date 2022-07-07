--
-- This file will contain Table structure for `"__simpleicalblock`
-- v 0.0.0 copied from wsaonepage and added `transientblob` the only specific field for this module.
--
DROP TABLE IF EXISTS `#__simpleicalblock`;


CREATE TABLE IF NOT EXISTS `#__simpleicalblock` (
  	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  	`asset_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
  	`title` varchar(255) NOT NULL DEFAULT '',
  	`alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
	`transientblob` MEDIUMBLOB NULL,
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
	PRIMARY KEY (`id`),
  	UNIQUE KEY `idx_alias` (`alias`),
  	KEY `idx_access` (`access`),
  	KEY `idx_checkout` (`checked_out`),
  	KEY `idx_createdby` (`created_by`),
  	KEY `idx_language` (`language`),
  	KEY `idx_published` (`published`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8mb4
	DEFAULT COLLATE=utf8mb4_unicode_ci;
--
-- info for com_contenthistory 
--
INSERT INTO `#__content_types` (`type_id`, `type_title`, `type_alias`, `table`, `rules`, `field_mappings`, `router`, `content_history_options`) 
VALUES
(null, 
'Simpleicalblock', 
'mod_simple_ical_block.simpleicalblock', 
'{"special":{"dbtable":"#__simpleicalblock","key":"id","type":"SimpleicalblockTable","prefix":"WaasdorpSoekhan\\Module\\Simpleicalblock\\Administrator\\Table\\","config":"array()"},"common":{"dbtable":"#__ucm_content","key":"ucm_id","type":"Corecontent","prefix":"Joomla\\CMS\\Table\\","config":"array()"}}', 
'',
'{"common":{"core_content_item_id":"id","core_title":"title","core_state":"published","core_alias":"alias","core_created_time":"created","core_modified_time":"modified","core_body":"description", "core_hits":"hits","core_publish_up":"publish_up","core_publish_down":"publish_down","core_access":"access", "core_params":"params", "core_featured":"null", "core_metadata":"metadata", "core_language":"language", "core_images":"null", "core_urls":"null", "core_version":"version", "core_ordering":"ordering", "core_metakey":"metakey", "core_metadesc":"metadesc", "core_catid":"null", "asset_id":"asset_id", "note":"null"}, "special":{"transientblob":"transientblob"}}',
'',
'{"formFile":"administrator\\/modules\\/mod_simple_ical_block\\/forms\\/simpleicalblock.xml", "hideFields":["checked_out","checked_out_time","params","language"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"} ]}'
);
