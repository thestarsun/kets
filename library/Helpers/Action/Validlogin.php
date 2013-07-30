<?php

class Helpers_Action_Validlogin extends Zend_Controller_Action_Helper_Abstract
{
    public function __construct(){
   
    }

    public function direct($params = null){
       $params['username'] = trim($params['username']);
       $params['password'] = trim($params['password']);
       if(empty($params['username'])||empty($params['password']))
               return array('error'=> 'Логин и пароль  обязательные поля. Заполните их.');
       if(!preg_match('@^[A-z0-9]{6,18}$@', $params['password']))
               return array('error'=> 'Пароль  должен состоять из 6-18 символов латинского алфавита или цифр');
       if(preg_match('@^\+?[0-9-() ]{12,20}$@', $params['username'])){
          $type = 'Tel';
          $params['username'] = str_replace(array('+','(',')',' ','-'), '', $params['username']);
       }
       elseif(preg_match('#^[A-z0-9_\.]+@[a-z_.]*\.[a-z]{2,3}$#', $params['username']))
               $type = 'Email';
       else
           return array('error'=> 'Логином может быть только телефон или email.');
       
       return array('type' => $type, 'data' =>$params['username']);
       
    }
}




