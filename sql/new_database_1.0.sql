-- MySQL dump 10.9
--
-- Host: localhost    Database: pieter2
-- ------------------------------------------------------
-- Server version	4.1.10a

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

--
-- Table structure for table `ICECart`
--

DROP TABLE IF EXISTS `ICECart`;
CREATE TABLE `ICECart` (
  `people_id` bigint(20) NOT NULL default '0',
  `customer` bigint(20) default NULL,
  PRIMARY KEY  (`people_id`),
  UNIQUE KEY `customer` TYPE BTREE (`customer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ICECart`
--


/*!40000 ALTER TABLE `ICECart` DISABLE KEYS */;
LOCK TABLES `ICECart` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `ICECart` ENABLE KEYS */;

--
-- Table structure for table `ICECartLine`
--

DROP TABLE IF EXISTS `ICECartLine`;
CREATE TABLE `ICECartLine` (
  `people_id` bigint(20) NOT NULL default '0',
  `cart` bigint(20) NOT NULL default '0',
  `product` bigint(20) NOT NULL default '0',
  `description` longtext NOT NULL,
  PRIMARY KEY  (`people_id`),
  KEY `cart` (`cart`),
  KEY `product` (`product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ICECartLine`
--


/*!40000 ALTER TABLE `ICECartLine` DISABLE KEYS */;
LOCK TABLES `ICECartLine` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `ICECartLine` ENABLE KEYS */;

--
-- Table structure for table `ICECompany`
--

DROP TABLE IF EXISTS `ICECompany`;
CREATE TABLE `ICECompany` (
  `people_id` bigint(20) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`people_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ICECompany`
--


/*!40000 ALTER TABLE `ICECompany` DISABLE KEYS */;
LOCK TABLES `ICECompany` WRITE;
INSERT INTO `ICECompany` VALUES (797,'De winkel zelf'),(793,'ETC Bestware'),(791,'Ingram Micro'),(795,'Techdata');
UNLOCK TABLES;
/*!40000 ALTER TABLE `ICECompany` ENABLE KEYS */;

--
-- Table structure for table `ICEDeliveryAddress`
--

DROP TABLE IF EXISTS `ICEDeliveryAddress`;
CREATE TABLE `ICEDeliveryAddress` (
  `people_id` bigint(20) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `street` varchar(255) NOT NULL default '',
  `number` varchar(32) NOT NULL default '',
  `extension` varchar(32) NOT NULL default '',
  `city` varchar(255) NOT NULL default '',
  `zip` varchar(32) NOT NULL default '',
  `country` varchar(2) default NULL,
  `directions` longtext NOT NULL,
  PRIMARY KEY  (`people_id`),
  KEY `name` (`name`),
  KEY `street` (`street`),
  KEY `number` (`number`),
  KEY `extension` (`extension`),
  KEY `city` (`city`),
  KEY `zip` (`zip`),
  KEY `country` (`country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ICEDeliveryAddress`
--


/*!40000 ALTER TABLE `ICEDeliveryAddress` DISABLE KEYS */;
LOCK TABLES `ICEDeliveryAddress` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `ICEDeliveryAddress` ENABLE KEYS */;

--
-- Table structure for table `ICEInvoiceAddress`
--

DROP TABLE IF EXISTS `ICEInvoiceAddress`;
CREATE TABLE `ICEInvoiceAddress` (
  `people_id` bigint(20) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `street` varchar(255) NOT NULL default '',
  `number` varchar(32) NOT NULL default '',
  `extension` varchar(32) NOT NULL default '',
  `city` varchar(255) NOT NULL default '',
  `zip` varchar(32) NOT NULL default '',
  `country` varchar(2) default NULL,
  PRIMARY KEY  (`people_id`),
  KEY `name` (`name`),
  KEY `street` (`street`),
  KEY `number` (`number`),
  KEY `extension` (`extension`),
  KEY `city` (`city`),
  KEY `zip` (`zip`),
  KEY `country` (`country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ICEInvoiceAddress`
--


/*!40000 ALTER TABLE `ICEInvoiceAddress` DISABLE KEYS */;
LOCK TABLES `ICEInvoiceAddress` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `ICEInvoiceAddress` ENABLE KEYS */;

--
-- Table structure for table `ICECustomerPaymentMethod`
--

DROP TABLE IF EXISTS `ICECustomerPaymentMethod`;
CREATE TABLE `ICECustomerPaymentMethod` (
  `people_id` bigint(20) NOT NULL default '0',
  `customer` bigint(20) NOT NULL default '0',
  `paymentmethod` bigint(20) NOT NULL default '0',
  KEY `customer` (`customer`),
  KEY `paymentmethod` (`paymentmethod`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ICECustomerPaymentMethod`
--


/*!40000 ALTER TABLE `ICECustomerPaymentMethod` DISABLE KEYS */;
LOCK TABLES `ICECustomerPaymentMethod` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `ICECustomerPaymentMethod` ENABLE KEYS */;

--
-- Table structure for table `ICEPerson`
--

DROP TABLE IF EXISTS `ICEPerson`;
CREATE TABLE `ICEPerson` (
  `people_id` bigint(20) NOT NULL default '0',
  `name` varchar(255) default NULL,
  `sex` char(1) character set ascii default NULL COMMENT 'enum ''m'', ''f''',
  PRIMARY KEY  (`people_id`),
  KEY `name` (`name`),
  KEY `sex` (`sex`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ICEPerson`
--


/*!40000 ALTER TABLE `ICEPerson` DISABLE KEYS */;
LOCK TABLES `ICEPerson` WRITE;
INSERT INTO `ICEPerson` VALUES (1,'Super User','m');
UNLOCK TABLES;
/*!40000 ALTER TABLE `ICEPerson` ENABLE KEYS */;

--
-- Table structure for table `ICEObjectUnit`
--

DROP TABLE IF EXISTS `ICEObjectUnit`;
CREATE TABLE `ICEObjectUnit` (
  `people_id` bigint(20) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`people_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ICEObjectUnit`
--


/*!40000 ALTER TABLE `ICEObjectUnit` DISABLE KEYS */;
LOCK TABLES `ICEObjectUnit` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `ICEObjectUnit` ENABLE KEYS */;

--
-- Table structure for table `ICEOrder`
--

DROP TABLE IF EXISTS `ICEOrder`;
CREATE TABLE `ICEOrder` (
  `people_id` bigint(20) NOT NULL default '0',
  `number` bigint(20) NOT NULL default '0',
  `customer` bigint(20) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `reference` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`people_id`),
  UNIQUE KEY `number` (`number`),
  KEY `customer` (`customer`),
  KEY `date` (`date`),
  KEY `reference` (`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ICEOrder`
--


/*!40000 ALTER TABLE `ICEOrder` DISABLE KEYS */;
LOCK TABLES `ICEOrder` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `ICEOrder` ENABLE KEYS */;

--
-- Table structure for table `ICEOrderDetail`
--

DROP TABLE IF EXISTS `ICEOrderDetail`;
CREATE TABLE `ICEOrderDetail` (
  `people_id` bigint(20) NOT NULL default '0',
  `count` bigint(20) NOT NULL default '0',
  `order` bigint(20) NOT NULL default '0',
  `price` decimal(10,3) NOT NULL default '0.000',
  `special_bid` varchar(255) NOT NULL default '',
  `description` longtext NOT NULL,
  PRIMARY KEY  (`people_id`),
  KEY `count` (`count`),
  KEY `order` (`order`),
  KEY `price` (`price`),
  KEY `special_bid` (`special_bid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ICEOrderDetail`
--


/*!40000 ALTER TABLE `ICEOrderDetail` DISABLE KEYS */;
LOCK TABLES `ICEOrderDetail` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `ICEOrderDetail` ENABLE KEYS */;

--
-- Table structure for table `ordersSequence`
--

DROP TABLE IF EXISTS `ordersSequence`;
CREATE TABLE `ordersSequence` (
  `id` bigint(20) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ordersSequence`
--


/*!40000 ALTER TABLE `ordersSequence` DISABLE KEYS */;
LOCK TABLES `ordersSequence` WRITE;
INSERT INTO `ordersSequence` VALUES (1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `ordersSequence` ENABLE KEYS */;

--
-- Table structure for table `ICEProductDescription`
--

DROP TABLE IF EXISTS `ICEProductDescription`;
CREATE TABLE `ICEProductDescription` (
  `people_id` bigint(20) NOT NULL default '0',
  `ProdId` varchar(32) NOT NULL default '',
  `Vendor` varchar(32) NOT NULL default '',
  `Language` varchar(7) default NULL,
  `Image` varchar(255) NOT NULL default '',
  `Naam` varchar(255) NOT NULL default '',
  `Koms` varchar(255) NOT NULL default '',
  `Loms` longtext NOT NULL,
  PRIMARY KEY  (`people_id`),
  UNIQUE KEY `dprodid` (`ProdId`,`Vendor`,`Language`),
  KEY `Vendor` (`Vendor`),
  KEY `Language` (`Language`),
  KEY `Image` (`Image`),
  KEY `Naam` (`Naam`),
  KEY `Koms` (`Koms`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ICEProductDescription`
--


/*!40000 ALTER TABLE `ICEProductDescription` DISABLE KEYS */;
LOCK TABLES `ICEProductDescription` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `ICEProductDescription` ENABLE KEYS */;

--
-- Table structure for table `ICESupplier`
--

DROP TABLE IF EXISTS `ICESupplier`;
CREATE TABLE `ICESupplier` (
  `people_id` bigint(20) NOT NULL default '0',
  `code` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`people_id`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ICESupplier`
--


/*!40000 ALTER TABLE `ICESupplier` DISABLE KEYS */;
LOCK TABLES `ICESupplier` WRITE;
INSERT INTO `ICESupplier` VALUES (794,'BW'),(798,'eigen'),(792,'IM'),(796,'TD');
UNLOCK TABLES;
/*!40000 ALTER TABLE `ICESupplier` ENABLE KEYS */;

--
-- Table structure for table `ICEVendor`
--

DROP TABLE IF EXISTS `ICEVendor`;
CREATE TABLE `ICEVendor` (
  `people_id` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`people_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ICEVendor`
--


/*!40000 ALTER TABLE `ICEVendor` DISABLE KEYS */;
LOCK TABLES `ICEVendor` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `ICEVendor` ENABLE KEYS */;

--
-- Table structure for table `ICEVendorCanonicalName`
--

DROP TABLE IF EXISTS `ICEVendorCanonicalName`;
CREATE TABLE `ICEVendorCanonicalName` (
  `people_id` bigint(20) NOT NULL default '0',
  `vendor` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`people_id`),
  KEY `vendor` (`vendor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ICEVendorCanonicalName`
--


/*!40000 ALTER TABLE `ICEVendorCanonicalName` DISABLE KEYS */;
LOCK TABLES `ICEVendorCanonicalName` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `ICEVendorCanonicalName` ENABLE KEYS */;

--
-- Table structure for table `PeopleAObject`
--

DROP TABLE IF EXISTS `PeopleAObject`;
CREATE TABLE `PeopleAObject` (
  `people_id` bigint(20) NOT NULL default '0',
  `people_owner` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`people_id`),
  KEY `owner` (`people_owner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `PeopleAObject`
--


/*!40000 ALTER TABLE `PeopleAObject` DISABLE KEYS */;
LOCK TABLES `PeopleAObject` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `PeopleAObject` ENABLE KEYS */;

--
-- Table structure for table `PeopleConnection`
--

DROP TABLE IF EXISTS `PeopleConnection`;
CREATE TABLE `PeopleConnection` (
  `source` bigint(20) NOT NULL default '0',
  `destination` bigint(20) NOT NULL default '0',
  `signal` char(255) NOT NULL default '',
  `slot` char(255) NOT NULL default '',
  KEY `source` (`source`),
  KEY `signal` (`signal`),
  KEY `destination` (`destination`),
  KEY `slot` (`slot`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `PeopleConnection`
--


/*!40000 ALTER TABLE `PeopleConnection` DISABLE KEYS */;
LOCK TABLES `PeopleConnection` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `PeopleConnection` ENABLE KEYS */;

--
-- Table structure for table `PeopleFacet`
--

DROP TABLE IF EXISTS `PeopleFacet`;
CREATE TABLE `PeopleFacet` (
  `people_id` bigint(20) NOT NULL default '0',
  `people_object` bigint(20) NOT NULL default '0',
  `people_owner` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`people_id`),
  KEY `object` (`people_object`),
  KEY `owner` (`people_owner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `PeopleFacet`
--


/*!40000 ALTER TABLE `PeopleFacet` DISABLE KEYS */;
LOCK TABLES `PeopleFacet` WRITE;
INSERT INTO `PeopleFacet` VALUES (2,1,1),(792,791,791),(794,793,793),(796,795,795),(798,797,797);
UNLOCK TABLES;
/*!40000 ALTER TABLE `PeopleFacet` ENABLE KEYS */;

--
-- Table structure for table `PeopleFacetGroup`
--

DROP TABLE IF EXISTS `PeopleFacetGroup`;
CREATE TABLE `PeopleFacetGroup` (
  `people_id` bigint(20) NOT NULL default '0',
  `name` varchar(255) default NULL,
  PRIMARY KEY  (`people_id`),
  UNIQUE KEY `name` TYPE BTREE (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `PeopleFacetGroup`
--


/*!40000 ALTER TABLE `PeopleFacetGroup` DISABLE KEYS */;
LOCK TABLES `PeopleFacetGroup` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `PeopleFacetGroup` ENABLE KEYS */;

--
-- Table structure for table `PeopleFacetUser`
--

DROP TABLE IF EXISTS `PeopleFacetUser`;
CREATE TABLE `PeopleFacetUser` (
  `people_id` bigint(20) NOT NULL default '0',
  `is_superuser` tinyint(4) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `pass` varchar(255) default NULL,
  `egid` bigint(20) default NULL,
  PRIMARY KEY  (`people_id`),
  UNIQUE KEY `name` TYPE BTREE (`name`),
  KEY `is_superuser` (`is_superuser`),
  KEY `pass` (`pass`),
  KEY `egid` (`egid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `PeopleFacetUser`
--


/*!40000 ALTER TABLE `PeopleFacetUser` DISABLE KEYS */;
LOCK TABLES `PeopleFacetUser` WRITE;
INSERT INTO `PeopleFacetUser` VALUES (2,1,'root','root',NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `PeopleFacetUser` ENABLE KEYS */;

--
-- Table structure for table `PeopleGroupUser`
--

DROP TABLE IF EXISTS `PeopleGroupUser`;
CREATE TABLE `PeopleGroupUser` (
  `people_id` bigint(20) NOT NULL default '0',
  `group` bigint(20) NOT NULL default '0',
  `user` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`people_id`),
  KEY `group` (`group`),
  KEY `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `PeopleGroupUser`
--


/*!40000 ALTER TABLE `PeopleGroupUser` DISABLE KEYS */;
LOCK TABLES `PeopleGroupUser` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `PeopleGroupUser` ENABLE KEYS */;

--
-- Table structure for table `PeopleIDSequence`
--

DROP TABLE IF EXISTS `PeopleIDSequence`;
CREATE TABLE `PeopleIDSequence` (
  `id` bigint(20) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `PeopleIDSequence`
--


/*!40000 ALTER TABLE `PeopleIDSequence` DISABLE KEYS */;
LOCK TABLES `PeopleIDSequence` WRITE;
INSERT INTO `PeopleIDSequence` VALUES (801);
UNLOCK TABLES;
/*!40000 ALTER TABLE `PeopleIDSequence` ENABLE KEYS */;

--
-- Table structure for table `PeopleObject`
--

DROP TABLE IF EXISTS `PeopleObject`;
CREATE TABLE `PeopleObject` (
  `people_id` bigint(20) NOT NULL default '0',
  `people_uid` bigint(20) NOT NULL default '0',
  `people_gid` bigint(20) default NULL,
  `people_gperms` tinyint(4) NOT NULL default '0',
  `people_wperms` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`people_id`),
  KEY `uid` (`people_uid`),
  KEY `gid` (`people_gid`),
  KEY `gperms` (`people_gperms`),
  KEY `wperms` (`people_wperms`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `PeopleObject`
--


/*!40000 ALTER TABLE `PeopleObject` DISABLE KEYS */;
LOCK TABLES `PeopleObject` WRITE;
INSERT INTO `PeopleObject` VALUES (1,2,NULL,0,1),(791,2,NULL,2,2),(793,2,NULL,2,2),(795,2,NULL,2,2),(797,2,NULL,2,2);
UNLOCK TABLES;
/*!40000 ALTER TABLE `PeopleObject` ENABLE KEYS */;

--
-- Table structure for table `PeoplePObject`
--

DROP TABLE IF EXISTS `PeoplePObject`;
CREATE TABLE `PeoplePObject` (
  `people_id` bigint(20) NOT NULL default '0',
  `people_classname` varchar(255) NOT NULL default '',
  `people_version` bigint(20) NOT NULL default '0',
  `people_modified` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `people_modified_by` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`people_id`),
  KEY `modified` (`people_modified`),
  KEY `classname` TYPE BTREE (`people_classname`),
  KEY `modifiedBy` (`people_modified_by`),
  KEY `version` (`people_version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `PeoplePObject`
--


/*!40000 ALTER TABLE `PeoplePObject` DISABLE KEYS */;
LOCK TABLES `PeoplePObject` WRITE;
INSERT INTO `PeoplePObject` VALUES (1,'ICEPerson',9,'2005-11-18 21:43:38',2),(2,'PeopleFacetUser',3,'2006-05-16 13:22:17',2),(791,'ICECompany',1,'2006-05-16 14:08:17',2),(792,'ICESupplier',2,'2006-05-16 14:18:15',2),(793,'ICECompany',1,'2006-05-16 14:34:51',2),(794,'ICESupplier',1,'2006-05-16 14:34:56',2),(795,'ICECompany',1,'2006-05-16 14:35:08',2),(796,'ICESupplier',1,'2006-05-16 14:35:13',2),(797,'ICECompany',1,'2006-05-16 14:35:43',2),(798,'ICESupplier',1,'2006-05-16 14:35:51',2);
UNLOCK TABLES;
/*!40000 ALTER TABLE `PeoplePObject` ENABLE KEYS */;

--
-- Table structure for table `countryname`
--

DROP TABLE IF EXISTS `countryname`;
CREATE TABLE `countryname` (
  `code` varchar(2) NOT NULL default '',
  `en` varchar(100) NOT NULL default '',
  `fr` varchar(100) NOT NULL default '',
  `nl` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`code`),
  KEY `en` (`en`),
  KEY `fr` (`fr`),
  KEY `nl` (`nl`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `countryname`
--


/*!40000 ALTER TABLE `countryname` DISABLE KEYS */;
LOCK TABLES `countryname` WRITE;
INSERT INTO `countryname` VALUES ('AD','ANDORRA','ANDORRE','Andorra'),('AE','UNITED ARAB EMIRATES','ÉMIRATS ARABES UNIS','Verenigde Arabische Emiraten'),('AF','AFGHANISTAN','AFGHANISTAN','Afghanistan'),('AG','ANTIGUA AND BARBUDA','ANTIGUA-ET-BARBUDA','Antigua en Barbuda'),('AI','ANGUILLA','ANGUILLA','Anguilla'),('AL','ALBANIA','ALBANIE','Albanië'),('AM','ARMENIA','ARMÉNIE','Armenië'),('AN','NETHERLANDS ANTILLES','ANTILLES NÉERLANDAISES','Nederlandse Antillen'),('AO','ANGOLA','ANGOLA','Angola'),('AQ','ANTARCTICA','ANTARCTIQUE','Antarctica'),('AR','ARGENTINA','ARGENTINE','Argentinië'),('AS','AMERICAN SAMOA','SAMOA AMÉRICAINES','Amerikaans-Samoa'),('AT','AUSTRIA','AUTRICHE','Oostenrijk'),('AU','AUSTRALIA','AUSTRALIE','Australië'),('AW','ARUBA','ARUBA','Aruba'),('AX','ÅLAND ISLANDS','ÅLAND, ÎLES D\'','Åland Eilanden'),('AZ','AZERBAIJAN','AZERBAÏDJAN','Azerbeidzjan'),('BA','BOSNIA AND HERZEGOVINA','BOSNIE-HERZÉGOVINE','Bosnië-Herzegovina'),('BB','BARBADOS','BARBADE','Barbados'),('BD','BANGLADESH','BANGLADESH','Bangladesh'),('BE','BELGIUM','BELGIQUE','België'),('BF','BURKINA FASO','BURKINA FASO','Burkina Faso'),('BG','BULGARIA','BULGARIE','Bulgarije'),('BH','BAHRAIN','BAHREÏN','Bahrein'),('BI','BURUNDI','BURUNDI','Burundi'),('BJ','BENIN','BÉNIN','Benin'),('BM','BERMUDA','BERMUDES','Bermuda'),('BN','BRUNEI DARUSSALAM','BRUNÉI DARUSSALAM','Brunei'),('BO','BOLIVIA','BOLIVIE','Bolivia'),('BR','BRAZIL','BRÉSIL','Brazilië'),('BS','BAHAMAS','BAHAMAS','Bahama\'s'),('BT','BHUTAN','BHOUTAN','Bhutan'),('BV','BOUVET ISLAND','BOUVET, ÎLE','Bouvet'),('BW','BOTSWANA','BOTSWANA','Botswana'),('BY','BELARUS','BÉLARUS','Wit-Rusland'),('BZ','BELIZE','BELIZE','Belize'),('CA','CANADA','CANADA','Canada'),('CC','COCOS (KEELING) ISLANDS','COCOS (KEELING), ÎLES','Cocoseilanden'),('CD','CONGO, THE DEM. REP. OF THE','CONGO, LA RÉPUBLIQUE DÉMOCRATIQUE DU','Democratische Republiek Congo'),('CF','CENTRAL AFRICAN REPUBLIC','CENTRAFRICAINE, RÉPUBLIQUE','Centraal-Afrikaanse Republiek'),('CG','CONGO','CONGO','Republiek Congo'),('CH','SWITZERLAND','SUISSE','Zwitserland'),('CI','COTE D\'IVOIRE','CÔTE D\'IVOIRE','Ivoorkust'),('CK','COOK ISLANDS','COOK, ÎLES','Cookeilanden'),('CL','CHILE','CHILI','Chili'),('CM','CAMEROON','CAMEROUN','Kameroen'),('CN','CHINA','CHINE','China'),('CO','COLOMBIA','COLOMBIE','Colombia'),('CR','COSTA RICA','COSTA RICA','Costa Rica'),('CS','SERBIA AND MONTENEGRO','SERBIE-ET-MONTÉNÉGRO','Servië en Montenegro'),('CU','CUBA','CUBA','Cuba'),('CV','CAPE VERDE','CAP-VERT','Kaapverdië'),('CX','CHRISTMAS ISLAND','CHRISTMAS, ÎLE','Christmaseiland'),('CY','CYPRUS','CHYPRE','Cyprus'),('CZ','CZECH REPUBLIC','TCHÈQUE, RÉPUBLIQUE','Tsjechië'),('DE','GERMANY','ALLEMAGNE','Duitsland'),('DJ','DJIBOUTI','DJIBOUTI','Djibouti'),('DK','DENMARK','DANEMARK','Denemarken'),('DM','DOMINICA','DOMINIQUE','Dominica'),('DO','DOMINICAN REPUBLIC','DOMINICAINE, RÉPUBLIQUE','Dominicaanse Republiek'),('DZ','ALGERIA','ALGÉRIE','Algerije'),('EC','ECUADOR','ÉQUATEUR','Ecuador'),('EE','ESTONIA','ESTONIE','Estland'),('EG','EGYPT','ÉGYPTE','Egypte'),('EH','WESTERN SAHARA','SAHARA OCCIDENTAL','Westelijke Sahara'),('ER','ERITREA','ÉRYTHRÉE','Eritrea'),('ES','SPAIN','ESPAGNE','Spanje'),('ET','ETHIOPIA','ÉTHIOPIE','Ethiopië'),('FI','FINLAND','FINLANDE','Finland'),('FJ','FIJI','FIDJI','Fiji'),('FK','FALKLAND ISLANDS (MALVINAS)','FALKLAND, ÎLES (MALVINAS)','Falklandeilanden'),('FM','MICRONESIA, FEDERATED STATES OF','MICRONÉSIE, ÉTATS FÉDÉRÉS DE','Micronesië'),('FO','FAROE ISLANDS','FÉROÉ, ÎLES','Faeröer'),('FR','FRANCE','FRANCE','Frankrijk'),('GA','GABON','GABON','Gabon'),('GB','UNITED KINGDOM','ROYAUME-UNI','Verenigd Koninkrijk'),('GD','GRENADA','GRENADE','Grenada'),('GE','GEORGIA','GÉORGIE','Georgië'),('GF','FRENCH GUIANA','GUYANE FRANÇAISE','Frans-Guyana'),('GH','GHANA','GHANA','Ghana'),('GI','GIBRALTAR','GIBRALTAR','Gibraltar'),('GL','GREENLAND','GROENLAND','Groenland'),('GM','GAMBIA','GAMBIE','Gambia'),('GN','GUINEA','GUINÉE','Guinee'),('GP','GUADELOUPE','GUADELOUPE','Guadeloupe'),('GQ','EQUATORIAL GUINEA','GUINÉE ÉQUATORIALE','Equatoriaal-Guinea'),('GR','GREECE','GRÈCE','Griekenland'),('GS','SOUTH GEORGIA','GÉORGIE DU SUD ET LES ÎLES SANDWICH DU SUD','Zuid-Georgië'),('GT','GUATEMALA','GUATEMALA','Guatemala'),('GU','GUAM','GUAM','Guam'),('GW','GUINEA-BISSAU','GUINÉE-BISSAU','Guinee-Bissau'),('GY','GUYANA','GUYANA','Guyana'),('HK','HONG KONG','HONG-KONG','Hongkong'),('HM','HEARD ISLAND AND MCDONALD ISLANDS','HEARD, ÎLE ET MCDONALD, ÎLES','Heard- en McDonaldeilanden'),('HN','HONDURAS','HONDURAS','Honduras'),('HR','CROATIA','CROATIE','Kroatië'),('HT','HAITI','HAÏTI','Haïti'),('HU','HUNGARY','HONGRIE','Hongarije'),('ID','INDONESIA','INDONÉSIE','Indonesië'),('IE','IRELAND','IRLANDE','Ierland'),('IL','ISRAEL','ISRAËL','Israël'),('IN','INDIA','INDE','India'),('IO','BRITISH INDIAN OCEAN TERRITORY','OCÉAN INDIEN, TERRITOIRE BRITANNIQUE DE L\'','Brits Indische Oceaan Territorium'),('IQ','IRAQ','IRAQ','Irak'),('IR','IRAN, ISLAMIC REPUBLIC OF','IRAN, RÉPUBLIQUE ISLAMIQUE D\'','Iran'),('IS','ICELAND','ISLANDE','IJsland'),('IT','ITALY','ITALIE','Italië'),('JM','JAMAICA','JAMAÏQUE','Jamaica'),('JO','JORDAN','JORDANIE','Jordanië'),('JP','JAPAN','JAPON','Japan'),('KE','KENYA','KENYA','Kenia'),('KG','KYRGYZSTAN','KIRGHIZISTAN','Kirgizië'),('KH','CAMBODIA','CAMBODGE','Cambodja'),('KI','KIRIBATI','KIRIBATI','Kiribati'),('KM','COMOROS','COMORES','Comoren'),('KN','SAINT KITTS AND NEVIS','SAINT-KITTS-ET-NEVIS','Saint Kitts en Nevis'),('KP','KOREA, DEM. PEOPLE\'S REP. OF','CORÉE, RÉPUBLIQUE POPULAIRE DÉMOCRATIQUE DE','Noord-Korea'),('KR','KOREA, REPUBLIC OF','CORÉE, RÉPUBLIQUE DE','Zuid-Korea'),('KW','KUWAIT','KOWEÏT','Koeweit'),('KY','CAYMAN ISLANDS','CAÏMANES, ÎLES','Caymaneilanden'),('KZ','KAZAKHSTAN','KAZAKHSTAN','Kazachstan'),('LA','LAO PEOPLE\'S DEMOCRATIC REPUBLIC','LAO, RÉPUBLIQUE DÉMOCRATIQUE POPULAIRE','Laos'),('LB','LEBANON','LIBAN','Libanon'),('LC','SAINT LUCIA','SAINTE-LUCIE','Saint Lucia'),('LI','LIECHTENSTEIN','LIECHTENSTEIN','Liechtenstein'),('LK','SRI LANKA','SRI LANKA','Sri Lanka'),('LR','LIBERIA','LIBÉRIA','Liberia'),('LS','LESOTHO','LESOTHO','Lesotho'),('LT','LITHUANIA','LITUANIE','Litouwen'),('LU','LUXEMBOURG','LUXEMBOURG','Groothertogdom Luxemburg'),('LV','LATVIA','LETTONIE','Letland'),('LY','LIBYAN ARAB JAMAHIRIYA','LIBYENNE, JAMAHIRIYA ARABE','Libië'),('MA','MOROCCO','MAROC','Marokko'),('MC','MONACO','MONACO','Monaco'),('MD','MOLDOVA, REPUBLIC OF','MOLDOVA, RÉPUBLIQUE DE','Republiek Moldavië'),('MG','MADAGASCAR','MADAGASCAR','Madagaskar'),('MH','MARSHALL ISLANDS','MARSHALL, ÎLES','Marshalleilanden'),('MK','MACEDONIA','MACÉDOINE, L\'EX-RÉPUBLIQUE YOUGOSLAVE DE','Macedonië'),('ML','MALI','MALI','Mali'),('MM','MYANMAR','MYANMAR','Myanmar'),('MN','MONGOLIA','MONGOLIE','Mongolië'),('MO','MACAO','MACAO','Macao'),('MP','NORTHERN MARIANA ISLANDS','MARIANNES DU NORD, ÎLES','Noordelijke Marianen'),('MQ','MARTINIQUE','MARTINIQUE','Martinique'),('MR','MAURITANIA','MAURITANIE','Mauritanië'),('MS','MONTSERRAT','MONTSERRAT','Montserrat'),('MT','MALTA','MALTE','Malta'),('MU','MAURITIUS','MAURICE','Mauritius'),('MV','MALDIVES','MALDIVES','Maldiven'),('MW','MALAWI','MALAWI','Malawi'),('MX','MEXICO','MEXIQUE','Mexico'),('MY','MALAYSIA','MALAISIE','Maleisië'),('MZ','MOZAMBIQUE','MOZAMBIQUE','Mozambique'),('NA','NAMIBIA','NAMIBIE','Namibië'),('NC','NEW CALEDONIA','NOUVELLE-CALÉDONIE','Nieuw-Caledonië'),('NE','NIGER','NIGER','Niger'),('NF','NORFOLK ISLAND','NORFOLK, ÎLE','Norfolkeiland'),('NG','NIGERIA','NIGÉRIA','Nigeria'),('NI','NICARAGUA','NICARAGUA','Nicaragua'),('NL','NETHERLANDS','PAYS-BAS','Nederland'),('NO','NORWAY','NORVÈGE','Noorwegen'),('NP','NEPAL','NÉPAL','Nepal'),('NR','NAURU','NAURU','Nauru'),('NU','NIUE','NIUÉ','Niue'),('NZ','NEW ZEALAND','NOUVELLE-ZÉLANDE','Nieuw-Zeeland'),('OM','OMAN','OMAN','Oman'),('PA','PANAMA','PANAMA','Panama'),('PE','PERU','PÉROU','Peru'),('PF','FRENCH POLYNESIA','POLYNÉSIE FRANÇAISE','Frans-Polynesië'),('PG','PAPUA NEW GUINEA','PAPOUASIE-NOUVELLE-GUINÉE','Papoea-Nieuw-Guinea'),('PH','PHILIPPINES','PHILIPPINES','Filipijnen'),('PK','PAKISTAN','PAKISTAN','Pakistan'),('PL','POLAND','POLOGNE','Polen'),('PM','SAINT PIERRE AND MIQUELON','SAINT-PIERRE-ET-MIQUELON','Saint-Pierre en Miquelon'),('PN','PITCAIRN','PITCAIRN','Pitcairneilanden'),('PR','PUERTO RICO','PORTO RICO','Puerto Rico'),('PS','PALESTINIAN TERRITORY, OCCUPIED','PALESTINIEN OCCUPÉ, TERRITOIRE','Palestina'),('PT','PORTUGAL','PORTUGAL','Portugal'),('PW','PALAU','PALAOS','Palau'),('PY','PARAGUAY','PARAGUAY','Paraguay'),('QA','QATAR','QATAR','Qatar'),('RE','REUNION','RÉUNION','Réunion'),('RO','ROMANIA','ROUMANIE','Roemenië'),('RU','RUSSIAN FEDERATION','RUSSIE, FÉDÉRATION DE','Rusland'),('RW','RWANDA','RWANDA','Rwanda'),('SA','SAUDI ARABIA','ARABIE SAOUDITE','Saoedi-Arabië'),('SB','SOLOMON ISLANDS','SALOMON, ÎLES','Salomonseilanden'),('SC','SEYCHELLES','SEYCHELLES','Seychellen'),('SD','SUDAN','SOUDAN','Soedan'),('SE','SWEDEN','SUÈDE','Zweden'),('SG','SINGAPORE','SINGAPOUR','Singapore'),('SH','SAINT HELENA','SAINTE-HÉLÈNE','Sint-Helena'),('SI','SLOVENIA','SLOVÉNIE','Slovenië'),('SJ','SVALBARD AND JAN MAYEN','SVALBARD ET ÎLE JAN MAYEN','Svalbard en Jan Mayen'),('SK','SLOVAKIA','SLOVAQUIE','Slowakije'),('SL','SIERRA LEONE','SIERRA LEONE','Sierra Leone'),('SM','SAN MARINO','SAINT-MARIN','San Marino'),('SN','SENEGAL','SÉNÉGAL','Senegal'),('SO','SOMALIA','SOMALIE','Somalië'),('SR','SURINAME','SURINAME','Suriname'),('ST','SAO TOME AND PRINCIPE','SAO TOMÉ-ET-PRINCIPE','Sao Tomé en Principe'),('SV','EL SALVADOR','EL SALVADOR','El Salvador'),('SY','SYRIAN ARAB REPUBLIC','SYRIENNE, RÉPUBLIQUE ARABE','Syrië'),('SZ','SWAZILAND','SWAZILAND','Swaziland'),('TC','TURKS AND CAICOS ISLANDS','TURKS ET CAÏQUES, ÎLES','Turks- en Caicoseilanden'),('TD','CHAD','TCHAD','Tsjaad'),('TF','FRENCH SOUTHERN TERRITORIES','TERRES AUSTRALES FRANÇAISES','Franse Zuidelijke Gebieden'),('TG','TOGO','TOGO','Togo'),('TH','THAILAND','THAÏLANDE','Thailand'),('TJ','TAJIKISTAN','TADJIKISTAN','Tadzjikistan'),('TK','TOKELAU','TOKELAU','Tokelau-eilanden'),('TL','TIMOR-LESTE','TIMOR-LESTE','Oost-Timor'),('TM','TURKMENISTAN','TURKMÉNISTAN','Turkmenistan'),('TN','TUNISIA','TUNISIE','Tunesië'),('TO','TONGA','TONGA','Tonga'),('TR','TURKEY','TURQUIE','Turkije'),('TT','TRINIDAD AND TOBAGO','TRINITÉ-ET-TOBAGO','Trinidad en Tobago'),('TV','TUVALU','TUVALU','Tuvalu'),('TW','TAIWAN, PROVINCE OF CHINA','TAÏWAN, PROVINCE DE CHINE','Taiwan'),('TZ','TANZANIA, UNITED REPUBLIC OF','TANZANIE, RÉPUBLIQUE-UNIE DE','Tanzania'),('UA','UKRAINE','UKRAINE','Oekraïne'),('UG','UGANDA','OUGANDA','Oeganda'),('UM','UNITED STATES MINOR OUTLYING ISLANDS','ÎLES MINEURES ÉLOIGNÉES DES ÉTATS-UNIS','Kleine Pacifische eilanden (VS)'),('US','UNITED STATES','ÉTATS-UNIS','Verenigde Staten'),('UY','URUGUAY','URUGUAY','Uruguay'),('UZ','UZBEKISTAN','OUZBÉKISTAN','Oezbekistan'),('VA','HOLY SEE (VATICAN CITY STATE)','SAINT-SIÈGE (ÉTAT DE LA CITÉ DU VATICAN)','Vaticaanstad'),('VC','SAINT VINCENT AND THE GRENADINES','SAINT-VINCENT-ET-LES GRENADINES','Saint Vincent en de Grenadines'),('VE','VENEZUELA','VENEZUELA','Venezuela'),('VG','VIRGIN ISLANDS, BRITISH','ÎLES VIERGES BRITANNIQUES','Britse Maagdeneilanden'),('VI','VIRGIN ISLANDS, U.S.','ÎLES VIERGES DES ÉTATS-UNIS','Amerikaanse Maagdeneilanden'),('VN','VIET NAM','VIET NAM','Vietnam'),('VU','VANUATU','VANUATU','Vanuatu'),('WF','WALLIS AND FUTUNA','WALLIS ET FUTUNA','Wallis en Futuna'),('WS','SAMOA','SAMOA','Samoa'),('YE','YEMEN','YÉMEN','Jemen'),('YT','MAYOTTE','MAYOTTE','Mayotte'),('ZA','SOUTH AFRICA','AFRIQUE DU SUD','Zuid-Afrika'),('ZM','ZAMBIA','ZAMBIE','Zambia'),('ZW','ZIMBABWE','ZIMBABWE','Zimbabwe');
UNLOCK TABLES;
/*!40000 ALTER TABLE `countryname` ENABLE KEYS */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

