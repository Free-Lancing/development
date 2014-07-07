<?php

class Zend_Controller_Action_Helper_Validate extends Zend_Controller_Action_Helper_Abstract {

    protected $_appIni = null;

    public function __construct() {
        $this->_appIni = new Zend_Config_Ini(APPLICATION_PATH . '/configs/settings.ini', APPLICATION_ENV);
    }

    public function direct($metaData, $dataArray) {
        $error = array();
        
        foreach ($dataArray as $fieldName => $fieldValue) {
            $functionName = (strpos($metaData[$fieldName]['DATA_TYPE'], 'enum') !== FALSE) ? 'enum' : $metaData[$fieldName]['DATA_TYPE'];
            $error[$fieldName] = $this->$functionName($metaData[$fieldName], $fieldName, $fieldValue);

            if (empty($error[$fieldName])) {
                unset($error[$fieldName]);
            }
        }
        
        return $error;
    }

    public function varchar($metaData, $fieldName, $fieldValue) {
        $errorTypes = array();

        if (strlen($fieldValue) > $metaData['LENGTH']) {
            $errorTypes['max_length'] = $metaData['LENGTH'];
        }

        if (!$metaData['NULLABLE'] && trim($fieldValue) === '') {
            $errorTypes['empty'] = '';
        }

        if ($fieldName === 'email' && !filter_var($fieldValue, FILTER_VALIDATE_EMAIL)) {
            $errorTypes['email'] = '';
        }

        return $errorTypes;
    }

    public function int($metaData, $fieldName, $fieldValue) {
        $errorTypes = array();

        if (!$metaData['NULLABLE'] && trim($fieldValue) === '') {
            $errorTypes['empty'] = '';
        }

        return $errorTypes;
    }

    function enum($metaData, $fieldName, $fieldValue) {
        $errorTypes = array();

        if (!$metaData['NULLABLE'] && trim($fieldValue) === '') {
            $errorTypes['empty'] = '';
        }

        preg_match_all("`'([^']*)'`", $metaData['DATA_TYPE'], $values);

        if (!in_array($fieldValue, $values[1])) {
            $errorTypes['invalid_value'] = $fieldValue;
        }

        return $errorTypes;
    }

}
