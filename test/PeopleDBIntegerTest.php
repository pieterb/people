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

require_once dirname(__FILE__) . '/global.php';

/**
 * A unit test.
 * @package People
 * @subpackage Tests
 * @author Pieter van Beek <pieter@djinnit.com>
 */
class PeopleDBIntegerTest extends PHPUnit_Framework_TestCase
{
  
protected $dbvalue = null;

protected function setUp() {
  $this->dbvalue = new PeopleDBInteger();
}

public static function providerValues() {
  return array (
    array(NULL, NULL),
    array(-1, -1),
    array(0, 0),
    array(1, 1),
    array('-1', -1),
    array('0', 0),
    array('1', 1),
    array('-9999999999999999999999999999', '-9999999999999999999999999999'),
    array( '9999999999999999999999999999',  '9999999999999999999999999999'),
  );
}

/**
 * @dataProvider providerValues
 */
public function testChanged($in, $test) {
  $this->assertFalse($this->dbvalue->changed());
  $this->dbvalue->set($in);
  $this->assertSame($test, $this->dbvalue->value());
  if (is_null($in))
    $this->assertFalse($this->dbvalue->changed());
  else
    $this->assertTrue($this->dbvalue->changed());
  $this->dbvalue->reset();
  $this->assertNull($this->dbvalue->value());
  $this->assertFalse($this->dbvalue->changed());
  $this->dbvalue->set($in);
  $this->dbvalue->persisted();
  $this->assertFalse($this->dbvalue->changed());
}

public function testSQLType() {
  $this->assertSame('s', $this->dbvalue->SQLType());
}

public static function providerSQL() {
  return array(
    array(NULL, NULL),
    array(-1, '-1'),
    array(0, '0'),
    array(1, '1'),
    array('-1', '-1'),
    array('0', '0'),
    array('1', '1'),
    array('-9999999999999999999999999999', '-9999999999999999999999999999'),
    array( '9999999999999999999999999999',  '9999999999999999999999999999'),
  );
}

/**
 * @dataProvider providerSQL
 */
public function testSQL($in, $test) {
  $this->dbvalue->set($in);
  $this->assertSame($test, $this->dbvalue->sql());
}


} // class

?>