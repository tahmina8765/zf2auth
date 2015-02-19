<?php

namespace Zf2auth\Form;

use Zend\Form\Form;
use \Zend\Form\Element;

class ForgetPasswordForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('users');
        $this->setAttribute('class', 'form-small');
        $this->setAttribute('method', 'post');
        $this->setAttribute('novalidate', true);

        $username = new Element\Text('username');
        $username->setLabel('User Name')
                ->setAttribute('class', '')
                ->setAttribute('id', 'username')
                ->setAttribute('placeholder', 'Enter Your Username');


        $email = new Element\Email('email');
        $email->setLabel('Email')
                ->setAttribute('id', 'email')
                ->setAttribute('class', 'form-control')
                ->setAttribute('maxlength', '200')
                ->setAttribute('required', true)
                ->setAttribute('placeholder', 'Enter your email address');


        $submit = new Element\Submit('submit');
        $submit->setValue('Submit')
                ->setAttribute('id', 'submit')
                ->setAttribute('class', 'btn btn-lg btn-primary btn-block mrg-top-10');

        $this->add($username);
        $this->add($email);
        $this->add($submit);
    }

}
