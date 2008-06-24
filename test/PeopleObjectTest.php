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
class PeopleObjectTest extends PHPUnit_Framework_TestCase
{

/**
 * Test CTOR of derived class.
 */
public function testConstructor() {
  global $PEOPLE_REGISTRY;
  $PEOPLE_REGISTRY->flush();
  peopleClearDB();
  // A manager MUST have an address, so we create that first:
  $address = new TestAddress( $PEOPLE_REGISTRY, array(
      'address' => 'Some Address'
    ));
  // Now, we construct a TestManager object:
  $manager = new TestManager( $PEOPLE_REGISTRY, array(
      // These properties are from base class TestEmployee:
      'name' => 'M. Anager',
      'address' => $address->id(),
      // This property is from derived class TestManager:
      'unitname' => 'someUnit'
    ));
  $id = $manager->id();
  $PEOPLE_REGISTRY->persist();
  $PEOPLE_REGISTRY->flush();
  // Now we test if everything came through alright:
  $manager = $PEOPLE_REGISTRY->assertObject($id);
  $this->assertNull( $manager->manager );
  $this->assertSame( 'M. Anager', $manager->name );
  $this->assertSame( $address->id(), $manager->address );
  $this->assertSame( 'someUnit', $manager->unitname );
}


public function testUniqueKey() {
  global $PEOPLE_REGISTRY;
  $PEOPLE_REGISTRY->flush();
  peopleClearDB();
  $address = new TestAddress( $PEOPLE_REGISTRY, array(
      'address' => 'Some Address'
    ));
  new TestManager( $PEOPLE_REGISTRY, array(
    'name' => 'M. Anager',
    'unitname' => 'someUnit',
    'address' => $address->id()
  ));
  new TestManager( $PEOPLE_REGISTRY, array(
    'name' => 'M. Anager',
    'unitname' => 'someUnit',
    'address' => $address->id()
  ));
  try {
    $PEOPLE_REGISTRY->persist();
    $this->assertTrue(false);
  }
  catch (PeopleException $e) {
    $this->assertSame( PeopleException::E_CONSTRAINT, $e->getCode() );
  }
  $PEOPLE_REGISTRY->flush();
}

} // class

?>