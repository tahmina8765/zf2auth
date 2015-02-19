<?php

namespace Zf2auth\Form;

use Zend\Form\Form;
use \Zend\Form\Element;

class RolesSearchForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('roles');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('id', 'searchform');
        $this->setAttribute('method', 'post');



        $name = new Element\Text('name');
        $name->setLabel('Name')
                ->setAttribute('class', 'required form-control')
                ->setAttribute('id', 'name')
                ->setAttribute('placeholder', 'Name');




        $submit = new Element\Submit('submit');
        $submit->setValue('Search')
                ->setAttribute('class', 'btn btn-success');


        $this->add($name);

        $this->add($submit);
    }

}
