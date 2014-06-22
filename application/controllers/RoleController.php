<?php

class RoleController extends Zend_Controller_Action {
    
    protected $_params = array();

    public function init() {
        $request = $this->getRequest();
        $this->_params = $request->getParams();
    }

    public function indexAction() {
        $manager = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('cachemanager');
        $aclCache = $manager->getCache('acl');
        $allowed_access = $aclCache->load('allowed_access');
        
        $dbCache = $manager->getCache('db');
        $roles = $dbCache->load('roles');
        
        
        // Make Controllers entry Dynamic
        $this->view->controllers = array('dashboard', 'user', 'login');
        $this->view->actions = array('view', 'edit', 'delete', 'import', 'export');
        $this->view->userTypes = array_keys($roles);
        
        foreach($allowed_access as $accessData) {
            $existingRoles[] = $accessData['user_type'] . '_' . $accessData['controller'] . '_' . $accessData['access'];
        }
        
        $this->view->existingRoles = $existingRoles;
    }
    
    public function getAction() {
        
    }
    
    public function viewAction() {
        
    }
    
    public function updateAction() {
        unset($this->_params['module']);
        unset($this->_params['controller']);
        unset($this->_params['action']);
        
        (new Application_Model_Acl)->update(array('status' => 1), 'status = 0 AND controller <> "role"');
        
        // empty the table and re assign roles
        if (!empty($this->_params)) {
            $manager = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('cachemanager');
            $dbCache = $manager->getCache('db');
            $roles = $dbCache->load('roles');
            
            foreach($this->_params as $userControllerAction => $value) {
                $insertDetails = explode('_', $userControllerAction);
                $insertValue['user_type_id'] = $roles[$insertDetails[0]]['user_type_id'];
                $insertValue['controller'] = $insertDetails[1];
                $insertValue['access'] = $insertDetails[2];
                $insertValue['created'] = date('Y-m-d G:i:s');
                (new Application_Model_Acl)->insert($insertValue);
            }
        
            $auth = Zend_Auth::getInstance();
            $user = $auth->getIdentity();
            $currentUserType = $user->userType;

            // Remove Cache
            $aclCache = $manager->getCache('acl');
            $aclCache->remove('ACL_' . $currentUserType);
            $aclCache->remove('allowed_access');
        }
        
        $this->_redirect('/role/index', array('type' => 'success'));        
    }
    
    public function importAction() {
        
    }
    
    public function exportAction() {
        
    }
    
    public function cloneAction() {
        
    }
}

