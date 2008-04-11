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
 * <pre>BIGINT</pre> with NULL values allowed.
 * @package People
 * @subpackage DBValue
 * @author Pieter van Beek <pieter@djinnit.com>
 */
class PeopleDBInteger extends PeopleDBValue
{


protected function validate($value) {
  return is_null($value) ? NULL : (int)$value;
}


public function SQLType() { return 'i'; }


} // end of Type

?>