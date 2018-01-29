/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50636
Source Host           : localhost:3306
Source Database       : initdb

Target Server Type    : MYSQL
Target Server Version : 50636
File Encoding         : 65001

Date: 2017-11-16 08:14:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tbl_activity
-- ----------------------------
DROP TABLE IF EXISTS `tbl_activity`;
CREATE TABLE `tbl_activity` (
  `id` bigint(20) NOT NULL,
  `provider_id` bigint(20) DEFAULT '0',
  `product_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `kind` tinyint(2) DEFAULT '1',
  `man_cnt` int(20) DEFAULT '0',
  `group_cnt` int(20) DEFAULT '0',
  `group_cost` decimal(20,2) DEFAULT '0.00',
  `origin_cost` decimal(20,2) DEFAULT '0.00',
  `buy_cnt` varchar(255) DEFAULT NULL,
  `status` tinyint(2) DEFAULT '1' COMMENT 'activity status',
  `recommend_status` tinyint(2) DEFAULT '0' COMMENT 'recommend status(0:none, 1:recommend)',
  `order` int(20) DEFAULT '1000',
  `more_data` text,
  `users` varchar(255) DEFAULT '' COMMENT 'user list entered in this activity',
  `nums` varchar(255) DEFAULT '' COMMENT 'count list that user bought',
  `reason` varchar(255) DEFAULT '',
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `success_time` datetime DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_activity
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_carousel
-- ----------------------------
DROP TABLE IF EXISTS `tbl_carousel`;
CREATE TABLE `tbl_carousel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Carousel Image Url',
  `type` int(5) DEFAULT '0' COMMENT 'The Type of Carousel',
  `activity` varchar(50) CHARACTER SET utf8 DEFAULT '0' COMMENT 'The part of used in.',
  `sort` int(11) DEFAULT '1000' COMMENT 'The sorting number of the carousel',
  `create_date` datetime DEFAULT NULL COMMENT 'Created date of Carousel.',
  `status` int(5) DEFAULT '0' COMMENT 'The availability status of carousel.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of tbl_carousel
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_coupon
-- ----------------------------
DROP TABLE IF EXISTS `tbl_coupon`;
CREATE TABLE `tbl_coupon` (
  `id` varchar(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `coupon` decimal(10,0) DEFAULT '0',
  `using` tinyint(2) DEFAULT '0',
  `pass_time` datetime DEFAULT NULL,
  `use_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_coupon
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_feedback
-- ----------------------------
DROP TABLE IF EXISTS `tbl_feedback`;
CREATE TABLE `tbl_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(50) DEFAULT NULL COMMENT 'user id',
  `feedback` varchar(255) DEFAULT NULL COMMENT 'feedback message',
  `add_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_feedback
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_menu
-- ----------------------------
DROP TABLE IF EXISTS `tbl_menu`;
CREATE TABLE `tbl_menu` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `parent_id` int(5) DEFAULT '0',
  `menu_id` varchar(10) DEFAULT NULL,
  `menu` varchar(20) DEFAULT NULL COMMENT 'menu item''s caption',
  `order` int(1) DEFAULT '0' COMMENT 'menu order number',
  `url` varchar(50) DEFAULT NULL COMMENT 'menu item jump url',
  `icon` varchar(50) DEFAULT NULL COMMENT 'menu item icon',
  `type` int(5) DEFAULT '1' COMMENT 'menu''s limitation( 0: all, 1: admin, 2: other)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_menu
-- ----------------------------
INSERT INTO `tbl_menu` VALUES ('1', '0', 'm_1', '首页', '1', null, null, '0');
INSERT INTO `tbl_menu` VALUES ('2', '0', 'm_2', '轮播图管理', '2', null, null, '1');
INSERT INTO `tbl_menu` VALUES ('3', '0', 'm_2', '用户管理', '3', null, null, '1');
INSERT INTO `tbl_menu` VALUES ('4', '3', 'm_3_1', '供货商', '1', null, null, '1');
INSERT INTO `tbl_menu` VALUES ('5', '3', 'm_3_2', '配送员', '2', null, null, '1');
INSERT INTO `tbl_menu` VALUES ('6', '3', 'm_3_3', '终端便利店', '3', null, null, '1');
INSERT INTO `tbl_menu` VALUES ('7', '0', 'm_4', '终端便利店', '4', null, null, '2');
INSERT INTO `tbl_menu` VALUES ('8', '0', 'm_4', '商品模板', '5', null, null, '1');
INSERT INTO `tbl_menu` VALUES ('9', '8', 'm_5_1', '商品模板', '1', null, null, '1');
INSERT INTO `tbl_menu` VALUES ('10', '8', 'm_5_2', '分类管理', '2', null, null, '1');
INSERT INTO `tbl_menu` VALUES ('11', '8', 'm_5_3', '品牌管理', '3', null, null, '1');
INSERT INTO `tbl_menu` VALUES ('12', '8', 'm_5_4', '单位管理', '4', null, null, '1');
INSERT INTO `tbl_menu` VALUES ('13', '0', 'm_6', '商品管理', '6', null, null, '1');
INSERT INTO `tbl_menu` VALUES ('14', '0', 'm_7', '拼团活动', '7', null, null, '0');
INSERT INTO `tbl_menu` VALUES ('15', '14', 'm_7_1', '单品活动', '1', null, null, '0');
INSERT INTO `tbl_menu` VALUES ('16', '14', 'm_7_2', '套装活动', '2', null, null, '0');
INSERT INTO `tbl_menu` VALUES ('17', '0', 'm_8', '订单管理', '8', null, null, '0');
INSERT INTO `tbl_menu` VALUES ('18', '0', 'm_9', '配送管理', '9', null, null, '2');
INSERT INTO `tbl_menu` VALUES ('19', '0', 'm_10', '提现管理', '10', null, null, '1');
INSERT INTO `tbl_menu` VALUES ('20', '0', 'm_11', '交易明细', '11', null, null, '1');
INSERT INTO `tbl_menu` VALUES ('21', '0', 'm_12', '我的钱包', '12', null, null, '2');
INSERT INTO `tbl_menu` VALUES ('22', '0', 'm_13', '优惠券管理', '13', null, null, '1');
INSERT INTO `tbl_menu` VALUES ('23', '0', 'm_14', '销售统计', '14', null, null, '0');
INSERT INTO `tbl_menu` VALUES ('24', '23', 'm_14_1', '配送员统计', '1', null, null, '0');
INSERT INTO `tbl_menu` VALUES ('25', '23', 'm_14_2', '商品销量统计', '2', null, null, '0');
INSERT INTO `tbl_menu` VALUES ('26', '23', 'm_14_3', '便利店统计', '3', null, null, '0');
INSERT INTO `tbl_menu` VALUES ('27', '23', 'm_14_4', '供货商统计', '4', null, null, '1');
INSERT INTO `tbl_menu` VALUES ('28', '23', 'm_14_5', '品牌统计', '5', null, null, '0');
INSERT INTO `tbl_menu` VALUES ('29', '23', 'm_14_6', '分类统计', '6', null, null, '0');
INSERT INTO `tbl_menu` VALUES ('30', '23', 'm_14_7', '销售业绩', '7', null, null, '0');
INSERT INTO `tbl_menu` VALUES ('31', '0', 'm_15', '推荐人统计', '15', null, null, '1');
INSERT INTO `tbl_menu` VALUES ('32', '31', 'm_15_1', '供货商', '1', null, null, '1');
INSERT INTO `tbl_menu` VALUES ('33', '31', 'm_15_2', '终端便利店', '2', null, null, '1');
INSERT INTO `tbl_menu` VALUES ('34', '0', 'm_16', '消息管理', '16', null, null, '0');
INSERT INTO `tbl_menu` VALUES ('35', '0', 'm_17', '配送员管理', '17', null, null, '2');
INSERT INTO `tbl_menu` VALUES ('36', '0', 'm_18', '系统管理', '18', null, null, '0');
INSERT INTO `tbl_menu` VALUES ('37', '36', 'm_18_1', '运营人员管理', '1', null, null, '0');
INSERT INTO `tbl_menu` VALUES ('38', '36', 'm_18_2', '角色管理', '2', null, null, '0');
INSERT INTO `tbl_menu` VALUES ('39', '36', 'm_18_3', '修改密码', '3', null, null, '0');

-- ----------------------------
-- Table structure for tbl_news
-- ----------------------------
DROP TABLE IF EXISTS `tbl_news`;
CREATE TABLE `tbl_news` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sender` bigint(50) DEFAULT '0',
  `receiver` bigint(50) DEFAULT '0',
  `type` varchar(255) DEFAULT NULL COMMENT '拼团活动消息,拼团活动消息,....',
  `message` varchar(255) DEFAULT NULL COMMENT '订单号XXXXXX，货到付款金额XXX元，配送员XXX已收款。',
  `status` tinyint(2) DEFAULT '0' COMMENT '0:no read, 1:read',
  `note` varchar(255) DEFAULT '',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_news
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_order
-- ----------------------------
DROP TABLE IF EXISTS `tbl_order`;
CREATE TABLE `tbl_order` (
  `id` varchar(255) NOT NULL COMMENT '0: none, 1: allow, 2: start',
  `shop` bigint(20) DEFAULT '0',
  `provider` bigint(20) DEFAULT '0',
  `pay_method` tinyint(2) DEFAULT '1' COMMENT '1: online pay, 2: after pay',
  `activity_ids` varchar(255) DEFAULT '',
  `activity_cnts` varchar(255) DEFAULT '',
  `origin_cost` decimal(10,2) DEFAULT '0.00',
  `group_cost` decimal(10,2) DEFAULT '0.00',
  `create_time` datetime DEFAULT NULL,
  `pay_cost` decimal(10,2) DEFAULT '0.00' COMMENT 'total paid money = online pay money + wallet money',
  `coupon` decimal(10,2) DEFAULT '0.00' COMMENT 'pay from coupon',
  `pay_wallet` decimal(10,2) DEFAULT '0.00' COMMENT 'amount of money that paid from shop''s balance',
  `pay_time` datetime DEFAULT NULL,
  `ship_man` int(20) DEFAULT '0',
  `ship_status` int(2) DEFAULT '0' COMMENT 'order status(1~6)',
  `ship_time` datetime DEFAULT NULL,
  `refund_cost` decimal(10,2) DEFAULT '0.00',
  `refund_time` datetime DEFAULT NULL,
  `status` tinyint(2) DEFAULT '0',
  `note` varchar(255) DEFAULT '',
  `isSuccess` tinyint(1) DEFAULT '0' COMMENT '0 : none, 1: grouping success, 2: grouping fail',
  `success_time` datetime DEFAULT NULL,
  `cancel_time` datetime DEFAULT NULL,
  `cancel_reason` varchar(255) DEFAULT '',
  `complete_time` datetime DEFAULT NULL,
  `tmp` int(10) DEFAULT '0' COMMENT 'temporary count',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_order
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_product
-- ----------------------------
DROP TABLE IF EXISTS `tbl_product`;
CREATE TABLE `tbl_product` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) DEFAULT '0',
  `provider_id` bigint(20) DEFAULT '0',
  `store` int(11) DEFAULT '0',
  `cost` decimal(10,2) DEFAULT '0.00',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_product
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_product_brand
-- ----------------------------
DROP TABLE IF EXISTS `tbl_product_brand`;
CREATE TABLE `tbl_product_brand` (
  `id` bigint(30) NOT NULL AUTO_INCREMENT,
  `type` int(10) DEFAULT '0' COMMENT 'product type indentifier',
  `name` varchar(255) DEFAULT '' COMMENT 'brand name',
  `image` varchar(255) DEFAULT '' COMMENT 'brand logo image url',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_product_brand
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_product_format
-- ----------------------------
DROP TABLE IF EXISTS `tbl_product_format`;
CREATE TABLE `tbl_product_format` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `barcode` varchar(15) DEFAULT '',
  `name` varchar(50) DEFAULT NULL COMMENT 'product name',
  `type` varchar(10) DEFAULT '' COMMENT 'product type',
  `standard` varchar(20) DEFAULT '' COMMENT '规格型号',
  `brand` varchar(10) DEFAULT '',
  `unit` int(2) DEFAULT '0' COMMENT 'unit ',
  `unit_note` varchar(50) DEFAULT '' COMMENT '1g*12/箱',
  `cover` varchar(255) DEFAULT '',
  `images` text,
  `contents` text,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_product_format
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_product_type
-- ----------------------------
DROP TABLE IF EXISTS `tbl_product_type`;
CREATE TABLE `tbl_product_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) DEFAULT NULL COMMENT 'product kind',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_product_type
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_product_unit
-- ----------------------------
DROP TABLE IF EXISTS `tbl_product_unit`;
CREATE TABLE `tbl_product_unit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'The name of commodity unit',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of tbl_product_unit
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_provider_bankinfo
-- ----------------------------
DROP TABLE IF EXISTS `tbl_provider_bankinfo`;
CREATE TABLE `tbl_provider_bankinfo` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `provider_id` bigint(50) DEFAULT '0',
  `provider_name` varchar(50) DEFAULT '',
  `bank_name` varchar(50) DEFAULT '',
  `card_no` varchar(50) DEFAULT '',
  `cert_no` varchar(50) DEFAULT '',
  `reserve_mobile` varchar(20) DEFAULT '',
  `create_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_provider_bankinfo
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_provider_withdraw
-- ----------------------------
DROP TABLE IF EXISTS `tbl_provider_withdraw`;
CREATE TABLE `tbl_provider_withdraw` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `provider_id` int(20) DEFAULT NULL,
  `money` decimal(10,2) DEFAULT NULL,
  `status` tinyint(2) DEFAULT '1',
  `time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_provider_withdraw
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_role
-- ----------------------------
DROP TABLE IF EXISTS `tbl_role`;
CREATE TABLE `tbl_role` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(20) DEFAULT '0',
  `role` varchar(50) DEFAULT NULL,
  `permission` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_role
-- ----------------------------
INSERT INTO `tbl_role` VALUES ('1', '0', '最高管理员', '');
INSERT INTO `tbl_role` VALUES ('2', '0', '供货商', '');

-- ----------------------------
-- Table structure for tbl_shipping
-- ----------------------------
DROP TABLE IF EXISTS `tbl_shipping`;
CREATE TABLE `tbl_shipping` (
  `id` varchar(20) NOT NULL,
  `ship_id` int(20) DEFAULT NULL,
  `provider` tinyint(10) DEFAULT '0',
  `order_id` text,
  `state` tinyint(2) DEFAULT '1' COMMENT '1- 未装车, 2-已装车, 3-配送完成',
  `money` decimal(10,2) DEFAULT '0.00',
  `create_time` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_shipping
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_ship_allocate
-- ----------------------------
DROP TABLE IF EXISTS `tbl_ship_allocate`;
CREATE TABLE `tbl_ship_allocate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider` int(20) DEFAULT '0',
  `shop` int(20) DEFAULT '0',
  `shipman` int(20) DEFAULT '0',
  `set_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_ship_allocate
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_shop_favorite
-- ----------------------------
DROP TABLE IF EXISTS `tbl_shop_favorite`;
CREATE TABLE `tbl_shop_favorite` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `shop` varchar(20) DEFAULT '' COMMENT 'shop''s userid',
  `type` tinyint(2) DEFAULT '0' COMMENT '0- activity, 1- provider',
  `favorite_id` varchar(50) DEFAULT '' COMMENT 'activity or provider id',
  `add_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'the time that shop favorited.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_shop_favorite
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_transaction
-- ----------------------------
DROP TABLE IF EXISTS `tbl_transaction`;
CREATE TABLE `tbl_transaction` (
  `id` varchar(50) NOT NULL,
  `order_id` varchar(50) DEFAULT NULL,
  `provider_id` bigint(20) DEFAULT NULL,
  `shop_id` bigint(20) DEFAULT '0',
  `money` decimal(10,2) DEFAULT '0.00',
  `pay_type` tinyint(2) DEFAULT '0' COMMENT '1: online pay, 2: after pay',
  `note` varchar(255) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_transaction
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_user
-- ----------------------------
DROP TABLE IF EXISTS `tbl_user`;
CREATE TABLE `tbl_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT '0',
  `userid` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT '',
  `role` int(10) DEFAULT '0',
  `level` tinyint(2) DEFAULT '1' COMMENT '1: system manager, 2: provider manager',
  `status` tinyint(2) DEFAULT '1' COMMENT '1: enable, 2:disable',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_user
-- ----------------------------
INSERT INTO `tbl_user` VALUES ('1', '0', 'admin', 'System Administrator', '$2y$10$.Ruf4Dprou/GhyUoHcmUJ.oLXm3r082093sRhMeklldnhyvOZG1qK', '1', '1', '1', '2017-10-08 06:16:08', '2017-10-08 15:16:05');

-- ----------------------------
-- Table structure for tbl_userinfo
-- ----------------------------
DROP TABLE IF EXISTS `tbl_userinfo`;
CREATE TABLE `tbl_userinfo` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `provider_id` int(20) DEFAULT '0',
  `userid` varchar(255) DEFAULT NULL COMMENT 'user identifier',
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `orderID` varchar(255) DEFAULT NULL COMMENT 'password',
  `type` int(10) DEFAULT '0' COMMENT 'kind of user( 2: 供货商, 3: 便利店, 4: 配送员 )',
  `shop_type` tinyint(2) DEFAULT '0',
  `address` varchar(255) DEFAULT NULL COMMENT 'user address information',
  `filter_addr` varchar(255) DEFAULT '' COMMENT 'shop''s limit range',
  `location` varchar(30) DEFAULT '' COMMENT 'shop''s location information( format : [111,22])',
  `coupon` decimal(10,2) DEFAULT '0.00',
  `integral` int(10) DEFAULT '0',
  `balance` decimal(20,2) DEFAULT '0.00' COMMENT 'my balance',
  `contact_name` varchar(20) DEFAULT NULL COMMENT 'contact name',
  `contact_phone` varchar(20) DEFAULT NULL COMMENT 'contact phone number',
  `saleman` int(10) DEFAULT '0' COMMENT 'saleman name',
  `saleman_mobile` varchar(20) DEFAULT NULL COMMENT 'saleman mobile number',
  `status` int(2) DEFAULT '0',
  `auth` int(2) DEFAULT '1' COMMENT 'user authorization status',
  `ratio` decimal(10,4) DEFAULT '0.0000',
  `reason` varchar(255) DEFAULT NULL,
  `token` varchar(30) DEFAULT '',
  `more_data` text COMMENT 'extra datas(JSON format''s data)',
  `cart_info` text COMMENT 'shopping cart information',
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_userinfo
-- ----------------------------
