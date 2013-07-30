<?php

class Application_Form_Login extends Zend_Form
{

    public function init()
    {
        // указываем имя формы
        $this->setName('login');

        // сообщение о незаполненном поле
        $isEmptyMessage = 'Значение является обязательным и не может быть пустым';

        // создаём текстовый элемент
        $username = new Zend_Form_Element_Text('username');

        // задаём ему label и отмечаем как обязательное поле;
        // также добавляем фильтры и валидатор с переводом
        $username->setLabel('Логин:')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $isEmptyMessage))
        );

        // создаём элемент формы для пароля
        $password = new Zend_Form_Element_Password('password');

        // задаём ему label и отмечаем как обязательное поле;
        // также добавляем фильтры и валидатор с переводом
        $password->setLabel('Пароль:')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $isEmptyMessage))
        );

        // создаём кнопку submit
        $submit = new Zend_Form_Element_Submit('login');
        $submit->setLabel('Войти в систему');

        // добавляем элементы в форму
        $this->addElements(array($username, $password, $submit));

        // указываем метод передачи данных
        $this->setMethod('post');
    }


}