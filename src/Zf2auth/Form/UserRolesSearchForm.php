<?php

namespace Zf2auth\Form;

use Zend\Form\Form;
use \Zend\Form\Element;

class UserRolesSearchForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('user_roles');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('id', 'searchform');
        $this->setAttribute('method', 'post');



        $user_id = new Element\Text('user_id');
        $user_id->setLabel('User Id')
                ->setAttribute('class', 'required form-control')
                ->setAttribute('id', 'user_id')
                ->setAttribute('placeholder', 'User Id');


        $role_id = new Element\Text('role_id');
        $role_id->setLabel('Role Id')
                ->setAttribute('class', 'required form-control')
                ->setAttribute('id', 'role_id')
                ->setAttribute('placeholder', 'Role Id');




        $submit = new Element\Submit('submit');
        $submit->setValue('Search')
                ->setAttribute('class', 'btn btn-success');


        $this->add($user_id);
        $this->add($role_id);

        $this->add($submit);
    }

}
