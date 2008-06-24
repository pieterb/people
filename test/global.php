<?php

/*·************************************************************************
 * Copyright © 2005-2008 by Pieter van Beek                               *
 * pieter@djinnit.com                                                     *
 *                                                                        *
 * This program may be distributed under the terms of the Q Public        *
 * License as defined by Trolltech AS of Norway and appearing in the file *
 * LICENSE.QPL included in the packaging of this file.                    *
 *                                                                        *
 * This program is distributed in the hope that it will be useful, but    *
 * WITHOUT ANY WARRANTY; without even the implied warranty of             *
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.                   *
 **************************************************************************/

/**
 * @package People
 * @subpackage Tests
 * @author Pieter van Beek <pieter@djinnit.com>
 */

require_once '../inc/People.php';
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'PHPUnit/Framework/TestSuite.php';

//PHPUnit_Framework_Notice::$enabled = FALSE;

function __autoload($classname) {
  require_once("$classname.php");
}

$GLOBALS['PEOPLE_MYSQLI'] = new mysqli('localhost', 'people', 'people', 'test_people');
if (mysqli_connect_errno())
  throw new PeopleException(
    'Connection failed: ' . mysqli_connect_error(),
    PeopleException::E_RUNTIME_ERROR
  );
$GLOBALS['PEOPLE_REGISTRY'] = new PeopleRegistry($GLOBALS['PEOPLE_MYSQLI']);

date_default_timezone_set('Europe/Amsterdam');

function peopleClearDB() {
  $sql = array(
    "DROP TABLE IF EXISTS `PeopleIDSequence`",
    "CREATE TABLE `PeopleIDSequence` (
       `id` bigint(20) NOT NULL auto_increment,
       PRIMARY KEY  (`id`)
     ) ENGINE=InnoDB DEFAULT CHARSET=utf8",
    "DROP TABLE IF EXISTS `PeopleObject`",
    "CREATE TABLE `PeopleObject` (
       `people_id` bigint(20) NOT NULL default '0',
       `people_classname` varchar(255) NOT NULL default '',
       `people_version` bigint(20) NOT NULL default '0',
       `people_modified` timestamp NOT NULL default CURRENT_TIMESTAMP,
       PRIMARY KEY  (`people_id`),
       KEY `modified` (`people_modified`),
       KEY `classname` USING BTREE (`people_classname`),
       KEY `version` (`people_version`)
     ) ENGINE=InnoDB DEFAULT CHARSET=utf8",
    "DROP TABLE IF EXISTS `TestEmployee`",
    "CREATE TABLE `TestEmployee` (
       `people_id` bigint(20) unsigned NOT NULL,
       `name` varchar(255) NOT NULL,
       `address` bigint(20) unsigned NOT NULL,
       `manager` bigint(20) unsigned,
       PRIMARY KEY  (`people_id`),
       KEY `name` (`name`),
       KEY `address` (`address`),
       KEY `manager` (`manager`)
     ) ENGINE=InnoDB DEFAULT CHARSET=utf8",
    "DROP TABLE IF EXISTS `TestManager`",
    "CREATE TABLE `TestManager` (
       `people_id` bigint(20) unsigned NOT NULL,
       `unitname` varchar(255) NOT NULL,
       PRIMARY KEY  (`people_id`),
       UNIQUE KEY `unitname` (`unitname`)
     ) ENGINE=InnoDB DEFAULT CHARSET=utf8",
    "DROP TABLE IF EXISTS `TestAddress`",
    "CREATE TABLE `TestAddress` (
       `people_id` bigint(20) unsigned NOT NULL,
       `address` text NOT NULL,
       PRIMARY KEY  (`people_id`)
     ) ENGINE=InnoDB DEFAULT CHARSET=utf8",
  );
  global $PEOPLE_REGISTRY;
  foreach ($sql as $statement)
    $PEOPLE_REGISTRY->execute($statement);
}

?>