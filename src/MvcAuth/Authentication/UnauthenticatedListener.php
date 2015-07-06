<?php
/**
 * Authogility
 *
 * @link        https://github.com/leogr/authogility
 * @copyright   Copyright (c) 2015, Ripa Club
 * @license     https://github.com/leogr/authogility/blob/develop/LICENSE
 */
namespace Authogility\MvcAuth\Authentication;

use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\MvcAuth\MvcAuthEvent;

/**
 * Class UnauthenticatedListener
 */
class UnauthenticatedListener
{
    /**
     * Determine if we have an authentication failure, and, if so, return a 401 response
     *
     * @param MvcAuthEvent $mvcAuthEvent
     * @return null|ApiProblemResponse
     */
    public function __invoke(MvcAuthEvent $mvcAuthEvent)
    {
        if (!$mvcAuthEvent->hasAuthenticationResult()) {
            return null;
        }

        $authResult = $mvcAuthEvent->getAuthenticationResult();
        if ($authResult->isValid()) {
            return null;
        }

        $mvcEvent = $mvcAuthEvent->getMvcEvent();
        $response = new ApiProblemResponse(new ApiProblem(401, 'Unauthorized'));
        $mvcEvent->setResponse($response);
        return $response;
    }
}
