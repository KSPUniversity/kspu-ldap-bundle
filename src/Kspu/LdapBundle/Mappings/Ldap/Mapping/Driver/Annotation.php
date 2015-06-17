<?php
namespace Kspu\LdapBundle\Mappings\Ldap\Mapping\Driver;

use Gedmo\Mapping\Driver;
use Doctrine\Common\Annotations\AnnotationReader;

class Annotation implements Driver {
    private $_originalDriver = null;
    /**
     * @param object $meta
     * @param array $config
     * @throws \Exception
     * @return void
     */
    public function readExtendedMetadata($meta, array &$config) {
        $reader = new AnnotationReader();

        $class = $meta->getReflectionClass();

        foreach ($class->getProperties() as $property) {
            // skip inherited properties
            if ($meta->isMappedSuperclass && !$property->isPrivate() ||
                $meta->isInheritedField($property->name) ||
                isset($meta->associationMappings[$property->name]['inherited'])
            ) {
                continue;
            }

            /** @var \Kspu\LdapBundle\Mappings\Ldap\Mapping\Ldap $ldap */
            if ($ldap = $reader->getPropertyAnnotation($property, 'Kspu\LdapBundle\Mappings\Ldap\Mapping\Ldap')) {
                $field = $property->getName();

                if (!$meta->hasField($field))
                    throw new \Exception("Field is not mapped as object property");

                if (empty($ldap->field)) {
                    throw new \Exception("No field specified");
                }

                $mapping = $meta->getFieldMapping($field);
                if ($mapping['type'] != 'string') {
                    throw new \Exception("Only strings are supported for ldap attributes");
                }

                $config['ldap'][$field] = ['field' => $ldap->field];
            }
        }
    }

    public function setOriginalDriver($driver) {
        $this->_originalDriver = $driver;
    }
}