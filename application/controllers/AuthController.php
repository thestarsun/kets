<?php

class AuthController extends Zend_Controller_Action {

    private $_managerQ;
    private $_errText;
    
    public function init() {
        $this->_managerQ = new Models_QuestManager();
        
        
    }

    public function indexAction() {
//        $this->_helper->redirector('login');
    }

    public function loginAction() {
        $lang = explode('/', $_SERVER['REQUEST_URI']); $lang= $lang[1];
        $this->_errText = $this->view->translate('You enter not right name or password');
        
        if (!empty($_SESSION['clientID'])) {
            $this->_helper->redirector->setGotoRoute(array(), 'base');
        }
//        $form = new Application_Form_Login();
//        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if (!empty($_SESSION['clientID'])) {
                $this->_helper->redirector->setGotoRoute(array(), 'base');
            }
            $formData = $this->getRequest()->getPost();
            
            $typeLogin = $this->_helper->validlogin($formData);
            if (!empty($typeLogin['error'])) {
                $this->view->errMessage = $typeLogin['error'];
            }else{
                $autorezationCheck = $this->_managerQ->getUser($typeLogin['type'], trim($typeLogin['data']), md5(trim($formData['password'])));
                
                if(!empty($autorezationCheck)){
                    $_SESSION['clientID'] = $autorezationCheck;
                    $this->_helper->redirector->setGotoRoute(array('lang' => $lang), 'base');
                }
                else $this->view->errMessage = $this->_errText;
            }
            
        }
    }
    
    public function loginajaxAction() {
        $this->_helper->login('ajax');
    }

    public function logoutAction() {
        unset($_SESSION['clientID']);
        $lang = explode('/', $_SERVER['REQUEST_URI']); $lang= $lang[1];
        $this->_redirect($this->view->url(array('lang' => $lang), 'base'));
//                
        // уничтожаем информацию об авторизации пользователя
//        Zend_Auth::getInstance()->clearIdentity();
//        $this->_helper->json(array("success" => 1));
//        $this->_helper->layout->disableLayout();
//        $this->_helper->viewRenderer->setNoRender();
    }

}

