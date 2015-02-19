<?php

namespace Zf2auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class Zf2authAppController extends AbstractActionController
{

    protected $resourcesTable;
    protected $roleResourcesTable;
    protected $rolesTable;
    protected $userRolesTable;
    protected $usersTable;
    protected $routerConfig;
    protected $currentUser;
    public $storage;
    public $authservice;
    public $message;
    public $error = array();

    public function __construct()
    {
        $this->message = new \Zend\Config\Config(include __DIR__ . '../../../../config/message.config.php');
//        parent::__construct();
    }

    /**
     *
     * @return type
     */
    public function getSessionStorage()
    {
        if (!$this->storage) {
            $this->storage = $this->getServiceLocator()->get('Zf2auth\Model\Zf2AuthStorage');
        }

        return $this->storage;
    }

    /**
     *
     * @return type
     */
    public function getAuthService()
    {
        if (!$this->authservice) {
            $this->authservice = $this->getServiceLocator()->get('AuthService');
        }
        return $this->authservice;
    }

    /**
     * Get Current User
     * @return type
     */
    protected function getCurrentUser()
    {
        $this->currentUser = false;
        if ($this->getAuthService()->hasIdentity()) {
            $this->currentUser = $this->getAuthService()->getIdentity();
        }
        return $this->currentUser;
    }

    protected function getRouterConfig()
    {
        if (!$this->routerConfig) {
            $sm = $this->getServiceLocator();
            $this->routerConfig = $sm->get('RouterConfig');
        }
        return $this->routerConfig;
    }

    protected function getResourcesTable()
    {
        if (!$this->resourcesTable) {
            $sm = $this->getServiceLocator();
            $this->resourcesTable = $sm->get('Zf2auth\Table\ResourcesTable');
        }
        return $this->resourcesTable;
    }

    protected function getRoleResourcesTable()
    {
        if (!$this->roleResourcesTable) {
            $sm = $this->getServiceLocator();
            $this->roleResourcesTable = $sm->get('Zf2auth\Table\RoleResourcesTable');
        }
        return $this->roleResourcesTable;
    }

    protected function getRolesTable()
    {
        if (!$this->rolesTable) {
            $sm = $this->getServiceLocator();
            $this->rolesTable = $sm->get('Zf2auth\Table\RolesTable');
        }
        return $this->rolesTable;
    }

    protected function getUserRolesTable()
    {
        if (!$this->userRolesTable) {
            $sm = $this->getServiceLocator();
            $this->userRolesTable = $sm->get('Zf2auth\Table\UserRolesTable');
        }
        return $this->userRolesTable;
    }

    protected function getUsersTable()
    {
        if (!$this->usersTable) {
            $sm = $this->getServiceLocator();
            $this->usersTable = $sm->get('Zf2auth\Table\UsersTable');
        }
        return $this->usersTable;
    }

}
