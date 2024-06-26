-- MariaDB dump 10.19-11.3.2-MariaDB, for osx10.19 (arm64)
--
-- Host: localhost    Database: data_encryption
-- ------------------------------------------------------
-- Server version	11.0.2-MariaDB-1:11.0.2+maria~ubu2204-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


--
-- Table structure for table `people`
--

DROP TABLE IF EXISTS `people`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `people` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nik` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `cecar` varchar(100) DEFAULT NULL,
  `idx_nik` int(10) unsigned DEFAULT NULL,
  `idx_name` int(10) unsigned DEFAULT NULL,
  `idx_cc` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `people`
--

LOCK TABLES `people` WRITE;
/*!40000 ALTER TABLE `people` DISABLE KEYS */;
INSERT INTO `people` VALUES
(1,'Wk9ORXRaVG1LY0VXYXc4b0JSK3dRUT09','a3FMY1Y1NjAxTzhiRE1qeVVVamVUUT09','Tjl6REphenNzb3lXaWVTcFYyNTA2Zz09',1,2,3,'2024-06-09 13:41:58','2024-06-09 13:41:58'),
(2,'TjR5bGtOYml2RXpxYXB1WHhLUENTdz09','NEtJaDVIVkc2OXpoRW5OSWNPU2JPZz09','TXc2UFZ0bi8yNU5tL05TdzlCL2JTdz09',4,5,6,'2024-06-09 13:42:45','2024-06-09 13:42:45'),
(3,'Z0lCZ0dzN29wWGJDM2Q5alRSdHF5dz09','SEIzek5tWEVNVnh1SUdDQlRzTjZEZz09','TmFOa0ZtYU5Rb2MzZHV5a1RSV1N2dz09',7,8,9,'2024-06-09 13:43:28','2024-06-09 13:43:28'),
(4,'QUI1UnRGbDFJcmpLeWNhMGR0SWVBZz09','QjV6Q09wV0l5c0pNdERvbnFXRW5EQT09','dFlnMUJMZEdrNlg4STZVNWNmZjdyQT09',10,11,12,'2024-06-17 10:42:19','2024-06-17 10:42:19'),
(5,'ZWhCQk5ueENkWkYzSUxmdlRuVGFYdz09','bE5qMnV4K1Fsb2tWM0xQYlZZaFRXQT09','VUJDenM1TXhoOUQwSGsvTU9jMGtGdz09',13,14,15,'2024-06-17 10:43:06','2024-06-17 10:43:06'),
(6,'ZUoySGxUT3ZBbDgxSkdLM0ZzRW1TZz09','eVFOdzVHR2tWMmVnbDc2dk5Odk85dz09','YXlEN3FkMWc3RXBxMWV6QTFoa2JDUT09',16,17,18,'2024-06-17 10:44:39','2024-06-17 10:44:39'),
(7,'ZWhCQk5ueENkWkYzSUxmdlRuVGFYdz09','ZWxlZE9ZV3F0Q3dJQnlnMmphZENjdz09','VDZTcTVVb1FaeHBuLzVuUkpQeExsQT09',19,20,21,'2024-06-17 11:08:26','2024-06-17 11:08:26'),
(12,'NTJrekRJU1JxcHBZMlhLbGZGOUJRdz09','NENUTjhVckRtWE4yMnAwU1hjSFhZdz09','a2VVOXRuVzRxZ2NrTS9TTUxnRXRmUT09',25,26,27,'2024-06-17 11:22:30','2024-06-17 11:22:30'),
(13,'UDN2VVdSTFNaVGlGU2o2Q1JGdTgydz09','SGpGZUcxMktSa2E3bUxqZStNR0oyQT09','SktmaEtGcVdzdmdCTWdmcktxZEc3dz09',28,29,30,'2024-06-17 11:23:21','2024-06-17 11:23:21');
/*!40000 ALTER TABLE `people` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'data_encryption'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-06-17 20:05:07
