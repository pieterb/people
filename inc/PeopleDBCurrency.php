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
 * An currency value in the database.
 * This value is just a number, not the "currency" of the value, like "Euro"
 * or "Dollar". If you want to store that kind of information, you should
 * probably do that in a separate {@link PeopleDBText Text} object.
 * @package People
 * @subpackage DBValue
 * @author Pieter van Beek <pieter@djinnit.com>
 */
class PeopleDBCurrency extends PeopleDBValue
{


protected function validate($value) {
  return is_null($value) ? NULL : (double)$value;
}


public function SQLType() { return 'd'; }


} // end of Type

?>