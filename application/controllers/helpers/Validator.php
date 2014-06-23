<?php

class Zend_Controller_Action_Helper_Validator extends Zend_Controller_Action_Helper_Abstract {

    protected $_appIni = null;

    public function __construct() {
        $this->_appIni = new Zend_Config_Ini(APPLICATION_PATH . '/configs/settings.ini', APPLICATION_ENV);
    }

    public function direct($metadata, $data) {
        $error = array();
        foreach ( $metadata as $key => $value){
            
            if($key == 'first_name' || $key == 'last_name' || $key == 'email' || $key == 'login' || $key == 'password' ){
                $check = $data[$key];
                if( empty($value['NULLABLE']) && empty($check) ){
                    $error['null'][] = $key ;
                }else if( !filter_var($check, FILTER_SANITIZE_STRING)){
                    $error['string'][] = $key ;
                }else if($key == 'email' && !filter_var($check, FILTER_VALIDATE_EMAIL)){
                    $error['email'][] = $check ;
                }else if(strlen($check) > $value['LENGTH']){
                    $error['length'][] = $key ;
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
