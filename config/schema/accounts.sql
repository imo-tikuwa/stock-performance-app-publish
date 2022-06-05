DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '口座名',
  `init_record` int NOT NULL COMMENT '初期資産額',
  `created` datetime NOT NULL COMMENT '作成日時',
  `modified` datetime NOT NULL COMMENT '更新日時',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='口座';
