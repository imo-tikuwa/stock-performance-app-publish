DROP TABLE IF EXISTS `deposits`;
CREATE TABLE `deposits` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `deposit_date` date NOT NULL COMMENT '入出金日',
  `deposit_amount` int NOT NULL COMMENT '入出金額',
  `created` datetime NOT NULL COMMENT '作成日時',
  `modified` datetime NOT NULL COMMENT '更新日時',
  `deleted` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='入出金';
