<?php

namespace App\Security\Voter;

use App\Entity\Page;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PageVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [
            'PAGE_LIST',
            'PAGE_ADD',
            'PAGE_EDIT',
            'PAGE_DELETE'
        ]) && ($subject instanceof Page || $subject === null);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'PAGE_LIST':
                return in_array("ROLE_ADMIN", $user->getRoles());
                break;
            case 'PAGE_ADD':
                return in_array("ROLE_ADMIN", $user->getRoles());
                break;
            case 'PAGE_EDIT':
                return in_array("ROLE_ADMIN", $user->getRoles());
                break;
            case 'PAGE_DELETE':
                return in_array("ROLE_ADMIN", $user->getRoles());
                break;
        }

        return false;
    }
}
