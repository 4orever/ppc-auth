<?php
namespace PpcAuth;

use PpcAuth\Controller\IndexController;
use PpcAuth\Entity\AuthStorage;
use Zend\Mvc\Controller\ControllerManager;

return array(
    'PpcAuth' => [
        'auth_controllers' => [
            'PpcBackend\Controller\Index'
            // Перестает работать и фронтенд
//            ,
//            'kjsencha_direct'
        ]
    ],
    'controllers' => array(
        'invokables' => array(
            //            'PpcAuth\Controller\Index' => 'PpcAuth\Controller\IndexController',
        ),
        'factories' => [
            'PpcAuth\Controller\Index' => function ($container) {
                $storage = $container->get(AuthStorage::class);
                $authService = $container->get('AuthService');
                $controller = new IndexController($storage, $authService);
                return $controller;
            }
        ]
    ),
    'router' => array(
        'routes' => array(
            'login' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/auth',
                    'defaults' => array(
                        '__NAMESPACE__' => 'PpcAuth\Controller',
                        'controller' => 'Index',
                        'action' => 'login',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'process' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:action]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'ppc-auth' => __DIR__ . '/../view',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'memcached',
                'paths' => array(__DIR__ . '/../src/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),
);
