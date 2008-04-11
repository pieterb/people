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

set_include_path( get_include_path() . PATH_SEPARATOR .
                  dirname(__FILE__));

class People {
  public static function tr($string) {
    return PeopleTranslator::inst()->tr($string);
  }
}

?>