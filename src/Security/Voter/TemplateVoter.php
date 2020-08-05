<?php

namespace App\Security\Voter;

use App\Entity\Module;
use App\Traits\SecurityTrait;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TemplateVoter extends Voter
{
    use SecurityTrait;

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [
            'TEMPLATE_LIST',
            'TEMPLATE_ADD',
            'TEMPLATE_EDIT',
            'TEMPLATE_DELETE'
        ]) && ($subject instanceof Module || $subject === null);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'TEMPLATE_LIST':
                return $this->isDeveloper();
                break;
            case 'TEMPLATE_ADD':
                return $this->isDeveloper();
                break;
            case 'TEMPLATE_EDIT':
                return $this->isDeveloper();
                break;
            case 'TEMPLATE_MANAGE':
                return $this->isDeveloper();
                break;
        }

        return false;
    }
}
