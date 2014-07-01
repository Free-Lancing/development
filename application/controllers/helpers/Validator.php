<?php

class Zend_Controller_Action_Helper_Validator extends Zend_Controller_Action_Helper_Abstract {

    protected $_appIni = null;

    public function __construct() {
        $this->_appIni = new Zend_Config_Ini(APPLICATION_PATH . '/configs/settings.ini', APPLICATION_ENV);
    }

    public function direct($metadata, $data) {
        $error = array();
        
        foreach ( $metadata as $key => $value){
            
            if($value['DATA_TYPE'] == 'varchar'){
                $check = $data[$key];
                if( empty($value['NULLABLE']) && empty($check) ){
                    $error[$key] = 'Null Value' ;
                }else if( !filter_var($check, FILTER_SANITIZE_STRING)){
                    $error[$key] = 'Not String' ;
                }else if($key == 'email' && !filter_var($check, FILTER_VALIDATE_EMAIL)){
                    $error[$key] = 'Email Invalid' ;
                }else if(strlen($check) > $value['LENGTH']){
                    $error[$key] = 'Exceeds max length ' . $value['LENGTH'];
                }
            }else if( preg_match('/^enum\((.*)\)$/', $value['DATA_TYPE'], $matches)){
                  foreach( explode(',', $matches[1]) as $val )
                 {
                     $enum[] = trim( $val, "'" );
                 }
                 if(!in_array($data[$key], $enum)){
                     $error[$key] = 'gender not selected';
                 }
                
            }
        }
       
        if(!empty($error)){
            return $error;
        }else{
            return "success";
        }
        
    }
    
    

}
