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
 * @subpackage Exceptions
 * @author Pieter van Beek <pieter@djinnit.com>
 */

/**
 * Superclass of all exceptions in the platform.
 *
 * Whenever an exception of type E_LOGICAL_ERROR or
 * E_MYSQL_ERROR is thrown, the message <i>and</i> a backtrace are dumped
 * to the debugger.
 *
 * The other types of exceptions, though "exceptional", do not necessarily
 * indicate an error, and hence do not result in debugger output.
 *
 * For more information about exceptions, see the section about the built-in
 * Exception class in the PHP manual.
 * @package People
 * @subpackage Exceptions
 * @author Pieter van Beek <pieter@djinnit.com>
 * @see PeopleDebugger
 */
class PeopleException extends Exception {


/**
 * A runtime error.
 * Runtime errors are those that can only be detected at runtime. This is
 * the default exception type, if it cannot be classified any other way.
 */
const E_RUNTIME_ERROR = -1;


/**
 * SQL Constraint Violation.
 * Is thrown by {@link PeopleRegistry::execute()} whenever the user executes
 * an insert or update on the database that would result in a duplicate
 * unique key.
 */
const E_CONSTRAINT = -2;


/**
 * Some object doesn't exist.
 */
const E_NO_SUCH_OBJECT = -3;


/**
 * Something could not be destroyed.
 */
const E_BLOCKED_DESTROY = -4;


/**
 * Some object was out of date.
 */
const E_OUT_OF_DATE = -5;


/**
 * Logical error.
 * An exception of this type is thrown if a condition occurs that indicates
 * a flaw in the application logic.
 */
const E_LOGICAL_ERROR = 1;


/**
 * Bad parameters.
 * An exception of this type is thrown by a method if it detects that it
 * received bad parameters.
 */
const E_BAD_PARAMS = 2;


/**
 * Logical Error.
 * An exception of this type is thrown if a condition occurs that indicates
 * a flaw in the application logic.
 */
const E_READ_ONLY = 3;


/**
 * Something could not be destroyed.
 */
const E_NO_SUCH_PROPERTY = 4;


/**
 * Some foreign key doesn't exist.
 */
const E_NO_SUCH_FOREIGN_KEY = 5;


/**
 * The constructor.
 * @param int $code The code of this exception. See the defined constants in this
 *            class.
 * @param string $message Some human-readable message telling the reason why
 *               this exception was thrown.
 */
public function __construct(
  $message = NULL,
  $code = self::E_LOGICAL_ERROR
) {
  if ($message === NULL)
    $message = People::tr('No message');
  $message = trim($message);
  parent::__construct($message, $code);
//  trigger_error(
//    sprintf(
//      People::tr("An exception was thrown. Stack trace:\n%s\nMessage:\n%s"),
//      $this->getTraceAsString(), $message
//    ), E_USER_NOTICE
//  );
}


private static function derecursify($p) {
  static $depth = 0;
  if (is_object($p)) return 'PHP object of class ' . get_class($p);
  if (is_resource($p)) return 'Some PHP Resource';
  if (is_array($p))
    if ($depth < 3) {
      $depth++;
      foreach ($p as &$value)
        $value = self::derecursify($value);
      $depth--;
      return $p;
    } else
      return 'Some PHP array';
  return $p;
}


/**
 * Constructor.
 * @param array $parameters The parameters with which the failing method
 * was called.
 * @param string $reason a reason why the parameters are bad.
 * @return PeopleException
 */
public static function bad_parameters($parameters, $reason = NULL) {
  $message = sprintf(
    People::tr("Unexpected parameters:\n%s"),
    var_export(self::derecursify($parameters), TRUE)
  );
  if ($reason !== NULL)
    $message .= sprintf(People::tr("\nReason: %s"), $reason);
  return new PeopleException($message, self::E_BAD_PARAMS);
}


/**
 * Constructor.
 * @param mixed $referer
 * @param mixed $referee
 * @param string $propertyName
 * @return PeopleException
 */
public function blocked_destroy($referer, $referee, $propertyName) {
  if (is_object($referer)) $referer = $referer->id();
  if (is_object($referee)) $referee = $referee->id();
  return new PeopleException(
    sprintf(
      People::tr('Property "%s" of object %d blocks destruction of object %d.'),
      $propertyName, $referer, $referee
    ), self::E_BLOCKED_DESTROY
  );
}


/**
 * @param string $classname
 * @param string $property
 * @return PeopleException
 */
public function no_such_property($classname, $property) {
  return new PeopleException(
    sprintf(
      People::tr('Class "%s" doesn\'t have a property "%s".'),
      $classname, $property
    ), self::E_NO_SUCH_PROPERTY
  );
}


/**
 * @param string $classname
 * @param string $property
 * @return PeopleException
 */
public function read_only($classname, $property) {
  return new PeopleException(
    sprintf(
      People::tr('Property %s of class %s is read-only.'),
      $property, $classname
    ), self::E_READ_ONLY
  );
}


/**
 * @param string $targetclass
 * @param string $sourceclass
 * @param string $sourceprop
 * @return PeopleException
 */
public function no_such_foreign_key($targetclass, $sourceclass, $sourceprop) {
  return new PeopleException(
    sprintf(
      People::tr('Class "%s" doesn\'t have a foreign key "%s::%s".'),
      $targetclass, $sourceclass, $sourceprop
    ), self::E_NO_SUCH_FOREIGN_KEY
  );
}


/**
 * Constructor.
 * @param mixed $referer
 * @param mixed $referee
 * @param string $propertyName
 * @return PeopleException
 */
public function no_such_object($id, $classname = NULL, $property = NULL) {
  $id = (int)$id;
  if (is_null($classname) or is_null($property))
    return new PeopleException(
      sprintf(
        People::tr('No object with id %d.'),
        $id
      ), self::E_NO_SUCH_OBJECT
    );
  return new PeopleException(
    sprintf(
      People::tr('No object with id %d'),
      $id
    ), self::E_NO_SUCH_OBJECT
  );
}


/**
 * @param PeopleObject $object
 * @return PeopleException
 */
public function out_of_date($object) {
  return new PeopleException(
    sprintf( People::tr('%s with id "%d" is out of date.'),
             get_class($object), $object->id() ),
    PeopleException::E_OUT_OF_DATE
  );
}



} // class PeopleException

?>