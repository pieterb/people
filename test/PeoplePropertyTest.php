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
class PeoplePropertyTest extends PHPUnit_Framework_TestCase
{

public static function providerFlags() {
  return array(
    array( FALSE, FALSE, FALSE, FALSE, 0 ),
    array( FALSE, FALSE, FALSE, TRUE,  PeopleProperty::READ_ONLY ),
    array( FALSE, FALSE, TRUE,  FALSE, PeopleProperty::NULL_ALLOWED ),
    array( FALSE, FALSE, TRUE,  TRUE,  PeopleProperty::NULL_ALLOWED | PeopleProperty::READ_ONLY ),
    array( FALSE, TRUE,  FALSE, FALSE, PeopleProperty::DEPRECATED ),
    array( FALSE, TRUE,  FALSE, TRUE,  PeopleProperty::DEPRECATED | PeopleProperty::READ_ONLY ),
    array( FALSE, TRUE,  TRUE,  FALSE, PeopleProperty::DEPRECATED | PeopleProperty::NULL_ALLOWED ),
    array( FALSE, TRUE,  TRUE,  TRUE,  PeopleProperty::DEPRECATED | PeopleProperty::NULL_ALLOWED | PeopleProperty::READ_ONLY ),
    array( TRUE,  FALSE, FALSE, FALSE, PeopleProperty::CASCADING ),
    array( TRUE,  FALSE, FALSE, TRUE,  PeopleProperty::CASCADING | PeopleProperty::READ_ONLY ),
    array( TRUE,  FALSE, TRUE,  FALSE, PeopleProperty::CASCADING | PeopleProperty::NULL_ALLOWED ),
    array( TRUE,  FALSE, TRUE,  TRUE,  PeopleProperty::CASCADING | PeopleProperty::NULL_ALLOWED | PeopleProperty::READ_ONLY ),
    array( TRUE,  TRUE,  FALSE, FALSE, PeopleProperty::CASCADING | PeopleProperty::DEPRECATED ),
    array( TRUE,  TRUE,  FALSE, TRUE,  PeopleProperty::CASCADING | PeopleProperty::DEPRECATED | PeopleProperty::READ_ONLY ),
    array( TRUE,  TRUE,  TRUE,  FALSE, PeopleProperty::CASCADING | PeopleProperty::DEPRECATED | PeopleProperty::NULL_ALLOWED ),
    array( TRUE,  TRUE,  TRUE,  TRUE,  PeopleProperty::CASCADING | PeopleProperty::DEPRECATED | PeopleProperty::NULL_ALLOWED | PeopleProperty::READ_ONLY ),
  );
}

/**
 * @dataProvider providerFlags
 */
public function testFlags( $p_cascading, $p_deprecated, $p_null_allowed, $p_read_only, $p_flags ) {
  foreach ( array( PeopleProperty::BOOLEAN,
                   PeopleProperty::CURRENCY,
                   PeopleProperty::DATE,
                   PeopleProperty::DATETIME,
                   PeopleProperty::INTEGER,
                   PeopleProperty::OBJECT,
                   PeopleProperty::TEXT,
                   PeopleProperty::DATETIME ) as $type ) {
    $fixture = new PeopleProperty( 'TestProperty', $type, $p_flags );
    $this->assertSame( $p_cascading, $fixture->cascading() );
    $this->assertSame( $p_deprecated, $fixture->deprecated() );
    $this->assertSame( $p_null_allowed, $fixture->nullAllowed() );
    $this->assertSame( $p_read_only, $fixture->readOnly() );
  }
}

public static function providerTypes() {
  return array(
    array( PeopleProperty::BOOLEAN,  'PeopleDBBoolean'  ),
    array( PeopleProperty::CURRENCY, 'PeopleDBCurrency' ),
    array( PeopleProperty::DATE,     'PeopleDBDate'     ),
    array( PeopleProperty::DATETIME, 'PeopleDBDateTime' ),
    array( PeopleProperty::INTEGER,  'PeopleDBInteger'  ),
    array( PeopleProperty::OBJECT,   'PeopleDBObject'   ),
    array( PeopleProperty::TEXT,     'PeopleDBText'     ),
  );
}

/**
 * @dataProvider providerTypes
 */
public function testTypes( $p_type, $p_name ) {
  $fixture = new PeopleProperty( 'TestProperty', $p_type );
  $this->assertSame( 'TestProperty', $fixture->name() );
  $this->assertSame( $p_type, $fixture->type() );
  $this->assertNull( $fixture->defaultValue() );
  $dbvalue = $fixture->dbvalue();
  $this->assertSame( $p_name, get_class($dbvalue) );
}

public function testConstants() {
  $test = array();
  $test[PeopleProperty::BOOLEAN] = PeopleProperty::BOOLEAN;
  $test[PeopleProperty::CURRENCY] = PeopleProperty::CURRENCY;
  $test[PeopleProperty::DATE] = PeopleProperty::DATE;
  $test[PeopleProperty::DATETIME] = PeopleProperty::DATETIME;
  $test[PeopleProperty::INTEGER] = PeopleProperty::INTEGER;
  $test[PeopleProperty::OBJECT] = PeopleProperty::OBJECT;
  $test[PeopleProperty::TEXT] = PeopleProperty::TEXT;
  $this->assertSame(7, count($test));
  
  $test = array();
  $test[PeopleProperty::CASCADING] = PeopleProperty::CASCADING;
  $test[PeopleProperty::DEPRECATED] = PeopleProperty::DEPRECATED;
  $test[PeopleProperty::NULL_ALLOWED] = PeopleProperty::NULL_ALLOWED;
  $test[PeopleProperty::READ_ONLY] = PeopleProperty::READ_ONLY;
  $this->assertSame(4, count($test));
}

} // class

?>