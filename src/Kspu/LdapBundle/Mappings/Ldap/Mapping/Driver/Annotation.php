<?php
namespace Kspu\LdapBundle\Mappings\Ldap\Mapping\Driver;

use Gedmo\Mapping\Driver;
use Gedmo\Mapping\Driver\AbstractAnnotationDriver;
use Gedmo\Exception\InvalidMappingException;

class Annotation extends AbstractAnnotationDriver {
    const LDAP = 'Kspu\LdapBundle\Mappings\Ldap\Mapping\Ldap';

    protected $validTypes = array(
        'string',
        'text',
    );

    /**
     * {@inheritDoc}
     */
    public function readExtendedMetadata($meta, array &$config) {
        $class = $this->getMetaReflectionClass($meta);
        // property annotations
        foreach ($class->getProperties() as $property) {
            if ($meta->isMappedSuperclass && !$property->isPrivate() ||
                $meta->isInheritedField($property->name) ||
                isset($meta->associationMappings[$property->name]['inherited'])
            ) {
                continue;
            }

            /** @var \Kspu\LdapBundle\Mappings\Ldap\Mapping\Ldap $ldap */
            if ($ldap = $this->reader->getPropertyAnnotation($property, self::LDAP)) {
                $field = $property->getName();

                if (!$meta->hasField($field))
                    throw new InvalidMappingException("Field is not mapped as object property");

                if (empty($ldap->field)) {
                    throw new InvalidMappingException("No field specified");
                }

                if (!$this->isValidField($meta, $field)) {
                    throw new InvalidMappingException("Only strings are supported for ldap attributes");
                }

                $config['ldap'][$field] = ['field' => $ldap->field];
            }
        }
    }
}