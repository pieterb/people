<?php

/*·************************************************************************
 * Copyright © 2008 by SARA Computing and Networking Services             *
 * pieterb@sara.nl                                                        *
 **************************************************************************/

/**
 * @package People
 * @subpackage Tests
 */


/**
 * @package People
 * @subpackage Tests
 */
class TestEmployeeMeta extends PeopleMetaObject {


protected function __construct() {
  parent::__construct();
  $this->registerProperty( 'name',    PeopleProperty::TEXT,   0 );
  $this->registerProperty( 'address', PeopleProperty::OBJECT, 0 );
  $this->registerProperty( 'manager', PeopleProperty::OBJECT, PeopleProperty::NULL_ALLOWED );
//  $this->registerForeignKey( 'SARAEmployeeSchedule', 'employee_id' );
//  $this->registerForeignKey( 'SARAShift', 'employee_id' );
}


} // class definition

?>