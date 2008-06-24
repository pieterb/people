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
class PeopleDBValueTest extends PHPUnit_Framework_TestCase
{
  
public static function providerValues() {
  return array (
    array('PeopleDBBoolean', NULL, NULL, NULL),
    array('PeopleDBBoolean', TRUE, TRUE, 1),
    array('PeopleDBBoolean', FALSE, FALSE, 0),
    array('PeopleDBBoolean', 1, TRUE, 1),
    array('PeopleDBBoolean', -1, TRUE, 1),
    array('PeopleDBBoolean', 0, FALSE, 0),
    array('PeopleDBBoolean', '', FALSE, 0),
    array('PeopleDBBoolean', '0', FALSE, 0),
    array('PeopleDBBoolean', 'TRUE', TRUE, 1),
    array('PeopleDBBoolean', 'FALSE', TRUE, 1),
    array('PeopleDBCurrency', NULL, NULL, NULL),
    array('PeopleDBCurrency', 1.0, 1.0, '1'),
    array('PeopleDBCurrency', -1.0, -1.0, '-1'),
    array('PeopleDBCurrency', 1.1E+04, 1.1E+04, '11000'),
    array('PeopleDBCurrency', 1.1E-04, 1.1E-04, '0.00011'),
    array('PeopleDBCurrency', '1.1E+04', '1.1E+04', '1.1E+04'),
    array('PeopleDBCurrency', '-1.1E-04', '-1.1E-04', '-1.1E-04'),
    array('PeopleDBDate', NULL, NULL, NULL),
    array('PeopleDBDate', 86399, '1970-01-01', '1970-01-01'),
    array('PeopleDBDate', 86400, '1970-01-02', '1970-01-02'),
    array('PeopleDBDate', 86401, '1970-01-02', '1970-01-02'),
    array('PeopleDBDate', '1901-12-14', '1901-12-14', '1901-12-14'), // the oldest possible date
    array('PeopleDBDate', '2038-01-19', '2038-01-19', '2038-01-19'), // the latest possible date
    array('PeopleDBDateTime', '2038-01-19 03:14:07', 2147483647, '2038-01-19 03:14:07'),
    array('PeopleDBDateTime', '1901-12-13 20:45:53', -2147483647, '1901-12-13 20:45:53'), // AKA the new millenium problem!
    array('PeopleDBInteger', NULL, NULL, NULL),
    array('PeopleDBInteger', -1, -1, '-1'),
    array('PeopleDBInteger', 0, 0, '0'),
    array('PeopleDBInteger', 1, 1, '1'),
    array('PeopleDBInteger', '-1', -1, '-1'),
    array('PeopleDBInteger', '0', 0, '0'),
    array('PeopleDBInteger', '1', 1, '1'),
    array('PeopleDBInteger', '-9999999999999999999999999999', '-9999999999999999999999999999', '-9999999999999999999999999999'),
    array('PeopleDBInteger',  '9999999999999999999999999999',  '9999999999999999999999999999',  '9999999999999999999999999999'),
  );
}

/**
 * @dataProvider providerValues
 */
public function testValues($class, $in, $test, $sql) {
  $fixture = eval("return new $class();");
  $this->assertFalse($fixture->changed());
  $fixture->set($in);
  $this->assertSame($test, $fixture->value());
  $this->assertSame($sql, $fixture->sql());
  if (is_null($in))
    $this->assertFalse($fixture->changed());
  else
    $this->assertTrue($fixture->changed());
  $fixture->reset();
  $this->assertNull($fixture->value());
  $this->assertFalse($fixture->changed());
  $fixture->set($in);
  $fixture->persisted();
  $this->assertFalse($fixture->changed());
}

public static function providerSQLType() {
  return array(
    array('PeopleDBBoolean', 'i'),
    array('PeopleDBCurrency', 's'),
    array('PeopleDBDate', 's'),
    array('PeopleDBInteger', 's'),
    array('PeopleDBObject', 's'),
    array('PeopleDBText', 's'),
  );
}

/**
 * @dataProvider providerSQLType
 */
public function testSQLType($class, $sqltype) {
  $fixture = eval("return new $class();");
  $this->assertSame($sqltype, $fixture->SQLType());
}

public static function providerBadParameter() {
  return array(
    array('PeopleDBCurrency', ''),
    array('PeopleDBCurrency', 'a'),
    array('PeopleDBDate', '2008-30-02'),
    array('PeopleDBDate', '1901-12-13'), // AKA the new millenium problem!
    array('PeopleDBDate', '2038-01-20'), // AKA the new millenium problem!
    array('PeopleDBDate', '30-02-2008'),
    array('PeopleDBDate', '02-30-2008'),
    array('PeopleDBDateTime', '1901-12-13 20:45:51'), // AKA the new millenium problem!
    array('PeopleDBDateTime', '2038-01-19 03:14:08'), // AKA the new millenium problem!
    array('PeopleDBDateTime', ''),
    array('PeopleDBDateTime', 'xeuoa'),
    array('PeopleDBInteger', ''),
    array('PeopleDBInteger', 'a'),
    array('PeopleDBInteger', -1.1),
    array('PeopleDBInteger', 1.1),
    array('PeopleDBInteger', '1E+3'),
  );
}

/**
 * @dataProvider providerBadParameter
 */
public function testBadParameter($class, $in) {
  //$this->setExpectedException('PeopleException');
  $fixture = eval("return new $class();");
  try {
    $fixture->set($in);
  }
  catch (PeopleRuntimeError $e) {
    if ($e->getCode() == PeopleException::E_CONSTRAINT) return;
  }
  $this->fail('Expected a PeopleRuntimeError E_CONSTRAINT!');
}


} // class

?>