<?php

class Zend_Controller_Action_Helper_Constants extends Zend_Controller_Action_Helper_Abstract {

    public function direct($variableName, $data) {
        // Field_Name => DB_Field_Name;
        $userDetails = array('fName' => 'first_name', 'lName' => 'last_name', 'email' => 'email',
                            'userName' => 'login', 'password' => 'password', 'gender' => 'gender',);
        
        
        return $this->formArray($$variableName, $data);
    }
    
    public function formArray($headerArray, $data) {
        foreach($headerArray as $elementName => $dbField) {
            $dataArray[$dbField] = (!empty($data[$elementName]) ? $data[$elementName] : '');
        }
        
        return $dataArray;
    }

}
