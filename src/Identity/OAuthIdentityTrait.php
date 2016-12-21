<?php
/**
 * Authogility
 *
 * @link        https://github.com/leogr/authogility
 * @copyright   Copyright (c) 2015-2016, The Authogility Project Authors
 * @license     https://github.com/leogr/authogility/blob/develop/LICENSE
 */
namespace Authogility\Identity;

trait OAuthIdentityTrait
{
    /**
     * @return string|NULL
     */
    public function getClientId()
    {
        if (isset($this->identity['client_id'])) {
            return $this->identity['client_id'];
        }
        return null;
    }

    /**
     * @return string|NULL
     */
    public function getScope()
    {
        if (isset($this->identity['scope'])) {
            return $this->identity['scope'];
        }
        return null;
    }
}
