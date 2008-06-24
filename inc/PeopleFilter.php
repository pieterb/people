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
 * An representation of an SQL select statument.
 * Ok, so you've done your homework, you've read all this documentation, and
 * now, finally, you're gonna write your first script. Now, where do you
 * start?
 * 
 * Well, probably, you want to manipulate some objects in a certain way.
 * But how do you get the right objects to work on? Maybe you want to
 * manipulate all the orders of a certain customer, but where do you start?
 * 
 * This is where the concept of a "filter" comes in. A filter allows you to
 * get a set of objects that answer certain demands.
 * 
 * For example, say you
 * want to get all orders of customer with ID 10000 with a total order
 * amount of more than €2000. You can do that with the following code:
 * <code>
 * // Create a new filter that will operate on "PeopleOrder" objects:
 * $filter = new PeopleFilter( 'PeopleOrder' );
 * // Narrow the filter down to the selection we need:
 * $filter
 *   ->add( 'totalAmount', PeopleFilter::GREATERTHAN, 2000  )
 *   ->add( 'customer',    PeopleFilter::EQUALS,      10000 );
 * // Now ask the registry to return all objects, filtered by $filter:
 * $objects = PeopleRegistry::inst()->getObjects( $filter );
 * </code>
 * Now that's not too hard, is it? As you can see, you can "chain" the calls
 * to {@link add()}, which makes it easy to build a complex filter with a
 * single, simple statement.
 * 
 * <b>Combining</b>
 * 
 * When you construct a new filter, you can optionally specify how to combine
 * the selection criteria. The default is "COMBINE_AND", but you may optionally
 * specify "COMBINE_OR".
 * 
 * Please note that if you choose "COMBINE_OR", and you <i>don't</i> specify
 * any conditions, the call to getIds will return ZERO ids, because there's
 * no condition to match with!
 * @todo Add the possibility to join tables and search for adjoining properties.
 * @package People
 * @subpackage Miscellaneous
 * @author Pieter van Beek <pieter@djinnit.com>
 */
final class PeopleFilter {


/**#@+ @ignore */
const EQUALS = 1;
const NOTEQUALS = 2;
const GREATERTHAN = 3;
const LTE = 4;
const LESSTHAN = 5;
const GTE = 6;
const LIKE = 7;
const NOTLIKE = 8;
const REGEXP = 9;
const NOTREGEXP = 10;
const IN = 11;
const NOTIN = 12;
/**#@-*/

/**#@+ @ignore */
const ASCENDING = 1;
const DESCENDING = 2;
/**#@-*/

/**#@+ @ignore */
const COMBINE_AND = 1;
const COMBINE_OR = 2;
/**#@-*/


private static $si_sql_optable = NULL;

/** @ignore */
public static function init() {
  if (is_null(self::$si_sql_optable))
    self::$si_sql_optable = array(
      self::EQUALS => '=',
      self::NOTEQUALS => '<>',
      self::GREATERTHAN => '>',
      self::LTE => '<=',
      self::LESSTHAN => '<',
      self::GTE => '>=',
      self::LIKE => 'LIKE',
      self::NOTLIKE => 'NOT LIKE',
      self::REGEXP => 'REGEXP',
      self::NOTREGEXP => 'NOT REGEXP',
      self::IN => 'IN',
      self::NOTIN => 'NOT IN'
    );
}


private static $cache = array();


private $i_sql_filter;
private $i_orderby = '';
private $i_limit_offset = NULL;
private $i_limit_count = NULL;
private $i_params = array();
private $i_metaclass;
private $i_combine;
private $i_found_rows = NULL;


/**
 * Constructor.
 * Creates a new filter, that will operate on objects of type $classname.
 * PeopleFilter respects object inheritance, so if you want a filter that
 * operates on <b>all</b> objects, just use 'PeopleObject' as the
 * classname.
 * @param string $classname the name of the class to filter.
 * @param int $combine Either COMBINE_AND or COMBINE_OR, depending on how
 *        you want to combine the selection criteria.
 */
public function __construct($classname, $combine = self::COMBINE_AND) {
  $this->i_metaclass = PeopleMetaObject::inst($classname); // unused?
  $this->i_combine = $combine;
  $this->i_sql_filter = ($this->i_combine === self::COMBINE_AND) ? '1' : '0';
}


/**
 * Alter the filter.
 * @param string $fieldname the name of the database field (=property) on
 *        which to filter.
 * @param int $operator the type of filter to use. This maps one-on-one to
 *        an operator in the WHERE-clause. The following operators can be
 *        used:<ul>
 *        <li><pre>EQUALS</pre> Maps to SQL operator '=', or 'IS NULL' if
 *        $value == NULL.</li>
 *        <li><pre>NOTEQUALS</pre> Maps to SQL operator '<>', or 'IS NOT NULL'
 *        if $value == NULL.</li>
 *        <li><pre>GREATERTHAN</pre> Maps to SQL operator '>'.</li>
 *        <li><pre>LTE</pre> Maps to SQL operator '<='.</li>
 *        <li><pre>LESSTHAN</pre> Maps to SQL operator '<'.</li>
 *        <li><pre>GTE</pre> Maps to SQL operator '>='.</li>
 *        <li><pre>LIKE</pre> Maps to SQL operator 'LIKE'</li>
 *        <li><pre>NOTLIKE</pre> Maps to SQL operator 'NOT LIKE'</li>
 *        <li><pre>REGEXP</pre> Maps to SQL operator 'REGEXP'</li>
 *        <li><pre>NOTREGEXP</pre> Maps to SQL operator 'NOT REGEXP'</li>
 *        </ul>
 * @param mixed $value Can be either an PeopleDBValue, or a just a PHP scalar
 *        type variable (which will be converted to an appropriate
 *        PeopleDBValue object).
 * @return PeopleFilter $this
 */
public function add($fieldname, $operator, $values) {
  $property = $this->i_metaclass->property($fieldname);
  if ($values instanceof PeopleFilter)
    $values = array_keys($values->getIds());
  if (!is_array($values)) $values = array($values);
  foreach ($values as &$value)
    if ( $value instanceof PeopleDBValue )
      $value = clone $value;
    elseif ( $value instanceof PeopleObject )
      $value = new PeopleDBObject($value);
  $this->i_sql_filter .= ($this->i_combine === self::COMBINE_AND) ? ' AND ' : ' OR ';
  if (!count($values)) {
    switch ($operator) {
    case self::IN:
      $this->i_sql_filter .= 0;
      break;
    case self::NOTIN:
      $this->i_sql_filter .= 1;
      break;
    default:
      throw PeopleException::bad_parameters(
        func_get_args(),
        People::tr('Empty array is not allowed with this operator.')
      );
    }
  } else {
    $this->i_sql_filter .= "`$fieldname` ";
    if (is_null($values[0]) or
        $values[0] instanceof PeopleDBValue &&
        is_null($values[0]->value())) {
      switch ($operator) {
      case self::EQUALS:
        $this->i_sql_filter .= 'IS NULL';
        return $this;
      case self::NOTEQUALS:
        $this->i_sql_filter .= 'IS NOT NULL';
        return $this;
      case self::IN:
      case self::NOTIN:
        break;
      default:
        throw PeopleException::bad_parameters(
          func_get_args(),
          People::tr('Value NULL not allowed for this operator.')
        );
      }
    }
    $this->i_sql_filter .= self::$si_sql_optable[$operator];
    $this->i_sql_filter .= ($operator == self::IN)
      ? ' (' . join(', ', array_fill(0, count($values), '?')) . ')'
      : ' ?';
    $this->i_params = array_merge($this->i_params, $values);
  }
  return $this;
}


/**
 * Add an 'ORDER BY' part to the SQL filter statement.
 * You can call this function multiple times, thus sorting on multiple
 * columns.
 * @param string $fieldname the name of the property on which to sort
 * @param int $direction the sorting direction. One of<ul>
 *        <li><pre>ASCENDING</pre>(the default), or</li>
 *        <li><pre>DESCENDING</pre></li>
 *        </ul>
 * @return PeopleFilter $this
 */
public function orderBy($fieldname, $direction = self::ASCENDING) {
  $this->i_orderby .= ($this->i_orderby == '') ? ' ORDER BY ' : ', ';
  $this->i_orderby .= "`$fieldname` " .
    ($direction == self::ASCENDING ? 'ASC' : 'DESC');
  return $this;
}


/**
 * Add an 'LIMIT' part to the SQL filter statement.
 * This can be used for "pagination" of the result.
 * @param int $count the (maximum) number of rows to return. If set to 0,
 *        the number of rows in unlimited.
 * @param int $offset the offset at which to start.
 */
public function setLimit($count = 0, $offset = 0) {
  $this->i_limit_offset = $offset;
  $this->i_limit_count = $count;
  return $this;
}


/**
 * Get the ID's of all objects satisfying this filter.
 * @return array an array of <object_id> => <classname> pairs.
 */
public function getIds($registry) {
  if (!($registry instanceof PeopleRegistry))
    throw PeopleException::bad_parameters(
      func_get_args(),
      People::tr('This is not a PeopleRegistry.')
    );
  if ( !array_key_exists( $this->i_metaclass->classname(), self::$cache ) ) {
    $query = '`PeopleObject`';
    foreach ( array_keys( $this->i_metaclass->allProperties() ) as $classname )
      if ($classname != 'PeopleObject')
        $query = "$query INNER JOIN `$classname` USING(`people_id`)";
    self::$cache[$this->i_metaclass->classname()] = $query;
  }
  if ($this->i_limit_count) {
    $limit = " LIMIT {$this->i_limit_offset}, {$this->i_limit_count}";
  } else {
    $limit = '';
  }
  $result = $registry->execute(
    'SELECT SQL_CALC_FOUND_ROWS `PeopleObject`.`people_id`, `people_classname` FROM ' .
      self::$cache[$this->i_metaclass->classname()] . " WHERE (" .
      $this->i_sql_filter . ")" . $this->i_orderby . $limit,
    $this->i_params
  );
  $retval = array();
  foreach ($result as $row)
    $retval[$row[0]] = $row[1];
  if ($this->i_limit_count) {
    $result = $registry->execute( 'SELECT FOUND_ROWS()' );
    $this->i_found_rows = $row[0][0];
  } else
    $this->i_found_rows = count($retval);
  return $retval;
}


/**
 * @return the total number of objects that satisfy the current filter.
 */
public function totalObjects() {
  if (is_null($this->i_found_rows)) $this->getIds();
  return $this->i_found_rows;
}


public static function flush() { self::$cache = array(); }


} // Class PeopleFilter

PeopleFilter::init();

?>