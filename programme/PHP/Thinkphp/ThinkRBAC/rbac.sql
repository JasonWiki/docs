-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 02 月 05 日 10:47
-- 服务器版本: 5.5.24-log
-- PHP 版本: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `rbac`
--

-- --------------------------------------------------------

--
-- 表的结构 `rbac_access`
--

CREATE TABLE IF NOT EXISTS `rbac_access` (
  `role_id` smallint(6) unsigned NOT NULL COMMENT '组id',
  `node_id` smallint(6) unsigned NOT NULL COMMENT '节点id',
  `level` tinyint(1) NOT NULL COMMENT '节点表等级',
  `pid` smallint(6) NOT NULL COMMENT '节点表pid',
  `module` varchar(50) DEFAULT NULL,
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='给组授权，让组有访问某个节点的能力';

--
-- 转存表中的数据 `rbac_access`
--

INSERT INTO `rbac_access` (`role_id`, `node_id`, `level`, `pid`, `module`) VALUES
(1, 5, 3, 3, NULL),
(1, 4, 3, 3, NULL),
(1, 3, 2, 1, NULL),
(1, 1, 1, 0, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `rbac_node`
--

CREATE TABLE IF NOT EXISTS `rbac_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '名称',
  `title` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '状态1启用0禁用',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `sort` smallint(6) unsigned DEFAULT NULL COMMENT '排序',
  `pid` smallint(6) unsigned NOT NULL COMMENT '父节点id',
  `level` tinyint(1) unsigned NOT NULL COMMENT '节点等级1项目2模块3功能',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `group_id` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='节点表(1项目、2模块、3方法)的关系' AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `rbac_node`
--

INSERT INTO `rbac_node` (`id`, `name`, `title`, `status`, `remark`, `sort`, `pid`, `level`, `type`, `group_id`) VALUES
(1, 'RBAC', NULL, 1, '项目名', NULL, 0, 1, 0, 0),
(2, 'User', NULL, 1, '用户模块', NULL, 1, 2, 0, 0),
(3, 'Index', NULL, 1, '主页模块', NULL, 1, 2, 0, 0),
(4, 'index', NULL, 1, 'Index模块下的index功能', NULL, 3, 3, 0, 0),
(5, 'update', NULL, 1, 'Index模块下的update功能', NULL, 3, 3, 0, 0),
(6, 'delete', NULL, 1, 'Index模块下的delete功能', NULL, 3, 3, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `rbac_role`
--

CREATE TABLE IF NOT EXISTS `rbac_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `ename` varchar(5) DEFAULT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parentId` (`pid`),
  KEY `ename` (`ename`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='组' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `rbac_role`
--

INSERT INTO `rbac_role` (`id`, `name`, `pid`, `status`, `remark`, `ename`, `create_time`, `update_time`) VALUES
(1, 'Index', 0, 1, '主页组', NULL, 0, 0),
(2, 'User', 0, 1, '用户组', NULL, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `rbac_role_user`
--

CREATE TABLE IF NOT EXISTS `rbac_role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL COMMENT '组id',
  `user_id` char(32) DEFAULT NULL COMMENT '用户id',
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='让用户隶属某个组';

--
-- 转存表中的数据 `rbac_role_user`
--

INSERT INTO `rbac_role_user` (`role_id`, `user_id`) VALUES
(1, '2'),
(1, '1'),
(1, '3');

-- --------------------------------------------------------

--
-- 表的结构 `rbac_user`
--

CREATE TABLE IF NOT EXISTS `rbac_user` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `password` char(32) NOT NULL,
  `last_login_time` int(11) unsigned DEFAULT '0',
  `last_login_ip` varchar(40) DEFAULT NULL,
  `login_count` mediumint(8) unsigned DEFAULT '0',
  `email` varchar(50) NOT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '用户状态1启用0禁用(默认为0)',
  `type_id` tinyint(2) unsigned DEFAULT '0' COMMENT '用户类型',
  `info` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户表' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `rbac_user`
--

INSERT INTO `rbac_user` (`id`, `username`, `nickname`, `password`, `last_login_time`, `last_login_ip`, `login_count`, `email`, `create_time`, `update_time`, `status`, `type_id`, `info`) VALUES
(1, 'admin', '管理员', '21232f297a57a5a743894a0e4a801fc3', 1360060580, '127.0.0.1', 2, 'zhanglin492103904@qq.com', 0, 0, 1, NULL, ''),
(2, 'user1', '用户一', '24c9e15e52afc47c225b757e7bee1f9d', 1360060966, '127.0.0.1', 2, '', 0, 0, 1, 0, ''),
(3, 'user2', '用户二', '7e58d63b60197ceb55a1c487989a3720', 1360060993, '127.0.0.1', NULL, '', 0, 0, 1, 0, '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
