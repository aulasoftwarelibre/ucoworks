<?php

namespace App\Security\Voter;

use App\Entity\Student;
use App\Entity\Subject;
use App\Repository\SubjectRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SubjectVoter extends Voter
{
    /**
     * @var SubjectRepository
     */
    private $repository;

    public function __construct(SubjectRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['ENROLL', 'UNENROLL'])
            && $subject instanceof Subject;
    }

    /**
     * @param string $attribute
     * @param Subject $subject
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

        $found = $user->getSubjects()->filter(function (Subject $registeredSubject) use ($subject) {
            return $registeredSubject->getId() === $subject->getId();
        });

        switch ($attribute) {
            case 'ENROLL':
                return $found->isEmpty();
            case 'UNENROLL':
                return !$found->isEmpty();
        }

        return false;
    }
}
