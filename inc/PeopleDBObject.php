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
 * An object value in the database.
 * The preferred representation of this class in the database is
 * <pre>BIGINT UNSIGNED</pre>.
 * @package People
 * @subpackage DBValue
 * @author Pieter van Beek <pieter@djinnit.com>
 */
class PeopleDBObject extends PeopleDBValue
{


/**
 * Allows both objects and integers.
 * Internally, PeopleDBObject represents the object as an integer, namely the
 * object ID. But the "setter" {@link set()} accepts both an integer and an object reference.
 * Cool huh?
 */
protected function validate($value) {
  if (is_null($value)) return NULL;
  if (!preg_match('/^\\s*(\\d+)\\s*$/', "$value", $matches))
    throw PeopleException::bad_parameters(func_get_args());
  return $matches[1];
}


public function value() {
  return ((string)(int)($this->i_value) == $this->i_value) ?
    (int)($this->i_value) : $this->i_value;
}


public function sql() {
  return is_null($this->i_value) ? NULL : (string)$this->i_value;
}


public function SQLType() { return 's'; }


} // end of Type

?>