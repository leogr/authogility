<?php
/**
 * Authogility
 *
 * @link        https://github.com/leogr/authogility
 * @copyright   Copyright (c) 2015-2016, The Authogility Project Authors
 * @license     https://github.com/leogr/authogility/blob/develop/LICENSE
 */
namespace Authogility\Identity;

interface UserEntityServiceInterface
{
    /**
     * @param mixed $identity
     * @return UserEntityInterface
     */
    public function getUserByIdentity($identity);
}
