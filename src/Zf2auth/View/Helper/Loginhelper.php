<?php

namespace Zf2auth\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zf2auth\Form\LoginForm;
use Zend\ServiceManager\ServiceManager;

class Loginhelper extends AbstractHelper
{

    protected $serviceLocator;
    protected $authService;

    public function __invoke($result_div_id = 'loginresults')
    {
        $this->authService = $this->serviceLocator->get('AuthService');
        if ($this->authService->hasIdentity()) {
            $userData = $this->serviceLocator->get('Zf2auth\Table\UsersTable')->getUsers($this->authService->getIdentity()->id);
            return $this->getView()->render('partial/login', array('getIdentity' => $this->authService->getIdentity(), 'userData' => $userData));
        } else {
            $form = new LoginForm();
            return $this->getView()->render('partial/login', array('form' => $form, 'result_div_id' => $result_div_id));
        }
    }

    public function setServiceLocator(ServiceManager $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

}
