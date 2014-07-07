<?php

class Application_Model_Acl extends Zend_Db_Table {
    protected $_name = "acl";

    function fetchAllAcl($where = '1') {
        try {
            $sql = $this->_db->select()
                    ->from(array('a' => $this->_name), array('controller', 'access'))
                    ->joinLeft(array('ut' => 'user_types'), 'a.user_type_id = ut.id', array('ut.name as user_type', 'ut.id as user_type_id'))
                    ->where($where)
                    ->where('a.status = 1');
            
            return $this->_db->fetchAll($sql);
            
        } catch (Zend_Db_Statement_Exception $exc) {
            throw new Plugin_Exceptions_MySql('Sql Query :- ' . $sql . '<br /> Exception Found For fetchAllAcl() :- ' . $exc->getMessage());
        }
        
    }
}

?>