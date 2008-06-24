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
 * Registry of all Persistent Objects in one database
 * <i>No object comes to life but through me.</i>
 *
 * You cannot achieve <i>anything</i> with Layer 1 object persistence
 * without using this class.
 * @package People
 * @subpackage Miscellaneous
 * @author Pieter van Beek <pieter@djinnit.com>
 */
class PeopleRegistry
{


////////////////////////////////////////////////////////////////////////////
//                               STATE                                    //
////////////////////////////////////////////////////////////////////////////

const MAX_PACKET_SIZE = 4096;

/**
 * Cache of partial SQL statements.
 * @var array
 */
private $i_cache = array();


/**
 * An array of classnames, indexed by object_id.
 * @var array
 */
private $i_class_by_id;


/**
 * An array of id=>1 pairs of destroyed objects.
 * @var array
 */
private $i_destroyed_flags;


/**
 * An array of id=>1 pairs of dirty objects.
 * @internal dirty objects are objects, marked for persistence.
 * @var array
 */
private $i_dirty_flags;


/**
 * An array of objects, indexed by classname and id.
 * @var array
 */
private $i_objects;


/**
 * MySQLi object.
 * @var mysqli
 */
private $i_mysqli;


/**
 * The MySQLi database handle.
 * @return mysqli
 */
public function mysqli() {
  return $this->i_mysqli;
}


/** @var string */
private $i_prefix;


/**
 * The table name prefix.
 * @return string
 */
public function prefix() {
  return $this->i_prefix;
}


/** @var array */
private $i_stmtcache = array();


/**
 * Constructor
 * @param mysqli $mysqli The mysqli object for this registry.
 * @param string $prefix The tablename prefix for this registry.
 */
public function __construct(mysqli $mysqli, $prefix = '') {
  $this->i_mysqli = $mysqli;
  $this->i_prefix = "$prefix";
  $this->flush();
}


/**
 * Wrapper around getObject().
 * {@link getObject()} returns NULL if the requested object cannot be found.
 * This method asserts that a valid object be returned. If the object cannot be found,
 * an exception is thrown.
 * @param integer $object_id see {@link getObject()}
 * @param string $classname see {@link getObject()}
 * @return object PeopleObject an object
 */
public function assertObject( $object_id, $classname = NULL ) {
  $retval = $this->getObject( $object_id, $classname );
  if ( is_null( $retval ) )
    if ($object_id instanceof PeopleFilter)
      throw PeopleException::logical_error(
        People::tr( 'No objects in filter.' )
      );
    else
      throw PeopleException::no_such_object($object_id);
  return $retval;
}


/**
 * A wrapper around {@link getObjects()}.
 * @param mixed $object_id an object id (int) or filter (PeopleFilter)
 * @param string $classname optional; the classname of this object.
 * @return object PeopleObject an object, or NULL
 */
public function getObject($object_id, $classname = NULL) {
  if ($object_id instanceof PeopleObject) {
    trigger_error(
      People::tr('PeopleRegistry::getObject called with PeopleObject.'),
      E_USER_WARNING
    );
    return $object_id;
  }
  if ($object_id instanceof PeopleFilter)
    $retval = $this->getObjects($object_id);
  else
    $retval = $this->getObjects(array((int)$object_id => $classname));
  return count( $retval ) ? array_shift( $retval ) : NULL;
}


/**
 * 
 * @param mixed $p_objects Either an array with $object_id => $classname
 * pairs, or a PeopleFilter object.
 * @return array an array of PeopleObjects, indexed by ID
 */
public function getObjects($p_objects) {
  if (is_string($p_objects))
    $p_objects = new PeopleFilter($p_objects);
  // If the parameter is a filter instead of an array, then use this filter.
  if ($p_objects instanceof PeopleFilter)
    $p_objects = $p_objects->getIds($this);
  // Get a list of objects that are not yet loaded.
  $objects = array_diff_key( $p_objects, $this->i_class_by_id );
  // If there are still unloaded objects...
  if (count($objects)) {
    // then load them.
    // First, determine the class of each requested object.
    $objects_by_class = array();
    foreach ($objects as $id => $classname)
      $objects_by_class[ $classname ? $classname : '' ][] = $id;
    // Determine the class of classless objects.
    if (array_key_exists('', $objects_by_class)) {
      $result = $this->execute(
        'SELECT `people_id`, `people_classname` FROM `PeopleObject` '.
        'WHERE `people_id` IN (' . implode($objects_by_class[''], ',') . ')'
      );
      foreach ($result as $row)
        $objects_by_class[$row[1]][] = $row[0];
      unset($objects_by_class['']);
    }

    foreach ($objects_by_class as $class => $ids) {
      if (!isset($this->i_cache[$class])) {
        $allProperties = PeopleMetaObject::inst($class)->allProperties();
        $tables = '`PeopleObject`';
        $columns = '`PeopleObject`.`people_id`, `people_version`, `people_modified`';
        foreach ( $allProperties as $classname => $props )
          if ($classname != 'PeopleObject') {
            $tables = "$tables INNER JOIN `$classname` USING(`people_id`)";
            foreach ($props as $propname => $property)
              $columns .= ", `$propname`";
          }
        $this->i_cache[$class] = "SELECT $columns FROM $tables WHERE `PeopleObject`.`people_id` IN ";
      }
      $result = $this->execute(
        $this->i_cache[$class] . '(' . implode($ids, ',') . ')'
      );
      foreach ($result as $row)
        eval( "new $class(\$this, \$row );" );
    } // foreach $objects_by_class
  } // if count($objects)

  $retval = array();
  foreach ( array_keys( $p_objects ) as $id )
    if ( !isset( $this->i_destroyed_flags[$id] ) &&
         isset( $this->i_class_by_id[$id] ) )
      $retval[$id] = $this->i_objects[$this->i_class_by_id[$id]][$id];
  return $retval;
}


/**
 * Persists all changes made until now.
 * Objects marked as destroyed are removed from the database, and objects
 * marked as dirty are persisted.
 * @return object PeopleRegistry $this
 */
public function persist() {
  $this->begin();
  try {
    $changed = $this->i_dirty_flags + $this->i_destroyed_flags;
    ksort($changed, SORT_NUMERIC);
    foreach ($changed as $id => $classname)
      if ($this->i_objects[$classname][$id]->changed())
        $this->i_objects[$classname][$id]->persist();
    $this->commit();
  }
  catch (Exception $e) {
    $this->rollback();
    throw $e;
  }
  foreach ($this->i_destroyed_flags as $id => $classname) {
    unset( $this->i_objects[$classname][$id] );
    unset( $this->i_class_by_id[$id] );
  }
  $this->i_destroyed_flags = array();
  $this->i_dirty_flags = array();
  return $this;
} // end of member function persistAll


/**
 * All dirty objects of a certain class.
 * @return array an array of all dirty objects of class $classname, indexed by id.
 * @param string $classname the name of the class of which you want all dirty objects.
 */
public function dirtyObjectsByClassname( $classname ) {
  return array_key_exists($classname, $this->i_objects) ?
    array_intersect_key( $this->i_objects[$classname], $this->i_dirty_flags ) :
    array();
}


/**
 * Flushes the registry.
 * Removes all objects from the registry. Pending changes will be lost!
 */
public function flush() {
  $this->i_objects = array();
  $this->i_dirty_flags = array();
  $this->i_destroyed_flags = array();
  $this->i_class_by_id = array();
  PeopleFilter::flush();
  return $this;
}


////////////////////////////////////////////////////////////////////////////
//                         DATABASE FUNCTIONS                             //
////////////////////////////////////////////////////////////////////////////


/**
 * Prepares an SQL statement.
 * This method is a wrapper around mysqli_stmt_prepare().
 * @internal We need to index all query strings that pass through here.
 * But these queries can be very long, so using them as keys in an
 * associative array isn't very nice. That's why I'm using a fast hashing
 * algorithm here, to speed things up. Instead of storing the
 * statement into $this->i_stmtcache[$query], I store it into
 * <pre>$this->i_stmtcache[\<hashed_query>][$query]</pre>.
 * @return mysql
 * @param string $statement The SQL statement
 */
public function prepare($statement) {
  //trigger_error($statement, E_USER_NOTICE);
  $key = "$statement\n" . @serialize($driver_options);
  $hash = mhash(MHASH_TIGER160, $key);
  if (!isset($this->i_stmtcache[$hash][$key])) {
    $statement = preg_replace('/`People/', "`{$this->i_prefix}People", $statement);
    //trigger_error($statement, E_USER_NOTICE);
    $this->i_stmtcache[$hash][$key] =
      $this->i_mysqli->prepare($statement);
    if (!$this->i_stmtcache[$hash][$key])
      throw PeopleException::sql_error( $this->i_mysqli->error );
  }
  return $this->i_stmtcache[$hash][$key];
}


/**
 * Binds parameters to a statement, than executes it.
 * Can be called in two ways:<ul>
 * <li>execute($stmt, $param1, $param2, ...)</li>
 * <li>execute($stmt, array($param1, $param2, ...))</li>
 * </ul>
 * @param mixed $statement a mysqli_stmt or string
 * @param PeopleDBValue $params
 * @return mixed array rows from a select, or the number of affected rows.
 */
public function execute() {
  $args = func_get_args();
  if (count($args) < 1)
    throw PeopleException::bad_parameters(
      $args, People::tr('No statement provided.')
    );
  $stmt = array_shift($args);
  if (is_string($stmt))
    $stmt = $this->prepare($stmt);
  if (count($args) == 1 && is_array($args[0]))
    $args = $args[0];
  //trigger_error(var_export($args, TRUE), E_USER_NOTICE);
  foreach ($args as &$value)
    if ( $value instanceof PeopleObject )
      $value = new PeopleDBObject( $value );
  $params = array('');
  $lobs = array();
  foreach ($args as $arg) {
    if ( $arg instanceof PeopleDBValue ) {
      $type = $arg->SQLType();
      $arg = $arg->sql();
    } else {
      $type = 's';
    }
    if (strlen($arg) > self::MAX_PACKET_SIZE) {
      $parameternumber = strlen($params[0]);
      $lobs[$parameternumber] = $arg;
      $params[0] .= 'b';
      $params[] = NULL;
    } else {
      $params[0] .= $type;
      $params[] = $arg;
    }
  }
  if ( count($args) &&
       !call_user_func_array(array($stmt, 'bind_param'), $params))
    throw PeopleException::sql_error(
      sprintf(
        People::tr("Can't bind params: %s\n%s"),
        var_export($params, TRUE), $stmt->error
      )
    );
  if ( count($lobs) )
    foreach ($lobs as $key => $value) {
      $start = 0;
      while ($start < strlen($value)) {
        if ( ! $stmt->send_long_data(
                 $key,
                 substr( $value, $start, self::MAX_PACKET_SIZE )
               ) )
          throw PeopleException::sql_error( $stmt->error );
        $start += self::MAX_PACKET_SIZE;
      }
    }
    if (!$stmt->execute()) {
    if ($stmt->errno == 1062)
      throw PeopleException::constraint( $stmt->error );
    else
      throw PeopleException::sql_error( $stmt->error );
  }
  if (!($metadata = $stmt->result_metadata()))
    return $stmt->affected_rows;

  if (!$stmt->store_result())
    throw PeopleException::sql_error( $stmt->error );
  $fields = $metadata->fetch_fields();
  $call = 'return mysqli_stmt_bind_result($stmt';
  for ($i = 0; $i < count($fields); $i++)
    $call .= ", \$result[$i]";
  $call .= ');';
  if (!eval($call))
    throw PeopleException::sql_error(
      sprintf(
        People::tr('Can\'t bind result: %s'),
        $stmt->error
      )
    );
  $retval = array();
  while ($stmt->fetch()) {
    $row = count($retval);
    $retval[$row] = array();
    for ($i = 0; $i < count($fields); $i++)
      $retval[$row][] = $result[$i];
    for ($i = 0; $i < count($fields); $i++)
      $retval[$row][$fields[$i]->name] = $result[$i];
    for ($i = 0; $i < count($fields); $i++)
      $result[$i] = NULL;
  }
  $stmt->free_result();
  return $retval;
//    try {
//    $stmt->execute();
//  }
//  catch (PDOException $e) {
//    foreach ($fh as $f) fclose($f);
//    if (substr($e->getCode(), 0, 2) == '23')
//      throw new PeopleException(
//        $e->getMessage(),
//        PeopleException::E_CONSTRAINT
//      );
//    throw $e;
//  }
//  foreach ($fh as $f) fclose($f);
//  return $stmt;
}


/**
 * Fabricates a new Unique Database Object ID.
 * @throws object PeopleException E_MYSQL_ERROR
 * @return int a new unique ID.
 */
public function uid() {
  $stmt = $this->execute('INSERT INTO `PeopleIDSequence` () VALUES ()');
  $insertid = $this->i_mysqli->insert_id;
  $this->execute(
    'DELETE FROM `PeopleIDSequence` WHERE id < ?',
    new PeopleDBInteger($insertid)
  );
  return $insertid;
}


/**
 * Starts a transaction.
 * By default, a MySQL database connection has "autocommit" set to 1 (true).
 * This method sets autocommit to 0 (false) and hence starts a transaction
 * that can be committed or rolled back.
 * @return object PeopleDatabase $this
 * @throws object PeopleException E_MYSQL_ERROR
 * @see commit(), rollback()
 */
public function begin() {
  if (!$this->i_mysqli->autocommit(FALSE))
    throw PeopleException::sql_error(
      sprintf(
        People::tr('Setting autocommit failed: %s'),
        $this->i_mysqli->error
      )
    );
  return $this;
}


/**
 * Commits the last transaction.
 * Additionally, it sets "autocommit" on the current database connection
 * back to 1 (true). So if you want to begin the next transaction, you must
 * call begin() again.
 * @return object PeopleDatabase $this
 * @throws object PeopleException E_MYSQL_ERROR
 * @see begin(), rollback()
 */
public function commit() {
  if (!$this->i_mysqli->commit())
    throw PeopleException::transaction_failed(
      $this->i_mysqli->error
    );
  if (!$this->i_mysqli->autocommit(TRUE))
    throw PeopleException::sql_error(
      sprintf(
        People::tr('Setting autocommit failed: %s'),
        $this->i_mysqli->error
      )
    );
  return $this;
}


/**
 * Rolls back the last transaction.
 * Additionally, it sets "autocommit" on the current database connection
 * back to 1 (true). So if you want to begin the next transaction, you must
 * call begin() again.
 * @return object PeopleDatabase $this
 * @throws object PeopleException E_MYSQL_ERROR
 * @see begin(), commit()
 */
public function rollback() {
  if (!$this->i_mysqli->rollback())
    throw PeopleException::sql_error(
      sprintf(
        People::tr('Rollback failed: %s'),
        $this->i_mysqli->error
      )
    );
  if (!$this->i_mysqli->autocommit(TRUE))
    throw PeopleException::sql_error(
      sprintf(
        People::tr('Setting autocommit failed: %s'),
        $this->i_mysqli->error
      )
    );
  return $this;
}
////////////////////////////////////////////////////////////////////////////
//                           FRIEND FUNCTIONS                             //
////////////////////////////////////////////////////////////////////////////


/**
 * Tells the Registry that some object has been changed.
 * @param int object_id
 * @return PeopleRegistry $this
 */
public function changed($object_id) {
  if (!isset($this->i_class_by_id[$object_id]))
    throw PeopleException::logical_error(
      sprintf(
        People::tr('Unknown object %s claims to have changed.'),
        $object_id
      )
    );
  $this->i_dirty_flags[$object_id] = $this->i_class_by_id[$object_id];
  return $this;
} // end of member function setDirty


/**
 * Tells the Registry that some object has been destroyed.
 * @param int object_id
 * @return object PeopleRegistry $this
 */
public function destroyed($object_id) {
  if (!isset($this->i_class_by_id[$object_id]))
    throw PeopleException::logical_error(
      sprintf(
        People::tr('Unknown object %s claims to be destroyed.'),
        $object_id
      )
    );
  $this->i_destroyed_flags[$object_id] = $this->i_class_by_id[$object_id];
  return $this;
}


/**
 * Registers a new object in this registry.
 * Called by PeopleObject::__construct().
 * @param PeopleObject $object
 * @return PeopleRegistry $this
 */ 
public function registerObject(PeopleObject $object) {
  $id = $object->id();
  $classname = get_class($object);
  if ( isset( $this->i_class_by_id[$id] ) ) {
    throw PeopleException::logical_error(
      sprintf(
        People::tr( '%s %d has been recreated!' ),
        get_class($object), $id
      )
    );
  }
  $this->i_class_by_id[$id] = $classname;
  $this->i_objects[$classname][$id] = $object;
  return $this;
}


/**
 * Called by PeopleObject::destroy().
 * @return PeopleRegistry $this
 */
public function unregisterObject($object_id) {
  unset ( $this->i_objects[$this->i_class_by_id[$object_id]][$object_id] );
  unset ( $this->i_class_by_id[$object_id] );
  return $this;
}


} // end of PeopleRegistry


?>