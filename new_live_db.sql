/*
 Navicat Premium Data Transfer

 Source Server         : 127.0.0.1
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : tp5_nasi_live_db

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 06/04/2023 18:00:17
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for db_activity
-- ----------------------------
DROP TABLE IF EXISTS `db_activity`;
CREATE TABLE `db_activity`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `thumb_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `start_time` datetime(0) NULL DEFAULT NULL,
  `end_time` datetime(0) NULL DEFAULT NULL,
  `type` int(255) NULL DEFAULT NULL COMMENT '1-活动 2-公告',
  `create_time` datetime(0) NULL DEFAULT NULL,
  `modify_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_activity
-- ----------------------------
INSERT INTO `db_activity` VALUES (1, 'asdasdasdasd', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/20200723095318572411%402x.png', '<p>爱豆世纪啊三个和大家收到货，爱丽丝肯德基噶几是客户端，安康市大V安徽的少年。</p><p><br></p><p>爱空间设计稿大会开始缴纳。</p><p><br></p><p>阿克苏估计电话卡US你搭建。</p>', '2020-06-04 00:00:00', '2020-06-06 23:59:59', 1, '2020-06-06 11:33:34', '2020-06-06 16:33:34');
INSERT INTO `db_activity` VALUES (2, '测试公告', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020060615040978345%289F2AD%29.jpg', '<p>禁止黄赌毒禁止黄赌毒禁止黄赌毒禁止黄赌毒</p>', '2020-06-04 00:00:00', '2020-06-04 00:00:00', 2, '2020-06-06 15:04:12', '2020-06-06 15:04:12');
INSERT INTO `db_activity` VALUES (3, 'asdasdasdasd', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/20200723095345785672%402x.png', '<p>爱豆世纪啊三个和大家收到货，爱丽丝肯德基噶几是客户端，安康市大V安徽的少年。</p><p><br></p><p>爱空间设计稿大会开始缴纳。</p><p><br></p><p>阿克苏估计电话卡US你搭建。</p>', '2020-06-04 00:00:00', '2020-06-06 23:59:59', 1, '2020-06-06 11:33:34', '2020-06-06 15:33:34');
INSERT INTO `db_activity` VALUES (4, '测试公告', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020060615040978345%289F2AD%29.jpg', '<p>禁止黄赌毒禁止黄赌毒禁止黄赌毒禁止黄赌毒</p>', '2020-06-04 00:00:00', '2020-06-04 00:00:00', 2, '2020-06-06 15:04:12', '2020-06-06 15:04:12');
INSERT INTO `db_activity` VALUES (5, 'asdasdasdasd', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/20200723095357920713%402x.png', '<p>爱豆世纪啊三个和大家收到货，爱丽丝肯德基噶几是客户端，安康市大V安徽的少年。</p><p><br></p><p>爱空间设计稿大会开始缴纳。</p><p><br></p><p>阿克苏估计电话卡US你搭建。</p>', '2020-06-04 00:00:00', '2020-06-06 23:59:59', 1, '2020-06-06 11:33:34', '2020-06-06 15:33:34');
INSERT INTO `db_activity` VALUES (6, '测试公告', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020060615040978345%289F2AD%29.jpg', '<p>禁止黄赌毒禁止黄赌毒禁止黄赌毒禁止黄赌毒</p>', '2020-06-04 00:00:00', '2020-06-04 00:00:00', 2, '2020-06-06 15:04:12', '2020-06-06 15:04:12');
INSERT INTO `db_activity` VALUES (7, '充值有礼，主播限时送祝福', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/20200723095357920713%402x.png', '<p>充值有礼，主播限时送祝福</p><p><br></p><p>充值有礼，主播限时送祝福充值有礼，主播限时送祝福充值有礼，主播限时送祝福充值有礼，主播限时送祝福充值有礼，主播限时送祝福</p><p><br></p><p>充值有礼，主播限时送祝福充值有礼，主播限时送祝福充值有礼，主播限时送祝福充值有礼，主播限时送祝福充值有礼，主播限时送祝福充值有礼，主播限时送祝福充值有礼，主播限时送祝福。</p>', '2020-06-04 00:00:00', '2020-06-06 23:59:59', 1, '2020-06-06 17:33:34', '2020-06-06 15:05:34');

-- ----------------------------
-- Table structure for db_admin
-- ----------------------------
DROP TABLE IF EXISTS `db_admin`;
CREATE TABLE `db_admin`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `account` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `roleid` int(10) NULL DEFAULT NULL,
  `create_time` datetime(0) NULL DEFAULT NULL,
  `status` int(1) NULL DEFAULT 1 COMMENT '1-正常 0-禁用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_admin
-- ----------------------------
INSERT INTO `db_admin` VALUES (1, '超管', 'admin', 'vVR9zE3wyDrZw+yWLDzGZg==', 'MG9JBYVDFTTWKKKYFJCI9PAMR7/LL//Z', 1, '2022-04-10 02:52:07', 1);

-- ----------------------------
-- Table structure for db_admin_auth
-- ----------------------------
DROP TABLE IF EXISTS `db_admin_auth`;
CREATE TABLE `db_admin_auth`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `parentid` int(10) NULL DEFAULT 0,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `status` int(1) NULL DEFAULT 1 COMMENT '0-无效 1-有效',
  `sort` int(10) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 76 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_admin_auth
-- ----------------------------
INSERT INTO `db_admin_auth` VALUES (1, '', 0, '总览', 'layui-icon-home', 1, 0);
INSERT INTO `db_admin_auth` VALUES (2, '/admin/index/home', 1, '网站概况', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (3, NULL, 0, '用户管理', 'layui-icon-user', 1, 1);
INSERT INTO `db_admin_auth` VALUES (4, '/admin/user/index', 3, '用户列表', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (5, '/admin/user/add', 3, '添加用户', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (6, NULL, 0, '主播管理', 'layui-icon-auz', 1, 2);
INSERT INTO `db_admin_auth` VALUES (7, '/admin/auth/index', 6, '身份认证', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (8, NULL, 0, '直播管理', 'layui-icon-video', 1, 3);
INSERT INTO `db_admin_auth` VALUES (9, '/admin/live/add', 8, '新建直播', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (10, '/admin/live/index', 8, '直播列表', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (11, '/admin/live/category', 8, '直播分类', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (12, '/admin/live/logs', 8, '直播记录', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (13, NULL, 0, '短视频管理', 'layui-icon-play', 1, 4);
INSERT INTO `db_admin_auth` VALUES (14, '/admin/shortvideo/index', 13, '短视频列表', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (15, '/admin/shortvideo/add', 13, '添加短视频', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (16, '/admin/shortvideo/report', 13, '举报列表', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (17, NULL, 0, '动态管理', 'layui-icon-carousel', 1, 5);
INSERT INTO `db_admin_auth` VALUES (18, '/admin/moment/index', 17, '动态列表', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (19, '/admin/moment/add', 17, '添加动态', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (20, '/admin/moment/report', 17, '举报列表', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (21, '', 0, '商品管理', 'layui-icon-cart', 1, 8);
INSERT INTO `db_admin_auth` VALUES (22, '/admin/gift/index', 21, '礼物管理', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (23, '/admin/gold/index', 21, '金币价格', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (24, '/admin/vip/index', 21, 'VIP价格', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (25, NULL, 0, '财务管理', 'layui-icon-rmb', 1, 7);
INSERT INTO `db_admin_auth` VALUES (26, '/admin/order/index', 25, '订单管理', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (27, '/admin/withdrawals/index', 25, '提现申请', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (28, '/admin/profit/index', 25, '收支明细', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (29, '/admin/gift/sendlog', 25, '赠礼记录', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (30, '/admin/charge/manual', 25, '手动充值', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (31, NULL, 0, '等级管理', 'layui-icon-upload-circle', 1, 9);
INSERT INTO `db_admin_auth` VALUES (32, '/admin/level/pointlevel', 31, '经验等级', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (33, '/admin/level/starlevel', 31, '主播星级', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (34, NULL, 0, '推荐管理', 'layui-icon-rate', 1, 10);
INSERT INTO `db_admin_auth` VALUES (35, '/admin/userrec/index', 34, '主播推荐', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (36, NULL, 0, '公会管理', 'layui-icon-face-surprised', 1, 11);
INSERT INTO `db_admin_auth` VALUES (37, '/admin/guild/index', 36, '公会列表', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (38, '/admin/guild/add', 36, '创建公会', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (39, NULL, 0, '分销管理', 'layui-icon-release', 1, 12);
INSERT INTO `db_admin_auth` VALUES (40, '/admin/agent/index', 39, '代理列表', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (41, NULL, 0, '广告管理', 'layui-icon-fire', 1, 13);
INSERT INTO `db_admin_auth` VALUES (42, '/admin/ads/appindex', 41, 'APP广告配置', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (43, NULL, 0, '活动管理', 'layui-icon-theme', 1, 14);
INSERT INTO `db_admin_auth` VALUES (44, '/admin/activity/add', 43, '添加活动', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (45, '/admin/activity/index', 43, '活动管理', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (46, NULL, 0, '消息管理', 'layui-icon-notice', 1, 15);
INSERT INTO `db_admin_auth` VALUES (47, '/admin/push/index', 46, '推送管理', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (48, '/admin/smscode/index', 46, '验证码管理', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (49, NULL, 0, '系统设置', 'layui-icon-set-sm', 1, 19);
INSERT INTO `db_admin_auth` VALUES (50, '/admin/setting/tag', 74, '用户标签', '', 1, 0);
INSERT INTO `db_admin_auth` VALUES (52, '/admin/setting/menu', 49, '后台目录管理', NULL, 0, 0);
INSERT INTO `db_admin_auth` VALUES (53, '/admin/setting/indexpb', 49, '公共配置', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (54, '/admin/setting/indexpri', 49, '私密配置', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (55, '/admin/admin/index', 3, '管理员列表', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (56, '/admin/agent/withdrawals', 39, '提现申请', NULL, 1, 0);
INSERT INTO `db_admin_auth` VALUES (57, '', 0, '权限管理', 'layui-icon-auz', 1, 17);
INSERT INTO `db_admin_auth` VALUES (58, '/admin/role/index', 57, '权限列表', '', 1, 1);
INSERT INTO `db_admin_auth` VALUES (59, '/admin/role/role', 57, '角色管理', '', 1, 0);
INSERT INTO `db_admin_auth` VALUES (64, '/admin/user/report', 6, '举报列表', '', 1, 1);
INSERT INTO `db_admin_auth` VALUES (65, '/admin/live/dashboard', 8, '直播大屏', '', 1, 5);
INSERT INTO `db_admin_auth` VALUES (66, '', 0, '内容管理', 'layui-icon-form', 1, 16);
INSERT INTO `db_admin_auth` VALUES (67, '/admin/protocal/index', 66, '用户协议', '', 1, 1);
INSERT INTO `db_admin_auth` VALUES (68, '', 0, '商城管理', 'layui-icon-cart-simple', 1, 6);
INSERT INTO `db_admin_auth` VALUES (69, '/admin/shop/shop', 68, '店铺管理', '', 1, 0);
INSERT INTO `db_admin_auth` VALUES (70, '/admin/shop/goods', 68, '商品管理', '', 1, 1);
INSERT INTO `db_admin_auth` VALUES (71, '/admin/shop/order', 68, '订单管理', '', 1, 2);
INSERT INTO `db_admin_auth` VALUES (72, '/admin/shop/withdrawals', 68, '提现管理', '', 1, 3);
INSERT INTO `db_admin_auth` VALUES (73, '/admin/guild/withdrawals', 36, '提现申请', '', 1, 1);
INSERT INTO `db_admin_auth` VALUES (74, '', 0, '类目管理', 'layui-icon-form', 1, 18);
INSERT INTO `db_admin_auth` VALUES (75, '/admin/topic/index', 74, '话题管理', '', 1, 1);

-- ----------------------------
-- Table structure for db_admin_log
-- ----------------------------
DROP TABLE IF EXISTS `db_admin_log`;
CREATE TABLE `db_admin_log`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `adminid` int(10) NOT NULL,
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_admin_log
-- ----------------------------

-- ----------------------------
-- Table structure for db_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `db_admin_role`;
CREATE TABLE `db_admin_role`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` int(1) NULL DEFAULT 1,
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `is_visitor` int(1) NULL DEFAULT 0 COMMENT '1-仅有浏览权限',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_admin_role
-- ----------------------------
INSERT INTO `db_admin_role` VALUES (1, '超级管理员', 1, '全部权限', 0);
INSERT INTO `db_admin_role` VALUES (2, '访客', 1, '仅开放浏览权限，无法编辑，新增，删除', 1);
INSERT INTO `db_admin_role` VALUES (4, '1', 1, '测试', 0);
INSERT INTO `db_admin_role` VALUES (5, 'test', 1, '测试账号', 0);

-- ----------------------------
-- Table structure for db_admin_role_auth
-- ----------------------------
DROP TABLE IF EXISTS `db_admin_role_auth`;
CREATE TABLE `db_admin_role_auth`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `roleid` int(10) NULL DEFAULT NULL,
  `authid` int(10) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1210 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_admin_role_auth
-- ----------------------------
INSERT INTO `db_admin_role_auth` VALUES (1076, 2, 1);
INSERT INTO `db_admin_role_auth` VALUES (1077, 2, 2);
INSERT INTO `db_admin_role_auth` VALUES (1078, 2, 3);
INSERT INTO `db_admin_role_auth` VALUES (1079, 2, 4);
INSERT INTO `db_admin_role_auth` VALUES (1080, 2, 5);
INSERT INTO `db_admin_role_auth` VALUES (1081, 2, 55);
INSERT INTO `db_admin_role_auth` VALUES (1082, 2, 6);
INSERT INTO `db_admin_role_auth` VALUES (1083, 2, 7);
INSERT INTO `db_admin_role_auth` VALUES (1084, 2, 8);
INSERT INTO `db_admin_role_auth` VALUES (1085, 2, 9);
INSERT INTO `db_admin_role_auth` VALUES (1086, 2, 10);
INSERT INTO `db_admin_role_auth` VALUES (1087, 2, 11);
INSERT INTO `db_admin_role_auth` VALUES (1088, 2, 12);
INSERT INTO `db_admin_role_auth` VALUES (1089, 2, 65);
INSERT INTO `db_admin_role_auth` VALUES (1090, 2, 13);
INSERT INTO `db_admin_role_auth` VALUES (1091, 2, 14);
INSERT INTO `db_admin_role_auth` VALUES (1092, 2, 15);
INSERT INTO `db_admin_role_auth` VALUES (1093, 2, 16);
INSERT INTO `db_admin_role_auth` VALUES (1094, 2, 17);
INSERT INTO `db_admin_role_auth` VALUES (1095, 2, 18);
INSERT INTO `db_admin_role_auth` VALUES (1096, 2, 19);
INSERT INTO `db_admin_role_auth` VALUES (1097, 2, 20);
INSERT INTO `db_admin_role_auth` VALUES (1098, 2, 68);
INSERT INTO `db_admin_role_auth` VALUES (1099, 2, 69);
INSERT INTO `db_admin_role_auth` VALUES (1100, 2, 70);
INSERT INTO `db_admin_role_auth` VALUES (1101, 2, 71);
INSERT INTO `db_admin_role_auth` VALUES (1102, 2, 72);
INSERT INTO `db_admin_role_auth` VALUES (1103, 2, 25);
INSERT INTO `db_admin_role_auth` VALUES (1104, 2, 26);
INSERT INTO `db_admin_role_auth` VALUES (1105, 2, 27);
INSERT INTO `db_admin_role_auth` VALUES (1106, 2, 28);
INSERT INTO `db_admin_role_auth` VALUES (1107, 2, 29);
INSERT INTO `db_admin_role_auth` VALUES (1108, 2, 30);
INSERT INTO `db_admin_role_auth` VALUES (1109, 2, 21);
INSERT INTO `db_admin_role_auth` VALUES (1110, 2, 22);
INSERT INTO `db_admin_role_auth` VALUES (1111, 2, 23);
INSERT INTO `db_admin_role_auth` VALUES (1112, 2, 24);
INSERT INTO `db_admin_role_auth` VALUES (1113, 2, 31);
INSERT INTO `db_admin_role_auth` VALUES (1114, 2, 32);
INSERT INTO `db_admin_role_auth` VALUES (1115, 2, 33);
INSERT INTO `db_admin_role_auth` VALUES (1116, 2, 34);
INSERT INTO `db_admin_role_auth` VALUES (1117, 2, 35);
INSERT INTO `db_admin_role_auth` VALUES (1118, 2, 36);
INSERT INTO `db_admin_role_auth` VALUES (1119, 2, 37);
INSERT INTO `db_admin_role_auth` VALUES (1120, 2, 38);
INSERT INTO `db_admin_role_auth` VALUES (1121, 2, 39);
INSERT INTO `db_admin_role_auth` VALUES (1122, 2, 40);
INSERT INTO `db_admin_role_auth` VALUES (1123, 2, 56);
INSERT INTO `db_admin_role_auth` VALUES (1124, 2, 41);
INSERT INTO `db_admin_role_auth` VALUES (1125, 2, 42);
INSERT INTO `db_admin_role_auth` VALUES (1126, 2, 43);
INSERT INTO `db_admin_role_auth` VALUES (1127, 2, 44);
INSERT INTO `db_admin_role_auth` VALUES (1128, 2, 45);
INSERT INTO `db_admin_role_auth` VALUES (1129, 2, 46);
INSERT INTO `db_admin_role_auth` VALUES (1130, 2, 47);
INSERT INTO `db_admin_role_auth` VALUES (1131, 2, 48);
INSERT INTO `db_admin_role_auth` VALUES (1132, 2, 57);
INSERT INTO `db_admin_role_auth` VALUES (1133, 2, 59);
INSERT INTO `db_admin_role_auth` VALUES (1134, 2, 58);
INSERT INTO `db_admin_role_auth` VALUES (1135, 2, 74);
INSERT INTO `db_admin_role_auth` VALUES (1136, 2, 50);
INSERT INTO `db_admin_role_auth` VALUES (1137, 2, 49);
INSERT INTO `db_admin_role_auth` VALUES (1138, 2, 53);
INSERT INTO `db_admin_role_auth` VALUES (1139, 4, 1);
INSERT INTO `db_admin_role_auth` VALUES (1140, 4, 2);
INSERT INTO `db_admin_role_auth` VALUES (1141, 5, 1);
INSERT INTO `db_admin_role_auth` VALUES (1142, 5, 2);
INSERT INTO `db_admin_role_auth` VALUES (1143, 5, 3);
INSERT INTO `db_admin_role_auth` VALUES (1144, 5, 4);
INSERT INTO `db_admin_role_auth` VALUES (1145, 5, 5);
INSERT INTO `db_admin_role_auth` VALUES (1146, 5, 55);
INSERT INTO `db_admin_role_auth` VALUES (1147, 5, 6);
INSERT INTO `db_admin_role_auth` VALUES (1148, 5, 7);
INSERT INTO `db_admin_role_auth` VALUES (1149, 5, 64);
INSERT INTO `db_admin_role_auth` VALUES (1150, 5, 8);
INSERT INTO `db_admin_role_auth` VALUES (1151, 5, 9);
INSERT INTO `db_admin_role_auth` VALUES (1152, 5, 10);
INSERT INTO `db_admin_role_auth` VALUES (1153, 5, 11);
INSERT INTO `db_admin_role_auth` VALUES (1154, 5, 12);
INSERT INTO `db_admin_role_auth` VALUES (1155, 5, 65);
INSERT INTO `db_admin_role_auth` VALUES (1156, 5, 13);
INSERT INTO `db_admin_role_auth` VALUES (1157, 5, 14);
INSERT INTO `db_admin_role_auth` VALUES (1158, 5, 15);
INSERT INTO `db_admin_role_auth` VALUES (1159, 5, 16);
INSERT INTO `db_admin_role_auth` VALUES (1160, 5, 17);
INSERT INTO `db_admin_role_auth` VALUES (1161, 5, 18);
INSERT INTO `db_admin_role_auth` VALUES (1162, 5, 19);
INSERT INTO `db_admin_role_auth` VALUES (1163, 5, 20);
INSERT INTO `db_admin_role_auth` VALUES (1164, 5, 68);
INSERT INTO `db_admin_role_auth` VALUES (1165, 5, 69);
INSERT INTO `db_admin_role_auth` VALUES (1166, 5, 70);
INSERT INTO `db_admin_role_auth` VALUES (1167, 5, 71);
INSERT INTO `db_admin_role_auth` VALUES (1168, 5, 72);
INSERT INTO `db_admin_role_auth` VALUES (1169, 5, 25);
INSERT INTO `db_admin_role_auth` VALUES (1170, 5, 26);
INSERT INTO `db_admin_role_auth` VALUES (1171, 5, 27);
INSERT INTO `db_admin_role_auth` VALUES (1172, 5, 28);
INSERT INTO `db_admin_role_auth` VALUES (1173, 5, 29);
INSERT INTO `db_admin_role_auth` VALUES (1174, 5, 30);
INSERT INTO `db_admin_role_auth` VALUES (1175, 5, 21);
INSERT INTO `db_admin_role_auth` VALUES (1176, 5, 22);
INSERT INTO `db_admin_role_auth` VALUES (1177, 5, 23);
INSERT INTO `db_admin_role_auth` VALUES (1178, 5, 24);
INSERT INTO `db_admin_role_auth` VALUES (1179, 5, 31);
INSERT INTO `db_admin_role_auth` VALUES (1180, 5, 32);
INSERT INTO `db_admin_role_auth` VALUES (1181, 5, 33);
INSERT INTO `db_admin_role_auth` VALUES (1182, 5, 34);
INSERT INTO `db_admin_role_auth` VALUES (1183, 5, 35);
INSERT INTO `db_admin_role_auth` VALUES (1184, 5, 36);
INSERT INTO `db_admin_role_auth` VALUES (1185, 5, 37);
INSERT INTO `db_admin_role_auth` VALUES (1186, 5, 38);
INSERT INTO `db_admin_role_auth` VALUES (1187, 5, 73);
INSERT INTO `db_admin_role_auth` VALUES (1188, 5, 39);
INSERT INTO `db_admin_role_auth` VALUES (1189, 5, 40);
INSERT INTO `db_admin_role_auth` VALUES (1190, 5, 56);
INSERT INTO `db_admin_role_auth` VALUES (1191, 5, 41);
INSERT INTO `db_admin_role_auth` VALUES (1192, 5, 42);
INSERT INTO `db_admin_role_auth` VALUES (1193, 5, 43);
INSERT INTO `db_admin_role_auth` VALUES (1194, 5, 44);
INSERT INTO `db_admin_role_auth` VALUES (1195, 5, 45);
INSERT INTO `db_admin_role_auth` VALUES (1196, 5, 46);
INSERT INTO `db_admin_role_auth` VALUES (1197, 5, 47);
INSERT INTO `db_admin_role_auth` VALUES (1198, 5, 48);
INSERT INTO `db_admin_role_auth` VALUES (1199, 5, 66);
INSERT INTO `db_admin_role_auth` VALUES (1200, 5, 67);
INSERT INTO `db_admin_role_auth` VALUES (1201, 5, 57);
INSERT INTO `db_admin_role_auth` VALUES (1202, 5, 59);
INSERT INTO `db_admin_role_auth` VALUES (1203, 5, 58);
INSERT INTO `db_admin_role_auth` VALUES (1204, 5, 74);
INSERT INTO `db_admin_role_auth` VALUES (1205, 5, 50);
INSERT INTO `db_admin_role_auth` VALUES (1206, 5, 75);
INSERT INTO `db_admin_role_auth` VALUES (1207, 5, 49);
INSERT INTO `db_admin_role_auth` VALUES (1208, 5, 53);
INSERT INTO `db_admin_role_auth` VALUES (1209, 5, 54);

-- ----------------------------
-- Table structure for db_ads
-- ----------------------------
DROP TABLE IF EXISTS `db_ads`;
CREATE TABLE `db_ads`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `jump_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '跳转链接',
  `jump_type` int(1) NULL DEFAULT NULL COMMENT '1-app内部跳转 2-外部浏览器跳转',
  `type` int(1) NULL DEFAULT NULL COMMENT '1-启动页广告 2-首页轮播广告 3-首页弹窗广告 4-短视频广告 5-用户动态穿插广告 ',
  `create_time` datetime(0) NULL DEFAULT NULL,
  `status` int(1) NULL DEFAULT 1 COMMENT '1-生效 0-失效',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_ads
-- ----------------------------
INSERT INTO `db_ads` VALUES (1, '官网', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020121913360553333%E5%B9%BF%E5%91%8A%E9%A1%B51242.jpg', 'https://www.nasinet.com', 1, 1, '2019-12-12 10:33:23', 1);
INSERT INTO `db_ads` VALUES (2, '官网', 'https://fengzq-1300119863.cos.ap-shanghai.myqcloud.com/images/2020042601453543644davis.jpg', 'https://www.nasinet.com', 2, 5, '2019-12-12 10:33:23', 1);
INSERT INTO `db_ads` VALUES (3, '首页轮播1', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/20200724171549132211.jpg', 'http://www.nasinet.com', 2, 2, '2019-12-09 18:14:53', 1);
INSERT INTO `db_ads` VALUES (4, '钠斯科技创立之初，专注于音视频直播系统开发，以音视频直播平台发展及软件产品、解决方案的研发和服务为主，为客户打造多元化的直播系统。  依托多年的互联网核心技术沉淀，为创业者与企业提供符合企业“互联网+”战略的软件产品设计、开发以及解决方案，以技术创新驱动企业可持续发展。', '', 'https://www.nasinet.com', 1, 3, '2019-12-10 16:08:14', 1);
INSERT INTO `db_ads` VALUES (5, '首页轮播2', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/20200724171601366072.jpg', 'http://www.nasinet.com', 1, 2, '2019-12-09 18:14:53', 1);
INSERT INTO `db_ads` VALUES (8, '首页轮播3', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/20200724171618470473.jpg', 'http://www.nasinet.com', 1, 2, '2020-07-24 17:16:35', 1);

-- ----------------------------
-- Table structure for db_agent
-- ----------------------------
DROP TABLE IF EXISTS `db_agent`;
CREATE TABLE `db_agent`  (
  `uid` int(11) NOT NULL,
  `profit` decimal(11, 2) NULL DEFAULT NULL COMMENT '可提现收益',
  `total_profit` decimal(11, 2) NULL DEFAULT NULL COMMENT '累计收益 /元',
  `invite_code` char(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '邀请码 6位固定',
  PRIMARY KEY (`uid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_agent
-- ----------------------------

-- ----------------------------
-- Table structure for db_agent_profit
-- ----------------------------
DROP TABLE IF EXISTS `db_agent_profit`;
CREATE TABLE `db_agent_profit`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `agentid` int(10) NULL DEFAULT NULL,
  `profit` decimal(10, 2) NULL DEFAULT NULL COMMENT '收益',
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '说明',
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_agent_profit
-- ----------------------------

-- ----------------------------
-- Table structure for db_agent_withdrawals
-- ----------------------------
DROP TABLE IF EXISTS `db_agent_withdrawals`;
CREATE TABLE `db_agent_withdrawals`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL,
  `cash` decimal(10, 2) NULL DEFAULT NULL COMMENT '提取金额',
  `alipay_account` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '收款支付宝账号',
  `alipay_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '收款人支付宝姓名',
  `create_time` datetime(0) NULL DEFAULT NULL COMMENT '申请提现时间',
  `operate_time` datetime(0) NULL DEFAULT NULL COMMENT '处理时间',
  `status` int(1) NULL DEFAULT 0 COMMENT '0-未处理 1-已提现 2-已拒绝(钻石返还) 3-异常单(扣除金币)',
  `trade_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '转账订单号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_agent_withdrawals
-- ----------------------------

-- ----------------------------
-- Table structure for db_anchor_fans
-- ----------------------------
DROP TABLE IF EXISTS `db_anchor_fans`;
CREATE TABLE `db_anchor_fans`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `anchorid` int(10) NULL DEFAULT NULL COMMENT '被关注者id（主播id）',
  `fansid` int(10) NULL DEFAULT NULL COMMENT '关注者id',
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_anchor_fans
-- ----------------------------

-- ----------------------------
-- Table structure for db_anchor_guardian
-- ----------------------------
DROP TABLE IF EXISTS `db_anchor_guardian`;
CREATE TABLE `db_anchor_guardian`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `anchorid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `type` int(1) NULL DEFAULT 2 COMMENT '0-年 1-月 2-周',
  `expire_time` datetime(0) NULL DEFAULT NULL COMMENT '失效时间',
  `effective_time` datetime(0) NULL DEFAULT NULL COMMENT '生效时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_anchor_guardian
-- ----------------------------

-- ----------------------------
-- Table structure for db_anchor_income
-- ----------------------------
DROP TABLE IF EXISTS `db_anchor_income`;
CREATE TABLE `db_anchor_income`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `anchorid` int(10) NULL DEFAULT NULL COMMENT '主播id',
  `income` int(10) NULL DEFAULT 0 COMMENT '当日收入钻石',
  `date` date NULL DEFAULT NULL COMMENT '日期 2020-02-02',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_anchor_income
-- ----------------------------

-- ----------------------------
-- Table structure for db_anchor_level_rule
-- ----------------------------
DROP TABLE IF EXISTS `db_anchor_level_rule`;
CREATE TABLE `db_anchor_level_rule`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `level` int(1) NULL DEFAULT NULL COMMENT '星级 1-5',
  `point` int(10) NULL DEFAULT NULL COMMENT '经验值',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_anchor_level_rule
-- ----------------------------
INSERT INTO `db_anchor_level_rule` VALUES (1, 3, 0);
INSERT INTO `db_anchor_level_rule` VALUES (2, 4, 600);
INSERT INTO `db_anchor_level_rule` VALUES (3, 5, 1000);
INSERT INTO `db_anchor_level_rule` VALUES (4, 6, 2000);
INSERT INTO `db_anchor_level_rule` VALUES (5, 7, 3000);
INSERT INTO `db_anchor_level_rule` VALUES (6, 8, 4000);
INSERT INTO `db_anchor_level_rule` VALUES (7, 9, 5000);
INSERT INTO `db_anchor_level_rule` VALUES (8, 10, 6000);
INSERT INTO `db_anchor_level_rule` VALUES (9, 11, 7000);
INSERT INTO `db_anchor_level_rule` VALUES (10, 12, 8000);
INSERT INTO `db_anchor_level_rule` VALUES (11, 13, 9000);
INSERT INTO `db_anchor_level_rule` VALUES (12, 14, 10000);
INSERT INTO `db_anchor_level_rule` VALUES (13, 15, 11000);
INSERT INTO `db_anchor_level_rule` VALUES (14, 16, 12000);
INSERT INTO `db_anchor_level_rule` VALUES (15, 17, 13000);
INSERT INTO `db_anchor_level_rule` VALUES (16, 18, 14000);
INSERT INTO `db_anchor_level_rule` VALUES (17, 19, 15000);
INSERT INTO `db_anchor_level_rule` VALUES (18, 20, 16000);

-- ----------------------------
-- Table structure for db_anchor_report
-- ----------------------------
DROP TABLE IF EXISTS `db_anchor_report`;
CREATE TABLE `db_anchor_report`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL,
  `anchorid` int(10) NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '举报内容',
  `create_time` datetime(0) NULL DEFAULT NULL,
  `img_urls` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_anchor_report
-- ----------------------------

-- ----------------------------
-- Table structure for db_anchor_withdrawals
-- ----------------------------
DROP TABLE IF EXISTS `db_anchor_withdrawals`;
CREATE TABLE `db_anchor_withdrawals`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL,
  `diamond` int(10) NULL DEFAULT NULL COMMENT '扣除钻石数量',
  `cash` decimal(10, 2) NULL DEFAULT NULL COMMENT '提取金额',
  `alipay_account` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '收款支付宝账号',
  `alipay_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '收款人支付宝姓名',
  `create_time` datetime(0) NULL DEFAULT NULL COMMENT '申请提现时间',
  `operate_time` datetime(0) NULL DEFAULT NULL COMMENT '处理时间',
  `status` int(1) NULL DEFAULT 0 COMMENT '0-未处理 1-已提现 2-已拒绝(钻石返还) 3-异常单(扣除金币)',
  `trade_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '转账订单号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_anchor_withdrawals
-- ----------------------------

-- ----------------------------
-- Table structure for db_config_pri
-- ----------------------------
DROP TABLE IF EXISTS `db_config_pri`;
CREATE TABLE `db_config_pri`  (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `qcloud_appid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '腾讯云appid',
  `qcloud_secretid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '腾讯云secretid',
  `qcloud_secretkey` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '腾讯云secretkey',
  `im_sdkappid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '腾讯云 IM SDKAppId',
  `im_sdksecret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '腾讯云 IM  SDK secretkey',
  `push_domain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '推流地址',
  `pull_domain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '播流地址',
  `push_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '腾讯云直播推流防盗链key',
  `qsms_appid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '腾讯云短信appid',
  `qsms_appkey` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '腾讯云短信key',
  `qsms_tplid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '腾讯云短信模板id',
  `qsms_sign` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '腾讯云短信签名',
  `txim_admin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '腾讯IM管理员账号',
  `txim_broadcast` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '腾讯IM广播账号',
  `txim_notify_zan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '腾讯IM通知账号-点赞收藏',
  `txim_notify_comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '腾讯IM通知账号-评论',
  `txim_notify_attent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '腾讯IM通知账号-关注',
  `cos_bucket` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '腾讯云存储cos bucket',
  `cos_region` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '腾讯云存储region',
  `cos_appfolder` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '腾讯云存储app授权文件夹',
  `jpush_appkey` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '极光推送appkey',
  `jpush_master_secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '极光推送master_seckey',
  `wx_appid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '微信开放平台 appid',
  `wx_secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '微信开放平台 secret',
  `wx_mchid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '微信商户id',
  `wx_key` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '微信支付key',
  `universal_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'UNIVERSAL_LINK',
  `qq_appid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'QQ互联 appid',
  `qq_appkey` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'QQ互联 appkey',
  `pay_notify_domain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '支付回调地址域名',
  `socket_server` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'socket服务地址',
  `agent_ratio` int(3) NULL DEFAULT NULL COMMENT '代理返佣比例(%)',
  `cos_folder_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图片存放文件夹',
  `cos_folder_blurimage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '高斯模糊图片文件夹',
  `exchange_rate` int(10) NULL DEFAULT 100 COMMENT '钻石人民币汇率 默认 钻石:人民币 = 100:1',
  `withdraw_min` int(10) NULL DEFAULT 0 COMMENT '单次最低提现金额 /元',
  `switch_shortvideo_check` int(1) NULL DEFAULT 0 COMMENT '短视频审核开关 1-需要审核',
  `switch_moment_check` int(1) NULL DEFAULT 0 COMMENT '动态审核开关 1-需要审核',
  `tisdk_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '拓幻美颜key',
  `beauty_channel` int(1) NULL DEFAULT 0 COMMENT '0-腾讯云基础美颜 1-TiSDK',
  `alipay_appid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '支付宝appid',
  `alipay_prikey` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '支付宝私钥',
  `alipay_pubkey` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '支付宝公钥',
  `sharesdk_appkey` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'sharesdk appkey\n',
  `sharesdk_appsecret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'sharesdk appsecret',
  `switch_iap` int(1) NULL DEFAULT 0 COMMENT '1-开启苹果内购',
  `kuaidi100_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '快递100 key',
  `kuaidi100_customer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '快递100 customer',
  `shop_commission` int(3) NULL DEFAULT 0 COMMENT '提现手续费比例 %',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_config_pri
-- ----------------------------
INSERT INTO `db_config_pri` VALUES (1, '', '', '', '', '', '', '', '', '', '', '', '钠斯网络', 'admin', 'broadcast', NULL, NULL, NULL, 'live-1300631461', 'ap-shanghai', 'app_files', '', '', '', '', '', '', '', '', '', '', '', 6, 'images', '', 10, 1, 1, 1, '', 1, '', '', '', '', '', 0, '', '', 5);

-- ----------------------------
-- Table structure for db_config_pub
-- ----------------------------
DROP TABLE IF EXISTS `db_config_pub`;
CREATE TABLE `db_config_pub`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `site_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `site_domain` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `copyright` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `version_android` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `dl_android` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `update_info_android` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `version_ios` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `dl_ios` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `update_info_ios` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `dl_qrcode` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `dl_web_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '下载页',
  `share_live_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '分享直播页',
  `share_shortvideo_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '分享短视频页',
  `share_moment_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '分享动态页',
  `room_notice` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '直播间公告',
  `service_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '客服邮箱',
  `service_phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '客服电话',
  `service_qq` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '客服QQ',
  `service_wechat` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '客服微信',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_config_pub
-- ----------------------------
INSERT INTO `db_config_pub` VALUES (1, '钠斯直播后台管理系统', 'https://live.nasinet.com', '钠斯网络© 2017-2022 nasinet.com , All rights reserved.', '3.2.1', 'https://download-1300631461.cos.ap-shanghai.myqcloud.com/nasilive_v3.3.1_28_321_jiagu_sign.apk', 'v3.2.1 更新 1. UI效果升级 2. PK动画升级 3. 守护功能上线 4. 全频道礼物，守护专属礼物', '3.2.0', 'https://testflight.apple.com/join/8AL8QZvO', 'v3.2.0 更新 1. UI效果升级 2. PK动画升级 3. 守护功能上线 4. 全频道礼物，守护专属礼物', 'https://video-1303209584.cos.ap-hongkong.myqcloud.com/qiyweweix.png', 'https://live.nasinet.com/download/', 'https://live.nasinet.com/h5/share/live/', 'https://live.nasinet.com/h5/share/video/', 'https://live.nasinet.com/h5/share/dynamic/', '禁止发布任何色情、暴力、低俗、赌博等不良信息！禁止在直播间发布手机号码、QQ、微信等联系方式！否则将被永久封禁并上报公安部门。', 'contact@nasinet.com', '189-0860-5871', '245792062', null);

-- ----------------------------
-- Table structure for db_config_tag
-- ----------------------------
DROP TABLE IF EXISTS `db_config_tag`;
CREATE TABLE `db_config_tag`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '标签颜色 格式#000000',
  `point` int(10) NULL DEFAULT 0 COMMENT '分值',
  `type` int(1) NULL DEFAULT 1 COMMENT '0-减分 1-加分',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_config_tag
-- ----------------------------
INSERT INTO `db_config_tag` VALUES (1, '小仙女', '#f14578', 10, 1);
INSERT INTO `db_config_tag` VALUES (2, '性感小猫', '#f8a619', 10, 1);
INSERT INTO `db_config_tag` VALUES (4, '照骗', '#a3a6a9', 10, 0);

-- ----------------------------
-- Table structure for db_gift
-- ----------------------------
DROP TABLE IF EXISTS `db_gift`;
CREATE TABLE `db_gift`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `animation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '动画地址',
  `duration` int(10) NULL DEFAULT 1000 COMMENT '动画时长 单位毫秒',
  `price` int(10) NULL DEFAULT NULL,
  `type` int(1) NULL DEFAULT 0 COMMENT '动画类型 0-普通礼物 1-豪华礼物',
  `animat_type` int(1) NULL DEFAULT 0 COMMENT '动画类型 1-gif 2-svga',
  `use_type` int(1) NULL DEFAULT 0 COMMENT '0-普通类型 1-全频道2-守护专属',
  `status` int(1) NULL DEFAULT 1 COMMENT '0-下架 1-正常',
  `sort` int(10) NULL DEFAULT 0 COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 37 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_gift
-- ----------------------------
INSERT INTO `db_gift` VALUES (4, '情书', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020120516174417910%E6%83%85%E4%B9%A6.png', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020120516174417910%E6%83%85%E4%B9%A6.png', 0, 18, 0, 0, 0, 1, 11);
INSERT INTO `db_gift` VALUES (5, '粉丝牌', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020120516160757143%E7%B2%89%E4%B8%9D%E7%89%8C.png', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020120516160757143%E7%B2%89%E4%B8%9D%E7%89%8C.png', 0, 20, 0, 0, 0, 1, 2);
INSERT INTO `db_gift` VALUES (6, '招财猫', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020120516181632587%E6%8B%9B%E8%B4%A2%E7%8C%AB.png', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020120516181632587%E6%8B%9B%E8%B4%A2%E7%8C%AB.png', 0, 20, 0, 0, 0, 1, 3);
INSERT INTO `db_gift` VALUES (7, '金话筒', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020120516163991491%E9%87%91%E8%AF%9D%E7%AD%92.png', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020120516163991491%E9%87%91%E8%AF%9D%E7%AD%92.png', 0, 30, 0, 0, 0, 1, 4);
INSERT INTO `db_gift` VALUES (8, '香水', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/images/2020010310384243868%E9%A6%99%E6%B0%B4%402x.png', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/animations/2020010310384565556%E9%A6%99%E6%B0%B4%402x.png', 0, 50, 0, 0, 0, 1, 32);
INSERT INTO `db_gift` VALUES (9, '钻戒', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020120516172751123%E9%92%BB%E6%88%92.png', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020120516172751123%E9%92%BB%E6%88%92.png', 0, 70, 0, 0, 0, 1, 6);
INSERT INTO `db_gift` VALUES (10, '告白气球', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/images/2020010310510981237%E5%91%8A%E7%99%BD%E6%B0%94%E7%90%83%402x.png', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/animations/2020010609190119774%E5%91%8A%E7%99%BD%E6%B0%94%E7%90%830035.svga', 1400, 150, 1, 2, 0, 1, 21);
INSERT INTO `db_gift` VALUES (11, '爱心', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/images/202001161458294989920170412_1207090522330.png', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/animations/20201022172240916102020011614585365671%E7%88%B1%E5%BF%83.svga', 2400, 100, 1, 2, 2, 1, 33);
INSERT INTO `db_gift` VALUES (12, '666', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/images/2020010314230522756666%402x.png', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/animations/2020010612553889882gift_gif_90.svga', 1300, 300, 1, 2, 0, 1, 9);
INSERT INTO `db_gift` VALUES (13, '火箭', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020120516243424263gift_31.png', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/animations/2020120516244113181gift_gif_31.svga', 3700, 1000, 1, 2, 1, 1, 1);
INSERT INTO `db_gift` VALUES (14, '带我走', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/images/2020010315542210637gift_86.png', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/animations/2020010315542639599gift_gif_86.gif', 4000, 500, 1, 1, 0, 1, 11);
INSERT INTO `db_gift` VALUES (15, '跑车', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020120516275425592gift_7.png', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/animations/2020120516280025770gift_gif_7.svga', 3700, 500, 1, 2, 2, 1, 7);
INSERT INTO `db_gift` VALUES (16, '游艇', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/images/2020010316251993918%E6%B8%B8%E8%89%87.png', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/animations/2020010316252422267gift_gif_93.svga', 6400, 600, 1, 2, 1, 1, 13);
INSERT INTO `db_gift` VALUES (17, '爱神丘比特', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020120516255674491gift_1.png', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/animations/2020120516260026049gift_gif_1.svga', 6300, 650, 1, 2, 2, 1, 14);
INSERT INTO `db_gift` VALUES (18, '会飞的心', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/images/2020010610164759703%E4%BC%9A%E9%A3%9E%E7%9A%84%E5%BF%830001.png', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/animations/2020010610170161927%E4%BC%9A%E9%A3%9E%E7%9A%84%E5%BF%830047.svga', 1920, 200, 1, 2, 1, 1, 25);
INSERT INTO `db_gift` VALUES (19, '幸福摩天轮', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/images/202001061059009427512.png', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/animations/2020010610590620859%E5%B9%B8%E7%A6%8F%E6%91%A9%E5%A4%A9%E8%BD%AE0128.svga', 5300, 700, 1, 2, 0, 1, 15);
INSERT INTO `db_gift` VALUES (20, '520', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/202012051622436983360e9d57f553ef6b238f29a76f0fa3ede.png', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/animations/202012051622495422088a57385f606f9e4bee7bf30a2587d96.svga', 4500, 200, 1, 2, 2, 1, 6);
INSERT INTO `db_gift` VALUES (21, '爱心小火车', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/images/2020010611360022221%E7%88%B1%E5%BF%83%E5%B0%8F%E7%81%AB%E8%BD%A60031.png', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/animations/2020010611360631504%E7%88%B1%E5%BF%83%E5%B0%8F%E7%81%AB%E8%BD%A60071.svga', 2680, 1000, 1, 2, 2, 1, 17);
INSERT INTO `db_gift` VALUES (22, '海洋之心钻戒', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/images/20200106121129704211212.png', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/animations/2020010612113351510%E6%B5%B7%E6%B4%8B%E4%B9%8B%E5%BF%830008.svga', 3200, 1500, 1, 2, 0, 1, 18);
INSERT INTO `db_gift` VALUES (23, '别墅', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/images/2020010614200917400212121.png', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/animations/2020010614165427020%E5%88%AB%E5%A2%85', 2500, 5000, 1, 2, 0, 0, 27);
INSERT INTO `db_gift` VALUES (24, '小狐狸', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/images/2020010615283010494%E5%B0%8F%E7%8B%90%E7%8B%B80007.png', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/animations/2020010615283445678%E5%B0%8F%E7%8B%90%E7%8B%B80032.svga', 2500, 1000, 1, 2, 0, 1, 19);
INSERT INTO `db_gift` VALUES (25, '冰淇淋', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/images/2020010615474195360%E5%86%B0%E6%B7%87%E6%B7%8B0061.png', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/animations/2020010615474734711%E5%86%B0%E6%B7%87%E6%B7%8B.gif', 7000, 500, 1, 1, 0, 1, 20);
INSERT INTO `db_gift` VALUES (26, '钞票枪', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020120516192753482gift_30.png', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/animations/2020120516193063084gift_gif_30.svga', 2900, 600, 1, 2, 1, 1, 2);
INSERT INTO `db_gift` VALUES (27, '宝箱', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/images/2020010616070373938%E5%AE%9D%E7%AE%B1.png', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/animations/2020010616070758935%E5%AE%9D%E7%AE%B1.gif', 1430, 600, 1, 1, 0, 1, 22);
INSERT INTO `db_gift` VALUES (28, '弹幕小电视', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/images/2020010616134196293%E5%B0%8F%E7%94%B5%E8%A7%86.png', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/animations/20200106161344836983175651.gif', 1750, 600, 1, 1, 0, 1, 23);
INSERT INTO `db_gift` VALUES (29, '火锅', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/images/2020010616342598609%E7%81%AB%E9%94%85.png', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/animations/20200106163428313443175651.gif', 1400, 600, 1, 1, 0, 1, 24);
INSERT INTO `db_gift` VALUES (30, '星际战舰', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/images/2020010616512127435%E6%98%9F%E4%BA%91%E6%88%98%E6%9C%BA.png', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/animations/2020010616512592965%E6%98%9F%E4%BA%91%E6%88%98%E6%9C%BA.gif', 4600, 3000, 1, 1, 0, 1, 26);
INSERT INTO `db_gift` VALUES (31, '千纸鹤', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/images/2020010617150569301%E5%8D%83%E7%BA%B8%E9%B9%A4.png', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/animations/20200106171509350932148699_VJshi_523ff4fd87be068727c8116184d0b1a3.gif', 3000, 400, 1, 1, 0, 1, 25);
INSERT INTO `db_gift` VALUES (32, '蓝玫瑰', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/images/202001171317569059120171218_1808571395540.png', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/animations/2020011713180025038%E8%8A%B1.svga', 3000, 100, 1, 2, 0, 1, 28);
INSERT INTO `db_gift` VALUES (33, '旋转木马', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020120516271314576gift_9.png', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/animations/2020120516272532088gift_gif_9.svga', 5000, 1000, 1, 2, 1, 1, 11);
INSERT INTO `db_gift` VALUES (34, '鹊桥相会', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020120516303757594gift_8.png', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/animations/2020120516304069154gift_gif_8.svga', 7500, 2000, 1, 2, 1, 1, 12);
INSERT INTO `db_gift` VALUES (35, '魔法棒', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020120516321689023%E9%AD%94%E6%B3%95%E6%A3%92.png', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020120516321689023%E9%AD%94%E6%B3%95%E6%A3%92.png', 0, 1, 0, 0, 0, 1, 22);
INSERT INTO `db_gift` VALUES (36, '蛋糕', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020120516324821902%E8%9B%8B%E7%B3%95.png', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020120516324821902%E8%9B%8B%E7%B3%95.png', 0, 5, 0, 0, 0, 1, 22);

-- ----------------------------
-- Table structure for db_gift_log
-- ----------------------------
DROP TABLE IF EXISTS `db_gift_log`;
CREATE TABLE `db_gift_log`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `giftid` int(10) NULL DEFAULT NULL,
  `anchorid` int(10) NULL DEFAULT NULL COMMENT '收礼用户id',
  `uid` int(10) NULL DEFAULT NULL COMMENT '送礼用户id',
  `liveid` bigint(14) NULL DEFAULT NULL COMMENT '归属直播id，可为空',
  `count` int(10) NULL DEFAULT 1,
  `spend` int(10) NULL DEFAULT NULL COMMENT '赠送礼物消耗金币数',
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_gift_log
-- ----------------------------

-- ----------------------------
-- Table structure for db_gift_log_admin
-- ----------------------------
DROP TABLE IF EXISTS `db_gift_log_admin`;
CREATE TABLE `db_gift_log_admin`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `giftid` int(10) NULL DEFAULT NULL,
  `uid` int(10) NULL DEFAULT NULL COMMENT '送礼用户id',
  `relateid` int(10) NULL DEFAULT NULL COMMENT '归属id',
  `spend` int(10) NULL DEFAULT NULL COMMENT '赠送礼物消耗金币数',
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_gift_log_admin
-- ----------------------------

-- ----------------------------
-- Table structure for db_gold_price
-- ----------------------------
DROP TABLE IF EXISTS `db_gold_price`;
CREATE TABLE `db_gold_price`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `gold` int(10) NULL DEFAULT 0 COMMENT '金币数量',
  `gold_ios` int(10) NULL DEFAULT 0 COMMENT 'ios内购金币数量',
  `gold_added` int(10) NULL DEFAULT 0 COMMENT '额外赠送数量',
  `price` float(10, 2) NULL DEFAULT NULL COMMENT '价格/元',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_gold_price
-- ----------------------------
INSERT INTO `db_gold_price` VALUES (2, 60, 42, 0, 0.99);
INSERT INTO `db_gold_price` VALUES (3, 300, 210, 0, 29.99);
INSERT INTO `db_gold_price` VALUES (4, 680, 476, 0, 64.99);
INSERT INTO `db_gold_price` VALUES (5, 1280, 896, 0, 129.99);
INSERT INTO `db_gold_price` VALUES (6, 2880, 2016, 0, 299.99);
INSERT INTO `db_gold_price` VALUES (7, 6180, 4326, 0, 599.99);
INSERT INTO `db_gold_price` VALUES (8, 12980, 9086, 0, 899.99);

-- ----------------------------
-- Table structure for db_guard_price
-- ----------------------------
DROP TABLE IF EXISTS `db_guard_price`;
CREATE TABLE `db_guard_price`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `week_price` int(10) NULL DEFAULT NULL,
  `month_price` int(10) NULL DEFAULT NULL,
  `year_price` int(10) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_guard_price
-- ----------------------------
INSERT INTO `db_guard_price` VALUES (1, 1200, 30000, 100000);

-- ----------------------------
-- Table structure for db_guild
-- ----------------------------
DROP TABLE IF EXISTS `db_guild`;
CREATE TABLE `db_guild`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '公会说明',
  `diamond` int(10) NULL DEFAULT 0 COMMENT '钻石数量',
  `diamond_total` int(10) NULL DEFAULT 0 COMMENT '累计钻石数量',
  `sharing_ratio` int(3) NULL DEFAULT 60 COMMENT '分成比例 %',
  `create_time` datetime(0) NULL DEFAULT NULL,
  `last_login_time` datetime(0) NULL DEFAULT NULL,
  `last_login_ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `alipay_account` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '支付宝账号',
  `alipay_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '支付宝姓名',
  `status` int(1) NULL DEFAULT 1 COMMENT '1-正常 0-封禁',
  `member_count` int(11) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_guild
-- ----------------------------

-- ----------------------------
-- Table structure for db_guild_member_apply
-- ----------------------------
DROP TABLE IF EXISTS `db_guild_member_apply`;
CREATE TABLE `db_guild_member_apply`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL,
  `guildid` int(11) NULL DEFAULT NULL,
  `status` int(1) NULL DEFAULT 0 COMMENT '0-申请中 1-已通过 2-被拒绝',
  `create_time` datetime(0) NULL DEFAULT NULL,
  `operate_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_guild_member_apply
-- ----------------------------

-- ----------------------------
-- Table structure for db_guild_profit
-- ----------------------------
DROP TABLE IF EXISTS `db_guild_profit`;
CREATE TABLE `db_guild_profit`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guildid` int(11) NULL DEFAULT NULL,
  `diamond` int(11) NULL DEFAULT NULL COMMENT '收入钻石数量',
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '说明',
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_guild_profit
-- ----------------------------

-- ----------------------------
-- Table structure for db_guild_withdrawals
-- ----------------------------
DROP TABLE IF EXISTS `db_guild_withdrawals`;
CREATE TABLE `db_guild_withdrawals`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guildid` int(10) NULL DEFAULT NULL,
  `diamond` int(10) NULL DEFAULT NULL COMMENT '扣除钻石数量',
  `cash` decimal(10, 2) NULL DEFAULT NULL COMMENT '提取金额',
  `alipay_account` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '收款支付宝账号',
  `alipay_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '收款人支付宝姓名',
  `create_time` datetime(0) NULL DEFAULT NULL COMMENT '申请提现时间',
  `operate_time` datetime(0) NULL DEFAULT NULL COMMENT '处理时间',
  `status` int(1) NULL DEFAULT 0 COMMENT '0-未处理 1-已提现 2-已拒绝(钻石返还) 3-异常单(扣除金币)',
  `trade_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '转账订单号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_guild_withdrawals
-- ----------------------------

-- ----------------------------
-- Table structure for db_intimacy
-- ----------------------------
DROP TABLE IF EXISTS `db_intimacy`;
CREATE TABLE `db_intimacy`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL,
  `anchorid` int(10) NULL DEFAULT NULL,
  `intimacy` int(10) NULL DEFAULT NULL COMMENT '亲密度',
  `intimacy_week` int(10) NULL DEFAULT 0 COMMENT '亲密度-本周',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_intimacy
-- ----------------------------

-- ----------------------------
-- Table structure for db_live
-- ----------------------------
DROP TABLE IF EXISTS `db_live`;
CREATE TABLE `db_live`  (
  `anchorid` int(10) NOT NULL COMMENT '主播id  房间号',
  `liveid` bigint(14) NULL DEFAULT NULL COMMENT '直播编号',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `thumb` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '直播封面',
  `isvideo` int(1) NULL DEFAULT 0 COMMENT '1-假视频',
  `stream` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '流名',
  `pull_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '播流地址',
  `acc_pull_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '加速播流地址 rtmp协议',
  `categoryid` int(10) NULL DEFAULT NULL COMMENT '直播分类',
  `orientation` int(1) NULL DEFAULT 1 COMMENT '1-横屏 2-竖屏',
  `start_stamp` int(11) NULL DEFAULT 0,
  `end_stamp` int(11) NULL DEFAULT NULL,
  `start_time` datetime(0) NULL DEFAULT NULL,
  `end_time` datetime(0) NULL DEFAULT NULL,
  `hot` int(11) NULL DEFAULT 0 COMMENT '热度',
  `rec_weight` int(11) NULL DEFAULT 0 COMMENT '推荐值',
  `password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '房间密码 md5加密',
  `price` int(10) NULL DEFAULT 0 COMMENT '收费标准 /分钟',
  `room_type` int(1) NULL DEFAULT 0 COMMENT '0-普通房间 1-私密房间 2-付费房间',
  `profit` int(10) NULL DEFAULT 0 COMMENT '实时收入钻石数',
  `link_on` int(1) NULL DEFAULT 0 COMMENT '1-开启连麦',
  `pk_status` int(1) NULL DEFAULT 0 COMMENT '0-未开启 1-PK中 2-匹配中',
  `link_status` int(1) NULL DEFAULT 0 COMMENT '1-连麦中',
  `pkid` int(11) NULL DEFAULT 0 COMMENT 'Pk id',
  PRIMARY KEY (`anchorid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_live
-- ----------------------------

-- ----------------------------
-- Table structure for db_live_category
-- ----------------------------
DROP TABLE IF EXISTS `db_live_category`;
CREATE TABLE `db_live_category`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sort` int(10) NULL DEFAULT 1 COMMENT '排序',
  `status` int(1) NULL DEFAULT 1 COMMENT '0-隐藏 1-显示',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_live_category
-- ----------------------------
INSERT INTO `db_live_category` VALUES (1, '颜值', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/images/2020020114512325325IMG_7552.JPG', 1, 1);
INSERT INTO `db_live_category` VALUES (2, '游戏', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/images/2020020114512325325IMG_7552.JPG', 2, 1);
INSERT INTO `db_live_category` VALUES (3, '男神', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/images/2020020114512325325IMG_7552.JPG', 3, 1);
INSERT INTO `db_live_category` VALUES (5, '户外', 'https://meet1v1-1300631461.cos.ap-shanghai.myqcloud.com/images/2020020114512325325IMG_7552.JPG', 5, 1);
INSERT INTO `db_live_category` VALUES (9, '真人秀', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020070309361928182IMG_7610.JPG', 4, 1);
INSERT INTO `db_live_category` VALUES (10, '购物', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020070309361928182IMG_7610.JPG', 6, 1);
INSERT INTO `db_live_category` VALUES (11, '搞笑', 'https://live-1300631461.cos.ap-shanghai.myqcloud.com/images/2020111011025960990%E6%90%9E%E7%AC%91.png', 7, 1);
INSERT INTO `db_live_category` VALUES (12, '&nbsp;', '1', 10, 0);

-- ----------------------------
-- Table structure for db_live_history
-- ----------------------------
DROP TABLE IF EXISTS `db_live_history`;
CREATE TABLE `db_live_history`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `anchorid` int(10) NOT NULL COMMENT '主播id  房间号',
  `liveid` bigint(14) NULL DEFAULT NULL COMMENT '直播编号',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `stream` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '流名',
  `pull_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '播流地址',
  `categoryid` int(10) NULL DEFAULT NULL COMMENT '直播分类',
  `orientation` int(1) NULL DEFAULT 1 COMMENT '1-横屏 2-竖屏',
  `start_stamp` int(11) NULL DEFAULT 0,
  `end_stamp` int(11) NULL DEFAULT NULL,
  `start_time` datetime(0) NULL DEFAULT NULL,
  `end_time` datetime(0) NULL DEFAULT NULL,
  `profit` int(10) NULL DEFAULT 0 COMMENT '直播收益（钻石数量）',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_live_history
-- ----------------------------

-- ----------------------------
-- Table structure for db_live_pk
-- ----------------------------
DROP TABLE IF EXISTS `db_live_pk`;
CREATE TABLE `db_live_pk`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `home_anchorid` int(11) NOT NULL,
  `away_anchorid` int(11) NOT NULL,
  `home_score` int(10) NULL DEFAULT 0,
  `away_score` int(10) NULL DEFAULT 0,
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_live_pk
-- ----------------------------

-- ----------------------------
-- Table structure for db_live_room_banneduser
-- ----------------------------
DROP TABLE IF EXISTS `db_live_room_banneduser`;
CREATE TABLE `db_live_room_banneduser`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL,
  `anchorid` int(11) NULL DEFAULT NULL,
  `mgrid` int(11) NULL DEFAULT NULL,
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_live_room_banneduser
-- ----------------------------

-- ----------------------------
-- Table structure for db_live_room_manager
-- ----------------------------
DROP TABLE IF EXISTS `db_live_room_manager`;
CREATE TABLE `db_live_room_manager`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `anchorid` int(11) NULL DEFAULT NULL,
  `mgrid` int(11) NULL DEFAULT NULL,
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_live_room_manager
-- ----------------------------

-- ----------------------------
-- Table structure for db_moment
-- ----------------------------
DROP TABLE IF EXISTS `db_moment`;
CREATE TABLE `db_moment`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `image_url` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `video_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `blur_image_url` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '模糊图',
  `type` int(1) NULL DEFAULT 1 COMMENT '1-文字，2-图文，3-视频',
  `recommend` int(1) NULL DEFAULT 0 COMMENT '1-置顶',
  `collect_count` int(10) NULL DEFAULT 0 COMMENT '收藏量',
  `watch_count` int(10) NULL DEFAULT 0 COMMENT '点击量',
  `like_count` int(10) NULL DEFAULT 0 COMMENT '点赞量',
  `comment_count` int(10) NULL DEFAULT 0 COMMENT '评论量',
  `single_display_type` int(1) NULL DEFAULT NULL COMMENT '1-横向 2-纵向 3-方形',
  `create_time` datetime(0) NULL DEFAULT NULL,
  `status` int(1) NULL DEFAULT 0 COMMENT '0-待审核，1-通过，2-拒绝，3-下架',
  `banned_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '下架原因',
  `unlock_price` int(10) NULL DEFAULT 0 COMMENT '解锁价格',
  `is_secret` int(1) NULL DEFAULT 0 COMMENT '1-私密（仅自己可见）',
  `topic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '话题 #...#',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_moment
-- ----------------------------

-- ----------------------------
-- Table structure for db_moment_collect
-- ----------------------------
DROP TABLE IF EXISTS `db_moment_collect`;
CREATE TABLE `db_moment_collect`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL,
  `momentid` int(10) NULL DEFAULT NULL,
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_moment_collect
-- ----------------------------

-- ----------------------------
-- Table structure for db_moment_comment
-- ----------------------------
DROP TABLE IF EXISTS `db_moment_comment`;
CREATE TABLE `db_moment_comment`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rootid` int(10) NULL DEFAULT 0 COMMENT '根评论',
  `tocommentid` int(10) NULL DEFAULT 0 COMMENT '被回复评论',
  `momentid` int(10) NULL DEFAULT NULL,
  `uid` int(10) NULL DEFAULT NULL,
  `touid` int(10) NULL DEFAULT 0 COMMENT '被回复用户id',
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `create_time` datetime(0) NULL DEFAULT NULL,
  `like_count` int(10) NULL DEFAULT 0 COMMENT '被点赞数量',
  `reply_count` int(10) NULL DEFAULT 0 COMMENT '回复数量',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_moment_comment
-- ----------------------------

-- ----------------------------
-- Table structure for db_moment_comment_like
-- ----------------------------
DROP TABLE IF EXISTS `db_moment_comment_like`;
CREATE TABLE `db_moment_comment_like`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL,
  `commentid` int(10) NULL DEFAULT NULL,
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_moment_comment_like
-- ----------------------------

-- ----------------------------
-- Table structure for db_moment_like
-- ----------------------------
DROP TABLE IF EXISTS `db_moment_like`;
CREATE TABLE `db_moment_like`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL,
  `momentid` int(10) NULL DEFAULT NULL,
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_moment_like
-- ----------------------------

-- ----------------------------
-- Table structure for db_moment_report
-- ----------------------------
DROP TABLE IF EXISTS `db_moment_report`;
CREATE TABLE `db_moment_report`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL,
  `momentid` int(10) NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `content` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '举报内容',
  `create_time` datetime(0) NULL DEFAULT NULL,
  `img_urls` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_moment_report
-- ----------------------------

-- ----------------------------
-- Table structure for db_moment_unlock
-- ----------------------------
DROP TABLE IF EXISTS `db_moment_unlock`;
CREATE TABLE `db_moment_unlock`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL,
  `momentid` int(10) NULL DEFAULT NULL,
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_moment_unlock
-- ----------------------------

-- ----------------------------
-- Table structure for db_moment_watch
-- ----------------------------
DROP TABLE IF EXISTS `db_moment_watch`;
CREATE TABLE `db_moment_watch`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL,
  `momentid` int(10) NULL DEFAULT NULL,
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_moment_watch
-- ----------------------------

-- ----------------------------
-- Table structure for db_order
-- ----------------------------
DROP TABLE IF EXISTS `db_order`;
CREATE TABLE `db_order`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL,
  `order_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '商户订单号',
  `type` int(1) NULL DEFAULT 0 COMMENT '0-购买金币 1-开通贵族',
  `vip_level` int(255) NULL DEFAULT 0 COMMENT '购买vip等级，type=1时有效',
  `amount` decimal(10, 2) NULL DEFAULT NULL COMMENT '应付金额',
  `pay_amount` decimal(10, 2) NULL DEFAULT NULL COMMENT '实付金额',
  `gold` int(10) NULL DEFAULT 0 COMMENT '购买金币',
  `gold_added` int(10) NULL DEFAULT 0 COMMENT '额外赠送金币',
  `out_trade_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '外部订单号',
  `pay_channel` int(1) NULL DEFAULT NULL COMMENT '1-微信 2-支付宝 3-苹果支付 4-其他 5-人工',
  `create_time` datetime(0) NULL DEFAULT NULL,
  `pay_time` datetime(0) NULL DEFAULT NULL,
  `pay_status` int(1) NULL DEFAULT 0 COMMENT '0-等待支付 1-支付成功 2-支付取消 3-支付失败',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_order
-- ----------------------------

-- ----------------------------
-- Table structure for db_prefix_jobs
-- ----------------------------
DROP TABLE IF EXISTS `db_prefix_jobs`;
CREATE TABLE `db_prefix_jobs`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED NULL DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_prefix_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for db_protocal
-- ----------------------------
DROP TABLE IF EXISTS `db_protocal`;
CREATE TABLE `db_protocal`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `type` int(1) NULL DEFAULT NULL COMMENT '1-用户注册协议 2-隐私权政策 3-用户阳光行为规范',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_protocal
-- ----------------------------
INSERT INTO `db_protocal` VALUES (1, '用户注册协议', '<ul class=\" list-paddingleft-2\"><li><p><strong>提示条款：</strong></p></li><li><p>欢迎您（下称&quot;用户&quot;或&quot;您&quot;）与钠斯平台经营者（详见定义条款）共同签署本《用户注册协议》（下称“本协议”）并使用钠斯平台服务！ 各条款标题仅为帮助您理解该条款表达的主旨之用，不影响或限制本协议条款的含义或解释。为维护您自身权益，建议您仔细阅读各条款具体表述</p></li><li><p><strong>【审慎阅读】</strong>您在申请注册流程中点击同意本协议之前，应当认真阅读本协议。<strong>请您务必审慎阅读、充分理解各条款内容，特别是免除或限制责任的条款、法律适用和争议解决条款。免除或限制责任的条款将以粗体下划线标识，您应重点阅读。</strong>若您对协议有任何疑问，可向钠斯平台客服咨询</p></li><li><p><strong>【签约动作】</strong>当您按照注册页面提示填写信息、阅读并同意本协议且完成全部注册程序后，即表示您已充分阅读、理解并接受本协议的全部内容，并与钠斯平台达成一致，成为钠斯平台 “用户”。阅读本协议的过程中，如果您不同意本协议或其中任何条款约定，您应立即停止注册程序。\n如果您未申请注册流程，或者在本协议生效前已成为钠斯平台的注册用户，则您通过访问和/或使用钠斯平台，即视为您表示同意接受本协议的全部内容，否则请您不要访问或使用钠斯平台。</p></li><li><p><strong>一、定义</strong></p></li><li><p><strong>钠斯平台：</strong>指钠斯直播（域名为live.nasinet.com）网站及客户端。</p></li><li><p><strong>钠斯平台服务：</strong>钠斯基于互联网，以包含钠斯平台网站、客户端等在内的各种形态（包括未来技术发展出现的新的服务形态）向您提供的各项服务。</p></li><li><p><strong>钠斯平台服务提供者：</strong>武汉钠斯网络科技有限公司及其关联方，包括但不限于武汉瓯越网视有限公司，武汉钠斯鱼乐网络科技有限公司及其境内分支机构。</p></li><li><p><strong>钠斯平台规则：</strong>包括在所有钠斯平台网站内已经发布及后续发布的全部规则、解读、公告等内容以及钠斯平台各频道、活动页面、鱼吧等发布的各类规则、实施细则、产品说明、公告等。</p></li><li><p><strong>用户</strong>：下称“您”或“用户”，是指注册、登录、使用、浏览、获取本协议项下服务的个人或组织。</p></li><li><p><strong>二、协议范围</strong></p></li><li><p><strong>2.1签约主体</strong></p></li><li><p>本协议是您与钠斯平台之间关于注册、登录钠斯平台以及使用钠斯平台服务所订立的协议。本协议对您与钠斯平台经营者均具有合同效力。</p></li><li><p>钠斯平台经营者是指经营钠斯平台的各法律主体，您可随时查看钠斯平台网站首页底部公示的证照信息以确定与您履约的钠斯主体。本协议项下，<strong>钠斯平台经营者可能根据钠斯平台的业务调整而发生变更，变更后的钠斯平台经营者与您共同履行本协议并向您提供服务，钠斯平台经营者的变更不会影响您本协议项下的权益。钠斯平台经营者还有可能因为提供新的钠斯平台服务而新增，如您使用新增的钠斯平台服务的，视为您同意新增的钠斯平台经营者与您共同履行本协议。发生争议时，您可根据您具体使用的服务及对您权益产生影响的具体行为对象确定与您履约的主体及争议相对方。</strong></p></li><li><p><strong>2.2补充协议</strong></p></li><li><p>由于互联网高速发展，您与钠斯签署的本协议列明的条款并不能完整罗列并覆盖您与钠斯所有权利与义务，现有的约定也不能保证完全符合未来发展的需求。<strong>因此，钠斯平台公示的相关声明及政策、钠斯平台规则和协议均为本协议的补充协议，与本协议不可分割且具有同等法律效力。如您使用钠斯平台服务，视为您同意上述补充协议。</strong></p></li><li><p><strong>三、账户注册与使用</strong></p></li><li><p><strong>3.1用户资格</strong></p></li><li><p>您确认，在您开始使用/注册程序使用钠斯平台服务前，您应当具备中华人民共和国法律规定的与您行为相适应的民事行为能力。<strong>若您不具备前述与您行为相适应的民事行为能力，则您及您的监护人应依照法律规定承担因此而导致的一切后果。</strong></p></li><li><p><strong>特别地，如果您是未成年人，请在您的监护人的陪同下审阅和接受本协议。未成年人应当在合理程度内使用钠斯平台，不得因使用钠斯平台而影响了日常的学习生活。您理解钠斯平台无义务对本款前述事项进行任何形式的审查和确认。</strong></p></li><li><p><strong>3.2账户说明</strong></p></li><li><p>当您按照注册页面提示填写信息、阅读并同意本协议且完成全部注册程序后，您可获得钠斯平台账户并成为钠斯平台用户。</p></li><li><p>您有权使用您设置或确认的钠斯用户名（以下简称“账户名称”）及您设置的密码（账户名称及密码合称“账户”）登录钠斯平台。\n由于您的钠斯账户关联您的个人信息及钠斯平台商业信息，您的钠斯账户仅限您本人使用。未经钠斯平台同意，您直接或间接授权第三方使用您钠斯账户或获取您账户项下信息的行为所导致的一切责任后果由您自行承担，钠斯平台对此不承担任何责任。但如若钠斯平台判断您钠斯账户的使用可能危及您的账户安全及/或钠斯平台信息安全的，钠斯平台可拒绝提供相应服务或终止本协议。\n由于用户账户关联用户信用信息，仅当有法律明文规定、司法裁定或经钠斯同意，并符合钠斯平台规则规定的用户账户转让流程的情况下，您可进行账户的转让。您的账户一经转让，该账户项下权利义务一并转移。<strong>除此外，您的账户不得以任何方式转让，否则由此产生的一切责任均由您承担。</strong></p></li><li><p>作为钠斯平台经营者，为使您更好地使用钠斯平台的各项服务，保障您的账户安全，钠斯可要求您按钠斯平台要求及我国法律规定完成实名认证。<strong>如您的账户长期未登录，钠斯有权予以进行注销、回收、替换或采取删除该用您账户在钠斯平台数据库中的任何记录（包括但不限于注册信息、虚拟礼物信息等）等清理措施，您的账户将不能再登录任一钠斯平台，相应服务同时终止。钠斯在对此类账户进行清理前，将以包括但不限于弹窗、网站公告、站内消息、客户端推送信息等方式通知您。</strong></p></li><li><p><strong>3.3注册信息管理</strong></p></li><li><p><strong>3.3.1真实合法</strong></p></li><li><p>在使用钠斯平台服务时，您应当按钠斯平台页面的提示准确完整地提供您的信息（包括您的姓名及电子邮件地址、联系电话、联系地址等），以便钠斯在必要时与您联系。<strong>您了解并同意，您有义务保持您提供信息的真实性及有效性，不得以虚假、冒用的居民身份信息、企业注册信息、组织机构代码等注册信息进行注册或认证，任何非法、不真实、不准确的用户信息所产生的责任由您自行承担。您所设置的账户名不得违反国家法律法规及钠斯平台规则关于账户名的管理规定，否则钠斯可对您的账户名进行暂停使用或注销等处理，并向主管机关报告。</strong></p></li><li><p><strong>您理解并承诺，您的账户名称、头像和简介等注册信息中不得出现违法和不良信息，没有冒用、关联机构或社会名人，您在账户注册过程中需遵守法律法规、社会主义制度、国家利益、公民合法权益、公共秩序、社会道德风尚和信息真实性等七条底线。</strong></p></li><li><p>如平台发现您在软件中注册或使用的帐号等一切自定义名称与其他用户相同而导致无法识别，钠斯平台有权要求您修改上述名称，如您在钠斯平台要求的时限内未予修改，则钠斯平台有权在您自定义的名称后加注识别符号予以区别以确保软件正常运行（例如您希望或正在使用的账户名称为“钠斯”，但在同一组服务器中同样存在另外一个用户账户名为“钠斯”，则在您不愿意修改名称的情况下，钠斯平台有权不经您同意的情况在您名称后加注识别符号后成为“钠斯1”、“钠斯2”等），您保证无条件同意上述修改<br/>您同意并授权，为了更好的为您提供服务以及确保您的账户安全，钠斯可以根据您提供的手机号码、身份证号码等信息，向全国公民身份号码查询服务中心、电信运营商、金融服务机构等可靠单位发起用户身份真实性、用户征信记录、用户手机号码有效性状态等情况的查询。</p></li><li><p><strong>3.3.2 更新维护</strong></p></li><li><p>您应当及时更新您提供的信息（包括但不限于注册资料等），确保符合及时、详尽、真实、准确的要求。在法律有明确规定要求钠斯对部分用户的信息进行核实的情况下，钠斯将依法不时地对您的信息进行检查核实，您应当配合提供最新、真实、完整的信息。<strong>您在更改、删除有关信息的同时也可能会造成储存在系统中的文字、图片、视频等丢失，钠斯对您自主操作导致内容丢失不承担责任。\n如钠斯按您最后一次提供的信息与您联系未果、您未按钠斯的要求及时提供信息、您提供的信息存在明显不实或行政司法机关核实您提供的信息无效的，您将承担因此对您自身、他人及钠斯造成的全部损失与不利后果。钠斯可向您发出询问或要求整改的通知，并要求您进行重新认证，直至中止、终止对您提供部分或全部钠斯平台服务，钠斯对此不承担责任。</strong></p></li><li><p><strong>3.3.3账户安全规范</strong></p></li><li><p>您的账户为您自行设置并由您保管，钠斯任何时候均不会主动要求您提供您的账户密码。因此，建议您务必保管好您的账户，并确保您在每个上网时段结束时退出登录并以正确步骤离开钠斯平台。<strong>如您主动通知钠斯平台，要求钠斯平台采取措施暂停您帐号的登录和使用的，钠斯平台应当要求您提供并核实与其注册身份信息相一致的个人有效身份信息，否则钠斯有权拒绝您的上述请求。钠斯平台核实您所提供的个人有效身份信息与所注册的身份信息相一致的，应当及时采取措施暂停用户帐号的登录和使用。用户没有提供其个人有效身份证件或者用户提供的个人有效身份证件与所注册的身份信息不一致的，钠斯平台有权拒绝用户上述请求。</strong></p></li><li><p><strong>账户因您主动泄露或因您遭受他人攻击、诈骗等行为导致的损失及后果，均由您自行承担。钠斯并不承担责任，您应通过司法、行政等救济途径向侵权行为人追偿。</strong></p></li><li><p><strong>您的账户只限您本人使用，不得出借、赠与、出租、未按规定程序转让、售卖或分享他人使用。当您的账户遭到未经授权的使用时，您应当立即通知钠斯平台，否则未经授权的使用行为均视为您本人的行为，您将自行承担所有由此导致的损失及后果。</strong><strong>除钠斯存在过错外，您应对您账户项下的所有行为结果（包括但不限于在线签署各类协议、发布信息、购买商品及服务及披露信息等）负责。</strong>如发现任何未经授权使用您账户登录钠斯平台或其他可能导致您账户遭窃、遗失的情况，建议您立即通知钠斯。<strong>您理解钠斯对您的任何请求采取行动均需要合理时间，且钠斯应您请求而采取的行动可能无法避免或阻止侵害后果的形成或扩大，除钠斯存在法定过错外，钠斯不承担责任。</strong></p></li><li><p><strong>四、钠斯平台服务及规范</strong></p></li><li><p><strong>4.1钠斯平台服务</strong></p></li><li><p><br/></p></li><li><p>钠斯平台向您提供包括但不限于以下服务：</p></li><li><p>4.1.1钠斯直接所有或运营的任何网站，如www.nasinet.com （及其他由钠斯运营的任何网站）；</p></li><li><p>4.1.2钠斯直接拥有或运营网站的客户端包括但不限于PC、平板、手机、电视、机顶盒子等全部终端的客户端；</p></li><li><p>4.1.3钠斯平台用户个人中心服务；</p></li><li><p>4.1.4鱼吧发帖服务；</p></li><li><p>4.1.5钠斯直播服务；</p></li><li><p>4.1.6钠斯搜索服务；</p></li><li><p>4.1.7钠斯游戏中心服务；</p></li><li><p>4.1.8钠斯平台提供的其他技术和/或服务（下称“其他技术和服务”）。</p></li><li><p>（以上服务统称为“钠斯平台服务”）。</p></li><li><p>钠斯平台服务，均仅限于您在钠斯平台使用，任何以恶意破解等非法手段将钠斯服务与钠斯平台分离的行为，均不属于本协议中约定的钠斯服务。由此引起的一切法律后果由行为人负责，钠斯将依法追究行为人的法律责任。</p></li><li><p>钠斯平台网站官方公布的方式为注册、登录、下载、使用钠斯服务的唯一合法方式，您通过其他任何渠道、任何途径、任何方式获取的钠斯服务（包括但不限于账户、鱼丸、鱼翅、道具、下载等）均为非法取得，钠斯概不承认其效力，且一经发现钠斯有权立即做出删除、取消、清零、封号等处理，任何因此导致的一切不利后果均由您自行承担，钠斯有权依法追究相应人员或机构的法律责任。<strong>钠斯有权向您公告（包括但不限于弹出页面、网站公告、站内消息等方式）以修改、替换、升级与钠斯平台服务相关的任何软件。如果您不同意或者不接受钠斯平台服务相关软件的修改、替换、升级，请直接拒绝、停止、取消使用行为，否则视为您同意并接受钠斯平台相关软件的修改、替代、升级，同时该同意并接受的行为仍受本协议约束。</strong></p></li><li><p>您理解并认可钠斯平台享有如下权利，钠斯平台行使如下权利不视为违约，您不追究或者豁免钠斯平台的相关法律责任：<strong>您使用钠斯平台网站或钠斯平台账户所获得的经验值、等级、关注、订阅、头衔、电子票务、虚拟直播房间、虚拟礼物、虚拟赠品及奖励、下载以及钠斯平台运营过程中产生并储存于钠斯网络数据库的任何数据信息（包括对但不限于账户数据信息、直播时长数据信息、虚拟礼物数据信息、消费数据信息等）等衍生物（下称“衍生物”），您确认对其不享有所有权（除非钠斯平台另有公告说明），钠斯许可您按照钠斯平台规则进行使用，钠斯对上述衍生物不承担任何赔偿责任。钠斯有权根据实际情况自行决定收回日期，无需另行通知您亦无需征得您同意。</strong></p></li><li><p>您同意并保证，不得利用钠斯平台服务或其衍生物进行倒卖、转手、置换、抵押有价交易等方式非法牟利。您不会利用钠斯平台服务或其衍生物侵犯他人的合法权益，禁止通过网络漏洞、恶意软件或其他非法手段窃取、盗用他人的账户、虚拟礼物等。</p></li><li><p>您理解并认可，如果您通过第三方支付工具在钠斯平台账户支付或充值购买钠斯平台的收费服务（包括但不限于购买钠斯平台内的虚拟礼物的使用权以及接受其他增值服务等各类收费项目）。钠斯平台有权按需要修改或变更所提供的收费服务、收费标准、收费方式、服务费、及服务条款。钠斯平台提供服务时，可能现在或日后对部分服务的用户开始收取一定的费用，如您拒绝支付该等费用，则不能在收费开始后继续使用相关的服务。如果您通过第三方支付工具在钠斯平台账户支付或充值后可能产生的任何商业风险（包括但不限于不法分子利用您账户或银行卡等有价卡等进行违法活动），该等风险均有可能给您造成相应的经济损失，钠斯在充分履行其在本协议项下义务和符合法律规定的前提下，不对您的前述风险和损失承担任何责任。</p></li><li><p><strong>4.2服务规范</strong></p></li><li><p>钠斯平台账户的所有权归钠斯所有，您完成申请注册手续后，获得钠斯平台账户的使用权，该使用权仅属于初始申请注册人。您有义务妥善保管在注册并使用钠斯平台时获得的账户及密码，并为此组账户及密码登入系统后所开始的一连串行为或活动负责。鉴于网络服务的特殊性，钠斯平台不审核是否为您本人使用该组账户及密码，仅审核账户及密码是否与数据库中保存的一致，只要任何人输入的账户及密码与数据库中保存的一致，即可凭借该组账户及密码登陆钠斯平台。若使用者并非账户初始申请注册人，<strong>钠斯平台有权在未经通知的情况下冻结、回收该账户且无需向该账户使用人承担任何法律责任，由此导致的包括并不限于您通讯中断、用户资料和虚拟道具等清空等损失由您自行承担。若账户的归属出现争议的，钠斯平台在收到相关方的投诉后，有权暂时冻结该争议账户；争议各方在合理举证期限内提供证据证明账户归属，钠斯平台依据各方提供的证据判断归属后，解冻争议账户。</strong></p></li><li><p><strong>您需独立对自己在钠斯上实施的行为承担法律责任。您理解并承诺，您使用钠斯软件及相关服务直播、上传、发布或传输的内容（包括但不限于文字、图片、音乐、视频、音频、声音、对话等各种形式的内容）均由您原创或已获合法授权。除非另有约定，您通过钠斯直播、上传、发布或传输的内容的知识产权归属您或原始著作权人所有。您应确保钠斯对前述内容的展示、公布及推广不会侵犯他人知识产权或其他合法权益，也无需向任何第三方支付任何费用，否则因此造成钠斯公司及其关联方、授权方损失的，您应承担责任予以赔偿。</strong></p></li><li><p><strong>为提高内容曝光量及发布效率，您同意您在钠斯的账号所发布的全部内容均授权钠斯以您的账号自动同步、发布至钠斯公司及其关联方、授权方运营的全部产品，包括但不限于在当前或其他网站、应用程序、产品或终端设备等。您在钠斯发布、修改、删除内容的操作，均会同步到上述产品。任何经由钠斯提供的服务，以上传、张贴、发送电子邮件或其他方式传送的文字、图片、音乐、视频、音频、声音、对话等各种形式的内容，无论系公开还是私下传送，均由内容提供者、上传者承担责任。</strong></p></li><li><p>为方便您使用钠斯和钠斯关联公司的其他相关服务，<strong>您授权钠斯将您在账户注册和使用钠斯平台服务过程中提供、形成的信息传递给钠斯关联公司等其他相关服务提供者，或从钠斯关联公司等其他相关服务提供者获取您在注册、使用相关服务期间提供、形成的信息。</strong></p></li><li><p>如您在征得钠斯同意情况下利用钠斯平台实施推广等商业行为和营销内容的，您必须在国家相关法律法规及钠斯平台关于推广等商业行为规范的基础上进行。您的商业行为和营销内容，必须本着平等、公平原则进行合理竞争，必须本真着真实准确原则进行合理推广，必须本着保护用户体验原则选择合理的推广方式及营销内容。<strong>如您进行恶意竞争，或采取虚假的商业行为、发布虚假夸大的营销内容，或发布频繁的、低质量的营销类信息，或您发布国家相关法规明令禁止的广告内容、违规开展商业行为等，钠斯有权将其内容的传播范围、传播对象作出限制，限制或禁止您使用钠斯全部或部分服务、注销账户以及依据法律法规保存有关信息并向有关部门报告等。</strong></p></li><li><p><strong>您在钠斯平台中与他人进行的商业行为引发的法律纠纷，由交易双方自行处理，钠斯不承担任何责任。</strong></p></li><li><p><strong>您理解并知晓在使用钠斯平台服务时，所接触的内容和信息来源广泛，钠斯无法对该内容和信息的准确性、真实性、可用性、安全性、完整性和正当性负责。您理解并认可您可能会接触到不正确的、令人不快的、不适当的或令人厌恶的内容和信息，您不会以此追究钠斯的相关责任。但钠斯有权依法停止传输任何前述内容并采取相应处理，包括但不限于暂停您继续使用钠斯的部分或全部服务，保存有关记录并向有关机关报告。</strong></p></li><li><p>如您发布新闻信息，你理解并确认您具备互联网新闻信息服务资质，发布的新闻信息真实准确、客观公正。转载新闻信息应当完整准确，不得歪曲新闻信息内容，并在显著位置注明来源，保证新闻信息来源可追溯。钠斯平台有权审核你发布的新闻信息，如您发布不实新闻或您不具备发布资质，<strong>钠斯平台有权将您发布内容的传播范围、传播对象作出限制，限制或禁止您使用钠斯全部或部分服务、注销账户以及依据法律法规保存有关信息并向有关部门报告等。</strong></p></li><li><p><strong>钠斯不对您在钠斯平台上传、发布或传输的任何内容和信息背书、推荐或表达观点，也不对任何内容和信息的错误、瑕疵及产生的损失或损害承担任何责任，您对内容和信息的任何使用需自行承担相关的风险。</strong></p></li><li><p>您同意钠斯在提供服务的过程中以各种方式投放商业性广告或其他任何类型的商业信息（包括但不限于在钠斯平台的任何位置上投放广告，在您上传、传播的内容中投放广告），您同意接受钠斯通过电子邮件、站内消息、手机短信、网站公告或其他方式向您发送促销或其他相关商业信息。同时，钠斯有权在直播间内添加网站logo（或名称）和时间的水印标志。钠斯将尽力但不保证不影响您的直播体验。</p></li><li><p>您应当自行约束您在钠斯平台上的用户行为，保证自己在线直播过程中的人身安全及财产安全。如您在钠斯平台网站外的场所或地域，因您自身原因、不可抗力或意外事件或第三方原因造成的人身及财产损失，由您自行承担。</p></li><li><p>使用钠斯平台服务过程中，您需遵守以下法律法规：《中华人民共和国保守国家秘密法》、《中华人民共和国著作权法》、《中华人民共和国计算机信息系统安全保护条例》、《计算机软件保护条例》、《信息网络传播权保护条例》、《互联网直播服务管理规定》等有关计算机及互联网规定的法律、法规。在任何情况下，钠斯一旦合理地认为您的行为可能违反上述法律、法规，可以在任何时候，不经事先通知终止向您提供服务。</p></li><li><p><strong>4.3禁止的内容</strong></p></li><li><p><br/></p></li><li><p><strong>您理解并保证您在钠斯平台制作、上传、发布或传输的内容（包括您的账户名称、评论、弹幕等信息）不含有以下内容：</strong></p></li><li><p>（一）反对宪法确定的基本原则的；</p></li><li><p>（二）危害国家统一、主权和领土完整，颠覆国家政权的；</p></li><li><p>（三）泄露国家秘密、危害国家安全或者损害国家荣誉和利益的；</p></li><li><p>（四）煽动民族仇恨、民族歧视，破坏民族团结，或者侵害民族风俗、习惯的；</p></li><li><p>（五）破坏国家宗教政策，宣扬邪教、迷信的；</p></li><li><p>（六）扰乱社会秩序，破坏社会稳定的；</p></li><li><p>（七）诱导未成年人违法犯罪和渲染及传播淫秽、色情、赌博、暴力、凶杀、恐怖活动或者教唆犯罪的；</p></li><li><p>（八）侮辱或者诽谤他人，侵害公民个人隐私等他人合法权益的；</p></li><li><p>（九）散布谣言，危害社会公德，损害民族优秀文化传统的；</p></li><li><p>（十）有关法律、行政法规和国家规定禁止的其他内容；</p></li><li><p><strong>如果您上传、发布或传输的内容含有以上违反法律法规的信息或内容的，或者侵犯任何第三方的合法权益，您将直接承担以上导致的一切不利后果。如因此给钠斯造成不利后果的，您应负责消除影响，并且赔偿钠斯因此导致的一切损失，包括且不限于财产损害赔偿、名誉损害赔偿、律师费、交通费等因维权而产生的合理费用。</strong></p></li><li><p><strong>4.4禁止的行为</strong></p></li><li><p><strong>您理解并保证不就钠斯平台服务进行下列的禁止的行为，也不允许任何人利用您的账户进行下列行为：</strong></p></li><li><p>1) 在注册账户时，或使用钠斯平台服务（包括但不限于上传、发布、传播信息等）时，冒充他人或机构，或您讹称与任何人或实体有联系（包括设置失实的账户名称或接入另一用户的账户），或您恶意使用注册账户导致其他用户误认；</p></li><li><p>2) 伪造标题或以其他方式操控内容，使他人误认为该内容为钠斯所传输；</p></li><li><p>3) 将无权传输的内容（例如内部资料、机密资料、侵犯任何人的专利、商标、著作权、商业秘密或其他专属权利之内容等）进行上传、发布、发送电子邮件或以其他方式传输；</p></li><li><p>4) 上传、张贴、发送电子邮件或以其他方式传送任何未获邀约或未经授权的“垃圾电邮”、广告或宣传资料、促销资料，或任何其他商业通讯；</p></li><li><p>5) 未经钠斯明确许可，使用钠斯平台服务用于任何商业用途或为任何第三方的利益；</p></li><li><p>6) 跟踪或以其他方式骚扰他人；</p></li><li><p>7) 参与任何非法或有可能非法（由钠斯判断）的活动或交易，包括传授犯罪方法、出售任何非法药物、洗钱活动、诈骗等；</p></li><li><p>8) 赌博、提供赌博数据或透过任何方法诱使他人参与赌博活动；</p></li><li><p>9) 使用或利用钠斯知识产权（包括钠斯的商标、品牌、标志、任何其他专有数据或任何网页的布局或设计），或在其他方面侵犯钠斯任何知识产权（包括试图对钠斯平台客户端或所使用的软件进行逆向工程）；</p></li><li><p>10) 通过使用任何自动化程序、软件、引擎、网络爬虫、网页分析工具、数据挖掘工具或类似工具，接入钠斯平台服务、收集或处理通过钠斯平台服务所提供的内容；</p></li><li><p>11) 参与任何“框架”、“镜像”或其他技术，目的是模仿钠斯平台服务的外观和功能；</p></li><li><p>12) 干预或试图干预任何用户或任何其他方接入钠斯平台服务；</p></li><li><p>13) 故意散播含有干扰、破坏或限制计算机软件、硬件或通讯设备功能、钠斯平台服务、与钠斯平台服务相连的服务器和网络的病毒、网络蠕虫、特洛伊木马病毒、已损毁的档案或其他恶意代码或项目等资料；</p></li><li><p>14) 未经他人明确同意，分享或发布该等人士可识别个人身份的资料；</p></li><li><p>15) 探究或测试钠斯平台服务、系统或其他用户的系统是否容易入侵攻击，或在其他方面规避（或试图规避）钠斯平台服务、系统或其他用户</p></li><li><p>的系统的任何安全功能；</p></li><li><p>16) 对钠斯平台服务所用的软件进行解编、反向编译或逆向工程，或试图作出上述事项；</p></li><li><p>17) 为破坏或滥用的目的开设多个账户，或恶意上传重复的、无效的大容量数据和信息；</p></li><li><p>18）进行任何破坏钠斯平台服务公平性或者其他影响应用正常秩序的行为，如主动或被动刷分、合伙作弊、使用外挂或者其他的作弊软件、利用BUG（又叫“漏洞”或者“缺陷”）来获得不正当的非法利益，或者利用互联网或其他方式将外挂、作弊软件、BUG公之于众；</p></li><li><p>19）进行任何诸如发布广告、销售商品的商业行为，或者进行任何非法的侵害钠斯平台利益的行为，如贩卖钠斯平台平台鱼丸鱼翅等虚拟产品、外挂等；</p></li><li><p>20）通过任何渠道或媒体（包括但不限于自媒体等）发出“与钠斯合作”、“与钠斯共同出品”等任何携带“钠斯”品牌的字样，如您宣传推广合作节目，您只能在宣传中提及节目本身而不得提及与钠斯关系或者擅自以钠斯品牌进行推广，凡是您的发稿带有“钠斯”的一切宣传稿件必须通过钠斯相应合作部门之书面同意，否则因此给钠斯造成的一切损失您应予以赔偿；</p></li><li><p>21）腾讯游戏发布的《关于直播行为规范化的公告》（及日后不时的修订）中禁止的任何不良行为。</p></li><li><p>22) 故意或非故意违反任何相关的中国法律、法规、规章、条例等其他具有法律效力的规范。</p></li><li><p>您可以在使用钠斯平台服务过程中购买鱼翅、门票等虚拟产品增值服务。若无特殊说明，您通过在钠斯平台及其他钠斯平台授权的合法渠道上获得的鱼丸鱼翅、虚拟礼物等虚拟产品，具体使用方法、期限等以钠斯平台页面中附带的说明及用户指南或具备以上解说性质的类似官方文档为准。虚拟产品会因用户需求、网站策略调整、用户接受程度等因素随时进行调整，具体信息请以当时的页面说明为准。\n基于虚拟产品的性质和特征，钠斯不提供虚拟产品的退货、换货服务。如您消耗所获得的全部虚拟产品，且不将继续使用虚拟产品服务的，则该服务终止。</p></li><li><p>您应遵守本协议的各项条款，正确、适当地使用本服务，不得扰乱钠斯平台秩序，包括但不限于扰乱钠斯平台金融秩序（如出售鱼丸鱼翅、虚拟礼物、门票等）。除非得到钠斯的书面授权，您不得将鱼丸鱼翅、虚拟礼物等虚拟产品用于商业领域，包括但不限于买卖、置换、抵押或以特定方式使用虚拟产品服务获取不当得利等。需要特别注意的是，如您涉嫌使用不合理手段充值（包括但不限于非法使用信用卡套现）的帐号，钠斯有权依合理判断将您的账户暂时或永久封停。同时，钠斯保留在任何时候收回该帐号、用户名的权利。\n任何用户都应通过正规渠道获得鱼丸鱼翅、虚拟礼物、门票等虚拟产品服务，一切通过非官方公布渠道取得的虚拟产品及其衍生服务均不对钠斯发生法律效力，钠斯有权单方面收回相关虚拟产品并终止相应服务，严重者钠斯有权对中止或终止您使用钠斯全部或部分服务、注销账户以及依据法律法规保存有关信息并向有关部门报告等。</p></li><li><p><strong>4.5用户保证</strong></p></li><li><p><strong>您特此陈述并保证：</strong></p></li><li><p>1）您当前并未在受美国制裁国家或地区（包括但不限于克里米亚、古巴、伊朗、北朝鲜、及叙利亚）居住或停留；</p></li><li><p>2）您并不属于美国政府禁止或限制主体名单（包括美国财政部海外资产办公室管理的《特别指定国民名单》Specially Designated Nationals and Blocked Persons List）的主体或人员；</p></li><li><p>3）您并不属于其他任何遭受美国或联合国经济制裁名单主体或人员，该等名单包括但不限于a）《行业制裁识别名单》Sectoral Sanctions Identifications List；b)《海外逃避制裁者名单》 Foreign Sanctions Evaders List；c)《巴勒斯坦立法会名单》NON-SDN Palestinian Legislative Council List；d)《非SDN涉伊朗制裁法案名单》Non-SDN Iran Sanctions Act List等。</p></li><li><p><strong>五、第三方链接</strong></p></li><li><p>钠斯平台服务可能会包含与其他网站或资源的链接。钠斯对于前述网站或资源的内容、隐私政策和活动，无权控制、审查或修改，因而也不承担任何责任。钠斯建议您在离开钠斯平台，访问其他网站或资源前仔细阅读其服务条款和隐私政策。</p></li><li><p><strong>六、知识产权</strong></p></li><li><p>除非另有约定或钠斯另行声明，钠斯平台内的所有内容（用户依法享有版权的内容除外）、技术、软件、程序、数据及其他信息（包括但不限于文字、图像、图片、照片、音频、视频、图表、色彩、版面设计、电子文档）的所有知识产权（包括但不限于版权、商标权、专利权、商业秘密等）及相关权利，均归钠斯或钠斯关联公司所有。未经钠斯许可，任何人不得擅自使用（包括但不限于复制、传播、展示、镜像、上载、下载、修改、出租）。</p></li><li><p>钠斯平台的Logo、“钠斯”、“nasi”、“Nasi”、“NASI”等文字、图形及其组合，以及钠斯平台的其他标识、徵记、产品和服务名称均为钠斯或钠斯关联公司在中国或其它国家的商标，未经钠斯书面授权，任何人不得以任何方式展示、使用或作其他处理，也不得向他人表明您有权展示、使用或作其他处理。</p></li><li><p>钠斯对钠斯专有内容、原创内容和其他通过授权取得的独占或独家内容享有完全知识产权。未经钠斯许可，任何单位和个人不得私自转载、传播和提供观看服务或者有其他侵犯钠斯知识产权的行为，否则将承担包括侵权等所有相关的法律责任。</p></li><li><p><strong>七、用户的违约及处理</strong></p></li><li><p><strong>7.1 违约认定</strong></p></li><li><p>发生如下情形之一的，视为您违约：</p></li><li><p>（一）使用钠斯平台服务时违反有关法律法规规定的；</p></li><li><p>（二）违反本协议或本协议补充协议（即本协议第2.2条）约定的。</p></li><li><p>为适应互联网行业发展和满足海量用户对高效优质服务的需求，您理解并同意，<strong>钠斯可在钠斯平台规则中约定违约认定的程序和标准。如：钠斯可依据您的用户数据与海量用户数据的关系来认定您是否构成违约；您有义务对您的数据异常现象进行充分举证和合理解释，否则将被认定为违约。</strong></p></li><li><p><strong>7.2 违约处理措施</strong></p></li><li><p>您在钠斯平台上发布的内容和信息构成违约的，<strong>钠斯可根据相应规则立即对相应内容和信息进行删除、屏蔽等处理或对您的账户进行暂停使用、查封、冻结或清空虚拟礼物、注销等处理。\n您在钠斯平台上实施的行为，或虽未在钠斯平台上实施但对钠斯平台及其用户产生影响的行为构成违约的，钠斯可依据相应规则对您的账户执行限制参加活动、中止向您提供部分或全部服务（如查封虚拟直播房间、冻结或清空虚拟礼物、扣划违约金等）处理措施。如您的行为构成根本违约的，钠斯可查封您的账户，终止向您提供服务。如您的账户被查封，您账户内的鱼丸鱼翅、虚拟礼物等虚拟产品将被清空，相关虚拟产品服务将被终止提供。</strong></p></li><li><p>如果您在钠斯平台上的行为违反相关的法律法规，钠斯可依法向相关主管机关报告并提交您的使用记录和其他信息。同时，钠斯可将对您上述违约行为处理措施信息以及其他经国家行政或司法机关生效法律文书确认的违法信息在钠斯平台上予以公示。<strong>除此之外，钠斯可依据国家相关法律法规规定，对您进行黑名单管理和信用管理，提供与信用等级挂钩的管理和服务，若您被纳入黑名单，有权对纳入黑名单的您采取禁止重新注册帐号的措施，并及时向相关部门报告。</strong></p></li><li><p><strong>7.3赔偿责任</strong></p></li><li><p><strong>如您的行为使钠斯及/或其关联公司遭受损失（包括但不限于自身的直接经济损失、商誉损失及对外支付的赔偿金、和解款、律师费、诉讼费等间接经济损失），您应赔偿钠斯及/或其关联公司的上述全部损失。\n如您的行为使钠斯及/或其关联公司遭受第三人主张权利，钠斯及/或其关联公司可在对第三人承担金钱给付等义务后就全部损失向您追偿。</strong></p></li><li><p><strong>7.4特别约定</strong></p></li><li><p>如您向钠斯及/或其关联公司的雇员或顾问等提供实物、现金、现金等价物、劳务、旅游等价值明显超出正常商务洽谈范畴的利益，则可视为您存在商业贿赂行为。<strong>发生上述情形的，钠斯可立即终止与您的所有合作并向您收取违约金及/或赔偿金，该等金额以钠斯因您的贿赂行为而遭受的经济损失和商誉损失作为计算依据。</strong></p></li><li><p>钠斯负责&quot;按现状&quot;和&quot;可得到&quot;的状态向您提供钠斯平台服务。钠斯依法律规定承担相应义务，但<strong>无法对由于信息网络设备维护、连接故障，电脑、通讯或其他系统的故障，黑客活动、计算机病毒、电力故障，罢工，暴乱，火灾，洪水，风暴，爆炸，战争，政府行为，司法行政机关的命令或因第三方原因而给您造成的损害结果承担责任。</strong></p></li><li><p><strong>钠斯通过中华人民共和国境内的设施控制和提供钠斯平台服务，钠斯不担保控制或提供的服务在其他国家或地区是适当的、可行的，任何在其他司法辖区使用钠斯平台服务的用户应自行确保其遵守当地的法律法规，钠斯对此不承担任何责任。</strong></p></li><li><p><strong>八、协议的变更</strong></p></li><li><p>钠斯可根据国家法律法规变化及钠斯平台服务变化的需要，不时修改本协议、补充协议，变更后的协议、补充协议（下称“变更事项”）将通过本协议第10条约定的方式通知您。更新后的协议条款一旦公布即代替原来的协议条款。</p></li><li><p><strong>如您对变更事项不同意的，您应当于变更事项确定的生效之日起停止使用钠斯平台服务；如您在变更事项生效后仍继续使用钠斯平台服务，则视为您同意已生效的变更事项。</strong></p></li><li><p><strong>九、通知</strong></p></li><li><p>您同意钠斯以以下合理的方式向您送达各类通知：</p></li><li><p>（一）公示的文案；</p></li><li><p>（二）站内消息、弹出消息、客户端推送的消息；</p></li><li><p>（三）根据您预留于钠斯平台的联系方式发出的电子邮件、手机短信、函件等；</p></li><li><p>钠斯通过上述方式向您发出通知，在发送成功后即视为送达；以纸质载体发出的书面通知，按照提供联系地址交邮后的第五个自然日即视为送达。</p></li><li><p>对于在钠斯平台上因交易活动引起的任何纠纷，您同意司法机关（包括但不限于人民法院）可以通过手机短信、电子邮件等现代通讯方式或邮寄方式向您送达法律文书（包括但不限于诉讼文书）。您指定接收法律文书的手机号码、电子邮箱等联系方式为您在钠斯平台注册、更新时提供的手机号码、电子邮箱联系方式，司法机关向上述联系方式发出法律文书即视为送达。您指定的邮寄地址为您的法定联系地址或您提供的有效联系地址。</p></li><li><p>您同意司法机关可采取以上一种或多种送达方式向您达法律文书，司法机关采取多种方式向您送达法律文书，送达时间以上述送达方式中最先送达的为准。</p></li><li><p>您同意上述送达方式适用于各个司法程序阶段。如进入诉讼程序的，包括但不限于一审、二审、再审、执行以及督促程序等。</p></li><li><p>你应当保证所提供的联系方式是准确、有效的，并进行实时更新。如果因提供的联系方式不确切，或不及时告知变更后的联系方式，使法律文书无法送达或未及时送达，由您自行承担由此可能产生的法律后果。</p></li><li><p><strong>十、协议的终止</strong></p></li><li><p><strong>10.1 终止的情形</strong></p></li><li><p><strong>您有权通过以下任一方式终止本协议：</strong></p></li><li><p><strong>（一）在满足钠斯平台网站公示的账户注销等清理条件时您通过网站注销您的账户的；</strong></p></li><li><p><strong>（二）变更事项生效前您停止使用并明示不愿接受变更事项的；</strong></p></li><li><p><strong>（三）您明示不愿继续使用钠斯平台服务，且符合钠斯平台终止条件的。</strong></p></li><li><p><strong>出现以下情况时，钠斯可以本协议第10条的所列的方式通知您中止或终止本协议：</strong></p></li><li><p>（一）您违反本协议约定，钠斯依据违约条款终止本协议的</p></li><li><p>（二）您转让本人账户、盗用他人账户、提供虚假注册身份信息、发布违禁内容和信息、骗取他人财物、采取不正当手段谋利等行为，钠斯依据钠斯平台规则对您的账户予以查封的；</p></li><li><p>（三）除上述情形外，因您多次违反钠斯平台规则相关规定且情节严重，钠斯依据钠斯平台规则对您的账户予以查封的；</p></li><li><p>（四）您的账户被钠斯依据本协议进行注销等清理的；</p></li><li><p>（五）您在钠斯平台有侵犯他人合法权益或其他严重违法违约行为的；</p></li><li><p>（六）其它根据相关法律法规钠斯应当终止服务的情况。</p></li><li><p><strong>10.2 协议终止后的处理</strong></p></li><li><p><strong>本协议终止后，除法律有明确规定外，钠斯无义务向您或您指定的第三方披露您账户中的任何信息。</strong></p></li><li><p>本协议终止后，钠斯享有下列权利：</p></li><li><p>（一）停止收集和使用您的个人信息，但可继续保存您留存于钠斯平台的其他内容和信息；</p></li><li><p>（二）对于您过往的违约行为，钠斯仍可依据本协议向您追究违约责任。</p></li><li><p><strong>十一、法律适用、管辖与其他</strong></p></li><li><p><strong>本协议之订立、生效、解释、修订、补充、终止、执行与争议解决均适用中华人民共和国大陆地区法律；如法律无相关规定的，参照商业惯例及/或行业惯例。</strong></p></li><li><p><strong>您因使用钠斯平台服务所产生及与钠斯平台服务有关的争议，由钠斯与您协商解决。协商不成时，任何一方均可向宜昌市高新区人民法院提起诉讼。</strong></p></li><li><p>本协议任一条款被视为废止、无效或不可执行，该条应视为可分的且并不影响本协议其余条款的有效性及可执行性。</p></li></ul>', 1);
INSERT INTO `db_protocal` VALUES (2, '隐私权政策', '<ul class=\" list-paddingleft-2\">\n    <li>\n        <p>\n            <strong>亲爱的钠斯用户，感谢您信任并使用钠斯的产品和服务，近期我们对钠斯的《隐私权政策》进行了更新，本次更新集中在钠斯如何从第三方获取您的信息及信息共享的内容，请您仔细阅读并充分理解相关条款。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>提示条款</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            您的信任对我们非常重要，我们深知个人信息对您的重要性 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>（本政策所指个人信息及个人敏感信息所包含的内容出自国家标准GB/T35273《信息安全技术 个人信息安全规范》）</strong>，\n &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;我们将按照法律法规要求，采取相应安全保护措施，尽力保护您的个人信息安全可控。鉴于此，钠斯平台制定本《隐私权政策》（下称“本政策/本隐私权政策”）并提醒您：\n        </p>\n    </li>\n    <li>\n        <p>\n            本政策适用于钠斯网站提供的所有产品和服务。<strong>未满14周岁的用户，同时适用本政策以及<a href=\"#children\" style=\"color:red;font-weight: bold;text-decoration: underline;\">《儿童隐私保护声明》</a>。</strong><span class=\"text-underline\">如我们关联公司的产品或服务中使用了钠斯平台的产品或服务（例如使用钠斯平台帐号登陆）但未设独立隐私权政策的，则本政策同样适用于该产品或服务。</span>\n        </p>\n    </li>\n    <li>\n        <p>\n            需要特别说明的是，<span class=\"text-underline\">本政策不适用其他独立第三方向您提供的服务，也不适用于钠斯平台中已另行独立设置法律声明及隐私权政策的产品或服务。</span>例如钠斯平台上第三方依托本平台向您提供服务时，您向第三方提供的个人信息不适用于本隐私政策，钠斯对任何第三方使用由您提供的信息不承担任何责任。\n        </p>\n    </li>\n    <li>\n        <p>\n            <span class=\"text-underline\">在使用钠斯平台各项产品或服务前，请您务必仔细阅读并透彻理解本政策，特别是以粗体/粗体下划线标识的条款，您应重点阅读，在确认充分理解并同意后使用相关产品或服务。</span>一旦您开始使用钠斯平台各项产品或服务，即表示您已充分理解并同意本政策。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>第一部分 定义</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>钠斯平台：</strong>指钠斯网（域名为nasinet.com）网站及客户端。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>钠斯平台服务：</strong>钠斯基于互联网，以包含钠斯平台网站、客户端等在内的各种形态（包括未来技术发展出现的新的服务形态）向您提供的各项服务。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>钠斯平台服务提供者：</strong>武汉钠斯网络科技有限公司及其关联方，包括但不限于武汉瓯越网视有限公司，武汉钠斯鱼乐网络科技有限公司及其境内分支机构。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>用户：</strong>下称“您”或“用户”，是指注册、登录、使用、浏览、获取本政策项下服务的个人或组织。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>个人信息：指以电子或者其他方式记录的能够单独或者与其他信息结合识别特定自然人身份或者反映特定自然人活动情况的各种信息，包括自然人姓名、出生日期、身份证号码、个人生物识别信息、住址、电话号码等。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>个人敏感信息：指一旦泄露、非法提供或滥用可能危害人身和财产安全，极易导致个人名誉、身心健康受到损害或歧视性待遇的个人信息，包括身份证号码、个人生物识别信息、银行账户、财产信息、行踪轨迹、通信内容、未成年人信息、健康生理信息等息。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>第二部分 隐私政策</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            本隐私权政策部分将帮助您了解以下内容：\n        </p>\n    </li>\n    <li>\n        <p>\n            一、钠斯如何收集和使用信息\n        </p>\n    </li>\n    <li>\n        <p>\n            二、钠斯如何共享、转让、公开披露信息\n        </p>\n    </li>\n    <li>\n        <p>\n            三、Cookie的使用\n        </p>\n    </li>\n    <li>\n        <p>\n            四、信息存储\n        </p>\n    </li>\n    <li>\n        <p>\n            五、钠斯如何保护您的信息\n        </p>\n    </li>\n    <li>\n        <p>\n            六、您如何管理您的信息\n        </p>\n    </li>\n    <li>\n        <p>\n            七、钠斯如何处理未成年人信息\n        </p>\n    </li>\n    <li>\n        <p>\n            八、本隐私权政策如何更新\n        </p>\n    </li>\n    <li>\n        <p>\n            九、如何联系我们\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>一、钠斯如何收集和使用信息</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            在您使用我们的产品及/或服务时，我们需要/可能需要收集和使用您的个人信息包括如下两种：\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>1、为实现向您提供我们产品及/或服务的基本功能，您需授权我们收集、使用的必要信息。如您拒绝提供相应信息，您将无法正常使用我们的产品及/或服务。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>2、为实现向您提供我们的产品及/或服务的附加功能，您可选择授权我们收集、使用的信息。如您拒绝提供，您将无法正常使用相关附加功能或无法达到我们拟达到的功能效果，但并不会影响您正常使用我们产品及/或服务的基本功能。 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>（一）基础功能信息收集和使用</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            1、<strong>您注册并登陆钠斯帐号时，</strong><span class=\"text-underline\">需要向我们提供以下信息：帐号名称、头像（如有）和手机号码（用于实名认证）。提供上述信息并同意《用户注册协议》和本政策，你可以使用钠斯观看直播内容，发布弹幕信息。</span>如果您选择不提供为实现钠斯基础业务功能的必备信息或使用其中某类功能的必备信息，将导致我们无法为您提供服务。\n        </p>\n    </li>\n    <li>\n        <p>\n            2、<span class=\"text-underline\">在您注册钠斯帐号时，我们会需要您提供手机号码进行实名认证，如您拒绝提供手机号码进行实名验证，将导致注册不成功，因此无法使用发布信息的相关功能。但是您可以退出注册页面后以“游客”身份观看直播。</span>\n        </p>\n    </li>\n    <li>\n        <p>\n            3、<strong>浏览、关注功能。</strong>您可浏览的内容包括主播直播间、主播鱼吧、钠斯点播视频、钠斯音频，在浏览的过程中，您还可以关注您感兴趣的主播及用户。<strong>为此，我们会收集您使用钠斯时的设备信息（包括设备型号、唯一设备标识符、操作系统版本、网络设备硬件、地址MAC等软硬件特征信息）。我们还会收集您的浏览器类型，以此来为您提供页面展示的最优方案。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            4、<strong>信息发布功能。</strong>您注册成为钠斯用户后，可在直播间内发送弹幕、发布鱼吧帖子、对其他用户鱼吧内容进行评论、在钠斯战队群组内发送信息。<strong>在此过程中，我们会收集您的设备信息、浏览器类型、日志信息。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>（二）附加功能信息收集和使用</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            1、<strong>基于相机/摄像头的附加功能。</strong>您可在开启相机/摄像头权限后使用该功能进行扫码用于登陆、支付、拍摄照片或视频上传、分享，以及特定场景下经您授权的人脸识别等功能。<span class=\"text-underline\">当您使用该附加功能进行人脸识别时我们会收集您的面部特征，且严格在经您授权同意的范围内使用。</span>\n        </p>\n    </li>\n    <li>\n        <p>\n            2、<strong>基于相册（图片库/视频库）的图片/视频访问及上传的附加功能。</strong>您可以在开启权限后使用相关功能上传您的照片/图片/视频，以实现更换头像、发布动态等功能。\n        </p>\n    </li>\n    <li>\n        <p>\n            3、<strong>基于麦克风的语音技术的附加功能。</strong>您可在开启麦克风权限后使用麦克风实现语音连麦聊天或录音。<span class=\"text-underline\">在此过程中，我们会收集您的录音内容。请您知晓，即使您已同意开启麦克风权限，我们也仅会在您主动点击客户端内麦克风图标时通过麦克风获取语音信息。</span>\n        </p>\n    </li>\n    <li>\n        <p>\n            4、<strong>定位功能。</strong>当您开启设备定位功能并使用钠斯基于位置提供的相关服务时，我们会获取<span class=\"text-underline\">设备所在位置信息（包括IP地址、GPS位置以及能够提供相关信息的WLAN接入点、蓝牙和基站等传感器信息）。</span>\n        </p>\n    </li>\n    <li>\n        <p>\n            5、<strong>主播身份认证服务。</strong>当您申请成为钠斯平台的主播时，根据法律法规要求，钠斯将对您个人信息进行认证、核实。因此，当您申请成为钠斯平台主播，<span class=\"text-underline\">我们需要收集您的真实姓名、性别、年龄、国籍、IP地址、身份证号、面部特征信息、电子邮箱、电话号码。</span>\n        </p>\n    </li>\n    <li>\n        <p>\n            6、<strong>搜索。</strong>您使用钠斯的搜索服务时，我们会收集您的<strong>相关搜索关键字信息、日志记录</strong>。为了提供高效的搜索服务，部分前述信息会暂时存储在您的本地存储设备之中，并可向您展示搜索结果内容、搜索历史记录。\n        </p>\n    </li>\n    <li>\n        <p>\n            7、<strong>客户服务。</strong>当您向钠斯提起投诉、申诉或进行咨询时，为了方便与您联系或帮助您解决问题，<span class=\"text-underline\">我们可能需要您提供姓名、手机号码、电子邮件。</span>如您拒绝提供上述信息，我们可能无法及时反馈投诉、申诉或咨询结果。\n        </p>\n    </li>\n    <li>\n        <p>\n            8、<strong>支付功能。</strong>您可以在钠斯购买鱼翅礼物道具、会员增值服务。在您支付过程中，<span class=\"text-underline\">我们可能会收集您的第三方支付账号，例如支付宝账号、微信支付账号、PayPal账号或其他形式的银行卡信息。如果您开通了指纹支付，我们还可能需要收集您的生物识别信息用于付款验证。</span>\n        </p>\n    </li>\n    <li>\n        <p>\n            9、<strong>安全保障。</strong>为提高您使用我们及我们关联公司、合作伙伴提供服务的安全性，保护您或其他用户或公众的人身财产安全免受侵害，更好的预防钓鱼网站、欺诈、网络漏洞、计算机病毒、网络攻击、网络入侵等安全风险，我们会收集为实现安全保障功能的必要信息。 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>我们可能使用或整合您的帐号信息、交易信息、设备信息、服务日志信息以及我们关联公司、合作伙伴取得您授权或依据法律共享的信息，来综合判断您帐号交易风险、进行身份验证、检测及防范安全事件，并采取必要的记录、审计、分析、处置措施。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            （1）为了保障软件与服务的安全运行，我们会收集您的<strong>设备信息（包括设备型号、唯一设备标识符、操作系统版本、网络设备硬件、地址MAC等软硬件特征信息）、设备所在位置信息（包括IP地址、GPS位置以及能够提供相关信息的WLAN接入点、蓝牙和基站等传感器信息）、网络接入方式、类型、状态、网络质量数据、操作、使用、服务日志。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            （2）为了预防恶意程序及安全运营所必需，我们会收集<strong>安装的应用信息或正在运行的进程信息、应用程序的总体运行、使用情况与频率、应用崩溃情况、总体安装使用情况、性能数据、应用来源。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            （3）我们可能使用您的<strong>帐号信息、交易信息、设备信息、服务日志信息</strong>以及我们关联方、合作方在获得您授权或依法可以共享的信息，用于判断账户安全、进行身份验证、检测及防范安全事件，并采取必要的记录、审计、分析、处置措施。\n        </p>\n    </li>\n    <li>\n        <p>\n            10、<strong>定向推送。</strong>我们会基于收集的信息，对您的偏好、习惯、位置作特征分析或用户画像，以便为您提供更适合的定制化服务，<strong>为此，我们需要收集您的设备信息、浏览器型号、设备安装应用列表信息、日志信息。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>上述日志信息包括：</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            （1）您操作、使用的行为信息：点击、关注、收藏、搜索、浏览、分享；\n        </p>\n    </li>\n    <li>\n        <p>\n            （2）您主动提供的信息：反馈、打赏、发布信息（如弹幕、贴吧）；\n        </p>\n    </li>\n    <li>\n        <p>\n            （3）设备所在位置信息：IP地址、GPS位置以及能够提供相关信息的WLAN接入点、蓝牙和基站等传感器信息，<strong><span class=\"text-underline\">GPS地里位置是个人敏感信息</span>，若您拒绝提供，我们将不会根据GPS信息向您推送内容，且不会影响钠斯其他功能的正常使用。</strong> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br/><strong>除非取得您授权或法律法规另有规定外，我们不会将上述信息与您在应用程序中提供的任何个人身份信息相结合，以确保不识别到您个人。</strong>我们可能将上述信息与来自我们服务所收集的其他非身份信息结合，通过程序算法进行特征与偏好分析、基于特征标签进行间接人群画像，用以向您定向推荐、展示或推送您可能感兴趣的或更适合您的特定功能或服务。\n        </p>\n    </li>\n    <li>\n        <p>\n            （1）向您展示、推荐或推送与您有更高相关程度的直播、音视频，但您可以通过【关注页→设置管理→推荐管理】关闭“为你推荐”服务。\n        </p>\n    </li>\n    <li>\n        <p>\n            （2）向您推荐与您相关程度更高的个性化广告。个性化广告可以减少无关广告对您的侵扰，您可以<a href=\"/protocal/privacyAd\" style=\"color:red;font-weight: bold;text-decoration: underline;\">点击这里</a>了解并管理个性化广告的内容。\n        </p>\n    </li>\n    <li>\n        <p>\n            11、<strong>订阅提醒服务。</strong>您使用钠斯直播的赛事或直播活动的开播预定功能时，我们会请求您授权/写入您日历的权限，以便我们记录并向您通知直播开始时间。我们不会访问、改变您日历中与钠斯直播无关的内容。\n        </p>\n    </li>\n    <li>\n        <p>\n            12、<strong>营销活动。</strong>我们将会不时举办线上或线下的营销活动。<strong>在此类营销活动中，我们需要用户提供身份信息或收件信息，以便我们能够提供票务服务或寄送礼品。在涉及钠斯向您支付费用的情况下，我们可能需要收集您的身份信息用于代缴税款。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>（三）来自第三方的信息</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            1、<strong>第三方帐号授权</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>（1）您选择第三方帐号登陆钠斯时，可选择授权钠斯读取并获得您在该第三方平台上登记、公布、记录的公开信息（包括昵称、头像）。钠斯需要您授权从第三方获取上述信息是为了记住您作为钠斯用户的登陆身份，以向您提供更优质的产品和/或服务。我们仅会在您授权同意的范围内收集并使用您的个人信息。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            在您使用第三方帐号注册或登陆后，您的第三方帐号将会与钠斯帐号进行绑定，但您可以在【设置→帐号绑定设置】中选择关闭，对帐号解绑。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>（2）您选择授权绑定钠斯帐号及相关游戏帐号时，我们会获取您游戏帐号中的昵称、头像信息以及游戏中的操作信息，具体包括您的登录状态、对战信息状态、成就信息等，您授权平台向您本人或其他用户或好友展示该等信息，基于用户体验优化之目的，上述信息会用于直播间、列表、推荐、搜索场景以数据应用产品形式出现。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            您的钠斯帐号与游戏帐号绑定后，您可以在钠斯移动端【直播设置→直播分类→游戏帐号绑定】或钠斯直播伴侣【工具→游戏帐号绑定】中进行再设置。\n        </p>\n    </li>\n    <li>\n        <p>\n            2、<strong>其他用户分享的信息中含有您的信息。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>（四）敏感信息</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            在向钠斯提供任何属于敏感信息的个人信息前，请您清楚考虑该等提供是恰当的并且同意您的个人敏感信息可按本政策所述的目的和方式进行处理。\n        </p>\n    </li>\n    <li>\n        <p>\n            我们会在得到您的同意后收集和使用您的敏感信息以实现与钠斯业务相关的功能，并允许您对这些敏感信息的收集与使用做出不同意的选择，但是拒绝使用这些信息会影响您使用相关功能。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>（五）征得授权同意的例外</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>在以下情形中，我们可以在不征得您授权同意的情况下收集、使用一些必要的个人信息：</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            1、与国家安全、国防安全直接相关的；\n        </p>\n    </li>\n    <li>\n        <p>\n            2、与公共安全、公共卫生、重大公共利益直接相关的；\n        </p>\n    </li>\n    <li>\n        <p>\n            3、与犯罪侦查、起诉、审判和判决执行等直接相关的；\n        </p>\n    </li>\n    <li>\n        <p>\n            4、出于维护您或其他个人的生命、财产等重大合法权益但又很难得到本人同意的；\n        </p>\n    </li>\n    <li>\n        <p>\n            5、所收集的个人信息是您自行向社会公众公开的；\n        </p>\n    </li>\n    <li>\n        <p>\n            6、从合法公开披露的信息中收集到您的个人信息，如从合法的新闻报道、政府信息公开等渠道；\n        </p>\n    </li>\n    <li>\n        <p>\n            7、根据您的要求签订和履行合同所必需的；\n        </p>\n    </li>\n    <li>\n        <p>\n            8、用于维护钠斯的产品和/或服务的安全稳定运行所必需的，例如发现、处置产品或服务的故障；\n        </p>\n    </li>\n    <li>\n        <p>\n            9、为合法的新闻报道所必需的；\n        </p>\n    </li>\n    <li>\n        <p>\n            10、学术研究机构基于公共利益开展统计或学术研究所必要，且对外提供学术研究或描述的结果时，对结果中所包含的个人信息进行去标识化处理的；\n        </p>\n    </li>\n    <li>\n        <p>\n            11、法律法规规定的其他情形。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>如我们停止运营钠斯网产品或服务，我们将及时停止继续收集您个人信息的活动，将停止运营的通知以逐一送达或公告的形式通知您，并对我们所持有的与已关停业务相关的个人信息进行删除或匿名化处理。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>二、钠斯如何共享、转让、公开披露信息</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>（一）共享</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            钠斯对您的信息承担保密义务，不会出售或出租您的任何信息，但以下情况除外：\n        </p>\n    </li>\n    <li>\n        <p>\n            1、<strong>在法定情形下的共享：</strong>我们可能会根据法律法规规定、诉讼、争议解决需要，或按行政、司法机关依法提出的要求，对外共享您的个人信息。\n        </p>\n    </li>\n    <li>\n        <p>\n            2、<strong>在获取明确同意的情况下共享：</strong>获得您的明确同意后，我们会与其他方共享您的个人信息。\n        </p>\n    </li>\n    <li>\n        <p>\n            3、<strong>在法律要求或允许的范围内，为了保护钠斯及其用户或社会公众的利益、财产或安全免遭损害而有必要提供您的个人信息给第三方。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            4、<strong>与关联公司间共享：</strong>为便于我们基于钠斯平台帐号向您提供产品或服务，推荐您可能感兴趣的信息，识别钠斯帐号异常，您的个人信息可能会与我们的关联公司和/或其指定的服务提供商共享。我们只会共享必要的个人信息，且受本隐私政策中所声明目的的约束，如果我们共享您的个人敏感信息或关联公司改变个人信息的使用及处理目的，将再次征求您的授权同意。\n        </p>\n    </li>\n    <li>\n        <p>\n            5、<strong>与授权合作伙伴共享：为了向您提供更完善、优质的产品和服务，我们可能委托授权合作伙伴或使用第三方SDK相关技术为您提供服务或代表我们履行职能。</strong>我们仅会出于本隐私权政策声明的合法、正当、必要、特定、明确的目的共享您的信息，合作伙伴只能接触到其履行职责所需信息，且不得将此信息用于其他任何目的。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>目前，我们的授权信息包括以下内容：</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>（1）实现功能或服务的共享信息</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            a.当您在使用钠斯中由我们的关联方、第三方提供的功能，或者当软件服务提供商、智能设备提供商、系统服务提供商与我们联合为您提供服务时我们会将实现业务所必需的信息与这些关联方、第三方共享，用于综合统计并通过算法做特征与偏好分析，形成间接人群画像，用以向您进行推荐、展示或推送您可能感兴趣的信息，或者推送更适合您的特定功能、服务或商业广告。\n        </p>\n    </li>\n    <li>\n        <p>\n            b.登陆、绑定其他帐号：当您使用钠斯帐号登陆第三方的产品或服务时，或将钠斯帐号与其他第三方帐号绑定，经过您的同意，我们会将您的<strong>昵称、头像及其他您授权的信息</strong>与前述产品或服务共享。\n        </p>\n    </li>\n    <li>\n        <p>\n            c.当您选择发布信息可以同步到我们的关联方或第三方的产品或服务后，钠斯可能会使用SDK或相关技术与这些产品或服务的提供方共享<strong>发布的内容及评论等信息</strong>。\n        </p>\n    </li>\n    <li>\n        <p>\n            d.小程序：当您使用小程序时，未经您同意，我们不会向这些开发者、运营者共享您的个人信息。当您使用小程序时，小程序可能会使用您授权的相关系统权限，您可以在小程序中撤回授权。\n        </p>\n    </li>\n    <li>\n        <p>\n            e..地理位置服务：当您使用地理位置相关服务时，我们会通过SDK或相关技术将设备位置信息与位置服务提供商进行共享以便可以向您反应位置结果，内容包括：<strong>IP信息、GPS信息、WLAN接入点、蓝牙和基站传感器信息。</strong><span class=\"text-underline\">GPS信息是个人敏感信息，拒绝提供仅会影响地里位置服务功能，但不影响其他功能的正常使用。</span>\n        </p>\n    </li>\n    <li>\n        <p>\n            f.支付功能：支付功能由与我们合作的第三方支付机构向您提供服务。第三方支付机构可能需要收集您的<strong>姓名、银行卡类型及卡号、有效期及手机号码。</strong><span class=\"text-underline\">银行卡号、有效期及手机号码是个人敏感信息，这些信息是支付功能所必需的信息，拒绝提供将导致您无法使用该功能，但不影响其他功能的正常使用。</span>\n        </p>\n    </li>\n    <li>\n        <p>\n            g.为与您使用的终端机型适配消息推送功能，我们可能会通过SDK等技术与终端设备制造商共享<strong>手机型号、版本及相关设备信息。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            h.免流量服务:当您使用部分运营商推出的通信套餐服务并使用我们的产品时，可能需要您提供<strong>设备信息、手机号码、运营商、设备系统版本、软件安装列表。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>（2）实现广告相关的共享信息</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            a.广告推送：我们可能与委托我们进行推广和广告投放的合作伙伴共享<strong>不识别您个人身份的间接画像标签及去标识化的设备信息、匿名化的浏览记录、搜索习惯、常用软件应用列表信息，</strong>以帮助其在不识别您个人身份的前提下提升广告有效触达率。\n        </p>\n    </li>\n    <li>\n        <p>\n            b.广告统计：我们可能与业务的服务商、供应商和其他合作伙伴共享分析去标识化的<strong>设备信息或统计信息</strong>，这些信息难以或无法与您的真实身份相关联。这些信息将帮助我们分析、衡量广告和相关服务的有效性。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>（3）实现安全与分析统计的共享信息</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            a.保障使用安全：我们非常重视帐号、服务及内容安全，为保障您和其他用户的帐号与财产安全，使您和我们的正当合法权益免受不法侵害，我们和关联方或服务提供商可能会共享必要的<strong>设备信息、设备所在位置相关信息、日志信息、应用信息或正在运行的进程信息、应用程序的总体运行、使用情况与频率、应用崩溃情况、总体安装使用情况、性能数据、应用来源。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            b.分析产品使用情况：为分析我们服务的使用情况，提升用户使用的体验，可能会与关联方或第三方共享<strong>产品使用情况（崩溃、闪退）的统计性数据</strong>，这些数据难以与其他信息结合识别您的个人身份。\n        </p>\n    </li>\n    <li>\n        <p>\n            c.学术研究与科研：为提升相关领域的科研能力，促进科技发展水平，我们在确保数据安全与目的正当的前提下，可能会与科研院所、高校等机构共享去标识化或匿名化的数据。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>（4）帮助您参加营销推广活动</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            当您选择参加我们及我们的关联方或第三方举办的有关营销活动时，可能需要您提供<span class=\"text-underline\">姓名、通信地址、联系方式、银行账号</span>信息。<strong>这些信息是个人敏感信息，</strong>拒绝提供可能会影响您参加相关活动，但不会影响其他功能。只有经过您的同意，我们才会将这些信息与关联方或第三方共享，以保障您在联合活动中获得体验一致的服务，或委托第三方及时向您兑换奖励。<br/>\n        </p>\n    </li>\n    <li>\n        <p>\n            请您理解，我们会对合作伙伴或其获取信息的软件工具开发包进行严格检测，以保护数据安全。<a class=\"red\" href=\"/protocal/thirdSdkList\" target=\"_blank\">合作伙伴SDK或相关技术获取用户信息情况披露点此了解</a>。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>（二）转让</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            钠斯不会将您的个人信息转让给任何公司、组织和个人，但以下情况除外：\n        </p>\n    </li>\n    <li>\n        <p>\n            1、获得您的明确同意后，我们会向其他方转让您的个人信息；\n        </p>\n    </li>\n    <li>\n        <p>\n            2、在钠斯平台服务提供者发生合并、收购或破产清算情形，或其他涉及合并、收购或破产清算情形时，如涉及到个人信息转让，我们会要求新的持有您个人信息的公司、组织继续收本政策的约束，否则我们将要求该公司、组织或个人重新向您征求授权同意。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>（三）公开披露</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            钠斯仅会在以下情况下，公开披露您的个人信息：\n        </p>\n    </li>\n    <li>\n        <p>\n            1、获得您明确同意或基于您的主动选择，我们可能会公开披露您的个人信息；\n        </p>\n    </li>\n    <li>\n        <p>\n            2、<strong>如果我们确定您出现违反法律法规或严重违反钠斯平台相关协议及规则的情况，或为保护钠斯平台用户或公众的人身财产安全免遭侵害，我们可能依据法律法规或征得您同意的情况下披露关于您的个人信息。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>（四）共享、转让、公开披露个人信息时事先征得授权同意的例外</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            以下情形中，共享、转让、公开披露您的个人信息无需事先征得您的授权同意：\n        </p>\n    </li>\n    <li>\n        <p>\n            1、与国家安全、国防安全有关的；\n        </p>\n    </li>\n    <li>\n        <p>\n            2、与公共安全、公共卫生、重大公共利益有关的；\n        </p>\n    </li>\n    <li>\n        <p>\n            3、与犯罪侦查、起诉、审判和判决执行等司法或行政执法有关的；\n        </p>\n    </li>\n    <li>\n        <p>\n            4、出于维护您或其他个人的生命、财产等重大合法权益但又很难得到本人同意的；\n        </p>\n    </li>\n    <li>\n        <p>\n            5、您自行向社会公众公开的个人信息；\n        </p>\n    </li>\n    <li>\n        <p>\n            6、从合法公开披露的信息中收集个人信息的，如合法的新闻报道、政府信息公开等渠道；\n        </p>\n    </li>\n    <li>\n        <p>\n            7、学术研究机构基于公共利益开展统计或学术研究所必要，且对外提供学术研究或描述的结果时，对结果中所包含的个人信息进行去标识化处理的；\n        </p>\n    </li>\n    <li>\n        <p>\n            8、法律法规规定的其他情形。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>请知悉，若我们对个人信息采取技术措施和其他必要措施进行处理，使得数据接收方无法重新识别特定个人且不能复原，则此类处理后数据的共享、转让、公开披露无需另行向您通知并征得您的同意。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>三、Cookie的使用</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            为使您获得更轻松的访问体验，您访问钠斯平台网站或使用钠斯平台提供的服务时，钠斯可能会通过小型数据文件识别您的身份，这么做是帮您省去重复输入注册信息的步骤，或者帮助判断您的帐号安全。这些数据文件可能是Cookie，Flash Cookie，或您的浏览器或关联应用程序提供的其他本地存储（统称“Cookie”）。 请您理解，钠斯的某些服务只能通过使用“Cookie”才可得到实现。如果您的浏览器或浏览器附加服务允许，您可以修改对Cookie的接受程度或者拒绝钠斯的Cookie，但这一举动在某些情况下可能会影响您安全访问钠斯平台相关网站和使用钠斯平台提供的服务。\n        </p>\n    </li>\n    <li>\n        <p>\n            我们使用Cookie和同类技术主要为了实现以下功能或服务：\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>1、保障产品与服务的安全、高速运转</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            我们可能会设置认证与保障安全性的Cookie或匿名标识符，使我们确认您是否安全登陆服务，或者是否遇到盗用、欺诈及其他不法行为。这些技术还会帮助我们改进服务效率，提升登陆和响应速度。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>2、帮助您获得更轻松的访问体验</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            使用此类技术可以帮助您省去重复填写个人信息、输入搜索内容的步骤和流程。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>3、为您推荐、展示、推送您可能感兴趣的直播或帐号</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            我们可能利用此类技术了解您的偏好和使用习惯，收集的信息包括<strong>您的设备信息、浏览信息、点击信息，并将该等信息存储为日志信息</strong>用于数据分析，以改善产品服务、推荐用户感兴趣的信息或功能，并优化您对广告的选择。同时，我们也可能会使用第三方SDK实现以上采集和使用。\n        </p>\n    </li>\n    <li>\n        <p>\n            在钠斯的分享页中，我们可能会使用Cookie对浏览活动进行记录，用于向您推荐信息和排查崩溃、延迟的相关异常情况以及探索更好的服务方式。\n        </p>\n    </li>\n    <li>\n        <p>\n            钠斯提醒您，大多数浏览器均为用户提供了清除浏览器缓存数据的功能，您可以在浏览器设置功能中进行相应的数据清除操作。如您进行清除，您可能无法使用由我们提供的、依赖于Cookie的服务或相应功能。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>四、信息存储</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>我们在中华人民共和国境内运营中收集和产生的个人信息存储在中国境内，不会对您的个人信息跨境传输。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>我们承诺只会在达成本政策所述目的所必需的最短时间保留您的个人信息。</strong>但在下列情况下，我们可能因需符合法律要求，更改个人信息的存储时间：\n        </p>\n    </li>\n    <li>\n        <p>\n            1、为遵守适用的法律法规规定，例如，《中华人民共和国网络安全法》第二十一条第三款要求监测、记录网络运行状态、网络安全事件的技术措施的网络日志留存不得少于六个月；\n        </p>\n    </li>\n    <li>\n        <p>\n            2、为遵守法院判决、裁定或其他法律程序的规定；\n        </p>\n    </li>\n    <li>\n        <p>\n            3、为遵守政府机关或法定授权组织的要求；\n        </p>\n    </li>\n    <li>\n        <p>\n            4、为执行钠斯平台协议或本政策、维护社会公共利益，保护钠斯及钠斯关联公司、其他用户或雇员的人身安全或其他合法权益所合理必需的用途。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>在您的个人信息超出保留期间后，我们会删除您的个人信息，或使其匿名化处理。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>五、钠斯如何保护您的信息</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            为保障您的信息安全，钠斯已采取符合业界标准、合理可行的各类物理、电子和管理方面的安全措施来保护您的信息，使您的信息不会被泄漏、毁损或者丢失，包括但不限于设置部署主机入侵检测系统、重要敏感数据加密存储、日志记录安全审计。\n        </p>\n    </li>\n    <li>\n        <p>\n            钠斯对可能接触到您的信息的员工采取了严格管理，包括但不限于根据岗位的不同采取不同的权限控制，与他们签署保密协议，监控他们的操作情况等措施。钠斯会按现有技术提供相应的安全措施来保护您的信息，提供合理的安全保障，钠斯将尽力做到使您的信息不被泄漏、毁损或丢失。\n        </p>\n    </li>\n    <li>\n        <p>\n            您的帐号均有安全保护功能，请妥善保管您的帐号及密码信息。钠斯将通过向其它服务器备份、对用户密码进行加密等安全措施确保您的信息不丢失，不被滥用和变造。互联网并非绝对安全的环境，尽管有前述安全措施，但同时也请您理解在信息网络上不存在“完善的安全措施”。\n        </p>\n    </li>\n    <li>\n        <p>\n            在使用钠斯平台服务进行网上交易时，您不可避免的要向交易对方或潜在的交易对方披露自己的个人信息，如银行账户信息、联络方式或者邮寄地址。我们强烈建议您不要使用非钠斯平台推荐的通信方式发送您的信息。请您妥善保护自己的个人信息，仅在必要的情形下向他人提供。如您发现自己的个人信息泄密，尤其是你的帐号及密码发生泄露，请您立即联络钠斯客服，以便钠斯采取相应措施。\n        </p>\n    </li>\n    <li>\n        <p>\n            在不幸发生个人信息安全事件后，我们将按照法律法规的要求向您告知安全事件的基本情况和可能的影响、我们已采取或将要采取的处置措施、您可自主防范和降低风险的建议、对您的补救措施等。事件相关情况我们将以邮件、信函、电话、推送通知等方式告知您，难以逐一告知个人信息主体时，我们会采取合理、有效的方式发布公告。同时，我们还将按照监管部门要求，上报个人信息安全事件的处置情况。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>六、您如何管理您的信息</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>（一）信息管理</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            钠斯非常重视您对个人信息的关注，并尽全力保护您对于自己个人信息访问、更正及删除的权利，以使您拥有充分的能力保障您的隐私和安全。您的权利包括：\n        </p>\n    </li>\n    <li>\n        <p>\n            1、您可通过【帐号】→【个人中心】→【帐号设置】→【手机】访问、修改您的手机号码信息；\n        </p>\n    </li>\n    <li>\n        <p>\n            2、您可通过【帐号】→【个人中心】→【帐号设置】→【密码】访问、修改您设置的帐号密码；\n        </p>\n    </li>\n    <li>\n        <p>\n            3、您可通过【帐号】→【个人中心】→【帐号设置】→【邮箱】访问、修改您的邮箱信息；\n        </p>\n    </li>\n    <li>\n        <p>\n            4、您可通过【帐号】→【个人中心】→【帐号设置】→【实名认证/身份证认证】访问您的实名认证/身份证认证信息；\n        </p>\n    </li>\n    <li>\n        <p>\n            5、您可通过【帐号】→【个人中心】→【帐号设置】→【银行卡】访问、修改您的银行卡信息；\n        </p>\n    </li>\n    <li>\n        <p>\n            6、您可通过【帐号】→【个人中心】→【第三方帐号绑定】访问、修改您的QQ帐号、微博帐号及微信帐号；\n        </p>\n    </li>\n    <li>\n        <p>\n            7、您可通过【帐号】→【个人中心】→【站内信】访问、删除好友及相关对话信息；\n        </p>\n    </li>\n    <li>\n        <p>\n            8、您可通过【帐号】→【个人中心】→【我的关注】访问、取消关注相关主播及直播间；\n        </p>\n    </li>\n    <li>\n        <p>\n            9、您可通过【帐号】→【个人中心】→【消费记录】访问您的礼物、贵族、商城、门票、视频、公会等消费的鱼翅、鱼丸数量。\n        </p>\n    </li>\n    <li>\n        <p>\n            当您发现我们处理关于您的信息有错误时，您有权要求我们做出更正或补充。我们在提供服务的过程中，可能需要您开通一些设备权限，例如通知、相册、相机、麦克风、手机通讯录、蓝牙等访问权限。您可以在设备的【设置】功能中随时选择关闭部分或者全部权限，从而拒绝钠斯平台收集您的个人信息。在不同的设备中，权限显示方式及关闭方式可能有所不同，具体参考设备及系统开发方说明或指引。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>（二）用户授权撤回</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            您可以通过以下途径撤销已同意的授权：\n        </p>\n    </li>\n    <li>\n        <p>\n            1、联系钠斯官方在线客服https://kefu.nasinet.com/nx/index.html；\n        </p>\n    </li>\n    <li>\n        <p>\n            2、拨打客户服务热线：\n        </p>\n    </li>\n    <li>\n        <p>\n            3、发送邮件至kefu@nasinet.tv.\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>您申请授权撤回后，钠斯将不再处理相应的个人信息。请您理解，因您的授权撤回，我们无法继续为您提供撤回同意或授权所对应的特定功能或/和服务。但您撤回同意的决定，不会影响此前基于您的授权而展开的个人信息处理。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>（三）个人信息副本获取</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            您可以通过以下途径获取个人信息副本：\n        </p>\n    </li>\n    <li>\n        <p>\n            1、联系钠斯官方在线客服https://kefu.nasinet.com/nx/index.html；\n        </p>\n    </li>\n    <li>\n        <p>\n            2、拨打客户服务热线：\n        </p>\n    </li>\n    <li>\n        <p>\n            3、发送邮件至kefu@nasinet.com.\n        </p>\n    </li>\n    <li>\n        <p>\n            钠斯将在10个工作日内向您提供个人信息副本。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>（四）帐号注销</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            您可以自行在钠斯平台提交帐号注销申请。\n        </p>\n    </li>\n    <li>\n        <p>\n            注销方式：\n        </p>\n    </li>\n    <li>\n        <p>\n            【钠斯网页端→个人中心→我的资料→个人信息→帐号注销】\n        </p>\n    </li>\n    <li>\n        <p>\n            【钠斯移动端→设置→帐号绑定与安全→帐号安全→帐号注销】\n        </p>\n    </li>\n    <li>\n        <p>\n            您知悉并理解，注销帐号的行为是不可逆的。在您主动注销帐号之后，钠斯平台将停止为您提供产品或服务，尽快删除您的个人信息或作匿名化处理，但法律法规另有规定的除外。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>七、钠斯如何处理未成年人信息</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>钠斯平台的产品、网站和服务主要面向成人。若您是未成年人，在使用我们的产品和/或服务前，您应在监护人的陪同下阅读本政策，并应确保已征得您的监护人同意后使用我们的服务并向我们提供您的信息。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            如您的监护人不同意您按照本政策使用我们的服务或向我们提供信息，请您立即终止使用我们的服务并及时通知我们。\n        </p>\n    </li>\n    <li>\n        <p>\n            对于经父母或法定监护人同意而收集未成年人个人信息的情况，我们只会在受到法律允许、父母或监护人明确同意或者保护未成年人所必要的情况下使用或公开披露此信息。\n        </p>\n    </li>\n    <li>\n        <p>\n            若您是未成年人的监护人，当您对您所监护的未成年人使用我们的服务或其向我们提供的用户信息有任何疑问时，请您及时与我们联系。我们将根据国家相关法律法规及本政策的规定保护未成年人用户信息的保密性及安全性。如果我们发现自己在未事先获得可证实的父母或法定监护人同意的情况下收集了未成年人的个人信息，则会尽快删除相关数据。\n        </p>\n    </li>\n    <li>\n        <p>\n            <span class=\"text-underline\">若我们的产品或服务要求用户输入生日、年龄信息等识别到用户为14周岁以下的儿童用户，则该儿童用户同时适用本政策以及《儿童隐私保护声明》。</span>\n        </p>\n    </li>\n    <li>\n        <p>\n            若您是儿童的监护人，当您在帮助儿童完成产品或服务的注册、使用前，应当仔细阅读本政策、产品具体的隐私保护指引（如有）和《儿童隐私保护声明》，决定是否同意本政策、产品具体的隐私保护指引（如有）和《儿童隐私保护声明》并帮助儿童进行注册、使用，以便儿童能使用我们提供的产品或服务。\n        </p>\n    </li>\n    <li>\n        <p>\n            如您对您所监护的儿童的个人信息保护有相关疑问或权利请求时，请通过《儿童隐私保护声明》中所披露的联系方式与我们联系。我们会在合理的时间内处理并回复您。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>八、本隐私权政策如何更新</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            我们的隐私权政策可能变更。\n        </p>\n    </li>\n    <li>\n        <p>\n            未经您明确同意，钠斯不会限制您按照本政策所应享有的权利。我们会通过站内信、弹窗推送通知等合理方式告知您，以便您能及时了解本政策所做的任何变更。\n        </p>\n    </li>\n    <li>\n        <p>\n            对于重大变更，视具体情况钠斯可能还会提供更为显著的通知说明本政策的具体变更内容。\n        </p>\n    </li>\n    <li>\n        <p>\n            本政策所指的重大变更包括但不限于：\n        </p>\n    </li>\n    <li>\n        <p>\n            1、钠斯的服务模式发生重大变化。如处理个人信息的目的、处理的个人信息类型、个人信息的使用方式等；\n        </p>\n    </li>\n    <li>\n        <p>\n            2、钠斯在所有权结构、组织架构等方面发生重大变化。如业务调整、破产并购等引起的所有者变更等；\n        </p>\n    </li>\n    <li>\n        <p>\n            3、个人信息共享、转让或公开披露的主要对象发生变化；\n        </p>\n    </li>\n    <li>\n        <p>\n            4、您参与个人信息处理方面的权利及其行使方式发生重大变化；\n        </p>\n    </li>\n    <li>\n        <p>\n            5、钠斯负责处理个人信息安全的责任部门、联络方式及投诉渠道发生变化；\n        </p>\n    </li>\n    <li>\n        <p>\n            6、个人信息安全影响评估报告表明存在高风险.\n        </p>\n    </li>\n    <li>\n        <p>\n            若您不同意修改后的隐私权政策，您有权并应立即停止使用钠斯的服务。如果您继续使用钠斯的服务，则视为您接受钠斯对本政策相关条款所做的修改。\n        </p>\n    </li>\n</ul>', 2);
INSERT INTO `db_protocal` VALUES (3, '用户阳光行为规范', '<ul class=\" list-paddingleft-2\">\n    <li>\n        <p>\n            钠斯欢迎每一名用户：无论是组织还是个人，无论是主播还是观众，我们都力图为用户提供最好的直播与观看环境；并希望与用户携手，共同进步，绘制更加美好的未来。\n        </p>\n    </li>\n    <li>\n        <p>\n            为响应国家网信办最新发布的《互联网信息服务严重失信主体信用信息管理办法（征求意见稿）》中相关内容，营造和谐健康的网络环境，更好地保障用户合法权益及良好用户体验，钠斯根据现行法律法规及《钠斯用户注册协议》、《钠斯用户阳光行为准则》等钠斯平台规则制定《钠斯用户阳光行为规范》（以下简称“本规范”）。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>第一章 总则</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            第一条 钠斯尊重每一位用户的合法权益，将依照公开、透明的标准平等对待所有用户。\n        </p>\n    </li>\n    <li>\n        <p>\n            第二条 用户在使用钠斯平台服务的过程中应当遵守所在地区的法律法规，并尊重所在地区的道德和风俗习惯。如果用户的行为违反了当地法律法规或道德风俗，用户应当为此独立承担责任。\n        </p>\n    </li>\n    <li>\n        <p>\n            第三条 用户在钠斯平台的所有行为包括但不限于弹幕、发帖、转帖、回复、留言、站内信，均应同时遵守本规范及其他钠斯平台规则。本规范与原规则均有规定的，以本规范为准；本规范未作规定的，以原规则为准。\n        </p>\n    </li>\n    <li>\n        <p>\n            第四条 如用户行为违反相关法律法规及本规范规定，钠斯平台将依照相关法律法规本规范对违规行为采取合理处理措施，并配合司法行政机关维护钠斯用户及其他主体合法权益。\n        </p>\n    </li>\n    <li>\n        <p>\n            对任何涉嫌违反国家法律、行政法规、部门规章等规范性文件的行为，本规范及其他钠斯平台规则尚无规定的，钠斯平台将酌情处理。\n        </p>\n    </li>\n    <li>\n        <p>\n            第五条 如用户对本规范的理解和执行有任何疑惑或争议，可告知钠斯平台客服，钠斯平台将根据有关规则予以解释或处理。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>第二章 违规行为处理规则</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            第六条 如果钠斯平台发现或收到他人举报或投诉用户违反本规范约定，钠斯平台有权对用户行为进行独立判断，直接删除、屏蔽违规内容，根据情节严重程度给予警告、禁言（包括违规帐号及其关联帐号直播间禁言及鱼吧禁言）、暂时性或永久封禁违规帐号及其关联帐号等处罚，并有权公告处理结果。\n        </p>\n    </li>\n    <li>\n        <p>\n            根据情节严重程度，处罚措施分为：\n        </p>\n    </li>\n    <li>\n        <p>\n            1级处罚：警告\n        </p>\n    </li>\n    <li>\n        <p>\n            2级处罚：禁言\n        </p>\n    </li>\n    <li>\n        <p>\n            3级处罚：封禁帐号1至30天（不含30天）\n        </p>\n    </li>\n    <li>\n        <p>\n            4级处罚：封禁帐号30至360天\n        </p>\n    </li>\n    <li>\n        <p>\n            5级处罚：永久封禁帐号并拒绝再次为该用户提供任何服务\n        </p>\n    </li>\n    <li>\n        <p>\n            因用户违规行为受到1-4级处罚的，处罚期间及时与钠斯平台联系沟通，有效减少因违规行为造成的不良影响，并承诺严格按照平台规则合法合理使用平台内服务，钠斯平台将视情节对用户已做出的处罚减轻处理。\n        </p>\n    </li>\n    <li>\n        <p>\n            第七条 用户在禁言期间将无法使用钠斯平台为注册用户提供的跟帖服务，包括但不限于直播、弹幕、发帖、转帖、回复、留言、站内信等。\n        </p>\n    </li>\n    <li>\n        <p>\n            第八条 用户在封号期间将无法使用钠斯平台为注册用户提供的所有服务，帐号中的虚拟礼物、虚拟道具及虚拟服务都将无法使用。<span class=\"text-underline\">如前述虚拟礼物、虚拟道具及虚拟服务存在一定有效期，该有效期可能会在封号期间过期，帐号解封后，用户将无法使用该等已过期的虚拟礼物、虚拟道具及虚拟服务，且用户不能据此追究平台任何法律责任。</span>\n        </p>\n    </li>\n    <li>\n        <p>\n            第九条 钠斯对违规行为的处理并不免除用户应尽的法律责任。钠斯平台有权就用户的违法违规行为提起相应民事诉讼，追究用户的侵权、违约或其他民事责任，并要求用户赔偿钠斯平台因违法违规行为所受到的损失（包括钠斯平台所受到的直接经济损失、名誉或商誉损失以及钠斯平台对外支付的赔偿金、和解费用、律师费用、诉讼费用及其他间接损失），或移交有关行政管理机关给予行政处罚，或者移交司法机关追究用户的刑事责任。\n        </p>\n    </li>\n    <li>\n        <p>\n            第十条 钠斯平台将在能力范围内尽最大努力依照有效的法律法规做出判断，但并不保证其判断完全与司法机构、行政机关的判断一致。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>第三章 用户行为规范</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            第十一条 用户钠斯帐号头像、昵称、个性签名等注册信息和认证资料及用户在钠斯平台上传、发布、传输的所有文字、图片、视频、音频均不得含有违背国家法律法规政策和社会公序良俗、危害国家及社会公共利益、侵犯第三方合法权益、干扰钠斯平台正常运营的内容。\n        </p>\n    </li>\n    <li>\n        <p>\n            第十二条 如用户在钠斯平台上的行为或发布的信息包含如下内容，将被视为严重违规，钠斯平台有权直接删除或屏蔽违规内容，同时给予5级处罚。\n        </p>\n    </li>\n    <li>\n        <p>\n            （一）违反、反对宪法确定的基本原则的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （二）危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （三）损害国家荣誉和利益的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （四）煽动民族仇恨、民族歧视、破坏民族团结的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （五）破坏国家宗教政策，宣扬邪教和封建迷信的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （六）散布谣言，扰乱社会秩序，破坏社会稳定的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （七）煽动非法集会、结社、游行、示威、聚众扰乱社会秩序的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （八）歪曲、丑化、亵渎、否定英雄烈士事迹和精神的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （九）侮辱、诽谤或者其他方式侵害国家领导人、英雄烈士的姓名、肖像、名誉、荣誉的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （十）直接或间接散布淫秽色情或性暗示内容的，包括但不限于：\n        </p>\n    </li>\n    <li>\n        <p>\n            1．	表现或描述性行为，暴露或描写人体性器官、隐私部位的；\n        </p>\n    </li>\n    <li>\n        <p>\n            2．	发布带有性暗示、性挑逗内容的；\n        </p>\n    </li>\n    <li>\n        <p>\n            3．	发布色情低俗小说、色情音视频内容、色情播放链接及色情平台场所的；\n        </p>\n    </li>\n    <li>\n        <p>\n            4．	发布裸聊、一夜情、换妻、SM等不正当交友信息或色情服务交易信息的；\n        </p>\n    </li>\n    <li>\n        <p>\n            5．	其他淫秽色情或性暗示内容。\n        </p>\n    </li>\n    <li>\n        <p>\n            （十一）宣扬暴力、凶杀、血腥、恐怖、黑社会、教唆犯罪的，包括但不限于：\n        </p>\n    </li>\n    <li>\n        <p>\n            1．	传播人或动物自虐、自残、自杀、被杀、枪击、刺伤、拷打等令人不适的内容的；\n        </p>\n    </li>\n    <li>\n        <p>\n            2．	传播诱导他人自虐、自残、自杀或教唆传授他人犯罪等内容的；\n        </p>\n    </li>\n    <li>\n        <p>\n            3．	传播吸食注射毒品或违禁药品等令人不适的画面内容的；\n        </p>\n    </li>\n    <li>\n        <p>\n            4．	传播销售仿真枪、弓箭、管制刀具、气枪等含有杀伤力武器内容的；\n        </p>\n    </li>\n    <li>\n        <p>\n            5．	传播以鼓励非法或不当方式使用为目的而描述真实武器内容的。\n        </p>\n    </li>\n    <li>\n        <p>\n            6．	传播替人复仇、收账等具有黑社会性质的信息；\n        </p>\n    </li>\n    <li>\n        <p>\n            7．	雇佣、引诱、传授他人从事恐怖、暴力活动的；\n        </p>\n    </li>\n    <li>\n        <p>\n            8．	其他宣扬暴力、凶杀、血腥、恐怖、黑社会、教唆犯罪内容。\n        </p>\n    </li>\n    <li>\n        <p>\n            （十二）散布虚假诈骗信息骗取他人信息或财物的，包括但不限于：\n        </p>\n    </li>\n    <li>\n        <p>\n            1.	通过发布虚假刷单、虚假招聘兼职信息、票务信息、中奖信息、钓鱼网站等方式骗取他人钱财的；\n        </p>\n    </li>\n    <li>\n        <p>\n            2.	通过冒充亲友或以组织活动名义等方式骗取他人个人信息的；\n        </p>\n    </li>\n    <li>\n        <p>\n            3.	组织、宣传、诱导用户加入传销（或有传销嫌疑）机构或其他非法组织的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （十三）发布胁迫或诱使他人参与赌博、介绍传授赌博技巧方法、出售赌博器具等宣扬赌博内容的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （十四）将犯罪或其他违法所得及其产生的收益，在平台内以各种手段掩饰、隐瞒资金的来源和性质，使其在形式上合法化的洗钱行为；\n        </p>\n    </li>\n    <li>\n        <p>\n            （十五）传播买卖发票、假烟、假币、赃物、走私物品、象牙或虎骨等野生动物制品、毒品、窃听器、军火、人体器官、迷药、国家机密等非法物品内容的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （十六）传播违法办证刻章、代办身份证、护照、港澳通行证、结婚证、户口本、学历学位证明等证件等内容的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （十七）传播违法违规办理信用卡、信用卡套现、公积金套现、医保卡套现、手机复制卡等内容的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （十八）法律、行政法规限制或禁止的其他内容。\n        </p>\n    </li>\n    <li>\n        <p>\n            第十三条 如用户在钠斯平台发布侵犯第三方合法权益的内容，钠斯平台有权直接删除或屏蔽违规内容，同时视情节严重程度给予2级、3级或4级处罚。\n        </p>\n    </li>\n    <li>\n        <p>\n            如用户持续发布或大量发布侵犯第三方合法权益内容，或收到钠斯平台处理通知后再次违规，将被视为严重违规，钠斯平台有权给予5级处罚。\n        </p>\n    </li>\n    <li>\n        <p>\n            （一）谩骂、侮辱、诽谤、骚扰、歧视、恐吓他人的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （二）调查、刺探、公开他人姓名、照片、私生活镜头、身份证号码（或护照号码）、银行账号、网络帐号、电话、住址、电子邮件地址、财产状况、家庭背景、社会关系、单位职业信息、学校教育信息、医疗病例资料、性取向、犯罪记录等个人信息的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （三）传播侵犯他人著作权、商标权、专利权等知识产权内容的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （四）非法披露他人商业秘密的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （五）擅自使用他人姓名、肖像，侵犯他人姓名权、肖像权等合法权益的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （六）擅自使用他人已经登记注册的企业名称或商标，侵犯他人企业名称权或商标专用权的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （七）盗用他人身份信息或帐号，明示或暗示自己与他人相等同或存在关联的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （八）其他侵犯他人名誉权、隐私权、姓名权、名称权、荣誉权、肖像权、商业秘密、知识产权等合法权益的内容。\n        </p>\n    </li>\n    <li>\n        <p>\n            第十四条 如用户在钠斯平台或其他第三方平台存在如下干扰钠斯平台正常运营情形，钠斯平台有权直接删除或屏蔽违规内容，同时视情节严重程度给予2级、3级或4级处罚。\n        </p>\n    </li>\n    <li>\n        <p>\n            如用户持续或严重干扰钠斯平台正常运营的，或收到钠斯平台处理通知后再次违规，将被视为严重违规，钠斯平台有权给予5级处罚。\n        </p>\n    </li>\n    <li>\n        <p>\n            （一）刺激煽动他人情绪，攻击、嘲讽、歧视、孤立第三方，引战或与其他群体发生群体纠纷、骂战的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （二）侮辱、诋毁、骚扰、冒充钠斯主播、钠斯用户、钠斯平台工作人员及钠斯平台的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （三）宣传、诱导用户到其他直播平台的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （四）利用特殊文字、符号等恶意刷屏，严重影响其他用户体验的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （五）传播病毒、文件、计算机代码或程序，可能对钠斯平台的正常运行造成损害或者中断的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （六）使用或利用钠斯知识产权（包括钠斯的商标、品牌、标志、任何其他专有数据或任何网页的布局或设计），或在其他方面侵犯钠斯任何知识产权（包括试图对钠斯平台客户端或所使用的软件进行逆向工程）；\n        </p>\n    </li>\n    <li>\n        <p>\n            （七）未经钠斯明确许可，使用钠斯平台服务用于任何商业用途或为任何第三方的利益；\n        </p>\n    </li>\n    <li>\n        <p>\n            （八）通过使用任何自动化程序、软件、引擎、网络爬虫、网页分析工具、数据挖掘工具或类似工具，接入钠斯平台服务、收集或处理通过钠斯平台服务所提供的内容；\n        </p>\n    </li>\n    <li>\n        <p>\n            （九） 参与任何“框架”、“镜像”或其他技术，目的是模仿钠斯平台服务的外观和功能；\n        </p>\n    </li>\n    <li>\n        <p>\n            （十）干预或试图干预任何用户或任何其他方接入钠斯平台服务；\n        </p>\n    </li>\n    <li>\n        <p>\n            （十一）探究或测试钠斯平台服务、系统或其他用户的系统是否容易入侵攻击，或在其他方面规避（或试图规避）钠斯平台服务、系统或其他用户的系统的任何安全功能；\n        </p>\n    </li>\n    <li>\n        <p>\n            （十二）对钠斯平台服务所用的软件进行解编、反向编译或逆向工程，或试图作出上述事项；\n        </p>\n    </li>\n    <li>\n        <p>\n            （十三）为破坏或滥用的目的开设多个账户，或恶意上传重复的、无效的大容量数据和信息；\n        </p>\n    </li>\n    <li>\n        <p>\n            （十四）进行任何破坏钠斯平台服务公平性或者其他影响应用正常秩序的行为，如主动或被动刷分、合伙作弊、使用外挂或者其他的作弊软件、利用BUG（又叫“漏洞”或者“缺陷”）来获得不正当的非法利益，或者利用互联网或其他方式将外挂、作弊软件、BUG公之于众；\n        </p>\n    </li>\n    <li>\n        <p>\n            （十五）进行任何诸如发布广告、销售商品的商业行为，或者进行任何非法的侵害钠斯平台利益的行为，如贩卖钠斯平台帐号、鱼丸鱼翅等虚拟产品、贵族增值服务、粉丝勋章、外挂等；\n        </p>\n    </li>\n    <li>\n        <p>\n            （十六）对直播间或其他个人进行恶意举报；\n        </p>\n    </li>\n    <li>\n        <p>\n            （十七）其他干扰钠斯平台秩序侵犯钠斯平台利益的行为。\n        </p>\n    </li>\n    <li>\n        <p>\n            第十五条 如用户在钠斯平台存在如下违背社会公序良俗情形，钠斯平台有权直接删除、屏蔽违规内容，同时视情节严重程度给予1级或/及2级处罚。\n        </p>\n    </li>\n    <li>\n        <p>\n            如用户行为造成严重影响，致使钠斯平台卷入公共事件，钠斯平台有权给予3级、4级或5级处罚。\n        </p>\n    </li>\n    <li>\n        <p>\n            （一）爆粗口、讲黄色笑话等不文明行为的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （二）宣扬炫富、拜金等不恰当的消费观及价值观的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （三）宣扬抽烟、酗酒、婚外情等不良生活作风的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （四）其他违背社会公序良俗的内容。\n        </p>\n    </li>\n    <li>\n        <p>\n            第十六条 如用户存在本规范第十二条到第十五条规定以外的其他违反法律法规及钠斯平台规则的行为，有具体处理规则的钠斯将按具体规则处理，没有具体处理规则的，钠斯平台将视情节严重程度酌情处理，采取删除、屏蔽违规内容、警告、禁言（包括直播间禁言及鱼吧禁言）、暂时性或永久封禁帐号等一项或多项处理措施，并有权公告处理结果。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>第四章 附则</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            第十七条 <strong>本规范为动态文档，钠斯平台有权根据相关法律法规或产品运营需要对本规范内容进行修改并公示。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            第十八条 本规范自发布之日起生效并实施，适用于钠斯平台所有用户。<strong>用户使用钠斯平台服务即视为对本规范的接受，对用户具有法律约束力。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            第十九条 本规范是《钠斯用户注册协议》不可分割的组成部分，如本规范有未尽事宜的，适用《用户注册协议》相关约定。\n        </p>\n    </li>\n    <p style=\"text-align: right;\">\n        钠斯平台\n    </p>\n    <p style=\"text-align: right;\">\n        2019年8月5日\n    </p>\n</ul>', 3);
INSERT INTO `db_protocal` VALUES (4, '直播协议', '<ul class=\" list-paddingleft-2\">\n    <li>\n        <p>\n            《钠斯直播协议》，是湖北钠斯网络科技有限公司（以下简称“我方”）和你方（你方为自然人、法人或其他组织）所约定的规范双方权利和义务的具有法律效力的电子协议，下称“本协议”。<strong>你方勾选“我同意”或点击“我已阅读并遵守该协议”按钮，即表示你方已经仔细阅读、充分理解并完全地毫无保留地接受本协议的所有条款。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            第一条 总则\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>1、钠斯平台是指由湖北钠斯网络科技有限公司成立的在线解说平台。</strong>你方根据我方注册要求及规则，在我方合法经营的钠斯平台（以下简称“平台”）上申请成为我方的直播服务提供方（或称“直播方”），为我方平台用户提供在线解说（本协议项下“解说”均亦指“直播”）视频内容的直播服务，你方在我方平台提供服务期间均应视为协议期内。我方不事先审核前述被上载的、由你方参与、编辑、制作的视频内容，也不主动对该等视频进行任何编辑、整理、修改、加工。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>2、</strong>签署本协议前，你方已充分了解我方之各项规则及要求，且有条件及有能力、资格履行本协议约定的直播方职责及义务。本协议对你方构成有效的、带有约束力的、可强制执行的法定义务，你方对本协议下所有条款及定义等内容均已明确知悉，并无疑义。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>3、</strong>你方承诺并声明在为我方提供服务时符合所在地法律的相关规定，不得以履行本协议名义从事其他违反中国及所在地法律规定的行为。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>4、你方与我方不构成任何劳动法律层面的雇佣、劳动、劳务关系，我方无需向你方支付社会保险金和福利。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>5、</strong>未经我方事先书面同意，你方不得在第三方竞争平台上从事任何与解说相关的行为（包括但不限于：视频直播互动、同步推流、发布解说视频或其余类似行为）。前述第三方竞争平台指：与我方及我方关联公司有竞争关系的第三方直播平台，包括但不限于虎牙直播、战旗TV、熊猫TV、火猫直播、风云直播、播狗、新浪游戏、QT、17173、PPTV、TGA、AZUBU、TWITCH等及其相关联的直播网站。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>6、</strong> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>\n &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;你方在注册成为钠斯主播或在钠斯平台进行直播服务前已确认，未与任何第三方平台签署或存在有效存续的独家直播解说协议。 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>7、</strong> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>\n &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;在双方合作期间，你方直播、上传、发布或传输等内容的相关权利，适用于《用户注册协议》“4.2服务规范”中条款的约定。 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            第二条 我方权利义务\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>1、</strong>我方有权制定平台运营制度及对直播方的管理规则，并将其作为本协议的一部分，有权对你方进行管理和监督，有权根据运营情况对相应规则做出调整或变更，你方对此表示理解和同意。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>2、</strong>我方有权对你方进行考察、评判，以确立（取消）对你方的奖励或处罚，具体考察项目及标准由我方另行制定，无需额外征得你方同意。如我方要求与你方另行签订正式的解说合作协议的，你方不得拒绝或以其他形式变相拒绝签订【否则你方应一次性向我方支付违约金【50000】（大写：伍万）元】。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>3、</strong>我方有权对你方的直播服务提出改进建议和意见，你方应在收到我方的建议和意见后【3】个工作日内进行相应的整改，否则我方有权限制、关闭、回收、或终止你方对钠斯直播间的使用，相应扣减应支付给你方的服务费用（若有）可能会给你方造成一定的损失，该损失由你方自行承担，我方不承担任何责任。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>4、</strong>我方对你方进行的解说直播相关事宜拥有最终决定权。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>5、</strong>我方有权使用你方的的名称（包括但不限于你方真实姓名、笔名、网名、曾用名及任何代表你方身份的文字符号）、肖像（包括但不限于真人肖像及卡通肖像等）进行我方平台的各类宣传。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>6、</strong>我方负责提供平台技术服务支持，同时负责平台服务费用结算（若有）。\n        </p>\n    </li>\n    <li>\n        <p>\n            第三条 你方权利义务\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>1、</strong>你方应当使用真实身份信息及个人资料，不得以虚假、冒用的居民身份信息、企业注册信息、组织机构代码信息进行注册并认证。若你方的个人资料有任何变动，应及时更新。<strong>我方禁止未成年人直播，若法定监护人希望未成年人得以提供本协议约定的网络直播及解说服务的，必须以法定监护人身份加以判断该等服务内容是否适合于未成年人，并由法定监护人承担因此而导致的一切后果。</strong>你方承诺不会因执行本协议损害任何第三方合法利益，你方接受并履行本协议不违反任何对你有约束力的法律文件，亦不会使我方对任何第三方承担责任。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>2、</strong>你方自己承担进行解说直播所需要的网络、支持视频和语音的设备，并保证直播图像清、语音质量清晰、稳定。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>3、</strong>你方承诺，愿意遵照平台设定的直播间申请程序，提交平台所需的申请材料并自愿缴付相应的保证金。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>4、</strong>你方承诺直播房间必须作解说直播用途，不得用于其他任何非解说直播性质的活动。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>5、</strong>你方开展本协议项下解说直播事项和/或在本平台上发布的所有信息/资讯/言论/内容等均不得含有任何违反中华人民共和国有关法律、法规及规定的内容，包括但不限于危害国家安全、淫秽色情、虚假、违法、诽谤（包括商业诽谤）、非法恐吓或非法骚扰、侵犯他人知识产权、人身权、商业秘密或其他合法权益以及有违公序良俗的内容或指向这些内容的链接。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>6、</strong>你方承诺积极维护我方及我方平台形象，你方不会做出有损于我方及/或我方平台形象或利益的行为，本协议期内及协议终止后，你方不会通过任何渠道（包括但不限于网站、博客、微博、微信、QQ聊天群、玩家聚会等）暗示或发布不利于我方及/或我方平台的言论。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>7、</strong>未经我方书面同意，你方不得在任何场合以任何形式（包括但不限于文字、口播、视频贴片等）提及第三方竞争平台的相关信息，不得引导或为我方平台现有用户、其他直播方及我方员工进入其他第三方竞争平台提供任何信息或便利，包括但不限于提供联络上的协助、进行说服工作等。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>8、</strong>您特此陈述并保证：(1）您当前并未在受美国制裁国家或地区（包括但不限于克里米亚、古巴、伊朗、北朝鲜、及叙利亚）居住或停留；(2）您并不属于美国政府禁止或限制主体名单（包括美国财政部海外资产办公室管理的《特别指定国民名单》Specially Designated Nationals and Blocked Persons List）的主体或人员；(3）您并不属于其他任何遭受美国或联合国经济制裁名单主体或人员，该等名单包括但不限于a）《行业制裁识别名单》Sectoral Sanctions Identifications List；b)《海外逃避制裁者名单》 Foreign Sanctions Evaders List；c)《巴勒斯坦立法会名单》NON-SDN Palestinian Legislative Council List；d)《非SDN涉伊朗制裁法案名单》Non-SDN Iran Sanctions Act List等。\n        </p>\n    </li>\n    <li>\n        <p>\n            第四条 服务费用及结算\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>1、</strong>以你方为平台用户提供解说直播服务为前提，用户可对你方进行赠送虚拟礼物的消费，你方可根据我方的结算要求及规则申请结算相应的服务费用（若有）。我方就你方收到的每笔虚拟礼物以数量为计价单位，且以一定的比例为价值基准进行兑换结算，作为支付给你方的服务费用。对于非正常手段获得的虚拟礼物消费，我方有权进行独立判断和处理。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>2、你方所获得的服务费用应当依据国家相关法律法规缴纳税金，我方将你方所获得的服务费用支付于你方在用户中心中填写的银行账户中，你方可在登陆我方平台后在个人中心-主播相关-收益记录中查询相关信息（结算数据为含税数据）。</strong>\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>3、</strong>若你方为自然人，并在我方平台注册并通过个人认证的，则我方有权将你方所获得的服务费用支付于你方在用户中心填写的个人银行账户中；若你方为法人或其他组织，并在我方平台注册且通过机构认证的，则我方有权将你方所获得服务费用支付于你方在机构认证页面填写的机构账户中，但你方应当在我方付款前5个工作日内向我方提供等额有效的增值税专用发票（发票名目为直播服务费），因你方延迟提供发票导致我方付款延迟的，不构成我方违约。我方按照你方填写的账户支付服务费用，即视为我方已经履行了本协议约定的付款义务。若你方为法人或其他组织的，你方工作人员或旗下主播因管理及运营该帐号及其直播房间产生的费用，由你方与你方工作人员或旗下主播之间自行结算。若你方因该费用结算而引起纠纷、诉讼或赔偿给我方造成损失的（包括但不限于你方拖欠你方工作人员或旗下主播薪资费用时我方先行垫付其薪资的款项），我方有权在应付服务费用中先行扣除，不足部分我方有权向你方追偿。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>4、</strong>你方保证填写账户信息正确、真实、有效，如因账户信息造成我方的任何支付错误，由你方独自承担责任。同时，若你方需要变更帐户信息，需及时书面通知我方，新账户信息由你方提交申请且经我方审核通过后下一个结算月生效。\n        </p>\n    </li>\n    <li>\n        <p>\n            第五条 保密制度\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>1、</strong>你方应严格遵守我方的保密制度，承诺无限期保守我方的商业秘密。因你方违反约定使用或披露我方商业秘密和信息使我方遭受任何名誉、声誉或经济上的、直接或间接的损失，你方应赔偿我方人民币[ 100000 ]元违约金，不足以弥补我方损失的，你方还应赔偿我方损失。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>2、</strong>商业秘密是指由我方提供的、或者你方在双方合作期间了解到的、或者我方对第三方承担保密义务的，与我方业务有关的，能为我方带来经济利益，具有实用性的、非公知的所有信息，包括（但不限于）：技术信息、经营信息和与我方行政管理有关的信息和文件（含本协议及相关协议内容）、你方从我方获得的服务费用的金额和结算方式、标准、权利归属方式、授权方式、客户名单、其他解说员的名单、联系方式、服务费用、我方工作人员名单等不为公众所知悉的信息。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>3、</strong>你方应严格遵守本协议，未经我方书面授权或同意，对我方的商业秘密不得：\n        </p>\n    </li>\n    <li>\n        <p>\n            （1）以任何方式向第三方或不特定的公众进行传播、泄露；\n        </p>\n    </li>\n    <li>\n        <p>\n            （2）为非本协议的目的而使用我方的商业秘密。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>4、</strong>本协议终止后，你方应将我方商业秘密悉数返还我方，或在我方监督下，将记载我方商业秘密的全部文件销毁。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>5、</strong>本条规定在本协议终止后仍然有效。\n        </p>\n    </li>\n    <li>\n        <p>\n            第六条 协议的变更、解除、终止\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>1、</strong>我方有权在必要时变更、中止、终止本协议，并在相关页面进行通知，变更后的协议一旦在相关的页面上公布即有效代替原来的协议。本协议条款变更后，如你方继续为我方平台用户提供解说等直播服务，即视为你方已知悉并接受变更后的协议。如你方不同意我方对本协议的所作的任何变更，你方应立即书面通知我方并停止在我方平台进行的任何直播服务。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>2、</strong>双方就解除本协议协商一致即可终止协议。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>3、</strong>你方有下列情形之一，我方可以立即解除本协议，不需要提前通知：\n        </p>\n    </li>\n    <li>\n        <p>\n            （1）我方发现你方违反对本协议所做的声明与承诺的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （2）因你方行为直接或间接给对我方利益造成重大损害的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （3）违反国家法律法规的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （4）违反本协议规定的其它义务；\n        </p>\n    </li>\n    <li>\n        <p>\n            （5）以消极、不作为等不符合我方要求的方式履行本协议的（即使未构成违约），经我方通知后10日内仍未改正的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （6）因异常情形的出现，我方认为你方不适合进行本协议下服务事项，经我方通知后10日内异常情形仍未消除的；\n        </p>\n    </li>\n    <li>\n        <p>\n            （7）因我方业务调整，不再进行直播服务业务的。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>4、</strong>由于本协议第六条第1、2款造成的协议解除、终止，我方按本协议第四条规定及我方平台实时政策约定与你方结算服务费用。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>5、</strong>由于本协议第六条第3款造成的协议解除、终止，我方有权扣除你方帐号中尚未结算的全部服务费用，并有权要求你方按约定承担违约责任。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>6、</strong>本协议终止后，不影响本协议约定的直播方成果的权利归属，我方仍为本协议所涉直播方成果的权利人；如果根据中国法律，上述之约定无法实际履行的，你方同意并承诺自本协议终止或双方后续合作中止、终止或解除之日起五年内不将直播方成果的全部或部分的发布、使用等相关的权利（包括但不限于《中华人民共和国著作权法》第十条第一款第（五）项至第（十七）项规定的著作权权利）自行行使、转让或授权许可于任何第三方。\n        </p>\n    </li>\n    <li>\n        <p>\n            第七条 违约责任\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>1、</strong>任何由于你方声明不实或违反其声明承诺事项导致他方向我方提起诉讼、索赔及/或导致我方声誉受损之后果，你方将承担我方因此产生的全部直接及间接费用、损失及赔偿，其中包括我方为诉讼支付的有关费用及律师费。\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>2、</strong>除本协议另有约定外，你方违反本协议下任何规定或你方提供的服务不符合我方的要求，我方有权单方面采取相应限制或处罚措施，包括但不限于：限制、关闭、回收、或终止你方对钠斯直播间的使用，限制或停止某项单独服务（如视频直播）并根据实际情况决定是否恢复使用，扣除你方帐号中尚未结算的服务费用。\n        </p>\n    </li>\n    <li>\n        <p>\n            第八条 争议处理\n        </p>\n    </li>\n    <li>\n        <p>\n            <strong>因履行本协议而产生的任何争议，双方均应本着友好协商的原则加以解决。协商解决未果，任何一方均可以提请宜昌市高新新技术开发区人民法院诉讼解决。</strong>\n        </p>\n    </li>\n</ul>', 4);
INSERT INTO `db_protocal` VALUES (5, '直播内容管理规定', '<p class=\"p\" style=\"text-indent:2em;\"><br/></p><p class=\"p\" style=\"text-indent:2em;\"><strong>第一章</strong><strong> 总则</strong></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><strong>1.钠斯对所有直播间进行量化计分扣分管理方式。</strong></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><strong>2.每个直播间</strong><strong>扣分系统总分值为12分，管理期间将针对主播的违规行为进行相应扣分，当主播直播间分数低于4分时（包括4分），系统将关闭该直播间的礼物系统，超管将重点关注该直播间，</strong><strong><span style=\"color:#E53333;\">当直播间分数为</span><span style=\"color:#E53333;\">0分，将永久封停该直播间。</span></strong></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><strong><span style=\"color:#E53333;\">3.</span></strong><strong><span style=\"color:#E53333;\">严重违规</span></strong><strong><span style=\"color:#E53333;\">：</span></strong><strong><span style=\"color:#E53333;\">扣除所有分值，并永久封停直播间</span></strong><strong><span style=\"color:#E53333;\">；</span></strong><strong><span style=\"color:#E53333;\">其他普通违规</span></strong><strong><span style=\"color:#E53333;\">：</span></strong><strong><span style=\"color:#E53333;\">根据各版块规则进行警告、处罚，并扣除相应分值</span></strong><strong><span style=\"color:#E53333;\">。</span></strong><strong><span style=\"color:#E53333;\">情节轻微扣</span><span style=\"color:#E53333;\">1分，其他情况将根据违规程度给予扣除2-11分不等进行处罚。情节特别严重者将扣除所有分值，并永久封停直播间。</span></strong></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><strong>4.如果主播直播间分值低于12分，在整改后成功规范自己的直播内容、言行举止，并在连续3天的直播过程中，无扣分、无违规行为，表现良好，可加1分。</strong></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><strong><br/> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</strong></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><strong>第二章</strong><strong> 主播违规管理办法</strong></p><p class=\"p\" style=\"text-indent:2em;\"><br/></p><p class=\"p\" style=\"text-indent:2em;\"><strong><span style=\"color:#E53333;\">1.</span></strong><strong><span style=\"color:#E53333;\">严重违规</span></strong></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><strong>1.1</strong><strong>严禁进行反党反政府或带有侮辱诋毁党和国家的行为</strong>，包括且不限于：</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">(1)违反宪法确定的基本原则的；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">(2)危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">(3)损害国家荣誉和利益的；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">(4)煽动民族仇恨、民族歧视、破坏民族团结的；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">(5)破坏国家宗教政策，宣扬邪教和封建迷信的；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">(6)散布谣言，扰乱社会秩序，破坏社会稳定的；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">(7)散布暴力、恐怖或者教唆犯罪的；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">(8)煽动非法集会、结社、游行、示威、聚众扰乱社会秩序的；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">(9)其他可能引起或已经引起不良影响的政治话题。</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><br/></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><strong>1.2</strong><strong>严禁直播违反国家法律法规的内容</strong>，包括且不限于：</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">(1)展示毒品样品、表演及传播毒品吸食或注射方式、讲解毒品制作过程等一切与毒品相关的内容；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">(2)组织、宣传、诱导用户加入传销（或有传销嫌疑）的机构；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">(3)与赌博或涉嫌赌博有关的任何活动，例如：赌球、赌马、网络百家乐等；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">(4)其它低俗涉黄行为，例如：播放AV、播放露点内容的影视、浏览或传播色情图片、直播包含色情内容的游戏、出售色情网盘资源等。</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><br/></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><strong>1.3</strong><strong>严禁进行威胁生命健康，或利用枪支、刀具表演</strong>，包括且不限于：</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">(1)使用刀具、仿真刀具、弩、枪支、仿真枪支表演具有高度危险性的节目；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">(2)表演危害他人人身安全的内容，如：殴打他人、威胁他人等；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">(3)表演危害自身安全的内容，如自残、自杀、食用明显有害身体健康的食物等。</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><br/></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><strong>1.4</strong><strong>禁止侵害平台合法权益和妨碍平台正常运营，利用平台漏洞获取非法利益，包括</strong><strong>且</strong><strong>不限于利用画面、文字、语言等手段进行宣传、诱导、介绍的行为，</strong>如：盗号以及盗刷鱼翅等行为。</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><br/></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><strong>1.5</strong><strong>禁止以任何方式诋毁、损害平台形象，</strong><strong>发布与本平台相关的不实信息、恶意信息。</strong>包括且不限于微博微信等其他社交平台，如：在粉丝群曲解平台政策规范，在微博发表片面不实信息等行为。</p><p class=\"p\" style=\"text-indent:2em;\"><strong>1.6</strong><strong>严禁利用平台或其他社交媒体发布违法信息，</strong>包括且不限于涉黄、涉赌等内容。</p><p class=\"p\" style=\"text-indent:2em;\"><strong>1.7 禁止歪曲、丑化、亵渎、否定英雄烈士的事迹和精神。</strong></p><p class=\"p\" style=\"text-indent:2em;\"><strong>1.8 禁止调侃自然灾害、历史事件、发表相关不当言论</strong><strong>。</strong></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><strong>2.其他违规</strong></p><p class=\"p\" style=\"text-indent:2em;\"><strong>2.1其他普通违规</strong></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><strong>2.</strong><strong>1</strong><strong>.1</strong><strong>禁止播放一切无版权内容</strong>：平台禁止播放一切无版权内容，包括且不仅限于影视、游戏、体育赛事、演唱会等；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><strong>2.</strong><strong>1.2</strong><strong>禁止播放一切暴力、血腥、大尺度内容</strong>：平台禁止播放一切暴力、血腥、大尺度内容，包括且不仅限于影视、游戏等；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><strong>2.</strong><strong>1.3</strong><strong>禁止出现其他直播平台的直播内容</strong>：本平台严禁出现其他直播平台的直播内容；严禁出现宣传其他直播平台、鼓动观众观看非本平台内容的行为；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><strong>2.</strong><strong>1.4</strong><strong>未经允许，严禁盗播本平台其他主播的直播内容</strong><strong>；</strong></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><strong>2.</strong><strong>1.5</strong><strong>其他相关内容</strong>：严禁播放法律法规、相关监管部门规定的禁止播放的内容；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><strong>2.</strong><strong>1.6</strong><strong>无意义直播内容：</strong>严禁播放无意义直播内容，包括且不限于挂机、主播无自主行为意识，如直播睡觉等；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><strong>2.</strong><strong>1.7</strong><strong>禁止播放国家明令禁止的视听内容</strong><strong>；</strong></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><strong>2.1.8 </strong><strong>禁止</strong><strong>以</strong><strong>个人</strong><strong>形式直播</strong><strong>任何荐股行为，包括</strong><strong>且</strong><strong>不限于引导用户签订指导炒股协议、投资咨询合同、收取咨询费或指导费等。利用直播非法经营证券业务或者为非法活动提供便利的行为。</strong></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><br/></p><p class=\"p\" style=\"text-indent:2em;\"><strong>2.2 </strong><strong>游戏相关内容</strong></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.2.1严禁直播扰乱游戏正常秩序的内容，例如直播或宣传游戏私服、外挂、漏洞、辅助等；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.2.2传奇类游戏需取得官方授权进行直播，直播传奇类游戏需要先联系客服进行报备，无报备将暂时关闭直播间。</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><br/></p><p class=\"p\" style=\"text-indent:2em;\"><strong>2.3</strong><strong>钠斯平台直播内容行为规范</strong></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.3.1未经钠斯官方许可，主播不得以钠斯官方名义开展活动；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.3.2严禁播放和宣传涉赌、涉毒、涉黄等违法行为的擦边类直播内容，包括且不限于采访站街女、抓车震、抓野战、大保健等，以及暴力血腥、消极反动、色情的擦边直播内容；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.3.3严禁直播任何涉嫌违法行为，包括且不仅限于：展示管制刀具、枪械，猎杀国家保护动物、私自盗掘古文化遗址、古墓葬、电鱼等，如有合法证件，需要联系客服进行报备，超管将对直播内容进行监控；</p><p class=\"MsoNormal\" style=\"text-indent:2em;\">2.3.4严禁直播血腥、暴力、恶心等引起观众视听不适的内容行为，包括且不限于：用不人道的方式虐待动物、过于血腥的屠宰镜头、展示伤口或身体残缺为噱头进行直播；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.3.5严禁主播在无任何保障措施下的情况下直播高风险内容或威胁生命健康的内容，高危户外活动、需要专业人士指导的活动需提前报备，无报备将暂时关闭直播间；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.3.6严禁侵犯他人合法权益，侵犯泄露他人隐私，例如：未经他人允许闯入他人住宅、偷拍，抹黑诋毁谩骂攻击他人等行为；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.3.7严禁在直播过程中出现骚扰、扰乱周围居民的正常生活的行为，例如：制造噪音、破坏公共设施、堵塞道路、损害共享单车等；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.3.8严禁主播在容易暴露他人隐私的场所进行直播，例如：洗手间、更衣室等；在进入非公共场所进行直播时，必须征得他人或该单位的同意；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.3.9主播在进行采访、拍摄活动时，应告知参与者正在进行钠斯的直播，若被拍摄者拒绝进行，应立即停止该行为；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.3.10严禁直播任何低俗不良直播内容，或以此为噱头的相关擦边内容，包括且不限于以标题或文字等形式宣传“偷拍”、“车震”、“大保健”、“挖坟”等；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.3.11严禁以任何形式直播涉赌或者相关擦边行为，如果是亲朋好友之间以娱乐为目的的棋牌娱乐，需提前报备，并在取得同意后直播，否则一律关闭处理；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.3.12严禁直播抽水烟、皮下注射等疑似吸食毒品的行为；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.3.13严禁直播任何暴力血腥内容、打架斗殴、上访游行等不和谐行为；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.3.14严禁主播在成人娱乐场所直播，例如：夜总会、洗浴中心等；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.3.15严禁在直播过程中做出任何违法擦边或破坏社会公序良俗的行为，例如：意图约架、插队、逃票、翻越围墙、乱写乱画等；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.3.16严禁主播在机动车（包括汽车与电动车等所有上路行驶车辆）行驶期间手持直播工具进行直播，不得在驾驶过程中进行其他因直播对驾驶产生干扰的行为，如参与弹幕互动等；不得出现酒后驾驶、闯红灯、超速行驶等违反交通规则的行为；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.3.17严禁主播在无专业安保人员及完善安保措施的情况下展示枪械；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.3.18文玩鉴赏类的直播形式必须通过报备，报备者需为有一定专业素养，经过相关协会或机构认证的专业人士，<strong>无报备者将暂时关闭直播间；</strong></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.3.19不允许私自播放射击、狩猎等相关直播内容，须获得相关部门许可，或能证明所在场所的专业性，并获得相关部门以及直播场所负责人的直播许可，<strong>无报备者将暂时关闭直播间；</strong></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.3.20进行户外生存类（在非专业场地野外生存、打猎）的内容，需向本平台提供当地机关许可的书面证明以及直播计划书，有此项直播内容的主播，需提前联系本平台客服，提供相关报备资料，并获得报备通过；报备通过后，直播时需严格遵从国家相关法律法规。<strong>无报备者将暂时关闭直播间</strong>；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.3.21不得捕捉、食用或者售卖受国家法律法规保护的野生动植物，若直播涉及为人工繁育的野生动植物品种，需提前联系本平台客服，提供相关报备资料，<strong><span style=\"color:#E53333;\">无报备者</span></strong><strong><span style=\"color:#E53333;\">将</span></strong><strong><span style=\"color:#E53333;\">暂时关闭直播间</span></strong><span style=\"color:#E53333;\">；</span></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.3.22严禁主播直播时宣扬不正当价值观、消费观、婚恋观，不得出现有违道德底线或其他产生不良导向的直播内容；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.3.23严禁主播在直播时传递不良个人生活作风，如抽烟、酗酒、炫富等；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.3.24严禁主播以个人身份通过直播发布任何募捐、筹款信息，有相关资质的机构如需合作可提前联系本平台客服报备，<strong><span style=\"color:#E53333;\">无报备者将暂时关闭直播间。</span></strong></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><br/></p><p class=\"p\" style=\"text-indent:2em;\"><strong>2.4</strong><strong>.</strong><strong>其他平台直播内容</strong></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.4.1本平台严禁出现其他直播平台的直播内容；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.4.2严禁出现宣传其他直播平台、鼓动观众观看非本平台内容的行为。</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><br/></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><strong>2.5</strong><strong>钠斯平台主播行为、着装规范</strong></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">主播最低着装标准见下图：</p><p class=\"p\"><img src=\"https://sta-op.douyucdn.cn/dylamr/2019/08/30/488d62ba0765dec3136a3cd46b46c30c.png\" alt=\"钠斯直播内容管理规定（2019修订）\"/></p><p class=\"MsoNormal\" style=\"text-align:center;margin-left:0pt;text-indent:2em;background:#FFFFFF;\"><br/></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.5.1女主播服装不能过透过露，不能只穿比基尼及类似内衣的服装或不穿内衣，不能露出内衣或内裤（安全裤）；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.5.2女主播背部的裸露部位不能超过上半部的三分之二以上即腰节线以上；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.5.3女主播下装腰部必须穿到骨盆以上，低腰下装不得低于脐下2cm即不得露出胯骨及骨盆位置，短裙或短裤下摆不得高于臀下线；男主播不得仅着下装或穿着内裤、紧身裤的服装直播，且裤腰不得低于胯骨；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.5.4女主播胸部的裸露面积不能超过胸部的三分之一，上装最低不得超过胸部三分之一的位置；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.5.5主播拍摄角度不得由上至下拍摄胸部等敏感部位，或由下至上拍摄腿部、臀部等敏感部位；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.5.6主播不得长时间聚焦腿部、脚部等敏感部位；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.5.7游戏分类（标签为游戏类别）主播摄像头不得超过游戏界面1/3且不得超过整体屏幕1/4；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.5.8主播不得进行具有挑逗性或诱导性的表演，包括且不限于：脱衣舞等；特殊舞蹈（钢管舞、肚皮舞等）需先联系客服进行报备，报备者需要一定专业素养，<strong>无报备者将暂时关闭直播间</strong>；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.5.9主播不得进行带有性暗示的抚摸、拉扯、舔咬、拍打等动作，或使用道具引起观众对性敏感部位的注意，也不能利用身体上的敏感部位进行游戏，包括且不限于：猜内裤的颜色、猜内衣的颜色、剪丝袜、直播脱/穿丝袜、撕扯或剪衣服等；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.5.10主播不得做出有走光风险的动作，包括且不限于弯腰、高抬腿、双腿分开、劈叉、下腰、倒立、频繁切换坐姿等；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.5.11主播不得以诱惑、挑逗性质的声音或语言吸引观众，包括且不限于模仿动物发情时的叫声、使用直接或者隐晦性暗示词语；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.5.12禁止主播以暴力工具枪支（含仿真枪）、刀具、赌博工具、性用品、内衣等涉及暴力或不雅内容的物品作为表演道具；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.5.13在直播过程中严禁穿着或在未经允许的情况下刻意拍摄穿着带有中华人民共和国（包含港澳台）国家机关人员、军队工作制服的内容进行直播，包括且不仅限于警服、军服、城管制服等；直播内容与此相关的情况下，必须先取得所属部门的直播许可，并且联系客服进行报备，直播间将暂时关闭；身着宗教着装，需先联系客服进行报备，<strong>无报备者将暂时关闭直播间</strong>；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.5.14特殊行业、具有较高专业知识要求的行业(例如：医生、律师等），直播内容与此相关的情况下，需先联系客服进行报备，<strong>无报备者将暂时关闭直播间</strong>；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.5.15主播不得展示或露出纹身。</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><strong><br/> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</strong></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><strong>2.6商务及广告内容规范</strong></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.6.1主播在直播画面中放置的文字内容，均不得影响观众观看，放置的位置必须在直播画面的四角的任意一角，且不得超过屏幕的1/10；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.6.2直播画面不得出现图片信息的广告内容，例如：二维码、商品图片等，如有需要必须经过工作人员审核通过；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.6.3&nbsp;QQ号、QQ粉丝群等主播个人的联系方式，必须为文字形式，放置在直播画面的四角的任意一角，且不得超过屏幕的1/10；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.6.4禁止出现支付宝、微信、网银、红包等交易方式，包括标注红包、价钱、价格、低价、有偿等非钠斯直播平台礼物、涉及金钱交易内容；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.6.5代练广告必须通过正规的电商网站进行，不得出现例如：代练加QQ12345678、代练联系微信XXXXXX等方式；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.6.6禁止以出售、展示商品为主要直播内容，且无正常直播互动的直播形式；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.6.7除钠斯直播官方提供的正规游戏推广和其他广告外，禁止主播在直播间以任何形式（包括且不限于二维码、文字、网址、图片、语音等）与观众进行私下交易行为，如账号交易、通过非正规电商网站（如：淘宝）的商品交易等；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">2.6.8 禁止通过直播售卖国家明令禁止销售的商品，包括且不限于医疗器械、药品、烟草类产品等。</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">符合规则的图例展示如下：</p><p class=\"p\" style=\"text-align:center;margin-left:0.0000pt;\"><img src=\"https://sta-op.douyucdn.cn/dylamr/2019/08/30/abfb41ff9881afe183d2128eaf8dfead.png\" alt=\"钠斯直播内容管理规定（2019修订）\"/></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><strong>2.7</strong><strong>钠斯平台特殊板块相关规定</strong></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><strong>2.7</strong><strong>.</strong><strong>1</strong><strong>数码板块：</strong></p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">（1）严禁主播的配置单或者文字超过播放器的四分之一，文字颜色必须统一，且必须放在页面的左侧；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">（2）严禁出现“问配置”、“预算”、“专业”、“买”、“卖”字眼的标题；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">（3）严禁在播放器上长时间放置价格清单，或者在配置单下面注明价格；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">（4）严禁出现零互动的流水线装机行为或者挂机行为，直播过程中不得向观众推销产品；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">（5）禁止直播间画面长期固定展示露出品牌商logo或名称；</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\">（6）禁止宣传第三方电商（即除钠斯电商活动及主播自家店铺以外的第三方平台）的导流广告行为，例如：买主板就去京东；内存淘宝购实惠；显卡只选名人堂等。</p><p class=\"p\" style=\"margin-left:0pt;text-indent:2em;\"><br/></p><p class=\"p\" style=\"text-indent:2em;\"><strong>第</strong><strong>三</strong><strong>章　附　则</strong></p><p class=\"p\" style=\"text-indent:2em;\"><strong>钠斯直播平台将根据平台发展并结合相关法律、法规、政策和监管部门的指令，更新本管理规定。以上内容如有疑问，请及时联系钠斯官方客服QQ</strong></p><p class=\"p\" style=\"text-indent:2em;\"><strong><br/> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</strong></p><p class=\"p\" style=\"text-align: left; text-indent: 2em;\"><strong>本</strong><strong>管理规定</strong><strong>自2020</strong><strong>年</strong><strong>2</strong><strong>月1</strong><strong>4</strong><strong>日起施行</strong><strong>。</strong></p>', 5);

-- ----------------------------
-- Table structure for db_shop
-- ----------------------------
DROP TABLE IF EXISTS `db_shop`;
CREATE TABLE `db_shop`  (
  `id` int(10) NOT NULL,
  `status` int(1) NULL DEFAULT 1 COMMENT '0-未支付保证金 1-正常 2-关闭 3-封禁',
  `profit` decimal(10, 2) NULL DEFAULT NULL COMMENT '现有收益',
  `total_profit` decimal(10, 2) NULL DEFAULT NULL COMMENT '累计收益',
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_shop
-- ----------------------------

-- ----------------------------
-- Table structure for db_shop_cart
-- ----------------------------
DROP TABLE IF EXISTS `db_shop_cart`;
CREATE TABLE `db_shop_cart`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL,
  `shopid` int(10) NULL DEFAULT NULL,
  `operate_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_shop_cart
-- ----------------------------

-- ----------------------------
-- Table structure for db_shop_cart_goods
-- ----------------------------
DROP TABLE IF EXISTS `db_shop_cart_goods`;
CREATE TABLE `db_shop_cart_goods`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL,
  `shopid` int(11) NULL DEFAULT NULL,
  `goodsid` int(10) NULL DEFAULT NULL,
  `inventoryid` int(10) NULL DEFAULT NULL,
  `count` int(10) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_shop_cart_goods
-- ----------------------------

-- ----------------------------
-- Table structure for db_shop_deposit_order
-- ----------------------------
DROP TABLE IF EXISTS `db_shop_deposit_order`;
CREATE TABLE `db_shop_deposit_order`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopid` int(11) NULL DEFAULT NULL,
  `order_no` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `pay_channel` int(255) NULL DEFAULT NULL COMMENT '1-微信 2-支付宝 3-苹果支付 4-其他 5-人工',
  `trade_no` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `amount` decimal(10, 2) NULL DEFAULT NULL,
  `pay_status` int(255) NULL DEFAULT 0,
  `create_time` datetime(0) NULL DEFAULT NULL,
  `pay_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_shop_deposit_order
-- ----------------------------

-- ----------------------------
-- Table structure for db_shop_goods
-- ----------------------------
DROP TABLE IF EXISTS `db_shop_goods`;
CREATE TABLE `db_shop_goods`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `shopid` int(10) NULL DEFAULT NULL,
  `categoryid` int(10) NULL DEFAULT NULL,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '标题',
  `thumb_urls` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '封面图',
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '详情',
  `desc_img_urls` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '详情图',
  `delivery` int(10) NULL DEFAULT NULL COMMENT '发货周期 /小时',
  `freight` int(10) NULL DEFAULT NULL COMMENT '运费',
  `price` decimal(10, 2) NULL DEFAULT NULL COMMENT '价格',
  `status` int(1) NULL DEFAULT 1 COMMENT '0-待审核 1-已上架 2-已下架 3-审核拒绝',
  `sale_count` int(10) NULL DEFAULT 0 COMMENT '销量',
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '发货地',
  `live_explaining` int(1) NULL DEFAULT 0 COMMENT '1-直播讲解中',
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_shop_goods
-- ----------------------------

-- ----------------------------
-- Table structure for db_shop_goods_category
-- ----------------------------
DROP TABLE IF EXISTS `db_shop_goods_category`;
CREATE TABLE `db_shop_goods_category`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `parentid` int(10) NULL DEFAULT 0,
  `status` int(1) NULL DEFAULT 1 COMMENT '0-失效 1-正常',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_shop_goods_category
-- ----------------------------
INSERT INTO `db_shop_goods_category` VALUES (1, '服饰/鞋包', 0, 1);
INSERT INTO `db_shop_goods_category` VALUES (2, 'T恤', 1, 1);
INSERT INTO `db_shop_goods_category` VALUES (3, '卫衣', 1, 1);
INSERT INTO `db_shop_goods_category` VALUES (4, '上衣', 1, 1);
INSERT INTO `db_shop_goods_category` VALUES (5, '裤子', 1, 1);
INSERT INTO `db_shop_goods_category` VALUES (6, '衬衫', 1, 1);
INSERT INTO `db_shop_goods_category` VALUES (7, '数码', 0, 1);
INSERT INTO `db_shop_goods_category` VALUES (8, '手机', 7, 1);
INSERT INTO `db_shop_goods_category` VALUES (9, '电脑', 7, 1);
INSERT INTO `db_shop_goods_category` VALUES (10, '电视', 7, 1);

-- ----------------------------
-- Table structure for db_shop_goods_color
-- ----------------------------
DROP TABLE IF EXISTS `db_shop_goods_color`;
CREATE TABLE `db_shop_goods_color`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `goodsid` int(11) NULL DEFAULT 0,
  `color` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `img_url` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_shop_goods_color
-- ----------------------------

-- ----------------------------
-- Table structure for db_shop_goods_evaluate
-- ----------------------------
DROP TABLE IF EXISTS `db_shop_goods_evaluate`;
CREATE TABLE `db_shop_goods_evaluate`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL,
  `goodsid` int(10) NULL DEFAULT NULL,
  `ordergoodsid` int(10) NULL DEFAULT NULL,
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `img_urls` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `create_time` datetime(0) NULL DEFAULT NULL,
  `score` int(1) NULL DEFAULT 5,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_shop_goods_evaluate
-- ----------------------------

-- ----------------------------
-- Table structure for db_shop_goods_inventory
-- ----------------------------
DROP TABLE IF EXISTS `db_shop_goods_inventory`;
CREATE TABLE `db_shop_goods_inventory`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) NULL DEFAULT NULL,
  `colorid` int(10) NULL DEFAULT NULL,
  `sizeid` int(10) NULL DEFAULT 0,
  `left_count` int(10) NULL DEFAULT 0 COMMENT '库存',
  `sale_count` int(10) NULL DEFAULT 0 COMMENT '已售',
  `price` decimal(10, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_shop_goods_inventory
-- ----------------------------

-- ----------------------------
-- Table structure for db_shop_goods_size
-- ----------------------------
DROP TABLE IF EXISTS `db_shop_goods_size`;
CREATE TABLE `db_shop_goods_size`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) NULL DEFAULT 0,
  `size` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_shop_goods_size
-- ----------------------------

-- ----------------------------
-- Table structure for db_shop_goods_visits
-- ----------------------------
DROP TABLE IF EXISTS `db_shop_goods_visits`;
CREATE TABLE `db_shop_goods_visits`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL,
  `goodsid` int(11) NULL DEFAULT NULL,
  `visits_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_shop_goods_visits
-- ----------------------------

-- ----------------------------
-- Table structure for db_shop_order
-- ----------------------------
DROP TABLE IF EXISTS `db_shop_order`;
CREATE TABLE `db_shop_order`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL,
  `order_no` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `total_price` decimal(10, 2) NULL DEFAULT NULL,
  `create_time` datetime(0) NULL DEFAULT NULL,
  `pay_channel` int(1) NULL DEFAULT NULL COMMENT '1-微信 2-支付宝 3-苹果支付 4-其他 5-人工',
  `pay_time` datetime(0) NULL DEFAULT NULL,
  `pay_no` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `pay_amount` decimal(10, 2) NULL DEFAULT NULL COMMENT '实际支付金额',
  `pay_status` int(1) NULL DEFAULT 0 COMMENT '0-待支付 1-已支付 2-订单超时 ',
  PRIMARY KEY (`id`, `order_no`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_shop_order
-- ----------------------------

-- ----------------------------
-- Table structure for db_shop_order_goods
-- ----------------------------
DROP TABLE IF EXISTS `db_shop_order_goods`;
CREATE TABLE `db_shop_order_goods`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL,
  `shopid` int(10) NULL DEFAULT NULL,
  `suborderid` int(10) NULL DEFAULT NULL COMMENT '子订单id',
  `goodsid` int(10) NULL DEFAULT NULL,
  `inventoryid` int(10) NULL DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `size` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `count` int(10) NULL DEFAULT 0,
  `price` decimal(10, 2) NULL DEFAULT NULL COMMENT '单价',
  `evaluate_status` int(1) NULL DEFAULT 0 COMMENT '0-待评价 1-已评价',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_shop_order_goods
-- ----------------------------

-- ----------------------------
-- Table structure for db_shop_order_return
-- ----------------------------
DROP TABLE IF EXISTS `db_shop_order_return`;
CREATE TABLE `db_shop_order_return`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL,
  `shopid` int(10) NULL DEFAULT NULL,
  `suborderid` int(10) NULL DEFAULT NULL COMMENT '子订单id',
  `ordergoodsid` int(10) NULL DEFAULT NULL COMMENT '订单商品id',
  `reason` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '原因',
  `amount` decimal(10, 2) NULL DEFAULT NULL COMMENT '退款金额',
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '原因描述',
  `status` int(1) NULL DEFAULT 1 COMMENT '1-发起退货 2-卖家同意退货 3-卖家拒绝退货 4-退货完成 5-买家取消退货',
  `create_time` datetime(0) NULL DEFAULT NULL,
  `operate_time` datetime(0) NULL DEFAULT NULL,
  `express_no` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '快递单号',
  `receiver_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '收货人姓名',
  `receiver_mobile` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '收货人手机号',
  `receiver_address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '收货人地址',
  `refund_trade_no` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '第三方支付平台退款单号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_shop_order_return
-- ----------------------------

-- ----------------------------
-- Table structure for db_shop_suborder
-- ----------------------------
DROP TABLE IF EXISTS `db_shop_suborder`;
CREATE TABLE `db_shop_suborder`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL,
  `shopid` int(10) NULL DEFAULT NULL,
  `parentid` int(10) NULL DEFAULT 0 COMMENT '母订单id',
  `order_no` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `total_price` decimal(10, 2) NULL DEFAULT NULL,
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '买家备注',
  `create_time` datetime(0) NULL DEFAULT NULL,
  `pay_channel` int(1) NULL DEFAULT NULL COMMENT '1-微信 2-支付宝 3-苹果支付 4-其他 5-人工',
  `pay_time` datetime(0) NULL DEFAULT NULL,
  `pay_no` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `pay_type` int(1) NULL DEFAULT 0 COMMENT '1-母订单支付 2-子订单支付',
  `pay_amount` decimal(10, 2) NULL DEFAULT NULL COMMENT '支付金额（pay_type=2时有效）',
  `status` int(1) NULL DEFAULT 0 COMMENT '0-待支付 1-已支付 2-订单超时(关闭) 3-已确认收货',
  `delivery_status` int(1) NULL DEFAULT 0 COMMENT '0-待发货 1-已发货 2-已签收',
  `express_no` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '快递单号',
  `express_company` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '快递公司',
  `receiver_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '收货人姓名',
  `receiver_mobile` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '收货人手机号',
  `receiver_address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '收货人地址',
  `display_status` int(1) NULL DEFAULT 0 COMMENT '0-显示 1-买家删除',
  `return_amount` decimal(10, 2) NULL DEFAULT NULL COMMENT '退款金额',
  PRIMARY KEY (`id`, `order_no`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_shop_suborder
-- ----------------------------

-- ----------------------------
-- Table structure for db_shop_withdrawals
-- ----------------------------
DROP TABLE IF EXISTS `db_shop_withdrawals`;
CREATE TABLE `db_shop_withdrawals`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `shopid` int(10) NULL DEFAULT NULL,
  `apply_cash` decimal(10, 2) NULL DEFAULT NULL COMMENT '提取金额',
  `commission_cash` decimal(10, 2) NULL DEFAULT NULL COMMENT '手续费',
  `alipay_account` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '收款支付宝账号',
  `alipay_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '收款人支付宝姓名',
  `create_time` datetime(0) NULL DEFAULT NULL COMMENT '申请提现时间',
  `operate_time` datetime(0) NULL DEFAULT NULL COMMENT '处理时间',
  `status` int(1) NULL DEFAULT 0 COMMENT '0-未处理 1-已提现 2-已拒绝(余额返还) 3-异常单(扣除金币)',
  `trade_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '转账订单号',
  `trade_cash` decimal(10, 2) NULL DEFAULT NULL COMMENT '到账金额',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_shop_withdrawals
-- ----------------------------

-- ----------------------------
-- Table structure for db_shortvideo
-- ----------------------------
DROP TABLE IF EXISTS `db_shortvideo`;
CREATE TABLE `db_shortvideo`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `thumb_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `play_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `tag` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '标签',
  `create_time` datetime(0) NULL DEFAULT NULL,
  `status` int(1) NULL DEFAULT 0 COMMENT '0-待审核 1-审核通过 2-审核拒绝 3-已下架',
  `comment_count` int(10) NULL DEFAULT 0 COMMENT '评论数量',
  `play_count` int(10) NULL DEFAULT 0 COMMENT '播放数量',
  `like_count` int(10) NULL DEFAULT 0 COMMENT '点赞数量',
  `share_count` int(10) NULL DEFAULT 0 COMMENT '分享数量',
  `collect_count` int(10) NULL DEFAULT NULL COMMENT '收藏数量',
  `banned_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '下架原因',
  `topic` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '话题',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_shortvideo
-- ----------------------------

-- ----------------------------
-- Table structure for db_shortvideo_collect
-- ----------------------------
DROP TABLE IF EXISTS `db_shortvideo_collect`;
CREATE TABLE `db_shortvideo_collect`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL,
  `videoid` int(10) NULL DEFAULT NULL,
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_shortvideo_collect
-- ----------------------------

-- ----------------------------
-- Table structure for db_shortvideo_comment
-- ----------------------------
DROP TABLE IF EXISTS `db_shortvideo_comment`;
CREATE TABLE `db_shortvideo_comment`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rootid` int(10) NULL DEFAULT 0 COMMENT '根评论',
  `tocommentid` int(10) NULL DEFAULT 0 COMMENT '被回复评论',
  `videoid` int(10) NULL DEFAULT NULL,
  `uid` int(10) NULL DEFAULT NULL,
  `touid` int(10) NULL DEFAULT 0 COMMENT '被回复用户id',
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `create_time` datetime(0) NULL DEFAULT NULL,
  `like_count` int(10) NULL DEFAULT 0 COMMENT '被点赞数量',
  `reply_count` int(10) NULL DEFAULT 0 COMMENT '回复数量',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_shortvideo_comment
-- ----------------------------

-- ----------------------------
-- Table structure for db_shortvideo_comment_like
-- ----------------------------
DROP TABLE IF EXISTS `db_shortvideo_comment_like`;
CREATE TABLE `db_shortvideo_comment_like`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL,
  `commentid` int(10) NULL DEFAULT NULL,
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_shortvideo_comment_like
-- ----------------------------

-- ----------------------------
-- Table structure for db_shortvideo_like
-- ----------------------------
DROP TABLE IF EXISTS `db_shortvideo_like`;
CREATE TABLE `db_shortvideo_like`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL,
  `videoid` int(10) NULL DEFAULT NULL,
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_shortvideo_like
-- ----------------------------

-- ----------------------------
-- Table structure for db_shortvideo_report
-- ----------------------------
DROP TABLE IF EXISTS `db_shortvideo_report`;
CREATE TABLE `db_shortvideo_report`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL,
  `videoid` int(10) NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `content` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '举报内容',
  `create_time` datetime(0) NULL DEFAULT NULL,
  `img_urls` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_shortvideo_report
-- ----------------------------

-- ----------------------------
-- Table structure for db_shortvideo_watch
-- ----------------------------
DROP TABLE IF EXISTS `db_shortvideo_watch`;
CREATE TABLE `db_shortvideo_watch`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL,
  `videoid` int(10) NULL DEFAULT NULL,
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_shortvideo_watch
-- ----------------------------

-- ----------------------------
-- Table structure for db_smscode
-- ----------------------------
DROP TABLE IF EXISTS `db_smscode`;
CREATE TABLE `db_smscode`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nation_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '国码',
  `mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `code` int(6) NULL DEFAULT NULL,
  `status` int(1) NULL DEFAULT 0 COMMENT '0-未使用 1-已使用',
  `create_time` datetime(0) NULL DEFAULT NULL,
  `request_ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '请求ip',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_smscode
-- ----------------------------

-- ----------------------------
-- Table structure for db_statistics
-- ----------------------------
DROP TABLE IF EXISTS `db_statistics`;
CREATE TABLE `db_statistics`  (
  `id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'DATE_FORMAT(NOW(),\'%Y%m%d\')',
  `launch_ios` int(10) NULL DEFAULT 0 COMMENT '苹果日启动数',
  `launch_android` int(10) NULL DEFAULT 0 COMMENT '安卓日启动数',
  `activity_ios` int(10) NULL DEFAULT 0 COMMENT '苹果日活用户数',
  `activity_android` int(10) NULL DEFAULT 0 COMMENT '安卓日活用户数',
  `regist_ios` int(10) NULL DEFAULT 0 COMMENT '苹果日注册数',
  `regist_android` int(10) NULL DEFAULT 0 COMMENT '安卓日注册数',
  `regist_pc` int(10) NULL DEFAULT 0 COMMENT 'PC日注册数',
  `time` datetime(0) NULL DEFAULT NULL COMMENT '日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_statistics
-- ----------------------------

-- ----------------------------
-- Table structure for db_system_msg
-- ----------------------------
DROP TABLE IF EXISTS `db_system_msg`;
CREATE TABLE `db_system_msg`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `touid` int(10) NULL DEFAULT 0 COMMENT '推送对象（多个,隔开） 为0则为所有用户',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `content` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '外链地址',
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_system_msg
-- ----------------------------

-- ----------------------------
-- Table structure for db_topic
-- ----------------------------
DROP TABLE IF EXISTS `db_topic`;
CREATE TABLE `db_topic`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `back_img_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `used_times` int(10) NULL DEFAULT 0 COMMENT '参与人次',
  `status` int(255) NULL DEFAULT 1 COMMENT '1-正常',
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_topic
-- ----------------------------
INSERT INTO `db_topic` VALUES (1, 'OOTD', '', 'OOTD 每天不一样，给你最fell的感觉', 124, 1, '2020-11-11 09:56:42');
INSERT INTO `db_topic` VALUES (2, '每日穿搭', '', 'OOTD 每天不一样，给你最fell的感觉', 2341, 1, '2020-11-11 09:56:42');
INSERT INTO `db_topic` VALUES (3, '心情不美丽', '', '天气不好，心情不好，快来释放自己吧^_^', 230, 1, '2020-11-11 09:56:42');
INSERT INTO `db_topic` VALUES (4, '撸猫日记', '', '每天最爱的就是与你一起窝在床上', 1235, 1, '2020-11-11 09:56:42');
INSERT INTO `db_topic` VALUES (5, '这周日你有空吗', '', '这周日，还是下周日？', 2343, 1, '2020-11-11 09:56:42');
INSERT INTO `db_topic` VALUES (6, '我有马甲线', '', '马甲线，最靓丽的风景线！', 65449, 1, '2020-11-11 09:56:42');
INSERT INTO `db_topic` VALUES (7, '秀出大长腿', '', '长腿尤物，紧急集合！', 3765, 1, '2020-11-11 09:56:42');

-- ----------------------------
-- Table structure for db_user
-- ----------------------------
DROP TABLE IF EXISTS `db_user`;
CREATE TABLE `db_user`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `nick_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `account` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '手机号',
  `nation_code` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '国码',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `wx_unionid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '微信unionid',
  `qq_unionid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'QQ unionid',
  `online_status` int(1) NULL DEFAULT 9 COMMENT '1-在线 2-进入后台（离开）3-通话中 9-离线',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '头像地址',
  `gold` int(10) NULL DEFAULT 0 COMMENT '金币数量',
  `diamond` int(10) NULL DEFAULT 0 COMMENT '钻石数量',
  `diamond_total` int(10) NULL DEFAULT 0 COMMENT '累计钻石数量',
  `tags` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '标签数组',
  `anchor_level` int(10) NULL DEFAULT 3 COMMENT '主播等级 默认3级',
  `user_level` int(10) NULL DEFAULT 1 COMMENT '用户等级 默认1级',
  `point` int(10) NULL DEFAULT 0 COMMENT '经验值',
  `anchor_point` int(10) NULL DEFAULT 0 COMMENT '主播经验值',
  `is_anchor` int(1) NULL DEFAULT 0 COMMENT '是否主播 1-是',
  `inliving` int(11) NULL DEFAULT 0 COMMENT '是否正在直播中 1-是',
  `status` int(1) NULL DEFAULT 0 COMMENT '0-正常 1封禁',
  `sharing_ratio` int(3) NULL DEFAULT 40 COMMENT '分成比例 %',
  `guildid` int(10) NULL DEFAULT 0 COMMENT '所属公会id',
  `agentid` int(10) NULL DEFAULT 0 COMMENT '上级代理id',
  `rec_weight` int(10) NULL DEFAULT 0 COMMENT '推广权重0-100',
  `call_recive_count` int(10) NULL DEFAULT 0 COMMENT '收到通话请求次数',
  `call_accept_count` int(10) NULL DEFAULT 0 COMMENT '接受通话请求次数',
  `vip_date` datetime(0) NULL DEFAULT NULL COMMENT 'vip到期日',
  `vip_level` int(11) NULL DEFAULT 0 COMMENT 'vip等级',
  `auth_time` datetime(0) NULL DEFAULT NULL COMMENT '完成实名认证时间',
  `regist_time` datetime(0) NULL DEFAULT NULL,
  `last_login` datetime(0) NULL DEFAULT NULL,
  `last_online` datetime(0) NULL DEFAULT NULL COMMENT '上次在线时间',
  `login_platform` int(1) NULL DEFAULT 0 COMMENT '0-Web 1-iOS 2-Android',
  `is_salesman` int(11) NULL DEFAULT 0 COMMENT '是否是业务员 0否 1是',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_user
-- ----------------------------

-- ----------------------------
-- Table structure for db_user_address
-- ----------------------------
DROP TABLE IF EXISTS `db_user_address`;
CREATE TABLE `db_user_address`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL,
  `province` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `district` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `mobile` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `is_default` int(1) NULL DEFAULT 0 COMMENT '1-默认地址',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_user_address
-- ----------------------------

-- ----------------------------
-- Table structure for db_user_auth
-- ----------------------------
DROP TABLE IF EXISTS `db_user_auth`;
CREATE TABLE `db_user_auth`  (
  `uid` int(10) NOT NULL,
  `real_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '真实姓名',
  `id_num` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '身份证号',
  `id_card_url` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '身份证照片 正面,反面,手持',
  `submit_time` datetime(0) NULL DEFAULT NULL COMMENT '提交时间',
  `status` int(1) NULL DEFAULT 0 COMMENT '审核状态0-待审核 1-已通过 2-已拒绝',
  `reject_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '拒绝原因',
  `check_time` datetime(0) NULL DEFAULT NULL COMMENT '审核操作时间',
  PRIMARY KEY (`uid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_user_auth
-- ----------------------------

-- ----------------------------
-- Table structure for db_user_consume
-- ----------------------------
DROP TABLE IF EXISTS `db_user_consume`;
CREATE TABLE `db_user_consume`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL COMMENT '主播id',
  `consume` int(10) NULL DEFAULT 0 COMMENT '当日消费金币',
  `date` date NULL DEFAULT NULL COMMENT '日期 2020-02-02',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_user_consume
-- ----------------------------

-- ----------------------------
-- Table structure for db_user_level_rule
-- ----------------------------
DROP TABLE IF EXISTS `db_user_level_rule`;
CREATE TABLE `db_user_level_rule`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `level` int(10) NULL DEFAULT NULL COMMENT '等级',
  `point` int(10) NULL DEFAULT NULL COMMENT '经验值',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_user_level_rule
-- ----------------------------
INSERT INTO `db_user_level_rule` VALUES (1, 1, 0);
INSERT INTO `db_user_level_rule` VALUES (2, 2, 1000);
INSERT INTO `db_user_level_rule` VALUES (3, 3, 2800);
INSERT INTO `db_user_level_rule` VALUES (4, 4, 20000);
INSERT INTO `db_user_level_rule` VALUES (5, 5, 60000);
INSERT INTO `db_user_level_rule` VALUES (6, 6, 170000);
INSERT INTO `db_user_level_rule` VALUES (7, 7, 800000);
INSERT INTO `db_user_level_rule` VALUES (8, 8, 1900000);
INSERT INTO `db_user_level_rule` VALUES (9, 9, 3000000);
INSERT INTO `db_user_level_rule` VALUES (10, 10, 5000000);
INSERT INTO `db_user_level_rule` VALUES (11, 11, 7000000);
INSERT INTO `db_user_level_rule` VALUES (12, 12, 9000000);
INSERT INTO `db_user_level_rule` VALUES (13, 13, 10000000);
INSERT INTO `db_user_level_rule` VALUES (14, 14, 11000000);
INSERT INTO `db_user_level_rule` VALUES (15, 15, 12000000);
INSERT INTO `db_user_level_rule` VALUES (16, 16, 13000000);
INSERT INTO `db_user_level_rule` VALUES (17, 17, 14000000);
INSERT INTO `db_user_level_rule` VALUES (18, 18, 15000000);
INSERT INTO `db_user_level_rule` VALUES (19, 19, 16000000);
INSERT INTO `db_user_level_rule` VALUES (20, 20, 17000000);

-- ----------------------------
-- Table structure for db_user_message_status
-- ----------------------------
DROP TABLE IF EXISTS `db_user_message_status`;
CREATE TABLE `db_user_message_status`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `number` int(11) NOT NULL DEFAULT 0 COMMENT '消息数量',
  `status` int(2) NOT NULL DEFAULT 0 COMMENT '状态（0未读 1已读）',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户消息推送' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_user_message_status
-- ----------------------------

-- ----------------------------
-- Table structure for db_user_onlinetime
-- ----------------------------
DROP TABLE IF EXISTS `db_user_onlinetime`;
CREATE TABLE `db_user_onlinetime`  (
  `uid` int(10) NOT NULL,
  `total` int(10) NULL DEFAULT 0 COMMENT '在线时长：秒',
  `month` int(10) NULL DEFAULT 0 COMMENT '月在线时长：秒',
  PRIMARY KEY (`uid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_user_onlinetime
-- ----------------------------

-- ----------------------------
-- Table structure for db_user_photo
-- ----------------------------
DROP TABLE IF EXISTS `db_user_photo`;
CREATE TABLE `db_user_photo`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL,
  `img_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_cover` int(1) NULL DEFAULT 0 COMMENT '1-封面图',
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_user_photo
-- ----------------------------

-- ----------------------------
-- Table structure for db_user_profile
-- ----------------------------
DROP TABLE IF EXISTS `db_user_profile`;
CREATE TABLE `db_user_profile`  (
  `uid` int(11) NOT NULL,
  `age` int(3) NULL DEFAULT 22,
  `gender` int(1) NULL DEFAULT 0 COMMENT '0-女 1-男',
  `career` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '职业',
  `height` int(3) NULL DEFAULT 0 COMMENT '身高 cm',
  `weight` int(10) NULL DEFAULT 0 COMMENT '体重 kg',
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `birthday` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '生日',
  `constellation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '星座',
  `signature` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '个性签名',
  `photos` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '照片墙',
  PRIMARY KEY (`uid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_user_profile
-- ----------------------------

-- ----------------------------
-- Table structure for db_user_profit
-- ----------------------------
DROP TABLE IF EXISTS `db_user_profit`;
CREATE TABLE `db_user_profit`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL,
  `coin_count` int(10) NULL DEFAULT 0 COMMENT '金币或钻石数量 type=0时为金币 1时为钻石',
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '收支说明',
  `type` int(1) NULL DEFAULT 0 COMMENT '1-收入 0-消费',
  `consume_type` int(1) NULL DEFAULT 1 COMMENT '消费类型 1-礼物 2-动态 3-守护',
  `resid` int(10) NULL DEFAULT NULL COMMENT '商品id 对应giftid或momentid',
  `count` int(10) NULL DEFAULT 1,
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_user_profit
-- ----------------------------

-- ----------------------------
-- Table structure for db_user_rec
-- ----------------------------
DROP TABLE IF EXISTS `db_user_rec`;
CREATE TABLE `db_user_rec`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL COMMENT '推广用户id',
  `rec_weight` int(3) NULL DEFAULT 50 COMMENT '推广权重（1-100）',
  `start_time` datetime(0) NULL DEFAULT NULL COMMENT '推广开始时间（使用定时任务写入user表）',
  `end_time` datetime(0) NULL DEFAULT NULL COMMENT '推广结束时间（使用定时任务写入user表）',
  `start_status` int(1) NULL DEFAULT 0 COMMENT '0-未处理 1-已处理',
  `end_status` int(1) NULL DEFAULT 0 COMMENT '0-未处理 1-已处理',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_user_rec
-- ----------------------------

-- ----------------------------
-- Table structure for db_user_withdraw_account
-- ----------------------------
DROP TABLE IF EXISTS `db_user_withdraw_account`;
CREATE TABLE `db_user_withdraw_account`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL,
  `default` int(1) NULL DEFAULT 0 COMMENT '1-默认账户',
  `alipay_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '支付宝姓名',
  `alipay_account` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '支付宝账号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_user_withdraw_account
-- ----------------------------

-- ----------------------------
-- Table structure for db_vip_price
-- ----------------------------
DROP TABLE IF EXISTS `db_vip_price`;
CREATE TABLE `db_vip_price`  (
  `level` int(2) NOT NULL DEFAULT 0 COMMENT 'vip等级',
  `price` decimal(10, 2) NULL DEFAULT NULL COMMENT 'VIP价格/元',
  `gold` int(10) NULL DEFAULT NULL COMMENT '赠送金币数量',
  PRIMARY KEY (`level`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_vip_price
-- ----------------------------
INSERT INTO `db_vip_price` VALUES (1, 0.01, 400);
INSERT INTO `db_vip_price` VALUES (2, 0.02, 800);
INSERT INTO `db_vip_price` VALUES (3, 0.03, 8000);
INSERT INTO `db_vip_price` VALUES (4, 0.04, 24000);

-- ----------------------------
-- Table structure for db_visitor_log
-- ----------------------------
DROP TABLE IF EXISTS `db_visitor_log`;
CREATE TABLE `db_visitor_log`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NULL DEFAULT NULL COMMENT '被访问用户id',
  `visitorid` int(10) NULL DEFAULT NULL COMMENT '访客id',
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_visitor_log
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
