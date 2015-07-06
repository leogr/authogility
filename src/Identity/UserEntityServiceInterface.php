<?php
/**
 * Authogility
 *
 * @link        https://github.com/leogr/authogility
 * @copyright   Copyright (c) 2015, Ripa Club
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

