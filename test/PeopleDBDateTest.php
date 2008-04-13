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
class PeopleDBDateTest extends PHPUnit_Framework_TestCase
{

public static function providerDiff() {
  return array (
    array('1972-02-28', 1, 'day',    '1972-02-29'),
    array('1972-02-28', 1, 'week',   '1972-03-06'),
    array('1972-02-28', 1, 'month',  '1972-03-28'),
    array('1972-03-31', 1, 'month',  '1972-05-01'),
    array('1972-01-31', 1, 'year',   '1973-01-31'),
    array('1972-02-29', 1, 'year',   '1973-03-01'),
  );
}

/**
 * @dataProvider providerDiff
 */
public function testDiff($p_time, $p_diff, $p_what, $p_expected) {
  $fixture = new PeopleDBDate($p_time);
  $fixture->diff($p_diff, $p_what);
  $this->assertSame($p_expected, $fixture->sql());
}

public function testDateAndGMDate() {
  $fixture = new PeopleDBDate('1999-12-31');
  $this->assertSame(1999, $fixture->year());
  $this->assertSame(12, $fixture->month());
  $this->assertSame(31, $fixture->day());
  $this->assertSame(52, $fixture->week());
}

} // class

?>