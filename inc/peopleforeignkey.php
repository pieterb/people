<?php

/*·****************************************************************************
 * Copyright © 2005-2006 by Pieter van Beek                                   *
 * pieter@djinnit.com                                                         *
 *                                                                            *
 * This program may be distributed under the terms of the Q Public License as *
 * defined  by  Trolltech AS  of Norway and appearing in the file LICENSE.QPL *
 * included in the packaging of this file.                                    *
 *                                                                            *
 * This  program  is  distributed  in the  hope  that it will  be useful, but *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY *
 * or FITNESS FOR A PARTICULAR PURPOSE.                                       *
 ******************************************************************************/

/**
 * @package People
 * @subpackage Miscellaneous
 * @author Pieter van Beek <pieter@djinnit.com>
 */

/**
 * A Foreign Key Constraint.
 * As a user of PEOPLE, there's no need to understand this class.
 * @internal <p>A class can have foreign keys (FK's). The platform facilitates
 * foreign key constraints (FKC's) in code. By default, a meta-object just
 * inherits its FKC's from its parent object (see {@link getParent() }). If you
 * want to define additional FKC's, you should call {@link registerForeignKey() }
 * from the constructor of your derived metaclass.</p>
 * <p>To describe FKC's, this class defines the following constants:<ul>
 * <li><pre>C_BLOCK</pre> If object A holds a reference to object
 *     B, then object B cannot be destroyed (i.e. {@link
 *     PeopleObject::destructionProbe() } will fail).</li>
 * <li><pre>C_DESTROY</pre> If object A holds a reference to object
 *     B, and object B is destroyed, then object A will be destroyed, too.</li>
 * <li><pre>C_NULL</pre> If object A holds a reference to object
 *     B, and object B is destroyed, then A's reference to B will be set to
 *     NULL.</li>
 * </ul></p>
 * <p>MySQL 5.0 and higher facilitates foreign key constraints on DB
 * level. However, this doesn't provide what we need. Enforcing
 * FKC's in code was a choice, not a missed chance.</p>
 * @author Pieter van Beek <pieter@djinnit.com>
 * @package People
 * @subpackage Miscellaneous
 * @author Pieter van Beek <pieter@djinnit.com>
 */
final class PeopleForeignKey
{


/**#@+ @ignore */
const C_BLOCK = 0;
const C_DESTROY = 1;
const C_NULL = 2;
/**#@-*/


/**#@+ @ignore */
private $i_classname;
private $i_property;
/**#@-*/

/**
 * Constructor.
 * @param string $classname the name of the class in which this foreign key exists.
 * @param string $property the name of the property holding this foreign key.
 * @param string $realName the real name of this foreign key, to be shown in e.g. an
 *               object browser.
 */
public function __construct ( $classname, $property ) {
  $this->i_classname = (string)$classname;
  $this->i_property = (string)$property;
}

/**
 * @return string the class that has the foreign key.
 */
public function classname() { return $this->i_classname; }


/**
 * @return string the name of the property containing the foreign key.
 */
public function property() { return $this->i_property; }


/**
 * See also the defined constants in this class.
 * @return int the type of constraint for this foreign key.
 */
public function constraint() {
  $metaObject = PeopleMetaObject::inst( $this->i_classname );
  $property = $metaObject->property( $this->i_property );
  if ( $property->cascading() )
    return self::C_DESTROY;
  if ( $property->nullAllowed() )
    return self::C_NULL;
  return self::C_BLOCK;
}


} // class 

?>