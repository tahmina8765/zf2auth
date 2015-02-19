<?php

namespace Zf2auth\Form;

use Zend\Form\Form;
use \Zend\Form\Element;

class ChangePasswordForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('users');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('method', 'post');
        $this->setAttribute('id', 'changePasswordForm');
        $this->setAttribute('novalidate', true);
        $this->setAttribute('autocomplete', false);

        $id = new Element\Hidden('id');
        $id->setAttribute('class', 'primarykey');
        $id->setAttribute('id', 'id');

        $cpassword = new Element\Password('cpassword');
        $cpassword->setLabel('Current Password')
                ->setAttribute('class', 'required form-control')
                ->setAttribute('id', 'cpassword')
                ->setAttribute('placeholder', 'Current Password');


        $password = new Element\Password('password');
        $password->setLabel('New Password')
                ->setAttribute('class', 'required form-control password_strength_check')
                ->setAttribute('id', 'password')
                ->setAttribute('placeholder', 'New Password');

        $repassword = new Element\Password('repassword');
        $repassword->setLabel('Re-type Password')
                ->setAttribute('class', 'required form-control')
                ->setAttribute('id', 'repassword')
                ->setAttribute('placeholder', 'Re-type Password');


        $submit = new Element\Submit('submit');
        $submit->setValue('Change Password')
                ->setAttribute('id', 'submit')
                ->setAttribute('class', 'btn btn-lg btn-success btn-block');

        $this->add($id);
        $this->add($cpassword);
        $this->add($password);
        $this->add($repassword);
//        $this->add($submit);
    }

}
