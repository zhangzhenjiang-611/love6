/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.5.54-log : Database - auto_device
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`auto_device` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `auto_device`;

/*Table structure for table `devstatus` */

DROP TABLE IF EXISTS `devstatus`;

CREATE TABLE `devstatus` (
  `DevName` varchar(20) NOT NULL COMMENT '自助机编号',
  `Paper_Near_End` varchar(20) DEFAULT NULL COMMENT '纸快没了',
  `Ticket_Out` varchar(20) DEFAULT NULL COMMENT '出纸口有纸',
  `Paper_Jam` varchar(20) DEFAULT NULL COMMENT '卡纸',
  `Cover_Open` varchar(20) DEFAULT NULL,
  `UpdateTime` varchar(20) DEFAULT NULL,
  `Paper_End` varchar(20) DEFAULT NULL COMMENT '没纸了',
  `position` varchar(100) DEFAULT NULL,
  `paper_number` int(11) NOT NULL DEFAULT '0' COMMENT '卡纸次数',
  `short_number` int(11) NOT NULL DEFAULT '0' COMMENT '缺纸次数',
  `condition` varchar(20) NOT NULL DEFAULT '0000000000' COMMENT '开关机状态',
  PRIMARY KEY (`DevName`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `devstatus` */

insert  into `devstatus`(`DevName`,`Paper_Near_End`,`Ticket_Out`,`Paper_Jam`,`Cover_Open`,`UpdateTime`,`Paper_End`,`position`,`paper_number`,`short_number`,`condition`) values ('zzj006','纸量充足','出纸口无纸','打印机没卡纸','胶辊关闭','2018/7/23 15:48:42','有纸','门诊一楼右1',0,0,'1521511555'),('zzj007','纸量充足','出纸口无纸','打印机没卡纸','胶辊关闭','2018/7/23 15:45:47','有纸','门诊一楼右2',0,0,'0000000000'),('zzj008','纸量充足','出纸口无纸','打印机没卡纸','胶辊关闭','2018/7/23 10:30:26','有纸','门诊一楼右3',0,0,'0000000000'),('zzj009','纸量充足','出纸口无纸','打印机没卡纸','胶辊关闭','2018/7/23 15:47:21','有纸','门诊一楼右4',0,0,'0000000000'),('zzj010','纸量充足','出纸口无纸','打印机没卡纸','胶辊关闭','2018/7/19 12:58:21','有纸','门诊一楼右5',0,0,'0000000000');

/*Table structure for table `group` */

DROP TABLE IF EXISTS `group`;

CREATE TABLE `group` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `name` varchar(50) NOT NULL COMMENT '名称',
  `module_ids` varchar(500) DEFAULT NULL COMMENT '授权的模块ID,英文逗号分隔',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色表';

/*Data for the table `group` */

/*Table structure for table `module` */

DROP TABLE IF EXISTS `module`;

CREATE TABLE `module` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `url` varchar(200) NOT NULL COMMENT '例：Index/index',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级id',
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '类型(1为链接、2为按钮)',
  `sort` int(11) NOT NULL COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：为1正常，为0禁用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=164 DEFAULT CHARSET=utf8 COMMENT='规则表';

/*Data for the table `module` */

insert  into `module`(`id`,`name`,`url`,`pid`,`type`,`sort`,`status`) values (1,'基本设置','',0,1,0,1),(2,'模块管理','Auth/moduleIndex',1,1,0,1),(3,'角色管理','Auth/roleIndex',1,1,0,1),(4,'用户管理','Auth/userIndex',1,1,0,1),(9,'统计分析','',0,1,0,1),(10,'统计查询','Index/statistics',9,1,0,0),(11,'终端状态','Index/zhongduan',9,1,0,1),(145,'支付宝-&gt;HIS','Check/search',144,1,0,1),(13,'系统日志','Index/rizhi_system',12,1,0,0),(14,'运行日志','Index/rizhi_yunxing',12,1,0,0),(142,'综合查询','Index/banklog',9,1,0,0),(144,'账目核对','Check/index',0,1,0,1),(146,'自助-&gt;银联','Check/blank',144,1,0,1),(147,'自助-&gt;支付宝','Check/alipay',144,1,0,0),(148,'日志管理','Check/loglist',9,1,0,1),(149,'支付宝日志','Check/alipay_log',9,1,0,0),(150,'支付宝交易记录','Index/alipay_record',9,1,0,1),(151,'支付宝退款记录','Check/alipay_tk',9,1,0,0),(158,'异常处理',' ',0,1,0,1),(159,'微信缴费交易退款','Index/wx_yc',158,1,0,1),(160,'支付宝缴费交易记录','Index/alipay_yc',158,1,0,1),(156,'微信交易记录','Index/wx_record',9,1,0,1),(157,'微信-&gt;HIS','Check/wx_search',144,1,0,1),(161,'支付宝部分退款','Index/zfb_bf',158,1,0,1),(162,'微信部分退款','Index/wx_bf',158,1,0,1);

/*Table structure for table `role` */

DROP TABLE IF EXISTS `role`;

CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `name` varchar(50) NOT NULL COMMENT '名称',
  `module_ids` varchar(500) DEFAULT NULL COMMENT '授权的模块ID,英文逗号分隔',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1可以0不可用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='角色表';

/*Data for the table `role` */

insert  into `role`(`id`,`name`,`module_ids`,`status`) values (1,'超级管理员','158,159,160',0),(16,'监控打印机','9,11',1),(2,'退款员','144,145,157',1);

/*Table structure for table `rule` */

DROP TABLE IF EXISTS `rule`;

CREATE TABLE `rule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级id',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文名称',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：为1正常，为0禁用',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '' COMMENT '规则表达式，为空表示存在就验证，不为空表示按照条件验证',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=138 DEFAULT CHARSET=utf8 COMMENT='规则表';

/*Data for the table `rule` */

insert  into `rule`(`id`,`pid`,`name`,`title`,`status`,`type`,`condition`) values (1,0,'','基本设置',1,1,''),(2,1,'Index/rule','权限管理',1,1,''),(3,1,'Index/group','用户组管理',1,1,''),(4,1,'Index/user','管理员列表',1,1,''),(5,0,'','系统管理',1,1,''),(6,5,'Index/zhongduan_guanli','终端管理',1,1,''),(7,5,'Index/operation','操作管理',1,1,''),(8,5,'Index/','挂号管理',1,1,''),(9,0,'','统计分析',1,1,''),(10,9,'Index/statistics','统计查询',1,1,''),(11,9,'Index/zhongduan_status','终端状态',1,1,''),(12,0,'','日志管理',1,1,''),(13,12,'Index/rizhi_system','系统日志',1,1,''),(14,12,'Index/rizhi_yunxing','运行日志',1,1,'');

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `userid` int(50) NOT NULL AUTO_INCREMENT,
  `uname` char(50) DEFAULT NULL COMMENT '用户名',
  `password` char(50) DEFAULT NULL,
  `rname` char(50) CHARACTER SET utf8 DEFAULT NULL,
  `comment` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `role_id` int(11) NOT NULL COMMENT '角色id',
  `status` int(11) NOT NULL COMMENT '1 可用 0 不可用',
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

/*Data for the table `user` */

insert  into `user`(`userid`,`uname`,`password`,`rname`,`comment`,`role_id`,`status`) values (1,'admin','zgc@123','管理员','管理员账号 切勿删除',1,0),(3,'tky002','zgc666','退款员二号','',2,1),(2,'tky001','zgc888','退款员一号','',2,1),(20,'zzj','zzj','门诊一楼','监测打印机',16,0);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
