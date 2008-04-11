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
 * A boolean field in the database.
 * This Object persistence layer defines a boolean as follows in the database:
 * <pre>TINYINT NOT NULL DEFAULT 0</pre>
 * @package People
 * @subpackage DBValue
 * @author Pieter van Beek <pieter@djinnit.com>
 */
class PeopleDBBoolean extends PeopleDBValue
{


public function sql() {
  return $this->i_value ? 1 : 0;
}


protected function validate($value) {
  return $value ? TRUE : FALSE;
}


public function SQLType() { return 'i'; }


} // end of Type

?>