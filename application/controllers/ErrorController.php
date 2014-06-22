<?php
class ErrorController extends Zend_Controller_Action {
    public function init() {
        // Throwing Custom errors
        //throw new Plugin_Exceptions_NoAccess('You do not have permissions to access this page');
        //throw new Plugin_Exceptions_MaintenanceMode('This web page is in maintenance mode');
        $this->_errors = $this->_getParam('error_handler');
        $this->view->message = $this->_errors->exception->getMessage();
    }

    /**
     * Default Error handler action
     */
    public function errorAction() {
        switch ($this->_errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
                $this->_forward('page-not-found');
                break;
                
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
                $this->_forward('page-not-found');
                break;
                
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                $this->_forward('page-not-found');
                break;
            
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER:
                if ($this->_errors->exception instanceof Plugin_Exceptions_NoAccess) {
                    $this->_forward('no-access');
                } else if ($this->_errors->exception instanceof Plugin_Exceptions_MaintenanceMode) {
                    $this->_forward('maintenance');
                } else if ($this->_errors->exception instanceof Plugin_Exceptions_MySql) {
                    $this->_forward('mysql');
                }
                
                break;
                
            default:
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                break;
        }
    }

    public function itemAction() {
        $this->getResponse()->setHttpResponseCode(404);
    }
    
    public function noAccessAction() {
        $this->getResponse()->setHttpResponseCode(401);
    }

    public function maintenanceAction() {
        $this->getResponse()->setHttpResponseCode(503);
    }
    
    public function mysqlAction() {
        $this->getResponse()->setHttpResponseCode(500);
    }
    
    public function pageNotFoundAction() {
        $this->getResponse()->setHttpResponseCode(404);
    }

}
