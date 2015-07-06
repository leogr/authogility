<?php
/**
 * Authogility
 *
 * @link        https://github.com/leogr/authogility
 * @copyright   Copyright (c) 2015, Ripa Club
 * @license     https://github.com/leogr/authogility/blob/develop/LICENSE
 */
namespace Authogility;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;
use ZF\MvcAuth\MvcAuthEvent;
use Authogility\MvcAuth\Authorization\DefaultAuthorizationListener;
use Authogility\MvcAuth\Authentication\UnauthenticatedListener;
use Authogility\MvcAuth\Authorization\UnauthorizedListener;
use Authogility\MvcAuth\Authentication\IdentityPostAuthenticationListener;
use Authogility\Identity\UserEntityInterface;
use Authogility\Exception;

/**
 * Class Module
 */
class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{


    /**
     * @param MvcEvent $e
     * @throws RuntimeException
     */
    public function onBootstrap(MvcEvent $e)
    {

        $serviceManager = $e->getApplication()->getServiceManager();
        $config = $serviceManager->get('Config');

        if (empty($config['authogility']['user_entity_service'])) {
            throw new Exception\RuntimeException(sprintf(
                '"user_entity_service" configuration node must be set'
            ));
        }

        $userEntityService = $serviceManager->get($config['authogility']['user_entity_service']);

        if (!$userEntityService instanceof UserEntityInterface) {
            throw new Exception\RuntimeException(sprintf(
                '"user_entity_service" must be an instance of "%s"',
                UserEntityInterface::class
            ));
        }

        // Setup user identity
        // Attach with high priority before anyone else in ZF\MvcAuth\Module
        $events->attach(MvcAuthEvent::EVENT_AUTHENTICATION_POST, new IdentityPostAuthenticationListener($userEntityService), 1000);

        // Setup user ACL
        $events->attach(MvcAuthEvent::EVENT_AUTHORIZATION, new DefaultAuthorizationListener, 999); // After resolver
        $events->attach(MvcAuthEvent::EVENT_AUTHENTICATION_POST, new UnauthenticatedListener, 100);
        $events->attach(MvcAuthEvent::EVENT_AUTHORIZATION_POST, new UnauthorizedListener, 100);
    }


    /**
     * {@inheritdoc}
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}