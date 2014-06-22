<?php

class UserController extends Zend_Controller_Action {

    public function init() {
        // User Init
        $request = $this->getRequest();
        $this->_params = $request->getParams();
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
    
    public function registerAction(){
        Zend_Layout::getMvcInstance()->setLayout('login');
        
    }
    
    public function submitAction(){
        $objUser = new Application_Model_Users();
        $post = $this->_request->getPost();
        $getInfo = $objUser->getInfo();
        
        if(!empty($post)){
            $checkValidation = array(
                'first_name' => $post['fname'],
                'last_name' => $post['lname'],
                'email'     => $post['email'],
                'login'     => $post['uname'],
                'password'  => $post['password'],
            );
            $returnString = $this->_helper->validator($getInfo['metadata'], $checkValidation);
            echo '<pre>';
            print_r($returnString);
            echo '</pre>';
            exit;
        }
    }
}
