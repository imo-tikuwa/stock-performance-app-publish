DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) NOT NULL COMMENT '名前',
  `mail` varchar(255) NOT NULL COMMENT 'メールアドレス',
  `password` varchar(255) NOT NULL COMMENT 'パスワード',
  `use_otp` tinyint(1) DEFAULT '0' COMMENT '二段階認証を使用する？',
  `otp_secret` varchar(255) DEFAULT NULL COMMENT '二段階認証用シークレットキー',
  `privilege` json DEFAULT NULL COMMENT '権限',
  `api_token` varchar(255) DEFAULT NULL COMMENT 'OpenAPIトークン',
  `created` datetime DEFAULT NULL COMMENT '作成日時',
  `modified` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理者情報';
