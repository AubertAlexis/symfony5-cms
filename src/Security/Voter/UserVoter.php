<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Security\SecurityTrait;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    use SecurityTrait;

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
                return $this->isAdminSecurity();
                break;
            case 'USER_PROFIL':
                return $this->isAdminSecurity() && $user == $subject;
                break;
        }

        return false;
    }
}
