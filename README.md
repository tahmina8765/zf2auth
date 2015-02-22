Zf2auth
=======

A Zend Framework 2 User Authentication and role based authorization module, created by Tahmina Khatoon

### This Package is still not stable. Do not use it untill beta version released.

Installation
============

#### With composer

1. Add this project in your composer.json:

    ```json
    "require": {
        "tahmina8765/zf2auth": "dev-master"
    }
    ```

2. Now tell composer to download ZfcUser by running the command:

    ```bash
    $ php composer.phar update
    ```

#### Post installation

1. Enabling it in your `application.config.php`file.

    ```php
    <?php
    return array(
        'modules' => array(
            // ...
            'Zf2auth'
        ),
        // ...
    );
    ```

2. Then Import the SQL schema located in `./vendor/tahmina8765/zf2auth/data/schema.sql`.

3. Add the following in Application/Module.php (the main module which use to bootstrap the application)

    use Zend\Authentication\AuthenticationService;
    use Application\Table\AppTable;
    use Zend\Http\Response;
    use Zend\Session\Container;
    use Zend\Session\Config\SessionConfig;
    use Zend\Session\SessionManager;

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $this->initAcl($e);
        $eventManager->attach('route', array($this, 'checkAcl'));
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'handleError'));
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_RENDER_ERROR, array($this, 'handleError'));
    }

    public function initSession($config)
    {
        $sessionConfig = new SessionConfig();
        $sessionConfig->setOptions($config);
        $sessionManager = new SessionManager($sessionConfig);
        $sessionManager->start();
        Container::setDefaultManager($sessionManager);
    }

    public function initAcl(MvcEvent $e)
    {

        $acl = new \Zend\Permissions\Acl\Acl();
        $application = $e->getApplication();
        $services = $application->getServiceManager();

        $this->rolesTable = $services->get('Zf2auth\Table\RolesTable');
        $this->resourcesTable = $services->get('Zf2auth\Table\ResourcesTable');
        $this->roleResourcesTable = $services->get('Zf2auth\Table\RoleResourcesTable');


        $roles = $this->rolesTable->fetchAll();
        $resources = $this->resourcesTable->fetchAll();

        $allResources = array();
        foreach ($resources as $resource) {
            if (!empty($resource)) {
                $acl->addResource(new \Zend\Permissions\Acl\Resource\GenericResource($resource->name));
                $allResources[] = $resource->name;
            }
        }
        $allowed = array();
        foreach ($roles as $role) {
            $role_id = $role->id;
            $role_name = ($role->name);

            $role = new \Zend\Permissions\Acl\Role\GenericRole($role_name);
            $acl->addRole($role_name);

            $allowed[$role_name] = array();
            if ($role_name == 'Administrator') {
                $acl->allow($role_name);
                $allowed[$role_name] = $allResources;
            } else {
                $role_resources = $this->roleResourcesTable->getResourcesBasedOnRole($role_id);
                $allowd_resources = array();
                foreach ($role_resources as $row) {
                    if (!empty($row)) {
                        $allowd_resources[] = $row;
                        $acl->allow($role_name, $row->resource_name);
                        $allowed[$role_name][] = $row->resource_name;
                    }
                }
            }
        }
        // Set Allowed Resources In session
        $container = new Container('system_init');
        if (empty($container->allowed_resources)) {
            $container->allowed_resources = $allowed;
        }
        $e->getViewModel()->acl = $acl;
    }

    public function checkAcl(MvcEvent $e)
    {

        $route = $e->getRouteMatch()->getMatchedRouteName();
        $Zf2AuthStorage = new \Zf2auth\Model\Zf2AuthStorage;
        $userRole = $Zf2AuthStorage->getRole();

        if (!$e->getViewModel()->acl->hasResource($route) || !$e->getViewModel()->acl->isAllowed($userRole, $route)) {
//            echo $route;
//            die();
            $response = $e->getResponse();
//            location to page or what ever
//            $response->getHeaders()->addHeaderLine('Location', $e->getRequest()->getBaseUrl() . '/403');
//            $response->setStatusCode(303);
//            exit;


            if (!empty($_SESSION['zf2authSession'])) {

                $response->getHeaders()->addHeaderLine('Location', $e->getRequest()->getBaseUrl() . '/404');
                $response->setStatusCode(403);
                $response->sendHeaders();
            } else {
                $url = $e->getRouter()->assemble(array('controller' => 'users', 'action' => 'login'), array('name' => 'users/login'));
                $response->getHeaders()->addHeaderLine('Location', $url);
                $response->setStatusCode(302);
                $response->sendHeaders();
            }
            exit;
        }
    }

    public function authPreDispatch(MvcEvent $e)
    {

        //- assemble your own URL - this is just an example
        $url = $e->getRouter()->assemble(array('action' => 'login'), array('name' => 'frontend'));

        $response = $e->getResponse();
        $response->getHeaders()->addHeaderLine('Location', $url);
        $response->setStatusCode(302);
        $response->sendHeaders();
        exit;
    }

    public function handleError(MvcEvent $e)
    {
//        echo "here";
//        die();
        //get the exception
//        echo "<pre>";
//        print_r($e->getName());
//        print_r($e->getParams());
//        echo "</pre>";
//        die();
        $exception = $e->getParam('exception');

        //...handle the exception... maybe log it and redirect to another page,
        //or send an email that an exception occurred...
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'ZF2AuthService' => function($sm) {
                    $authService = new AuthenticationService();
                    $authService->setStorage($sm->get('Zf2auth\Model\Zf2AuthStorage'));
                    return $authService;
                },
                'Application\Table\AppTable' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $dbAdapter2 = $sm->get('db2');
                    $table = new CustomersTable($dbAdapter, $dbAdapter2);
                    return $table;
                },
            ),
        );
    }

    public function getSessionConfig()
    {
        $config = array();
//        $config = include __DIR__ . '/../../config/session.config.php';
        return $config;
    }