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
use Authogility\MvcAuth\Authorization\UnauthorizedListener;
use Authogility\MvcAuth\Authentication\IdentityPostAuthenticationListener;
use Authogility\Exception;
use Authogility\Identity\UserEntityServiceInterface;
use Authogility\Mvc\ResponseListener;

/**
 * Class Module
 */
class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{

    /**
     * @var bool
     */
    protected static $registerDefaultListeners = true;


    /**
     * @param bool $enable
     */
    public static function setRegisterDefaultListeners($enable = true)
    {
        static::$registerDefaultListeners = (bool) $enable;
    }


    /**
     * @param MvcEvent $e
     * @throws RuntimeException
     */
    public function onBootstrap(MvcEvent $e)
    {

        if (!static::$registerDefaultListeners) {
            return;
        }

        $serviceManager = $e->getApplication()->getServiceManager();
        $config = $serviceManager->get('Config');

        if (empty($config['authogility']['user_entity_service'])) {
            throw new Exception\RuntimeException(sprintf(
                '"user_entity_service" configuration node must be set'
            ));
        }

        $userEntityService = $serviceManager->get($config['authogility']['user_entity_service']);

        if (!$userEntityService instanceof UserEntityServiceInterface) {
            throw new Exception\RuntimeException(sprintf(
                '"user_entity_service" must be an instance of "%s", given "%s"',
                UserEntityServiceInterface::class,
                is_object($userEntityService) ? get_class($userEntityService) : gettype($userEntityService)
            ));
        }

        $events = $e->getApplication()->getEventManager();

        // Setup user identity
        // Attach with higher priority than ZF\MvcAuth\Module listeners
        $events->attach(MvcAuthEvent::EVENT_AUTHENTICATION_POST, new IdentityPostAuthenticationListener($userEntityService), 1000);

        // Setup user ACL
        $events->attach(MvcAuthEvent::EVENT_AUTHORIZATION, new DefaultAuthorizationListener, 999); // After resolver
        $events->attach(MvcAuthEvent::EVENT_AUTHORIZATION_POST, new UnauthorizedListener, 100);
        
        // Handling errors within the response, if any
        $events->attach(MvcEvent::EVENT_FINISH, new ResponseListener, 1000);
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