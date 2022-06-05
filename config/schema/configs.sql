DROP TABLE IF EXISTS `configs`;
CREATE TABLE `configs` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `display_only_month` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '月ごと表示モード',
  `display_init_record` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '初期資産額表示',
  `record_total_real_color` char(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '実質資産のチャートカラー',
  `init_record_color` char(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '初期資産のチャートカラー',
  `display_setting` json DEFAULT NULL COMMENT '表示項目設定',
  `chromedriver_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'ChromeDriverのパス',
  `created` datetime NOT NULL COMMENT '作成日時',
  `modified` datetime NOT NULL COMMENT '更新日時',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='設定';
