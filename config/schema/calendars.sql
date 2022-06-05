DROP TABLE IF EXISTS `calendars`;
CREATE TABLE `calendars` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `day` date NOT NULL COMMENT '日付',
  `is_holiday` tinyint(1) NOT NULL DEFAULT '0' COMMENT '休日？',
  `holiday_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '休日名',
  `created` datetime NOT NULL COMMENT '作成日時',
  `modified` datetime NOT NULL COMMENT '更新日時',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='カレンダー';
