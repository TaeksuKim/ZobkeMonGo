-- MySQL dump 10.13  Distrib 5.1.73, for redhat-linux-gnu (x86_64)
--
-- Host: localhost    Database: dolicoli
-- ------------------------------------------------------
-- Server version	5.1.73-log

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
-- Table structure for table `POKE_MONSTER`
--

DROP TABLE IF EXISTS `POKE_MONSTER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `POKE_MONSTER` (
  `MONSTER_ID` int(11) NOT NULL,
  `MONSTER_NUMBER` varchar(10) COLLATE utf8_bin NOT NULL,
  `MONSTER_NAME` varchar(255) COLLATE utf8_bin NOT NULL,
  `MONSTER_IMAGE` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`MONSTER_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `POKE_MONSTER_OWNER`
--

DROP TABLE IF EXISTS `POKE_MONSTER_OWNER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `POKE_MONSTER_OWNER` (
  `MONSTER_ID` int(11) NOT NULL,
  `LOGIN_ID` varchar(20) COLLATE utf8_bin NOT NULL,
  `EDIT_DATE` datetime NOT NULL,
  PRIMARY KEY (`MONSTER_ID`,`LOGIN_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `POKE_MONSTER_OWNER_TEMP`
--

DROP TABLE IF EXISTS `POKE_MONSTER_OWNER_TEMP`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `POKE_MONSTER_OWNER_TEMP` (
  `MONSTER_ID` int(11) NOT NULL,
  `LOGIN_ID` varchar(20) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`MONSTER_ID`,`LOGIN_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `POKE_USER`
--

DROP TABLE IF EXISTS `POKE_USER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `POKE_USER` (
  `USER_ID` int(11) NOT NULL AUTO_INCREMENT,
  `LOGIN_ID` varchar(20) COLLATE utf8_bin NOT NULL,
  `LOGIN_PASSWORD` varchar(100) COLLATE utf8_bin NOT NULL,
  `USER_NAME` varchar(100) COLLATE utf8_bin NOT NULL,
  `USER_KEY` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`USER_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `POKE_USER_TEMP`
--

DROP TABLE IF EXISTS `POKE_USER_TEMP`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `POKE_USER_TEMP` (
  `LOGIN_ID` varchar(20) COLLATE utf8_bin NOT NULL,
  `LOGIN_PASSWORD` varchar(100) COLLATE utf8_bin NOT NULL,
  `USER_NAME` varchar(100) COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-02-18 21:52:27
