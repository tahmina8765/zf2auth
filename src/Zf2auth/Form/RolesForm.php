<?php

namespace Zf2auth\Form;

use Zend\Form\Form;
use \Zend\Form\Element;

class RolesForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('roles');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('method', 'post');
        $formValue = $this->formValue();


        $id = new Element\Hidden('id');
        $id->setAttribute('class', 'primarykey');

        $csrf = new Element\Csrf('csrf');
        $csrf_options = array(
            'csrf_options' => array(
                'timeout' => 1000
            )
        );
        $csrf->setOptions($csrf_options);


        $name = new Element\Text('name');
        $name->setLabel('Name')
                ->setAttribute('class', 'required form-control')
                ->setAttribute('id', 'name')
                ->setAttribute('placeholder', 'Name');


        $label = new Element\Select('label');
        $label->setLabel('Label')
                ->setAttribute('class', 'required form-control')
                ->setValueOptions($formValue['label'])
                ->setDisableInArrayValidator(true)
                ->setAttribute('id', 'label')
                ->setAttribute('placeholder', 'Label');

        $weight = new Element\Text('weight');
        $weight->setLabel('Weight')
                ->setAttribute('class', 'required form-control')
                ->setAttribute('id', 'weight')
                ->setAttribute('placeholder', 'Weight in %');


        $submit = new Element\Submit('submit');
        $submit->setValue('Submit')
                ->setAttribute('class', 'btn btn-success');

        $this->add($id);
        $this->add($csrf);
        $this->add($label);
        $this->add($name);
        $this->add($weight);
    }

    public function formValue()
    {

        $formValue['label'] = array(
            'Customer' => "Customer",
            'Support' => "Support",
            'Admin' => "Admin",
            'Guest' => "Guest",
        );
        return $formValue;
    }

}
