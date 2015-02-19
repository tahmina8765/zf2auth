<?php

namespace Zf2auth\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zf2auth\Form\RegistrationForm;
use Zend\ServiceManager\ServiceManager;

class Registrationhelper extends AbstractHelper
{

    protected $serviceLocator;
    protected $authService;

    public function __invoke()
    {
        $this->authService = $this->serviceLocator->get('AuthService');

        if ($this->authService->hasIdentity()) {
            return $this->getView()->render('partial/registration', array('getIdentity' => $this->authService->getIdentity()));
        } else {
            $form = new RegistrationForm();
            return $this->getView()->render('partial/registration', array('form' => $form));
        }
    }

    public function setServiceLocator(ServiceManager $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

}
