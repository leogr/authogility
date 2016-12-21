<?php
/**
 * Authogility
 *
 * @link        https://github.com/leogr/authogility
 * @copyright   Copyright (c) 2015-2016, The Authogility Project Authors
 * @license     https://github.com/leogr/authogility/blob/develop/LICENSE
 */
namespace Authogility\MvcAuth\Authorization;

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

        // TODO: lazy load ACL per identity
    }
}
