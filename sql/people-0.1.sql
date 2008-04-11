-- MySQL dump 10.10
--
-- Host: localhost    Database: shifts
-- ------------------------------------------------------
-- Server version       5.0.22-Debian_0ubuntu6.06.9-log

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
-- Table structure for table `PeopleIDSequence`
--

DROP TABLE IF EXISTS `PeopleIDSequence`;
CREATE TABLE `PeopleIDSequence` (
  `id` bigint(20) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `PeopleObject`
--

DROP TABLE IF EXISTS `PeopleObject`;
CREATE TABLE `PeopleObject` (
  `people_id` bigint(20) NOT NULL default '0',
  `people_classname` varchar(255) NOT NULL default '',
  `people_version` bigint(20) NOT NULL default '0',
  `people_modified` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`people_id`),
  KEY `modified` (`people_modified`),
  KEY `classname` USING BTREE (`people_classname`),
  KEY `version` (`people_version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
