<?php

class Application_Model_SchemaBuildLog extends Zend_Db_Table {

    protected $_name = "schema_build_log";

    function fetchAssocSchemaBuildLog($fieldArr = array(), $where = '1') {
        $sql = $this->_db->select()
                ->from(array('sbl' => $this->_name), $fieldArr)
                ->where($where);

        return $this->_db->fetchAssoc($sql);
    }

    function createSchemaBuildLog() {
        $sql = "CREATE TABLE IF NOT EXISTS `schema_build_log` (
              `build_id` int(10) NOT NULL AUTO_INCREMENT,
              `build_function` varchar(255) NOT NULL,
              `build_no` mediumint(9) NOT NULL,
              `status` INT(1) NOT NULL DEFAULT '1' COMMENT '0 => Inactive, 1 => Active',
              `created` datetime NOT NULL,
              `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              PRIMARY KEY (`build_id`),
              UNIQUE INDEX `build_function_build_no_UQ` (`build_function`, `build_no`, `status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This is the new schema log for all db updates' AUTO_INCREMENT=1 ;";

        return $this->_db->query($sql);
    }

    function checkColumnExists($table, $column) {
        $dbName = 'schema_test';

        $sql = "SELECT *
                    FROM information_schema.COLUMNS
                    WHERE
                        TABLE_SCHEMA = '" . $dbName . "'
                    AND TABLE_NAME = '" . $table . "'
                    AND COLUMN_NAME = '" . $column . "'";

        $numRows = $this->_db->fetchAll($sql);

        return (!empty($numRows));
    }

    function runSQL($sql) {
        try {
            if ($this->_db->query($sql)) {
                return true;
            }
        } catch (Zend_Db_Exception $e) {
            echo $e->getMessage();
        }
        return false;
    }

}
