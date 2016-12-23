<?php
/**
 * Authogility
 *
 * @link        https://github.com/leogr/authogility
 * @copyright   Copyright (c) 2015-2016, The Authogility Project Authors
 * @license     https://github.com/leogr/authogility/blob/develop/LICENSE
 */
namespace Authogility\MvcAuth\Authentication;

use Authogility\Identity\ClientIdentity;
use Authogility\Identity\UserIdentity;
use Authogility\Identity\InvalidIdentity;
use Authogility\Identity\UserEntityServiceInterface;
use Authogility\Identity\UserEntityInterface;
use ZF\MvcAuth\Identity\AuthenticatedIdentity;
use ZF\MvcAuth\MvcAuthEvent;

/**
 * Class IdentityPostAuthenticationListener
 */
class IdentityPostAuthenticationListener
{

    /**
     * @var UserEntityServiceInterface
     */
    protected $userEntityService;


    public function __construct(UserEntityServiceInterface $userEntityService)
    {
        $this->userEntityService = $userEntityService;
    }


    /**
     * @param MvcAuthEvent $e
     */
    public function __invoke(MvcAuthEvent $e)
    {
        $identity = $e->getIdentity();
        if ($identity instanceof AuthenticatedIdentity) {
            $authenticationIdentity = $identity->getAuthenticationIdentity();
            // It is an user?
            if (!empty($authenticationIdentity['user_id'])) {
                try {
                    $user = $this->userEntityService->getUserByIdentity($authenticationIdentity['user_id']);
                    if ($user instanceof UserEntityInterface) {
                        $userIdentity = new UserIdentity($identity, $user);
                        $identity = $userIdentity;
                    } else {
                        $identity = new InvalidIdentity($authenticationIdentity);
                        $identity->setReasonMessage('User not found for provided identity');
                    }
                } catch (\Exception $ex) {
                    $identity = new InvalidIdentity($authenticationIdentity);
                    $identity->setException($ex);
                    $identity->setReasonMessage($ex->getMessage());
                }
            } elseif (!empty($authenticationIdentity['client_id'])) {
                $identity = new ClientIdentity($authenticationIdentity);
                $identity->setName($authenticationIdentity['client_id']);
            } else {
                $identity = new InvalidIdentity($authenticationIdentity);
                $identity->setReasonMessage('Unknown identity type');
            }

            $e->setIdentity($identity);
        }
    }
}
