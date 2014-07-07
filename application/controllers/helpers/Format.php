<?php

class Zend_Controller_Action_Helper_Format extends Zend_Controller_Action_Helper_Abstract {

    protected $_appIni = null;

    public function __construct() {
        $this->_appIni = new Zend_Config_Ini(APPLICATION_PATH . '/configs/settings.ini', APPLICATION_ENV);
    }

    public function direct($metaData, $dataArray) {
        foreach ($dataArray as $fieldName => $fieldValue) {
            $functionName = $metaData[$fieldName]['DATA_TYPE'];

            if ($functionName === 'varchar') {
                $dataArray[$fieldName] = $this->$functionName($fieldName, $fieldValue);
            }
        }

        return $dataArray;
    }

    public function varchar($fieldName, $fieldValue) {
        if ($fieldName === 'password') {
            // code handling for password
        } else {
            //$fieldValue = htmlspecialchars($fieldValue);
        }

        return $fieldValue;
    }

}
