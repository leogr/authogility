<?php
/**
 * Authogility
 *
 * @link        https://github.com/leogr/authogility
 * @copyright   Copyright (c) 2015-2016, The Authogility Project Authors
 * @license     https://github.com/leogr/authogility/blob/develop/LICENSE
 */
namespace Authogility\Identity;

interface OAuthIdentityInterface
{
    /**
     * @return string|NULL
     */
    public function getClientId();

    /**
     * @return string|NULL
     */
    public function getScope();
}
