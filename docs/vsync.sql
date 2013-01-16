-- Create syntax for TABLE 'sina'
CREATE TABLE `sina` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增长id',
      `access_token` char(42) NOT NULL COMMENT '访问凭证',
      `uid` int(10) unsigned NOT NULL COMMENT '用户id',
      `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
      PRIMARY KEY (`id`)
);

-- Create syntax for TABLE 'sync'
CREATE TABLE `sync` (
      `id` char(32) NOT NULL COMMENT '唯一同步id',
      `t_id` int(10) NOT NULL COMMENT '腾讯表id',
      `s_id` int(10) NOT NULL COMMENT '新浪表id',
      `type` tinyint(2) unsigned NOT NULL COMMENT '同步方式(0关闭1双向2腾讯->新浪3新浪->腾讯)',
      `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
      `mtime` int(10) unsigned NOT NULL COMMENT '更改时间',
      PRIMARY KEY (`id`)
);

-- Create syntax for TABLE 'tencent'
CREATE TABLE `tencent` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增长id',
      `access_token` char(42) NOT NULL COMMENT '访问凭证',
      `refresh_token` char(42) NOT NULL COMMENT '刷新凭证',
      `openid` char(42) NOT NULL COMMENT '用户同意标识',
      `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
      PRIMARY KEY (`id`)
)
