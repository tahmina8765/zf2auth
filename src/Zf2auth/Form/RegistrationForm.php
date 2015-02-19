<?php

namespace Zf2auth\Form;

use Zend\Form\Form;
use \Zend\Form\Element;

class RegistrationForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('users');
        $this->setAttribute('class', '');
        $this->setAttribute('method', 'post');


        $id = new Element\Hidden('id');
        $id->setAttribute('class', 'primarykey');


//        $username = new Element\Text('username');
//        $username->setLabel('User Name')
//                ->setAttribute('class', 'required inputfullwidth')
//                ->setAttribute('maxlength', '100')
//                ->setAttribute('placeholder', 'Username');


        $email = new Element\Email('email');
        $email->setLabel('Email')
                ->setAttribute('class', 'required email inputfullwidth')
                ->setAttribute('maxlength', '100')
                ->setAttribute('placeholder', 'Email');


        $password = new Element\Password('password');
        $password->setLabel('Password')
                ->setAttribute('class', 'required passValid inputfullwidth')
                ->setAttribute('id', 'inputPassword')
                ->setAttribute('maxlength', '100')
                ->setAttribute('min', '6')
                ->setAttribute('placeholder', 'Password');

        $repassword = new Element\Password('repassword');
        $repassword->setLabel('Confirm Password')
                ->setAttribute('class', 'required inputfullwidth')
                ->setAttribute('id', 'inputPassword')
                ->setAttribute('maxlength', '100')
                ->setAttribute('min', '6')
                ->setAttribute('placeholder', 'Confirm Password');

        $first_name = new Element\Text('first_name');
        $first_name->setLabel('First Name')
                ->setAttribute('class', 'required inputfullwidth')
                ->setAttribute('maxlength', '100')
                ->setAttribute('placeholder', 'First Name');


        $last_name = new Element\Text('last_name');
        $last_name->setLabel('Last Name')
                ->setAttribute('class', 'required inputfullwidth')
                ->setAttribute('maxlength', '100')
                ->setAttribute('placeholder', 'Last Name');


        $submit = new Element\Submit('submit');
        $submit->setValue('Join Now')
                ->setAttribute('class', 'btn btn-large btn-success btn-hossbrag join-now-home fullwidth');


        $this->add($id);
        $this->add($first_name);
        $this->add($last_name);
        // $this->add($username);
        $this->add($email);
        $this->add($password);
        $this->add($repassword);
        $this->add($submit);
    }

}
