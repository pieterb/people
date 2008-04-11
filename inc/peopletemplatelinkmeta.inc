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
 * @subpackage Templates
 */

/**
 * Template for implementors of new Layer2 meta objects.
 * This class serves only as a template for new persistent object meta
 * classes that derive from PeopleMetaPObject.
 * 
 * All further documentation is in the sources. Good luck.
 * @package People
 * @subpackage Templates
 */
class PeopleTemplateLinkMeta extends PeopleMetaLink {


protected function __construct() {

  // First of all, call the parent CTor:
  parent::__construct('ClassnameOfFacet1', 'ClassnameOfFacet2');

  // This is the right time to describe the properties of this object, as
  // they are defined in the database: (see also PeopleProperty::__construct())
  $this->registerProperty('some_property', 'real property name', PeopleProperty::INTEGER);
  // You can specify more than one, of course.

  // If this facet has any foreign keys, you should register those, too:
  $this->registerForeignKey(
    'some_classname', 'some_property', 'real foreign key name'
  );
  // You can specify more than one, of course.

}


/*
 * Modify this method to return something meaningful
 */
public function realName() {
  return tr( 'Some object' );
}


/*
 * If your object can have any additional facets (and probably, it can)
 * then you should implement this function. Otherwise, omit it altogether.
 */
public function canHaveFacets() {
  $retval = $this->i_parent->canHaveFacets();
  // Add your own facets here...
  $retval[] = 'SomeFacetClassName1';
  $retval[] = 'SomeFacetClassName2';
  return $retval;
}


} // class definition

?>