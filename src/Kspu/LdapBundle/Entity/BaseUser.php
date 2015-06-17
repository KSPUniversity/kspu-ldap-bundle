<?php
namespace Kspu\LdapBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Kspu\LdapBundle\Validator\LdapExists;
use Kspu\LdapBundle\Mappings\Ldap\Mapping as Ldap;
use Kspu\LDAP\Entity\BaseUser as KspuBaseUser;

/**
 * @ORM\MappedSuperclass
 */
abstract class BaseUser extends KspuBaseUser {
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=80, unique=true)
     * @Assert\NotBlank
     * @LdapExists
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=160, unique=true)
     * @Assert\Length(max=160)
     * @Ldap\Ldap(field="getDn")
     */
    protected $dn;

    /**
     * @var string
     * @ORM\Column(type="string", length=200)
     * @Assert\Length(max=200)
     * @Ldap\Ldap(field="getFio")
     */
    protected $fio;
}