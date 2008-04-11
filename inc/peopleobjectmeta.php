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
 * @subpackage MetaObjects
 * @author Pieter van Beek <pieter@djinnit.com>
 */

/**
 * @package People
 * @subpackage MetaObjects
 * @author Pieter van Beek <pieter@djinnit.com>
 */
final class PeopleObjectMeta extends PeopleMetaObject {
  
public function __construct() {
  $this->registerProperty(
    'people_id',
    PeopleProperty::INTEGER,
    PeopleProperty::READ_ONLY
  );
  /*
   * The version of this object.
   *
   * Newly created (still unpersisted) objects have version 0. When the object
   * is persisted for the first time, this is done in a two phases:
   * - first, empty SQL INSERT statements are executed, to insert rows for
   *   this object in the database. The rows in the database are filled with
   *   default values. The "version" field in the DB is now 0.
   * - second, SQL UPDATE statements are executed, to persist all object data,
   *   and the version number of the object is incremented by 1.
   *
   * For already persisted objects, only the second phase is executed.
   *
   * When object persistence takes place, the current version in memory is
   * compared with the version in the database. If there's a mismatch,
   * persistence fails, and {@link persist()} will throw a {@link PeopleException}.
   * @var object PeopleDBInteger
   */
  $this->registerProperty(
    'people_version',
    PeopleProperty::INTEGER,
    PeopleProperty::READ_ONLY
  );
  $this->registerProperty(
    'people_modified',
    PeopleProperty::DATETIME,
    PeopleProperty::READ_ONLY | PeopleProperty::NULL_ALLOWED
  );
}

} // class

?>