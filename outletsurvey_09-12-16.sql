-- MySQL dump 10.13  Distrib 5.7.13, for Linux (x86_64)
--
-- Host: localhost    Database: outletsurvey
-- ------------------------------------------------------
-- Server version	5.7.13-0ubuntu0.16.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_value` varchar(254) DEFAULT NULL,
  `config_type` varchar(254) DEFAULT NULL,
  `config_status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config`
--

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` VALUES (1,'Survey','menu',1),(2,'Report','menu',1),(3,'Access','menu',1),(4,'User','menu',1),(5,'Set Up','menu',1),(6,'View','control',1),(7,'Create','control',1),(8,'Read','control',1),(9,'Update','control',1),(10,'Delete','control',1),(11,'CSD (Non-Alcohol)','question',1),(12,'Soda (Non-Alcohol)','question',1),(13,'FF Drink (Non-Alcohol)','question',1),(14,'FF Powder (Non-Alcohol)','question',1),(15,'ASD (Non-Alcohol)','question',1),(16,'Isotonic Drink (Non-Alcohol)','question',1),(17,'Energy Drink (Non-Alcohol)','question',1),(18,'Carbonated Energy Drink (Non-alcohol)','question',1),(19,'Drinking Water (Non-Alcohol)','question',1),(20,'Instant Coffee (Non-Alcohol)','question',1),(21,'Computer Shop','outlet_type',1),(22,'Cafe','outlet_type',1),(23,'aaddffá€»á€™á€”á€¹','question',1);
/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `control`
--

DROP TABLE IF EXISTS `control`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `control` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `config_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=144 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `control`
--

LOCK TABLES `control` WRITE;
/*!40000 ALTER TABLE `control` DISABLE KEYS */;
INSERT INTO `control` VALUES (62,21,1,6),(63,21,1,7),(64,21,1,8),(65,21,1,9),(66,21,1,10),(67,21,2,6),(68,21,2,7),(69,21,2,8),(70,21,2,9),(71,21,2,10),(72,21,3,6),(73,21,3,7),(74,21,3,8),(75,21,3,9),(76,21,3,10),(77,21,4,6),(78,21,4,7),(79,21,4,8),(80,21,4,9),(81,21,4,10),(82,21,5,6),(83,21,5,7),(84,21,5,8),(85,21,5,9),(86,21,5,10),(87,19,1,6),(88,19,1,7),(89,19,1,8),(90,19,1,9),(91,19,1,10),(92,19,2,6),(93,19,2,7),(94,19,2,8),(95,19,2,9),(96,19,2,10),(97,19,3,6),(98,19,3,7),(99,19,3,8),(100,19,3,9),(101,19,3,10),(102,19,4,6),(103,19,4,7),(104,19,4,8),(105,19,4,9),(106,19,4,10),(107,19,5,6),(108,19,5,7),(109,19,5,8),(110,19,5,9),(111,19,5,10),(112,2,1,6),(113,2,1,7),(114,2,1,8),(115,2,1,9),(116,2,1,10),(117,2,2,6),(118,2,2,7),(119,2,2,8),(120,2,2,9),(121,2,2,10),(122,2,3,6),(123,2,3,7),(124,2,3,8),(125,2,3,9),(126,2,3,10),(127,2,4,6),(128,2,4,7),(129,2,4,8),(130,2,4,9),(131,2,4,10),(132,2,5,6),(133,2,5,7),(134,2,5,8),(135,2,5,9),(136,2,5,10),(137,22,1,6),(138,22,1,7),(139,22,1,8),(140,22,1,9),(141,22,4,6),(142,22,4,8),(143,22,4,9);
/*!40000 ALTER TABLE `control` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `question`
--

DROP TABLE IF EXISTS `question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `survey_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `answer` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question`
--

LOCK TABLES `question` WRITE;
/*!40000 ALTER TABLE `question` DISABLE KEYS */;
/*!40000 ALTER TABLE `question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(254) DEFAULT NULL,
  `role_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'sysadmin',1),(2,'admin',1),(19,'super',1),(21,'administrator',1),(22,'surveyor',1);
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `survey`
--

DROP TABLE IF EXISTS `survey`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `survey` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `area` varchar(254) DEFAULT NULL,
  `city_mm` text,
  `city_en` varchar(254) DEFAULT NULL,
  `township_mm` text,
  `township_en` varchar(254) DEFAULT NULL,
  `ward_mm` text,
  `ward_en` varchar(254) DEFAULT NULL,
  `outlet_mm` text,
  `outlet_en` varchar(254) DEFAULT NULL,
  `outlet_type` int(11) DEFAULT NULL,
  `owner_mm` text,
  `owner_en` varchar(254) DEFAULT NULL,
  `phone1` varchar(100) DEFAULT NULL,
  `phone2` varchar(100) DEFAULT NULL,
  `phone3` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `image1` varchar(254) DEFAULT NULL,
  `image2` varchar(254) DEFAULT NULL,
  `image3` varchar(254) DEFAULT NULL,
  `latitude` varchar(254) DEFAULT NULL,
  `longitude` varchar(254) DEFAULT NULL,
  `survey_status` tinyint(1) DEFAULT NULL,
  `created_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `survey`
--

LOCK TABLES `survey` WRITE;
/*!40000 ALTER TABLE `survey` DISABLE KEYS */;
/*!40000 ALTER TABLE `survey` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(254) DEFAULT NULL,
  `department` varchar(254) DEFAULT NULL,
  `position` varchar(254) DEFAULT NULL,
  `username` text,
  `password` text,
  `role_id` int(11) DEFAULT NULL,
  `user_status` tinyint(1) DEFAULT '1',
  `delete_status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Nyein Chan','I.T','Business Application Specialist','nyeinchan','Basn4@C01',1,1,0);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-09-12 10:06:22
