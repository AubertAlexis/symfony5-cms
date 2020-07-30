<?php

namespace App\Security\Voter;

use App\Entity\Nav;
use App\Traits\SecurityTrait;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class NavVoter extends Voter
{

    use SecurityTrait;

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [
            'NAV_LIST',
            'NAV_MANAGE',
            'NAV_ADD',
            'NAV_EDIT',
            'NAV_DELETE'
        ]) && ($subject instanceof Nav || $subject === null);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'NAV_LIST':
                return $this->isAdminSecurity();
                break;
            case 'NAV_MANAGE':
                return $this->isAdminSecurity();
                break;
            case 'NAV_ADD':
                return $this->isAdminSecurity();
                break;
            case 'NAV_EDIT':
                return $this->isAdminSecurity();
                break;
            case 'NAV_DELETE':
                return $this->isAdminSecurity();
                break;
        }

        return false;
    }
}
