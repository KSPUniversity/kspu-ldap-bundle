<?php

namespace Kspu\LdapBundle\Ldap;

use FR3D\LdapBundle\Ldap\LdapManager as BaseLdapManager;
use Kspu\LDAP\Entity\BaseUser;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class LdapManager extends BaseLdapManager {
    protected function hydrate(UserInterface $user, array $entry) {
        parent::hydrate($user, $entry);

        if(!$user instanceof BaseUser) return;

        try {
            // ищем пользователя в базе, чтобы добыть роли

            /** @var UserProviderInterface $userProvider */
            $userProvider = $this->userManager;
            /** @var UserInterface $dUser */
            $dUser = $userProvider->loadUserByUsername($user->getUsername());
            /** @var RoleInterface[] $roles */
            $roles = $dUser->getRoles();

            foreach($roles as $role)
                $user->addRole($role);
        } catch (UsernameNotFoundException $ex) {
            /**
             * пользователя нет в базе
             * значит нет и ролей
             */
        }
    }
}