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
class TestManagerMeta extends PeopleMetaObject {


protected function __construct() {
  parent::__construct();
  $this->registerProperty( 'unitname', PeopleProperty::TEXT,   0 );
//  $this->registerForeignKey( 'SARAEmployeeSchedule', 'employee_id' );
//  $this->registerForeignKey( 'SARAShift', 'employee_id' );
}


} // class definition

?>