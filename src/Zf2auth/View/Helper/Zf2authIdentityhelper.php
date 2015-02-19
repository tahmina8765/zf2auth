<?php

namespace Zf2auth\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceManager;

class Zf2authIdentityhelper extends AbstractHelper
{

    protected $serviceLocator;
    protected $authService;

    public function __invoke()
    {
        $this->authService = $this->serviceLocator->get('AuthService');
        if ($this->authService->hasIdentity()) {
            return $this->authService->getIdentity();
        } else {
            return false;
        }
    }

    public function setServiceLocator(ServiceManager $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

}
