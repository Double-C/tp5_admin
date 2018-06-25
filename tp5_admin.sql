/*
 Navicat Premium Data Transfer

 Source Server         : homestead
 Source Server Type    : MySQL
 Source Server Version : 50720
 Source Host           : localhost:33060
 Source Schema         : tp5_admin

 Target Server Type    : MySQL
 Target Server Version : 50720
 File Encoding         : 65001

 Date: 25/06/2018 16:11:13
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_users
-- ----------------------------
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号',
  `username` varchar(150) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否开启',
  `created_time` datetime DEFAULT NULL COMMENT '创建时间',
  `last_login_time` datetime DEFAULT NULL COMMENT '上次登录时间',
  `last_login_ip` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '上次登录ip',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of admin_users
-- ----------------------------
BEGIN;
INSERT INTO `admin_users` VALUES (1, 'admin', '超级管理员', 'ee5e1ae328b1cd9e6998effd8ca37ea5', 1, '2018-06-21 16:30:56', '2018-06-25 15:06:16', '192.168.1.1');
COMMIT;

-- ----------------------------
-- Table structure for auth_group
-- ----------------------------
DROP TABLE IF EXISTS `auth_group`;
CREATE TABLE `auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` char(80) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of auth_group
-- ----------------------------
BEGIN;
INSERT INTO `auth_group` VALUES (1, '超级管理员', 1, '1,2,4,5,6,11,3,13,14,15,16,17,18,19,7,20,21,22,23,24');
COMMIT;

-- ----------------------------
-- Table structure for auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `auth_group_access`;
CREATE TABLE `auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  `pid` int(10) NOT NULL DEFAULT '0' COMMENT '上级id;一级菜单默认为0',
  `sort` int(10) NOT NULL DEFAULT '0' COMMENT '排序，值越大排名越靠前',
  `icon` varchar(50) DEFAULT NULL COMMENT '图标',
  `created_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of auth_rule
-- ----------------------------
BEGIN;
INSERT INTO `auth_rule` VALUES (1, 'admin/System/default', '系统配置', 1, 1, '', 0, 0, 'fa fa-gears', '2018-06-21 17:54:33', '2018-06-21 17:54:38');
INSERT INTO `auth_rule` VALUES (2, 'admin/Menu/index', '菜单管理', 1, 1, '', 1, 0, NULL, NULL, NULL);
INSERT INTO `auth_rule` VALUES (3, 'admin/AuthGroup/index', '权限管理', 1, 1, '', 1, 0, '', NULL, NULL);
INSERT INTO `auth_rule` VALUES (4, 'admin/Menu/add', '添加菜单页面', 1, 0, '', 2, 0, '', NULL, NULL);
INSERT INTO `auth_rule` VALUES (5, 'admin/Menu/save', '添加菜单权限', 1, 0, '', 2, 0, '', NULL, NULL);
INSERT INTO `auth_rule` VALUES (6, 'admin/Memu/edit', '编辑菜单页面', 1, 0, '', 2, 0, '', NULL, NULL);
INSERT INTO `auth_rule` VALUES (7, 'admin/AdminUser/index', '用户管理', 1, 1, '', 1, 0, '', NULL, NULL);
INSERT INTO `auth_rule` VALUES (11, 'admin/Menu/update', '编辑菜单权限', 1, 0, '', 2, 0, '', NULL, NULL);
INSERT INTO `auth_rule` VALUES (13, 'admin/AuthGroup/add', '权限组添加页面', 1, 0, '', 3, 0, '', NULL, NULL);
INSERT INTO `auth_rule` VALUES (14, 'admin/AuthGroup/save', '权限组添加权限', 1, 0, '', 3, 0, '', NULL, NULL);
INSERT INTO `auth_rule` VALUES (15, 'admin/AuthGroup/edit', '权限组编辑页面', 1, 0, '', 3, 0, '', NULL, NULL);
INSERT INTO `auth_rule` VALUES (16, 'admin/AuthGroup/update', '权限组编辑权限', 1, 0, '', 3, 0, '', NULL, NULL);
INSERT INTO `auth_rule` VALUES (17, 'admin/AuthGroup/delete', '权限组删除权限', 1, 0, '', 3, 0, '', NULL, NULL);
INSERT INTO `auth_rule` VALUES (18, 'admin/AuthGroup/auth', '权限组授权页面', 1, 0, '', 3, 0, '', NULL, NULL);
INSERT INTO `auth_rule` VALUES (19, 'admin/AuthGroup/updateAuthGroupRule', '权限组授权权限', 1, 0, '', 3, 0, '', NULL, NULL);
INSERT INTO `auth_rule` VALUES (20, 'admin/AdminUser/add', '添加用户页面', 1, 0, '', 7, 0, '', NULL, NULL);
INSERT INTO `auth_rule` VALUES (21, 'admin/AdminUser/save', '添加用户权限', 1, 0, '', 7, 0, '', NULL, NULL);
INSERT INTO `auth_rule` VALUES (22, 'admin/AdminUser/edit', '编辑用户页面', 1, 0, '', 7, 0, '', NULL, NULL);
INSERT INTO `auth_rule` VALUES (23, 'admin/AdminUser/update', '编辑用户权限', 1, 0, '', 7, 0, '', NULL, NULL);
INSERT INTO `auth_rule` VALUES (24, 'admin/AdminUser/delete', '删除用户权限', 1, 0, '', 7, 0, '', NULL, NULL);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
