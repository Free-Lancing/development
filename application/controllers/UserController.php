<?php

class UserController extends Zend_Controller_Action {

    public function init() {
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
        
        $this->_helper->log('user/index :- Auth Storage Cleared and Session all Unset and Cache Remove Success ', array(), false);
        $this->_redirect('login/');
    }

}
