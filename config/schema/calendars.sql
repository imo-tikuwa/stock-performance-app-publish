DROP TABLE IF EXISTS `calendars`;
CREATE TABLE `calendars` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `day` date DEFAULT NULL COMMENT '日付',
  `is_holiday` tinyint(1) DEFAULT 0 COMMENT '休日？',
  `holiday_name` varchar(255) DEFAULT NULL COMMENT '休日名',
  `created` datetime DEFAULT NULL COMMENT '作成日時',
  `modified` datetime DEFAULT NULL COMMENT '更新日時',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='カレンダー';
