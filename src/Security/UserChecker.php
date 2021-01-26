<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{


    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof User) return;

        if ($user->getStatus() == User::STATUS_ACTIF) return;

        if ($user->getStatus() == User::STATUS_DELETED) {
            throw new CustomUserMessageAuthenticationException(
                'Votre compte à été supprimé'
            );
        }
        elseif ($user->getStatus() == User::STATUS_UNVERIFIED) {
            throw new CustomUserMessageAuthenticationException(
                'Merci de confirmer via votre boite de réception'
            );
        }
        else{
            throw new CustomUserMessageAuthenticationException(
                'Erreur'
            );
        }
    }

    public function checkPostAuth(UserInterface $user)
    {

    }
}
