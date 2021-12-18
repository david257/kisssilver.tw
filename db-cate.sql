/*
SQLyog Enterprise v12.09 (64 bit)
MySQL - 10.4.13-MariaDB : Database - kiss_silver
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` int(10) unsigned NOT NULL COMMENT '上级分类',
  `catname` varchar(100) NOT NULL COMMENT '分类名称',
  `cat_banner` varchar(100) DEFAULT NULL COMMENT '分类图片',
  `sortorder` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '排序',
  `icon` varchar(100) DEFAULT NULL,
  `state` tinyint(1) unsigned NOT NULL DEFAULT 1 COMMENT '状态',
  `seo_title` varchar(255) DEFAULT NULL COMMENT 'SEO标题',
  `seo_keywords` varchar(255) DEFAULT NULL COMMENT 'SEO关键词',
  `seo_desc` varchar(255) DEFAULT NULL COMMENT 'SEO描述',
  `update_at` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '更新日期',
  `create_at` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '创建日期',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COMMENT='产品分类';

/*Data for the table `categories` */

insert  into `categories`(`catid`,`parentid`,`catname`,`cat_banner`,`sortorder`,`icon`,`state`,`seo_title`,`seo_keywords`,`seo_desc`,`update_at`,`create_at`) values (14,0,'戒指',NULL,1,'',1,'','','',1600779126,0),(15,0,'耳環',NULL,2,'',1,'','','',1600779204,0),(16,0,'項鍊',NULL,3,'',1,'','','',1600779216,0),(17,0,'情侶對飾',NULL,4,'',1,'','','',1600779229,0),(18,0,'手鍊',NULL,5,'',1,'','','',1600779241,0),(19,0,'腳鍊',NULL,6,'',1,'','','',1600779255,0),(20,0,'手環',NULL,7,'',1,'','','',1600779267,0),(21,14,'訂婚戒指',NULL,1,'',1,'','','',1600779284,0),(22,14,'純銀戒指',NULL,2,'',1,'','','',1600779297,0),(23,22,'蜜糖彩鑽線戒系列',NULL,1,'',1,'','','',1600779321,0),(24,15,'純銀耳環',NULL,1,'',1,'','','',1600779338,0),(25,15,'珠寶鋼耳環',NULL,2,'',1,'','','',1600779352,0),(26,24,'極簡',NULL,1,'',1,'','','',1600779366,0),(27,24,'圈圈系列',NULL,2,'',1,'','','',1600779379,0),(28,24,'鎖式系列',NULL,3,'',1,'','','',1600779391,0),(29,25,'圈圈系列',NULL,1,'',1,'','','',1600779405,0),(30,25,'鎖式系列',NULL,2,'',1,'','','',1600779423,0),(31,16,'純銀項鍊',NULL,1,'',1,'','','',1600779449,0),(32,31,'小資銀系列',NULL,1,'',1,'','','',1600779464,0),(33,31,'跳舞墜系列',NULL,2,'',1,'','','',1600779476,0),(34,17,'情侶對戒｜純銀',NULL,1,'',1,'','','',1600779494,0),(35,17,'情侶對鍊｜純銀',NULL,2,'',1,'','','',1600779510,0),(36,18,'純銀手鍊',NULL,1,'',1,'','','',1600779536,0),(37,19,'純銀腳鍊',NULL,1,'',1,'','','',1600779552,0),(38,20,'純銀手環',NULL,1,'',1,'','','',1600779568,0);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
