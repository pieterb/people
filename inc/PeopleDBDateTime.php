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
 * @subpackage DBValue
 * @author Pieter van Beek <pieter@djinnit.com>
 */

/**
 * A DateTime value in the database.
 * This Object persistence layer defines a datetime as follows in the database:
 * <pre>DATETIME</pre>
 * @package People
 * @subpackage DBValue
 * @author Pieter van Beek <pieter@djinnit.com>
 */
class PeopleDBDateTime extends PeopleDBValue
{


public function sql() {
  return is_null($this->i_value) ? NULL : gmdate('Y-m-d H:i:s', $this->i_value);
}


protected function validate($value) {
  if (is_null($value)) return NULL;
  if (is_numeric($value)) return (int)$value;
  if (is_string($value)) {
    $oldtz = date_default_timezone_get();
    if ($oldtz != 'UTC') date_default_timezone_set('UTC');
    $retval = strtotime("$value");
    if ($oldtz != 'UTC') date_default_timezone_set($oldtz);
    if ($retval === FALSE)
      throw PeopleException::bad_parameters(
        func_get_args(),
        People::tr('Invalid time')
      );
    return $retval;
  }
  throw PeopleException::bad_parameters(
    func_get_args(),
    People::tr('Invalid type')
  );
}


public function SQLType() { return 's'; }


public function date($format) {
  return is_null($this->i_value) ? '' : date($format, $this->i_value);
}
public function gmdate($format) {
  return is_null($this->i_value) ? '' : gmdate($format, $this->i_value);
}


/**
 * Adds or substracts $n $what's from the current date.
 * @param int $n
 * @param string $what 'year(s)', 'month(s)', 'week(s)', 'day(s)',
 *        'hour(s)', 'minute(s)', or 'second(s)'.
 * @return PeopleDBDate $this
 */
public function diff($n, $what) {
  if (is_null($this->i_value)) return $this;
  if ( !is_int($n) or
       !in_array(
         $what, array(
           'year',   'years',
           'month',  'months',
           'week',   'weeks',
           'day',    'days',
           'hour',   'hours',
           'minute', 'minutes',
           'second', 'seconds',
         )
       )
     )
     throw PeopleException::bad_parameters(func_get_args());
  if ($n >= 0) $n = "+$n";
  $this->set( strtotime( gmdate(
    'Y-m-d G:i:s', $this->i_value
  ) . " UTC $n $what" ) );
  return $this;
}


} // end of Type

?>