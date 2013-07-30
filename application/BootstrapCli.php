<?php

class BootstrapCli extends Zend_Application_Bootstrap_Bootstrap {

    protected $_getopt = null;
    protected $_getOptRules = array(
        'environment|e-s' => 'Application environment switch (optional)',
        'module|m-s' => 'Module name (optional)',
        'controller|c=s' => 'Controller name (required)',
        'action|a=s' => 'Action name (required)'
    );

    public function __construct($application) {
        parent::__construct($application);
        Zend_Session::start(true);
    }
    protected function _initView() {
        // displaces View Resource class to prevent execution
    }

    public function _initConfig() {
        Zend_Registry::set('config', new Zend_Config($this->getOptions()));
        
    }

    protected function _initCliFrontController() {
        $this->bootstrap('FrontController');
        $front = $this->getResource('FrontController');
        $getopt = new Zend_Console_Getopt($this->getOptionRules(),
                        $this->_isolateMvcArgs());
        $request = new ZFExt_Controller_Request_Cli($getopt);
        $front->setResponse(new Zend_Controller_Response_Cli)
                ->setRequest($request)
                ->setRouter(new ZFExt_Controller_Router_Cli)
                ->setParam('noViewRenderer', true);
    }  // CLI specific methods for option management

    public function setGetOpt(Zend_Console_Getopt $getopt) {
        $this->_getopt = $getopt;
    }

    public function getGetOpt() {
        if (is_null($this->_getopt)) {
            $this->_getopt = new Zend_Console_Getopt($this->getOptionRules());
        }
        return $this->_getopt;
    }

    public function addOptionRules(array $rules) {
        $this->_getOptRules = $this->_getOptRules + $rules;
    }

    public function getOptionRules() {
        return $this->_getOptRules;
    }

    // get MVC related args only (allows later uses of Getopt class
    // to be configured for cli arguments)
    protected function _isolateMvcArgs() {
        $options = array($_SERVER['argv'][0]);
        foreach ($_SERVER['argv'] as $key => $value) {
            if (in_array($value, array(
                        '--action', '-a', '--controller', '-c', '--module', '-m', '--environment', '-e'
                    ))) {
                $options[] = $value;
                $options[] = $_SERVER['argv'][$key + 1];
            }
        }
        return $options;
    }


    
//    
//    public function _initGogleAccount(){
//        $options = $this->getOption('google');
//        Zend_Registry::set('Google_clientID', $options['clientID']);
//        
//        Zend_Registry::set('Google_redirectURL',$options['redirectURL']);
//    }

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
    
//    protected function _initPartialsPath()
//    {
//        $options = $this->getOption('partials');
//        $this->bootstrap('view');
//        $view = $this->getResource('view');
//        $view->addScriptPath($options['path']);
//        return $view;
//    }
    
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