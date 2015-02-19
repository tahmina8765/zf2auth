<?php

namespace Zf2auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class Zf2authController extends Zf2authAppController
{

    public $vm;

    function __construct()
    {
        parent::__construct();
        $this->vm = new viewModel();
    }

    public function indexAction()
    {
        
    }

}
