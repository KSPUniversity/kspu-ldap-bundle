<?php
namespace Kspu\LdapBundle\Mappings\Ldap;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Gedmo\Mapping\MappedEventSubscriber;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LdapListener extends MappedEventSubscriber {
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container) {
        parent::__construct();
        $this->container = $container;
    }

    public function getSubscribedEvents() {
        return array(
            'onFlush',
            'loadClassMetadata'
        );
    }

    protected function getNamespace() {
        return __NAMESPACE__;
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $args) {
        // this will check for our metadata
        $this->loadMetadataForObjectClass(
            $args->getEntityManager(),
            $args->getClassMetadata()
        );
    }

    public function onFlush(OnFlushEventArgs $args) {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityUpdates() as $object) {
            $meta = $em->getClassMetadata(get_class($object));
            if ($config = $this->getConfiguration($em, $meta->name)) {
                $this->setProperty($em, $object, $config);
            }
        }

        foreach ($uow->getScheduledEntityInsertions() as $object) {
            $meta = $em->getClassMetadata(get_class($object));
            if ($config = $this->getConfiguration($em, $meta->name)) {
                $this->setProperty($em, $object, $config);
            }

            $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $object);
        }
    }

    private function setProperty(EntityManager $em, $object, $config) {
        $meta = $em->getClassMetadata(get_class($object));

        $ldapManager = $this->container->get('fr3d_ldap.ldap_manager');

        foreach ($config['ldap'] as $field => $options) {
            /** @noinspection PhpUndefinedMethodInspection */
            $username = $object->getUsername();
            $user = $ldapManager->findUserByUsername($username);
            if($user !== false) {
                $res = call_user_func([$user, $options['field']]);
                $meta->getReflectionProperty($field)->setValue($object, $res);
            }
        }

        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $object);
    }
}