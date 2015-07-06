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

class ClientIdentity extends AuthenticatedIdentity implements OAuthIdentityInterface
{
    use OAuthIdentityTrait;
}