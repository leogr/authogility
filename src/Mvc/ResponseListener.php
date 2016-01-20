<?php
/**
 * Authogility
 *
 * @link        https://github.com/leogr/authogility
 * @copyright   Copyright (c) 2015, Ripa Club
 * @license     https://github.com/leogr/authogility/blob/develop/LICENSE
 */
namespace Authogility\Mvc;

use Zend\Mvc\MvcEvent;
use Zend\Http\Response;
use ZF\ApiProblem\ApiProblem;
use Zend\Http\Header\HeaderInterface;
use ZF\ApiProblem\ApiProblemResponse;

/**
 *
 */
class ResponseListener
{
    /**
     * @param MvcEvent $e
     */
    public function __invoke(MvcEvent $e)
    {
        $response = $e->getResponse();
        if ($response instanceof Response && in_array($response->getStatusCode(), [401, 403], true)) {
            
            // https://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10.4.2
            $wwwAuthHeader = $response->getHeaders()->get('WWW-Authenticate');
            if ($wwwAuthHeader instanceof \ArrayIterator) {
                $wwwAuthHeader = $wwwAuthHeader->current();
            }
            if ($wwwAuthHeader instanceof HeaderInterface) {
                $errorDescription = null;
                $wwwAuthHeader = explode(',', $wwwAuthHeader->getFieldValue());
                foreach ($wwwAuthHeader as $part) {
                    $part = explode('=', trim($part));
                    switch ($part[0]) {
                        case 'error_description':
                            $errorDescription = trim($part[1], '"');
                            break;
                    }
                }
                
                // Decorating error response with ApiProblem
                $apiProblem = new ApiProblem($response->getStatusCode(), $errorDescription);
                $apiProblemResponse = new ApiProblemResponse($apiProblem);
                $e->setResponse($apiProblemResponse);
            }
        }
    }
}