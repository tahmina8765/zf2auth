<?php

namespace Zf2auth\Form;

use Zend\Form\Form;
use \Zend\Form\Element;

class LoginForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('users');
        $this->setAttribute('class', 'form-small');
        $this->setAttribute('method', 'post');
        $this->setAttribute('novalidate', true);

        $csrf = new Element\Csrf('csrf');
        $csrf_options = array(
            'csrf_options' => array(
                'timeout' => 1000
            )
        );
        $csrf->setAttribute('id', 'csrf');
        $csrf->setOptions($csrf_options);

        $email = new Element\Email('email');
        $email->setLabel('Email')
                ->setAttribute('id', 'email')
                ->setAttribute('class', 'form-control')
                ->setAttribute('maxlength', '200')
                ->setAttribute('required', true)
                ->setAttribute('placeholder', 'Email address');


        $password = new Element\Password('password');
        $password->setLabel('Password')
                ->setAttribute('id', 'password')
                ->setAttribute('class', 'form-control')
                ->setAttribute('maxlength', '200')
                ->setAttribute('required', true)
                ->setAttribute('placeholder', 'Password');

//        $rememberme = new Element\Checkbox('rememberme');
//        $rememberme->setLabel(' Remember me')
//                ->setAttribute('id', 'rememberme')
//                ->setAttribute('class', '')
//                ->setValue('1');

        $submit = new Element\Submit('submit');
        $submit->setValue('Log in')
                ->setAttribute('id', 'submit')
                ->setAttribute('class', 'btn btn-lg btn-primary btn-block');

        $this->add($csrf);
        $this->add($email);
        $this->add($password);
//        $this->add($rememberme);
        $this->add($submit);
    }

}
