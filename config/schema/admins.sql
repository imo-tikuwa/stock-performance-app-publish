DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '名前',
  `mail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'メールアドレス',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'パスワード',
  `use_otp` tinyint(1) DEFAULT '0' COMMENT '二段階認証を使用する？',
  `otp_secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '二段階認証用シークレットキー',
  `privilege` json DEFAULT NULL COMMENT '権限',
  `api_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'OpenAPIトークン',
  `created` datetime NOT NULL COMMENT '作成日時',
  `modified` datetime NOT NULL COMMENT '更新日時',
  `deleted` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='管理者情報';
