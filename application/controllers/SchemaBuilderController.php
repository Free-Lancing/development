<?php

class SchemaBuilderController extends Zend_Controller_Action {

    protected $_params = array();
    protected $_modelObj = array();
    protected $_appIni = null;
    protected $_buildNo = null;
    protected $_executedFunctions = array();

    public function init() {
        $this->_appIni = Zend_Registry::get('APPENV')->env;
        $request = $this->getRequest();
        $this->_params = $request->getParams();
        $this->_modelObj = new Application_Model_SchemaBuildLog();
        $this->_buildNo = (int) $this->_appIni['project']['build'];
    }

    public function indexAction() {
        
    }

    public function setUpProjectAction() {
        // Check if schema buildLog exists and buildNo is 0
        if ($this->_buildNo === 0) {
            $className = 'schema_build' . $this->_buildNo;
            $this->_modelObj->createSchemaBuildLog();
            Zend_Loader::loadClass($className, APPLICATION_PATH . '/../');
            $buildClass = new $className(Zend_Db_Table::getDefaultAdapter());
            $createdFunctions = $this->_modelObj->fetchAssocSchemaBuildLog(array('build_function', 'build_id'), 'status = 1 AND build_no = ' . $this->_buildNo);
            
            $data = $buildClass->executeFunctions(array_keys($createdFunctions), 'execute', $this->_buildNo);
            echo '<pre>';
            print_r($data);
            echo '</pre>';
            exit;
        }
    }

    public function getListAction() {
        
    }

    public function revertAction() {
        $createdFunctions = $this->_modelObj->fetchAssocSchemaBuildLog(array('build_function', 'build_id'), 'status = 0 AND build_no = ' . $this->_buildNo);
        $className = 'schema_build' . $this->_buildNo;
        Zend_Loader::loadClass($className, APPLICATION_PATH . '/../');
        $buildClass = new $className(Zend_Db_Table::getDefaultAdapter());
        $data = $buildClass->executeFunctions(array_keys($createdFunctions), 'revert', $this->_buildNo);
        $this->_modelObj->update(array('status' => 0), 'build_no = ' . $this->_buildNo);
        
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        exit;
    }

    public function updateAction() {
        if ($this->_buildNo > 0) {
            $createdFunctions = $this->_modelObj->fetchAssocSchemaBuildLog(array('build_function', 'build_id'), 'status = 1 AND build_no = ' . $this->_buildNo);
            $className = 'schema_build' . $this->_buildNo;
            Zend_Loader::loadClass($className, APPLICATION_PATH . '/../');
            $buildClass = new $className(Zend_Db_Table::getDefaultAdapter());
            $data = $buildClass->executeFunctions(array_keys($createdFunctions), 'execute', $this->_buildNo);
            
            echo '<pre>';
            print_r($data);
            echo '</pre>';
            exit;
        }
    }

}
