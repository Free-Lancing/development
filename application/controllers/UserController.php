<?php

class UserController extends Zend_Controller_Action {

    public function init() {
        $request = $this->getRequest();
        $this->_params = $request->getParams();
        $val = $this->_helper->constants('userDetails');
        echo '<pre>';
        print_r($val);
        echo '</pre>';
        exit;
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

    public function registerAction() {
        Zend_Layout::getMvcInstance()->setLayout('login');

        if ($this->_request->isPost()) {
            if (empty($this->_params['status'])) {
                $objUser = new Application_Model_Users();
                $metaData = $objUser->info()['metadata'];
                
                $dataArray['first_name'] = $this->_params['fname'];
                $dataArray['last_name'] = $this->_params['lname'];
                $dataArray['email'] = $this->_params['email'];
                $dataArray['login'] = $this->_params['uname'];
                $dataArray['password'] = $this->_params['password'];
                $dataArray['gender'] = $this->_params['gender'];

                $error = $this->_helper->validate($metaData, $dataArray);

                if (!empty($error)) {
                    $this->_request->setPost(array('status' => 'error', 'msg' => serialize($error)));
                    $this->_forward('register', 'user', null);
                    return;
                }
                
                $dataArray = $this->_helper->format($metaData, $dataArray);
                $dataArray['created'] = date('Y-m-d G:i:s');
                $objUser->insert($dataArray);
                
                $this->_request->setPost(array('status' => 'success', 'msg' => 'User Created Successfully. Check email for the verification Url'));
                $this->redirect('login/index/success/1');
            }
        }
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
