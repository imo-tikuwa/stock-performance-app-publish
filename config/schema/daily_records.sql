DROP TABLE IF EXISTS `daily_records`;
CREATE TABLE `daily_records` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `account_id` int NOT NULL COMMENT '口座名',
  `day` date NOT NULL COMMENT '日付',
  `record` int NOT NULL COMMENT '資産額',
  `created` datetime NOT NULL COMMENT '作成日時',
  `modified` datetime NOT NULL COMMENT '更新日時',
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='資産記録';
