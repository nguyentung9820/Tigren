-- MySQL dump 10.13  Distrib 8.0.24, for Linux (x86_64)
--
-- Host: localhost    Database: magento2
-- ------------------------------------------------------
-- Server version	8.0.24

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `customer_group_catalog_rules`
--

DROP TABLE IF EXISTS `customer_group_catalog_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_group_catalog_rules` (
  `rule_id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Entity ID',
  `name` varchar(255) DEFAULT NULL COMMENT 'Name',
  `customer_group_id` varchar(50) NOT NULL,
  `store_id` varchar(50) NOT NULL,
  `product_id` varchar(50) DEFAULT NULL,
  `discount_amount` decimal(20,6) NOT NULL DEFAULT '0.000000' COMMENT 'Discount Amount',
  `start_time` date DEFAULT NULL COMMENT 'From',
  `end_time` date DEFAULT NULL COMMENT 'To',
  `sort_order` int unsigned NOT NULL DEFAULT '0' COMMENT 'Sort Order',
  `is_active` smallint NOT NULL DEFAULT '0' COMMENT 'Is Active',
  PRIMARY KEY (`rule_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3 COMMENT='Customer Group Catalog Rules';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_group_catalog_rules`
--

LOCK TABLES `customer_group_catalog_rules` WRITE;
/*!40000 ALTER TABLE `customer_group_catalog_rules` DISABLE KEYS */;
INSERT INTO `customer_group_catalog_rules` VALUES (2,'New Rule 1','1,2,3','1','24-MB01,24-MB03,24-MB02',20.000000,'2021-04-02','2021-04-29',5,1),(5,'rule 2','1,3','1,2','WT09-S-White',10.000000,'2021-04-14','2021-04-29',3,1),(6,'rule 3','1,3','1,2','WT09-S-White,24-MB01,24-MB03,24-MB02',30.000000,'2021-04-11','2021-05-09',4,1),(7,'rule 4','1,2,3','1','WT09-S-White',50.000000,'2021-04-12','2021-04-29',10,1),(8,'rul 5','1,2,3','1,2','WT09-S-White',90.000000,'2021-04-04','2021-04-30',1,1);
/*!40000 ALTER TABLE `customer_group_catalog_rules` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-05-04 11:04:34
