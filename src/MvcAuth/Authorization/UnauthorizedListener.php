<?php
/**
 * Authogility
 *
 * @link        https://github.com/leogr/authogility
 * @copyright   Copyright (c) 2015, Ripa Club
 * @license     https://github.com/leogr/authogility/blob/develop/LICENSE
 */
namespace Authogility\MvcAuth\Authorization;

use Zend\Permissions\Acl\Role\RoleInterface as AclRoleInterface;
use Zend\Permissions\Rbac\RoleInterface as RbacRoleInterface;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\MvcAuth\MvcAuthEvent;
use Authogility\Identity\InvalidIdentity;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\Mvc\MvcEvent;

/**
 * Class UnauthorizedListener
 */
class UnauthorizedListener
{
    /**
     * Determine if we have an authorization failure, and, if so, return a 403 response
     *
     * @param MvcAuthEvent $mvcAuthEvent
     * @return null|ApiProblemResponse
     */
    public function __invoke(MvcAuthEvent $mvcAuthEvent)
    {
        if ($mvcAuthEvent->isAuthorized()) {
            return null;
        }

        $mvcEvent = $mvcAuthEvent->getMvcEvent();
        $mvcResponse = $mvcEvent->getResponse();

        $identity = $mvcAuthEvent->getIdentity();

        if ($identity instanceof InvalidIdentity) {
            $mvcEvent->setError($identity->getReasonMessage());
            if ($identity->getException()) {
                $mvcEvent->setParam('exception', $identity->getException());
            }

            // Dispatch error into Mvc flow if event manager is available
            if ($mvcEvent->getTarget() instanceof EventManagerAwareInterface) {
                $results = $mvcEvent->getTarget()->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $mvcEvent);
                if (count($results)) {
                    return $results->last();
                }
                return $mvcEvent->getParams();
            }
            // Else, return a 403 Api Problem
            $response = new ApiProblemResponse(new ApiProblem(403, sprintf('Invalid identity: %s', $identity->getReasonMessage())));
            $mvcEvent->stopPropagation(true);
            $mvcAuthEvent->stopPropagation(true);
            return $response;
        }

        if ($identity instanceof AclRoleInterface) {
            $role = $identity->getRoleId();
        } elseif ($identity instanceof RbacRoleInterface) {
            $role = $identity->getName();
        } elseif (is_string($identity)) {
            $role = $identity;
        } else {
            $role = 'unknown';
        }
        $role = ucfirst($role);

        $response = new ApiProblemResponse(new ApiProblem(403, sprintf('%s is not authorized', $role)));
        $mvcEvent->setResponse($response);
        return $response;
    }
}
