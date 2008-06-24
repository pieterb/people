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
 * @subpackage Miscellaneous
 * @author Pieter van Beek <pieter@djinnit.com>
 */

/**
 * Brief description.
 * <b>Class constants</b>
 *
 * This class defines the following constants:<ul>
 * <li>BOOLEAN (see class {@link PeopleDBBoolean })</li>
 * <li>CURRENCY (see class {@link PeopleDBCurrency })</li>
 * <li>DATE (see class {@link PeopleDBDate })</li>
 * <li>DATETIME (see class {@link PeopleDBDateTime })</li>
 * <li>INTEGER (see class {@link PeopleDBInteger })</li>
 * <li>LOB (see class {@link PeopleDBLOB })</li>
 * <li>OBJECT (see class {@link PeopleDBObject })</li>
 * <li>TEXT (see class {@link PeopleDBText })</li>
 * </ul>
 * @package People
 * @subpackage Miscellaneous
 * @author Pieter van Beek <pieter@djinnit.com>
 */
final class PeopleProperty
{


/**#@+ @ignore */
const BOOLEAN  =  4;
const CURRENCY =  3;
const DATE     =  9;
const DATETIME = 11;
const INTEGER  =  1;
const OBJECT   =  7;
const TEXT     =  2;
/**#@-*/


/**
 * Indicates that this property is read-only.
 */
const READ_ONLY = 1;

/**
 * Indicates that a property is deprecated.
 * The only effect of this flag is that when a property marked deprecated is
 * accessed, a warning is written to the debugger. This allows a developer
 * to check if a production version of the software might still be using
 * deprecated properties.
 */
const DEPRECATED = 2;

/**
 * Indicates that value NULL is allowed for this property.
 */
const NULL_ALLOWED = 4;

/**
 * Indicates a foreign key with cascading deletion properties.
 */
const CASCADING = 8;


private $i_defaultValue;


public function defaultValue() {
  return $this->i_defaultValue;
}


/** @var int */
private $i_type;


/**
 * @return int one of the type constants defined in {@link PeopleDBValue }.
 */
public function type() { return $this->i_type; }


/** @var string */
private $i_name;


/** @return string */
public function name() { return $this->i_name; }


private $i_flags;


/**
 * @return boolean TRUE if a NULL value is valid for this property.
 */
public function nullAllowed() {
  return (bool)($this->i_flags & self::NULL_ALLOWED);
}


/**
 * @return boolean TRUE if this property is read-only.
 * @todo this meta-property has little to no consequences in the rest
 *       of the code. That should be improved. Maybe method {@link PeopleObject::__set()}
 *       should throw an exception if a modification of a read-only property is attempted.
 */
public function readOnly() {
  return (bool)($this->i_flags & self::READ_ONLY);
}


/**
 * @return boolean TRUE if this property is deprecated.
 */
public function deprecated() {
  return (bool)($this->i_flags & self::DEPRECATED);
}


/**
 * @return boolean TRUE if this property is cascading.
 * @see PeopleObject::destroy()
 */
public function cascading() {
  return (bool)($this->i_flags & self::CASCADING);
}


///**
// * @param int $type_id One of the defined constants of this class.
// * @return string a human readable name for $type_id, in the language of
// *         the current user.
// */
//public function typeName() {
//  switch ($this->i_type) {
//  case self::BOOLEAN  : return People::tr('Boolean');
//  case self::CURRENCY : return People::tr('Currency');
//  case self::DATE     : return People::tr('Date');
//  case self::DATETIME : return People::tr('Date & Time');
//  case self::INTEGER  : return People::tr('Integer');
//  case self::LOB     :  return People::tr('LOB');
//  case self::OBJECT   : return People::tr('Object');
//  case self::TEXT     : return People::tr('Text');
//  default:
//    throw new PeopleException(
//      "Unknown property type {$this->i_type}"
//    );
//  }
//}


/**
 * Constructor.
 * @param string $name the name of this property in the {@link PeopleObject} that
 *        contains it.
 * @param int $type one of the type constants defined in {@link PeopleDBValue }.
 * @param int $flags any combination (binary OR-ed) of the defined constants of this
 *        class: CASCADING, DEPRECATED, and NULL_ALLOWED and READ_ONLY. Defaults to
 *        NULL_ALLOWED.
 * @param mixed $default the default value for this property.
 * @todo review
 */
public function __construct (
  $name, $type, $flags = NULL, $default = NULL
) {
  if (is_null($flags)) $flags = self::NULL_ALLOWED;
  $this->i_name = (string)$name;
  $this->i_type = (int)$type;
  $this->i_flags = $flags;
  $this->i_defaultValue = $default;
}


/**
 * Creates a new instance of type $type_id.
 * @param mixed $p_value forwarded to the constructor.
 * @return PeopleDBValue a new PeopleDBValue instance if the type designated
 * by $type_id.
 */
public function dbvalue($p_value = NULL) {
  switch ($this->i_type) {
  case PeopleProperty::BOOLEAN:
    return new PeopleDBBoolean ($p_value);
  case PeopleProperty::CURRENCY:
    return new PeopleDBCurrency($p_value);
  case PeopleProperty::DATE:
    return new PeopleDBDate    ($p_value);
  case PeopleProperty::DATETIME:
    return new PeopleDBDateTime($p_value);
  case PeopleProperty::INTEGER:
    return new PeopleDBInteger ($p_value);
  case PeopleProperty::OBJECT:
    return new PeopleDBObject  ($p_value);
  case PeopleProperty::TEXT:
    return new PeopleDBText    ($p_value);
  default:
    throw PeopleException::logical_error(
      sprintf(
        People::tr('Unknown type %s'),
        $this->i_type
      )
    );
  }
}


} // class 

?>