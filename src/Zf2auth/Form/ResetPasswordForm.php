<?php

namespace Zf2auth\Form;

use Zend\Form\Form;
use \Zend\Form\Element;

class ResetPasswordForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('users');
        $this->setAttribute('class', 'form-small');
        $this->setAttribute('method', 'post');
        $this->setAttribute('novalidate', true);
        $this->setAttribute('autocomplete', false);

        $id = new Element\Hidden('id');
        $id->setAttribute('class', 'primarykey');
        $id->setAttribute('id', 'id');

        $password_access_tocken = new Element\Hidden('password_access_tocken');
        $password_access_tocken->setAttribute('id', 'password_access_tocken');



        $password = new Element\Password('password');
        $password->setLabel('New Password')
                ->setAttribute('class', 'required form-control')
                ->setAttribute('id', 'password')
                ->setAttribute('placeholder', 'New Password');

        $repassword = new Element\Password('repassword');
        $repassword->setLabel('Re-type Password')
                ->setAttribute('class', 'required form-control')
                ->setAttribute('id', 'repassword')
                ->setAttribute('placeholder', 'Re-type Password');


        $submit = new Element\Submit('submit');
        $submit->setValue('Change')
                ->setAttribute('id', 'submit')
                ->setAttribute('class', 'btn btn-lg btn-success btn-block');

        $this->add($id);
        $this->add($password_access_tocken);
        $this->add($password);
        $this->add($repassword);
        $this->add($submit);
    }

}
