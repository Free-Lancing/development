<?php

class Plugin_AjaxCheck extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        parent::preDispatch($request);
        //If the request is not an XHR, do nothing.
        if (!$request->isXmlHttpRequest()) {
            return FALSE;
        }

        $layout = Zend_Layout::getMvcInstance();
        $layout->disableLayout();
        $layout->setLayout('ajax');
    }

}

?>