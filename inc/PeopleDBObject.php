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
  if (empty($value)) return NULL;
  if ($value instanceof PeopleObject) return $value->id();
  return (int)$value;
}


public function SQLType() { return 'i'; }


} // end of Type

?>