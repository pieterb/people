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
class PeopleDBDateTimeTest extends PHPUnit_Framework_TestCase
{

public static function providerDiff() {
  return array (
    array('1972-02-28 23:59:59', 1, 'second', '1972-02-29 00:00:00'),
    array('1972-02-28 23:59:59', 1, 'minute', '1972-02-29 00:00:59'),
    array('1972-02-28 23:59:59', 1, 'hour',   '1972-02-29 00:59:59'),
    array('1972-02-28 23:59:59', 1, 'day',    '1972-02-29 23:59:59'),
    array('1972-02-28 23:59:59', 1, 'week',   '1972-03-06 23:59:59'),
    array('1972-02-28 23:59:59', 1, 'month',  '1972-03-28 23:59:59'),
    array('1972-03-31 23:59:59', 1, 'month',  '1972-05-01 23:59:59'),
    array('1972-01-31 23:59:59', 1, 'year',   '1973-01-31 23:59:59'),
    array('1972-02-29 23:59:59', 1, 'year',   '1973-03-01 23:59:59'),
  );
}

/**
 * @dataProvider providerDiff
 */
public function testDiff($p_time, $p_diff, $p_what, $p_expected) {
  $fixture = new PeopleDBDateTime($p_time);
  $fixture->diff($p_diff, $p_what);
  $this->assertSame($p_expected, $fixture->sql());
}

public function testDateAndGMDate() {
  $fixture = new PeopleDBDateTime('1999-12-31 23:30:00 UTC');
  $oldtz = date_default_timezone_get();
  date_default_timezone_set('Europe/Amsterdam');
  $this->assertSame(date('r', $fixture->value()), $fixture->date('r'));
  $this->assertSame(gmdate('r', $fixture->value()), $fixture->gmdate('r'));
  date_default_timezone_set($oldtz);
}

} // class

?>