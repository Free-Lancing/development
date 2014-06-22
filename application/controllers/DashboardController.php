<?php

class DashboardController extends Zend_Controller_Action {

    public function init() {
        //http://framework.zend.com/manual/1.10/en/zend.controller.actionhelpers.html
        // AjaxContext Functionality
//        $ajaxContext = $this->_helper->getHelper('AjaxContext');
//        $ajaxContext->addActionContext('view', 'html')
//                    ->addActionContext('form', 'html')
//                    ->addActionContext('process', 'json')
//                    ->initContext();
//         Code for Rendering invalid url if type is post
//        $request = $this->getRequest();
//        
//        if ($request->isPost()) {
//            $request->setDispatched(false)
//                    ->setControllerName("error")
//                    ->setActionName("invalid-url");
//            return;
//        }
        //http://stackoverflow.com/questions/545702/need-guidance-to-start-with-zend-acl
//        $this->_authUser = (new Zend_Auth_Storage_Session())->read();
//        if (!$this->_helper->acl($this->_authUser->user_type, ,'index')) {
//            $this->_helper->log('Authenticate:- Has No Access to Dashboard page ', array(), false);
//            throw new Plugin_Exceptions_NoAccess();
//        }
    }

    public function indexAction() {
        
    }

    public function getListAction() {
        
    }

    public function viewAction() {
        
    }

    public function updateAction() {
        
    }

    public function importAction() {
        
    }

    public function exportAction() {
        
    }

    public function cloneAction() {
        
    }

    public function logoutAction() {
        $auth = Zend_Auth::getInstance();
        $user = $auth->getIdentity();
        $currentUserType = $user->userType;
        
        // Remove Cache
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        $manager = $bootstrap->getResource('cachemanager');
        $cache = $manager->getCache('acl');
        $cache->remove('ACL_' . $currentUserType);
        
        // Remove Auth Storage
        $auth->clearIdentity();
        
        //(new Zend_Session_Namespace('acl'))->unsetAll();
        
        $this->_helper->log('dashboard/index :- Auth Storage Cleared and Session all Unset and Cache Remove Success ', array(), false);
        $this->_redirect('login/');
    }

}
