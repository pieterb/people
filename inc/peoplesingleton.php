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
 * Base class of all singletons in the platform.
 * If you don't know what a singleton is, please surf the web. E.g., you
 * could look in {@link http://en.wikipedia.org/wiki/Singleton_pattern Wikipedia}.
 *
 * There's no strict reason to have a single superclass for all singleton
 * classes in your project. It is convenient, though. E.g., you can check if
 * some $object is a singleton by calling <code>$object instance_of PeopleSingleton</code>
 *
 * Also, if your project has many singletons, there are usually some
 * dependancies between them. By having all singletons derive from
 * PeopleSingleton, it will be easier in the future to implement a singleton
 * registry. (If you don't know what that is: never mind.)
 * @package People
 * @subpackage Miscellaneous
 * @author Pieter van Beek <pieter@djinnit.com>
 */
abstract class PeopleSingleton {


private static $i_registry = array();


/**
 * Accessor of self::$i_registry.
 * @param string $classname
 * @return object The instance of class $classname
 */
protected static function instance($classname) {
  return array_key_exists($classname, self::$i_registry) ?
    self::$i_registry[$classname] : NULL;
}


/**
 * The constructor.
 * Adds this object to the registry.
 */
protected function __construct() {
  self::$i_registry[get_class($this)] = $this; // Register this object.
} // function __construct


/**
 * The destructor.
 * Removes this instance from the registry.
 */
public function __destruct() {
  unset (self::$i_registry[get_class($this)]);
} // function __destruct()


/**
 * Private dummy implementation.
 * This implementation of __clone() does nothing. It's declared private
 * here to prevent subclasses being cloned (AKA 'copied').
 */
private function __clone() {}


} // class Singleton


?>