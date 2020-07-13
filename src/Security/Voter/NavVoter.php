<?php

namespace App\Security\Voter;

use App\Entity\Nav;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class NavVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [
            'NAV_LIST',
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
                return in_array("ROLE_ADMIN", $user->getRoles());
                break;
            case 'NAV_ADD':
                return in_array("ROLE_ADMIN", $user->getRoles());
                break;
            case 'NAV_EDIT':
                return in_array("ROLE_ADMIN", $user->getRoles());
                break;
            case 'NAV_DELETE':
                return in_array("ROLE_ADMIN", $user->getRoles());
                break;
        }

        return false;
    }
}
