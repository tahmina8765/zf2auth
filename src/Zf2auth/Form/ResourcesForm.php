<?php

namespace Zf2auth\Form;

use Zend\Form\Form;
use \Zend\Form\Element;

class ResourcesForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('resources');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('method', 'post');




        $id = new Element\Hidden('id');
        $id->setAttribute('class', 'primarykey');



        $name = new Element\Text('name');
        $name->setLabel('Name')
                ->setAttribute('class', 'required form-control')
                ->setAttribute('id', 'name')
                ->setAttribute('readonly', 'readonly')
                ->setAttribute('placeholder', 'Name');

        $title = new Element\Text('title');
        $title->setLabel('Title')
                ->setAttribute('class', 'required form-control')
                ->setAttribute('id', 'title')
                ->setAttribute('placeholder', 'Title');




        $submit = new Element\Submit('submit');
        $submit->setValue('Submit')
                ->setAttribute('class', 'btn btn-success');

        $this->add($id);
        $this->add($name);
        $this->add($title);
    }

}
