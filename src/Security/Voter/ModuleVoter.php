<?php

namespace App\Security\Voter;

use App\Entity\Module;
use App\Traits\SecurityTrait;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ModuleVoter extends Voter
{
    use SecurityTrait;

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [
            'MODULE_ADD',
            'MODULE_EDIT',
            'MODULE_DELETE'
        ]) && ($subject instanceof Module || $subject === null);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'MODULE_ADD':
                return $this->isDeveloper();
                break;
            case 'MODULE_EDIT':
                return $this->isDeveloper();
                break;
            case 'MODULE_DELETE':
                return $this->isDeveloper();
                break;
        }

        return false;
    }
}
