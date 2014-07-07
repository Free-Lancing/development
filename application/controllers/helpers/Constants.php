<?php

class Zend_Controller_Action_Helper_Constants extends Zend_Controller_Action_Helper_Abstract {

    public function direct($variableName) {
        // Display Name => array(Field_Name => Field_Name, DB_Field_Name => DB_Field_Name);
        $userDetails = array('First Name' => array('field_name' => 'fname', 'db_field_name' => 'first_name'),
                            'Last Name' => array('field_name' => 'lname', 'db_field_name' => 'last_name'),
                            'First Name' => array('field_name' => 'fname', 'db_field_name' => 'first_name'),
                            'First Name' => array('field_name' => 'fname', 'db_field_name' => 'first_name'),
                            'First Name' => array('field_name' => 'fname', 'db_field_name' => 'first_name'),
                            'First Name' => array('field_name' => 'fname', 'db_field_name' => 'first_name'),
                            'First Name' => array('field_name' => 'fname', 'db_field_name' => 'first_name'),
                            'First Name' => array('field_name' => 'fname', 'db_field_name' => 'first_name'),
                            'First Name' => array('field_name' => 'fname', 'db_field_name' => 'first_name'),
                            'First Name' => array('field_name' => 'fname', 'db_field_name' => 'first_name'),
                            'First Name' => array('field_name' => 'fname', 'db_field_name' => 'first_name'));
        return $$variableName;
    }

}
