<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function __construct($application) {
        parent::__construct($application);
        Zend_Session::start(true);
    }
    
    protected function _initAcl()
	    {
	        // Создаём объект Zend_Acl
	        $acl = new Zend_Acl();
	        // Добавляем ресурсы нашего сайта,
	        // другими словами указываем контроллеры и действия
	        $acl->addResource('index');
	        $acl->addResource('registration');
	        $acl->addResource('error');
	        $acl->addResource('auth');
	        $acl->addResource('login', 'auth');
	        $acl->addResource('logout', 'auth');
	        // далее переходим к созданию ролей
	        $acl->addRole('guest');
	        $acl->addRole('user', 'guest');
	        $acl->addRole('admin', 'user');
	        // разрешаем просматривать ресурсы
	        $acl->allow('guest', 'registration', array('index'));
	        $acl->allow('guest', 'auth', array('index', 'login', 'logout'));
	        $acl->allow('user', 'index', array('index'));
	        $acl->allow('admin', 'error');
	        // получаем экземпляр главного контроллера
	        $fc = Zend_Controller_Front::getInstance();
	        // регистрируем плагин с названием AccessCheck, в который передаём
	        // на ACL и экземпляр Zend_Auth
	        $fc->registerPlugin(new Application_Plugin_AccessCheck($acl, Zend_Auth::getInstance()));
    }
    
    protected function _initPartialsPath()
    {
        $options = $this->getOption('partials');
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->addScriptPath($options['path']);
        return $view;
    }
    
    public function _initConfig() {
        \Zend_Registry::set('config', new Zend_Config($this->getOptions()));
    }
    
    protected function _initRouters(){
        $front = Zend_Controller_Front::getInstance();
        $router = $front->getRouter();
        
        foreach (Zend_Registry::get('config')->routeConfig as $routeFile) {
                $routerConfig = new Zend_Config_Ini(
                                APPLICATION_PATH . "/configs/routers/$routeFile",
                                APPLICATION_ENV
                );
                $router->addConfig($routerConfig, 'routes');
        } 
    }
    
    protected function _initHelpers(){
        Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH.'/../library/Helpers/Action', 'Helpers_Action');
    }
    
    protected function _initTranslate(){
       $fc = Zend_Controller_Front::getInstance();
//       $zl = new Zend_Locale();
//       Zend_Registry::set('Zend_Locale',$zl);
//       $lang = $zl->getLanguage().'_'.$zl->getRegion();
//       $router = $fc->getRouter();
//       $route = new Zend_Controller_Router_Route(':lang/:module/:controller/:action/*', 
//                array(
//            'lang'=>$lang, 'module'=>'default', 'controller'=>'index', 'action'=>'index'
//       ));
//       $router->addRoute('default', $route);
//       $fc->setRouter($router);
       $fc->registerPlugin(new Application_Plugin_LanguageSetup());
       
   }
}