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
 * @subpackage PersistentObjects
 * @author Pieter van Beek <pieter@djinnit.com>
 */

/**
 * Superclass of all persistent object classes.
 * @package People
 * @subpackage PersistentObjects
 * @author Pieter van Beek <pieter@djinnit.com>
 */
abstract class PeopleObject
{


////////////////////////////////////////////////////////////////////////////
//                                 STATE                                  //
////////////////////////////////////////////////////////////////////////////

  
/** @var boolean */
private $i_almost_destroyed = FALSE;


/** @var boolean */
private $i_destroyed = FALSE;


/**
 * Indicates that this object has been destroyed.
 * @return boolean
 */
public function destroyed() { return $this->i_destroyed; }


/**
 * The id of this object.
 * Synonym for $object->people_id.
 * @return int
 */
public function id() { return $this->people_id; }


/**
 * An array of PeopleDBValue objects, indexed by property name.
 * @var array
 */
protected $i_properties;


/**
 * Returns an array with all (cloned) PeopleProperty objects for this class.
 * @return array
 */
public function dbvalue($name) {
  if ( ( $p = $this->metaObject()->property($name) ) &&
       $p->deprecated() ) {
    $e = new Exception();
    trigger_error(
      sprintf( "Someone's querying deprecated property %s.%s.\n",
               get_class($this), $name ) . $e->getTraceAsString(),
      E_USER_WARNING
    );
  }
  return clone $this->i_properties[$name];
}


/** @var PeopleRegistry */
private $i_registry;


/**
 * The PeopleRegistry of this object.
 *
 * @return PeopleRegistry
 */
public function registry() {
  return $this->i_registry;
}


////////////////////////////////////////////////////////////////////////////
//                             CTORS AND DTORS                            //
////////////////////////////////////////////////////////////////////////////

  
/**
 * Generic constructor.
 * This constructor has 2 functions:
 * <ol>
 * <li>To construct an object in memory that is already in the database.
 *     In this case, $p_id specifies the ID of this object, and
 *     $p_properties is an array with name-value pairs for all properties.</li>
 * <li>To create a new object. In this case, $p_id is NULL, and $p_properties
 *     is an optional array with name-value pairs for all properties that
 *     you need to have initialized on construction. E.g., an PeopleFacet
 *     requires an PeopleObject to relate to.</li>
 * </ol>
 * The first type of invocation of the CTor is done exclusively by the
 * PeopleRegistry, that constructs persistent objects in memory on demand.
 * 
 * The second invocation can be done from anywhere in the code. Newly
 * created objects register themselves in the PeopleRegistry.
 * @param $p_registry PeopleRegistry
 * @param $p_properties array
 */
public function __construct( $p_registry, $p_properties = array() ) {
  $this->i_registry = $p_registry;
  $properties = $this->metaObject()->allProperties();
  if (!is_array($p_properties))
    throw PeopleException::bad_parameters(func_get_args());
  if (isset($p_properties['people_id'])) {
    foreach ( $properties as $props )
      foreach ( $props as $propname => $prop )
        $this->i_properties[$propname] = $prop->dbvalue( $p_properties[$propname] );
    $p_registry->registerObject($this);
  } else {
    $p_properties['people_id'] = $p_registry->uid();
    $p_properties['people_version'] = 0;
    foreach ( $properties as $classname => $props )
      foreach ( $props as $propname => $prop ) {
        $dbvalue = array_key_exists( $propname, $p_properties ) ?
          $p_properties[$propname] :
          $prop->defaultValue();
        if (is_null($dbvalue) && !$prop->nullAllowed())
          throw PeopleException::bad_parameters(
            func_get_args(),
            "Property '$propname' shouldn't be NULL"
          );
        $this->i_properties[$propname] = $prop->dbvalue( $dbvalue );
      }
    $p_registry->registerObject($this);
    $p_registry->changed($this->people_id);
  }
} // end of member function __construct


////////////////////////////////////////////////////////////////////////////
//                               PROPERTIES                               //
////////////////////////////////////////////////////////////////////////////

/**
 * Magic setter.
 * This magic setter first checks if the current user has write permissions
 * for this object, and if so, it invokes protected method set(), that you
 * may override to implement any non-standard property setting.
 * @param string $name the name of the property to set.
 * @param mixed $value the value to set this property to.
 * @throws object PeopleException E_ACCESS_DENIED if
 * - the current user doesn't have write permissions on this object;
 * - if the user tries to set an not-null property to value NULL;
 * - if the user tries to set a readOnly property.
 * @see set()
 */
final public function __set( $name,  $value ) {
  $property = $this->metaObject()->property($name);
  if ($property->readOnly())
    throw PeopleException::read_only(get_class($this), $name);
  if ( is_null($value) && !$property->nullAllowed() )
    throw PeopleException::bad_parameters(func_get_args());
  $this->set( $name,  $value );
}


/**
 * Virtual property setter.
 * The default implementation just set the persistent property $name to
 * value $value, without any error checking (apart from the error checking
 * done by the PeopleDBValue for this property).
 *
 * If you want smarter error checking or some other non-standard property
 * handling in your subclass, you should override this method.
 * @param string $name the name of the property to set.
 * @param mixed $value the value to set this property to.
 * @return void
 */
protected function set( $name, $value ) {
//  $property = $this->metaObject()->property($name);
//  if (!is_null($property->classname()) && !is_null($value)) {
//    if (!($value instanceof PeopleObject))
//      $value = $this->i_registry->assertObject($value);
//    $classname = $property->classname();
//    if (!($value instanceof $classname))
//      throw PeopleException::bad_parameters(func_get_args());
//  }
  $this->i_properties[$name]->set( $value );
  $this->registry()->changed($this->people_id);
} // end of member function __set


/**
 * Magic getter.
 * This getter just returns a property value. It's just a wrapper around
 * <code>return $this->i_properties[$name]->value();</code> with some error
 * checking.
 * @param string $name the name of the property to get
 * @return mixed
 * @throws object PeopleException E_BAD_PARAM if the property doesn't exist.
 */
final public function __get( $name ) {
  if ( ( $p = $this->metaObject()->property($name) ) &&
       $p->deprecated() ) {
    $e = new Exception();
    trigger_error(
      sprintf( "Someone's querying deprecated property %s.%s.\n",
               get_class($this), $name ) . $e->getTraceAsString(),
      E_USER_WARNING
    );
  }
  return $this->get( $name );
}


protected function get( $name ) {
  if (!array_key_exists($name, $this->i_properties))
    throw PeopleException::no_such_property(get_class($this), $name);
  return $this->i_properties[$name]->value();
}


/**
 * Returns the object pointed to by property $name
 * @param string $name
 * @return PeopleObject
 */
public function object($name) {
  if ( !( $p = $this->metaObject()->property($name) ) ||
       $p->type() != PeopleProperty::OBJECT )
    throw PeopleException::no_such_property(get_class($this), $name);
  $value = $this->get($name);
  return is_null($value) ? NULL : $this->i_registry->assertObject($value);
}


/**
 * Returns the objects for a foreign key.
 * @param string $classname
 * @param string $property
 * @return array PeopleObjects, indexed by id.
 */
public function objects($classname, $property) {
  $f = new PeopleFilter($classname);
  $f->add($property, PeopleFilter::EQUALS, $this->id());
  return $this->i_registry->getObjects($f);
}


/**
 * Checks if this object has changed since the latest DB sync.
 * New (not yet persistent) and destroyed objects always return TRUE.
 * Other objects check if any of their properties has changed.
 * 
 * PeopleRegistry::persist() uses this function to check if an object
 * needs persistence.
 * @see PeopleRegistry::persist()
 * @return bool TRUE if this object has changed, otherwise FALSE.
 */
public function changed() {
  if (!$this->people_version || $this->destroyed()) // || $this->i_connections_changed
    return TRUE;
  foreach ($this->i_properties as $value)
    if ($value->changed()) return TRUE;
  return FALSE;
}


////////////////////////////////////////////////////////////////////////////////
//                            OBJECT DESTRUCTION                              //
////////////////////////////////////////////////////////////////////////////////


/**
 * Destroys this object and its depending objects.
 * For all objects, foreign keys are checked. If some foreign key points to
 * $this object, then appropriate action is taken. This can be one of the 
 * following:<ul>
 *   <li>if the foreign key has the PeopleProperty::CASCADING flag, then the
 *   object pointing to $this object will be destroyed, too.</li>
 *   <li>otherwise, if the foreign key has the PeopleProperty::NULL_ALLOWED
 *   flag, then the foreign key value will be set to 'null'.</li>
 *   <li>otherwise (which is the default) an exception is thrown.</li>
 * </ul>
 * @throws PeopleDestroyException if destruction of this object or its
 * depending objects fails.
 */
public function destroy() {
	if ($this->i_almost_destroyed) return;
  if ($this->destroyed()) {
    trigger_error(
      __CLASS__ . '::destroy() called for destroyed ' . get_class($this) .
      ' ' . $this->people_id,
      E_USER_WARNING
    );
    return;
  }
  $this->i_almost_destroyed = TRUE;
  try {
	  foreach ($this->metaObject()->foreignKeys() as $fk) {
	    $objects = $this->objects($fk->classname(), $fk->property());
	    $dirty_objects = $this->registry()->dirtyObjectsByClassname($fk->classname());
	    foreach ($dirty_objects as $id => $object) {
	      if ( array_key_exists( $id, $objects ) &&
	           $object->__get( $fk->property() ) != $this->people_id )
	        unset( $objects[$id] );
	      elseif ( !array_key_exists( $id, $objects ) &&
	           $object->__get( $fk->property() ) == $this->people_id )
	        $objects[$id] = $object;
	    }
	    $constraint = $fk->constraint();
	    foreach ($objects as $id => $object) {
	      switch ($constraint) {
	      case PeopleForeignKey::C_DESTROY:
	        $object->destroy();
	        break;
	      case PeopleForeignKey::C_NULL:
	        $object->__set($fk->property(), NULL);
	        break;
	      default:
	        throw PeopleException::blocked_destroy(
            $this->people_id,
	          $object->id(),
	          $fk->property()
	        );
	      } // switch ($fk->constraint())
	    } // foreach ($objects as $id => $object)
	  } // foreach ($this->metaObject()->foreignKeys() as $fk)
  }
  catch (Exception $e) {
    $this->i_almost_destroyed = FALSE;
    throw $e;
  }
  $this->i_destroyed = TRUE;
  $this->registry()->destroyed($this->people_id);
}


////////////////////////////////////////////////////////////////////////////////
//                                VARIOUS                                     //
////////////////////////////////////////////////////////////////////////////////


/**
 * Writes this object to the database.
 * You should never call this function directly! Use PeopleRegistry::persist()
 * instead.
 * @return PeopleObject $this
 */
public function persist() {
  if (!$this->changed()) return $this;
  if ($this->destroyed()) {
    if (!$this->people_version) return $this;
    $result = $this->registry()->execute(
      'DELETE FROM `PeopleObject` WHERE `people_id` = ? AND `people_version` = ?',
      $this->people_id, $this->people_version
    );
    if ($result == 0)
      throw PeopleException::out_of_date($this);
    foreach (array_keys($this->metaObject()->allProperties()) as $classname)
      if ($classname != __CLASS__)
        $this->registry()->execute(
          "DELETE FROM `$classname` WHERE `people_id` = ?", $this->people_id
        );
    $this->registry()->unregisterObject( $this->people_id );
    return $this;
  }

  if (!$this->people_version) {
    foreach (array_keys( $this->metaObject()->allProperties() ) as $classname)
      if ($classname != __CLASS__)
        $this->registry()->execute(
          "INSERT INTO `$classname` (`people_id`) VALUES (?)",
          $this->people_id
        );
    $this->registry()->execute(
      'INSERT INTO `' . __CLASS__ . '` (`people_id`, `people_classname`, `people_version`) VALUES (?, ?, 0)',
      new PeopleDBInteger( $this->people_id ),
      new PeopleDBText( get_class( $this ) )
    );
  }

  $properties = $this->metaObject()->allProperties();
  $persistedProperties = array();
  foreach ($properties as $classname => $props) 
    if ($classname != __CLASS__) {
      $query = $params = array();
      foreach ($props as $propname => $property)
        if ( $this->people_version == 0 ||
             $this->i_properties[$propname]->changed() ) {
          $query[] = "`$propname` = ?";
          $params[] = $this->i_properties[$propname];
          $persistedProperties[] = $propname;
        }
      if (count($query)) {
        $params[] = new PeopleDBInteger( $this->people_id );
        $this->registry()->execute(
          "UPDATE `$classname` SET " . join(', ', $query) . ' WHERE `people_id` = ?',
          $params
        );
      }
    }

  if ( !$this->registry()->execute(
         'UPDATE `' . __CLASS__ . '` SET `people_version` = `people_version` + 1, `people_modified` = NULL WHERE `people_id` = ? AND `people_version` = ?',
         $this->people_id,
         $this->people_version
       )
     )
    throw PeopleException::out_of_date($this);
  foreach ($persistedProperties as $propname)
    $this->i_properties[$propname]->persisted();
  $this->i_properties['people_version'] =
    new PeopleDBInteger($this->people_version + 1);
  return $this;
}


/**
 * This object's MetaObject.
 * @return PeopleMetaObject
 */
public function metaObject() {
  return PeopleMetaObject::inst( get_class( $this ) );
}


} // class PeopleObject


?>