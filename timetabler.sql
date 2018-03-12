-- MySQL dump 10.13  Distrib 5.7.21, for Linux (x86_64)
--
-- Host: localhost    Database: timetabler
-- ------------------------------------------------------
-- Server version	5.7.21-0ubuntu0.16.04.1

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
-- Table structure for table `class_subject`
--

DROP TABLE IF EXISTS `class_subject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class_subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `table_id` int(11) DEFAULT NULL,
  `c_class` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3EBB5986A76ED395` (`user_id`),
  KEY `IDX_3EBB598641807E1D` (`teacher_id`),
  KEY `IDX_3EBB598623EDC87` (`subject_id`),
  KEY `IDX_3EBB5986ECFF285C` (`table_id`),
  CONSTRAINT `FK_3EBB598623EDC87` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_3EBB598641807E1D` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_3EBB5986A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_3EBB5986ECFF285C` FOREIGN KEY (`table_id`) REFERENCES `timetable` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=155 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class_subject`
--

LOCK TABLES `class_subject` WRITE;
/*!40000 ALTER TABLE `class_subject` DISABLE KEYS */;
INSERT INTO `class_subject` VALUES (1,1,1,1,1,1),(2,1,1,1,1,2),(3,1,1,2,1,1),(4,1,1,3,1,2),(5,1,1,3,1,1),(6,1,1,2,1,2),(7,1,1,4,1,1),(8,1,1,5,1,1),(9,1,1,6,1,1),(10,1,1,7,1,1),(12,1,1,8,1,1),(13,1,1,9,1,1),(14,1,1,10,1,1),(15,1,1,11,1,1),(16,1,1,12,1,1),(17,1,1,13,1,1),(18,1,1,14,1,1),(19,1,1,15,1,1),(20,1,1,4,1,2),(21,1,1,5,1,2),(22,1,1,6,1,2),(23,1,1,7,1,2),(24,1,1,8,1,2),(25,1,1,9,1,2),(26,1,1,10,1,2),(27,1,1,11,1,2),(28,1,1,11,1,2),(29,1,1,12,1,2),(30,1,1,13,1,2),(31,1,1,14,1,2),(32,1,1,15,1,2),(33,1,1,16,1,1),(34,1,1,16,1,2),(35,1,1,17,1,1),(36,1,1,17,1,2),(37,1,2,1,2,3),(38,1,2,2,2,3),(39,1,2,3,2,3),(40,1,2,18,2,3),(41,1,2,19,2,3),(42,1,2,8,2,3),(43,1,2,16,2,3),(44,1,2,20,2,3),(45,1,2,21,2,3),(46,1,2,11,2,3),(47,1,2,6,2,3),(48,1,2,12,2,3),(49,1,2,14,2,3),(50,1,2,13,2,3),(51,1,4,3,3,4),(52,1,4,3,3,5),(53,1,4,18,3,6),(54,1,4,18,3,7),(55,1,4,18,3,8),(57,1,8,1,3,5),(58,1,8,3,3,6),(59,1,8,1,3,6),(61,1,7,22,3,4),(62,1,7,6,3,6),(63,1,7,2,3,7),(64,1,7,2,3,8),(65,1,7,6,3,8),(66,1,3,2,3,4),(67,1,3,6,3,4),(68,1,3,2,3,5),(69,1,3,2,3,6),(70,1,3,20,3,7),(71,1,5,1,3,7),(72,1,5,3,3,7),(73,1,5,1,3,8),(74,1,5,3,3,8),(75,1,6,1,3,4),(76,1,6,19,3,5),(77,1,6,19,3,6),(78,1,6,19,3,7),(79,1,6,19,3,8),(81,1,8,18,3,5),(82,1,4,20,3,6),(83,1,7,19,3,4),(84,1,3,6,3,5),(85,1,3,22,3,8),(86,1,8,20,3,5),(88,1,3,18,3,4),(89,1,7,6,3,7),(90,1,7,20,3,4),(91,1,10,20,3,4),(92,1,10,20,3,5),(93,1,10,20,3,6),(94,1,10,20,3,7),(95,1,10,20,3,8),(96,1,3,15,3,8),(97,1,3,15,3,7),(98,1,6,15,3,5),(99,1,5,15,3,4),(100,1,10,15,3,4),(101,1,10,15,3,5),(102,1,10,15,3,6),(103,1,10,15,3,7),(104,1,10,15,3,8),(105,1,10,22,3,4),(106,1,10,22,3,5),(107,1,10,22,3,6),(108,1,10,22,3,7),(109,1,10,22,3,8),(110,1,10,14,3,4),(111,1,10,14,3,5),(112,1,10,14,3,6),(113,1,10,14,3,7),(114,1,10,14,3,8),(115,2,11,24,5,3),(116,2,11,24,5,2),(117,2,12,24,5,2),(118,2,12,25,5,3),(121,1,18,23,4,1),(122,1,18,30,4,1),(123,1,18,31,4,1),(124,1,18,32,4,1),(125,1,18,33,4,1),(126,1,18,34,4,1),(127,1,18,35,4,1),(128,1,18,36,4,1),(129,1,18,37,4,1),(130,1,18,38,4,1),(131,1,18,39,4,1),(132,1,19,35,4,1),(133,1,1,40,1,1),(134,1,1,41,1,1),(135,1,1,42,1,2),(136,1,1,43,1,2),(137,1,1,44,1,2),(138,1,1,45,1,1),(146,4,24,52,8,1),(147,4,24,52,8,2),(148,4,24,54,8,1),(149,4,26,52,8,3),(150,4,26,53,8,2),(151,4,26,53,8,1),(152,4,25,53,8,3),(153,4,25,54,8,2),(154,4,25,54,8,3);
/*!40000 ALTER TABLE `class_subject` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lesson_duration`
--

DROP TABLE IF EXISTS `lesson_duration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lesson_duration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `table_id` int(11) DEFAULT NULL,
  `lesson` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_break` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `long_break` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lunch` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `games` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `activity` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `start_time` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CE18CAD9A76ED395` (`user_id`),
  KEY `IDX_CE18CAD9ECFF285C` (`table_id`),
  CONSTRAINT `FK_CE18CAD9A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_CE18CAD9ECFF285C` FOREIGN KEY (`table_id`) REFERENCES `timetable` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lesson_duration`
--

LOCK TABLES `lesson_duration` WRITE;
/*!40000 ALTER TABLE `lesson_duration` DISABLE KEYS */;
/*!40000 ALTER TABLE `lesson_duration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `school_days`
--

DROP TABLE IF EXISTS `school_days`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `school_days` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `table_id` int(11) DEFAULT NULL,
  `day` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_AACC238BA76ED395` (`user_id`),
  KEY `IDX_AACC238BECFF285C` (`table_id`),
  CONSTRAINT `FK_AACC238BA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_AACC238BECFF285C` FOREIGN KEY (`table_id`) REFERENCES `timetable` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `school_days`
--

LOCK TABLES `school_days` WRITE;
/*!40000 ALTER TABLE `school_days` DISABLE KEYS */;
/*!40000 ALTER TABLE `school_days` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `table_id` int(11) DEFAULT NULL,
  `f_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `l_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `class` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `age` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B723AF33A76ED395` (`user_id`),
  KEY `IDX_B723AF33ECFF285C` (`table_id`),
  CONSTRAINT `FK_B723AF33A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_B723AF33ECFF285C` FOREIGN KEY (`table_id`) REFERENCES `timetable` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student`
--

LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
/*!40000 ALTER TABLE `student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subject`
--

DROP TABLE IF EXISTS `subject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `table_id` int(11) DEFAULT NULL,
  `s_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_FBCE3E7AA76ED395` (`user_id`),
  KEY `IDX_FBCE3E7AECFF285C` (`table_id`),
  CONSTRAINT `FK_FBCE3E7AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_FBCE3E7AECFF285C` FOREIGN KEY (`table_id`) REFERENCES `timetable` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subject`
--

LOCK TABLES `subject` WRITE;
/*!40000 ALTER TABLE `subject` DISABLE KEYS */;
INSERT INTO `subject` VALUES (1,1,1,'Maths'),(2,1,1,'Kiswahili'),(3,1,1,'English'),(4,1,1,'Movement'),(5,1,1,'Hygiene & Nut'),(6,1,1,'C.R.E'),(7,1,1,'Prac Activities'),(8,1,1,'H/Writing'),(9,1,1,'Environment'),(10,1,1,'Literacy'),(11,1,1,'Story Telling'),(12,1,1,'Games'),(13,1,1,'Clubs'),(14,1,1,'P.P.I'),(15,1,1,'Cr/Arts'),(16,1,1,'Homework'),(17,1,1,'Activity'),(18,1,2,'Science'),(19,1,2,'Social S'),(20,1,2,'P.E'),(21,1,2,'Drawing'),(22,1,2,'L/Skills'),(23,1,4,'Language'),(24,2,5,'Maths'),(25,2,5,'Science'),(26,2,5,'Social S.'),(27,2,5,'C.R.E'),(30,1,4,'Maths'),(31,1,4,'Environment'),(32,1,4,'Pys'),(33,1,4,'Prac Activities'),(34,1,4,'Creative Art'),(35,1,4,'Story Telling'),(36,1,4,'Religious'),(37,1,4,'Games'),(38,1,4,'Nature Walk'),(39,1,4,'P.P.I'),(40,1,1,'CRE Activity'),(41,1,1,'H/W H/Writing'),(42,1,1,'CRE Activity'),(43,1,1,'H/W H/Writing'),(44,1,1,'Env Activities'),(45,1,1,'Env Activities'),(46,3,7,'Math'),(47,3,7,'English'),(48,3,7,'Kiswahili'),(49,3,7,'Science'),(50,3,7,'Social Studies'),(51,3,7,'C.R.E'),(52,4,8,'Maths'),(53,4,8,'English'),(54,4,8,'Kiswahili');
/*!40000 ALTER TABLE `subject` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `table_format`
--

DROP TABLE IF EXISTS `table_format`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `table_format` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `table_id` int(11) DEFAULT NULL,
  `activity` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `duration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8068771CA76ED395` (`user_id`),
  KEY `IDX_8068771CECFF285C` (`table_id`),
  CONSTRAINT `FK_8068771CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_8068771CECFF285C` FOREIGN KEY (`table_id`) REFERENCES `timetable` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `table_format`
--

LOCK TABLES `table_format` WRITE;
/*!40000 ALTER TABLE `table_format` DISABLE KEYS */;
INSERT INTO `table_format` VALUES (1,1,1,'lesson','30','1'),(2,1,1,'lesson','30','2'),(3,1,1,'break','30','Break'),(4,1,1,'lesson','30','4'),(5,1,1,'lesson','30','5'),(6,1,1,'break','40','Break'),(7,1,1,'lesson','30','7'),(8,1,1,'lesson','30','8'),(9,1,1,'break','90','Lunch'),(10,1,1,'lesson','60','10'),(11,1,1,'lesson','60','11'),(12,1,2,'lesson','30','1'),(13,1,2,'lesson','30','2'),(14,1,2,'break','30','Break'),(15,1,2,'lesson','30','4'),(16,1,2,'lesson','30','5'),(17,1,2,'break','40','Break'),(18,1,2,'lesson','30','7'),(19,1,2,'lesson','30','8'),(20,1,2,'break','90','Lunch'),(21,1,2,'lesson','60','10'),(22,1,2,'lesson','60','11'),(23,1,3,'lesson','35','1'),(24,1,3,'lesson','35','2'),(25,1,3,'break','20','Short Break'),(26,1,3,'lesson','35','4'),(27,1,3,'lesson','35','5'),(28,1,3,'break','30','Long Break'),(29,1,3,'lesson','35','7'),(30,1,3,'lesson','35','8'),(31,1,3,'break','80','Lunch'),(32,1,3,'lesson','35','10'),(33,1,3,'lesson','35','11'),(34,1,4,'break','40','Free Choice'),(35,1,4,'break','40','Assembly'),(36,1,4,'lesson','30','3'),(37,1,4,'lesson','30','4'),(38,1,4,'break','60','Break'),(39,1,4,'lesson','30','6'),(40,1,4,'lesson','30','7'),(41,1,4,'break','100','Lunch'),(42,1,4,'break','40','Quiet Moment'),(43,1,4,'lesson','60','11'),(44,2,5,'lesson','30','1'),(45,2,5,'break','20','Break'),(46,2,5,'lesson','35','3'),(51,3,7,'lesson','30','...'),(52,3,7,'lesson','30','...'),(53,3,7,'break','20','Break'),(54,3,7,'lesson','30','...'),(55,3,7,'lesson','30','...'),(56,3,7,'break','60','Lunch'),(57,3,7,'lesson','30','...'),(58,3,7,'lesson','30','...'),(59,4,8,'lesson','30','...'),(60,4,8,'lesson','30','...'),(61,4,8,'break','20','Break'),(62,4,8,'lesson','30','...'),(63,4,8,'lesson','30','...'),(64,4,8,'break','60','Lunch'),(65,4,8,'lesson','30','...'),(66,4,8,'lesson','30','...');
/*!40000 ALTER TABLE `table_format` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacher`
--

DROP TABLE IF EXISTS `teacher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teacher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `table_id` int(11) DEFAULT NULL,
  `f_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `l_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_B0F6A6D5665648E9` (`color`),
  KEY `IDX_B0F6A6D5A76ED395` (`user_id`),
  KEY `IDX_B0F6A6D5ECFF285C` (`table_id`),
  CONSTRAINT `FK_B0F6A6D5A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_B0F6A6D5ECFF285C` FOREIGN KEY (`table_id`) REFERENCES `timetable` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher`
--

LOCK TABLES `teacher` WRITE;
/*!40000 ALTER TABLE `teacher` DISABLE KEYS */;
INSERT INTO `teacher` VALUES (1,1,1,'Tr.','Rhoda','#dbafe1'),(2,1,2,'Tr.','Moses','#dedede'),(3,1,3,'Tr.','Erick','#7983e1'),(4,1,3,'Tr.','Milcah','#73d9bb'),(5,1,3,'Tr.','Limo','#928691'),(6,1,3,'Tr.','Mary','#e3a55b'),(7,1,3,'Tr.','Dorcas','#f5d522'),(8,1,3,'Tr.','Charity','#b25ce6'),(10,1,3,'Tr.','Unassigned','#ffffff'),(11,2,5,'Tr.','Johann','#8b87dc'),(12,2,5,'Tr.','Selenn','#86d795'),(18,1,4,'Tr.','Mercy','#d0b9e9'),(19,1,4,'Tr','Unassigned','#f59797'),(20,3,7,'Charles','Birai','#a6ead1'),(21,3,7,'Tabitha','Wayua','#c0c8e5'),(22,3,7,'George','Birai','#dec484'),(23,3,7,'Joyce','Birai','#eab2e2'),(24,4,8,'Tr','Ngungu','#dea6a6'),(25,4,8,'Tr.','Ngunguess','#89dbc7'),(26,4,8,'Tr.','Kayu','#cacaca');
/*!40000 ALTER TABLE `teacher` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timetable`
--

DROP TABLE IF EXISTS `timetable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timetable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_id` int(11) DEFAULT NULL,
  `time` datetime NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `classRange` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6B1F670ECFF285C` (`table_id`),
  CONSTRAINT `FK_6B1F670ECFF285C` FOREIGN KEY (`table_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timetable`
--

LOCK TABLES `timetable` WRITE;
/*!40000 ALTER TABLE `timetable` DISABLE KEYS */;
INSERT INTO `timetable` VALUES (1,1,'1970-01-01 08:20:00','Destiny Junior Academy - Term 1 Grade 1 TimeTable','1-1'),(2,1,'1970-01-01 08:20:00','Destiny Junior Academy - Class 3 Term 1 Timetable','3-3'),(3,1,'1970-01-01 08:20:00','Destiny Junior Academy - Class 4 - 8 Timetable','4-8'),(4,1,'1970-01-01 08:00:00','Destiny Junior Academy - PP2 Timetable','1-1'),(5,2,'1970-01-01 08:00:00','Test TimeTable','1-3'),(7,3,'1970-01-01 08:30:00','My First Timetable','4-6'),(8,4,'1970-01-01 08:30:00','My First Timetable','1-3');
/*!40000 ALTER TABLE `timetable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timetabler`
--

DROP TABLE IF EXISTS `timetabler`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timetabler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `class_sub_id` int(11) DEFAULT NULL,
  `time` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `class` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `teacher` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `table_format_column` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `day` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3C0A3F57ECFF285C` (`table_id`),
  KEY `IDX_3C0A3F57A76ED395` (`user_id`),
  KEY `IDX_3C0A3F57CA35D061` (`class_sub_id`),
  CONSTRAINT `FK_3C0A3F57A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_3C0A3F57CA35D061` FOREIGN KEY (`class_sub_id`) REFERENCES `class_subject` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_3C0A3F57ECFF285C` FOREIGN KEY (`table_id`) REFERENCES `timetable` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=423 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timetabler`
--

LOCK TABLES `timetabler` WRITE;
/*!40000 ALTER TABLE `timetabler` DISABLE KEYS */;
INSERT INTO `timetabler` VALUES (1,1,1,1,'08:20-08:50','1','1','1','1','Monday'),(2,1,1,2,'08:20-08:50','2','1','1','1','Monday'),(3,1,1,3,'08:50-09:20','1','1','2','2','Monday'),(4,1,1,6,'08:50-09:20','2','1','2','2','Monday'),(5,1,1,7,'09:50-10:20','1','1','4','4','Monday'),(6,1,1,20,'09:50-10:20','2','1','4','4','Monday'),(7,8,4,148,'10:20-10:50','1','24','54','63','Monday'),(8,8,4,150,'10:20-10:50','2','26','53','63','Monday'),(9,1,1,5,'09:50-10:20','1','1','3','4','Monday'),(10,8,4,153,'09:50-10:20','2','25','54','62','Monday'),(11,1,1,8,'11:30-12:00','1','1','5','7','Monday'),(12,1,1,21,'11:30-12:00','2','1','5','7','Monday'),(13,1,1,9,'12:00-12:30','1','1','6','8','Monday'),(14,1,1,22,'12:00-12:30','2','1','6','8','Monday'),(15,1,1,10,'14:00-15:00','1','1','7','10','Monday'),(16,1,1,23,'14:00-15:00','2','1','7','10','Monday'),(17,1,1,12,'15:00-16:00','1','1','8','11','Monday'),(18,1,1,24,'15:00-16:00','2','1','8','11','Monday'),(19,1,1,15,'15:00-16:00','1','1','11','11','Tuesday'),(20,1,1,27,'15:00-16:00','2','1','11','11','Tuesday'),(21,1,1,12,'14:00-15:00','1','1','8','10','Tuesday'),(22,1,1,24,'14:00-15:00','2','1','8','10','Tuesday'),(23,1,1,14,'12:00-12:30','1','1','10','8','Tuesday'),(24,1,1,26,'12:00-12:30','2','1','10','8','Tuesday'),(25,1,1,3,'11:30-12:00','1','1','2','7','Tuesday'),(26,1,1,6,'11:30-12:00','2','1','2','7','Tuesday'),(27,1,1,1,'10:20-10:50','1','1','1','5','Tuesday'),(28,1,1,2,'10:20-10:50','2','1','1','5','Tuesday'),(29,1,1,13,'09:50-10:20','1','1','9','4','Tuesday'),(30,1,1,25,'09:50-10:20','2','1','9','4','Tuesday'),(31,1,1,7,'08:50-09:20','1','1','4','2','Tuesday'),(32,1,1,20,'08:50-09:20','2','1','4','2','Tuesday'),(33,1,1,5,'08:20-08:50','1','1','3','1','Tuesday'),(34,1,1,4,'08:20-08:50','2','1','3','1','Tuesday'),(35,1,1,1,'08:20-08:50','1','1','1','1','Wednesday'),(36,1,1,2,'08:20-08:50','2','1','1','1','Wednesday'),(37,1,1,7,'08:50-09:20','1','1','4','2','Wednesday'),(38,1,1,20,'08:50-09:20','2','1','4','2','Wednesday'),(39,1,1,14,'09:50-10:20','1','1','10','4','Wednesday'),(40,1,1,26,'09:50-10:20','2','1','10','4','Wednesday'),(41,1,1,3,'10:20-10:50','1','1','2','5','Wednesday'),(42,1,1,6,'10:20-10:50','2','1','2','5','Wednesday'),(43,1,1,5,'11:30-12:00','1','1','3','7','Wednesday'),(44,1,1,4,'11:30-12:00','2','1','3','7','Wednesday'),(45,1,1,9,'12:00-12:30','1','1','6','8','Wednesday'),(46,1,1,22,'12:00-12:30','2','1','6','8','Wednesday'),(47,1,1,12,'14:00-15:00','1','1','8','10','Wednesday'),(48,1,1,24,'14:00-15:00','2','1','8','10','Wednesday'),(49,1,1,16,'15:00-16:00','1','1','12','11','Wednesday'),(50,1,1,29,'15:00-16:00','2','1','12','11','Wednesday'),(51,1,1,15,'15:00-16:00','1','1','11','11','Thursday'),(52,1,1,27,'15:00-16:00','2','1','11','11','Thursday'),(53,1,1,12,'14:00-15:00','1','1','8','10','Thursday'),(54,1,1,24,'14:00-15:00','2','1','8','10','Thursday'),(55,1,1,34,'15:00-16:00','2','1','16','11','Monday'),(56,1,1,33,'14:00-15:00','1','1','16','10','Wednesday'),(57,1,1,36,'12:00-12:30','2','1','17','8','Wednesday'),(58,1,1,34,'14:00-15:00','2','1','16','10','Thursday'),(59,1,1,13,'12:00-12:30','1','1','9','8','Thursday'),(60,1,1,25,'12:00-12:30','2','1','9','8','Thursday'),(61,1,1,1,'11:30-12:00','1','1','1','7','Thursday'),(62,1,1,2,'11:30-12:00','2','1','1','7','Thursday'),(63,1,1,9,'10:20-10:50','1','1','6','5','Thursday'),(64,1,1,22,'10:20-10:50','2','1','6','5','Thursday'),(65,1,1,5,'09:50-10:20','1','1','3','4','Thursday'),(66,1,1,4,'09:50-10:20','2','1','3','4','Thursday'),(67,1,1,7,'08:50-09:20','1','1','4','2','Thursday'),(68,1,1,20,'08:50-09:20','2','1','4','2','Thursday'),(69,1,1,8,'08:20-08:50','1','1','5','1','Thursday'),(70,1,1,21,'08:20-08:50','2','1','5','1','Thursday'),(71,1,1,18,'08:20-08:50','1','1','14','1','Friday'),(72,1,1,31,'08:20-08:50','2','1','14','1','Friday'),(73,1,1,3,'08:50-09:20','1','1','2','2','Friday'),(74,1,1,6,'08:50-09:20','2','1','2','2','Friday'),(75,1,1,1,'09:50-10:20','1','1','1','4','Friday'),(76,1,1,2,'09:50-10:20','2','1','1','4','Friday'),(77,1,1,7,'10:20-10:50','1','1','4','5','Friday'),(78,1,1,20,'10:20-10:50','2','1','4','5','Friday'),(79,1,1,13,'11:30-12:00','1','1','9','7','Friday'),(80,1,1,25,'11:30-12:00','2','1','9','7','Friday'),(81,1,1,19,'12:00-12:30','1','1','15','8','Friday'),(82,1,1,32,'12:00-12:30','2','1','15','8','Friday'),(83,1,1,24,'14:00-15:00','2','1','8','10','Friday'),(84,1,1,12,'14:00-15:00','1','1','8','10','Friday'),(85,1,1,17,'15:00-16:00','1','1','13','11','Friday'),(86,1,1,30,'15:00-16:00','2','1','13','11','Friday'),(87,2,1,37,'08:20-08:50','3','2','1','12','Monday'),(88,2,1,38,'08:20-08:50','3','2','2','13','Monday'),(89,2,1,39,'08:20-08:50','3','2','3','15','Monday'),(90,2,1,44,'08:20-08:50','3','2','20','16','Monday'),(91,2,1,40,'08:20-08:50','3','2','18','18','Monday'),(92,2,1,41,'08:20-08:50','3','2','19','19','Monday'),(93,2,1,42,'08:20-08:50','3','2','8','21','Monday'),(94,2,1,43,'08:20-08:50','3','2','16','22','Monday'),(95,2,1,39,'08:20-08:50','3','2','3','12','Tuesday'),(96,2,1,44,'08:20-08:50','3','2','20','13','Tuesday'),(97,2,1,41,'08:20-08:50','3','2','19','15','Tuesday'),(98,2,1,37,'08:20-08:50','3','2','1','16','Tuesday'),(99,2,1,38,'08:20-08:50','3','2','2','18','Tuesday'),(100,2,1,40,'08:20-08:50','3','2','18','19','Tuesday'),(101,2,1,45,'08:20-08:50','3','2','21','21','Tuesday'),(102,2,1,46,'08:20-08:50','3','2','11','22','Tuesday'),(103,2,1,48,'08:20-08:50','3','2','12','22','Wednesday'),(104,2,1,47,'08:20-08:50','3','2','6','19','Wednesday'),(105,2,1,41,'08:20-08:50','3','2','19','18','Wednesday'),(106,2,1,44,'08:20-08:50','3','2','20','16','Wednesday'),(107,2,1,42,'08:20-08:50','3','2','8','21','Wednesday'),(108,2,1,38,'08:20-08:50','3','2','2','15','Wednesday'),(109,2,1,39,'08:20-08:50','3','2','3','13','Wednesday'),(110,2,1,37,'08:20-08:50','3','2','1','12','Wednesday'),(111,2,1,38,'08:20-08:50','3','2','2','12','Thursday'),(112,2,1,44,'08:20-08:50','3','2','20','13','Thursday'),(113,2,1,37,'08:20-08:50','3','2','1','15','Thursday'),(114,2,1,40,'08:20-08:50','3','2','18','16','Thursday'),(115,2,1,39,'08:20-08:50','3','2','3','18','Thursday'),(116,2,1,47,'08:20-08:50','3','2','6','19','Thursday'),(117,2,1,45,'08:20-08:50','3','2','21','21','Thursday'),(118,2,1,46,'08:20-08:50','3','2','11','22','Thursday'),(119,2,1,49,'08:20-08:50','3','2','14','12','Friday'),(120,2,1,38,'08:20-08:50','3','2','2','13','Friday'),(121,2,1,37,'08:20-08:50','3','2','1','15','Friday'),(122,2,1,39,'08:20-08:50','3','2','3','16','Friday'),(123,2,1,44,'08:20-08:50','3','2','20','18','Friday'),(124,2,1,47,'08:20-08:50','3','2','6','19','Friday'),(125,2,1,45,'08:20-08:50','3','2','21','21','Friday'),(126,2,1,50,'08:20-08:50','3','2','13','22','Friday'),(127,3,1,75,'08:00-08:35','4','6','1','23','Monday'),(128,3,1,57,'08:00-08:35','5','8','1','23','Monday'),(129,3,1,53,'08:00-08:35','6','4','18','23','Monday'),(130,3,1,71,'08:00-08:35','7','5','1','23','Monday'),(131,3,1,64,'08:00-08:35','8','7','2','23','Monday'),(132,3,1,75,'08:35-09:10','4','6','1','24','Monday'),(133,3,1,57,'08:35-09:10','5','8','1','24','Monday'),(134,3,1,53,'08:35-09:10','6','4','18','24','Monday'),(135,3,1,71,'08:35-09:10','7','5','1','24','Monday'),(136,3,1,64,'08:35-09:10','8','7','2','24','Monday'),(137,3,1,51,'08:20-08:55','4','4','3','23','Monday'),(138,3,1,66,'08:55-09:30','4','3','2','24','Monday'),(139,3,1,76,'08:20-08:55','5','6','19','23','Monday'),(140,3,1,59,'08:20-08:55','6','8','1','23','Monday'),(141,3,1,71,'08:20-08:55','7','5','1','23','Monday'),(142,3,1,64,'08:20-08:55','8','7','2','23','Monday'),(143,3,1,52,'08:55-09:30','5','4','3','24','Monday'),(144,3,1,77,'08:55-09:30','6','6','19','24','Monday'),(145,3,1,63,'08:55-09:30','7','7','2','24','Monday'),(146,3,1,74,'08:55-09:30','8','5','3','24','Monday'),(147,3,1,67,'09:50-10:25','4','3','6','26','Monday'),(148,3,1,57,'09:50-10:25','5','8','1','26','Monday'),(150,3,1,72,'09:50-10:25','7','5','3','26','Monday'),(151,3,1,65,'09:50-10:25','8','7','6','26','Monday'),(152,3,1,75,'10:25-11:00','4','6','1','27','Monday'),(153,3,1,81,'10:25-11:00','5','8','18','27','Monday'),(154,3,1,69,'10:25-11:00','6','3','2','27','Monday'),(155,3,1,82,'09:50-10:25','6','4','20','26','Monday'),(156,3,1,54,'10:25-11:00','7','4','18','27','Monday'),(157,3,1,73,'10:25-11:00','8','5','1','27','Monday'),(158,3,1,75,'11:30-12:05','4','6','1','29','Monday'),(159,3,1,68,'11:30-12:05','5','3','2','29','Monday'),(160,3,1,58,'11:30-12:05','6','8','3','29','Monday'),(161,3,1,72,'11:30-12:05','7','5','3','29','Monday'),(162,3,1,55,'11:30-12:05','8','4','18','29','Monday'),(163,3,1,51,'12:05-12:40','4','4','3','30','Monday'),(164,3,1,57,'12:05-12:40','5','8','1','30','Monday'),(165,3,1,62,'12:05-12:40','6','7','6','30','Monday'),(166,3,1,78,'12:05-12:40','7','6','19','30','Monday'),(167,3,1,74,'12:05-12:40','8','5','3','30','Monday'),(168,3,1,83,'14:00-14:35','4','7','19','32','Monday'),(169,3,1,84,'14:00-14:35','5','3','6','32','Monday'),(170,3,1,58,'14:00-14:35','6','8','3','32','Monday'),(171,3,1,70,'14:00-14:35','7','3','20','32','Monday'),(172,3,1,85,'14:00-14:35','8','3','22','32','Monday'),(174,3,1,86,'14:35-15:10','5','8','20','33','Monday'),(175,3,1,88,'14:35-15:10','4','3','18','33','Monday'),(176,3,1,53,'14:35-15:10','6','4','18','33','Monday'),(177,3,1,89,'14:35-15:10','7','7','6','33','Monday'),(178,3,1,79,'14:35-15:10','8','6','19','33','Monday'),(179,3,1,75,'08:20-08:55','4','6','1','23','Tuesday'),(180,3,1,57,'08:20-08:55','5','8','1','23','Tuesday'),(181,3,1,69,'08:20-08:55','6','3','2','23','Tuesday'),(182,3,1,72,'08:20-08:55','7','5','3','23','Tuesday'),(183,3,1,64,'08:20-08:55','8','7','2','23','Tuesday'),(184,3,1,90,'08:55-09:30','4','7','20','24','Tuesday'),(185,3,1,81,'08:55-09:30','5','8','18','24','Tuesday'),(186,3,1,53,'08:55-09:30','6','4','18','24','Tuesday'),(187,3,1,78,'08:55-09:30','7','6','19','24','Tuesday'),(188,3,1,73,'08:55-09:30','8','5','1','24','Tuesday'),(189,3,1,66,'09:50-10:25','4','3','2','26','Tuesday'),(190,3,1,52,'09:50-10:25','5','4','3','26','Tuesday'),(191,3,1,59,'09:50-10:25','6','8','1','26','Tuesday'),(192,3,1,72,'09:50-10:25','7','5','3','26','Tuesday'),(193,3,1,65,'09:50-10:25','8','7','6','26','Tuesday'),(194,3,1,61,'10:25-11:00','4','7','22','27','Tuesday'),(195,3,1,84,'10:25-11:00','5','3','6','27','Tuesday'),(196,3,1,77,'10:25-11:00','6','6','19','27','Tuesday'),(197,3,1,54,'10:25-11:00','7','4','18','27','Tuesday'),(198,3,1,74,'10:25-11:00','8','5','3','27','Tuesday'),(199,3,1,83,'11:30-12:05','4','7','19','29','Tuesday'),(200,3,1,68,'11:30-12:05','5','3','2','29','Tuesday'),(201,3,1,59,'11:30-12:05','6','8','1','29','Tuesday'),(202,3,1,71,'11:30-12:05','7','5','1','29','Tuesday'),(203,3,1,55,'11:30-12:05','8','4','18','29','Tuesday'),(204,3,1,67,'12:05-12:40','4','3','6','30','Tuesday'),(205,3,1,52,'12:05-12:40','5','4','3','30','Tuesday'),(206,3,1,82,'12:05-12:40','6','4','20','30','Tuesday'),(207,3,1,93,'12:05-12:40','6','10','20','30','Tuesday'),(208,3,1,93,'09:50-10:25','6','10','20','26','Monday'),(209,3,1,63,'12:05-12:40','7','7','2','30','Tuesday'),(210,3,1,79,'12:05-12:40','8','6','19','30','Tuesday'),(211,3,1,88,'14:00-14:35','4','3','18','32','Tuesday'),(212,3,1,76,'14:00-14:35','5','6','19','32','Tuesday'),(213,3,1,58,'14:00-14:35','6','8','3','32','Tuesday'),(214,3,1,89,'14:00-14:35','7','7','6','32','Tuesday'),(215,3,1,95,'14:00-14:35','8','10','20','32','Tuesday'),(216,3,1,90,'14:35-15:10','4','7','20','33','Tuesday'),(217,3,1,51,'14:35-15:10','4','4','3','33','Tuesday'),(218,3,1,86,'14:35-15:10','5','8','20','33','Tuesday'),(219,3,1,62,'14:35-15:10','6','7','6','33','Tuesday'),(220,3,1,94,'14:35-15:10','7','10','20','33','Tuesday'),(221,3,1,96,'14:35-15:10','8','3','15','33','Tuesday'),(222,3,1,66,'08:20-08:55','4','3','2','23','Wednesday'),(223,3,1,52,'08:20-08:55','5','4','3','23','Wednesday'),(224,3,1,77,'08:20-08:55','6','6','19','23','Wednesday'),(225,3,1,63,'08:20-08:55','7','7','2','23','Wednesday'),(226,3,1,73,'08:20-08:55','8','5','1','23','Wednesday'),(227,3,1,75,'08:55-09:30','4','6','1','24','Wednesday'),(228,3,1,57,'08:55-09:30','5','8','1','24','Wednesday'),(229,3,1,69,'08:55-09:30','6','3','2','24','Wednesday'),(230,3,1,72,'08:55-09:30','7','5','3','24','Wednesday'),(231,3,1,64,'08:55-09:30','8','7','2','24','Wednesday'),(232,3,1,75,'09:50-10:25','4','6','1','26','Wednesday'),(233,3,1,57,'09:50-10:25','5','8','1','26','Wednesday'),(234,3,1,62,'09:50-10:25','6','7','6','26','Wednesday'),(235,3,1,97,'09:50-10:25','7','3','15','26','Wednesday'),(236,3,1,74,'09:50-10:25','8','5','3','26','Wednesday'),(237,3,1,83,'10:25-11:00','4','7','19','27','Wednesday'),(238,3,1,98,'10:25-11:00','5','6','15','27','Wednesday'),(239,3,1,59,'10:25-11:00','6','8','1','27','Wednesday'),(240,3,1,71,'10:25-11:00','7','5','1','27','Wednesday'),(241,3,1,55,'10:25-11:00','8','4','18','27','Wednesday'),(242,3,1,99,'11:30-12:05','4','5','15','29','Wednesday'),(243,3,1,68,'11:30-12:05','5','3','2','29','Wednesday'),(244,3,1,59,'11:30-12:05','6','8','1','29','Wednesday'),(245,3,1,78,'11:30-12:05','7','6','19','29','Wednesday'),(246,3,1,96,'11:30-12:05','8','3','15','29','Wednesday'),(247,3,1,104,'11:30-12:05','8','10','15','29','Wednesday'),(248,3,1,88,'12:05-12:40','4','3','18','30','Wednesday'),(249,3,1,76,'12:05-12:40','5','6','19','30','Wednesday'),(250,3,1,53,'12:05-12:40','6','4','18','30','Wednesday'),(251,3,1,89,'12:05-12:40','7','7','6','30','Wednesday'),(252,3,1,73,'12:05-12:40','8','5','1','30','Wednesday'),(253,3,1,51,'14:00-14:35','4','4','3','32','Wednesday'),(254,3,1,81,'14:00-14:35','5','8','18','32','Wednesday'),(255,3,1,62,'14:00-14:35','6','7','6','32','Wednesday'),(256,3,1,108,'14:00-14:35','7','10','22','32','Wednesday'),(257,3,1,74,'14:00-14:35','8','5','3','32','Wednesday'),(258,3,1,67,'14:35-15:10','4','3','6','33','Wednesday'),(259,3,1,101,'14:35-15:10','5','10','15','33','Wednesday'),(260,3,1,58,'14:35-15:10','6','8','3','33','Wednesday'),(261,3,1,54,'14:35-15:10','7','4','18','33','Wednesday'),(262,3,1,79,'14:35-15:10','8','6','19','33','Wednesday'),(263,3,1,51,'08:20-08:55','4','4','3','23','Thursday'),(264,3,1,68,'08:20-08:55','5','3','2','23','Thursday'),(265,3,1,59,'08:20-08:55','6','8','1','23','Thursday'),(266,3,1,71,'08:20-08:55','7','5','1','23','Thursday'),(267,3,1,64,'08:20-08:55','8','7','2','23','Thursday'),(268,3,1,66,'08:55-09:30','4','3','2','24','Thursday'),(269,3,1,81,'08:55-09:30','5','8','18','24','Thursday'),(270,3,1,53,'08:55-09:30','6','4','18','24','Thursday'),(271,3,1,63,'08:55-09:30','7','7','2','24','Thursday'),(272,3,1,79,'08:55-09:30','8','6','19','24','Thursday'),(273,3,1,75,'09:50-10:25','4','6','1','26','Thursday'),(274,3,1,52,'09:50-10:25','5','4','3','26','Thursday'),(275,3,1,58,'09:50-10:25','6','8','3','26','Thursday'),(276,3,1,103,'09:50-10:25','7','10','15','26','Thursday'),(277,3,1,95,'09:50-10:25','8','10','20','26','Thursday'),(278,3,1,51,'10:25-11:00','4','4','3','27','Thursday'),(279,3,1,57,'10:25-11:00','5','8','1','27','Thursday'),(280,3,1,69,'10:25-11:00','6','3','2','27','Thursday'),(281,3,1,78,'10:25-11:00','7','6','19','27','Thursday'),(282,3,1,73,'10:25-11:00','8','5','1','27','Thursday'),(283,3,1,88,'11:30-12:05','4','3','18','29','Thursday'),(284,3,1,92,'11:30-12:05','5','10','20','29','Thursday'),(285,3,1,77,'11:30-12:05','6','6','19','29','Thursday'),(286,3,1,71,'11:30-12:05','7','5','1','29','Thursday'),(287,3,1,65,'11:30-12:05','8','7','6','29','Thursday'),(288,3,1,90,'12:05-12:40','4','7','20','30','Thursday'),(289,3,1,91,'12:05-12:40','4','10','20','30','Thursday'),(290,3,1,76,'12:05-12:40','5','6','19','30','Thursday'),(291,3,1,107,'12:05-12:40','6','10','22','30','Thursday'),(292,3,1,108,'12:05-12:40','7','10','22','30','Thursday'),(293,3,1,74,'12:05-12:40','8','5','3','30','Thursday'),(294,3,1,83,'14:00-14:35','4','7','19','32','Thursday'),(295,3,1,101,'14:00-14:35','5','10','15','32','Thursday'),(296,3,1,58,'14:00-14:35','6','8','3','32','Thursday'),(297,3,1,72,'14:00-14:35','7','5','3','32','Thursday'),(298,3,1,55,'14:00-14:35','8','4','18','32','Thursday'),(299,3,1,67,'14:35-15:10','4','3','6','33','Thursday'),(300,3,1,106,'14:35-15:10','5','10','22','33','Thursday'),(301,3,1,54,'14:35-15:10','7','4','18','33','Thursday'),(302,3,1,102,'14:35-15:10','6','10','15','33','Thursday'),(303,3,1,104,'14:35-15:10','8','10','15','33','Thursday'),(304,3,1,75,'08:20-08:55','4','6','1','23','Friday'),(305,3,1,57,'08:20-08:55','5','8','1','23','Friday'),(306,3,1,75,'08:55-09:30','4','6','1','24','Friday'),(307,3,1,57,'08:55-09:30','5','8','1','24','Friday'),(308,3,1,69,'08:55-09:30','6','3','2','24','Friday'),(309,3,1,72,'08:55-09:30','7','5','3','24','Friday'),(310,3,1,64,'08:55-09:30','8','7','2','24','Friday'),(311,3,1,110,'08:20-08:55','4','10','14','23','Friday'),(312,3,1,111,'08:20-08:55','5','10','14','23','Friday'),(313,3,1,112,'08:20-08:55','6','10','14','23','Friday'),(314,3,1,113,'08:20-08:55','7','10','14','23','Friday'),(315,3,1,114,'08:20-08:55','8','10','14','23','Friday'),(316,3,1,51,'09:50-10:25','4','4','3','26','Friday'),(317,3,1,76,'09:50-10:25','5','6','19','26','Friday'),(318,3,1,107,'09:50-10:25','6','10','22','26','Friday'),(319,3,1,94,'09:50-10:25','7','10','20','26','Friday'),(320,3,1,73,'09:50-10:25','8','5','1','26','Friday'),(321,3,1,51,'10:25-11:00','4','4','3','27','Friday'),(322,3,1,88,'10:25-11:00','4','3','18','27','Friday'),(323,3,1,52,'10:25-11:00','5','4','3','27','Friday'),(324,3,1,59,'10:25-11:00','6','8','1','27','Friday'),(325,3,1,71,'10:25-11:00','7','5','1','27','Friday'),(326,3,1,79,'10:25-11:00','8','6','19','27','Friday'),(327,3,1,83,'11:30-12:05','4','7','19','29','Friday'),(328,3,1,68,'11:30-12:05','5','3','2','29','Friday'),(329,3,1,58,'11:30-12:05','6','8','3','29','Friday'),(330,3,1,54,'11:30-12:05','7','4','18','29','Friday'),(331,3,1,74,'11:30-12:05','8','5','3','29','Friday'),(332,3,1,99,'12:05-12:40','4','5','15','30','Friday'),(333,3,1,100,'12:05-12:40','4','10','15','30','Friday'),(334,3,1,52,'12:05-12:40','5','4','3','30','Friday'),(335,3,1,77,'12:05-12:40','6','6','19','30','Friday'),(336,3,1,71,'12:05-12:40','7','5','1','30','Friday'),(337,3,1,95,'12:05-12:40','8','10','20','30','Friday'),(338,3,1,66,'14:00-14:35','4','3','2','32','Friday'),(339,3,1,106,'14:00-14:35','5','10','22','32','Friday'),(340,3,1,53,'14:00-14:35','6','4','18','32','Friday'),(341,3,1,78,'14:00-14:35','7','6','19','32','Friday'),(342,3,1,73,'14:00-14:35','8','5','1','32','Friday'),(343,3,1,55,'14:35-15:10','8','4','18','33','Friday'),(344,3,1,63,'14:35-15:10','7','7','2','33','Friday'),(345,3,1,82,'14:35-15:10','6','4','20','33','Friday'),(346,3,1,81,'14:35-15:10','5','8','18','33','Friday'),(347,3,1,91,'14:35-15:10','4','10','20','33','Friday'),(348,3,1,101,'10:25-11:00','5','10','15','27','Wednesday'),(349,3,1,100,'11:30-12:05','4','10','15','29','Wednesday'),(350,3,1,104,'14:35-15:10','8','10','15','33','Tuesday'),(351,3,1,103,'09:50-10:25','7','10','15','26','Wednesday'),(352,3,1,91,'08:55-09:30','4','10','20','24','Tuesday'),(353,3,1,92,'14:35-15:10','5','10','20','33','Tuesday'),(354,3,1,94,'14:00-14:35','7','10','20','32','Monday'),(355,3,1,92,'14:35-15:10','5','10','20','33','Monday'),(356,3,1,93,'14:35-15:10','6','10','20','33','Friday'),(357,3,1,109,'14:00-14:35','8','10','22','32','Monday'),(358,3,1,105,'10:25-11:00','4','10','22','27','Tuesday'),(359,5,2,115,'08:00-08:30','1','11','24','44','Monday'),(360,5,2,117,'08:00-08:30','2','12','24','44','Monday'),(361,5,2,116,'08:00-08:30','3','11','24','44','Monday'),(362,5,2,118,'08:00-08:30','3','12','25','44','Monday'),(363,4,1,121,'09:20-09:50','1','18','23','36','Monday'),(364,8,4,146,'09:50-10:20','1','24','52','62','Monday'),(365,4,1,123,'11:20-11:50','1','18','31','39','Monday'),(366,8,4,151,'11:50-12:20','1','26','53','65','Monday'),(367,4,1,125,'14:40-15:40','1','18','33','43','Monday'),(368,4,1,122,'09:20-09:50','1','18','30','36','Tuesday'),(369,4,1,124,'09:50-10:20','1','18','32','37','Tuesday'),(370,4,1,121,'11:20-11:50','1','18','23','39','Tuesday'),(371,4,1,126,'11:50-12:20','1','18','34','40','Tuesday'),(372,4,1,127,'14:40-15:40','1','18','35','43','Tuesday'),(373,4,1,121,'09:20-09:50','1','18','23','36','Wednesday'),(374,4,1,128,'09:50-10:20','1','18','36','37','Wednesday'),(375,4,1,122,'11:20-11:50','1','18','30','39','Wednesday'),(376,4,1,123,'11:50-12:20','1','18','31','40','Wednesday'),(377,4,1,129,'14:40-15:40','1','18','37','43','Wednesday'),(378,4,1,122,'09:20-09:50','1','18','30','36','Thursday'),(379,4,1,124,'09:50-10:20','1','18','32','37','Thursday'),(380,4,1,126,'11:20-11:50','1','18','34','39','Thursday'),(381,4,1,121,'11:50-12:20','1','18','23','40','Thursday'),(382,4,1,130,'14:40-15:40','1','18','38','43','Thursday'),(383,4,1,131,'09:20-09:50','1','18','39','36','Friday'),(384,4,1,121,'09:50-10:20','1','18','23','37','Friday'),(385,4,1,122,'11:20-11:50','1','18','30','39','Friday'),(386,4,1,124,'11:50-12:20','1','18','32','40','Friday'),(387,4,1,127,'14:40-15:40','1','18','35','43','Friday'),(388,4,1,132,'14:40-15:40','1','19','35','43','Friday'),(389,4,1,132,'14:40-15:40','1','19','35','43','Tuesday'),(390,1,1,133,'12:00-12:30','1','1','40','8','Wednesday'),(391,1,1,134,'14:00-15:00','1','1','41','10','Wednesday'),(392,1,1,134,'14:00-15:00','1','1','41','10','Tuesday'),(393,1,1,134,'14:00-15:00','1','1','41','10','Thursday'),(394,1,1,134,'15:00-16:00','1','1','41','11','Monday'),(395,1,1,136,'15:00-16:00','2','1','43','11','Monday'),(396,1,1,135,'12:00-12:30','2','1','42','8','Wednesday'),(397,1,1,136,'14:00-15:00','2','1','43','10','Wednesday'),(398,1,1,136,'14:00-15:00','2','1','43','10','Thursday'),(399,1,1,137,'09:50-10:20','2','1','44','4','Tuesday'),(400,1,1,138,'09:50-10:20','1','1','45','4','Tuesday'),(401,3,1,63,'14:00-14:35','7','7','2','32','Monday'),(410,8,4,146,'08:30-09:00','1','24','52','59','Monday'),(411,8,4,153,'08:30-09:00','2','25','54','59','Monday'),(412,8,4,149,'08:30-09:00','3','26','52','59','Monday'),(413,8,4,148,'09:00-09:30','1','24','54','60','Monday'),(414,8,4,150,'09:00-09:30','2','26','53','60','Monday'),(415,8,4,152,'09:00-09:30','3','25','53','60','Monday'),(416,8,4,149,'09:50-10:20','3','26','52','62','Monday'),(417,8,4,152,'10:20-10:50','3','25','53','63','Monday'),(418,8,4,147,'11:50-12:20','2','24','52','65','Monday'),(419,8,4,154,'11:50-12:20','3','25','54','65','Monday'),(420,8,4,148,'12:20-12:50','1','24','54','66','Monday'),(421,8,4,153,'12:20-12:50','2','25','54','66','Monday'),(422,8,4,149,'12:20-12:50','3','26','52','66','Monday');
/*!40000 ALTER TABLE `timetabler` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `f_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `l_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Milcah','Kelly','milkayo','$2y$13$9VdafyZewTerdj9GG9GOsu3AARirc43b1d.xLvcx26.oyGPF8pSMS','milcahkelly6@gmail.com','1'),(2,'Josiah','Birai','maestrojosiah','$2y$13$gn52IWmQqfn9MHQ6eRnvl.gdkGoikSzT3SwVONyy9OQGqAERNEdiK','maestrojosiah@gmail.com','1'),(3,'Dennis','Mwanzia','mwasbid','$2y$13$62TVZPGX2lS0hmkM32/6OeLHqcCvGWHDO9V50ATpiuAKsEZwPnMa.','mwasbid@gmail.com','1'),(4,'Joyce','Birai','jbirai','$2y$13$AmZLUTzoi4sUjODSmvcEUeKY80NSsThUBN2j5FeaFjIO2sh.153gq','jbirai01@gmail.com','1');
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

-- Dump completed on 2018-03-09 10:32:05
