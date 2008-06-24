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
class PeopleDBDate extends PeopleDBValue
{


protected function validate($value) {
  if (is_null($value)) return NULL;
  if (is_int($value)) return gmdate('Y-m-d', $value);
  if (!is_string($value) ||
      !preg_match('/^(\\d{1,4})\\D(\\d{1,2})\\D(\\d{1,2})$/', $value, $matches))
    throw PeopleException::constraint(
      sprintf(
        People::tr('Date \'%s\' is not properly formatted (YYYY-MM-DD)'),
        $value
      )
    );
  $formatted = sprintf(
    '%04d-%02d-%02d', $matches[1], $matches[2], $matches[3]
  );
  if ($formatted !== gmdate('Y-m-d', strtotime("$formatted 00:00:00 UTC")))
    throw PeopleException::constraint(
      sprintf( People::tr('\'%s\' is not an existing date'), $value )
    );
  return $formatted;
}


/** @return int the year */
public function year() {
  return is_null($this->i_value) ? NULL : (int)substr($this->i_value, 0, 4);
}


/** @return int the month */
public function month() {
  return is_null($this->i_value) ? NULL : (int)substr($this->i_value, 5, 2);
}


/** @return int the day */
public function day() {
  return is_null($this->i_value) ? NULL : (int)substr($this->i_value, -2);
}


public function week() {
  return is_null($this->i_value) ? NULL : (int)gmdate(
    'W', strtotime("{$this->i_value} UTC")
  );
}


public function gmdate($format) {
  return is_null($this->i_value) ? '' :
    gmdate($format, strtotime("{$this->i_value} 00:00:00 UTC"));
}


/**
 * Adds or substracts $n $what's from the current date.
 * @param int $n
 * @param string $what 'year(s)', 'month(s)', 'week(s)' or 'day(s)'
 * @return PeopleDBDate $this
 */
public function diff($n, $what) {
  if ( !is_int($n) or
       !in_array(
         $what, array(
           'year', 'years', 'month', 'months', 'week', 'weeks', 'day', 'days'
         )
       )
     )
     throw PeopleException::bad_parameters(func_get_args());
  if ($n >= 0) $n = "+$n";
  if (! is_null($this->i_value))
    $this->set( gmdate( 'Y-m-d', strtotime(
      "{$this->i_value} 00:00:00 UTC $n $what"
    )));
  return $this;
}


public function SQLType() { return 's'; }


} // end of Type

?>