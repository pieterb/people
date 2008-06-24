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
class LargeValueTest extends PHPUnit_Framework_TestCase
{

public function testLargeValue() {
  peopleClearDB();
  global $PEOPLE_REGISTRY;
  $address = new TestAddress( $PEOPLE_REGISTRY, array(
      'address' => 'someAddress'
    ));
  $address_id = $address->id();
  $PEOPLE_REGISTRY->persist();
  $PEOPLE_REGISTRY->flush();
  $address = $PEOPLE_REGISTRY->getObject( $address_id );
  $this->assertSame( 'TestAddress', get_class( $address ) );
  $this->assertSame( 'someAddress', $address->address );
  $address->address = str_repeat('0123456789', 4096);
  $PEOPLE_REGISTRY->persist();
  $PEOPLE_REGISTRY->flush();
  $address = $PEOPLE_REGISTRY->getObject( $address_id );
  $this->assertSame( str_repeat('0123456789', 4096), $address->address );
  
}

} // class

?>