<?php

class RegistrationController extends Zend_Controller_Action {

    protected $emailNotifier;
    protected $qM;
    public function init() {
        $this->emailNotifier = new \App_Notifier_EmailSender();
        $this->qM = new Models_QuestManager();
    }

    public function indexAction() {
        $this->view->regions = $this->qM->getRegions();
        if ($this->getRequest()->isPost()) {
            //Счетчик ошибок и текста ошибки, который возвращаем пользователю
            $error_count = 0;
            $error_text = array('phone' => '', 'password' => '', 'email' => '', 'user_L'=>'','user_F'=>'', 'user_M'=>'','region'=>'','city'=>'');


            //Валидация ФИО. Любое количество букв латыницей или кирилицей, тире, апостров, пробел.
           $this->_checkFIO($_POST['user_L'], 'user_L' ,$error_text, $error_count);
           $this->_checkFIO($_POST['user_M'], 'user_M' ,$error_text, $error_count);
           $this->_checkFIO($_POST['user_F'], 'user_F' ,$error_text, $error_count);
           
            if(!empty($_POST['user_L']))$this->view->user_L = $_POST['user_L'];
            if(!empty($_POST['user_M']))$this->view->user_M = $_POST['user_M'];
            if(!empty($_POST['user_F']))$this->view->user_F = $_POST['user_F'];

            //Валидация пароля. 
            $pass = $this->_checkPass($_POST['password'], $_POST['password_controll'] ,$error_text, $error_count);
            
            //Валидация електронной почты и номера телефона
            $email = $this->_checkMail($_POST['email'], $error_text, $error_count);
            $phone = $this->_checkPhone($_POST['phone1'].$_POST['phone2'].$_POST['phone3'], $error_text, $error_count);
            if(!empty($_POST['email']))$this->view->email = $_POST['email'];
            if(!empty($_POST['phone1']))$this->view->phone1 = $_POST['phone1'];
            if(!empty($_POST['phone2']))$this->view->phone2 = $_POST['phone2'];
            if(!empty($_POST['phone3']))$this->view->phone3 = $_POST['phone3'];
            
            if(empty($_POST['city'])){
                $_POST['city'] = 0;
//                $error_text['city'] ="Выберите город";
//                $error_count++;
            }else
                $this->view->city = $_POST['city'];
            if(empty($_POST['region'])){
                $_POST['region'] = 0;
//                $error_text['region'] ="Выберите регион";
//                $error_count++;
            }else
                $this->view->region = $_POST['region'];
            
            //Если пользователь ввел всё без ошибок пишем в БД
            if ($error_count == 0) {
                //проверка не зарегестрирован ли уже пользователь
                $user_id = $this->qM->getUserIdByPhoneOrEmail($phone, $email);
                if ($user_id !== null) {
                    $error_text['email'] = $this->view->translate('User with such phone nubber or email already exist');
                } else {
                    //регистрируем пользователя
                    $this->qM->saveUser(array(
                        'F'=> trim($_POST['user_F']),
                        'L'=> trim($_POST['user_L']),
                        'M'=> trim($_POST['user_M']),
                        'RegionID'=> $_POST['region'],
                        'CityID'=> $_POST['city'],
                        'Tel'=> $phone,
                        'Email'=> $email,
                        'Lock'=> 1,
                        'pass'=> $pass,
                        'DateCr'=> date('Y-m-d'),
                        'Type'=> 1));
                    //Формируем подпись 
                    $domain = explode("@", $email);
                    $checkSum = base64_encode(substr(trim($phone), 0, 3). $domain[0] . md5($_SERVER['REMOTE_ADDR']));
                    //Получаем временную метку
                    $date = time();
                    //Добавляем данные во временную таблицу
                    $this->qM->saveActivateRegistration(array(
                        'Mail' => $email,
                        'CheckSum' => $checkSum,
                        'date' => $date
                    ));
                    //Сообщение зарегистрированному пользователю
                    $message = $this->view->translate("Link for activation your account").":<a href=\"http://".$_SERVER['SERVER_NAME']."/default/registration/getuserdata/?checkSum=" . $checkSum . "&email=" . $email . "\">".$this->view->translate('Click here.')."</a>";
                    //Посылаем сообщение пользователю
                    $subject="Account activation www.medinfo.ua";
                    $this->emailNotifier->send($email, $message, $subject );
                    $user_text = "1";//"Для подтвержения регистрации на ваш email отослано сообщение. Перейдите пожалуйста по ссылке указаной в нем. Чтоб перейти на главную страницу <a href=\"/default/index/index/\"> нажмите на ссылку</a>.";
                    $this->_helper->redirector->gotoSimple('success','registration',null, array("message" => "1"));
                }

            } 
                $this->view->mistake = $error_text;
            
        }
    }

    private function _checkFIO($fio, $name, &$error_text, &$error_count){
        $user_name = trim($fio);
        if (empty($user_name)) {
            $error_text[$name] = $this->view->translate("Не заполнено обязательное поле");
            $error_count++;
        } elseif (!preg_match('/^[A-Za-zА-ЯІЇЄҐЁа-яіїєґё\-\' ]+/', $fio)) {
            $error_text[$name] = $this->view->translate("Для ввода Ника разрешены только все буквы, апостроф и тире!");
            $error_count++;
        }
        return array($error_text, $error_count);
    }
    
    private function _checkPass($pass, $pass_control, &$error_text, &$error_count ){
        $pass = trim($pass);
        $pass_control = trim($pass_control);
        if (empty($pass) || empty($pass_control)) {
            $error_text['password'] = $this->view->translate("Не заполнено обязательное поле - пароль!");
            $error_count++;
        } elseif (strcmp($pass, $pass_control) == !0) {
            $error_text['password'] = $this->view->translate("Введенные пароли не сходяться!");
            $error_count++;
        } elseif (strlen($pass) < 6 || strlen($pass) > 18) {
            $error_text['password'] = $this->view->translate("Длина пароля не меньше 6ти и не больше 18 символов!");
            $error_count++;
        } else {
            $pass = md5($_POST['password']);
        }
        return $pass;
    }
    
    private function _checkMail($email, &$error_text, &$error_count ){
            $email = trim($email);
            if (empty($email)) {
                $error_text['email'] = $this->view->translate("Не заполнено обязательное поле - Email\n");
                $error_count++;
            } else {
                if (preg_match('/^[\._A-Za-z0-9-]+@[A-Za-z0-9-]+\.[a-z]{2,3}\.?[a-z]*$/', $email)) {
                    $domain = explode("@", $email);
                    if (!getmxrr($domain[1], $mxhosts)) {
                        $error_text['email'] = $this->view->translate("Введеный адресс отправка почты не возможна");
                        $error_count++;
                    }
                } else {
                    $error_text['email'] = $this->view->translate("Введеный адресс не являеться адресом електронной почты");
                    $error_count++;
                }
            }
            return $email;
    }
    
    private function _checkPhone($phone,&$error_text, &$error_count){
        $phone = trim($phone);
            if (empty($phone)) {
                $error_text['phone'] = $this->view->translate("Не заполнено обязательное поле - Телефон\n");
                $error_count++;
            } else {
                if(!preg_match('@^[0-9]{12}$@', $phone)){
//                   $phone = str_replace(array('+','(',')',' ','-'), '', $phone);
//                } else {
                    $error_text['phone'] = $this->view->translate("Введенные данные не являються номером телефона");
                    $error_count++;
                }
            }
            return $phone;
    }
    public function getuserdataAction() {
        // получаем контрольную сумму, мыло, айпишник
        $check_sum = $_GET['checkSum'];
        $email = $_GET['email'];
        $ip = $_SERVER['REMOTE_ADDR'];

        // проверяем есть ли пользователь с таким мылом в очереди на регистрацию
        $id = $this->qM->selectIDFromActivationRegistration($email);
        //если нету, выдаем ошибку и выходим
        if ($id === null){
            $error_text = $this->view->translate('Error in verification').  $this->view->translate('No such user');
        }else{
            // из таблицы пользователей по мылу вытягиваем логин, для формирования контрольной суммы
            $phone= $this->qM->selectPhoneFromCustumers($email);
            // формируем контрольную сумму
            $domain = explode("@", $email);
            $new_check_sum = base64_encode(substr($phone, 0, 3) . $domain[0] . md5($ip)); 
            // проверяем сходяться ли контрольные суммы
            if ($new_check_sum === $check_sum){
            // если да то апдейтим таблицу пользователь, меняем статус на активированый и отсылаем пользователю, письмо, что активация прошла успешно
                $this->qM->updateCustomersActivateUser($email);
                $message = "Registration complite successful";
                $subject = "All right!";
                $this->emailNotifier->send($email, $message, $subject );
                $this->_helper->redirector->gotoSimple('success','registration',null, array("message" => "2"));
            }
            // иначе, выводим ошибку
             $error_text = $this->view->translate('Error in verification').  $this->view->translate('Control summ does not match');
        }
//        $this->_helper->layout->disableLayout();
//        $this->_helper->viewRenderer->setNoRender();
    
        $this->view->mistake = $error_text;
    }
    
    public function successAction(){
        $message = $this->_getParam('message');
        $answer= "";
        if(!empty($message)){
            switch ($message) {
                case "1":
                    $answer = $this->view->translate('For verification of registration to yours email was send message. Please go to link specified there')
                        .$this->view->translate('For go to base page').'<a href=\"\">'.  $this->view->translate('press to link')."</a>.";
                    break;

                case "2":
                    $answer = $this->view->translate('Registration was successful'). $this->view->translate('For go to base page').'<a href=\"\">'.  $this->view->translate('press to link')."</a>.";
                    break;
            }
        }
        $this->view->message = $answer;
    }
   
    public function forgetpasswordAction(){
        
    }
    
    //Ajax from forgetpassword
    public function updatepassAction(){
        $email = $this->_getParam('email');
        if(!empty($email)){
            $id = $this->qM->selectUserByEmail($email);
            if(!empty($id)){
                $arr = str_split('ABCDEFGHIJKLMNOPVXWQqwertyuiopasdfghjklzxcvbnm1234567890'); // get all the characters into an array
                shuffle($arr); // randomize the array
                $arr = array_slice($arr, 0, 10); // get the first six (random) characters out
                $pass = implode('', $arr);
                $this->qM->updatePass(md5($pass), $email);
                $message = $this->view->translate("You new password").' '.$pass;
                $subject = $this->view->translate("Change password");
                $this->emailNotifier->send($email, $message, $subject);
                $type = 1;
//                 $this->_helper->redirector->gotoSimple('success','registration',null, array("message" => "3"));
            }else{
                $type = 3;
            }
        }else{
            $type = 2;
        }
        $this->_helper->json(array("type" => $type));
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
//        $this->renderScript('registration/forgetpassword.phtml');
    }
    
    public function licenceAction(){
        
    }
    
}


