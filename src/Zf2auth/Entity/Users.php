<?php

namespace Zf2auth\Entity;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Users implements InputFilterAwareInterface
{

    public $name;
    public $username;
    public $email;
    public $password;
    public $password_hints;
    public $salt;
    public $email_check_code;
    public $password_access_tocken;
    public $access_token_valid_till;
    public $is_disabled;
    public $created;
    public $modified;
    protected $inputFilter;                       // <-- Add this variable

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->name = (isset($data['name'])) ? trim($data['name']) : null;
        $this->username = (isset($data['username'])) ? trim($data['username']) : null;
        $this->email = (isset($data['email'])) ? trim($data['email']) : null;
        $this->password = (isset($data['password'])) ? $data['password'] : null;
        $this->password_hints = (isset($data['password_hints'])) ? $data['password_hints'] : null;
        $this->salt = (isset($data['salt'])) ? $data['salt'] : null;
        $this->email_check_code = (isset($data['email_check_code'])) ? $data['email_check_code'] : null;
        $this->password_access_tocken = (isset($data['password_access_tocken'])) ? $data['password_access_tocken'] : null;
        $this->access_token_valid_till = (isset($data['access_token_valid_till'])) ? $data['access_token_valid_till'] : null;
        $this->is_disabled = (isset($data['is_disabled'])) ? $data['is_disabled'] : null;
        $this->created = (isset($data['created'])) ? $data['created'] : null;
        $this->modified = (isset($data['modified'])) ? $data['modified'] : null;
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

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return trim($this->email);
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPasswordHints($password_hints)
    {
        $this->password_hints = $password_hints;
    }

    public function getPasswordHints()
    {
        return $this->password_hints;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function setEmailCheckCode($email_check_code)
    {
        $this->email_check_code = $email_check_code;
    }

    public function getEmailCheckCode()
    {
        return $this->email_check_code;
    }

    public function setPasswordAccessTocken($password_access_tocken)
    {
        $this->password_access_tocken = $password_access_tocken;
    }

    public function getPasswordAccessTocken()
    {
        return $this->password_access_tocken;
    }

    public function setAccessTokenValidTill($access_token_valid_till)
    {
        $this->access_token_valid_till = $access_token_valid_till;
    }

    public function getAccessTokenValidTill()
    {
        return $this->access_token_valid_till;
    }

    public function setIsDisabled($is_disabled)
    {
        $this->is_disabled = $is_disabled;
    }

    public function getIsDisabled()
    {
        return $this->is_disabled;
    }

    public function setCreated($created)
    {
        $this->created = $created;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setModified($modified)
    {
        $this->modified = $modified;
    }

    public function getModified()
    {
        return $this->modified;
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
                        'name' => 'username',
                        'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'email',
                        'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'password',
                        'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'password_hints',
                        'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'salt',
                        'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'email_check_code',
                        'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'password_access_tocken',
                        'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'access_token_valid_till',
                        'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'is_disabled',
                        'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'created',
                        'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'modified',
                        'required' => true,
            )));


            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
