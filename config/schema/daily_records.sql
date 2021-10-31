DROP TABLE IF EXISTS `daily_records`;
CREATE TABLE `daily_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `account_id` int(11) DEFAULT NULL COMMENT '口座名',
  `day` date DEFAULT NULL COMMENT '日付',
  `record` int DEFAULT NULL COMMENT '資産額',
  `created` datetime DEFAULT NULL COMMENT '作成日時',
  `modified` datetime DEFAULT NULL COMMENT '更新日時',
  PRIMARY KEY (`id`),
  INDEX `account_id` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='資産記録';
