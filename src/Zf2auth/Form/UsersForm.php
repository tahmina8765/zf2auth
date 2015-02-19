<?php

namespace Zf2auth\Form;

use Zend\Form\Form;
use \Zend\Form\Element;

class UsersForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('users');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('method', 'post');




        $id = new Element\Hidden('id');
        $id->setAttribute('class', 'primarykey');

        $csrf = new Element\Csrf('csrf');
        $csrf_options = array(
            'csrf_options' => array(
                'timeout' => 1000
            )
        );
        $csrf->setOptions($csrf_options);


        $username = new Element\Text('username');
        $username->setLabel('Username')
                ->setAttribute('class', 'required form-control')
                ->setAttribute('id', 'username')
                ->setAttribute('placeholder', 'Username');


        $email = new Element\Text('email');
        $email->setLabel('Email')
                ->setAttribute('class', 'required form-control')
                ->setAttribute('id', 'email')
                ->setAttribute('placeholder', 'Email');


        $password = new Element\Text('password');
        $password->setLabel('Password')
                ->setAttribute('class', 'required form-control')
                ->setAttribute('id', 'password')
                ->setAttribute('placeholder', 'Password');


        $salt = new Element\Text('salt');
        $salt->setLabel('Salt')
                ->setAttribute('class', 'required form-control')
                ->setAttribute('id', 'salt')
                ->setAttribute('placeholder', 'Salt');


        $email_check_code = new Element\Text('email_check_code');
        $email_check_code->setLabel('Email Check Code')
                ->setAttribute('class', 'required form-control')
                ->setAttribute('id', 'email_check_code')
                ->setAttribute('placeholder', 'Email Check Code');


        $is_disabled = new Element\Text('is_disabled');
        $is_disabled->setLabel('Is Disabled')
                ->setAttribute('class', 'required form-control')
                ->setAttribute('id', 'is_disabled')
                ->setAttribute('placeholder', 'Is Disabled');


        $created = new Element\Text('created');
        $created->setLabel('Created')
                ->setAttribute('class', 'required form-control')
                ->setAttribute('id', 'created')
                ->setAttribute('placeholder', 'Created');


        $modified = new Element\Text('modified');
        $modified->setLabel('Modified')
                ->setAttribute('class', 'required form-control')
                ->setAttribute('id', 'modified')
                ->setAttribute('placeholder', 'Modified');




        $submit = new Element\Submit('submit');
        $submit->setValue('Submit')
                ->setAttribute('class', 'btn btn-success');

        $this->add($id);
        $this->add($csrf);
        $this->add($username);
        $this->add($email);
        $this->add($password);
        $this->add($salt);
        $this->add($email_check_code);
        $this->add($is_disabled);
        $this->add($created);
        $this->add($modified);
    }

}
