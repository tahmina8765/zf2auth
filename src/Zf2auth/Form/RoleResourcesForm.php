<?php

namespace Zf2auth\Form;

use Zend\Form\Form;
use \Zend\Form\Element;

class RoleResourcesForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('role_resources');
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

        $role_id = new Element\Select('role_id');
        $role_id->setLabel('Role')
                ->setAttribute('class', 'required form-control')
                ->setOptions(array())
                ->setDisableInArrayValidator(true)
                ->setAttribute('placeholder', 'Role');


        $resource_id = new Element\Select('resource_id');
        $resource_id->setLabel('Resource')
                ->setAttribute('class', 'required form-control')
                ->setOptions(array())
                ->setDisableInArrayValidator(true)
                ->setAttribute('placeholder', 'Resource');

        $submit = new Element\Submit('submit');
        $submit->setValue('Submit')
                ->setAttribute('class', 'btn btn-success');

        $this->add($id);
        $this->add($csrf);
        $this->add($role_id);
        $this->add($resource_id);
    }

}
