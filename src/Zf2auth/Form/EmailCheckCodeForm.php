<?php

namespace Zf2auth\Form;

use Zend\Form\Form;
use \Zend\Form\Element;

class EmailCheckCodeForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('users');
        $this->setAttribute('class', '');
        $this->setAttribute('method', 'post');

        $id = new Element\Hidden('id');
        $id->setAttribute('class', 'primarykey');


        $email_check_code = new Element\Text('email_check_code');
        $email_check_code->setLabel('Email Check Code')
                ->setAttribute('class', 'required')
                ->setAttribute('placeholder', 'Confirmation Code');


        $submit = new Element\Submit('submit');
        $submit->setValue('Submit')
                ->setAttribute('class', 'btn btn-success btn-hossbrag pad-left-35 pad-right-35');

        $this->add($id);
        $this->add($email_check_code);
        $this->add($submit);
    }

}
