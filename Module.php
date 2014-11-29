<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Backend for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace PpcAuth;

use PpcAuth\Model\AuthStorage;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\ModuleRouteListener;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface,
    ConfigProviderInterface
{
    public function init(ModuleManager $mm)
    {

    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
//        $moduleRouteListener = new ModuleRouteListener();
//        $moduleRouteListener->attach($eventManager);
//        $eventManager->getSharedManager()->attach('Backend\Controller\IndexController', MvcEvent::EVENT_DISPATCH,
//            array($this, 'checkAuthenticated'));
        $eventManager->attach('route', array($this, 'checkAuthenticated'));
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'PpcAuth\Model\AuthStorage' => function ($sm) {
                    return new AuthStorage('PpcAuth');
                },

                'AuthService' => function ($sm) {

                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter,
                        'PpcAuthUser', 'username', 'password', 'MD5(?)');

                    $authService = new AuthenticationService();
                    $authService->setAdapter($dbTableAuthAdapter);
                    $authService->setStorage($sm->get('PpcAuth\Model\AuthStorage'));

                    return $authService;
                },
            ),
        );
    }


    public function checkAuthenticated(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $auth = $serviceManager->get('AuthService');
        if (!$this->isOpenRequest($e)) {
            if (!$auth->hasIdentity()) {
                $e->getRouteMatch()
                    ->setParam('controller', 'PpcAuth\Controller\Index')
                    ->setParam('action', 'login');
            }
        }
    }

    public function isOpenRequest(MvcEvent $e)
    {
        $config = $this->getConfig();
        $controller = $e->getRouteMatch()->getParam('controller');
        if (!in_array($controller, $config['PpcAuth']['auth_controllers'])) {
            return true;
        }

        return false;
    }
}
