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
 * @subpackage PersistentObjects
 */


/**
 * Object
 * @package People
 * @subpackage PersistentObjects
 */
abstract class PeopleLink extends PeopleObject {


/**
 * The constructor
 * @param array $p_properties for new links, keys 'linked_object_1' and 'linked_object_2' are required.
 */
public function __construct( $p_properties = array(), $p_id = NULL ) {
  if ( !array_key_exists( 'linked_object_1', $p_properties ) ||
       !array_key_exists( 'linked_object_2', $p_properties ) )
    throw new PeopleException(
      'Required parameters \'linked_object_1\' and/or \'linked_object_2\' missing.',
      PeopleException::E_BAD_PARAM
    );
  parent::__construct( $p_properties, $p_id );
}


/**
 * @internal Checks for attempts to change properties 'linked_object_1' or 'linked_object_2'.
 * @throws object PeopleException E_ACCESS_DENIED
 */
public function set($name, &$value) {
  if ( in_array( $name, array( 'linked_object_1', 'linked_object_2' ) ) )
    throw new PeopleException(
      tr("Property '$name' is immutable."), 
      PeopleException::E_ACCESS_DENIED
    );
  $parent->set($name, $value);
}


}

?>