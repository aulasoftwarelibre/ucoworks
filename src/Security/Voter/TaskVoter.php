<?php

namespace App\Security\Voter;

use App\Entity\Student;
use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TaskVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['CHECK'], true)
            && $subject instanceof Task;
    }

    /**
     * @param string $attribute
     * @param Task $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof Student) {
            return false;
        }

        $found = $user->getTasks()->filter(function (Task $checkedTask) use ($subject) {
            return $checkedTask->getId() === $subject->getId();
        });

        switch ($attribute) {
            case 'CHECK':
                return $found->isEmpty();
                break;
        }

        return false;
    }
}
