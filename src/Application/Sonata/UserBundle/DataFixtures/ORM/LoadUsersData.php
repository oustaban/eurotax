<?php

namespace Application\Sonata\UserBundle\DataFixtures\ORM;

use Application\Sonata\UserBundle\DataFixtures\AbstractLoadListData;
use Application\Sonata\UserBundle\Entity\User;

class LoadUsersData extends AbstractLoadListData
{
    /**
     * @var string
     */
    protected $_className = 'User';

    /**
     * @param $manager
     */
    public function saveFixtures($manager)
    {
        $class = $this->getClass($manager);

        /** @var $entity User */
        $entity = $manager->getRepository($this->getRepositoryName())->findOneById(User::SuperUserId);

        if (!$entity) {

            $username = 'admin';
            $password = '12345';
            $email = 'admin@example.com';

            /** @var $entity  User */
            $entity = new $class();

            $group = $manager->getRepository('ApplicationSonataUserBundle:Group')->find(User::GroupId);
            if ($group) {
                $entity->removeGroup($group);
            }

            $entity->setUsername($username);
            $entity->setUsernameCanonical($username);
            $entity->setEmail($email);
            $entity->setEmailCanonical($email);
            $entity->setEnabled(true);
            $entity->setSuperAdmin(true);
            $entity->setPlainPassword($password);

            $entity->setFirstname('Prénom');
            $entity->setLastname('Nom');

            $manager->persist($entity);
            $manager->flush();
        }
    }
}