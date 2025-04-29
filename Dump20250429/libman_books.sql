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
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `books` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `publish_date` date DEFAULT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `page_count` int DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `isbn` (`isbn`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `books`
--

LOCK TABLES `books` WRITE;
/*!40000 ALTER TABLE `books` DISABLE KEYS */;
INSERT INTO `books` VALUES (1,'The Great Adventure','John Doe','9780143127741','Adventure Press','2021-05-10','Adventure',320,'English',1),(2,'The Lost Kingdom','Jane Smith','9780316769488','Mystery Books','2019-11-20','Mystery',250,'English',1),(3,'Fictional Futures','Bob Johnson','9781451686691','Future Publishing','2020-01-15','Science Fiction',410,'English',1),(4,'Secrets of the Past','Emily Davis','9780593099389','History House','2018-07-23','Historical Fiction',280,'English',1),(5,'The Art of Cooking','Sarah Lee','9781449407357','Food Press','2017-03-09','Cookbook',150,'English',0),(6,'Journey to the Unknown','Mark Brown','9780061122415','Unknown Publisher','2015-10-05','Adventure',300,'English',1),(7,'Into the Wilderness','Linda Green','9780452295262','Wild Press','2021-08-30','Adventure',350,'English',0),(8,'The Digital World','Michael White','9781400079131','Tech Publishing','2022-02-18','Technology',420,'English',0),(9,'Deep Space Exploration','Oliver Harris','9780062295350','Space Press','2019-04-11','Science Fiction',480,'English',1),(10,'Love in the Air','Jessica Roberts','9780062362211','Romance House','2018-12-25','Romance',270,'English',0),(11,'The Unseen Depths','David Clark','9780735213212','Science Books','2021-06-16','Science',330,'English',0),(12,'The Mystery of Time','Sophia Johnson','9781101904168','Time Books','2020-09-04','Mystery',290,'English',0),(13,'Cooking with Passion','Thomas Brown','9781118982564','Kitchen Press','2017-01-10','Cookbook',160,'English',1),(14,'Darkness and Light','Mary Williams','9780525575749','Fiction House','2019-12-03','Fantasy',400,'English',0),(15,'Power and Politics','James Taylor','9780374281158','Political Press','2020-05-21','Politics',350,'English',0),(16,'Echoes of the Past','Karen White','9780399562704','History Books','2016-09-14','Historical Fiction',310,'English',1),(17,'City of Dreams','Carlos Martinez','9780345803510','Dream Press','2021-02-25','Urban Fiction',270,'English',0),(18,'Adventures in Technology','Steve McCauley','9780451487529','Tech Trends','2021-11-07','Technology',400,'English',1),(19,'Wonders of Nature','John Green','9780765374233','Nature Press','2020-04-19','Nature',310,'English',1),(20,'The Shadow Chronicles','Eliza Brown','9781409128425','Shadow Press','2018-06-23','Fantasy',380,'English',1),(22,'That One Book','Baba Yaga','324234','Penguin','1212-12-12','Horror',1212,'Latin',1);
/*!40000 ALTER TABLE `books` ENABLE KEYS */;
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
