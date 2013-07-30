<?php
class UserController extends Zend_Controller_Action
{
    protected $qM;
    
    public function init() {
        $this->qM = new Models_QuestManager();
    }
    public function indexAction(){
        
    }
    
    public function profileAction(){
        $this->view->regions = $this->qM->getRegions();
            $user = $this->qM->getUserData($_SESSION['clientID']);
            $this->view->user_F = $user["F"];
            $this->view->user_L = $user["L"];
            $this->view->user_M = $user["M"];
            $this->view->region = $user["RegionID"];
            $this->view->city = $user["CityID"];
            $this->view->phone1 = substr($user["Tel"], 0, 2);
            $this->view->phone2 = substr($user["Tel"], 2, 3);
            $this->view->phone3 = substr($user["Tel"], 5);
            $this->view->email = $user["Email"];
        
        if ($this->getRequest()->isPost()) {
            //Счетчик ошибок и текста ошибки, который возвращаем пользователю
            $error_count = 0;
            $error_text = array('phone' => '', 'email' => '', 'user_L'=>'','user_F'=>'', 'user_M'=>'');


            //Валидация ФИО. Любое количество букв латыницей или кирилицей, тире, апостров, пробел.
           $this->_checkFIO($_POST['user_L'], 'user_L' ,$error_text, $error_count);
           $this->_checkFIO($_POST['user_M'], 'user_M' ,$error_text, $error_count);
           $this->_checkFIO($_POST['user_F'], 'user_F' ,$error_text, $error_count);
           
            if(!empty($_POST['user_L']))$this->view->user_L = $_POST['user_L'];
            if(!empty($_POST['user_M']))$this->view->user_M = $_POST['user_M'];
            if(!empty($_POST['user_F']))$this->view->user_F = $_POST['user_F'];

            
            //Валидация електронной почты и номера телефона
            $email = $this->_checkMail($_POST['email'], $error_text, $error_count);
            $phone = $this->_checkPhone($_POST['phone1'].$_POST['phone2'].$_POST['phone3'], $error_text, $error_count);
            if(!empty($_POST['email']))$this->view->email = $_POST['email'];
            if(!empty($_POST['phone1']))$this->view->phone1 = $_POST['phone1'];
            if(!empty($_POST['phone2']))$this->view->phone2 = $_POST['phone2'];
            if(!empty($_POST['phone3']))$this->view->phone3 = $_POST['phone3'];
            
            if(empty($_POST['city'])){
//                $error_text['city'] ="Выберите город";
//                $error_count++;
            }else
                $this->view->city = $_POST['city'];
            if(empty($_POST['region'])){
//                $error_text['region'] ="Выберите регион";
//                $error_count++;
            }else
                $this->view->region = $_POST['region'];
            
            //Если пользователь ввел всё без ошибок пишем в БД
            if ($error_count == 0) {
                    $this->qM->updateUser($_SESSION['clientID'], array(
                        'F'=> trim($_POST['user_F']),
                        'L'=> trim($_POST['user_L']),
                        'M'=> trim($_POST['user_M']),
                        'RegionID'=> $_POST['region'],
                        'CityID'=> $_POST['city'],
                        'Tel'=> $phone,
                        'Email'=> $email,
                        'DateCr'=> date('Y-m-d'),
                        'Type'=> 1));
                    $this->_helper->redirector->gotoSimple('index','index',null);
                    
             }
        }
                    
    }
    
    private function _checkFIO($fio, $name, &$error_text, &$error_count){
        $user_name = trim($fio);
        if (empty($user_name)) {
            $error_text[$name] = "Не заполнено обязательное поле";
            $error_count++;
        } elseif (!preg_match('/^[A-Za-zА-ЯІЇЄҐЁа-яіїєґё\-\' ]+/', $fio)) {
            $error_text[$name] = "Для ввода Ника разрешены только все буквы, апостроф и тире!";
            $error_count++;
        }
        return array($error_text, $error_count);
    }
    
    private function _checkPass($pass, $pass_control, &$error_text, &$error_count ){
        $pass = trim($pass);
        $pass_control = trim($pass_control);
        if (empty($pass) || empty($pass_control)) {
            $error_text['password'] = "Не заполнено обязательное поле - пароль!";
            $error_count++;
        } elseif (strcmp($pass, $pass_control) == !0) {
            $error_text['password'] = "Введенные пароли не сходяться!";
            $error_count++;
        } elseif (strlen($pass) < 6 || strlen($pass) > 18) {
            $error_text['password'] = "Длина пароля не меньше 6ти и не больше 18 символов!";
            $error_count++;
        } else {
            $pass = md5($_POST['password']);
        }
        return $pass;
    }
    
    private function _checkMail($email, &$error_text, &$error_count ){
            $email = trim($email);
            if (empty($email)) {
                $error_text['email'] = "Не заполнено обязательное поле - Email\n";
                $error_count++;
            } else {
                if (preg_match('/^[\._A-Za-z0-9-]+@[A-Za-z0-9-]+\.[a-z]{2,3}\.?[a-z]*$/', $email)) {
                    $domain = explode("@", $email);
                    if (!getmxrr($domain[1], $mxhosts)) {
                        $error_text['email'] = "Введеный адресс отправка почты не возможна";
                        $error_count++;
                    }
                } else {
                    $error_text['email'] = "Введеный адресс не являеться адресом електронной почты";
                    $error_count++;
                }
            }
            return $email;
    }
    
    private function _checkPhone($phone,&$error_text, &$error_count){
        $phone = trim($phone);
            if (empty($phone)) {
                $error_text['phone'] = "Не заполнено обязательное поле - Телефон\n";
                $error_count++;
            } else {
                if(!preg_match('@^[0-9]{12}$@', $phone)){
//                   $phone = str_replace(array('+','(',')',' ','-'), '', $phone);
//                } else {
                    $error_text['phone'] = "Введенные данные не являються номером телефона";
                    $error_count++;
                }
            }
            return $phone;
    }

}