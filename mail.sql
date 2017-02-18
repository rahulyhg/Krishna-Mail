-- MySQL dump 10.13  Distrib 5.7.17, for Linux (x86_64)
--
-- Host: localhost    Database: mail
-- ------------------------------------------------------
-- Server version	5.7.17-0ubuntu0.16.10.1

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
-- Table structure for table `mails`
--

DROP TABLE IF EXISTS `mails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `mail` varchar(255) DEFAULT NULL,
  `send` tinyint(1) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mails`
--

LOCK TABLES `mails` WRITE;
/*!40000 ALTER TABLE `mails` DISABLE KEYS */;
INSERT INTO `mails` VALUES (1,'Igor Melnik','+380996654972','mcwinlin@mail.ru',0,0),(2,'Igor Melnik','+380996654972','inflatable.syphax@gmail.com',0,0),(5,'Igor Melnik','+380996654972','inflatable.syphax@gmail.com',0,0),(3,'Igor Melnik','+380996654972','inflatable.syphax@gmail.com',0,0),(4,'Igor Melnik','+380996654972','inflatable.syphax@gmail.com',0,0),(6,'Igor Melnik','+380996654972','inflatable.syphax@gmail.com',0,0),(7,'Igor Melnik','+380996654972','inflatable.syphax@gmail.com',0,0),(8,'Igor Melnik','+380996654972','mcwinlin@mail.ru',0,0),(9,'Igor Melnik','+380996654972','inflatable.syphax@gmail.com',0,0),(10,'Igor Melnik','+380996654972','inflatable.syphax@gmail.com',0,0),(11,'Igor Melnik','+380996654972','inflatable.syphax@gmail.com',0,0),(12,'Igor Melnik','+380996654972','inflatable.syphax@gmail.com',0,0),(13,'Igor Melnik','+380996654972','inflatable.syphax@gmail.com',0,0),(14,'Igor Melnik','+380996654972','inflatable.syphax@gmail.com',0,0),(15,'Igor Melnik','+380996654972','mcwinlin@mail.ru',0,0),(16,'Igor Melnik','+380996654972','inflatable.syphax@gmail.com',0,0),(17,'Igor Melnik','+380996654972','inflatable.syphax@gmail.com',0,0),(18,'Igor Melnik','+380996654972','inflatable.syphax@gmail.com',0,0),(19,'Igor Melnik','+380996654972','inflatable.syphax@gmail.com',0,0),(20,'Igor Melnik','+380996654972','inflatable.syphax@gmail.com',0,0),(21,'Igor Melnik','+380996654972','inflatable.syphax@gmail.com',0,0),(22,'Igor Melnik','+380996654972','mcwinlin@mail.ru',0,0),(23,'Igor Melnik','+380996654972','inflatable.syphax@gmail.com',0,0),(24,'Igor Melnik','+380996654972','inflatable.syphax@gmail.com',0,0),(25,'Igor Melnik','+380996654972','inflatable.syphax@gmail.com',0,0),(26,'Igor Melnik','+380996654972','inflatable.syphax@gmail.com',0,0),(27,'Igor Melnik','+380996654972','inflatable.syphax@gmail.com',0,0),(28,'Igor Melnik','+380996654972','inflatable.syphax@gmail.com',0,0);
/*!40000 ALTER TABLE `mails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(45) DEFAULT NULL,
  `message` text,
  `files` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,'Test','<ol><li data-mce-style=\"padding-left: 30px; text-align: left;\" style=\"padding-left: 30px; text-align: left;\"><strong>TestKLjhaskljdhkjasdh</strong></li></ol>','a:0:{}',1),(2,'Mes2','<ol><li data-mce-style=\"padding-left: 30px; text-align: left;\" style=\"padding-left: 30px; text-align: left;\"><strong>Test Message</strong></li></ol>','a:0:{}',1),(9,'Test we','<ol><li data-mce-style=\"padding-left: 30px; text-align: left;\" style=\"padding-left: 30px; text-align: left;\"><strong>TestKLjhaskljdhkjasdh</strong></li></ol>','a:1:{i:0;s:37:\"2017-01-27-165021_1920x1080_scrot.png\";}',NULL),(10,'Mes2 343','<ol><li data-mce-style=\"padding-left: 30px; text-align: left;\" style=\"padding-left: 30px; text-align: left;\"><strong>Test Messageasdasdasd</strong></li></ol>','a:3:{i:0;s:37:\"2017-01-27-165021_1920x1080_scrot.png\";i:1;s:17:\"MountainRange.jpg\";i:2;s:28:\"14775211410_42b8d244da_o.jpg\";}',NULL),(11,'Test123123','<ol><li data-mce-style=\"padding-left: 30px; text-align: left;\" style=\"padding-left: 30px; text-align: left;\"><strong>TestKLjhaskljdhkjasdh</strong></li></ol>','a:3:{i:0;s:37:\"2017-01-27-165021_1920x1080_scrot.png\";i:1;s:17:\"MountainRange.jpg\";i:2;s:28:\"14775211410_42b8d244da_o.jpg\";}',NULL),(12,'Test567567','<ol><li data-mce-style=\"padding-left: 30px; text-align: left;\" style=\"padding-left: 30px; text-align: left;\"><strong>TestKLjhaskljdhkjasdh</strong></li></ol>','a:3:{i:0;s:37:\"2017-01-27-165021_1920x1080_scrot.png\";i:1;s:17:\"MountainRange.jpg\";i:2;s:28:\"14775211410_42b8d244da_o.jpg\";}',NULL),(13,'Test3453535','<ol><li data-mce-style=\"padding-left: 30px; text-align: left;\" style=\"padding-left: 30px; text-align: left;\"><strong>TestKLjhaskljdhkjasdh</strong></li></ol>','a:3:{i:0;s:37:\"2017-01-27-165021_1920x1080_scrot.png\";i:1;s:17:\"MountainRange.jpg\";i:2;s:28:\"14775211410_42b8d244da_o.jpg\";}',NULL),(14,'Testsdfsdfsdfs','<ol><li data-mce-style=\"padding-left: 30px; text-align: left;\" style=\"padding-left: 30px; text-align: left;\"><strong>TestKLjhaskljdhkjasdh</strong></li></ol>','a:3:{i:0;s:37:\"2017-01-27-165021_1920x1080_scrot.png\";i:1;s:17:\"MountainRange.jpg\";i:2;s:28:\"14775211410_42b8d244da_o.jpg\";}',NULL);
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `options`
--

DROP TABLE IF EXISTS `options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `param_name` varchar(45) DEFAULT NULL,
  `val` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `options`
--

LOCK TABLES `options` WRITE;
/*!40000 ALTER TABLE `options` DISABLE KEYS */;
INSERT INTO `options` VALUES (1,'send_interval','10'),(2,'gmail_account','inflatable.syphax@gmail.com'),(3,'gmail_pass','SVdIc2RCaEIwZWVlMExaTVU3QlU='),(4,'test_emails','a:5:{i:0;s:27:\"inflatable.syphax@gmail.com\";i:1;s:16:\"mcwinlin@mail.ru\";i:2;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";}'),(5,'active_message','1');
/*!40000 ALTER TABLE `options` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-02-18 15:41:23
