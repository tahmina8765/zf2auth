<?php

namespace Zf2auth\Entity;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class RoleResources implements InputFilterAwareInterface
{

    public $role_id;
    public $resource_id;
    protected $inputFilter;                       // <-- Add this variable

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->role_id = (isset($data['role_id'])) ? $data['role_id'] : null;
        $this->resource_id = (isset($data['resource_id'])) ? $data['resource_id'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setRoleId($role_id)
    {
        $this->role_id = $role_id;
    }

    public function getRoleId()
    {
        return $this->role_id;
    }

    public function setResourceId($resource_id)
    {
        $this->resource_id = $resource_id;
    }

    public function getResourceId()
    {
        return $this->resource_id;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                        'name' => 'id',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
            )));


            $inputFilter->add($factory->createInput(array(
                        'name' => 'role_id',
                        'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'resource_id',
                        'required' => true,
            )));


            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
