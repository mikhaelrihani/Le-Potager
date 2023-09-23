<?php

namespace App\Security\Voter;

use App\Entity\Question;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class QuestionVoter extends Voter
{
    public const MODIFY = 'QUESTION_EDIT';
    public const ANSWER_VALIDATE = 'QUESTION_ANSWER_VALIDATE';

    private $security;
    public function __construct(Security $security){

        $this->security = $security;
        
    }

    protected function supports(string $attribute, $subject): bool
    {
        
        return in_array($attribute, [self::MODIFY, self::ANSWER_VALIDATE])
            && $subject instanceof Question;
    }

    protected function voteOnAttribute(string $attribute, $question, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // return true si on est user ou admin
        if($this->security->isGranted("ROLE_USER")) return true;
        if($this->security->isGranted("ROLE_ADMIN")) return true;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::MODIFY:
                
                return $this->isUser($question,$user);

                break;
            case self::ANSWER_VALIDATE:
            
                return $this->isUser($question,$user);

                break;
        }

        return false;
    }

    /**
     * permet de vérifier si l'utilisateur de la question est l'utilisateur connecté
     * @return bool
     */
    private function isUser(Question $question, User $user){

         // renvoi true si la question est bien celle de l'user
         return $user === $question->getUser();
    }
}