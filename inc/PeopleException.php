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
 */
class PeopleException extends Exception {


/**
 * A Logical Error.
 */
const E_LOGICAL_ERROR = -1;


/**
 * SQL Constraint Violation.
 * Is thrown by {@link PeopleRegistry::execute()} whenever the user executes
 * an insert or update on the database that would result in a duplicate
 * unique key.
 */
const E_CONSTRAINT = 1;


/**
 * Some object doesn't exist.
 */
const E_NO_SUCH_OBJECT = 2;


/**
 * Something could not be destroyed.
 */
const E_BLOCKED_DESTROY = 3;


/**
 * Some object was out of date.
 */
const E_OUT_OF_DATE = 4;


/**
 * A transaction failed.
 */
const E_TRANSACTION_FAILED = 4;


/**
 * The constructor.
 * @param int $code The code of this exception. See the defined constants in this
 *            class.
 * @param string $message Some human-readable message telling the reason why
 *               this exception was thrown.
 */
public function __construct( $message, $code ) {
  $message = trim($message);
  parent::__construct($message, $code);
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
  return new PeopleLogicalError($message);
}


/**
 * @param array $parameters The parameters with which the failing method
 * was called.
 * @param string $reason a reason why the parameters are bad.
 * @return PeopleException
 */
public static function sql_error( $sql_error ) {
  return new PeopleLogicalError(
    sprintf(
      People::tr("A MySQL error occured:\n%s"),
      $sql_error
    )
  );
}


/**
 * @param string $classname
 * @param string $property
 * @return PeopleException
 */
public static function no_such_property($classname, $property) {
  return new PeopleLogicalError(
    sprintf(
      People::tr('Class "%s" doesn\'t have a property "%s".'),
      $classname, $property
    )
  );
}


/**
 * @param string $classname
 * @param string $property
 * @return PeopleException
 */
public static function read_only($classname, $property) {
  return new PeopleLogicalError(
    sprintf(
      People::tr('Property %s of class %s is read-only.'),
      $property, $classname
    )
  );
}


/**
 * @param string $classname
 * @param string $property
 * @return PeopleException
 */
public static function null_not_allowed($classname, $property) {
  return new PeopleLogicalError(
    sprintf(
      People::tr('Property %s of class %s is can\'t be NULL.'),
      $property, $classname
    )
  );
}


/**
 * @param string $targetclass
 * @param string $sourceclass
 * @param string $sourceprop
 * @return PeopleException
 */
public static function no_such_foreign_key($targetclass, $sourceclass, $sourceprop) {
  return new PeopleLogicalError(
    sprintf(
      People::tr('Class "%s" doesn\'t have a foreign key "%s::%s".'),
      $targetclass, $sourceclass, $sourceprop
    )
  );
}


/**
 * @param mixed $referer
 * @param mixed $referee
 * @param string $propertyName
 * @return PeopleException
 */
public function constraint($mysql_error) {
  return new PeopleRuntimeError(
    sprintf(
      People::tr("A database constraint was violated.\n%s"),
      $mysql_error
    ), self::E_CONSTRAINT
  );
}


/**
 * @param mixed $referer
 * @param mixed $referee
 * @param string $propertyName
 * @return PeopleException
 */
public function blocked_destroy($referer, $referee, $propertyName) {
  if (is_object($referer)) $referer = $referer->id();
  if (is_object($referee)) $referee = $referee->id();
  return new PeopleRuntimeError(
    sprintf(
      People::tr('Property "%s" of object %d blocks destruction of object %d.'),
      $propertyName, $referer, $referee
    ), self::E_BLOCKED_DESTROY
  );
}


/**
 * @param mixed $referee
 * @param string $classname
 * @param string $propertyname
 * @return PeopleException
 */
public function no_such_object(
    $referee, $classname = NULL, $propertyname = NULL
) {
  if (is_null($classname) or is_null($propertname))
    return new PeopleRuntimeError(
      sprintf(
        People::tr('No object with id %s.'),
        $referee
      ), self::E_NO_SUCH_OBJECT
    );
  return new PeopleRuntimeError(
    sprintf(
      People::tr('Property %s in class %s points to non-existing object with id %s'),
      $propertname, $classname, $referee
    ), self::E_NO_SUCH_OBJECT
  );
}


/**
 * @param PeopleObject $object
 * @return PeopleException
 */
public function out_of_date($object) {
  return new PeopleRuntimeError(
    sprintf( People::tr('%s with id %s is out of date.'),
             get_class($object), $object->id() ),
    PeopleException::E_OUT_OF_DATE
  );
}


/**
 * @param PeopleObject $object
 * @return PeopleException
 */
public function transaction_failed($sql_error) {
  return new PeopleRuntimeError(
    sprintf(
      People::tr("A database transaction failed.\n%s"),
      $sql_error
    ), self::E_TRANSACTION_FAILED
  );
}


} // class PeopleException

?>