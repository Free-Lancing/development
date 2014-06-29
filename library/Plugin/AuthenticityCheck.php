<?php

class Plugin_AuthenticityCheck extends Zend_Controller_Plugin_Abstract {

    public function __construct() {
        Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH . '/controllers/helpers', 'Zend_Controller_Action_Helper_Log');
        $this->_redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
        $this->_logHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('Log');
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $appIni = Zend_Registry::get('APPENV')->env;
        
        // NOTE: If controller name is given without an action name, then it will have complete access to its scope else limited to that action
        $exclusions = array('error' => '', 'schema-builder' => 'set-up-project', 'login' => '', 'user' => 'logout',);

        $this->_logHelper->direct('$request->getControllerName()', $request->getControllerName());
        $this->_logHelper->direct('$request->getActionName()', $request->getActionName());

        if (isset($exclusions[$request->getControllerName()])) {
            if ($exclusions[$request->getControllerName()] === '') {
                return;
            }
            
            if ($exclusions[$request->getControllerName()] !== '' && $exclusions[$request->getControllerName()] === $request->getActionName()) {
                return;
            }
        }
        
        $auth = Zend_Auth::getInstance();
        $authUser = $auth->getStorage()->read();

        // For all users except admin this page will be shown in maintenance mode
        if ((int) $appIni['project']['maintenance'] === 1 && $authUser->userType !== 'admin') {
            throw new Plugin_Exceptions_MaintenanceMode('This web page is in maintenance mode');
        }

        $loginController = 'login';
        $loginAction = 'index';

        $storage = new Zend_Auth_Storage_Session();
        $data = $storage->read();

        if (empty($data)) {
            if ($request->getControllerName() === $loginController) {
                // avoid an infinite redirect loop when already in login
                return;
            }

            $this->_redirector->setCode(303)->setExit(true)->setGotoSimple($loginAction, $loginController);
        } else {
            $acl = $this->getACL($authUser);
            
            // Redirect if resource is not created
            if (!in_array($request->getControllerName(), $acl->getResources())) {
                throw new Zend_Controller_Dispatcher_Exception();
            }

            if (!($acl->isAllowed($authUser->userType, $request->getControllerName(), $request->getActionName()))) {
                $this->_logHelper->direct('Access denied for ' . $request->getControllerName() . '/' . $request->getActionName() . PHP_EOL, array(), false);
                throw new Plugin_Exceptions_NoAccess();
            }
        }
    }

    public function getACL($authUser) {
        // Please Note for all the roles specified in the User_types Table, one entry should be present under acl table
        $currentUserType = $authUser->userType;
        $manager = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('cachemanager');
        $cache = $manager->getCache('acl');
        $dbCache = $manager->getCache('db');
        $acl = $cache->load('ACL_' . $currentUserType);

        if ($acl === FALSE) {
            // role, resource , privilege

            if (!($accessPrivileges = $cache->load('access_privileges'))) {
                $accessPrivileges = array('view' => array('index', 'get', 'view'), 'edit' => array('update', 'edit', 'submit'), 'delete' => array('delete'), 'import' => array('import', 'import-submit'), 'export' => array('export'));
                $cache->save($accessPrivileges, 'access_privileges');
            }

            if (!($allowed_access = $cache->load('allowed_access'))) {
                $allowed_access = (new Application_Model_Acl())->fetchAllAcl();
                $cache->save($allowed_access, 'allowed_access');
                $this->_logHelper->direct('Acl.php :- allowed_access in ACL', $allowed_access, false);
            }

            if (!($roles = $dbCache->load('roles'))) {
                $roles = (new Application_Model_UserTypes())->fetchAssocUserTypes();
                $dbCache->save($roles, 'roles');
            }

            $resources = array_values(array_unique(array_column($allowed_access, 'controller')));
            $acl = new Zend_Acl();

            foreach ($resources as $resourceName) {
                $acl->add(new Zend_Acl_Resource($resourceName));
            }

            foreach ($roles as $roleName => $roleId) {
                $acl->addRole(new Zend_Acl_Role($roleName));
            }

            // Adding Roles for the controllers to ACL
            foreach ($allowed_access as $accessData) {
                foreach ($accessPrivileges[$accessData['access']] as $previliges) {
                    $acl->allow($accessData['user_type'], $accessData['controller'], $previliges);
                }
            }

            $cache->save($acl, 'ACL_' . $currentUserType);
        }

        return $acl;
    }

}

?>
