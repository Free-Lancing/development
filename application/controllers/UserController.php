<?php

class UserController extends Zend_Controller_Action {

    public function init() {
        // User Init
        $request = $this->getRequest();
        $this->_params = $request->getParams();
        $this->_now = date('Y-m-d H:i:s');
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
        Zend_Layout::getMvcInstance()->disableLayout();
        $objUser = new Application_Model_Users();
        $post = $this->_params;
        $getInfo = $objUser->getInfo();
        $userType = 2;
        if(!empty($post)){
            $checkValidation = array(
                'first_name' => $post['fname'],
                'last_name' => $post['lname'],
                'email'     => $post['email'],
                'login'     => $post['uname'],
                'password'  => $post['password'],
                'user_type_id'  => $userType,
                'status'  => 0,
                'created'  => $this->_now,
                'modified'  => $this->_now,
            );
            
            $returnString = $this->_helper->validator($getInfo['metadata'], $checkValidation);
            
            if($returnString == 'success'){
                
                if($objUser->insert($checkValidation)){
                    echo "Success";
                }else{
                    echo "Failure";
                }
                exit;
            }else{
                echo '<pre>';
                print_r($returnString);
                echo '</pre>';
                exit;
            }
            
        }
    }
}
