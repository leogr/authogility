<?php
/**
 * Authogility
 *
 * @link        https://github.com/leogr/authogility
 * @copyright   Copyright (c) 2015, Ripa Club
 * @license     https://github.com/leogr/authogility/blob/develop/LICENSE
 */
namespace Authogility\Identity;

use ZF\MvcAuth\Identity\AuthenticatedIdentity;
use Zend\Server\Method\Prototype;

class InvalidIdentity extends AuthenticatedIdentity implements OAuthIdentityInterface
{
    use OAuthIdentityTrait;

    protected $reasonMessage;

    protected $exception;

    public function getReasonMessage()
    {
        return $this->reasonMessage;
    }

    public function setReasonMessage($message)
    {
        $this->reasonMessage = (string) $message;
        return $this;
    }

    public function getException()
    {
        return $this->exception;
    }

    public function setException(\Exception $exception)
    {
        $this->exception = $exception;
        return $this;
    }
}
