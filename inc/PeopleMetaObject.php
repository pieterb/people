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
 * Abstract superclass of all meta-objects.
 * @internal MySQL 5.0 and higher facilitates foreign key constraints on DB
 *           level. However, this doesn't provide what we need. Enforcing
 *           FKC's in code was a choice, not a missed chance.
 * @package People
 * @subpackage MetaObjects
 * @author Pieter van Beek <pieter@djinnit.com>
 */
abstract class PeopleMetaObject extends PeopleSingleton {


/**
 * A nested array of PeopleProperty objects.
 * @var array
 * <code>array(
 *   &lt;classname&gt; => array(
 *     &lt;property_name&gt; => PeopleProperty
 *   )
 * )</code>
 */
private $i_properties = array();


/**
 * @var array A numeric array of foreign keys.
 */
private $i_foreign_keys = array();


/**
 * This is a very magic constructor.
 * Look at the implementation and stand in awe.
 * @param $aggregated_by string Name of the aggregating property, if any.
 */
protected function __construct() {
  parent::__construct();
  // As a convention, the meta class for <CLASS> is called <CLASS>Meta
  // We make use of that convention here: we just cut of the 'Meta' part
  // to know for which class we are the meta class.
  if ( substr( get_class( $this ), -4) != 'Meta' )
    throw PeopleException::logical_error(
      sprintf( 'Metaclass %s doesn\'t satisfy naming convention!',
               get_class( $this ) )
    );
  if ($this->parentMeta()) {
    $this->i_properties = $this->parentMeta()->i_properties;
    $this->i_foreign_keys = $this->parentMeta()->i_foreign_keys;
  }
}


/**
 * Returns the aggregating property, or NULL if there isn't any.
 * @return PeopleProperty
 */
public function aggregatedBy() {
  foreach ($this->i_properties as $classname => $props)
    foreach ($props as $name => $prop)
      if ($prop->cascading())
        return $prop;
  return NULL;
}


/**
 * Returns the name of the class for which this is a metaclass.
 * @return string
 */
public function classname() {
  return substr( get_class( $this ), 0, -4 );
}


/**
 * Returns the metaclass of the parent of the class for which this is a metaclass.
 * @return PeopleMetaObject
 */
public function parentMeta() {
  $parentClassName = get_parent_class( $this->classname() );
  return $parentClassName ? self::inst( $parentClassName ) : NULL;
}


/**
 * Register a property for this class.
 * For more info about the parameters, please check
 * {@link PeopleProperty::__construct()}
 * @param string $name
 * @param string $realName
 * @param int $type
 * @param int $flags
 * @param mixed $default The default value for this property
 * @return PeopleMetaObject $this
 */
protected function registerProperty( $name, $type, $flags = 0, $default = NULL ) {
  $this->i_properties[$this->classname()][$name] = 
    new PeopleProperty( $name, $type, $flags, $default );
  return $this;
}


/**
 * Register a Foreign Key for this class.
 * @param string $classname see {@link PeopleForeignKey::__construct() }
 * @param string $property_name see {@link PeopleForeignKey::__construct() }
 * @return PeopleMetaObject $this
 */
protected function registerForeignKey($classname, $property_name)
{
  $this->i_foreign_keys["$classname::$property_name"] =
    new PeopleForeignKey( $classname, $property_name );
  return $this;
}


/**
 * @param string $classname the name of the class for which the metaobject is requested.
 * @return object PeopleMetaObject the metaobject for class $classname.
 */
public static function inst($classname) {
  $retval = self::instance("{$classname}Meta");
  return $retval ? $retval : eval( "return new {$classname}Meta();" );
}


/**
 * Property info.
 * <pre>array(
 *   &lt;classname> => array(
 *     &lt;property_name> => PeopleProperty
 *   )
 * )</pre>
 * @return array
 */
public function allProperties() {
  return $this->i_properties;
}


/**
 * Properties for this class only.
 * @return array an array op PeopleProperty objects, indexed by property name.
 */
public function properties() {
  return $this->i_properties[$this->classname()];
}


/**
 * Fetch a specific property.
 * @param string $property_name the name of the property to fetch.
 * @return object PeopleProperty a property object.
 */
public function property($property_name) {
  foreach ( $this->i_properties as $classname => $props )
    if ( isset( $props[$property_name] ) ) {
      return $props[$property_name];
    }
  throw PeopleException::no_such_property($this->classname(), $property_name);
}


/**
 * Fetch all foreign keys.
 * The returned array is indexed by a string "classname::propertyname".
 * @return array an array of PeopleForeignKey objects.
 */
public function foreignKeys()
{
  return $this->i_foreign_keys;
}


/**
 * Fetch a foreign key.
 * @param string $classname
 * @param string $property
 * @return PeopleForeignKey
 */
public function foreignKey($classname, $property)
{
  if (!array_key_exists("$classname::$property", $this->i_foreign_keys))
    throw PeopleException::no_such_foreign_key(
      $this->classname(), $classname, $property
    );
  return $this->i_foreign_key["$classname::$property"];
}


} // class 

?>