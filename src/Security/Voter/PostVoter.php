<?php

namespace App\Security\Voter;

use App\Entity\Post;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PostVoter extends Voter
{

    public const EDIT = 'edit';

    /**
     * @inheritdoc
     */
    public function supports(string $attribute, $subject)
    {
        if (!$subject instanceof Post) {
            return false;
        }

        if (!in_array($attribute, [self::EDIT])){
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param Post $subject
     * @param TokenInterface $token
     * @return bool|void
     */
    public function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        switch ($attribute) {
            case self::EDIT:
                return $user === $subject->getUser();
                break; 
        }
    }
}