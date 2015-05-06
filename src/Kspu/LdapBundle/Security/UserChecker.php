<?php
namespace Kspu\LdapBundle\Security;

use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface {
    public function checkPreAuth(UserInterface $user) {
    }

    public function checkPostAuth(UserInterface $user) {
        $roles = $user->getRoles();

        if (empty($roles)) {
            throw new BadCredentialsException;
        }
    }
}