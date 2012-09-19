<?php

namespace Application\Sonata\ClientBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\DBAL\LockMode;

class ClientRepository extends EntityRepository
{
    /**
     * Finds all entities in the repository.
     *
     * @return array The entities.
     */
    public function findAll()
    {
        /* @var $securityContext SecurityContext */
        $securityContext = \AppKernel::getStaticContainer()->get('security.context');

        return $securityContext->isGranted('ROLE_EDIT_ALL_CLIENTS') ?
            parent::findAll() :
            $this->findBy(array('user' => $securityContext->getToken()->getUser()));
    }

    /**
     * {@inheritdoc}
     */
    public function find($id, $lockMode = LockMode::NONE, $lockVersion = null)
    {
        /* @var $client Client */
        $client = parent::find($id, $lockMode, $lockVersion);

        /* @var $securityContext SecurityContext */
        $securityContext = \AppKernel::getStaticContainer()->get('security.context');
        if ($securityContext->isGranted('ROLE_EDIT_ALL_CLIENTS') || ($client && $client->getUser() == $securityContext->getToken()->getUser())) {
            return $client;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function createQueryBuilder($alias)
    {
        $queryBuilder = parent::createQueryBuilder($alias);

        /* @var $securityContext SecurityContext */
        $securityContext = \AppKernel::getStaticContainer()->get('security.context');
        if (!$securityContext->isGranted('ROLE_EDIT_ALL_CLIENTS')){
            $queryBuilder
                ->where($alias . '.user = :user')
                ->setParameter(':user', $securityContext->getToken()->getUser());
        }

        return $queryBuilder;
    }
}