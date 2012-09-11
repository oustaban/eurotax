<?php

namespace Application\Sonata\ClientBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\DBAL\LockMode;

class ClientRepository extends EntityRepository
{
    /**
     * {@inheritdoc}
     */
    public function find($id, $lockMode = LockMode::NONE, $lockVersion = null)
    {
        /* @var $client Client */
        $client = parent::find($id, $lockMode, $lockVersion);

        /* @var $securityContext SecurityContext */
        $securityContext = \AppKernel::getStaticContainer()->get('security.context');
        if ($client && $client->getUser() == $securityContext->getToken()->getUser())
        {
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
        $queryBuilder
            ->where($alias . '.user = :user_id')
            ->setParameter(':user_id', $securityContext->getToken()->getUser())
        ;

        return $queryBuilder;
    }
}