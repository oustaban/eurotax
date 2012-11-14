<?php

namespace Application\Sonata\UserBundle\DataFixtures\ORM;

use Application\Sonata\UserBundle\DataFixtures\AbstractLoadListData;
use Application\Sonata\UserBundle\Entity\Group;

class LoadGroupData extends AbstractLoadListData
{
    /**
     * @var string
     */
    protected $_className = 'Group';

    protected $_lists = array(

        'Gestionnaire' => array(
            'ROLE_EDIT_DEVISES',
            'ROLE_EDIT_IMPOTS',
            'ROLE_EDIT_CLIENTS',
            'ROLE_EDIT_CLIENTOPERATIONS',
            'ROLE_CLIENT_TOOLS',
        ),

        'Superviseur' => array(
            'ROLE_EDIT_USERS',
            'ROLE_EDIT_DEVISES',
            'ROLE_EDIT_IMPOTS',
            'ROLE_EDIT_CLIENTS',
            'ROLE_EDIT_CLIENTOPERATIONS',
            'ROLE_CLIENT_TOOLS',
            'ROLE_ADD_CLIENTS',
            'ROLE_EDIT_ETATS',
        ),
    );

    /**
     * @param $manager
     */
    public function saveFixtures($manager)
    {
        $class = $this->getClass($manager);

        if ($this->_lists) {

            foreach ($this->_lists as $name => $roles) {
                /** @var $entity Group */
                $entity = $manager->getRepository($this->getRepositoryName())->findOneByName($name);

                if (!$entity) {
                    /** @var $entity Group */
                    $entity = new $class($name, $roles);
                    $manager->persist($entity);
                    $manager->flush();
                }
            }
        }
    }
}