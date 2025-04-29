-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: localhost    Database: libman
-- ------------------------------------------------------
-- Server version	8.0.41

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
-- Table structure for table `activityhistory`
--

DROP TABLE IF EXISTS `activityhistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activityhistory` (
  `activityID` int NOT NULL AUTO_INCREMENT,
  `userID` int NOT NULL,
  `bookID` int DEFAULT NULL,
  `activityType` varchar(50) NOT NULL,
  `activityTimestamp` datetime DEFAULT CURRENT_TIMESTAMP,
  `dueDate` datetime DEFAULT NULL,
  PRIMARY KEY (`activityID`),
  KEY `userID` (`userID`),
  KEY `bookID` (`bookID`),
  CONSTRAINT `activityhistory_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`),
  CONSTRAINT `activityhistory_ibfk_2` FOREIGN KEY (`bookID`) REFERENCES `books` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activityhistory`
--

LOCK TABLES `activityhistory` WRITE;
/*!40000 ALTER TABLE `activityhistory` DISABLE KEYS */;
INSERT INTO `activityhistory` VALUES (1,3,11,'Checked Out','2025-04-29 12:46:00',NULL),(2,3,18,'Checked Out','2025-04-29 13:01:09','2025-05-06 17:01:09'),(3,3,5,'Checked Out','2025-04-29 13:01:32','2025-05-06 17:01:32'),(4,3,18,'Returned','2025-04-29 13:31:27',NULL),(5,3,22,'Added Book','2025-04-29 14:22:53',NULL);
/*!40000 ALTER TABLE `activityhistory` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-29 14:55:36
