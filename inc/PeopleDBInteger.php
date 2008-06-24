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
 * An integer in the database.
 * The preferred representation of this class in the database is
 * <pre>BIGINT</pre>.
 * @package People
 * @subpackage DBValue
 * @author Pieter van Beek <pieter@djinnit.com>
 */
class PeopleDBInteger extends PeopleDBValue
{


protected function validate($value) {
  if (is_null($value)) return NULL;
  if (!preg_match('/^\\s*([\\-+]?)\\s*(\\d+)\\s*$/', "$value", $matches))
    throw PeopleException::constraint(
      sprintf( People::tr('Not a well-formed integer: %s'), $value )
    );
  if ($matches[1] == '+') $matches[1] = '';
  return $matches[1] . $matches[2];
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