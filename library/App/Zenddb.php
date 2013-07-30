<?php

class App_Zenddb extends Zend_Controller_Plugin_Abstract {

    public function __construct() {

        $params_db = array('host' => 'medinfo.cloudapp.net',
            'username' => '123',
            'password' => '123123',
            'dbname' => 'medinfo',
            'charset' => 'UTF8');

        $this->db = Zend_Db::factory('Pdo_Mysql', $params_db);
        $this->db->getConnection();
        
    }

}
