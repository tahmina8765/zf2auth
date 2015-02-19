<?php

namespace Zf2auth\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zf2auth\Form\LoginForm;
use Zend\ServiceManager\ServiceManager;

class Authenticatedhelper extends AbstractHelper
{

    protected $serviceLocator;
    protected $authService;

    public function __invoke()
    {
        $this->authService = $this->serviceLocator->get('AuthService');

        if ($this->authService->hasIdentity()) {
            $userData = $this->serviceLocator->get('Zf2auth\Table\UsersTable')->getUsers($this->authService->getIdentity()->id);
            return $this->getView()->render('partial/authenticated', array('getIdentity' => $this->authService->getIdentity(), 'userData' => $userData));
        } else {
            $form = new LoginForm();
            return $this->getView()->render('partial/authenticated', array('form' => $form));
        }
    }

    public function setServiceLocator(ServiceManager $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

}
