<?php
namespace Backend;

return array(
    'PpcAuth' => [
        'auth_controllers' => [
            'Backend\Controller\Index'
        ]
    ],
    'controllers' => array(
        'invokables' => array(
            'PpcAuth\Controller\Index' => 'PpcAuth\Controller\IndexController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'login' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/auth',
                    'defaults' => array(
                        '__NAMESPACE__' => 'PpcAuth\Controller',
                        'controller'    => 'Index',
                        'action'        => 'login',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'process' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:action]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'template_path_stack' => array(
            'ppc-auth' => __DIR__ . '/../view',
        ),
    ),
);
