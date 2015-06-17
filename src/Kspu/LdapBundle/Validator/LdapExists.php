<?php
namespace Kspu\LdapBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class LdapExists extends Constraint {
    public $message = "User doesn't exist.";
    public $service = 'kspu.ldap.validator.ldap_exists';

    public function getTargets() {
        return self::PROPERTY_CONSTRAINT;
    }

    public function validatedBy() {
        return $this->service;
    }
}