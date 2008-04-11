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
 * @package Objects
 * @subpackage MetaObjects
 */


/**
 * @package Objects
 * @subpackage MetaObjects
 */
class ObjectTemplateMeta extends PeopleMetaObject {


protected function __construct() {
  parent::__construct();
  $this->registerProperty(
    'some_property',
    PeopleProperty::SOME_TYPE,
    PeopleProperty::COMBINED_FLAGS,
    'some_classname'
  );
  $this->registerForeignKey( 'some_classname', 'some_property' );
}


} // class definition

?>
