<?php
namespace Kspu\LdapBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use FR3D\LdapBundle\Ldap\LdapManagerInterface;

class LdapExistsValidator extends ConstraintValidator {
    /**
     * @var LdapManagerInterface
     */
    private $ldapManager;

    function __construct(LdapManagerInterface $ldapManager) {
        $this->ldapManager = $ldapManager;
    }

    public function validate($value, Constraint $constraint) {
        if(!$constraint instanceof LdapExists)
            return;

        if(false === $this->ldapManager->findUserByUsername($value)) {
            $this->context->addViolation($constraint->message);
        }
    }
}