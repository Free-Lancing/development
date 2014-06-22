<?php

class Application_Model_Users extends Zend_Db_Table {

    protected $_name = "Users";

    public function insertUsers($data) {
        return $this->_db->insert($data);
    }

    function updateUsers($data, $where) {
        return $this->_db->update($data, $where);
    }

    function fetchAssocUsers($fieldArr = array(), $where = '1') {
        try {
            $sql = $this->_db->select()
                    ->from(array('u' => $this->_name), $fieldArr)
                    ->where($where);
            return $this->_db->fetchAssoc($sql);
            
        } catch (Zend_Db_Statement_Exception $exc) {
            throw new Plugin_Exceptions_MySql('Sql Query :- ' . $sql . '<br /> Exception :- ' . $exc->getMessage());
        }
        
    }
}
