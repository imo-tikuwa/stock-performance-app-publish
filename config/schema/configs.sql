DROP TABLE IF EXISTS `configs`;
CREATE TABLE `configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `display_only_month` char(2) DEFAULT NULL COMMENT '月ごと表示モード',
  `display_init_record` char(2) DEFAULT NULL COMMENT '初期資産額表示',
  `record_total_real_color` char(6) DEFAULT NULL COMMENT '実質資産のチャートカラー',
  `init_record_color` char(6) DEFAULT NULL COMMENT '初期資産のチャートカラー',
  `display_setting` json DEFAULT NULL COMMENT '表示項目設定',
  `chromedriver_path` varchar(255) DEFAULT NULL COMMENT 'ChromeDriverのパス',
  `created` datetime DEFAULT NULL COMMENT '作成日時',
  `modified` datetime DEFAULT NULL COMMENT '更新日時',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='設定';
