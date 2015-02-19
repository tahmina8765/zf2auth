<?php

namespace Zf2auth;

return array(
    'controllers' => array(
        'invokables' => array(
            'Zf2auth\Controller\Zf2auth' => 'Zf2auth\Controller\Zf2authController',
            'Zf2auth\Controller\Resources' => 'Zf2auth\Controller\ResourcesController',
            'Zf2auth\Controller\RoleResources' => 'Zf2auth\Controller\RoleResourcesController',
            'Zf2auth\Controller\Roles' => 'Zf2auth\Controller\RolesController',
            'Zf2auth\Controller\UserRoles' => 'Zf2auth\Controller\UserRolesController',
            'Zf2auth\Controller\Users' => 'Zf2auth\Controller\UsersController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'zf2auth' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/zf2auth[/:action][/:id][/page/:page][/item_per_page/:item_per_page][/order_by/:order_by][/:order][/search_by/:search_by]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)(?!\border_by\b)(?!\bsearch_by\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'page' => '[0-9]+',
                        'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order' => 'ASC|DESC',
                    ),
                    'defaults' => array(
                        'controller' => 'Zf2auth\Controller\Zf2auth',
                        'action' => 'index',
                    ),
                ),
            ),
            'resources' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/resources',
                    'defaults' => array(
                        'controller' => 'Zf2auth\Controller\Resources',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'search' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/search[/:id][/page/:page][/item_per_page/:item_per_page][/order_by/:order_by][/:order][/search_by/:search_by]',
                            'constraints' => array(
                                'id' => '[0-9]+',
                                'page' => '[0-9]+',
                                'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'order' => 'ASC|DESC',
                            ),
                            'defaults' => array(
                                'action' => 'search',
                            ),
                        ),
                    ),
                    'index' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/index[/:id][/page/:page][/item_per_page/:item_per_page][/order_by/:order_by][/:order][/search_by/:search_by]',
                            'constraints' => array(
                                'id' => '[0-9]+',
                                'page' => '[0-9]+',
                                'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'order' => 'ASC|DESC',
                            ),
                            'defaults' => array(
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'add' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/add',
                            'defaults' => array(
                                'action' => 'add',
                            ),
                        ),
                    ),
                    'edit' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/edit[/:id]',
                            'defaults' => array(
                                'action' => 'edit',
                            ),
                        ),
                    ),
                    'delete' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/delete[/:id]',
                            'constraints' => array(
                                'id' => '[0-9]+',
                                'page' => '[0-9]+',
                                'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'order' => 'ASC|DESC',
                            ),
                            'defaults' => array(
                                'action' => 'delete',
                            ),
                        ),
                    ),
                    'reset' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/reset',
                            'defaults' => array(
                                'action' => 'Reset',
                            ),
                        ),
                    ),
                    'refresh_resources' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/refresh-resources',
                            'defaults' => array(
                                'action' => 'refreshResources',
                            ),
                        ),
                    ),
                    'bind_roles' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/bind-roles[/order_by/:order_by][/:order]',
                            'defaults' => array(
                                'action' => 'bindRoles',
                            ),
                        ),
                    ),
                ),
            ),
            'role_resources' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/role-resources',
                    'defaults' => array(
                        'controller' => 'Zf2auth\Controller\RoleResources',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'search' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/search[/:id][/page/:page][/item_per_page/:item_per_page][/order_by/:order_by][/:order][/search_by/:search_by]',
                            'constraints' => array(
                                'id' => '[0-9]+',
                                'page' => '[0-9]+',
                                'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'order' => 'ASC|DESC',
                            ),
                            'defaults' => array(
                                'action' => 'search',
                            ),
                        ),
                    ),
                    'index' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/index[/:id][/page/:page][/item_per_page/:item_per_page][/order_by/:order_by][/:order][/search_by/:search_by]',
                            'constraints' => array(
                                'id' => '[0-9]+',
                                'page' => '[0-9]+',
                                'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'order' => 'ASC|DESC',
                            ),
                            'defaults' => array(
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'add' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/add',
                            'defaults' => array(
                                'action' => 'add',
                            ),
                        ),
                    ),
                    'edit' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/edit[/:id]',
                            'defaults' => array(
                                'action' => 'edit',
                            ),
                        ),
                    ),
                    'delete' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/delete[/:id]',
                            'constraints' => array(
                                'id' => '[0-9]+',
                                'page' => '[0-9]+',
                                'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'order' => 'ASC|DESC',
                            ),
                            'defaults' => array(
                                'action' => 'delete',
                            ),
                        ),
                    ),
                ),
            ),
            'roles' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/roles',
                    'defaults' => array(
                        'controller' => 'Zf2auth\Controller\Roles',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'search' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/search[/:id][/page/:page][/item_per_page/:item_per_page][/order_by/:order_by][/:order][/search_by/:search_by]',
                            'constraints' => array(
                                'id' => '[0-9]+',
                                'page' => '[0-9]+',
                                'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'order' => 'ASC|DESC',
                            ),
                            'defaults' => array(
                                'action' => 'search',
                            ),
                        ),
                    ),
                    'index' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/index[/:id][/page/:page][/item_per_page/:item_per_page][/order_by/:order_by][/:order][/search_by/:search_by]',
                            'constraints' => array(
                                'id' => '[0-9]+',
                                'page' => '[0-9]+',
                                'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'order' => 'ASC|DESC',
                            ),
                            'defaults' => array(
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'add' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/add',
                            'defaults' => array(
                                'action' => 'add',
                            ),
                        ),
                    ),
                    'edit' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/edit[/:id]',
                            'defaults' => array(
                                'action' => 'edit',
                            ),
                        ),
                    ),
                    'delete' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/delete[/:id]',
                            'constraints' => array(
                                'id' => '[0-9]+',
                                'page' => '[0-9]+',
                                'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'order' => 'ASC|DESC',
                            ),
                            'defaults' => array(
                                'action' => 'delete',
                            ),
                        ),
                    ),
                ),
            ),
            'user_roles' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/user-roles',
                    'defaults' => array(
                        'controller' => 'Zf2auth\Controller\UserRoles',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'search' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/search[/:id][/page/:page][/item_per_page/:item_per_page][/order_by/:order_by][/:order][/search_by/:search_by]',
                            'constraints' => array(
                                'id' => '[0-9]+',
                                'page' => '[0-9]+',
                                'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'order' => 'ASC|DESC',
                            ),
                            'defaults' => array(
                                'action' => 'search',
                            ),
                        ),
                    ),
                    'index' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/index[/:id][/page/:page][/item_per_page/:item_per_page][/order_by/:order_by][/:order][/search_by/:search_by]',
                            'constraints' => array(
                                'id' => '[0-9]+',
                                'page' => '[0-9]+',
                                'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'order' => 'ASC|DESC',
                            ),
                            'defaults' => array(
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'add' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/add',
                            'defaults' => array(
                                'action' => 'add',
                            ),
                        ),
                    ),
                    'edit' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/edit[/:id]',
                            'defaults' => array(
                                'action' => 'edit',
                            ),
                        ),
                    ),
                    'delete' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/delete[/:id]',
                            'constraints' => array(
                                'id' => '[0-9]+',
                                'page' => '[0-9]+',
                                'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'order' => 'ASC|DESC',
                            ),
                            'defaults' => array(
                                'action' => 'delete',
                            ),
                        ),
                    ),
                ),
            ),
            'users' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/users',
                    'defaults' => array(
                        'controller' => 'Zf2auth\Controller\Users',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'search' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/search[/:id][/page/:page][/item_per_page/:item_per_page][/order_by/:order_by][/:order][/search_by/:search_by]',
                            'constraints' => array(
                                'id' => '[0-9]+',
                                'page' => '[0-9]+',
                                'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'order' => 'ASC|DESC',
                            ),
                            'defaults' => array(
                                'action' => 'search',
                            ),
                        ),
                    ),
                    'index' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/index[/:id][/page/:page][/item_per_page/:item_per_page][/order_by/:order_by][/:order][/search_by/:search_by]',
                            'constraints' => array(
                                'id' => '[0-9]+',
                                'page' => '[0-9]+',
                                'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'order' => 'ASC|DESC',
                            ),
                            'defaults' => array(
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'add' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/add',
                            'defaults' => array(
                                'action' => 'add',
                            ),
                        ),
                    ),
                    'edit' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/edit[/:id]',
                            'defaults' => array(
                                'action' => 'edit',
                            ),
                        ),
                    ),
                    'delete' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/delete[/:id]',
                            'constraints' => array(
                                'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'action' => 'delete',
                            ),
                        ),
                    ),
                    'registration' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/registration',
                            'defaults' => array(
                                'action' => 'registration',
                            ),
                        ),
                    ),
                    'login' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/login',
                            'defaults' => array(
                                'action' => 'login',
                            ),
                        ),
                    ),
                    'logout' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/logout',
                            'defaults' => array(
                                'action' => 'logout',
                            ),
                        ),
                    ),
                    'authenticate' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/authenticate',
                            'defaults' => array(
                                'action' => 'authenticate',
                            ),
                        ),
                    ),
                    'confirmEmail' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/confirmEmail[/:id][/:email_check_code]',
                            'defaults' => array(
                                'action' => 'confirmEmail',
                            ),
                        ),
                    ),
                    'forgot-password' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/forgot-password',
                            'defaults' => array(
                                'action' => 'forgetPassword',
                            ),
                        ),
                    ),
                    'reset-password' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/reset-password[/:id][/:password_access_tocken]',
                            'defaults' => array(
                                'action' => 'resetPassword',
                            ),
                        ),
                    ),
                    'change-password' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/change-password',
                            'defaults' => array(
                                'action' => 'changePassword',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'resources' => __DIR__ . '/../view',
            'role_resources' => __DIR__ . '/../view',
            'roles' => __DIR__ . '/../view',
            'user_roles' => __DIR__ . '/../view',
            'users' => __DIR__ . '/../view',
        ),
        'template_map' => array(
            'paginator-slide' => __DIR__ . '/../view/partial/paginator.phtml'
        ),
    ),
);
