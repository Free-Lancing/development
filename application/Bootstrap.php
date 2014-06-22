<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initPlaceholders() {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');

        $view = $layout->getView();
        $view->doctype('XHTML1_STRICT');
        $view->headTitle('Basic Setup of Zend Framework')->setSeparator(' :: ');
    }

}
