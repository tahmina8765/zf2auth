<?php

namespace Zf2auth\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Session\Container;

class UtilHelper extends AbstractHelper
{

    protected $util;
    protected $role;
    protected $label;

    public function __construct()
    {
        $Zf2AuthStorage = new \Zf2auth\Model\Zf2AuthStorage;
        $this->role = $Zf2AuthStorage->getRole();
        $this->label = $Zf2AuthStorage->getRoleLabel();
    }

    public function displayStatus()
    {
        $labels = array('Admin', 'Support');
        return in_array($this->label, $labels);
    }

    public function hiddenElements()
    {
        $elements = array();
        switch ($this->label) {
            case 'Admin':

                break;
        }
        return $elements;
    }

    public function displayLink($link)
    {
        if('Administrator' == $this->role){
            return true;
        }
        $return = false;
        $container = new Container('system_init');
        $resources = $container->allowed_resources;
        $allowed_resources = empty($resources[$this->role]) ? array() : $resources[$this->role];
        if (in_array($link, $allowed_resources)) {
            $return = true;
        }
        return $return;
    }

    public function truncate($string, $length, $dots = "...")
    {
        return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
    }

}
