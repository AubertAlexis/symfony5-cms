<?php

namespace App\Traits;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

trait SecurityTrait
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorageInterface;

    public function __construct(TokenStorageInterface $tokenStorageInterface)
    {
        $this->tokenStorageInterface = $tokenStorageInterface;
    }

    /**
     * If a user is allowed to access admin system
     *
     * @return boolean
     */
    public function isAdminSecurity(): bool
    {
        $roles = $this->tokenStorageInterface->getToken()->getUser()->getRoles();

        return in_array("ROLE_ADMIN", $roles) || in_array("ROLE_DEV", $roles);
    }

    /**
     * If a user is allowed to access dev system
     *
     * @return boolean
     */
    public function isDeveloper(): bool
    {
        $roles = $this->tokenStorageInterface->getToken()->getUser()->getRoles();

        return in_array("ROLE_DEV", $roles);
    }
}
