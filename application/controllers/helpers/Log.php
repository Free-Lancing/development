<?php

class Zend_Controller_Action_Helper_Log extends Zend_Controller_Action_Helper_Abstract {

    protected $_appIni = null;

    public function __construct() {
        $this->_appIni = new Zend_Config_Ini(APPLICATION_PATH . '/configs/settings.ini', APPLICATION_ENV);
    }

    public function direct($message, $data, $exit = true, $fileWrite = true) {
        if ($this->_appIni !== null && $this->_appIni->debug->mode) {
            if ($fileWrite) {
                $message = PHP_EOL . $message . PHP_EOL;

                if (!empty($data)) {
                    $message = PHP_EOL . $message . ' ===== ' . print_r($data, true) . PHP_EOL . PHP_EOL;
                }

                file_put_contents($this->_appIni->debug->filePath . '/debug.log', $message, FILE_APPEND);

                if ($exit) {
                    exit;
                }

                return true;
            }

            echo '<pre>' . $message;

            if (!empty($data)) {
                echo ' ==== ';
                print_r($data);
            }

            echo '</pre>';

            if ($exit) {
                exit;
            }
        }
    }

}
