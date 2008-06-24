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
class PeopleLogicalError extends PeopleException {


/**
 * The constructor.
 * @param string $message Some human-readable message telling the reason why
 *               this exception was thrown.
 */
public function __construct( $message ) {
  parent::__construct($message, self::E_LOGICAL_ERROR);
}


} // class PeopleException

?>