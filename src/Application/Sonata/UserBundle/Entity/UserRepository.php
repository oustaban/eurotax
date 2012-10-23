<?php

namespace Application\Sonata\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\DBAL\LockMode;

class UserRepository extends EntityRepository
{
    /**
     * {@inheritdoc}
     */
    public function find($id, $lockMode = LockMode::NONE, $lockVersion = null)
    {
        /* @var $user User */
        $user = parent::find($id, $lockMode, $lockVersion);

        /* @var $securityContext SecurityContext */
        $securityContext = \AppKernel::getStaticContainer()->get('security.context');
        if ($user->getId() != 1 || $securityContext->isGranted('ROLE_SUPER_ADMIN')) {
            return $user;
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
        if (!$securityContext->isGranted('ROLE_SUPER_ADMIN')){
            $queryBuilder
                ->where($alias . '.id != :admin_id')
                ->setParameter(':admin_id', 1);
        }

        return $queryBuilder;
    }
}