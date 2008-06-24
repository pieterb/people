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
class TestAddressMeta extends PeopleMetaObject {


protected function __construct() {
  parent::__construct();
  $this->registerProperty( 'address', PeopleProperty::TEXT, 0 );
  $this->registerForeignKey( 'TestEmployee', 'address' );
}


} // class definition

?>