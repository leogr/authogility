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
use Zend\Permissions\Acl\Role\RoleInterface;

class UserIdentity extends AuthenticatedIdentity implements OAuthIdentityInterface
{

    use OAuthIdentityTrait;

    /**
     * @var UserEntityInterface
     */
    protected $user;

    /**
     * @param AuthenticatedIdentity $identity
     * @param UserEntityInterface $user
     */
    public function __construct(AuthenticatedIdentity $identity, UserEntityInterface $user)
    {
        $this->name     = $user instanceof RoleInterface ? $user->getRoleId() : $identity->getName();
        $this->identity = $identity->getAuthenticationIdentity();
        $this->user     = $user;
    }

    /**
     * @return UserEntityInterface
     */
    public function getUser()
    {
        return $this->user;
    }
}
