<?php

class Application_Model_UserTypes extends Zend_Db_Table {
    protected $_name = "user_types";
    
    function fetchUserTypes($where = '1') {
        $sql = $this->_db->select()
                ->from(array('ut' => $this->_name), array('name as user_type'))
                ->joinLeft(array('u' => 'users'), 'u.user_type_id = ut.id', array('u.first_name as user_name', 'u.id as user_id'))
                ->where($where);
        
        return $this->_db->fetchRow($sql);
    }
    
    function fetchAssocUserTypes() {
        $sql = $this->_db->select()
                ->from(array('ut' => $this->_name), array('name as user_type', 'id as user_type_id'));
        
        return $this->_db->fetchAssoc($sql);
    }
}
