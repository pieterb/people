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
 * @subpackage PersistentObjects
 */


/**
 * @package Objects
 * @subpackage PersistentObjects
 */
class ObjectTemplate extends PeopleObject // (or PeopleAObject or PeopleFacet)
{


// Probably, you're gonna want to write your own constructor, like this:
public function __construct( $registry, $properties = array() ) {

  // If people_id is set, then this function is being called by PeopleRegistry,
  // and you can safely assume that $properties contains all property info
  // from the database.
  // If people_id isn't set, however, then someone is trying to create a new instance
  // of your class.
  if ( !array_key_exists( 'people_id', $properties ) ) {

    // Now, this is THE place to do one of the following things:
    // - Check the passed $properties for validity and throw an error in case of
    //   problems.
    // - Provide a default value for certain properties.
    // PeoplePObject::__constructor already checks if required properties (i.e.
    // properties that don't have their PeopleProperty::NULL_ALLOWED flag set)
    // are provided in the $properties array, so you don't have to do that in
    // your derived ctor.

    // TODO This is how you could provide a default property value:
    //if ( !array_key_exists( 'some_property', $properties ))
    //  $properties['some_property'] = 'default';

    // TODO And this is how you should check the value of some parameter:
    //if ( array_key_exists( 'some_other_required_property', $properties) &&
    //     $properties['some_other_required_property'] !== 'some value')
    //  throw PeopleException::bad_parameters(
    //    func_get_args(), 'Value X is invalid for property Y!!!'
    //  );
  }

  // Now, before you decide to do anything else, please call the default
  // constructor, otherwise most, if not all, method calls on $this object
  // will fail! E.g., method metaObject() only works AFTER the default CTor
  // has been called.
  parent::__construct( $properties, $id );

  // Once the parent CTor has been called, you can do whatever you feel
  // is necessary here.

}


/*
 * If you want to protect the properties of your class against corruption, or
 * if you want certain actions to be taken when a property is changed, you can
 * do so here.
 */
protected function set( $name, $value ) {
  // Maybe you want to perform some task if some property is changed:
  if ( $name === 'some_property_name' ) {
    // Perform some business logic.
  }

  // Or protect a property against corruption:
  if ( $name === 'another_property_name' &&
       $value !== 'expected range of values' )
    throw PeopleException::bad_parameters(
      func_get_args(),
      People::tr( 'Some error message' )
    );

  // Probably, the default behaviour is still to call parent::set().
  parent::set( $name, $value );
}


/*
 * Maybe, objects of this class cannot simply be destroyed without
 * side-effects. Maybe, for some reason, you want to protect your class
 * against destruction, or you want to perform some business logic
 * when your object is destroyed. You can implement this by overriding
 * the following method:
 */
public function destroy() {
  // TODO: Your business logic.
  parent::destroy();
}


} // class

?>
