<?php
namespace Kspu\LdapBundle\Mappings\Ldap\Mapping;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class Ldap extends Annotation {
    /** @var string @Required */
    public $field;
}