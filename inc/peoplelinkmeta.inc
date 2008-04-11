<?php

/*·****************************************************************************
 * Copyright © 2005-2006 by Pieter van Beek                                   *
 * pieter@djinnit.com                                                         *
 *                                                                            *
 * This program may be distributed under the terms of the Q Public License as *
 * defined  by  Trolltech AS  of Norway and appearing in the file LICENSE.QPL *
 * included in the packaging of this file.                                    *
 *                                                                            *
 * This  program  is  distributed  in the  hope  that it will  be useful, but *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY *
 * or FITNESS FOR A PARTICULAR PURPOSE.                                       *
 ******************************************************************************/

/**
 * @package People
 * @subpackage MetaObjects
 */

/**
 * @package People
 * @subpackage MetaObjects
 */
class PeopleLinkMeta extends PeopleMetaPObject {


protected function __construct() {
  parent::__construct(/*tr*/ 'Link', NULL, NULL);
  $this->registerProperty( 'linked_object_1', /*tr*/ 'Linked object 1', PeopleProperty::OBJECT, 'PeopleFacet' );
  $this->registerProperty( 'linked_object_2', /*tr*/ 'Linked object 2', PeopleProperty::OBJECT, 'PeopleFacet' );
}


} // class 

?>