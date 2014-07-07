<?php

class schema_build0 {

    protected $_db = null;
    protected $_model = null;
    protected $_executionType = '';

    public function __construct($db) {
        $this->_db = $db;
        $this->_model = new Application_Model_SchemaBuildLog();
    }

    public function executeFunctions($executedFunctions, $executionType, $buildNo) {
        $returnMessage = array();
        $this->_executionType = $executionType;

        $class = new ReflectionClass($this);
        $methods = $class->getMethods(ReflectionMethod::IS_PROTECTED);

        foreach ($methods as $reflectionObject) {
            $function = $reflectionObject->name;

            if ($executionType === 'execute') {
                if (!in_array($function, $executedFunctions)) {
                    if ($this->$function()) {
                        $this->_model->insert(array('build_function' => $function, 'build_no' => $buildNo, 'created' => date('Y-m-d G:i:s')));
                        $returnMessage[] = array('status' => 'success', 'msg' => $function . ' ' . $executionType . 'd successfully');
                    } else {
                        $returnMessage[] = array('status' => 'error', 'msg' => $function . ':- failed to execute');
                        break;
                    }
                }
            } else {
                if ($this->$function()) {
                    $this->_model->update(array('status' => 0), 'status = 1 AND build_function = "' . $function . '" AND build_no = "' . $buildNo . '"');
                    $returnMessage[] = array('status' => 'success', 'msg' => $function . ' ' . $executionType . 'ed successfully');
                } else {
                    $returnMessage[] = array('status' => 'error', 'msg' => $function . ':- failed to execute');
                    break;
                }
            }
        }
        
        return $returnMessage;
    }

    protected function createTableAcl() {
        if ($this->_executionType === 'execute') {
            $sql = "CREATE TABLE IF NOT EXISTS `acl` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `user_type_id` int(11) NOT NULL,
                      `controller` varchar(100) NOT NULL,
                      `access` varchar(100) NOT NULL,
                      `status` int(1) NOT NULL DEFAULT '0' COMMENT '0 => Inactive, 1  => Active',
                      `created` datetime NOT NULL,
                      `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
        } else {
            $sql = "DROP TABLE IF EXISTS `acl`";
        }

        return $this->_model->runSQL($sql);
    }

    protected function insertDefaultAcl() {
        if ($this->_executionType === 'execute') {
            $sql = "INSERT INTO `acl` (`id`, `user_type_id`, `controller`, `access`, `status`, `created`, `modified`) VALUES
                    (1, 1, 'dashboard', 'view', 1, '" . date('Y-m-d G:i:s') . "', '" . date('Y-m-d G:i:s') . "'),
                    (2, 1, 'login', 'view', 1, '" . date('Y-m-d G:i:s') . "', '" . date('Y-m-d G:i:s') . "'),
                    (3, 1, 'dashboard', 'view', 1, '" . date('Y-m-d G:i:s') . "', '" . date('Y-m-d G:i:s') . "'),
                    (4, 1, 'dashboard', 'edit', 1, '" . date('Y-m-d G:i:s') . "', '" . date('Y-m-d G:i:s') . "'),
                    (5, 1, 'dashboard', 'delete', 1, '" . date('Y-m-d G:i:s') . "', '" . date('Y-m-d G:i:s') . "'),
                    (6, 1, 'dashboard', 'import', 1, '" . date('Y-m-d G:i:s') . "', '" . date('Y-m-d G:i:s') . "'),
                    (7, 1, 'dashboard', 'export', 1, '" . date('Y-m-d G:i:s') . "', '" . date('Y-m-d G:i:s') . "'),
                    (8, 1, 'user', 'view', 1, '" . date('Y-m-d G:i:s') . "', '" . date('Y-m-d G:i:s') . "'),
                    (9, 1, 'user', 'edit', 1, '" . date('Y-m-d G:i:s') . "', '" . date('Y-m-d G:i:s') . "'),
                    (10, 1, 'user', 'delete', 1, '" . date('Y-m-d G:i:s') . "', '" . date('Y-m-d G:i:s') . "'),
                    (11, 1, 'user', 'import', 1, '" . date('Y-m-d G:i:s') . "', '" . date('Y-m-d G:i:s') . "'),
                    (12, 1, 'user', 'export', 1, '" . date('Y-m-d G:i:s') . "', '" . date('Y-m-d G:i:s') . "'),
                    (13, 1, 'login', 'view', 1, '" . date('Y-m-d G:i:s') . "', '" . date('Y-m-d G:i:s') . "'),
                    (14, 1, 'login', 'edit', 1, '" . date('Y-m-d G:i:s') . "', '" . date('Y-m-d G:i:s') . "'),
                    (15, 1, 'login', 'delete', 1, '" . date('Y-m-d G:i:s') . "', '" . date('Y-m-d G:i:s') . "'),
                    (16, 1, 'login', 'import', 1, '" . date('Y-m-d G:i:s') . "', '" . date('Y-m-d G:i:s') . "'),
                    (17, 1, 'login', 'export', 1, '" . date('Y-m-d G:i:s') . "', '" . date('Y-m-d G:i:s') . "');";
        } else {
            if (!$this->_model->checkColumnExists('acl', 'id')) {
                return true;
            }

            $sql = "DELETE FROM `acl` WHERE `id` IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17)";
        }

        return $this->_model->runSQL($sql);
    }

    protected function createTableUsers() {
        if ($this->_executionType === 'execute') {
            $sql = "CREATE TABLE IF NOT EXISTS `users` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `first_name` varchar(100) NOT NULL,
                      `last_name` varchar(100) NOT NULL,
                      `email` varchar(50) NOT NULL,
                      `login` varchar(100) NOT NULL,
                      `password` varchar(50) NOT NULL,
                      `user_type_id` int(11) NULL,
                      `gender` ENUM('Male', 'Female') NOT NULL,
                      `status` int(1) NOT NULL DEFAULT '1' COMMENT '0 => Inactive, 1  => Active',
                      `created` datetime NOT NULL,
                      `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
        } else {
            $sql = "DROP TABLE IF EXISTS `users`";
        }

        return $this->_model->runSQL($sql);
    }

    protected function insertDefaultUsers() {
        if ($this->_executionType === 'execute') {
            $sql = "INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `login`, `password`, `user_type_id`, `status`, `created`, `gender`) 
                    VALUES
                    (1, 'Rachit', 'Doshi', 'doshi.rachit@gmail.com', 'rachit.d', 'admin', 1, 1, '" . date('Y-m-d G:i:s') . "', 'Male'),
                    (2, 'Xyz', 'Abc', 'xyz.a@gmail.com', 'xyz.a', '', 2, 1, '" . date('Y-m-d G:i:s') . "', 'Male');";
        } else {
            if (!$this->_model->checkColumnExists('acl', 'id')) {
                return true;
            }

            $sql = "DELETE FROM `users` WHERE `id` IN (1, 2)";
        }

        return $this->_model->runSQL($sql);
    }

    protected function createTableUserTypes() {
        if ($this->_executionType === 'execute') {
            $sql = "CREATE TABLE IF NOT EXISTS `user_types` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `name` varchar(100) NOT NULL,
                      `status` int(1) NOT NULL DEFAULT '0' COMMENT '0 => Inactive, 1  => Active',
                      `created` datetime NOT NULL,
                      `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
        } else {
            $sql = "DROP TABLE IF EXISTS `user_types`";
        }

        return $this->_model->runSQL($sql);
    }

    protected function insertDefaultUserTypes() {
        if ($this->_executionType === 'execute') {
            $sql = "INSERT INTO `user_types` (`id`, `name`, `status`, `created`) VALUES
                    (1, 'admin', 1, '" . date('Y-m-d G:i:s') . "'),
                    (2, 'guest', 1, '" . date('Y-m-d G:i:s') . "');";
        } else {
            if (!$this->_model->checkColumnExists('acl', 'id')) {
                return true;
            }

            $sql = "DELETE FROM `users_types` WHERE `id` IN (1, 2)";
        }

        return $this->_model->runSQL($sql);
    }

}

?>
