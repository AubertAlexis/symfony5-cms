<?php

namespace App\Security\Voter;

use App\Entity\HomePage;
use App\Security\SecurityTrait;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class HomePageVoter extends Voter
{
    use SecurityTrait;

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [
            'HOME_PAGE_EDIT'
        ]) && ($subject instanceof HomePage || $subject === null);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'HOME_PAGE_EDIT':
                return $this->isAdminSecurity();
                break;
        }

        return false;
    }
}
