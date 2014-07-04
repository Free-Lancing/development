<?php

class Zend_Controller_Action_Helper_Validator extends Zend_Controller_Action_Helper_Abstract {

    protected $_appIni = null;

    public function __construct() {
        $this->_appIni = new Zend_Config_Ini(APPLICATION_PATH . '/configs/settings.ini', APPLICATION_ENV);
    }

    public function direct($metadata, $data) {
        $error = array();

        foreach ($metadata as $columnName => $metaColumn) {
            // create different functions for varchar, enum, int/float, date, datetime
            if ($metaColumn['DATA_TYPE'] === 'varchar') {

                $error = $this->checkVarchar($metaColumn['NULLABLE'], $data[$columnName], $columnName);
            } else if (strchr($metaColumn['DATA_TYPE'], 'enum')) {
                $dataType = trim(str_replace(array('enum', '\'', '(', ')'), array('', '', '', ''), $metaColumn['DATA_TYPE']));
                $enumValues = explode(',', $dataType);
                $error = $this->checkEnum($enumValues, $data[$columnName], $columnName);
            }
        }
        echo '<pre>';
        print_r($error);
        echo '</pre>';
        exit;
        return $error;
    }

    public function checkVarchar($metaColumnValue, $valueToBeChecked, $columnName) {

        $returnError = array();
        if (empty($metaColumnValue) && empty($valueToBeChecked)) {
            $returnError[$columnName]['null'] = '';
        } else if (!filter_var($valueToBeChecked, FILTER_SANITIZE_STRING)) {
            $returnError[$columnName]['alphabets'] = 'Not String';
        } else if ($columnName === 'email' && !filter_var($valueToBeChecked, FILTER_VALIDATE_EMAIL)) {
            $returnError[$columnName]['email'] = '';
        } else if (strlen($valueToBeChecked) > $metaColumnValue) {
            $returnError[$columnName]['max_length'] = '';
        }  else {
           $returnError ='';
        }

        return $returnError;
    }

    function checkEnum($enumArray, $checkValue, $colName) {
        $returValue = array();
        if (!in_array($checkValue, $enumArray)) {
            $returValue[$colName]['enum'] = '';
        }else{
            $returValue= '';
        }
        
        return $returValue;
    }

}
