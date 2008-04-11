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
 * Abstract superclass of all meta-objects for PeopleObject (sub)classes.
 * @package People
 * @author Pieter van Beek <pieter@djinnit.com>
 * @subpackage MetaObjects
 */
abstract class PeopleMetaLink extends PeopleMetaFacetOwner {


private $i_objectNames;


protected function __construct($realName, $objectname1, $objectname2) {
  parent::__construct($realName);
  $this->i_objectNames = array($objectname1, $objectname2);
}


/**
 * Specifies which facets this link links.
 * @return array An array of two facet classnames
 */
public function linksObjects() {
  return $this->i_objectNames;
}


public function isLink() { return TRUE; }


} // class 

?>