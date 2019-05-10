# Host: localhost  (Version: 5.5.53)
# Date: 2019-05-10 09:12:14
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "ddsc_admin"
#

DROP TABLE IF EXISTS `ddsc_admin`;
CREATE TABLE `ddsc_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `pwd` varchar(255) DEFAULT NULL,
  `pretime` datetime DEFAULT NULL,
  `curtime` datetime DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL COMMENT '登录IP',
  `level` int(11) NOT NULL DEFAULT '0' COMMENT '管理员类型 0超级管理员 1普通管理员',
  `control` text COMMENT '控制器权限',
  `way` text COMMENT '方法',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#
# Data for table "ddsc_admin"
#

INSERT INTO `ddsc_admin` VALUES (1,'admin','8a30ec6807f71bc69d096d8e4d501ade','2019-05-10 09:08:26','2019-05-10 09:10:52','0.0.0.0',0,NULL,NULL);

#
# Structure for table "ddsc_assess"
#

DROP TABLE IF EXISTS `ddsc_assess`;
CREATE TABLE `ddsc_assess` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT NULL COMMENT '酒店id',
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `number` tinyint(3) NOT NULL DEFAULT '0' COMMENT '几颗星',
  `content` text COMMENT '内容',
  `addtime` varchar(255) DEFAULT NULL COMMENT '评论时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否审核 0否 1是',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品评价';

#
# Data for table "ddsc_assess"
#


#
# Structure for table "ddsc_basic"
#

DROP TABLE IF EXISTS `ddsc_basic`;
CREATE TABLE `ddsc_basic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quota` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '最少提现额度',
  `rate` decimal(4,2) NOT NULL DEFAULT '0.00' COMMENT '提现手续费',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '是否开启分销 0否 1是',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='分销基础设置';

#
# Data for table "ddsc_basic"
#

INSERT INTO `ddsc_basic` VALUES (1,10.00,6.00,1);

#
# Structure for table "ddsc_carte"
#

DROP TABLE IF EXISTS `ddsc_carte`;
CREATE TABLE `ddsc_carte` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `c_name` varchar(255) DEFAULT NULL COMMENT '模块名称',
  `c_modul` varchar(255) DEFAULT NULL COMMENT '控制器',
  `c_icon` varchar(255) DEFAULT NULL COMMENT '图标',
  `pid` int(11) DEFAULT NULL COMMENT '上级id',
  `c_sort` int(11) NOT NULL DEFAULT '50' COMMENT '排序',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#
# Data for table "ddsc_carte"
#

INSERT INTO `ddsc_carte` VALUES (1,'网站设置','Sys','fa-desktop',0,1),(2,'基本信息','seting','',1,1),(3,'网站优化','seo','',1,50),(4,'广告图管理','Lb','fa-picture-o',0,2),(5,'图片列表','lister','',4,50),(6,'广告位','place','',4,50),(13,'菜单管理','Carte','fa-reorder',0,3),(14,'后台模板','lister','',13,50),(16,'管理员管理','User','fa-user',0,4),(17,'管理员列表','lister','',16,50),(19,'会员管理','Member','fa-address-book-o',0,5),(20,'会员列表','lister','',19,50),(34,'日志管理','Log','fa-book',0,50),(36,'后台登录日志','index','',34,50),(39,'订单管理','Dd','fa-paper-plane',0,10),(40,'待付款订单','dai_dd','',39,50),(41,'已付款订单','fa_dd','',39,50),(69,'会议需求管理','Demand','fa-fax',0,6),(70,'会议类型','type','',69,50),(71,'会议时长','times','',69,50),(72,'参会人数','number','',69,50),(73,'会议预算','money','',69,50),(74,'会议需求','lister','',69,50),(75,'会议城市','city','',69,50),(76,'会议需求列表','Ment','fa-question-circle',0,7),(77,'资讯列表','lister','',76,50),(78,'已回复列表','index','',76,50),(79,'销售经理申请','apply','',19,50),(80,'入住酒店管理','hotel_apply','',19,50),(81,'酒店管理','Hotel','fa-hotel',0,8),(82,'城市管理','city','',81,50),(83,'场地类型','type','',81,50),(84,'会场面积','area','',81,50),(85,'容纳人数','num','',81,50),(86,'参考价格','money','',81,50),(87,'酒店列表','lister','',81,1),(88,'会议管理','Meeting','fa-cube',0,9),(89,'会议列表','lister','',88,50),(90,'支付配置','Payment','fa-paypal',0,49),(91,'微信支付','wxpay','',90,50),(92,'分销管理','Share','fa-anchor',0,11),(93,'基础设置','basic','',92,50),(94,'佣金设置','share','',92,50),(95,'提现管理','Cash','fa-fax',0,12),(96,'申请列表','index','',95,50),(97,'已通过列表','lister','',95,50),(98,'已驳回列表','reject','',95,50),(99,'评论管理','Assess','fa-question-circle',0,13),(100,'未审核评论','lister','',99,50),(101,'已审核评论','index','',99,50),(102,'营销管理','Market','fa-crop',0,50),(103,'红包','red','',102,50),(104,'大转盘','lister','',102,50),(105,'摇一摇','shake','',102,50);

#
# Structure for table "ddsc_cash"
#

DROP TABLE IF EXISTS `ddsc_cash`;
CREATE TABLE `ddsc_cash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '余额',
  `content` text COMMENT '提现账户信息',
  `time` varchar(255) DEFAULT NULL COMMENT '申请时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态 0已申请 1已通过 2 已驳回',
  `charge` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '手续费',
  `moneys` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '实际提现金额',
  `times` varchar(255) DEFAULT NULL COMMENT '操作时间',
  `types` tinyint(3) NOT NULL DEFAULT '0' COMMENT '类型 0佣金提现 1红包余额提现',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='提现';

#
# Data for table "ddsc_cash"
#

INSERT INTO `ddsc_cash` VALUES (1,1,18.80,32.77,'账号信息','1557393350',2,1.20,20.00,'1557394390',1),(2,1,18.80,12.77,'账号信息','1557393654',2,1.20,20.00,'1557394360',1);

#
# Structure for table "ddsc_demand"
#

DROP TABLE IF EXISTS `ddsc_demand`;
CREATE TABLE `ddsc_demand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '需求名称',
  `type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '类型 1会议类型 2会议时长 3参会人数 4会议预算 5会议需求 6会议城市',
  `sort` int(11) NOT NULL DEFAULT '50' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='会议内容需求列表';

#
# Data for table "ddsc_demand"
#

INSERT INTO `ddsc_demand` VALUES (1,'公司年会',1,1),(4,'一天',2,50),(6,'10-30人',3,1),(9,'3000以下',4,1),(11,'开会',5,50),(13,'北京',6,50),(14,'发布会',1,50),(15,'答谢会',1,50),(16,'培训会',1,50),(17,'一晚',2,50),(18,'半天',2,50),(19,'两天',2,50),(20,'30-60人',3,50),(21,'60-90人',3,50),(22,'3-5千',4,2),(23,'5-8千',4,3),(24,'用餐',5,50),(25,'住宿',5,50),(26,'上海',6,50),(27,'郑州',6,50);

#
# Structure for table "ddsc_hotel"
#

DROP TABLE IF EXISTS `ddsc_hotel`;
CREATE TABLE `ddsc_hotel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '酒店名称',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '预定价格',
  `addr` varchar(255) DEFAULT NULL COMMENT '酒店地址',
  `image` varchar(255) DEFAULT NULL COMMENT '图片',
  `up` tinyint(3) NOT NULL DEFAULT '1' COMMENT '上下架 0否 1是',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '热门酒店推荐 0否 1是',
  `statu` tinyint(3) NOT NULL DEFAULT '0' COMMENT '精选场地推荐 0否 1是',
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '城市id',
  `qid` int(11) NOT NULL DEFAULT '0' COMMENT '区域id',
  `tid` int(11) NOT NULL DEFAULT '0' COMMENT '场地类型id',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '会场面积id',
  `nid` int(11) NOT NULL DEFAULT '0' COMMENT '容纳人数id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '参考价格id',
  `type` varchar(255) DEFAULT NULL COMMENT '酒店类型',
  `ad` varchar(255) DEFAULT NULL COMMENT '酒店副标题',
  `score` varchar(255) DEFAULT NULL COMMENT '评分',
  `hall` varchar(255) DEFAULT NULL COMMENT '会议厅数量',
  `area` varchar(255) DEFAULT NULL COMMENT '最大面积',
  `number` varchar(255) DEFAULT NULL COMMENT '最多容纳人数',
  `content` longtext COMMENT '酒店详情',
  `activity` longtext COMMENT '举办的活动',
  `is_delete` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否删除 0否 -1是',
  `sort` varchar(255) NOT NULL DEFAULT '50' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='酒店列表';

#
# Data for table "ddsc_hotel"
#

INSERT INTO `ddsc_hotel` VALUES (1,'郑州红山楂树酒店',10.00,'河南郑州市金水区恒华大厦','/uploads/20190412/ccaa658a76332603e956a0b9da245ecb.png',1,0,1,1,3,4,10,13,17,'五星级酒店','位置好，高铁站旁','5','200','1200','1000','<p><span style=\"color: rgb(14, 23, 38); font-family: &quot;PingFang SC&quot;, -apple-system, BlinkMacSystemFont, Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, &quot;Hiragino Sans GB&quot;, &quot;Source Han Sans&quot;, &quot;Noto Sans CJK Sc&quot;, &quot;Microsoft YaHei&quot;, &quot;Microsoft Jhenghei&quot;, sans-serif; font-size: 14px; background-color: rgb(244, 247, 253);\">酒店详情: 喜来登(Sheraton)是世界500强的喜达屋饭店及度\r\n 假村管理集团旗下的品牌。喜来登1963年来在全\r\n 世界72个国家拥有400多家酒店的经营权。喜来登\r\n 在选址上比较严格，主要选择有吸引力的大都市和\r\n 度假村。在膳宿业与赌场......</span></p>','<p><span style=\"color: rgb(14, 23, 38); font-family: &quot;PingFang SC&quot;, -apple-system, BlinkMacSystemFont, Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, &quot;Hiragino Sans GB&quot;, &quot;Source Han Sans&quot;, &quot;Noto Sans CJK Sc&quot;, &quot;Microsoft YaHei&quot;, &quot;Microsoft Jhenghei&quot;, sans-serif; font-size: 14px; background-color: rgb(244, 247, 253);\">1.北京博览会会议</span></p><p><span style=\"color: rgb(14, 23, 38); font-family: &quot;PingFang SC&quot;, -apple-system, BlinkMacSystemFont, Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, &quot;Hiragino Sans GB&quot;, &quot;Source Han Sans&quot;, &quot;Noto Sans CJK Sc&quot;, &quot;Microsoft YaHei&quot;, &quot;Microsoft Jhenghei&quot;, sans-serif; font-size: 14px; background-color: rgb(244, 247, 253);\"><span style=\"color: rgb(14, 23, 38); font-family: &quot;PingFang SC&quot;, -apple-system, BlinkMacSystemFont, Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, &quot;Hiragino Sans GB&quot;, &quot;Source Han Sans&quot;, &quot;Noto Sans CJK Sc&quot;, &quot;Microsoft YaHei&quot;, &quot;Microsoft Jhenghei&quot;, sans-serif; font-size: 14px; background-color: rgb(244, 247, 253);\">1.北京博览会会议</span></span></p><p><span style=\"color: rgb(14, 23, 38); font-family: &quot;PingFang SC&quot;, -apple-system, BlinkMacSystemFont, Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, &quot;Hiragino Sans GB&quot;, &quot;Source Han Sans&quot;, &quot;Noto Sans CJK Sc&quot;, &quot;Microsoft YaHei&quot;, &quot;Microsoft Jhenghei&quot;, sans-serif; font-size: 14px; background-color: rgb(244, 247, 253);\"><span style=\"color: rgb(14, 23, 38); font-family: &quot;PingFang SC&quot;, -apple-system, BlinkMacSystemFont, Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, &quot;Hiragino Sans GB&quot;, &quot;Source Han Sans&quot;, &quot;Noto Sans CJK Sc&quot;, &quot;Microsoft YaHei&quot;, &quot;Microsoft Jhenghei&quot;, sans-serif; font-size: 14px; background-color: rgb(244, 247, 253);\"><span style=\"color: rgb(14, 23, 38); font-family: &quot;PingFang SC&quot;, -apple-system, BlinkMacSystemFont, Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, &quot;Hiragino Sans GB&quot;, &quot;Source Han Sans&quot;, &quot;Noto Sans CJK Sc&quot;, &quot;Microsoft YaHei&quot;, &quot;Microsoft Jhenghei&quot;, sans-serif; font-size: 14px; background-color: rgb(244, 247, 253);\">1.北京博览会会议</span></span></span></p>',0,'1'),(2,'郑州格瑞斯酒店',1000.00,'河南郑州二七区','/uploads/20190411/ca0702216e5ec8fcf39036167a37326a.png',1,1,0,1,5,6,12,15,19,'度假村','环境好,性价比高','4.5','100','1100','100','<p><img src=\"/ueditor/php/upload/image/20190412/1555032809.png\" title=\"1555032809.png\" alt=\"72ee8ddadd01407d98f99b9a8bc15421_720_480_s@2x.png\"/></p>','<p>举办活动</p>',0,'2'),(3,'郑州泰富酒店',1000.00,'河南郑州中原区','/uploads/20190411/aefeaa0d0d98e243b857556b4c054272.png',1,0,1,1,4,5,11,14,18,'四星级大酒店','环境好,性价比高','4','100','1000','500','<p>酒店详情酒店详情酒店详情酒店详情酒店详情酒店详情酒店详情酒店详情酒店详情酒店详情酒店详情酒店详情酒店详情酒店详情酒店详情酒店详情酒店详情酒店详情酒店详情酒店详情酒店详情酒店详情</p>','<p>举办活动举办活动举办活动举办活动举办活动举办活动举办活动举办活动举办活动举办活动举办活动举办活动举办活动举办活动举办活动举办活动</p>',0,'50');

#
# Structure for table "ddsc_hotel_city"
#

DROP TABLE IF EXISTS `ddsc_hotel_city`;
CREATE TABLE `ddsc_hotel_city` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `c_name` varchar(255) DEFAULT NULL COMMENT '模块名称',
  `pid` int(11) DEFAULT NULL COMMENT '上级id',
  `c_sort` int(11) NOT NULL DEFAULT '50' COMMENT '排序',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='城市列表';

#
# Data for table "ddsc_hotel_city"
#

INSERT INTO `ddsc_hotel_city` VALUES (1,'郑州市',0,1),(3,'金水区',1,2),(4,'中原区',1,4),(5,'二七区',1,3),(6,'高新区',1,50),(7,'漯河市',0,50),(8,'源汇区',7,50);

#
# Structure for table "ddsc_hotel_img"
#

DROP TABLE IF EXISTS `ddsc_hotel_img`;
CREATE TABLE `ddsc_hotel_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) DEFAULT NULL COMMENT '图片',
  `hid` int(11) DEFAULT NULL COMMENT '酒店id',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品多图';

#
# Data for table "ddsc_hotel_img"
#

INSERT INTO `ddsc_hotel_img` VALUES (1,'/uploads/20190411/ed34c628943797d7536407115fa9a3c8.png',2,NULL,1),(4,'/uploads/20190411/decaebb3a65111de2762e3269d0abb64.png',1,NULL,1),(5,'/uploads/20190412/00622f640ea7cf64d329929d4392446c.png',1,NULL,1),(6,'/uploads/20190412/4a98ee976d40806fcdd46e7800668846.png',3,NULL,1),(7,'/uploads/20190412/ab2a798644ee281b452c04cee1cffe3e.png',3,NULL,1),(8,'/uploads/20190412/556ff44eb57ccd57dec149ffd7e4fe5b.png',2,NULL,1);

#
# Structure for table "ddsc_hotel_other"
#

DROP TABLE IF EXISTS `ddsc_hotel_other`;
CREATE TABLE `ddsc_hotel_other` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `oname` varchar(255) DEFAULT NULL COMMENT '名称',
  `osort` int(11) NOT NULL DEFAULT '50' COMMENT '排序',
  `otype` tinyint(3) NOT NULL DEFAULT '0' COMMENT '类型 1场地类型 2会场面积 3容纳人数 4参考价格',
  PRIMARY KEY (`oid`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='酒店搜索条件';

#
# Data for table "ddsc_hotel_other"
#

INSERT INTO `ddsc_hotel_other` VALUES (4,'五星/顶级',3,1),(5,'经济型',1,1),(6,'度假村',2,1),(10,'500',1,2),(11,'1000',2,2),(12,'2000',3,2),(13,'10-50人',1,3),(14,'50-100人',2,3),(15,'100-150人',3,3),(17,'2000元以下',1,4),(18,'2000-5000元',2,4),(19,'5000-10000元',3,4);

#
# Structure for table "ddsc_hotel_room"
#

DROP TABLE IF EXISTS `ddsc_hotel_room`;
CREATE TABLE `ddsc_hotel_room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_name` varchar(255) DEFAULT NULL COMMENT '客房名称',
  `room_area` varchar(255) DEFAULT NULL COMMENT '面积',
  `room_num` varchar(255) DEFAULT NULL COMMENT '容纳人数',
  `room_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '预定价格',
  `room_sprices` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '市场参考价全天',
  `room_sprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '市场参考价半天',
  `room_xprices` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '会管家全天价',
  `room_xprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '会管家半天价',
  `room_content` longtext COMMENT '客房详情',
  `room_image` varchar(255) DEFAULT NULL COMMENT '客房图片',
  `room_up` tinyint(3) NOT NULL DEFAULT '1' COMMENT '上架 0否 1是',
  `room_is_delete` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否删除 0否 -1是',
  `room_type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '客房类型 1会议室 2客房',
  `room_sort` int(11) NOT NULL DEFAULT '50' COMMENT '排序',
  `hid` int(11) NOT NULL DEFAULT '0' COMMENT '酒店id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='酒店客房';

#
# Data for table "ddsc_hotel_room"
#

INSERT INTO `ddsc_hotel_room` VALUES (4,'秀场厅123','450','1000',1000.00,5000.00,3000.00,3000.00,2000.00,'<p><img src=\"/ueditor/php/upload/image/20190412/1555033277.png\" title=\"1555033277.png\" alt=\"image.png\"/></p>','/uploads/20190412/8f5bcb3b7d4b1f9ea58c019027044afe.png',1,0,1,50,1),(5,'秀场厅','450','1000',1000.00,5000.00,3000.00,3000.00,2000.00,'<p><img src=\"/ueditor/php/upload/image/20190412/1555033277.png\" title=\"1555033277.png\" alt=\"image.png\"/></p>','/uploads/20190412/8f5bcb3b7d4b1f9ea58c019027044afe.png',1,0,2,50,1),(6,'秀场厅','450','1000',1000.00,5000.00,3000.00,3000.00,2000.00,'<p><img src=\"/ueditor/php/upload/image/20190412/1555033277.png\" title=\"1555033277.png\" alt=\"image.png\"/></p>','/uploads/20190412/8f5bcb3b7d4b1f9ea58c019027044afe.png',1,0,1,50,2),(7,'秀场厅','450','1000',1000.00,5000.00,3000.00,3000.00,2000.00,'<p><img src=\"/ueditor/php/upload/image/20190412/1555033277.png\" title=\"1555033277.png\" alt=\"image.png\"/></p>','/uploads/20190412/8f5bcb3b7d4b1f9ea58c019027044afe.png',1,0,2,50,2),(8,'秀场厅','450','1000',1000.00,5000.00,3000.00,3000.00,2000.00,'<p><img src=\"/ueditor/php/upload/image/20190412/1555033277.png\" title=\"1555033277.png\" alt=\"image.png\"/></p>','/uploads/20190412/8f5bcb3b7d4b1f9ea58c019027044afe.png',1,0,1,50,3),(9,'秀场厅','450','1000',1000.00,5000.00,3000.00,3000.00,2000.00,'<p><img src=\"/ueditor/php/upload/image/20190412/1555033277.png\" title=\"1555033277.png\" alt=\"image.png\"/></p>','/uploads/20190412/8f5bcb3b7d4b1f9ea58c019027044afe.png',1,0,2,50,3);

#
# Structure for table "ddsc_lb"
#

DROP TABLE IF EXISTS `ddsc_lb`;
CREATE TABLE `ddsc_lb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) DEFAULT NULL COMMENT '父类id',
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态0关闭 1开启',
  `url` varchar(255) DEFAULT NULL,
  `desc` text COMMENT '简介',
  `image` varchar(255) DEFAULT NULL COMMENT '图片',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='广告列表';

#
# Data for table "ddsc_lb"
#

INSERT INTO `ddsc_lb` VALUES (1,1,'banner',50,1,'','','/uploads/20190320/f1a20640379c12a3553f5f1b26fb68ce.png'),(2,1,'banner',50,1,'','','/uploads/20190320/1530377373b754e0543e0ad4a1e285e3.png'),(3,2,'需求广告',50,1,'','<p>专业团队根据您的需求量身打造免费的的方案！我们将在15分钟内联系您希望我们合作愉快！</p>',NULL),(4,3,'价格全网最低',50,1,'','','/uploads/20190320/49488505224381dd7f4b44ee0e605f82.png'),(5,3,'场地真实有限',50,1,'','','/uploads/20190320/a507c275522e548087c45e8851b86105.png'),(6,3,'档期绝对保障',50,1,'','','/uploads/20190320/a98abf8898b43261bad3914bfe3d1993.png'),(7,4,'五星级酒店',50,1,'','',NULL),(8,4,'四星级酒店',50,1,'','',NULL),(9,4,'三星级酒店',50,1,'','',NULL),(10,4,'度假村',50,1,'','',NULL),(11,4,'餐厅',50,1,'','',NULL),(12,4,'培训中心',50,1,'','',NULL),(13,4,'会所',50,1,'','',NULL),(14,4,'其他',50,1,'','',NULL),(15,5,'申请加入广告',50,1,'','<p>申请成功后，我们将以最快的速度给您回应；想要查看进度可以拨打电话0371-123456789</p>',NULL),(16,6,'会管家的优势是什么?',50,1,'','<p>1.采购优势</p><p>（1）拥有完备的供应链体系，满足企业用户的多种会议类型需求。会小二与希尔顿、万豪、香格里拉、洲际、万达、恒大、碧桂园等国内外知名酒店集团有官方合作，并与中国本土场地、会议活动资源进行深度整合。50000多名认证酒店销售提供实时档期报价和线下服务。</p><p>（2）资源覆盖全国一、二、三线城市，极大提高异地办会采购效率。北京、上海、广州、深圳、武汉、杭州、郑州、天津、成都、西安、南京、重庆、苏州、青岛、厦门、三亚、沈阳、长沙、济南、昆明、佛山、哈尔滨、宁波等城市均已开通服务。提交一次需求，即可获得多地小二的共同服务，高效解决企业异地、多地办会需求。</p>',NULL),(17,6,'会管家服务流程',50,1,'','<p>会管家服务流程会管家服务流程会管家服务流程会管家服务流程会管家服务流程会管家服务流程</p>',NULL),(18,7,'下单广告',50,1,'','<p>1、10分钟快速回应，免费服务<br/></p><p>2.提交需求后，30分钟内您将获得精准报价</p><p>3.您也可以直接拨打：</p>',NULL),(19,8,'提现说明',50,1,'','<p>金额低于10元不可提现</p><p>提现说明提现说明提现说明提现说明</p>',NULL);

#
# Structure for table "ddsc_lb_place"
#

DROP TABLE IF EXISTS `ddsc_lb_place`;
CREATE TABLE `ddsc_lb_place` (
  `pl_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '轮播id',
  `pl_name` varchar(255) DEFAULT NULL COMMENT '位置名称',
  PRIMARY KEY (`pl_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='广告位';

#
# Data for table "ddsc_lb_place"
#

INSERT INTO `ddsc_lb_place` VALUES (1,'轮播图'),(2,'需求广告'),(3,'服务保障'),(4,'申请加入类型'),(5,'申请加入广告'),(6,'常见问题'),(7,'下单广告'),(8,'提现说明');

#
# Structure for table "ddsc_meeting"
#

DROP TABLE IF EXISTS `ddsc_meeting`;
CREATE TABLE `ddsc_meeting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '会议名称',
  `company` varchar(255) DEFAULT NULL COMMENT '公司名称',
  `hotel` varchar(255) DEFAULT NULL COMMENT '酒店名称',
  `image` varchar(255) DEFAULT NULL COMMENT '图片',
  `up` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态 0关闭 1开启',
  `sort` int(11) NOT NULL DEFAULT '50' COMMENT '排序',
  `is_delete` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否删除 0否 -1是',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='会议列表';

#
# Data for table "ddsc_meeting"
#

INSERT INTO `ddsc_meeting` VALUES (3,'培训会','培训会公司名称','郑州恒大五星级酒店','/uploads/20190412/5a749229b2551947eb244b602cf7196f.png',1,50,0),(4,'发布会','发布会公司名称','郑州富态酒店','/uploads/20190412/39c188ca1ee3005cbeea7f98b2262ea3.png',1,50,0),(5,'培训会','培训会公司名称','郑州恒大五星级酒店','/uploads/20190412/5a749229b2551947eb244b602cf7196f.png',1,50,0),(6,'发布会','发布会公司名称','郑州富态酒店','/uploads/20190412/39c188ca1ee3005cbeea7f98b2262ea3.png',1,50,0),(7,'培训会','培训会公司名称','郑州恒大五星级酒店','/uploads/20190412/5a749229b2551947eb244b602cf7196f.png',1,50,0),(8,'发布会','发布会公司名称','郑州富态酒店','/uploads/20190412/39c188ca1ee3005cbeea7f98b2262ea3.png',1,50,0);

#
# Structure for table "ddsc_money_log"
#

DROP TABLE IF EXISTS `ddsc_money_log`;
CREATE TABLE `ddsc_money_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '佣金',
  `type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '佣金类型 0减少 1增加',
  `oper` varchar(255) DEFAULT NULL COMMENT '操作人',
  `time` varchar(255) DEFAULT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='佣金日志';

#
# Data for table "ddsc_money_log"
#


#
# Structure for table "ddsc_need"
#

DROP TABLE IF EXISTS `ddsc_need`;
CREATE TABLE `ddsc_need` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL COMMENT '会议类型',
  `times` varchar(255) DEFAULT NULL COMMENT '会议时长',
  `dates` varchar(255) DEFAULT NULL COMMENT '会议时间',
  `number` varchar(255) DEFAULT NULL COMMENT '参会人数',
  `money` varchar(255) DEFAULT NULL COMMENT '会议预算',
  `ment` varchar(255) DEFAULT NULL COMMENT '会议需求',
  `city` varchar(255) DEFAULT NULL COMMENT '会议城市',
  `phone` varchar(255) DEFAULT NULL COMMENT '联系电话',
  `content` varchar(255) DEFAULT NULL COMMENT '会议备注',
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `time` varchar(255) DEFAULT NULL COMMENT '提交时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='提交的需求列表';

#
# Data for table "ddsc_need"
#

INSERT INTO `ddsc_need` VALUES (3,'年会','一天','2019-03-23','30-60人','3-5千','开会','郑州','15939590207','会议备注',1,'1553151625'),(4,'年会','一天','2019-03-23','30-60人','3-5千','开会','郑州','15939590207','会议备注',0,'1553151625'),(5,'年会','一天','2019-03-23','30-60人','3-5千','开会','郑州','15939590207','会议备注',0,'1553151625'),(6,'年会','一天','2019-03-23','30-60人','3-5千','开会','郑州','15939590207','会议备注',0,'1553151625'),(7,'年会','一天','2019-03-23','30-60人','3-5千','开会','郑州','15939590207','会议备注',0,'1553151625'),(8,'年会','一天','2019-03-23','30-60人','3-5千','开会','郑州','15939590207','会议备注',0,'1553151625'),(9,'年会','一天','2019-03-23','30-60人','3-5千','开会','郑州','15939590207','会议备注',1,'1553151625'),(10,'年会','一天','2019-03-23','30-60人','3-5千','开会','郑州','15939590207','会议备注',0,'1553151625'),(11,'年会','一天','2019-03-23','30-60人','3-5千','开会','郑州','15939590207','会议备注',0,'1553151625'),(12,'年会','一天','2019-03-23','30-60人','3-5千','开会','郑州','15939590207','会议备注',0,'1553151625'),(13,'年会','一天','2019-03-23','30-60人','3-5千','开会','郑州','15939590207','会议备注',0,'1553151625'),(14,'年会','一天','2019-03-23','30-60人','3-5千','开会','郑州','15939590207','会议备注',0,'1553151625');

#
# Structure for table "ddsc_order"
#

DROP TABLE IF EXISTS `ddsc_order`;
CREATE TABLE `ddsc_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hid` int(11) NOT NULL DEFAULT '0' COMMENT '酒店id',
  `rid` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `hotel` varchar(255) DEFAULT NULL COMMENT '酒店名称',
  `room` varchar(255) DEFAULT NULL COMMENT '房间名称',
  `room_type` varchar(255) DEFAULT NULL COMMENT '房间类型',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `time` varchar(255) DEFAULT NULL COMMENT '下单时间',
  `code` varchar(255) DEFAULT NULL COMMENT '订单编号',
  `number` varchar(255) DEFAULT NULL COMMENT '参会人数',
  `ment` varchar(255) DEFAULT NULL COMMENT '会议需求',
  `phone` varchar(255) DEFAULT NULL COMMENT '联系电话',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '订单状态 0待支付 1已支付',
  `dates` varchar(255) DEFAULT NULL COMMENT '会议时间',
  `image` varchar(255) DEFAULT NULL COMMENT '酒店图片',
  `fu_time` varchar(255) DEFAULT NULL COMMENT '付款时间',
  `is_delete` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否删除 0否 -1是',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单表';

#
# Data for table "ddsc_order"
#


#
# Structure for table "ddsc_payment"
#

DROP TABLE IF EXISTS `ddsc_payment`;
CREATE TABLE `ddsc_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appid` varchar(255) DEFAULT NULL,
  `mchid` varchar(255) DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL,
  `appsecret` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='支付配置';

#
# Data for table "ddsc_payment"
#

INSERT INTO `ddsc_payment` VALUES (1,'wx0bf6a1d285ef4fc8','1526037961','qwertyuiopasdfghjklzxcvbnm789456','32cded6845fe0735fea052ab0e415d1e'),(2,NULL,NULL,NULL,NULL);

#
# Structure for table "ddsc_prize"
#

DROP TABLE IF EXISTS `ddsc_prize`;
CREATE TABLE `ddsc_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '奖品名称',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `proba` decimal(10,2) NOT NULL DEFAULT '0.00',
  `image` varchar(255) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT '50' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='幸运大转盘';

#
# Data for table "ddsc_prize"
#

INSERT INTO `ddsc_prize` VALUES (1,'奖品1',10.00,0.01,'/uploads/20190509/c9b9f459ed26acbfa774d4b6cea3904f.png',1),(2,'奖品2',1.00,0.10,'/uploads/20190509/25decc2ad77ea7a4afbcaa9173d89193.png',2),(3,'奖品3',2.00,0.30,'/uploads/20190509/b378eb221aa931ab0bb658b5688065a4.png',50),(4,'奖品4',6.00,0.60,'/uploads/20190509/79f59cbf565edcbf14b268b719b271c9.png',50);

#
# Structure for table "ddsc_red"
#

DROP TABLE IF EXISTS `ddsc_red`;
CREATE TABLE `ddsc_red` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `open` tinyint(3) NOT NULL DEFAULT '0' COMMENT '开启状态 0否 1是',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '最大红包金额',
  `number` int(11) NOT NULL DEFAULT '1',
  `content` text COMMENT '红包规则',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='红包设置';

#
# Data for table "ddsc_red"
#

INSERT INTO `ddsc_red` VALUES (1,1,1.00,6,'<p>红包规则红包规则红包规则红包规则红包规则红包规则红包规则红包规则红包规则红包规则红包规则红包规则红包规则红包规则红包规则</p>'),(2,1,1.00,10,NULL),(3,1,0.00,10,NULL);

#
# Structure for table "ddsc_red_log"
#

DROP TABLE IF EXISTS `ddsc_red_log`;
CREATE TABLE `ddsc_red_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '红包金额',
  `time` varchar(255) DEFAULT NULL COMMENT '获取时间',
  `type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '红包类型 0提现 1红包 2摇一摇 3幸运大转盘',
  `content` text COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='红包记录';

#
# Data for table "ddsc_red_log"
#

INSERT INTO `ddsc_red_log` VALUES (1,1,0.31,'1557385734',2,'摇一摇'),(2,1,0.56,'1557385735',2,'摇一摇'),(3,1,0.57,'1557385743',1,'领取红包'),(4,1,0.43,'1557385745',1,'领取红包'),(5,1,6.00,'1557391683',3,'摇一摇'),(6,1,6.00,'1557391689',3,'摇一摇'),(7,1,2.00,'1557391726',3,'大转盘'),(8,1,10.00,'1557391753',3,'大转盘'),(9,1,2.00,'1557391755',3,'大转盘'),(10,1,6.00,'1557391758',3,'大转盘'),(11,1,1.00,'1557391885',3,'大转盘'),(12,1,20.00,'1557393350',0,'提现减少红包余额'),(13,1,20.00,'1557393654',0,'提现减少红包余额'),(14,1,20.00,'1557394360',1,'红包提现驳回增加'),(15,1,20.00,'1557394390',1,'红包提现驳回增加');

#
# Structure for table "ddsc_room_img"
#

DROP TABLE IF EXISTS `ddsc_room_img`;
CREATE TABLE `ddsc_room_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) DEFAULT NULL COMMENT '图片',
  `rid` int(11) DEFAULT NULL COMMENT '客房id',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品多图';

#
# Data for table "ddsc_room_img"
#

INSERT INTO `ddsc_room_img` VALUES (1,'/uploads/20190411/6bf6ee0702c998caab9fc34886826b4d.png',1,NULL,1),(3,'/uploads/20190412/014807e8ec621556567048d2a6212215.png',5,NULL,1),(4,'/uploads/20190412/cf5de37228cbbdf0151e4eadffc92295.png',5,NULL,1);

#
# Structure for table "ddsc_seo"
#

DROP TABLE IF EXISTS `ddsc_seo`;
CREATE TABLE `ddsc_seo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '首页标题',
  `keywords` varchar(255) DEFAULT NULL COMMENT 'seo关键词',
  `description` text COMMENT 'seo描述',
  `copy` text COMMENT '版权信息',
  `code` text COMMENT '统计代码',
  `support` varchar(255) DEFAULT NULL COMMENT '技术支持',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='网站优化';

#
# Data for table "ddsc_seo"
#

INSERT INTO `ddsc_seo` VALUES (1,NULL,NULL,NULL,NULL,NULL,NULL);

#
# Structure for table "ddsc_share"
#

DROP TABLE IF EXISTS `ddsc_share`;
CREATE TABLE `ddsc_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level_1` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '普通会员比例',
  `level_2` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '销售经理比例',
  `level_3` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '入住酒店比例',
  `level_12` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '二级会员佣金',
  `level_22` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '二级销售经理佣金',
  `level_32` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '二级入住酒店佣金',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='佣金比例表';

#
# Data for table "ddsc_share"
#

INSERT INTO `ddsc_share` VALUES (1,5.00,10.00,15.00,3.00,8.00,13.00);

#
# Structure for table "ddsc_sms_code"
#

DROP TABLE IF EXISTS `ddsc_sms_code`;
CREATE TABLE `ddsc_sms_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone` varchar(255) DEFAULT NULL COMMENT '手机号码',
  `code` varchar(255) DEFAULT NULL COMMENT '验证码',
  `time` varchar(255) DEFAULT NULL COMMENT '发送时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='手机验证码';

#
# Data for table "ddsc_sms_code"
#

INSERT INTO `ddsc_sms_code` VALUES (7,'15939590207','126836','1553497649');

#
# Structure for table "ddsc_sys"
#

DROP TABLE IF EXISTS `ddsc_sys`;
CREATE TABLE `ddsc_sys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '公司名称',
  `username` varchar(255) DEFAULT NULL COMMENT '负责人',
  `url` varchar(255) DEFAULT NULL COMMENT '网站域名',
  `qq` char(20) DEFAULT NULL COMMENT '客服QQ',
  `icp` varchar(255) DEFAULT NULL COMMENT 'icp备案号',
  `email` varchar(255) DEFAULT NULL COMMENT '邮箱',
  `tel` varchar(255) DEFAULT NULL COMMENT '固定电话',
  `phone` char(11) DEFAULT NULL COMMENT '手机号码',
  `longs` varchar(255) DEFAULT NULL COMMENT '经度',
  `lats` varchar(255) DEFAULT NULL COMMENT '纬度',
  `addr` varchar(255) DEFAULT NULL COMMENT '公司地址',
  `content` text COMMENT '公司简介',
  `pclogo` varchar(255) DEFAULT NULL COMMENT '电脑端logo',
  `waplogo` varchar(255) DEFAULT NULL COMMENT '手机端logo',
  `qrcode` varchar(255) DEFAULT NULL COMMENT '微信二维码',
  `wx` varchar(255) DEFAULT NULL COMMENT '微信公众号',
  `fax` varchar(255) DEFAULT NULL COMMENT '公司传真',
  `telphone` varchar(255) DEFAULT NULL COMMENT '400电话',
  `follow` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='网站基本信息';

#
# Data for table "ddsc_sys"
#

INSERT INTO `ddsc_sys` VALUES (1,'会管家','','','','','','','','','','','',NULL,NULL,NULL,NULL,'','',0);

#
# Structure for table "ddsc_sys_log"
#

DROP TABLE IF EXISTS `ddsc_sys_log`;
CREATE TABLE `ddsc_sys_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL COMMENT '类型',
  `time` datetime DEFAULT NULL COMMENT '操作时间',
  `admin` varchar(255) DEFAULT NULL COMMENT '操作账号',
  `ip` varchar(255) DEFAULT NULL COMMENT 'IP地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统日志';

#
# Data for table "ddsc_sys_log"
#

INSERT INTO `ddsc_sys_log` VALUES (1,'后台登录','2019-03-20 14:16:58','admin','0.0.0.0'),(2,'后台登录','2019-03-21 09:09:59','admin','0.0.0.0'),(3,'后台登录','2019-03-22 08:59:20','admin','0.0.0.0'),(4,'后台登录','2019-03-22 16:28:36','duoduokeji','127.0.0.1'),(5,'后台登录','2019-03-25 08:57:50','admin','0.0.0.0'),(6,'后台登录','2019-04-10 14:17:59','admin','0.0.0.0'),(7,'后台登录','2019-04-11 09:00:18','admin','0.0.0.0'),(8,'后台登录','2019-04-12 09:12:45','admin','0.0.0.0'),(9,'后台登录','2019-04-13 14:55:58','admin','0.0.0.0'),(10,'后台登录','2019-04-14 08:38:56','admin','0.0.0.0'),(11,'后台登录','2019-04-15 08:56:36','admin','0.0.0.0'),(12,'后台登录','2019-04-16 09:40:47','admin','0.0.0.0'),(13,'后台登录','2019-05-05 09:37:09','admin','0.0.0.0'),(14,'后台登录','2019-05-10 09:08:26','admin','0.0.0.0'),(15,'后台登录','2019-05-10 09:10:52','admin','0.0.0.0');

#
# Structure for table "ddsc_user"
#

DROP TABLE IF EXISTS `ddsc_user`;
CREATE TABLE `ddsc_user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '昵称',
  `time` varchar(255) DEFAULT NULL COMMENT '注册时间',
  `image` text COMMENT '头像',
  `openid` varchar(255) DEFAULT NULL COMMENT '用户的openID',
  `card` varchar(255) NOT NULL DEFAULT '' COMMENT '二维码',
  `fid` int(11) NOT NULL DEFAULT '0' COMMENT '上级id',
  `phone` varchar(20) NOT NULL DEFAULT '',
  `pwd` varchar(255) DEFAULT NULL COMMENT '密码',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '会员状态 0已授权 1已注册',
  `level` tinyint(3) NOT NULL DEFAULT '1' COMMENT '会员等级 1普通会员 2销售经理 3酒店',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '佣金',
  `is_delete` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `already_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '已提现佣金',
  `red_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '红包余额',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户列表';

#
# Data for table "ddsc_user"
#

INSERT INTO `ddsc_user` VALUES (1,'echo',NULL,NULL,NULL,'',0,'15939590207','123456',1,1,20.00,0,18.80,52.77);

#
# Structure for table "ddsc_user_apply"
#

DROP TABLE IF EXISTS `ddsc_user_apply`;
CREATE TABLE `ddsc_user_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `u_id` int(11) DEFAULT NULL COMMENT '用户id',
  `u_phone` varchar(255) DEFAULT NULL COMMENT '用户手机号码',
  `u_level` int(11) NOT NULL DEFAULT '0' COMMENT '申请等级 2销售经理 3酒店',
  `u_status` tinyint(3) NOT NULL DEFAULT '0',
  `u_time` varchar(255) DEFAULT NULL COMMENT '申请时间',
  `type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '申请类型 0销售经理 1酒店入住',
  `username` varchar(255) DEFAULT NULL COMMENT '法人姓名',
  `idcode` char(18) DEFAULT NULL COMMENT '身份证号码',
  `name` varchar(255) DEFAULT NULL COMMENT '酒店名称',
  `addr` varchar(255) DEFAULT NULL COMMENT '详细地址',
  `genre` varchar(255) DEFAULT NULL COMMENT '酒店类型',
  `rebut` text COMMENT '驳回原因',
  `rebut_look` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否查看驳回原因 0否 1是',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='入住申请表';

#
# Data for table "ddsc_user_apply"
#

