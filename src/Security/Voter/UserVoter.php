<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [
            'USER_PROFIL',
            'USER_ADMIN'
        ]) && $subject instanceof User;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'USER_ADMIN':
                return in_array("ROLE_ADMIN", $user->getRoles());
                break;
            case 'USER_PROFIL':
                return in_array("ROLE_ADMIN", $user->getRoles()) && $user == $subject;
                break;
        }

        return false;
    }
}
