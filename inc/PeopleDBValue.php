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
 * @subpackage DBValue
 * @author Pieter van Beek <pieter@djinnit.com>
 */

/**
 * Abstract superclass for all PeopleDB* database value classes.
 * This class presents a common interface to all different kinds of database
 * values. This is handy, because different kinds of data (text, integer,
 * currency etc.) require different HTML display methods, different
 * representations on HTML forms, different ways of persistence etcetera.
 *
 * Apart from serving as an interface, this class also has some static
 * utility functions, like gpc_get() and factory().
 *
 * Subclasses are advised to (re)implement the following members:
 * - validate()
 * - mysqliType()
 *
 * @internal This base class serves two purposes:
 *   - it defines a common interface for all PeopleDB* objects
 *   - it provides some basic functionality that all subclasses can use.
 * I'm aware that this latter purpose is actually bad design: common
 * functionality (AKA a common implementation) should be <i>delegated</i>
 * instead or <i>inherited</i>. But for now, this implementation suffices.
 * @package People
 * @subpackage DBValue
 * @author Pieter van Beek <pieter@djinnit.com>
 */
abstract class PeopleDBValue
{


/**
 * Current value, in PHP format.
 * Unless you're a PEOPLE developer, you can ignore this.
 * @ignore
 * @var mixed
 * @see value()
 */
protected $i_value;


/**
 * The original value, in PHP format.
 * "Original" means: is it is in the database.
 * @var mixed
 * @see reset()
 */
private $i_orig;


/**
 * Default constructor.
 * Subclasses may reimplement this CTOR, but probably that's not
 * necessary.
 * @param $p_value the value of the property. This value will be forwarded
 *                 to $this->set(), which should do a validity check.
 */
public function __construct($p_value = NULL) {
  $this->set($p_value);
  $this->i_orig = $this->i_value;
}


/** The current value of this object, as an appropriate PHP datatype. */
public function value() { return $this->i_value; }
/** Resets this property to it's original (persisted) value. */
public function reset() { return $this->i_value = $this->i_orig; }


/**
 * Tells this object that it has been persisted.
 * This means that calls to {@link reset() } will henceforward reset this object
 * to its current value. The {@link changed() } flag will be reset.
 * @return mixed The current value
 */
public function persisted() { return $this->i_orig = $this->i_value; }


/**
 * Checks if the current value differs from the original, persisted value.
 * Returns TRUE if this object has changed since its construction,
 *         otherwise FALSE.
 * @return boolean
 */
public function changed() { return $this->i_value !== $this->i_orig; }


/**
 * Sets the current value.
 * Parameter $p_value is sent through {@link validate() }.
 * @param mixed $p_value the value, stupid!
 * @return PeopleDBValue $this
 */
public function set($p_value) {
  $this->i_value = $this->validate($p_value);
  return $this;
}


/**
 * Validates a value.
 * Subclasses of PeopleDBValue may override this member to do some value
 * validation. The default implementation does no validation at all.
 * Hence, there's no use calling <pre>parent::validate()</pre> in your
 * implementation.
 */
protected function validate($value) { return $value; }


/**
 * The PDO parameter type of this object.
 * @return int one of 'i', 'd', 's', 'b'
 */
abstract public function SQLType();


/**
 * The MySQL value of this object.
 * See mysql_stmt_bind_params() in the PHP manual for details.
 * @return mixed a value that can be used as a SQL parameter. By default,
 *         this is the same as the PHP-representation of the value. But
 *         there are exceptions. E.g. the BOOLEAN value is FALSE or TRUE in
 *         PHP, but 'N' or 'Y' in the database;
 */
public function sql() {
  return $this->value();
}


} // end of Type

?>