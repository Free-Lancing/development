<?php

class LoginController extends Zend_Controller_Action {

    protected $_params = array();

    public function init() {
        $request = $this->getRequest();
        $this->_params = $request->getParams();
        $this->_defaultRedirect  = '/dashboard/index';
        Zend_Layout::getMvcInstance()->setLayout('login');
    }

    public function indexAction() {
        $storage = new Zend_Auth_Storage_Session();
        $data = $storage->read();

        if (!empty($data)) {
            $this->_helper->log('login/authenticate:- Is authentic user hence redirecting', $data, false);
            $this->_redirect($this->_defaultRedirect);
        }
        
        $this->_helper->log('login/authenticate:- Post Params', $this->_params, false);
        // In case of error we will get error => 1 Invalid id / password, error => 2 Access Denied for blocked or inactive users
    }

    public function authenticateAction() {
        if ($this->_request->isPost()) {
            $this->_helper->log('login/authenticate:- Post Params', $this->_params, false);
            $userName = $this->_params['userName'];
            $password = $this->_params['password'];

            // Setting Auth Adapter
            $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
            $authAdapter->setTableName('users')
                    ->setIdentityColumn('login')
                    ->setCredentialColumn('password')
                    ->setIdentity($userName)
                    ->setCredential($password);

            $auth = Zend_Auth::getInstance();
            $result = $auth->authenticate($authAdapter);

            if ($result->isValid()) {
                $identity = $authAdapter->getResultRowObject();
                $this->_helper->log('login/authenticate:- Auth Identity', $identity, false);

                // Get Logged In Users User Type and Access
                $userTypesObj = new Application_Model_UserTypes();
                $acl = $userTypesObj->fetchUserTypes('u.id = ' . $identity->id . ' AND u.status = 1 AND ut.status = 1');
                $this->_helper->log('login/authenticate:- DB ACL For User ' . $identity->login, $acl, false);                

                // Storing Zend Auth in session
                $storage = new Zend_Auth_Storage_Session();
                $identity->userType = $acl['user_type'];
                $storage->write($identity);
                $this->_helper->log('login/authenticate:- User Is authenticated and Auth Set ', array(), false);

                $this->_redirect($this->_defaultRedirect);
            } else {
                // Here 1 Denotes invalid Id or Password
                $this->_redirect('/login/index/error/1');
            }
        }
    }

}
