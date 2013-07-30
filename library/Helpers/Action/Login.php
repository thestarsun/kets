<?php

class Helpers_Action_Login extends Zend_Controller_Action_Helper_Abstract
{
    public function __construct(){
   
    }

    public function direct($type = null){
        $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
        // указываем таблицу, где необходимо искать данные о пользователях
        // колонку, где искать имена пользователей,
        // а также колонку, где хранятся пароли
        $authAdapter->setTableName('tbl_customer')
                ->setIdentityColumn('Mail')
                ->setCredentialColumn('Password');
        // получаем введённые данные
        $username = $this->getRequest()->getPost('username');
        $password = md5($this->getRequest()->getPost('password'));
        // подставляем полученные данные из формы
        $authAdapter->setIdentity($username)
                ->setCredential($password);
        // получаем экземпляр Zend_Auth
        $auth = Zend_Auth::getInstance();
        // делаем попытку авторизировать пользователя
        $result = $auth->authenticate($authAdapter);
        // если авторизация прошла успешно
        if ($result->isValid()) {
            // используем адаптер для извлечения оставшихся данных о пользователе
            $identity = $authAdapter->getResultRowObject();
            // получаем доступ к хранилищу данных Zend
            $authStorage = $auth->getStorage();
            // помещаем туда информацию о пользователе,
            // чтобы иметь к ним доступ при конфигурировании Acl
            $authStorage->write($identity);
            // Используем библиотечный helper для редиректа
            // на controller = index, action = index
            if(!empty($type))
                $this->_helper->json(array("nick"=>$identity->Nick, "result"=>1));
            else
                return 1;
               
        } else {
            if(!empty($type))
                $this->_helper->json(array("result"=>0));
            else
                return 2;
        }
    }
}


