--
-- 表的结构 `sync`
--

CREATE TABLE IF NOT EXISTS `sync` (
  `id` char(32) NOT NULL COMMENT '唯一同步标识',
  `t_access_token` char(42) NOT NULL COMMENT '腾讯授权令牌',
  `t_openid` char(42) NOT NULL COMMENT '腾讯用户id',
  `t_tweet_id` varchar(20) NOT NULL COMMENT '腾讯最后一条微博唯一id',
  `s_access_token` char(42) NOT NULL COMMENT '新浪授权令牌',
  `s_uid` varchar(10) NOT NULL COMMENT '新浪用户id',
  `s_tweet_id` varchar(20) NOT NULL COMMENT '新浪最后一条微博唯一id',
  `is_notify` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否通知',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '同步方式(0关闭1双向2腾讯->新浪3新浪->腾讯)',
  `counter` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '计数器',
  `time` int(10) unsigned NOT NULL COMMENT '记录创建/修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
);
