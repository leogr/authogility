<?php
/**
 * Authogility
 *
 * @link        https://github.com/leogr/authogility
 * @copyright   Copyright (c) 2015, Ripa Club
 * @license     https://github.com/leogr/authogility/blob/develop/LICENSE
 */
namespace Authogility\MvcAuth\Authorization;

use AclMan\Service\ServiceImplement as AclManager;
use ZF\MvcAuth\Identity\IdentityInterface;
use ZF\MvcAuth\MvcAuthEvent;

/**
 * Class DefaultAuthorizationListener
 */
class DefaultAuthorizationListener
{
    /**
     * @param MvcAuthEvent $mvcAuthEvent
     */
    public function __invoke(MvcAuthEvent $mvcAuthEvent)
    {
        $identity = $mvcAuthEvent->getIdentity();

        if (!$identity instanceof IdentityInterface) {
            return;
        }

        /* @var $mvcEvent \Zend\Mvc\MvcEvent */
        $mvcEvent = $mvcAuthEvent->getMvcEvent();
        $services = $mvcEvent->getApplication()->getServiceManager();

        /* @var $acl \Zend\Permissions\Acl\Acl */
        $acl = $services->get('authorization');
        $acl->deny(); // Deny by default for all roles

        /* @var $aclManager \AclMan\Service\Service */
        $aclManager = $services->get('Authogility\MvcAuth\Authorization\AclManager');

        $aclManager->setAcl($acl);
        $aclManager->loadResource($identity, $mvcAuthEvent->getResource());
    }
}
